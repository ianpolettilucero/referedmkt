<?php
namespace Core;

/**
 * Rate limiter de login basado en tabla login_attempts.
 *
 * Reglas:
 *   - Max 5 intentos fallidos por (ip_hash) en 15 minutos.
 *   - Max 10 intentos fallidos por (email) en 15 minutos (lock del user).
 *   - Un login exitoso limpia el contador para ese ip+email.
 *
 * El ip_hash usa APP_SALT para no guardar IP plana (consistencia con clicks).
 */
final class RateLimiter
{
    public const MAX_PER_IP    = 5;
    public const MAX_PER_EMAIL = 10;
    public const WINDOW_MIN    = 15;

    public static function ipHash(): string
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = trim(explode(',', (string)$ip)[0]);
        $salt = getenv('APP_SALT') ?: 'change-me-in-env';
        return hash('sha256', $ip . '|login|' . $salt);
    }

    public static function check(string $email): bool
    {
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

    public static function record(string $email, bool $successful): void
    {
        Database::instance()->insert('login_attempts', [
            'ip_hash'    => self::ipHash(),
            'email'      => $email !== '' ? strtolower($email) : null,
            'successful' => $successful ? 1 : 0,
        ]);

        if ($successful) {
            // Limpiar fails previos para este email (ya demostro que sabe el password).
            Database::instance()->query(
                'DELETE FROM login_attempts WHERE email = :e AND successful = 0',
                ['e' => strtolower($email)]
            );
        }
    }

    /**
     * GC optional: borrar intentos > 24h. Se puede llamar desde cron o desde el propio
     * record() con probabilidad baja.
     */
    public static function maybeGC(): void
    {
        if (random_int(0, 100) !== 0) { return; }
        Database::instance()->query(
            "DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 24 HOUR)"
        );
    }
}
