<?php
namespace Controllers;

use Core\Site;

/**
 * robots.txt.
 *
 * Politica: todo abierto, incluidos los crawlers de IA.
 *
 * La unica ruta bloqueada es /go/, que son los redirects de afiliado: no tienen
 * contenido propio, mandan a dominios de terceros y solo gastarian presupuesto
 * de rastreo. El resto del sitio, incluido /buscar y /comparar, es accesible;
 * esas paginas ademas llevan noindex en el <head>, que es el mecanismo correcto
 * para "no indexes" (un Disallow impide leer la pagina y por lo tanto impide
 * ver el noindex).
 *
 * Los agentes de IA se listan explicitamente aunque "User-agent: *" ya los
 * cubra. Dos razones: deja la intencion por escrito para quien audite el
 * archivo, y algunos —Google-Extended entre ellos— solo obedecen su bloque
 * nominal y no heredan del comodin.
 */
final class RobotsController
{
    /**
     * Agentes de IA a los que se les permite explicitamente todo el sitio.
     * Agrupados por para que sirve cada uno.
     */
    private const AI_AGENTS = [
        // OpenAI: entrenamiento, navegacion en vivo y el indice de ChatGPT Search.
        'GPTBot', 'ChatGPT-User', 'OAI-SearchBot',
        // Anthropic: rastreo, navegacion a pedido del usuario y busqueda.
        'ClaudeBot', 'Claude-User', 'Claude-SearchBot', 'anthropic-ai',
        // Perplexity: indice y fetch en vivo al responder.
        'PerplexityBot', 'Perplexity-User',
        // Google: sin este bloque, Gemini y los AI Overviews no usan el sitio
        // aunque Googlebot si lo indexe. Son controles separados.
        'Google-Extended',
        // Microsoft Copilot.
        'Bingbot', 'msnbot',
        // Apple Intelligence / Siri.
        'Applebot', 'Applebot-Extended',
        // Meta AI.
        'meta-externalagent', 'FacebookBot',
        // Common Crawl: alimenta datasets que usan casi todos los modelos.
        'CCBot',
        // Otros asistentes y motores de respuesta.
        'YouBot', 'Amazonbot', 'Bytespider', 'DuckAssistBot', 'cohere-ai',
    ];

    public function index(): void
    {
        $site = Site::current();
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=3600');

        $base = 'https://' . $site->domain;

        echo "# Capa Cero — abierto a buscadores y asistentes de IA.\n";
        echo "# Contenido estructurado para consumo por LLMs en {$base}/llms.txt\n";
        echo "\n";

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /go/\n";
        echo "\n";

        foreach (self::AI_AGENTS as $agent) {
            echo "User-agent: {$agent}\n";
        }
        echo "Allow: /\n";
        echo "Disallow: /go/\n";
        echo "\n";

        echo "Sitemap: {$base}/sitemap.xml\n";
        // El de noticias va aparte: reglas propias y solo las ultimas 48 horas.
        echo "Sitemap: {$base}/news-sitemap.xml\n";
        // Hint no estandar, pero varios crawlers de LLMs ya lo miran.
        echo "LLMs-Txt: {$base}/llms.txt\n";
        echo "LLMs-Full-Txt: {$base}/llms-full.txt\n";
    }
}
