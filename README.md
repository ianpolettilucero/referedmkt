# referedmkt

Sitio de guías y reseñas de productos de ciberseguridad. PHP sin frameworks ni
dependencias, **sin base de datos**: el contenido son archivos Markdown en el
repo y el deploy es un `git push`.

## Cómo funciona

```
content/          Markdown con front-matter — la fuente de verdad
   ↓  ContentBuilder compila (valida + renderiza Markdown + resuelve referencias)
var/content.php   Un array PHP con todo resuelto, que opcache mantiene en RAM
   ↓  require
El sitio          Cero I/O por request
```

El cache se regenera solo: en cada request se compara la huella de `content/`
(mtime y tamaño de cada archivo) contra la del cache. Si cambió, recompila y
reescribe de forma atómica. No hay build step, ni cron, ni comando que
acordarse de correr después de un deploy.

Escribir es: crear un `.md`, `git push`. Hostinger hace `git pull` solo y el
primer visitante dispara la recompilación.

## Escribir contenido

```
content/
  site.php                    dominio, nombre, tracking IDs, tema, newsletter
  affiliate-links.php         tracking_slug => URL real del programa
  redirects.php               URL vieja => URL nueva
  authors/{slug}.md
  categories/{slug}.md
  products/{slug}.md
  articles/{slug}.md
```

**El nombre del archivo es la URL.** `articles/mi-guia.md` de tipo `guide`
queda en `/guia/mi-guia`.

Un artículo mínimo:

```markdown
---
title: "Guía de EDR para PyMEs"
excerpt: Qué mirar antes de comprar.
type: guide           # guide | review | comparison | news
status: published     # draft | published | archived
category: antivirus-empresas
author: equipo-editorial
published: 2026-01-15
products: [bitdefender-gravityzone]
---

## Cuerpo en Markdown
```

Una reseña además puede llevar veredicto propio, que es lo que alimenta el
schema `Review`:

```yaml
rating: 4.6           # 0 a 5
verdict: |
  Para quién sí y para quién no.
pros:
  - Detección muy alta
cons:
  - Consola densa
```

Y si el artículo tiene un `## Preguntas frecuentes` con las preguntas como
`###`, se emite `FAQPage` automáticamente, sin cargar nada aparte.

El front-matter es un subconjunto acotado de YAML: escalares, listas inline
(`[a, b]`), listas en bloque (`- item`), mapas de un nivel (para `specs`) y
texto multilínea (`|` y `>`). Cualquier línea que no matchee rompe el build con
el número de línea, en vez de perder el dato en silencio.

## Comandos

```bash
php bin/build-content.php          # compila y muestra el resumen
php bin/build-content.php --check  # solo valida, no escribe (lo corre el CI)
php bin/list-urls.php              # todas las URLs públicas
php tests/run.php                  # 117 tests, sin dependencias
```

Desarrollo local:

```bash
DEV_SITE_DOMAIN=capacero.online php -S localhost:8080 -t public
```

## Qué valida el build

Rompe con un mensaje concreto (`articles/x.md: el producto 'y' no existe`) si:

- El front-matter está mal formado o sin cerrar
- Falta un campo obligatorio, o `status: published` sin fecha
- `type`, `status` o `pricing_model` traen un valor fuera del enum
- Un `rating` cae fuera de 0-5
- El nombre de archivo no es un slug limpio
- Una referencia a categoría, autor, producto o afiliado apunta a algo que no existe

El CI corre esto en cada PR, más un smoke test que levanta el sitio y verifica
que cada artículo, producto, categoría y autor renderice con 200.

## Migrar desde la versión con MySQL

```bash
php bin/export-content.php backup.sql.gz
php bin/build-content.php
```

Lee el `.sql.gz` que generaba el botón de backup del admin (o cualquier
mysqldump) y escribe el árbol `content/` completo. No pisa archivos existentes
salvo que le pases `--force`, y deja afuera los secretos que vivían en
`settings` — nada sensible entra al repo.

## SEO

- JSON-LD: `Review` con `itemReviewed` + `reviewRating` en reseñas, `Article` /
  `NewsArticle` en el resto, `Product` con review editorial, `BreadcrumbList`,
  `FAQPage`, `Organization`, `WebSite` y `Person`
- Open Graph, Twitter Cards, canonical, `noindex, follow` en listados filtrados
- `sitemap.xml` con imágenes, `robots.txt`, `feed.xml`
- `llms.txt` y `llms-full.txt` para los crawlers de LLMs
- Redirects 301 declarativos para cambiar URLs sin perder posiciones
- TOC automático y artículos relacionados por afinidad

## Analítica y operación

Nada de esto vive en el repo: son paneles externos ya cableados en el layout.

| Qué | Dónde |
|---|---|
| Pageviews, referrers, top pages | GA4 |
| Heatmaps y grabaciones | Microsoft Clarity |
| Indexación, queries, CTR | Search Console |
| Clicks y **conversiones** de afiliado | El panel de la red (Impact, PartnerStack) |
| WAF, rate limiting, bans | Cloudflare |

Los IDs de tracking se configuran en `content/site.php`; el que dejes en `null`
no emite snippet.

`/healthz` devuelve 200 con el conteo de contenido, o 503 si `content/` no
compila.

## Seguridad

Sin base de datos, sin panel de admin, sin login y sin un solo formulario que
postee al servidor, la superficie de ataque es casi nula: no hay nada que
inyectar ni nada a lo que autenticarse. Lo que queda:

- HTTPS forzado y HSTS por `.htaccess`
- Headers: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`,
  `Permissions-Policy`, COOP y CORP
- Markdown sanitizado: se escapa el HTML del input, se bloquean `javascript:` y
  `data:`, y los links externos salen con `rel="nofollow noopener"`
- `content/`, `core/`, `var/` y compañía bloqueados a nivel de `.htaccess`
- El único secreto posible es `.env`, que no se commitea

## Estructura

```
content/       El contenido (fuente de verdad)
core/          Motor: Content, ContentBuilder, FrontMatter, Router, SEO, Markdown…
models/        Lectura tipada sobre el store
controllers/   Controllers HTTP
themes/        Un subdirectorio por tema (layouts/partials/views)
public/        Front controller + assets + imágenes
bin/           CLI: build, export, list-urls
tests/         Suite propia, ~50 líneas de runner
var/           Cache compilado (gitignoreado, se regenera solo)
```
