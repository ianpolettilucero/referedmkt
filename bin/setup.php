<?php
/**
 * Setup todo-en-uno. Corré esto UNA sola vez después de subir el código a
 * Hostinger y crear el .env.
 *
 * Uso:
 *   php bin/setup.php
 *
 * Hace:
 *   1. Verifica .env y conexion a DB.
 *   2. Corre todas las migraciones pendientes.
 *   3. Si no hay usuarios admin, crea uno (interactivo).
 *   4. Si el unico sitio es el demo del seed, te pide datos del sitio real y lo registra.
 *   5. Imprime un resumen con las URLs que deberian funcionar.
 *
 * Idempotente: correrlo dos veces no rompe nada; solo hace los pasos que faltan.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente. Correlo via SSH o el Terminal web de Hostinger.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Database;

echo "\n\033[1m=== referedmkt setup ===\033[0m\n\n";

// 1. .env ---------------------------------------------------------------------
$envFile = APP_ROOT . '/.env';
if (!is_readable($envFile)) {
    fwrite(STDERR, "[ERROR] No existe .env en " . $envFile . "\n");
    fwrite(STDERR, "        Copialo de .env.example y completa credenciales de DB y APP_SALT.\n");
    exit(1);
}
echo "[1/5] .env encontrado en $envFile\n";

// 2. Conexion DB --------------------------------------------------------------
try {
    $one = (int)Database::instance()->fetchColumn('SELECT 1');
    if ($one !== 1) { throw new RuntimeException('SELECT 1 no devolvio 1'); }
    echo "[2/5] Conexion a DB OK\n";
} catch (Throwable $e) {
    fwrite(STDERR, "[ERROR] No se pudo conectar a DB: " . $e->getMessage() . "\n");
    fwrite(STDERR, "        Revisar DB_HOST/PORT/NAME/USER/PASS en .env\n");
    exit(1);
}

// 3. Migraciones --------------------------------------------------------------
echo "[3/5] Aplicando migraciones pendientes...\n";
$rc = 0;
passthru('php ' . escapeshellarg(APP_ROOT . '/migrate.php'), $rc);
if ($rc !== 0) {
    fwrite(STDERR, "[ERROR] Migraciones fallaron. Abortando.\n");
    exit(1);
}

// 4. Admin user ---------------------------------------------------------------
echo "\n[4/5] Usuarios admin\n";
$users = (int)Database::instance()->fetchColumn('SELECT COUNT(*) FROM users');
if ($users > 0) {
    echo "      Ya hay $users usuario(s) registrado(s). Salteo.\n";
} else {
    echo "      No hay usuarios. Vamos a crear el primer superadmin.\n";
    $email = prompt('      Email: ');
    while (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = prompt('      Email invalido. Probá de nuevo: ');
    }
    $name = prompt('      Nombre completo: ');
    while (trim($name) === '') {
        $name = prompt('      Nombre no puede estar vacio: ');
    }

    do {
        $pass1 = promptPassword('      Password (min 12 chars): ');
        $pass2 = promptPassword('      Repetir password: ');
        if ($pass1 !== $pass2) {
            echo "      Los passwords no coinciden.\n";
            continue;
        }
        if (strlen($pass1) < 12) {
            echo "      Password muy corto (min 12).\n";
            continue;
        }
        break;
    } while (true);

    Database::instance()->insert('users', [
        'email'         => strtolower(trim($email)),
        'password_hash' => password_hash($pass1, PASSWORD_BCRYPT, ['cost' => 12]),
        'name'          => trim($name),
        'role'          => 'superadmin',
        'active'        => 1,
    ]);
    echo "      Admin creado OK.\n";
}

// 5. Primer sitio -------------------------------------------------------------
echo "\n[5/5] Sitio principal\n";
$sites = Database::instance()->fetchAll("SELECT id, domain, slug, name FROM sites WHERE active = 1");
$demoOnly = count($sites) === 1 && $sites[0]['domain'] === 'demo.localhost';
$hasReal  = false;
foreach ($sites as $s) {
    if ($s['domain'] !== 'demo.localhost') { $hasReal = true; break; }
}

if ($hasReal) {
    echo "      Ya hay sitio(s) real(es) registrado(s):\n";
    foreach ($sites as $s) {
        if ($s['domain'] !== 'demo.localhost') {
            echo "        - {$s['name']} ({$s['domain']})\n";
        }
    }
    echo "      Salteo.\n";
} else {
    echo "      Configurar tu primer sitio.\n";
    $domain = strtolower(trim(prompt('      Dominio (ej: atalaya.lat, sin https:// ni www): ')));
    while ($domain === '' || strpos($domain, '/') !== false || strpos($domain, ' ') !== false) {
        $domain = strtolower(trim(prompt('      Dominio invalido: ')));
    }
    $name = trim(prompt('      Nombre del sitio (ej: Atalaya): '));
    $slug = trim(prompt('      Slug interno (default: derivado del nombre) [enter para auto]: '));
    if ($slug === '') { $slug = slugify($name); }

    if ($demoOnly) {
        // Reutilizar el row demo en lugar de crear otro (evita conflicto de slug).
        Database::instance()->query(
            'UPDATE sites SET domain = :d, name = :n, slug = :s WHERE id = :id',
            ['d' => $domain, 'n' => $name, 's' => $slug, 'id' => $sites[0]['id']]
        );
        echo "      Sitio demo reconvertido a $domain.\n";
    } else {
        $id = Database::instance()->insert('sites', [
            'domain'                    => $domain,
            'name'                      => $name,
            'slug'                      => $slug,
            'theme_name'                => 'default',
            'default_language'          => 'es',
            'default_country'           => 'AR',
            'affiliate_disclosure_text' => 'Divulgación: este sitio contiene enlaces de afiliados. Podemos recibir una comisión si compras a través de nuestros enlaces, sin costo adicional para vos.',
            'active'                    => 1,
        ]);
        echo "      Sitio creado (id=$id).\n";
    }
}

// Resumen ---------------------------------------------------------------------
$realSite = Database::instance()->fetch(
    "SELECT * FROM sites WHERE active = 1 AND domain <> 'demo.localhost' ORDER BY id LIMIT 1"
);
echo "\n\033[32m=== Setup completo ===\033[0m\n";
if ($realSite) {
    echo "URLs de tu sitio (una vez que el DNS apunte a Hostinger + SSL activo):\n";
    echo "  Home         https://{$realSite['domain']}/\n";
    echo "  Admin        https://{$realSite['domain']}/admin/login\n";
    echo "  Health check https://{$realSite['domain']}/healthz\n";
    echo "  Sitemap      https://{$realSite['domain']}/sitemap.xml\n";
    echo "  RSS          https://{$realSite['domain']}/feed.xml\n";
}
echo "\nSiguiente paso:\n";
echo "  - Login al admin con tu email + password.\n";
echo "  - Cargar productos y publicar articulos.\n";
echo "  - Configurar cron de backup (ver README).\n\n";

// Helpers ---------------------------------------------------------------------
function prompt(string $label): string
{
    fwrite(STDOUT, $label);
    $v = fgets(STDIN);
    return $v === false ? '' : rtrim($v, "\r\n");
}

function promptPassword(string $label): string
{
    fwrite(STDOUT, $label);
    if (stripos(PHP_OS_FAMILY, 'WIN') === 0) {
        $pass = trim((string)fgets(STDIN));
    } else {
        @system('stty -echo');
        $pass = trim((string)fgets(STDIN));
        @system('stty echo');
        fwrite(STDOUT, "\n");
    }
    return $pass;
}
