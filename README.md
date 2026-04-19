# referedmkt

Plataforma multi-tenant de sitios de afiliados. Un solo codebase PHP/MySQL,
multiples dominios, admin panel unificado, deploy por `git push` a Hostinger.

## Estado

Fase 1 en curso: motor base.

- [x] Schema SQL inicial (`migrations/001_initial_schema.sql`)
- [x] Core: `Autoloader`, `Database` (PDO), `Site` (tenant resolver), `Router`
- [x] Entry point `public/index.php` + `.htaccess` con HTTPS forzado y security headers
- [x] Migration runner CLI (`migrate.php`)
- [ ] Admin panel (login + CRUD sites/products/articles/affiliate_links)
- [ ] Tema `default` con layouts/partials/views y schema.org JSON-LD
- [ ] Modelos (`Article`, `Product`, `Category`, `AffiliateLink`, `Site`)

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
php -S localhost:8080 -t public
# visitar http://localhost:8080 con DEV_SITE_DOMAIN apuntando a un dominio registrado en la tabla sites
```

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
- Admin (pendiente): bcrypt + CSRF tokens + sesiones PHP.
