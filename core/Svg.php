<?php
namespace Core;

/**
 * Saneador de SVG para diagramas embebidos en articulos.
 *
 * El SVG es un vector de XSS clasico: admite <script>, atributos on*,
 * <foreignObject> con HTML adentro, y referencias externas via href. Por eso
 * esto es una LISTA BLANCA y no una lista negra. Todo lo que no este
 * explicitamente permitido se cae, y ante cualquier duda el metodo devuelve
 * null y el llamador escapa el bloque como codigo: falla cerrado, nunca
 * emitiendo markup sin sanear.
 *
 * Lo que queda deliberadamente afuera y por que:
 *   script, handler          ejecucion directa
 *   style (elemento)         CSS puede traer @import y url() remotos
 *   foreignObject            reabre la puerta a HTML arbitrario
 *   image, use               cargan recursos, potencialmente externos
 *   a                        navegacion desde dentro del grafico
 *   animate*, set            pueden mutar atributos ya saneados en runtime
 *   filter y sus primitivas  feImage carga recursos externos
 *   metadata                 no aporta y arrastra namespaces raros
 *
 * href y xlink:href se eliminan siempre, sin excepcion: no hay caso de uso
 * legitimo en un diagrama estatico y son el camino mas corto a un
 * javascript: o a una carga externa.
 */
final class Svg
{
    /** Elementos permitidos. Todo lo demas se descarta con su subarbol. */
    private const ELEMENTS = [
        // Estructura
        'svg', 'g', 'defs', 'title', 'desc', 'clippath', 'marker',
        'lineargradient', 'radialgradient', 'stop',
        // Formas
        'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon',
        // Texto
        'text', 'tspan',
    ];

    /**
     * Atributos permitidos. Sin style (puede traer url() remoto) y sin ningun
     * on* (los cubre igual el chequeo explicito de prefijo).
     */
    private const ATTRIBUTES = [
        // Geometria y posicion
        'viewbox', 'width', 'height', 'x', 'y', 'dx', 'dy',
        'x1', 'y1', 'x2', 'y2', 'cx', 'cy', 'r', 'rx', 'ry',
        'd', 'points', 'transform', 'preserveaspectratio',
        // Gradientes y marcadores
        'offset', 'gradientunits', 'gradienttransform', 'spreadmethod',
        'markerwidth', 'markerheight', 'refx', 'refy', 'orient', 'markerunits',
        'clippathunits',
        // Presentacion
        'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin',
        'stroke-dasharray', 'stroke-dashoffset', 'stroke-opacity',
        'fill-opacity', 'fill-rule', 'opacity', 'stop-color', 'stop-opacity',
        'clip-path', 'marker-start', 'marker-mid', 'marker-end', 'paint-order',
        // Texto
        'font-size', 'font-family', 'font-weight', 'font-style',
        'text-anchor', 'dominant-baseline', 'letter-spacing', 'word-spacing',
        // Identidad y accesibilidad
        'id', 'class', 'role', 'aria-label', 'aria-labelledby', 'aria-hidden',
        'xmlns', 'focusable',
    ];

    /** Atributos cuyo valor puede ser una referencia url(#id) local. */
    private const URL_REF_ATTRS = [
        'fill', 'stroke', 'clip-path',
        'marker-start', 'marker-mid', 'marker-end',
    ];

