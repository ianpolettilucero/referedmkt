<?php
namespace Core;

/**
 * Rate limiter del login + auto-ban por abuso.
 *
 * Reglas:
 *   - 3 intentos fallidos por IP en 15 minutos -> auto-ban de la IP por 24h.
 *   - Max MAX_PER_EMAIL fails por email en 15 min (lock temporal del user).
 *   - Un login exitoso limpia el contador de ese email.
 *   - Whitelist (ip_whitelist) NUNCA es baneada aunque dispare el threshold.
 *
 * Double tracking:
 *   - login_attempts (hash + email) para contabilidad y ventana temporal.
 *   - banned_ips (IP plana) para bloqueo en middleware y visibilidad admin.
 */
final class RateLimiter
{
    public const MAX_PER_IP    = Security::LOGIN_FAIL_THRESHOLD; // 3
    public const MAX_PER_EMAIL = 10;
    public const WINDOW_MIN    = Security::LOGIN_WINDOW_MIN;     // 15
    public const BAN_HOURS     = Security::DEFAULT_BAN_HOURS;    // 24

    public static function ipHash(): string
    {
        $ip = Security::getClientIp();
        $salt = getenv('APP_SALT') ?: 'change-me-in-env';
        return hash('sha256', $ip . '|login|' . $salt);
    }

    /**
     * Chequea si este email+IP pueden intentar login. True = OK, False = bloqueado.
     */
    public static function check(string $email): bool
    {
        // Si ya esta baneado a nivel IP, ni siquiera pregunta.
        $ip = Security::getClientIp();
        if (Security::isBanned($ip)) {
            return false;
        }

        $db = Database::instance();
        $window = self::WINDOW_MIN;

        $ipFails = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM login_attempts
             WHERE ip_hash = :ip AND successful = 0
               AND attempted_at >= (NOW() - INTERVAL $window MINUTE)",
            ['ip' => self::ipHash()]
        );
        if ($ipFails >= self::MAX_PER_IP) {
            return false;
        }

        if ($email !== '') {
            $emailFails = (int)$db->fetchColumn(
                "SELECT COUNT(*) FROM login_attempts
                 WHERE email = :e AND successful = 0
                   AND attempted_at >= (NOW() - INTERVAL $window MINUTE)",
                ['e' => strtolower($email)]
            );
            if ($emailFails >= self::MAX_PER_EMAIL) {
                return false;
            }
        }

        return true;
    }

    /**
     * Registra el intento. Si fue fallo y alcanzo el threshold, auto-banea la IP.
     */
    public static function record(string $email, bool $successful): void
    {
        $ip = Security::getClientIp();

        Database::instance()->insert('login_attempts', [
            'ip_hash'    => self::ipHash(),
            'email'      => $email !== '' ? strtolower($email) : null,
            'successful' => $successful ? 1 : 0,
        ]);

        if ($successful) {
            // Limpiar fails previos para este email.
            Database::instance()->query(
                'DELETE FROM login_attempts WHERE email = :e AND successful = 0',
                ['e' => strtolower($email)]
            );
            Security::logEvent('login_success', ['email' => $email, 'ip_address' => $ip]);
            return;
        }

        // Fallo: log el evento.
        Security::logEvent('login_fail', ['email' => $email, 'ip_address' => $ip]);

        // Auto-ban si este IP supero el threshold en la ventana.
        if (Security::isWhitelisted($ip)) {
            return; // IP en whitelist, no banear aunque falle mil veces.
        }

        $window = self::WINDOW_MIN;
        $ipFails = (int)Database::instance()->fetchColumn(
            "SELECT COUNT(*) FROM login_attempts
             WHERE ip_hash = :ip AND successful = 0
               AND attempted_at >= (NOW() - INTERVAL $window MINUTE)",
            ['ip' => self::ipHash()]
        );

        if ($ipFails >= self::MAX_PER_IP) {
            Security::ban(
                $ip,
                sprintf('Auto-ban: %d intentos de login fallidos en %d minutos', $ipFails, $window),
                self::BAN_HOURS,
                null,    // automatico
                true,    // auto
                $ipFails
            );
        }
    }

    /**
     * GC optional: borrar intentos > 24h. Llamable desde cron o probabilistico.
     */
    public static function maybeGC(): void
    {
        if (random_int(0, 100) !== 0) { return; }
        Database::instance()->query(
            "DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 24 HOUR)"
        );
    }
}
