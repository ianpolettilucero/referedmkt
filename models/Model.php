<?php
namespace Models;

use Core\Database;

/**
 * Modelo base tenant-scoped.
 *
 * Convenciones:
 *   - Cada modelo declara const TABLE y opcionalmente const TENANT_SCOPED = true.
 *   - Si es tenant-scoped, todas las queries filtran por site_id obligatoriamente.
 *   - Sin query builder: metodos explicitos, prepared statements siempre.
 *   - JSON columns se decodifican automaticamente en hydrate() via lista estatica.
 */
abstract class Model
{
    protected const TABLE = '';
    protected const TENANT_SCOPED = true;

    /** @var string[] Columnas JSON que se decodifican a array en fetch. */
    protected const JSON_COLUMNS = [];

    protected static function db(): Database
    {
        return Database::instance();
    }

    /**
     * Post-procesa una fila: decodifica columnas JSON declaradas.
     * @param array<string, mixed>|null $row
     * @return array<string, mixed>|null
     */
    protected static function hydrate(?array $row): ?array
    {
        if ($row === null) {
            return null;
        }
        foreach (static::JSON_COLUMNS as $col) {
            if (isset($row[$col]) && is_string($row[$col])) {
                $decoded = json_decode($row[$col], true);
                $row[$col] = $decoded ?? null;
            }
        }
        return $row;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    protected static function hydrateAll(array $rows): array
    {
        foreach ($rows as &$row) {
            $row = self::hydrate($row);
        }
        return $rows;
    }

    public static function findById(int $id, ?int $siteId = null): ?array
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE id = :id', static::TABLE);
        $params = ['id' => $id];
        if (static::TENANT_SCOPED) {
            if ($siteId === null) {
                throw new \InvalidArgumentException(static::class . ' es tenant-scoped y requiere $siteId.');
            }
            $sql .= ' AND site_id = :site';
            $params['site'] = $siteId;
        }
        $sql .= ' LIMIT 1';
        return self::hydrate(self::db()->fetch($sql, $params));
    }

    public static function findBySlug(int $siteId, string $slug): ?array
    {
        if (!static::TENANT_SCOPED) {
            throw new \LogicException(static::class . ' no es tenant-scoped; usar otra clave.');
        }
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE site_id = :site AND slug = :slug LIMIT 1',
            static::TABLE
        );
        return self::hydrate(self::db()->fetch($sql, ['site' => $siteId, 'slug' => $slug]));
    }
}
