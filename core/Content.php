<?php
namespace Core;

/**
 * Store de contenido. Reemplaza a MySQL como fuente de verdad.
 *
 * El contenido vive en content/ como Markdown con front-matter. ContentBuilder
 * lo compila a var/content.php: un unico array PHP con todo ya resuelto
 * (joins denormalizados, Markdown ya renderizado a HTML). En runtime esto es
 * un `require` de un archivo que opcache mantiene en memoria.
 *
 * Auto-healing: en cada request se compara el fingerprint del cache contra el
 * de content/ (path + mtime + size de cada archivo). Si no coinciden, se
 * recompila y se reescribe. Cuesta ~1ms y hace imposible servir contenido
 * viejo despues de un deploy — no hace falta cron ni build step.
 *
 * La escritura del cache es atomica (tmp + rename), asi que dos requests
 * concurrentes que recompilen a la vez nunca dejan un archivo a medio escribir.
 */
final class Content
{
    public const CONTENT_DIR = '/content';
    public const CACHE_FILE  = '/var/content.php';

    /** @var array<string, mixed>|null */
    private static ?array $data = null;

    /**
     * @return array<string, mixed>
     */
    public static function load(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $cachePath = APP_ROOT . self::CACHE_FILE;
        $fingerprint = self::fingerprint();

        if (is_readable($cachePath)) {
            $cached = @include $cachePath;
            if (is_array($cached) && ($cached['fingerprint'] ?? null) === $fingerprint) {
                self::$data = $cached;
                return self::$data;
            }
        }

        $data = ContentBuilder::build();
        $data['fingerprint'] = $fingerprint;
        self::write($cachePath, $data);

        self::$data = $data;
        return self::$data;
    }

    /**
     * Huella del arbol content/: path + mtime + size de cada archivo.
     * Cambia ante cualquier alta, baja o edicion.
     */
    public static function fingerprint(): string
    {
        $dir = APP_ROOT . self::CONTENT_DIR;
        if (!is_dir($dir)) {
            return 'empty';
        }

        $parts = [];
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            if (!$file->isFile()) { continue; }
            $parts[] = $file->getPathname() . ':' . $file->getMTime() . ':' . $file->getSize();
        }
        sort($parts);

        return md5(implode('|', $parts));
    }

    /**
     * Escritura atomica: escribimos a un temporal en el mismo directorio y
     * renombramos. rename() es atomico dentro del mismo filesystem.
     */
    private static function write(string $path, array $data): void
    {
        $dir = dirname($path);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return; // sin cache escribible seguimos igual: se recompila cada request
        }

        $php = "<?php\n// Generado por bin/build-content.php — NO editar a mano.\nreturn "
             . var_export($data, true) . ";\n";

        $tmp = $path . '.' . bin2hex(random_bytes(4)) . '.tmp';
        if (@file_put_contents($tmp, $php, LOCK_EX) === false) {
            return;
        }
        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            return;
        }
        if (function_exists('opcache_invalidate')) {
            @opcache_invalidate($path, true);
        }
    }

    /** Fuerza recarga en el proximo load(). Util en tests y en el CLI. */
    public static function reset(): void
    {
        self::$data = null;
    }

    /**
     * Inyecta datos ya compilados sin tocar disco. Solo para tests.
     * @param array<string, mixed> $data
     */
    public static function seed(array $data): void
    {
        self::$data = $data;
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /** @return array<string, mixed> */
    public static function site(): array
    {
        return self::load()['site'] ?? [];
    }

    /** @return array<string, array<string, mixed>> slug => row */
    public static function articles(): array
    {
        return self::load()['articles'] ?? [];
    }

    /** @return array<string, array<string, mixed>> slug => row */
    public static function products(): array
    {
        return self::load()['products'] ?? [];
    }

    /** @return array<string, array<string, mixed>> slug => row */
    public static function categories(): array
    {
        return self::load()['categories'] ?? [];
    }

    /** @return array<string, array<string, mixed>> slug => row */
    public static function authors(): array
    {
        return self::load()['authors'] ?? [];
    }

    /** @return array<string, array<string, mixed>> tracking_slug => row */
    public static function affiliateLinks(): array
    {
        return self::load()['affiliate_links'] ?? [];
    }

    /** @return array<int, array<string, mixed>> */
    public static function redirects(): array
    {
        return self::load()['redirects'] ?? [];
    }

    // -------------------------------------------------------------------------
    // Helpers de consulta compartidos por los modelos
    // -------------------------------------------------------------------------

    /**
     * Articulos publicados y con fecha ya cumplida (los programados a futuro
     * quedan fuera hasta que llega su momento), ordenados por published_at DESC.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function publishedArticles(): array
    {
        $now = date('Y-m-d H:i:s');
        $out = [];
        foreach (self::articles() as $a) {
            if (($a['status'] ?? '') !== 'published') { continue; }
            if (!empty($a['published_at']) && $a['published_at'] > $now) { continue; }
            $out[] = $a;
        }
        usort($out, fn($x, $y) => strcmp((string)($y['published_at'] ?? ''), (string)($x['published_at'] ?? '')));
        return $out;
    }

    /**
     * Busca una fila por su id entero en una coleccion indexada por slug.
     *
     * @param array<string, array<string, mixed>> $rows
     * @return array<string, mixed>|null
     */
    public static function byId(array $rows, ?int $id): ?array
    {
        if (!$id) { return null; }
        foreach ($rows as $row) {
            if ((int)($row['id'] ?? 0) === $id) { return $row; }
        }
        return null;
    }
}
