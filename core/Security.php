<?php
namespace Core;

/**
 * Security hardening: IP bans, whitelisting, event logging, middleware.
 *
 * Flujo:
 *   - Antes del router, public/index.php llama Security::enforceBans().
 *   - Si la IP esta en banned_ips y no vencio el ban, se responde 403 y se
 *     detiene la ejecucion.
 *   - Si la IP esta en ip_whitelist, jamas se banea (ni siquiera por fails
 *     de login repetidos).
 *   - Eventos criticos se loguean a security_events para auditoria.
 *
 * IPs:
 *   - getClientIp() considera (en orden) CF-Connecting-IP, X-Forwarded-For,
 *     REMOTE_ADDR. Preserva la IP real detras de Cloudflare/proxies.
 */
final class Security
{
    public const DEFAULT_BAN_HOURS = 24;
    public const LOGIN_FAIL_THRESHOLD = 3;   // 3 fails -> auto-ban
    public const LOGIN_WINDOW_MIN = 15;      // ventana para contar fails

    /**
     * Devuelve la IP real del cliente considerando proxies comunes.
     */
    public static function getClientIp(): string
    {
        // Cloudflare (mas confiable que X-Forwarded-For).
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Puede venir como "ip1, ip2, ip3" — tomamos la primera (el cliente real).
            $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }

        $ip = trim($ip);
        // Normalizar IPv6 localhost.
        if ($ip === '::1') { $ip = '127.0.0.1'; }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    /**
     * Valida formato de IP (v4 o v6). No considera rangos.
     */
    public static function isValidIp(string $ip): bool
    {
        return (bool)filter_var(trim($ip), FILTER_VALIDATE_IP);
    }

    /**
     * ¿Esta IP esta baneada y el ban aun vigente? Cachea por request.
     */
    public static function isBanned(string $ip): bool
    {
        if ($ip === '' || $ip === '0.0.0.0') { return false; }
        if (self::isWhitelisted($ip)) { return false; }

        $row = Database::instance()->fetch(
            'SELECT expires_at FROM banned_ips WHERE ip_address = :ip LIMIT 1',
            ['ip' => $ip]
        );
        if (!$row) { return false; }
        // expires_at NULL = permanente
        if ($row['expires_at'] === null) { return true; }
        // Si ya vencio, limpiar y retornar false
        if (strtotime($row['expires_at']) < time()) {
            Database::instance()->query(
                'DELETE FROM banned_ips WHERE ip_address = :ip',
                ['ip' => $ip]
            );
            return false;
        }
        return true;
    }

    public static function isWhitelisted(string $ip): bool
    {
        if ($ip === '') { return false; }
        $exists = Database::instance()->fetchColumn(
            'SELECT 1 FROM ip_whitelist WHERE ip_address = :ip LIMIT 1',
            ['ip' => $ip]
        );
        return (bool)$exists;
    }

    /**
     * Banea una IP. Si ya estaba baneada, actualiza reason y expires_at.
     *
     * @param int|null $durationHours null = permanente. Default 24h.
     * @param bool $auto true si fue automatico (RateLimiter), false si manual.
     */
    public static function ban(
        string $ip,
        string $reason,
        ?int $durationHours = self::DEFAULT_BAN_HOURS,
        ?int $byUserId = null,
        bool $auto = false,
        int $attemptCount = 0
    ): void {
        if (!self::isValidIp($ip)) { return; }
        if (self::isWhitelisted($ip)) { return; } // whitelist > ban

        $expiresAt = $durationHours === null
            ? null
            : date('Y-m-d H:i:s', time() + ($durationHours * 3600));

        Database::instance()->query(
            "INSERT INTO banned_ips (ip_address, reason, banned_by, auto_banned, expires_at, attempt_count)
             VALUES (:ip, :reason, :by, :auto, :exp, :attempts)
             ON DUPLICATE KEY UPDATE
                reason = VALUES(reason),
                banned_by = VALUES(banned_by),
                auto_banned = VALUES(auto_banned),
                banned_at = CURRENT_TIMESTAMP,
                expires_at = VALUES(expires_at),
                attempt_count = VALUES(attempt_count)",
            [
                'ip'       => $ip,
                'reason'   => mb_substr($reason, 0, 255),
                'by'       => $byUserId,
                'auto'     => $auto ? 1 : 0,
                'exp'      => $expiresAt,
                'attempts' => $attemptCount,
            ]
        );

        self::logEvent($auto ? 'auto_ban' : 'manual_ban', [
            'ip_address' => $ip,
            'user_id'    => $byUserId,
            'details'    => json_encode([
                'reason' => $reason,
                'expires_at' => $expiresAt,
                'attempt_count' => $attemptCount,
            ], JSON_UNESCAPED_UNICODE),
        ]);
    }

