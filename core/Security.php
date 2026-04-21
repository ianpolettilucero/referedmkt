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
     * Enmascara una IP para display en UI (oculta octetos/grupos intermedios).
     * Preserva el primer y ultimo octeto para identificacion gruesa pero evita
     * exponer la IP completa en un screenshot accidental.
     *
     * IPv4 "190.123.45.67" -> "190.***.***.67"
     * IPv6 "2001:db8:1:2:3:4:5:6" -> "2001:db8:****:****:****:****:5:6"
     */
    public static function maskIp(string $ip): string
    {
        if ($ip === '') { return ''; }
        if (strpos($ip, ':') !== false) {
            // IPv6: preservar primeros 2 y ultimos 2 grupos
            $parts = explode(':', $ip);
            if (count($parts) < 4) { return $ip; }
            return $parts[0] . ':' . $parts[1] . ':****:****:'
                . $parts[count($parts) - 2] . ':' . $parts[count($parts) - 1];
        }
        // IPv4
        $parts = explode('.', $ip);
        if (count($parts) !== 4) { return $ip; }
        return $parts[0] . '.***.***.' . $parts[3];
    }

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
     * Defensivo: si la tabla no existe (migracion 005 no corrida), retorna false.
     */
    public static function isBanned(string $ip): bool
    {
        if ($ip === '' || $ip === '0.0.0.0') { return false; }
        try {
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
        } catch (\Throwable $e) {
            // Tabla no existe / DB error -> no bloquear. Logueamos al error log.
            error_log('[referedmkt][security] isBanned falló: ' . $e->getMessage());
            return false;
        }
    }

    public static function isWhitelisted(string $ip): bool
    {
        if ($ip === '') { return false; }
        try {
            $exists = Database::instance()->fetchColumn(
                'SELECT 1 FROM ip_whitelist WHERE ip_address = :ip LIMIT 1',
                ['ip' => $ip]
            );
            return (bool)$exists;
        } catch (\Throwable $e) {
            error_log('[referedmkt][security] isWhitelisted falló: ' . $e->getMessage());
            return false;
        }
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
        try {
            if (self::isWhitelisted($ip)) { return; }

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
        } catch (\Throwable $e) {
            error_log('[referedmkt][security] ban() falló: ' . $e->getMessage());
        }
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
     *
     * Defensivo: cualquier error interno (DB down, tabla faltante, etc.)
     * NO bloquea el request. Fail open — privilegia disponibilidad del
     * sitio sobre la proteccion opcional de bans.
     */
    public static function enforceBans(): void
    {
        try {
            // Assets estaticos: no aplicamos ban. Apache casi siempre los sirve
            // directo sin pasar por index.php (por el .htaccess), pero por las
            // dudas skipeamos aca tambien para no bloquear CSS/JS de la pagina
            // de error que el user baneado igual tiene que poder ver.
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
            if (preg_match('#^/(theme-assets|admin-assets|uploads|favicon\.ico|robots\.txt)#', $path)) {
                return;
            }

            $ip = self::getClientIp();
            if (!self::isBanned($ip)) { return; }

            // Log del bloqueo
            self::logEvent('blocked_request', [
                'ip_address' => $ip,
                'details'    => json_encode([
                    'reason' => 'IP banned',
                    'uri'    => $_SERVER['REQUEST_URI'] ?? '',
                ]),
            ]);

            http_response_code(403);
            header('Content-Type: text/plain; charset=utf-8');
            header('Retry-After: 3600');
            echo "403 Forbidden\n\nTu IP ha sido bloqueada por actividad sospechosa.\n";
            echo "Si crees que es un error, contacta al administrador del sitio.\n";
            exit;
        } catch (\Throwable $e) {
            error_log('[referedmkt][security] enforceBans falló: ' . $e->getMessage());
            // Fail open: continua la ejecucion normal sin bloquear.
        }
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
