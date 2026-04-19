<?php
namespace Core;

/**
 * Autenticacion admin via tabla users (bcrypt).
 */
final class Auth
{
    private const SESSION_KEY = '_auth_user_id';

    /** @var array<string, mixed>|null */
    private static ?array $cache = null;

    public static function attempt(string $email, string $password): bool
    {
        $user = Database::instance()->fetch(
            'SELECT * FROM users WHERE email = :e AND active = 1 LIMIT 1',
            ['e' => strtolower(trim($email))]
        );
        if (!$user) {
            return false;
        }
        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        if (password_needs_rehash($user['password_hash'], PASSWORD_BCRYPT, ['cost' => 12])) {
            Database::instance()->query(
                'UPDATE users SET password_hash = :h WHERE id = :id',
                ['h' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]), 'id' => $user['id']]
            );
        }

        Database::instance()->query(
            'UPDATE users SET last_login_at = NOW() WHERE id = :id',
            ['id' => $user['id']]
        );

        Session::start();
        Session::regenerate();
        Session::set(self::SESSION_KEY, (int)$user['id']);
        Csrf::rotate();

        self::$cache = $user;
        return true;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function user(): ?array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }
        Session::start();
        $id = Session::get(self::SESSION_KEY);
        if (!$id) {
            return null;
        }
        $row = Database::instance()->fetch(
            'SELECT * FROM users WHERE id = :id AND active = 1 LIMIT 1',
            ['id' => (int)$id]
        );
        if (!$row) {
            self::logout();
            return null;
        }
        return self::$cache = $row;
    }

    public static function logout(): void
    {
        self::$cache = null;
        Session::destroy();
    }

    public static function isSuperadmin(): bool
    {
        $u = self::user();
        return $u !== null && ($u['role'] ?? '') === 'superadmin';
    }

    /**
     * @return int[] IDs de sitios a los que el usuario tiene acceso. superadmin = todos.
     */
    public static function accessibleSiteIds(): array
    {
        $u = self::user();
        if (!$u) { return []; }
        if (($u['role'] ?? '') === 'superadmin') {
            return array_map(
                fn($s) => (int)$s['id'],
                Database::instance()->fetchAll('SELECT id FROM sites WHERE active = 1')
            );
        }
        return array_map(
            fn($r) => (int)$r['site_id'],
            Database::instance()->fetchAll(
                'SELECT site_id FROM user_site_access WHERE user_id = :uid',
                ['uid' => (int)$u['id']]
            )
        );
    }

    public static function canAccessSite(int $siteId): bool
    {
        return in_array($siteId, self::accessibleSiteIds(), true);
    }
}