    public static function unban(string $ip, int $byUserId): bool
    {
        if (!self::isValidIp($ip)) { return false; }
        $affected = Database::instance()
            ->query('DELETE FROM banned_ips WHERE ip_address = :ip', ['ip' => $ip])
            ->rowCount();
        if ($affected > 0) {
            self::logEvent('unban', [
                'ip_address' => $ip,
                'user_id'    => $byUserId,
            ]);
            return true;
        }
        return false;
    }

    public static function addToWhitelist(string $ip, ?string $note, int $byUserId): bool
    {
        if (!self::isValidIp($ip)) { return false; }
        Database::instance()->query(
            "INSERT INTO ip_whitelist (ip_address, note, added_by) VALUES (:ip, :note, :by)
             ON DUPLICATE KEY UPDATE note = VALUES(note), added_by = VALUES(added_by)",
            ['ip' => $ip, 'note' => $note, 'by' => $byUserId]
        );
        // Si estaba baneada, remover.
        Database::instance()->query('DELETE FROM banned_ips WHERE ip_address = :ip', ['ip' => $ip]);
        self::logEvent('whitelist_add', [
            'ip_address' => $ip,
            'user_id'    => $byUserId,
            'details'    => json_encode(['note' => $note]),
        ]);
        return true;
    }

    public static function removeFromWhitelist(string $ip, int $byUserId): bool
    {
        if (!self::isValidIp($ip)) { return false; }
        $affected = Database::instance()
            ->query('DELETE FROM ip_whitelist WHERE ip_address = :ip', ['ip' => $ip])
            ->rowCount();
        if ($affected > 0) {
            self::logEvent('whitelist_remove', [
                'ip_address' => $ip,
                'user_id'    => $byUserId,
            ]);
            return true;
        }
        return false;
    }

    /**
     * Registra un evento de seguridad. No bloquea si falla la escritura.
     *
     * @param array{ip_address?:string|null,user_id?:int|null,email?:string|null,path?:string|null,details?:string|null} $data
     */
    public static function logEvent(string $type, array $data = []): void
    {
        try {
            Database::instance()->insert('security_events', [
                'event_type' => $type,
                'ip_address' => $data['ip_address'] ?? self::getClientIp(),
                'user_id'    => $data['user_id'] ?? null,
                'email'      => isset($data['email'])
                    ? mb_substr(strtolower(trim($data['email'])), 0, 191)
                    : null,
                'user_agent' => mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500) ?: null,
                'path'       => mb_substr((string)($_SERVER['REQUEST_URI'] ?? ''), 0, 500) ?: null,
                'details'    => $data['details'] ?? null,
            ]);
        } catch (\Throwable $e) {
            error_log('[referedmkt][security] logEvent failed: ' . $e->getMessage());
        }
    }

    /**
     * Middleware: detener la ejecucion si la IP actual esta baneada.
     * Llamado desde public/index.php ANTES del router.
     */
    public static function enforceBans(): void
    {
        $ip = self::getClientIp();
        if (!self::isBanned($ip)) { return; }

        // Log del bloqueo (solo 1 por sesion de ban, no spamear logs)
        self::logEvent('blocked_request', [
            'ip_address' => $ip,
            'details'    => json_encode([
                'reason' => 'IP banned',
                'uri'    => $_SERVER['REQUEST_URI'] ?? '',
            ]),
        ]);

        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        header('Retry-After: 3600'); // sugiere 1h al cliente (bots razonables lo respetan)
        echo "403 Forbidden\n\nTu IP ha sido bloqueada por actividad sospechosa.\n";
        echo "Si crees que es un error, contacta al administrador del sitio.\n";
        exit;
    }

    /**
     * Limpia registros viejos. Ejecutar via cron o desde admin.
     * Borra: bans expirados, login_attempts > 24h, security_events > 90 dias.
     */
    public static function gc(): void
    {
        $db = Database::instance();
        $db->query("DELETE FROM banned_ips WHERE expires_at IS NOT NULL AND expires_at < NOW()");
        $db->query("DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 24 HOUR)");
        $db->query("DELETE FROM security_events WHERE created_at < (NOW() - INTERVAL 90 DAY)");
    }
}
