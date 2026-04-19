<?php
namespace Controllers;

use Core\Database;

/**
 * Health check endpoint. Usado por UptimeRobot/BetterStack/etc.
 *
 * Responde 200 + JSON si el sistema esta OK, 503 + JSON si algo falla.
 * No requiere tenant (se chequea antes del Site::resolve en el front).
 */
final class HealthController
{
    public function check(): void
    {
        $started = microtime(true);
        $status = 'ok';
        $checks = [];

        try {
            $db = Database::instance();
            $t0 = microtime(true);
            $one = $db->fetchColumn('SELECT 1');
            $checks['db'] = [
                'ok' => $one === '1' || $one === 1,
                'ms' => round((microtime(true) - $t0) * 1000, 1),
            ];
            if (!$checks['db']['ok']) { $status = 'fail'; }

            // Migraciones conocidas (opcional pero util).
            $migrations = (int)$db->fetchColumn('SELECT COUNT(*) FROM migrations');
            $checks['migrations_applied'] = $migrations;
        } catch (\Throwable $e) {
            $status = 'fail';
            $checks['db'] = ['ok' => false, 'error' => $e->getMessage()];
        }

        $checks['php']      = PHP_VERSION;
        $checks['total_ms'] = round((microtime(true) - $started) * 1000, 1);

        http_response_code($status === 'ok' ? 200 : 503);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
        echo json_encode(['status' => $status, 'checks' => $checks], JSON_UNESCAPED_SLASHES);
    }
}
