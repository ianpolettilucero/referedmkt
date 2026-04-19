<?php
/**
 * Instalador web. Se accede como https://tudominio.com/install.php
 *
 * Flujo auto-guiado:
 *   1. Crear .env (si falta) con creds DB + APP_SALT random.
 *   2. Correr migraciones pendientes.
 *   3. Crear primer usuario admin.
 *   4. Registrar primer sitio real.
 *   5. Crear .installed para bloquear re-acceso.
 *
 * Idempotente: cada request recalcula el estado y te muestra el paso que falte.
 */

define('APP_ROOT', dirname(__DIR__));

// Una vez instalado, el archivo se auto-bloquea.
$installedFlag = APP_ROOT . '/.installed';
if (is_file($installedFlag)) {
    http_response_code(404);
    exit('Installer ya ejecutado. Si necesitás reinstalar, eliminá el archivo .installed del servidor.');
}

require APP_ROOT . '/core/Autoloader.php';
\Core\Autoloader::register();
\Core\Autoloader::addNamespace('Core', APP_ROOT . '/core');
require APP_ROOT . '/core/helpers/functions.php';
require APP_ROOT . '/core/helpers/slug.php';

// --- Detectar estado ---------------------------------------------------------
$envFile = APP_ROOT . '/.env';
$state = 'env'; // env | migrate | admin | site | done
$errors = [];
$notices = [];
$envConfig = null;

if (is_file($envFile)) {
    $envConfig = parseEnv($envFile);
    // Intentar conectar
    try {
        $pdo = connectDb($envConfig);
        $state = 'migrate';
    } catch (Throwable $e) {
        $errors[] = 'No se puede conectar a la DB con las credenciales del .env actual: ' . $e->getMessage();
        $state = 'env';
    }
}

