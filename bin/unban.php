<?php
/**
 * CLI para desbanear IPs en emergencia (si te autobaneaste).
 *
 * Uso:
 *   php bin/unban.php 1.2.3.4          # desbanear IP puntual
 *   php bin/unban.php --all            # desbanear TODAS las IPs
 *   php bin/unban.php --whitelist IP   # agregar IP a whitelist (no banear nunca)
 *   php bin/unban.php --list           # listar bans activos
 *
 * Pensado para correr via SSH cuando perdiste acceso al admin.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Database;
use Core\Security;

$argv = $_SERVER['argv'] ?? [];
$cmd  = $argv[1] ?? null;
$arg  = $argv[2] ?? null;

if ($cmd === null || $cmd === '--help' || $cmd === '-h') {
    echo <<<HELP
Uso:
  php bin/unban.php IP                  Desbanea una IP
  php bin/unban.php --all               Desbanea TODAS las IPs
  php bin/unban.php --whitelist IP      Agrega IP a whitelist (jamas sera baneada)
  php bin/unban.php --list              Muestra IPs baneadas activas

HELP;
    exit(0);
}

$db = Database::instance();

if ($cmd === '--list') {
    $rows = $db->fetchAll(
        "SELECT ip_address, reason, banned_at, expires_at, auto_banned, attempt_count
         FROM banned_ips
         WHERE expires_at IS NULL OR expires_at > NOW()
         ORDER BY banned_at DESC"
    );
    if (!$rows) {
        echo "No hay IPs baneadas activas.\n";
        exit(0);
    }
    echo sprintf("%-40s %-6s %-19s %-19s %-6s  %s\n", 'IP', 'Tipo', 'Baneada', 'Expira', 'Fails', 'Motivo');
    echo str_repeat('-', 120) . "\n";
    foreach ($rows as $r) {
        echo sprintf(
            "%-40s %-6s %-19s %-19s %-6s  %s\n",
            $r['ip_address'],
            $r['auto_banned'] ? 'auto' : 'manual',
            $r['banned_at'],
            $r['expires_at'] ?? 'permanente',
            $r['attempt_count'],
            mb_substr($r['reason'] ?? '', 0, 60)
        );
    }
    exit(0);
}

if ($cmd === '--all') {
    $n = (int)$db->fetchColumn('SELECT COUNT(*) FROM banned_ips');
    $db->query('DELETE FROM banned_ips');
    $db->query('DELETE FROM login_attempts WHERE successful = 0');
    echo "Desbaneadas $n IPs. Login attempts fallidos limpiados.\n";
    Security::logEvent('unban', [
        'ip_address' => null,
        'details'    => json_encode(['scope' => 'all', 'count' => $n, 'via' => 'CLI']),
    ]);
    exit(0);
}

if ($cmd === '--whitelist') {
    if (!$arg || !Security::isValidIp($arg)) {
        fwrite(STDERR, "IP invalida: $arg\n");
        exit(1);
    }
    $db->query(
        "INSERT INTO ip_whitelist (ip_address, note) VALUES (:ip, 'CLI whitelist')
         ON DUPLICATE KEY UPDATE note = VALUES(note)",
        ['ip' => $arg]
    );
    $db->query('DELETE FROM banned_ips WHERE ip_address = :ip', ['ip' => $arg]);
    echo "IP $arg agregada a whitelist (y desbaneada si estaba).\n";
    exit(0);
}

// Desbanear IP puntual (cmd es la IP)
if (!Security::isValidIp($cmd)) {
    fwrite(STDERR, "IP invalida: $cmd\n");
    fwrite(STDERR, "Corré `php bin/unban.php --help` para ver las opciones.\n");
    exit(1);
}

$affected = $db->query('DELETE FROM banned_ips WHERE ip_address = :ip', ['ip' => $cmd])->rowCount();
// Tambien limpiar login attempts fallidos de este ip_hash
$hashSalt = getenv('APP_SALT') ?: 'change-me-in-env';
$hash = hash('sha256', $cmd . '|login|' . $hashSalt);
$db->query('DELETE FROM login_attempts WHERE ip_hash = :h AND successful = 0', ['h' => $hash]);

if ($affected > 0) {
    Security::logEvent('unban', [
        'ip_address' => $cmd,
        'details'    => json_encode(['via' => 'CLI']),
    ]);
    echo "IP $cmd desbaneada + attempts fallidos limpiados.\n";
} else {
    echo "IP $cmd no estaba baneada (attempts fallidos limpiados igual).\n";
}
