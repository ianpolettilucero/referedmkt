<?php
namespace Models;

use Core\Content;

/**
 * Modelo base. Lee del store de contenido (content/ compilado), no de una DB.
 *
 * Convenciones que se conservan de la version con MySQL:
 *   - Los metodos siguen recibiendo $siteId aunque hoy haya un solo sitio.
 *     Evita tocar todas las llamadas y deja la puerta abierta a multi-sitio.
 *   - Devuelven arrays asociativos con la misma forma que devolvia PDO, para
 *     que controllers y vistas no cambien.
 *   - Los campos que antes salian de un JOIN (author_name, category_slug…)
 *     vienen denormalizados desde el build.
 */
abstract class Model
{
    /** Nombre de la coleccion en Content: articles, products, categories… */
    protected const COLLECTION = '';

    /**
     * Todas las filas de la coleccion, indexadas por slug.
     *
     * @return array<string, array<string, mixed>>
     */
    protected static function rows(): array
    {
        return match (static::COLLECTION) {
            'articles'   => Content::articles(),
            'products'   => Content::products(),
            'categories' => Content::categories(),
            'authors'    => Content::authors(),
            default      => [],
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findById(int $id, ?int $siteId = null): ?array
    {
        return Content::byId(static::rows(), $id);
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findBySlug(int $siteId, string $slug): ?array
    {
        return static::rows()[$slug] ?? null;
    }

    /**
     * Ordena una lista de filas por una lista de campos.
     * Cada campo es [clave, direccion] con direccion 'asc' | 'desc'.
     * Los null van siempre al final, sea cual sea la direccion.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param array<int, array{0:string, 1:string}> $by
     * @return array<int, array<string, mixed>>
     */
    protected static function sort(array $rows, array $by): array
    {
        usort($rows, static function (array $x, array $y) use ($by): int {
            foreach ($by as [$key, $dir]) {
                $a = $x[$key] ?? null;
                $b = $y[$key] ?? null;

                if ($a === null && $b === null) { continue; }
                if ($a === null) { return 1; }
                if ($b === null) { return -1; }

                $cmp = is_numeric($a) && is_numeric($b)
                    ? ($a <=> $b)
                    : strcasecmp((string)$a, (string)$b);

                if ($cmp !== 0) {
                    return $dir === 'desc' ? -$cmp : $cmp;
                }
            }
            return 0;
        });
        return $rows;
    }
}