// --- Procesar POST -----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $step = $_POST['__step'] ?? '';

    if ($step === 'env') {
        $host = trim($_POST['db_host'] ?? 'localhost');
        $port = (int)($_POST['db_port'] ?? 3306);
        $name = trim($_POST['db_name'] ?? '');
        $user = trim($_POST['db_user'] ?? '');
        $pass = (string)($_POST['db_pass'] ?? '');

        if ($name === '' || $user === '') {
            $errors[] = 'Nombre de DB y usuario son obligatorios.';
        } else {
            try {
                $pdo = connectDb(compact('host', 'port', 'name', 'user', 'pass'));
                // Generar APP_SALT random
                $salt = bin2hex(random_bytes(32));
                $envContent =
                    "APP_ENV=production\n" .
                    "APP_DEBUG=false\n" .
                    "APP_TZ=UTC\n" .
                    "APP_SALT=$salt\n" .
                    "DB_HOST=$host\n" .
                    "DB_PORT=$port\n" .
                    "DB_NAME=$name\n" .
                    "DB_USER=$user\n" .
                    "DB_PASS=$pass\n";
                if (@file_put_contents($envFile, $envContent) === false) {
                    $errors[] = 'No se pudo escribir el archivo .env. Chequeá permisos de escritura en ' . APP_ROOT . '.';
                } else {
                    @chmod($envFile, 0600);
                    header('Location: install.php');
                    exit;
                }
            } catch (Throwable $e) {
                $errors[] = 'Conexion fallida: ' . $e->getMessage();
            }
        }
    }

    if ($step === 'migrate') {
        bootstrapDb($envConfig);
        try {
            $r = \Core\Migrator::runPending();
            $notices[] = 'Migraciones aplicadas: ' . (count($r['applied']) ?: 'ninguna') . '.';
            header('Location: install.php');
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Error al migrar: ' . $e->getMessage();
        }
    }

    if ($step === 'admin') {
        bootstrapDb($envConfig);
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name  = trim($_POST['name'] ?? '');
        $p1 = (string)($_POST['password']  ?? '');
        $p2 = (string)($_POST['password2'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalido.';
        if ($name === '') $errors[] = 'Nombre obligatorio.';
        if (strlen($p1) < 12) $errors[] = 'Password debe tener al menos 12 caracteres.';
        if ($p1 !== $p2) $errors[] = 'Los passwords no coinciden.';

        if (!$errors) {
            \Core\Database::instance()->insert('users', [
                'email'         => $email,
                'password_hash' => password_hash($p1, PASSWORD_BCRYPT, ['cost' => 12]),
                'name'          => $name,
                'role'          => 'superadmin',
                'active'        => 1,
            ]);
            header('Location: install.php');
            exit;
        }
    }

    if ($step === 'site') {
        bootstrapDb($envConfig);
        $domain = strtolower(trim($_POST['domain'] ?? ''));
        $name   = trim($_POST['name'] ?? '');
        $slug   = trim($_POST['slug'] ?? '');
        if ($slug === '' && $name !== '') $slug = slugify($name);

        if ($domain === '' || strpos($domain, '/') !== false || strpos($domain, ' ') !== false) {
            $errors[] = 'Dominio invalido. Ej: atalaya.lat (sin https:// ni www).';
        }
        if ($name === '') $errors[] = 'Nombre del sitio obligatorio.';

        if (!$errors) {
            $db = \Core\Database::instance();
            // Si existe el row demo del seed, reusar.
            $demo = $db->fetch("SELECT * FROM sites WHERE domain = 'demo.localhost' LIMIT 1");
            if ($demo) {
                $db->query(
                    'UPDATE sites SET domain = :d, name = :n, slug = :s WHERE id = :id',
                    ['d' => $domain, 'n' => $name, 's' => $slug, 'id' => $demo['id']]
                );
            } else {
                $db->insert('sites', [
                    'domain'                    => $domain,
                    'name'                      => $name,
                    'slug'                      => $slug,
                    'theme_name'                => 'default',
                    'default_language'          => 'es',
                    'default_country'           => 'AR',
                    'affiliate_disclosure_text' => 'Divulgación: este sitio contiene enlaces de afiliados. Podemos recibir una comisión si compras a través de nuestros enlaces, sin costo adicional.',
                    'active'                    => 1,
                ]);
            }
            header('Location: install.php');
            exit;
        }
    }
}

// --- Re-detectar estado despues del POST ------------------------------------
if (is_file($envFile)) {
    $envConfig = parseEnv($envFile);
    try {
        bootstrapDb($envConfig);
        \Core\Migrator::ensureTable();
        $pending  = \Core\Migrator::pending();
        $userCount = (int)\Core\Database::instance()->fetchColumn('SELECT COUNT(*) FROM users');
        $realSite  = \Core\Database::instance()->fetch(
            "SELECT * FROM sites WHERE active = 1 AND domain <> 'demo.localhost' LIMIT 1"
        );

        if ($pending) {
            $state = 'migrate';
        } elseif ($userCount === 0) {
            $state = 'admin';
        } elseif (!$realSite) {
            $state = 'site';
        } else {
            // Todo listo: marcar como instalado.
            @file_put_contents($installedFlag, date('c'));
            @chmod($installedFlag, 0600);
            $state = 'done';
            $doneSite = $realSite;
        }
    } catch (Throwable $e) {
        $errors[] = 'Error: ' . $e->getMessage();
        $state = 'env';
    }
}

// --- Render ------------------------------------------------------------------
render($state, $errors, $notices, [
    'env_config' => $envConfig ?? null,
    'pending'    => $pending ?? [],
    'done_site'  => $doneSite ?? null,
]);


// =============================================================================
// Helpers
// =============================================================================

function parseEnv(string $file): array
{
    $cfg = [
        'host' => 'localhost', 'port' => 3306,
        'name' => '', 'user' => '', 'pass' => '',
        'charset' => 'utf8mb4',
    ];
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $v = trim($v, "\"'");
        if ($k === 'DB_HOST') $cfg['host'] = $v;
        if ($k === 'DB_PORT') $cfg['port'] = (int)$v;
        if ($k === 'DB_NAME') $cfg['name'] = $v;
        if ($k === 'DB_USER') $cfg['user'] = $v;
        if ($k === 'DB_PASS') $cfg['pass'] = $v;
    }
    return $cfg;
}

