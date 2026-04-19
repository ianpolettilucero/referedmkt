<?php
namespace Admin;

use Core\Auth;
use Core\Database;
use Core\Session;

/**
 * Contexto de admin: cual es el "sitio activo" elegido por el operador (stored
 * en session), y que sitios tiene visibles segun su rol.
 */
final class Context
{
    private const KEY = '_admin_active_site_id';

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function visibleSites(): array
    {
        $ids = Auth::accessibleSiteIds();
        if (!$ids) {
            return [];
        }
        $ph = implode(',', array_fill(0, count($ids), '?'));
        return Database::instance()->query(
            "SELECT * FROM sites WHERE id IN ($ph) ORDER BY name ASC",
            $ids
        )->fetchAll();
    }

    public static function setActiveSiteId(int $id): bool
    {
        if (!Auth::canAccessSite($id)) {
            return false;
        }
        Session::start();
        Session::set(self::KEY, $id);
        return true;
    }

    public static function activeSiteId(): ?int
    {
        Session::start();
        $id = Session::get(self::KEY);
        if ($id && Auth::canAccessSite((int)$id)) {
            return (int)$id;
        }
        // Autoseleccion: si el user tiene un solo sitio accesible, ese.
        $ids = Auth::accessibleSiteIds();
        if (count($ids) === 1) {
            Session::set(self::KEY, $ids[0]);
            return $ids[0];
        }
        return null;
    }

    /**
     * @return array<string, mixed>|null fila de sites
     */
    public static function activeSite(): ?array
    {
        $id = self::activeSiteId();
        if (!$id) { return null; }
        return Database::instance()->fetch(
            'SELECT * FROM sites WHERE id = :id LIMIT 1',
            ['id' => $id]
        );
    }

    public static function requireActiveSite(): array
    {
        $s = self::activeSite();
        if (!$s) {
            header('Location: /admin/sites', true, 302);
            exit;
        }
        return $s;
    }
}
