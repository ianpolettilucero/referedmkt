<?php
namespace Controllers;

use Core\Content;

/**
 * Health check. Usado por UptimeRobot/BetterStack/etc.
 *
 * Responde 200 + JSON si el sistema esta OK, 503 + JSON si algo falla.
 *
 * Sin base de datos lo unico que puede fallar es el contenido: que content/ no
 * compile (front-matter roto, referencia a algo que no existe) o que el sitio
 * no este configurado. Los dos casos dejan el sitio inservible, asi que
 * merecen el 503.
 */
final class HealthController
{
    public function check(): void
    {
        $started = microtime(true);
        $status = 'ok';
        $checks = [];

        try {
            $t0 = microtime(true);
            $site = Content::site();

            $checks['content'] = [
                'ok' => $site !== [] && !empty($site['domain']),
                'ms' => round((microtime(true) - $t0) * 1000, 1),
            ];
            if (!$checks['content']['ok']) { $status = 'fail'; }

            $checks['articles']  = count(Content::articles());
            $checks['published'] = count(Content::publishedArticles());
            $checks['products']  = count(Content::products());
        } catch (\Throwable $e) {
            $status = 'fail';
            $checks['content'] = ['ok' => false, 'error' => $e->getMessage()];
        }

        $checks['php']      = PHP_VERSION;
        $checks['total_ms'] = round((microtime(true) - $started) * 1000, 1);

        http_response_code($status === 'ok' ? 200 : 503);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
        echo json_encode(['status' => $status, 'checks' => $checks], JSON_UNESCAPED_SLASHES);
    }
}