function connectDb(array $cfg): PDO
{
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $cfg['host'], $cfg['port'], $cfg['name']);
    return new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function bootstrapDb(?array $cfg): void
{
    static $booted = false;
    if ($booted || !$cfg) return;
    $cfg['charset'] = 'utf8mb4';
    \Core\Database::boot($cfg);
    $booted = true;
}

function render(string $state, array $errors, array $notices, array $ctx): void
{
    $title = [
        'env'     => '1 · Conexion a la base de datos',
        'migrate' => '2 · Aplicar migraciones',
        'admin'   => '3 · Usuario administrador',
        'site'    => '4 · Tu primer sitio',
        'done'    => 'Listo',
    ][$state] ?? 'Instalador';
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> · referedmkt</title>
<style>
  :root { --b:#2b6cb0; --bg:#f6f7f9; --bd:#e2e5ea; --r:6px; --t:#1a1d22; --m:#6b7280; }
  * { box-sizing: border-box; }
  body { margin:0; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--t); line-height:1.5; }
  .wrap { max-width:600px; margin:2rem auto; padding:0 1rem; }
  .card { background:#fff; border:1px solid var(--bd); border-radius:var(--r); padding:1.5rem; }
  h1 { margin-top:0; font-size:1.3rem; }
  .steps { display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap; font-size:0.85rem; color:var(--m); }
  .steps .s { padding:0.3rem 0.7rem; border-radius:99px; background:#fff; border:1px solid var(--bd); }
  .steps .s.a { background:var(--b); color:#fff; border-color:var(--b); font-weight:600; }
  .steps .s.d { background:#c6f6d5; color:#22543d; border-color:#9ae6b4; }
  label { display:block; font-weight:600; font-size:0.9rem; margin-bottom:0.3rem; }
  input { width:100%; padding:0.5rem 0.6rem; border:1px solid var(--bd); border-radius:var(--r); font:inherit; }
  .row { margin-bottom:1rem; }
  .grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .hint { color:var(--m); font-size:0.82rem; margin-top:0.25rem; }
  .btn { display:inline-block; padding:0.6rem 1.1rem; background:var(--b); color:#fff; border:none; border-radius:var(--r); font-weight:600; cursor:pointer; font:inherit; font-weight:700; }
  .btn:hover { background:#255a96; }
  .alert { padding:0.7rem 1rem; border-radius:var(--r); margin-bottom:1rem; }
  .alert-err { background:#fff5f5; color:#742a2a; border:1px solid #feb2b2; }
  .alert-ok  { background:#e6fffa; color:#22543d; border:1px solid #9ae6b4; }
  code { background:#f6f7f9; padding:0.1rem 0.3rem; border-radius:4px; font-size:0.9em; }
  ul { padding-left:1.2rem; }
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="steps">
      <?php foreach (['env'=>'DB','migrate'=>'Schema','admin'=>'Admin','site'=>'Sitio','done'=>'Fin'] as $k => $label):
        $cls = 's';
        if ($state === $k) $cls .= ' a';
        elseif (stepIndex($state) > stepIndex($k)) $cls .= ' d';
      ?>
        <span class="<?= $cls ?>"><?= $label ?></span>
      <?php endforeach; ?>
    </div>
    <h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>

    <?php foreach ($errors as $e): ?>
      <div class="alert alert-err"><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>
    <?php foreach ($notices as $n): ?>
      <div class="alert alert-ok"><?= htmlspecialchars($n, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>

    <?php if ($state === 'env'): ?>
      <p>Necesito las credenciales de la DB MySQL que ya creaste en Hostinger. Las encontrás en <strong>hPanel → Bases de Datos → Administración</strong>.</p>
      <form method="post">
        <input type="hidden" name="__step" value="env">
        <div class="grid2">
          <div class="row"><label>Host</label><input name="db_host" value="localhost" required></div>
          <div class="row"><label>Puerto</label><input name="db_port" value="3306" required></div>
        </div>
        <div class="row"><label>Nombre de la DB</label>
          <input name="db_name" placeholder="u123456789_referedmkt" required>
          <div class="hint">Copialo exacto de hPanel (incluye el prefijo <code>u123456789_</code>).</div>
        </div>
        <div class="row"><label>Usuario</label>
          <input name="db_user" placeholder="u123456789_refmktuser" required>
        </div>
        <div class="row"><label>Password</label>
          <input type="password" name="db_pass">
        </div>
        <button class="btn" type="submit">Probar conexión y continuar →</button>
      </form>

    <?php elseif ($state === 'migrate'): ?>
      <p>Hay <strong><?= count($ctx['pending']) ?></strong> migracion(es) pendiente(s):</p>
      <ul>
        <?php foreach ($ctx['pending'] as $m): ?>
          <li><code><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></code></li>
        <?php endforeach; ?>
      </ul>
      <form method="post">
        <input type="hidden" name="__step" value="migrate">
        <button class="btn" type="submit">Aplicar migraciones →</button>
      </form>

    <?php elseif ($state === 'admin'): ?>
      <p>Creá tu usuario administrador (superadmin).</p>
      <form method="post">
        <input type="hidden" name="__step" value="admin">
        <div class="row"><label>Email</label><input type="email" name="email" required></div>
        <div class="row"><label>Nombre completo</label><input name="name" required></div>
        <div class="grid2">
          <div class="row"><label>Password (min 12)</label><input type="password" name="password" minlength="12" required></div>
          <div class="row"><label>Repetir password</label><input type="password" name="password2" minlength="12" required></div>
        </div>
        <button class="btn" type="submit">Crear admin →</button>
      </form>

    <?php elseif ($state === 'site'): ?>
      <p>Configurá el sitio que vas a publicar en este dominio.</p>
      <form method="post">
        <input type="hidden" name="__step" value="site">
        <div class="row"><label>Dominio</label>
          <input name="domain" placeholder="atalaya.lat" value="<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
          <div class="hint">Sin <code>https://</code> ni <code>www.</code>. Debe ser el mismo que configuraste en Hostinger.</div>
        </div>
        <div class="row"><label>Nombre del sitio</label><input name="name" placeholder="Atalaya" required></div>
        <div class="row"><label>Slug interno (opcional)</label><input name="slug" placeholder="se genera del nombre"></div>
        <button class="btn" type="submit">Crear sitio →</button>
      </form>

    <?php elseif ($state === 'done'): ?>
      <div class="alert alert-ok">
        <strong>Instalación completa.</strong> El archivo <code>.installed</code> bloquea este instalador — no se puede volver a abrir sin eliminarlo manualmente.
      </div>
      <?php $s = $ctx['done_site']; ?>
      <p>Tu sitio: <strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?></strong> — <code><?= htmlspecialchars($s['domain'], ENT_QUOTES, 'UTF-8') ?></code></p>
      <ul>
        <li><a href="/admin/login">Entrar al admin →</a></li>
        <li><a href="/">Ver el sitio público</a></li>
        <li><a href="/healthz" target="_blank">Health check</a></li>
      </ul>
      <p class="hint">Siguiente paso: entrá al admin, cargá productos y publicá tu primer artículo. Hostinger hace deploy automático con cada <code>git push</code>; si aparece una migración nueva en el dashboard del admin, la aplicás con un click.</p>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
    <?php
}

function stepIndex(string $s): int {
    return ['env'=>0,'migrate'=>1,'admin'=>2,'site'=>3,'done'=>4][$s] ?? 0;
}
