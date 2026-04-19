<?php
/**
 * Migration runner CLI.
 *
 * Uso:
 *   php migrate.php            # aplica migraciones pendientes en orden alfabetico
 *   php migrate.php --status   # muestra estado (aplicadas vs pendientes)
 *
 * Las migraciones viven en /migrations/*.sql.
 * La tabla `migrations` registra los filenames aplicados.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo corre desde CLI.\n");
}

require __DIR__ . '/core/bootstrap.php';

use Core\Database;

$db = Database::instance();

$dir = __DIR__ . '/migrations';
$files = glob($dir . '/*.sql') ?: [];
sort($files);

// Asegurar que existe la tabla migrations (la primera migracion la crea, pero por si corre parcial).
$db->pdo()->exec(
    "CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        filename VARCHAR(191) NOT NULL,
        applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uq_migrations_filename (filename)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci"
);

$applied = array_column(
    $db->fetchAll('SELECT filename FROM migrations'),
    'filename'
);
$appliedSet = array_flip($applied);

if (in_array('--status', $argv, true)) {
    echo "Applied:\n";
    foreach ($applied as $f) {
        echo "  [x] $f\n";
    }
    echo "Pending:\n";
    foreach ($files as $full) {
        $name = basename($full);
        if (!isset($appliedSet[$name])) {
            echo "  [ ] $name\n";
        }
    }
    exit(0);
}

$ran = 0;
foreach ($files as $full) {
    $name = basename($full);
    if (isset($appliedSet[$name])) {
        continue;
    }
    echo "Applying $name ... ";
    $sql = file_get_contents($full);
    try {
        $db->pdo()->exec($sql);
        // La 001 ya inserta su propia fila; para las siguientes, registrar aca.
        $exists = $db->fetchColumn(
            'SELECT 1 FROM migrations WHERE filename = :f LIMIT 1',
            ['f' => $name]
        );
        if (!$exists) {
            $db->insert('migrations', ['filename' => $name]);
        }
        echo "OK\n";
        $ran++;
    } catch (\Throwable $e) {
        echo "FAIL\n  " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo $ran === 0 ? "Nothing to migrate.\n" : "Applied $ran migration(s).\n";
