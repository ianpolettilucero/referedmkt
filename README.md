# referedmkt

Plataforma multi-tenant de sitios de afiliados. Un solo codebase PHP/MySQL,
multiples dominios, admin panel unificado, deploy por `git push` a Hostinger.

## Deploy en Hostinger (3 pasos, sin SSH)

**1. Crear la base de datos** en hPanel → Bases de Datos → Crear.
Anotá: host (normalmente `localhost`), nombre de DB (incluye prefijo
`u123456789_`), usuario, password.

**2. Conectar GitHub** en hPanel → Avanzado → Git → Create Repository.
Install path: `public_html`. Activá "Auto Deployment". Hostinger clona el
repo y hace `git pull` automáticamente con cada push a la branch elegida.

**3. Abrir el instalador** en tu navegador:
`https://tudominio.com/install.php`

El wizard te guía por 4 pasos con formularios: credenciales DB → migraciones
→ usuario admin → primer sitio. Al terminar se auto-bloquea creando
`.installed` en el root del repo.

Ya está. No se necesita SSH, ni crear `.env` a mano, ni correr comandos CLI.

> 📝 Recomendado (hPanel → Avanzado → PHP Configuration): PHP 8.1+, activar
> extensiones `pdo_mysql`, `mbstring`, `fileinfo`, `gd`. SSL Let's Encrypt
> gratis en Seguridad → SSL.

## Día a día

- **Publicar contenido**: todo desde el admin en `/admin/login`.
- **Deploy de código nuevo**: `git push` y listo.
- **Cambios de schema (migraciones)**: si una actualización trajo migraciones
  nuevas, en el admin aparece un banner con un botón "Aplicar migraciones".
  Un click, hecho.
- **Backup de DB**: en `/admin/dashboard` hay botón "Descargar backup de DB"
  que genera un `.sql.gz` on-demand. Recomendado antes de cambios grandes.

Opcional, si querés backups programados: hPanel → Avanzado → Cron Jobs →
agregar `0 3 * * *` con command `/usr/bin/php /home/USER/public_html/bin/backup-db.php`.

## Setup local (desarrollo)

```bash
cp .env.example .env
# editar credenciales de DB
php migrate.php
php bin/create-admin.php admin@ejemplo.com "Tu Nombre" superadmin
php -S localhost:8080 -t public
# visitar http://localhost:8080 con DEV_SITE_DOMAIN apuntando a un dominio registrado en sites
# admin en http://localhost:8080/admin/login
```

## Tests

```bash
php tests/run.php
```

50 tests smoke sin necesidad de DB (runner minimal ~50 lineas en `tests/TestRunner.php`).

## Estructura

```
core/          Motor compartido (Router, Database, Site, Autoloader, Migrator, ...)
models/        Modelos de dominio (Article, Product, etc.)
controllers/   Controllers HTTP (Redirect, Sitemap, Robots, ...)
themes/        Un subdirectorio por tema (layouts/partials/assets/views)
admin/         Panel admin unificado
public/        Entry point + install.php + assets estaticos + uploads
migrations/    Scripts SQL versionados
bin/           Scripts CLI (backup, create-admin, setup, post-deploy)
tests/         Smoke tests
config/        config.php + .env (no commiteado)
```

## Features

- Frontend publico: home, catalogo, categoria, producto, 4 tipos de articulo,
  sitemap, robots, tracking `/go/{slug}`, autor `/autor/{slug}`, RSS `/feed.xml`,
  busqueda `/buscar`, comparador `/comparar?ids=`
- Admin panel: login con rate limit + CRUD completo (sites/categories/authors/
  affiliate_links/products/articles/redirects) + biblioteca de imagenes con
  picker reusable + settings por sitio + analytics + migraciones y backup
  on-demand.
- SEO first-class: JSON-LD (Product + AggregateRating, Article/Review,
  BreadcrumbList, FAQPage, Organization), Open Graph, Twitter Cards,
  meta templates por sitio, hreflang ready.
- Uploads: MIME-check real con finfo, SVG sanitization, whitelist de formatos,
  storage bajo `public/uploads/{site-slug}/YYYY/MM/`.
- Newsletter: form embebido configurable por sitio (ConvertKit, Buttondown,
  Mailchimp), postea directo al proveedor — no guardamos emails.
- Health check `/healthz` con 503 si la DB falla.
- CI: GitHub Actions corre lint + tests en PHP 8.1/8.2/8.3.

## Seguridad

- Prepared statements en todo acceso a DB.
- Hash de IP con `APP_SALT` para clicks y rate limit (no se guarda IP plana).
- HTTPS forzado por `.htaccess`.
- Headers: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`.
- Admin: passwords bcrypt (cost 12), CSRF en toda mutacion, `session_regenerate_id`
  post-login, `noindex`, rate limit login (5/IP, 10/email por 15min).
- Markdown sanitizado (escape HTML input, block de `javascript:` / `data:`,
  `rel="nofollow noopener" target="_blank"` en links externos).
- Installer `install.php` se auto-bloquea con `.installed` tras completar.
