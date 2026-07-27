/**
 * Theme toggle (dark default / light opcional). Persiste en localStorage.
 * Respeta prefers-color-scheme solo si el usuario nunca seteo preferencia.
 *
 * El script principal de inicializacion se inyecta inline en el <head> del
 * layout para evitar flash de tema incorrecto (FOUC). Este archivo solo
 * maneja el click del toggle.
 */
(function () {
    var btn = document.querySelector('[data-theme-toggle]');
    if (!btn) { return; }

    btn.addEventListener('click', function () {
        var current = document.documentElement.getAttribute('data-theme');
        var next = current === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', next);
        try { localStorage.setItem('refmkt-theme', next); } catch (e) {}
        btn.setAttribute('aria-label', next === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
    });
})();

/**
 * Indice del articulo: plegado en pantallas angostas, abierto cuando es riel
 * lateral. Sin esto el riel se ve como una caja cerrada con 600px de aire al
 * lado, que es justo lo que el riel viene a resolver.
 *
 * Se hace por JS y no por el atributo `open` en el HTML porque el servidor no
 * sabe el ancho del viewport. Si el JS no corre, el indice queda plegado y
 * funcional: la pagina no depende de esto.
 */
(function () {
    var toc = document.querySelector('.article-toc');
    if (!toc || !window.matchMedia) { return; }

    var wide = window.matchMedia('(min-width: 1280px)');
    var touched = false;
    var lastSet = toc.open;

    // El evento `toggle` es asincrono, asi que un flag no distingue el cambio
    // propio del click: se compara contra el ultimo estado que fijamos. Si el
    // usuario decide, deja de sincronizarse y su eleccion manda.
    toc.addEventListener('toggle', function () {
        if (toc.open !== lastSet) { touched = true; }
    });

    function sync(mq) {
        if (touched) { return; }
        lastSet = mq.matches;
        toc.open = lastSet;
    }

    sync(wide);
    if (wide.addEventListener) { wide.addEventListener('change', sync); }
})();
