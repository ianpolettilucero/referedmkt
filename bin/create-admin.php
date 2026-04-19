<?php
/**
 * CLI para crear (o resetear) un usuario admin.
 *
 * Uso:
 *   php bin/create-admin.php email@dominio.com "Nombre Completo" [rol]
 *
 * rol: superadmin (default), admin, editor.
 * El password se pide interactivamente (no por argumento, para que no quede
 * en el history del shell).
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

$argv = $_SERVER['argv'] ?? [];
$email = strtolower(trim((string)($argv[1] ?? '')));
$name  = trim((string)($argv[2] ?? ''));
$role  = trim((string)($argv[3] ?? 'superadmin'));

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name === '') {
    fwrite(STDERR, "Uso: php bin/create-admin.php email \"Nombre\" [rol]\n");
    exit(1);
}
if (!in_array($role, ['superadmin','admin','editor'], true)) {
    fwrite(STDERR, "Rol invalido: $role\n");
    exit(1);
}

function promptPassword(string $label): string
{
    fwrite(STDOUT, $label);
    if (stripos(PHP_OS_FAMILY, 'WIN') === 0) {
        $pass = trim((string)fgets(STDIN));
    } else {
        system('stty -echo');
        $pass = trim((string)fgets(STDIN));
        system('stty echo');
        fwrite(STDOUT, "\n");
    }
    return $pass;
}

$pass1 = promptPassword("Password: ");
$pass2 = promptPassword("Repetir: ");

if ($pass1 !== $pass2) {
    fwrite(STDERR, "Los passwords no coinciden.\n");
    exit(1);
}
if (strlen($pass1) < 12) {
    fwrite(STDERR, "Password debe tener al menos 12 caracteres.\n");
    exit(1);
}

$hash = password_hash($pass1, PASSWORD_BCRYPT, ['cost' => 12]);

$db = \Core\Database::instance();
$existing = $db->fetch('SELECT id FROM users WHERE email = :e LIMIT 1', ['e' => $email]);

if ($existing) {
    $db->query(
        'UPDATE users SET password_hash = :h, name = :n, role = :r, active = 1 WHERE id = :id',
        ['h' => $hash, 'n' => $name, 'r' => $role, 'id' => $existing['id']]
    );
    echo "Usuario $email actualizado (rol: $role).\n";
} else {
    $db->insert('users', [
        'email'         => $email,
        'password_hash' => $hash,
        'name'          => $name,
        'role'          => $role,
        'active'        => 1,
    ]);
    echo "Usuario $email creado (rol: $role).\n";
}