    /**
     * Sanea un SVG. Devuelve el markup limpio, o null si no se puede garantizar
     * que sea seguro (falta ext-dom, XML mal formado, raiz que no es <svg>, o
     * quedo vacio despues de limpiar).
     *
     * $idPrefix namespacea los id internos. Sin eso, dos diagramas en la misma
     * pagina que definan un gradiente "grad" chocan entre si, y un id como
     * "main" puede pisar al del layout.
     */
    public static function sanitize(string $svg, string $idPrefix): ?string
    {
        if (!extension_loaded('dom')) {
            return null;
        }

        $svg = trim($svg);
        if ($svg === '') {
            return null;
        }

        // Corte previo a parsear: DOCTYPE y entidades son la puerta a XXE, y
        // las instrucciones de procesamiento no tienen para que estar.
        if (preg_match('~<!DOCTYPE|<!ENTITY|<\?~i', $svg)) {
            return null;
        }

        $doc = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        // LIBXML_NONET corta cualquier acceso a red durante el parseo. No se
        // usa LIBXML_NOENT a proposito: expandir entidades es justamente lo
        // que habilita XXE.
        $ok = $doc->loadXML($svg, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        if (!$ok || !$doc->documentElement) {
            return null;
        }
        if (strtolower($doc->documentElement->nodeName) !== 'svg') {
            return null;
        }

        self::clean($doc->documentElement, $idPrefix);

        // La raiz siempre lleva xmlns, aunque el autor lo haya omitido: sin el
        // namespace el navegador no lo interpreta como SVG.
        $doc->documentElement->setAttribute('xmlns', 'http://www.w3.org/2000/svg');

        $out = $doc->saveXML($doc->documentElement);
        return ($out === false || trim($out) === '') ? null : $out;
    }

    /** Recorre el arbol podando elementos y atributos fuera de la lista blanca. */
    private static function clean(\DOMElement $el, string $prefix): void
    {
        // Se recorre sobre una copia: quitar nodos mientras se itera la
        // DOMNodeList viva se saltea elementos. El `false` descarta las claves
        // del iterador; con claves, dos nodos homonimos se pisan y uno queda
        // sin visitar.
        foreach (iterator_to_array($el->childNodes, false) as $child) {
            if ($child instanceof \DOMElement) {
                if (!in_array(strtolower($child->nodeName), self::ELEMENTS, true)) {
                    $el->removeChild($child);
                    continue;
                }
                self::clean($child, $prefix);
            } elseif ($child instanceof \DOMComment
                   || $child instanceof \DOMProcessingInstruction
                   || $child instanceof \DOMDocumentType) {
                $el->removeChild($child);
            }
            // Los nodos de texto quedan: DOMDocument los escapa al serializar.
        }

        // El `false` es critico, no cosmetico: iterator_to_array preserva las
        // claves, y DOMNamedNodeMap las genera con el localName. "href" y
        // "xlink:href" comparten localName, asi que uno pisaba al otro en el
        // array y quedaba sin visitar — un href="javascript:…" se colaba
        // entero. Lo agarro el test, no la revision a ojo.
        foreach (iterator_to_array($el->attributes, false) as $attr) {
            $name = strtolower($attr->nodeName);

            // Los on* se chequean aparte del allowlist para que quede escrito
            // que se bloquean, aunque ninguno figure en la lista permitida.
            if (str_starts_with($name, 'on')
                || !in_array($name, self::ATTRIBUTES, true)) {
                // removeAttributeNode y no removeAttribute($nombre): con
                // atributos con namespace, borrar por nombre es ambiguo.
                $el->removeAttributeNode($attr);
                continue;
            }

            $value = $attr->nodeValue ?? '';

            // Ningun atributo permitido tiene motivo para traer un scheme.
            if (preg_match('~(javascript|data|vbscript)\s*:~i', $value)) {
                $el->removeAttributeNode($attr);
                continue;
            }

            if ($name === 'id') {
                $el->setAttribute('id', $prefix . '-' . $value);
                continue;
            }

            if ($name === 'aria-labelledby') {
                $ids = preg_split('~\s+~', trim($value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
                $el->setAttribute('aria-labelledby', implode(' ', array_map(
                    fn ($id) => $prefix . '-' . $id, $ids
                )));
                continue;
            }

            if (in_array($name, self::URL_REF_ATTRS, true) && stripos($value, 'url(') !== false) {
                // Solo referencias locales: url(#algo). Cualquier otra forma
                // —url(http…), url(data:…)— apunta afuera y se descarta.
                if (!preg_match('~^url\([\'"]?#([A-Za-z][\w.:-]*)[\'"]?\)$~', trim($value), $m)) {
                    $el->removeAttributeNode($attr);
                    continue;
                }
                $el->setAttribute($attr->nodeName, 'url(#' . $prefix . '-' . $m[1] . ')');
            }
        }
    }
}
