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

        // Commit desplegado. Permite verificar desde afuera que un deploy
        // realmente llego, en vez de asumirlo porque el sitio responde 200.
        $commit = self::deployedCommit();
        if ($commit !== null) {
            $checks['commit'] = $commit;
        }

        $checks['php']      = PHP_VERSION;
        $checks['total_ms'] = round((microtime(true) - $started) * 1000, 1);

        http_response_code($status === 'ok' ? 200 : 503);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
        echo json_encode(['status' => $status, 'checks' => $checks], JSON_UNESCAPED_SLASHES);
    }

    /**
     * SHA corto del commit desplegado, leido de .git sin depender del binario
     * de git (que en hosting compartido puede no estar en el PATH del proceso
     * web). Devuelve null si no hay repo, que es lo normal en un tarball.
     */
    private static function deployedCommit(): ?string
    {
        $head = APP_ROOT . '/.git/HEAD';
        if (!is_readable($head)) {
            return null;
        }
        $content = trim((string)file_get_contents($head));

        // HEAD desprendido: el SHA esta ahi mismo.
        if (preg_match('/^[0-9a-f]{40}$/i', $content)) {
            return substr($content, 0, 8);
        }

        if (!preg_match('#^ref:\s*(\S+)#', $content, $m)) {
            return null;
        }
        $ref = $m[1];

        $refFile = APP_ROOT . '/.git/' . $ref;
        if (is_readable($refFile)) {
            $sha = trim((string)file_get_contents($refFile));
            return preg_match('/^[0-9a-f]{40}$/i', $sha) ? substr($sha, 0, 8) : null;
        }

        // Ref empaquetada (git gc la mueve a packed-refs).
        $packed = APP_ROOT . '/.git/packed-refs';
        if (is_readable($packed)) {
            foreach (file($packed, FILE_IGNORE_NEW_LINES) as $line) {
                if (preg_match('/^([0-9a-f]{40})\s+' . preg_quote($ref, '/') . '$/i', $line, $pm)) {
                    return substr($pm[1], 0, 8);
                }
            }
        }

        return null;
    }
}
