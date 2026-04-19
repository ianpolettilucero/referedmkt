<?php
namespace Core;

/**
 * Motor de migraciones. Usado por CLI (migrate.php), installer web
 * (public/install.php) y admin (botón "Aplicar migraciones pendientes").
 */
final class Migrator
{
    public const MIGRATIONS_DIR = '/migrations';

    /**
     * Crea la tabla migrations si no existe.
     */
    public static function ensureTable(): void
    {
        Database::instance()->pdo()->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                filename VARCHAR(191) NOT NULL,
                applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uq_migrations_filename (filename)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"
        );
    }

    /**
     * Lista de filenames ya aplicados (ordenados).
     * @return string[]
     */
    public static function applied(): array
    {
        self::ensureTable();
        return array_column(
            Database::instance()->fetchAll('SELECT filename FROM migrations ORDER BY filename'),
            'filename'
        );
    }

    /**
     * Archivos presentes en /migrations ordenados.
     * @return string[] filenames (sin path)
     */
    public static function available(): array
    {
        $files = glob(APP_ROOT . self::MIGRATIONS_DIR . '/*.sql') ?: [];
        sort($files);
        return array_map('basename', $files);
    }

    /**
     * @return string[] filenames pendientes
     */
    public static function pending(): array
    {
        $applied = array_flip(self::applied());
        return array_values(array_filter(
            self::available(),
            fn($f) => !isset($applied[$f])
        ));
    }

    /**
     * Aplica todas las migraciones pendientes en orden. Lockea la tabla via
     * GET_LOCK de MySQL para evitar corridas concurrentes.
     *
     * @return array{applied: string[], log: string} filenames aplicadas y log textual
     * @throws \RuntimeException si alguna falla
     */
    public static function runPending(): array
    {
        $db = Database::instance();
        $lockName = 'refmkt_migrations';
        $gotLock = (int)$db->fetchColumn("SELECT GET_LOCK(:n, 5)", ['n' => $lockName]);
        if ($gotLock !== 1) {
            throw new \RuntimeException('Otra corrida de migraciones esta en curso; probá de nuevo en 10s.');
        }
        try {
            $applied = [];
            $log = '';
            foreach (self::pending() as $name) {
                $log .= "Applying $name ... ";
                $sql = file_get_contents(APP_ROOT . self::MIGRATIONS_DIR . '/' . $name);
                $db->pdo()->exec($sql);
                // La 001 hace su propio INSERT INTO migrations; para el resto
                // (o si 001 no lo hizo), registrar aca.
                $exists = $db->fetchColumn(
                    'SELECT 1 FROM migrations WHERE filename = :f LIMIT 1',
                    ['f' => $name]
                );
                if (!$exists) {
                    $db->insert('migrations', ['filename' => $name]);
                }
                $log .= "OK\n";
                $applied[] = $name;
            }
            return ['applied' => $applied, 'log' => $log];
        } finally {
            $db->query("SELECT RELEASE_LOCK(:n)", ['n' => $lockName]);
        }
    }
}
