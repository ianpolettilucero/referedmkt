# referedmkt

Plataforma multi-tenant de sitios de afiliados. Un solo codebase PHP/MySQL,
multiples dominios, admin panel unificado, deploy por `git push` a Hostinger.

## Estado

Fase 1 en curso: motor base.

- [x] Schema SQL inicial (`migrations/001_initial_schema.sql`)
- [x] Core: `Autoloader`, `Database` (PDO), `Site` (tenant resolver), `Router`,
      `View`, `SEO`, `Markdown`, `Session`, `Csrf`, `Flash`, `Auth`
- [x] Entry point `public/index.php` + `.htaccess` con HTTPS forzado y security headers
- [x] Migration runner CLI (`migrate.php`)
- [x] Modelos (`Article`, `Product`, `Category`, `AffiliateLink`, `Author`, `Site`)
- [x] Tema `default` con layouts/partials/views y schema.org JSON-LD
- [x] Frontend publico navegable: home, catalogo, categoria, producto, 4 tipos de articulo,
      sitemap, robots, tracking `/go/{slug}`, autor `/autor/{slug}`, RSS `/feed.xml`,
      busqueda `/buscar`, comparador `/comparar?ids=`
- [x] Admin panel (login con rate limit + CRUD sites/categories/authors/affiliate_links/
      products/articles/redirects + biblioteca de imagenes + analytics)
- [x] Redirects desde tabla `redirects` (middleware previo al router)
- [x] Uploads de imagenes con MIME-check real, SVG sanitization, picker reusable
- [x] Tests smoke (50 tests: Router, Markdown, SEO, Site, slugify, Upload, RateLimiter)

## Stack

- PHP 8.1+, MySQL 8
- PDO con prepared statements siempre
- Sin frameworks: Router y MVC custom minimal
- Markdown -> Parsedown (a integrar)
- Hosting: Hostinger con Git auto-pull

## Setup local

```bash
cp .env.example .env
# editar credenciales de DB
php migrate.php
php bin/create-admin.php admin@ejemplo.com "Tu Nombre" superadmin   # crea tu user
php -S localhost:8080 -t public
# visitar http://localhost:8080 con DEV_SITE_DOMAIN apuntando a un dominio registrado en la tabla sites
# admin en http://localhost:8080/admin/login
```

## Tests

```bash
php tests/run.php
```

Corre 40 tests smoke sin necesidad de DB. Sin framework: `tests/TestRunner.php`
es un runner minimal ~50 lineas de PHP puro.

## Admin

El admin es global (no tenant-scoped) y vive bajo `/admin` de cualquier dominio
registrado. Incluye:

- Login bcrypt + CSRF en todos los POST, session cookie `Secure`+`HttpOnly`+`SameSite=Lax`.
- Selector de sitio activo (stored en session; autoseleccion si el user solo tiene un sitio).
- CRUD: sites, categorias, autores, afiliados, productos, articulos (con preview de Markdown).
- Dashboard de analytics: clicks 30d, top afiliados/articulos/productos, serie diaria.
- Roles: `superadmin` (acceso global), `admin`, `editor` (scope via `user_site_access`).

## Deploy

`git push origin main` -> Hostinger auto-pull en `public_html`. Si el schema cambio,
correr `php migrate.php` via SSH de Hostinger.

## Estructura

```
core/          Motor compartido (Router, Database, Site, Autoloader, bootstrap)
models/        Modelos de dominio (Article, Product, etc.)
controllers/   Controllers HTTP (Redirect, Sitemap, Robots, ...)
themes/        Un subdirectorio por tema (layouts/partials/assets/views)
admin/         Panel admin unificado
public/        Entry point + assets estaticos + uploads
migrations/    Scripts SQL versionados
config/        config.php + .env (no commiteado)
```

## Seguridad

- Prepared statements en todo acceso a DB.
- Hash de IP con `APP_SALT` para clicks (no se guarda IP plana).
- HTTPS forzado por `.htaccess`.
- Headers: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`.
- Admin: passwords bcrypt (cost 12), CSRF en toda mutacion, `session_regenerate_id`
  post-login, `noindex` en admin, `admin/.htaccess` con deny de .php directos.
- Markdown sanitizado (escape HTML input, block de `javascript:` / `data:` en
  href/src, `rel="nofollow noopener" target="_blank"` en links externos).
