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
 * Clicks de afiliado como evento de GA4.
 *
 * /go/{slug} es un redirect del servidor y vive en el mismo dominio, asi que
 * la medicion automatica de GA4 no lo cuenta: "outbound click" solo dispara
 * cuando el href apunta a otro host. Sin esto, el dato que mas importa en un
 * sitio de afiliados —que articulo genera clicks y hacia que producto— no
 * existe en el panel.
 *
 * gtag manda el evento con sendBeacon, que sobrevive a la descarga de la
 * pagina, asi que no hace falta demorar la navegacion ni cancelar el click.
 */
(function () {
    if (!window.gtag) { return; }

    document.addEventListener('click', function (ev) {
        var a = ev.target.closest && ev.target.closest('a[href*="/go/"]');
        if (!a) { return; }

        var url;
        try { url = new URL(a.href, location.origin); } catch (e) { return; }
        if (url.origin !== location.origin || url.pathname.indexOf('/go/') !== 0) { return; }

        window.gtag('event', 'affiliate_click', {
            // El slug del programa: con que red se monetizo el click.
            programa:  decodeURIComponent(url.pathname.slice(4)),
            // De donde salio y hacia que ficha: los dos parametros que ya
            // arrastra affiliate_url(), asi el reporte cruza contenido con
            // producto sin configurar nada aparte.
            articulo:  url.searchParams.get('a') || '(ninguno)',
            producto:  url.searchParams.get('p') || '(ninguno)',
            transport_type: 'beacon'
        });
    }, true);
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

/**
 * Boton de "fuente preferida" de Google, con carga diferida.
 *
 * El script publisher.js de Google pesa 251 KB. Cargarlo en cada articulo
 * seria mas que todo el CSS y el JS propios del sitio juntos, y lo pagaria el
 * 99% de los lectores que nunca van a tocar el boton. Asi que se trae recien
 * cuando el bloque de compartir del final se acerca al viewport: quien no
 * termina el articulo no descarga nada.
 *
 * Se usa el modo manual (`preferred-sources-control="manual"`) para que Google
 * no dibuje su propio boton y podamos usar uno con el estilo del sitio.
 *
 * El boton empieza oculto y solo se muestra cuando el script confirmo que
 * inicializo. Un bloqueador de rastreadores corta el pedido, y en ese caso lo
 * correcto es que el boton no aparezca en vez de quedar muerto.
 *
 * Se carga 600px antes de que el bloque entre en pantalla para que al momento
 * del click el script ya este listo y el boton no tarde en responder. Google
 * abre un iframe propio y no una ventana nueva, asi que no hay riesgo de que
 * un bloqueador de popups corte la accion; es solo latencia.
 *
 * El patron de encolar en el array antes de que cargue el script es el que
 * publisher.js espera: al inicializarse recorre lo que haya en
 * self.PREFERRED_SOURCE, ejecuta cada callback con su API, y recien despues
 * reemplaza el array por un objeto con push().
 */
(function () {
    var wrap = document.querySelector('.article-preferred');
    var caja = document.querySelector('section.article-share');
    if (!wrap || !caja || !('IntersectionObserver' in window)) { return; }

    var pedido = false;

    function cargar() {
        if (pedido) { return; }
        pedido = true;

        // El tema del widget sigue al del sitio; el sitio arranca en oscuro.
        var tema = document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';

        self.PREFERRED_SOURCE = self.PREFERRED_SOURCE || [];
        self.PREFERRED_SOURCE.push(function (ps) {
            try {
                ps.init({ theme: tema, lang: 'es' });
                wrap.querySelector('button').addEventListener('click', function () {
                    ps.addPreferredSource();
                });
                wrap.hidden = false;
            } catch (e) { /* si Google cambia la API, el boton simplemente no aparece */ }
        });

        var s = document.createElement('script');
        s.async = true;
        s.src = 'https://news.google.com/swg/js/v1/publisher.js';
        s.setAttribute('preferred-sources-control', 'manual');
        document.head.appendChild(s);
    }

    var io = new IntersectionObserver(function (entradas) {
        for (var i = 0; i < entradas.length; i++) {
            if (entradas[i].isIntersecting) { cargar(); io.disconnect(); return; }
        }
    }, { rootMargin: '600px 0px' });

    io.observe(caja);
})();
