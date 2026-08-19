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

## Diagramas

Un bloque cercado con lenguaje `svg` se emite como dibujo en vez de como
código:

````markdown
```svg
<svg viewBox="0 0 400 100" role="img" aria-label="Qué muestra el diagrama">
  <line x1="10" y1="50" x2="380" y2="50" stroke="currentColor" stroke-width="2"/>
  <text x="195" y="30" text-anchor="middle" fill="currentColor">Etiqueta</text>
</svg>
```
````

Dos reglas para que se vea bien:

- **Usá `currentColor` para trazos y texto.** El diagrama hereda el color de la
  página, así que funciona igual en tema claro y oscuro. Un `#000` fijo
  desaparece en modo oscuro. Para acentos, `#e23a3a` (el rojo de la marca).
- **Poné `viewBox` y un `aria-label`** que describa qué muestra. El SVG se
  escala solo al ancho disponible; dibujá en las coordenadas que te resulten
  cómodas.

Es la única vía por la que entra markup sin escapar, y pasa siempre por la
lista blanca de `core/Svg.php`: se caen `script`, `foreignObject`, `image`,
`use`, `style`, los atributos `on*`, `href` y `xlink:href`, los `url()` que no
sean referencias locales, y cualquier DOCTYPE o entidad (XXE). Los `id` se
namespacean por diagrama para que dos gráficos en la misma página no choquen.
Si algo no se puede sanear, el bloque se muestra como código escapado: falla
cerrado, nunca emite SVG sin verificar.

El front-matter es un subconjunto acotado de YAML: escalares, listas inline
(`[a, b]`), listas en bloque (`- item`), mapas de un nivel (para `specs`) y
texto multilínea (`|` y `>`). Cualquier línea que no matchee rompe el build con
el número de línea, en vez de perder el dato en silencio.

## Comandos

```bash
php bin/build-content.php          # compila y muestra el resumen
php bin/build-content.php --check  # solo valida, no escribe (lo corre el CI)
php bin/audit.php                  # auditoría de SEO, GEO y arquitectura
php bin/list-urls.php              # todas las URLs públicas
php tests/run.php                  # 117 tests, sin dependencias
```

`bin/audit.php` levanta el sitio y mide sobre el HTML servido: largo de title y
meta description, H1 duplicados, saltos de jerarquía, canonical, alt de
imágenes, densidad de datos, presencia de FAQ, profundidad de click, huérfanas y
enlazado interno. Sale con error solo ante problemas graves, así puede correr en
CI; el resto queda como informe. Acepta una URL para auditar producción:

```bash
php bin/audit.php https://capacero.online
```

## Escribir una guía

Copiar `content/_plantillas/guia.md` a `content/articles/{slug}.md`. La
plantilla lleva la estructura y el checklist comentado — cada punto sale de algo
que mide la auditoría. Antes de publicar, `php bin/audit.php`.

## Noticias

`/noticias` es un blog de nicho con eje en hacking ético, alimentado por una
tarea programada diaria. La sección aparece sola: `active_sections()` la muestra
en la navegación, el sitemap y `llms.txt` en cuanto hay una nota publicada, y la
esconde si no queda ninguna.

El proceso completo está en **[docs/noticias.md](docs/noticias.md)** — escrito
para leerse sin contexto previo, porque cada disparo de la tarea arranca en una
sesión nueva. Ahí están el estándar que tiene que cumplir una historia, dónde
mirar, las reglas de honestidad y la regla que ordena todo: **si nada llega al
estándar, no se publica**. Un día sin publicar no cuesta nada; una nota floja
sí. La plantilla es `content/_plantillas/noticia.md`.

Desarrollo local:

```bash
DEV_SITE_DOMAIN=capacero.online php -S localhost:8080 -t public bin/dev-server.php
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

## Deploy

`git push` a `main` → el CI corre lint, tests y validación de contenido, más un
smoke test que levanta el sitio → si todo queda verde, el workflow `Deploy`
pinguea el webhook de Hostinger y después verifica `/healthz` hasta confirmar
que el sitio quedó sano.

Setup, una vez. Hay dos mecanismos y el workflow usa el que encuentre
configurado; con cualquiera de los dos alcanza.

**A) SSH — recomendado.** El plan Business incluye acceso SSH.

```bash
ssh-keygen -t ed25519 -f ~/.ssh/capacero_deploy -N ""
```

La clave **pública** (`capacero_deploy.pub`) va en hPanel → Avanzado → Acceso
SSH → Claves SSH. La **privada** (`capacero_deploy`, sin `.pub`) va como secret
`SSH_KEY` en GitHub. Host, puerto y usuario los muestra esa misma pantalla de
hPanel → secrets `SSH_HOST`, `SSH_USER` y, si el puerto no es el 22, `SSH_PORT`.

**B) Webhook.** Si en hPanel → Avanzado → GIT te aparece una URL de
auto-deployment, cargala como secret `HOSTINGER_DEPLOY_WEBHOOK`. No todos los
planes la exponen.

Los secrets se cargan en GitHub → Settings → Secrets and variables → Actions →
**New repository secret**. Sin ninguno, el workflow avisa y termina sin fallar:
el deploy sigue siendo manual desde hPanel.

Conviene **no** conectar el webhook de GitHub directo a Hostinger: ese dispara
con cualquier push, sin mirar si los tests pasan. Un front-matter roto tiraría
el sitio entero.

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
