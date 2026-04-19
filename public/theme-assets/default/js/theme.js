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
