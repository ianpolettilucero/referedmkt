# Estado del proyecto — referedmkt / capacero.online

Documento de traspaso. Contiene todo lo necesario para retomar el proyecto en
una sesión nueva sin contexto previo.

**Última actualización:** 2026-07-26 · commit `08fc803` · branch `claude/multi-tenant-affiliate-platform-dSxC7`

---

## 1. Qué es

Plataforma multi-tenant de sitios de afiliados. Un solo codebase PHP/MySQL sirve
varios dominios; cada dominio es un "sitio" con su propio contenido, tema y
configuración, resuelto por el `Host` HTTP.

**Sitio en producción:** `capacero.online` — nicho de ciberseguridad B2B para
PyMEs de LATAM. Hosting: Hostinger. Deploy por `git push` con auto-pull.

**Objetivo de negocio:** USD 3.000–8.000/mes de ingreso pasivo por afiliación en
18–24 meses.

**Tres patas de monetización:** directorio de productos, reseñas de productos,
guías informativas.

**Dueño / operador:** Ian Poletti Lucero (es también la firma editorial del sitio).
La comunicación es en español rioplatense.

---

## 2. Stack y decisiones de arquitectura

| Decisión | Detalle |
|---|---|
| Lenguaje | PHP 8.1+, sin frameworks |
| Base | MySQL 8, InnoDB, `utf8mb4_0900_ai_ci` |
| Dependencias | **Ninguna.** No hay composer, no hay `vendor/`. Todo es código propio |
| Patrón | MVC propio: `Router`, `Controller`, `View`, `Model` base |
| DB | PDO con prepared statements. `ATTR_EMULATE_PREPARES => false` |
| Frontend | CSS propio con variables, sin build step, sin framework JS |
| Autoload | PSR-4 propio en `core/Autoloader.php` |

### Por qué "sin dependencias" importa

Es una restricción deliberada del proyecto, no una omisión. **No agregar composer
ni librerías externas** sin discutirlo. Cuando hizo falta parsear YAML, Markdown o
firmar un JWT, se escribió a mano.

### Consecuencia práctica de `EMULATE_PREPARES => false`

**No se puede reusar el mismo `:placeholder` dos veces en una query.** Esto ya
causó un 500 en el buscador. Si un valor va en varios lugares, hay que nombrarlos
distinto (`:p1`, `:p2`, …) y pasar el mismo valor.

---

## 3. Mapa del repo

```
.
├── HANDOFF.md              ← este archivo
├── README.md               ← guía de deploy en Hostinger
├── migrate.php             ← runner de migraciones (web + CLI)
├── config/config.php       ← lee de env vars, no tiene secretos hardcodeados
│
├── core/                   ← 22 clases + helpers
├── models/                 ← 8 modelos
├── controllers/            ← 14 controladores públicos
├── admin/
│   ├── entry.php           ← router del admin
│   ├── controllers/        ← 17 controladores
│   └── views/              ← 30 vistas
│
├── themes/default/         ← layouts, partials, views del sitio público
├── public/                 ← docroot: index.php, .htaccess, assets, uploads
│   ├── theme-assets/default/css/site.css   ← ~1.700 líneas, el CSS del sitio
│   ├── theme-assets/default/css/presets/   ← 14 presets de color
│   └── admin-assets/admin.css
│
├── content/                ← ARTÍCULOS EN MARKDOWN (ver sección 7)
│   ├── README.md
│   └── capacero.online/articulos/*.md
│
├── migrations/             ← 10 migraciones SQL
├── bin/                    ← 8 scripts CLI
└── tests/cases/            ← 8 tests smoke, se corren en CI
```

**Números:** 136 archivos PHP, ~15.400 líneas, 48 commits.

---

## 4. Modelo de datos

21 tablas. Todo lo tenant-scoped tiene `site_id` y **siempre** hay que filtrar por él.

### Núcleo
- `sites` — tenants. Dominio, tema, colores, y todos los IDs de tracking
- `users`, `user_site_access` — usuarios del admin y a qué sitios acceden
- `settings` — key/value por sitio
- `migrations` — control de migraciones aplicadas

### Contenido
- `articles` — `article_type` es ENUM(`review`,`comparison`,`guide`,`news`), `content` es markdown
- `products` — catálogo, con `affiliate_link_id` opcional
- `categories` — jerarquía opcional vía `parent_id`
- `authors` — firmas editoriales (señal E-E-A-T)
- `uploads` — biblioteca de imágenes
- `redirects` — redirects gestionados desde el admin

### Afiliación
- `affiliate_links` — destino real + `tracking_slug` para `/go/{slug}`
- `affiliate_clicks` — un registro por click. La IP se guarda **hasheada** (SHA-256 + `APP_SALT`)

### Métricas y salud
- `article_views_daily` — pageviews por artículo por día (para el widget de trending)
- `article_links` — links dentro de artículos, con su estado de salud
- `index_status` — estado de indexación en Google, cacheado desde la GSC API
- `content_files` — hash sha256 de cada `.md` importado (idempotencia)

### Seguridad
- `banned_ips` — IP en claro (justificado por interés legítimo, art. 6(1)(f) GDPR)
- `ip_whitelist` — IPs que nunca se banean
- `security_events` — log de auditoría
- `login_attempts` — para el rate limiting

---

## 5. Rutas

### Públicas (`public/index.php`)

```
/                          home
/productos                 catálogo
/productos/{slug}          categoría
/producto/{slug}           ficha de producto
/guias  /guia/{slug}       tipo guide
/resenas  /resena/{slug}   tipo review
/comparativas  /comparativa/{slug}
/noticias  /noticia/{slug}
/autor/{slug}
/buscar                    búsqueda
/comparar                  comparador de productos
/go/{slug}                 redirect de afiliado (trackea)
/sitemap.xml  /robots.txt  /feed.xml
/llms.txt  /llms-full.txt  para crawlers de LLMs
/healthz                   health check (no requiere tenant)
/{indexnow-key}.txt        verificación de IndexNow (interceptado antes del router)
```

### Admin (`admin/entry.php`)

Todo bajo `/admin`. Único endpoint público: `/admin/login`.
CRUD completo de: articles, products, categories, authors, affiliate-links,
redirects, sites, uploads. Más:

```
/admin/dashboard           métricas + banners de alerta
/admin/analytics           clicks, CTR, referrers, países, export a clipboard
/admin/settings            settings del sitio activo
/admin/security            bans, whitelist, log de eventos
/admin/link-health         links rotos en artículos
/admin/index-health        estado de indexación en Google
/admin/affiliate-links/health   health check de URLs de afiliado
/admin/maintenance/migrate      aplicar migraciones desde el navegador
/admin/maintenance/backup       descargar dump .sql.gz
```

---

## 6. Funcionalidades implementadas

### SEO
- Meta tags, canonical, Open Graph, `og:locale`
- JSON-LD: Organization, WebSite, Person, Product, Article, FAQPage, BreadcrumbList
- Sitemap generado desde la DB con extensión `image:image`
- `robots.txt` dinámico por sitio
- RSS en `/feed.xml`
- `llms.txt` y `llms-full.txt`
- `noindex` automático en listados filtrados y en búsqueda
- Tiempo de lectura calculado (`reading_time()` en `core/helpers/functions.php`)
- TOC automático generado desde los `h2`/`h3` (`core/Toc.php`), aparece con 3+ encabezados
- Artículos relacionados al final (`Article::related()`, prioriza categoría > tipo > recencia)

### Afiliación
- `/go/{slug}` → 302 al destino, con UTM inyectados automáticamente
  (`utm_source`=dominio, `utm_medium`=affiliate, `utm_campaign`=slug del artículo,
  `utm_content`=slug del producto). Respeta los UTM que el vendor ya haya puesto
- `/go/{slug}?preview=1` muestra la info sin trackear ni redirigir
- Rate limit: más de 20 clicks/min desde la misma IP al mismo link no se loguean
- Health check con HEAD requests en paralelo (`curl_multi`)

### Analítica propia
- Clicks por link, artículo, producto, día, país, referrer
- CTR por artículo (clicks / views)
- Usuarios únicos y recurrentes (proxy: IPs hasheadas distintas)
- Botón "Copiar reporte" que exporta todo en texto plano para pegar en un chat

### Salud del contenido
- **Link health** (`core/LinkChecker.php`): links externos por HTTP (HEAD con
  fallback a GET, cache 6h) e internos resueltos **contra la DB** (instantáneo).
  Detecta slugs inexistentes, artículos despublicados y prefijos incorrectos
  (`/guia/x` cuando en realidad es `/resena/x`). Ofrece auto-fix con confirmación
  que reescribe el markdown
- **Index health** (`core/GscInspector.php`): consulta la GSC URL Inspection API
  con service account (JWT RS256 firmado con openssl). Cache 24h
- **IndexNow** (`core/IndexNow.php`): notifica a Bing/Yandex/Seznam/Naver al
  publicar. Automático en cada save y en cada import de contenido

### Seguridad
- bcrypt cost 12, CSRF con `hash_equals`, sesiones `Secure`/`HttpOnly`/`SameSite=Lax`
- Auto-ban tras 3 logins fallidos en 15 min, con whitelist
- Detección de IP consciente de Cloudflare (`CF-Connecting-IP`)
- IPs enmascaradas en la UI del admin, con toggle para revelar
- Headers: HSTS, COOP, CORP, sin `X-Powered-By`
- `bin/unban.php` para desbloquearse por SSH en emergencia
- Markdown sanitizado (escapa HTML crudo, bloquea `javascript:`/`data:` en links)
- **Todo el módulo de seguridad falla abierto**: si falta una tabla o la DB falla,
  loguea y deja pasar. Se prioriza disponibilidad sobre protección opcional

### Tracking de terceros
Configurable por sitio en `/admin/sites`: GA4, Google Tag Manager, Google Ads,
Microsoft Clarity, Meta Pixel, verificación de GSC.

**Detalle importante:** `gtag.js` se carga **una sola vez** aunque haya GA4 y
Google Ads configurados, y después se llama `gtag('config', id)` por cada uno.
Cargar dos `<script src=gtag.js>` rompe el dataLayer.

### Frontend
- Dark/light con toggle y anti-FOUC
- 14 presets de color aplicables desde el admin
- CSS personalizado por sitio
- Responsive con breakpoints en 479 / 639 / 720 / 900 / 1200 / 1600 / 1920
- Print CSS: fuerza blanco y negro, oculta el chrome, muestra las URLs de los
  links externos, controla los saltos de página
- Botones de compartir: LinkedIn, WhatsApp, X, email
- Widget "Más leídos esta semana" en la home (aparece con 3+ artículos con views)

---

## 7. Flujo de contenido — LO MÁS IMPORTANTE PARA CONTINUAR

**Desde el commit `08fc803`, los artículos se escriben como markdown en el repo.
Ya no se pegan a mano en el admin.**

```
content/capacero.online/articulos/que-es-edr.md   →   /guia/que-es-edr
```

El nombre del archivo es el slug. El `type` del front-matter define el prefijo
de la URL.

### Cómo funciona

1. Se escribe o edita un `.md` en `content/{dominio}/articulos/`
2. `git push`
3. Hostinger hace pull y ejecuta `bin/post-deploy.php`, que corre migraciones
   pendientes y después `bin/import-content.php`
4. El importer hace upsert por `(site_id, slug)` y avisa a IndexNow

Es idempotente: guarda el sha256 en `content_files`. Si el archivo no cambió, no
toca la fila (así no se ensucia el `lastmod` del sitemap).

### Front-matter

```yaml
---
title: Qué Es un EDR y En Qué Se Diferencia de un Antivirus
subtitle: Detección y respuesta en endpoints
excerpt: Un EDR no es "un antivirus mejor"...
category: Antivirus y EDR para empresas    # acepta slug O nombre visible
author: Ian Poletti Lucero                 # acepta slug O nombre visible
type: guide                                # guide | review | comparison | news
status: published
published_at: 2026-07-26
meta_title: "..."
meta_description: "..."
products: crowdstrike-falcon               # slugs separados por coma
---
```

Si una categoría, autor o producto no existe, avisa por stderr y deja el campo
sin asignar. No rompe el deploy.

Ver `content/README.md` para la referencia completa.

### Comandos

```bash
php bin/import-content.php --dry-run   # preview
php bin/import-content.php             # importa lo que cambió
php bin/import-content.php --force     # reimporta todo
```

---

## 8. Modelo editorial de las guías

Las guías escritas siguen un formato consistente. **Mantenerlo.**

- Abre con una **escena concreta y reconocible**, nunca con "en el mundo digital de hoy"
- Un párrafo que declara qué resuelve el artículo
- Bloque **TL;DR** en `>` blockquote, con la conclusión real y accionable
- 6–8 `h2`, cada uno con varios `h3`
- Tablas comparativas donde aportan
- Sección de **errores frecuentes**
- Cierre con "Qué sigue" y links internos
- 2.000–3.000 palabras
- Tono: directo, técnico, sin marketing. Dice explícitamente **qué NO resuelve**
  cada producto o categoría
- Español rioplatense neutro ("tenés", "venís", "acá")

### Artículos existentes

**Cluster de pentesting** (en la DB, escritos en sesiones previas, **no están en
`content/`**):
- Qué es Pentesting: Fases, Tipos y Cómo Evaluar Proveedores
- Red Teaming: Qué Es, Cuándo Contratarlo y Cómo Evaluarlo
- Qué Es un Pentester: Rol, Tipos y Por Qué las Empresas los Contratan
- Certificaciones de Pentesting
- Herramientas de Pentesting
- Costos de Pentesting y Red Teaming

**En `content/`** (importables):
- `que-es-edr.md` — 2.947 palabras
- `gestor-contrasenas-empresas.md` — 2.239 palabras

### Categorías del sitio

Fundamentos y Educación · Antivirus y EDR para empresas · Gestión de contraseñas ·
VPN empresarial y ZTNA · MFA y autenticación · Backup y recuperación ante
ransomware · Seguridad de email y anti-phishing · Hosting y Cloud

Solo las dos primeras tienen contenido.

### Productos ya cargados

1Password Business · Veeam Data Platform · CrowdStrike Falcon · Cloudflare Access

---

## 9. Deploy y configuración

### Deploy

Hostinger hPanel → Git → auto-deployment desde la branch. Al hacer pull corre
`bin/post-deploy.php` (por cron), que aplica migraciones e importa contenido.

Instalación inicial: `https://dominio.com/install.php`, wizard de 4 pasos que se
auto-bloquea al terminar creando `.installed`.

### Variables de entorno

```
DB_HOST  DB_PORT  DB_NAME  DB_USER  DB_PASS
APP_ENV  APP_DEBUG  APP_SALT  APP_TZ
DEV_SITE_DOMAIN     # override del tenant, para desarrollo local
```

`APP_SALT` es crítico: se usa para hashear las IPs de `affiliate_clicks`.
Si cambia, se pierde la continuidad de las métricas de usuarios únicos.

### Settings por sitio (`/admin/settings`)

`theme_preset` · `custom_css` · `newsletter_*` (8 keys) · `indexnow_key` ·
`gsc_property_url` · `gsc_service_account_json`

### Cron sugerido en Hostinger

```
*/10 * * * *  php ~/public_html/bin/post-deploy.php     # migraciones + contenido
0 4 * * *     php ~/public_html/bin/check-links.php     # salud de links
0 5 * * *     php ~/public_html/bin/check-index.php     # estado de indexación
```

---

## 10. Estado actual y pendientes

### Migraciones que pueden estar sin aplicar

Verificar el banner en el admin. Al 2026-07-26 estaban pendientes de confirmar:

| Migración | Qué habilita |
|---|---|
| `006_article_views_daily` | widget de trending |
| `007_article_links` | panel de salud de links |
| `008_index_status` | panel de salud de indexación |
| `009_sites_tracking_ids` | Google Ads, Clarity, Meta Pixel |
| `010_content_files` | **import de contenido desde `content/`** |

Sin la 010 el pipeline de contenido no funciona.

### Bloqueado / pendiente

- **Links de afiliado.** El usuario los está consiguiendo. Cuando lleguen, se
  cargan en `/admin/affiliate-links` y se referencian en los `.md` como
  `/go/{tracking_slug}` (nunca la URL del vendor directa)
- **Google Search Console API.** El setup del service account quedó trabado:
  GSC devuelve "correo no encontrado" al intentar agregar
  `gsc-inspector@capa-cero.iam.gserviceaccount.com` como usuario. Causas
  habituales: propagación (esperar 15 min), no ser Owner verificado de la
  property, o que sea Domain property en lugar de URL-prefix. **IndexNow no
  depende de esto y ya funciona**
- **Enlazado interno al cluster de pentesting.** Las guías nuevas no linkean a
  las viejas porque no se conocen sus slugs exactos. Es una pérdida real de SEO
  y conviene resolverlo apenas se tengan
- **Indexación en Google.** El sitio es nuevo y todavía no indexa bien. No hay
  atajo técnico legítimo: la Indexing API de Google es solo para `JobPosting` y
  `BroadcastEvent`; usarla para otra cosa viola los ToS

### Categorías sin contenido

Orden propuesto para las próximas guías, priorizando las que ya tienen producto
en el catálogo:

1. **MFA y autenticación** — cierra naturalmente la guía de contraseñas
2. **Ransomware y backup** — Veeam ya está cargado
3. **ZTNA vs VPN** — Cloudflare Access ya está cargado
4. Phishing dirigido a PyMEs
5. Hosting y Cloud

---

## 11. Restricciones del entorno de desarrollo

**El sandbox donde corre Claude Code no tiene salida a internet.** Verificado:
`capacero.online`, Google, Wikipedia y los sitios de fabricantes dan
`CONNECT tunnel failed, response 403` del proxy del entorno.

Consecuencias:

- **No se puede leer el sitio en producción.** Ni siquiera `llms-full.txt`
- **No se puede investigar.** No hay forma de verificar precios actuales,
  features de productos ni datos de mercado
- **No hay acceso a la base de datos.** MySQL está en Hostinger

Por eso las guías escritas hasta ahora son **conceptuales y evergreen**, y evitan
deliberadamente precios concretos, listas de features y rankings. Para escribir
reseñas y comparativas con datos reales hace falta que el usuario amplíe la
política de red del entorno, o que aporte él los datos.

Diagnóstico del proxy: `curl -sS "$HTTPS_PROXY/__agentproxy/status"`

---

## 12. Convenciones y preferencias del usuario

Estas salieron de correcciones explícitas durante el desarrollo. **Respetarlas.**

### Comunicación
- **Todo en español.** Rioplatense neutro
- No poner links de sesión de Claude, IDs de modelo ni información sensible en
  commits, PRs ni ningún artefacto del repo. **Pedido explícito del usuario**

### Código
- Comentarios en español, sin tildes en el código (sí en el contenido)
- Explicar el *porqué* en los comentarios, no el *qué*
- Programación defensiva: todo lo opcional (seguridad, salud de links, índice)
  **falla abierto** y loguea, nunca rompe la página
- Nada de dependencias nuevas sin discutirlo

### Decisiones de UI ya tomadas (no revertir)
- **El admin NO es responsive** y así está bien. Se intentó hacerlo y rompió el
  layout; se revirtió. Conserva solo los dos breakpoints originales
  (`max-width: 860px` para el sidebar colapsable y `min-width: 720px`)
- **El TOC va inline y cerrado por defecto.** Se probó como sidebar sticky con
  scrollspy en desktop y el usuario lo rechazó ("todo se aprieta y queda
  horrible"). Se revirtió en `2589d26`
- La home muestra **4** productos destacados y **4** artículos recientes, no 6:
  con 6 quedaban dos huérfanos en una grilla de 4 columnas
- El logo del header está limitado a 36px de alto y 140px de ancho

### Preferencias de trabajo
- Prefiere que se le expliquen los trade-offs antes de implementar
- Valora que se le diga qué **no** conviene hacer y por qué
- Rechazó explícitamente: sticky CTA en reseñas, schema de Review con estrellas
  editoriales, y campo de comentarios
- Preocupación original con la generación masiva de contenido por IA
  (Helpful Content Update). Después pidió que Claude escriba las guías; el
  criterio acordado es mantener la profundidad y que él revise lo técnico

---

## 13. Errores ya cometidos y resueltos

Para no repetirlos:

| Problema | Causa | Fix |
|---|---|---|
| 500 en el buscador | `MATCH AGAINST` sin índice FULLTEXT + `:placeholder` reusado | Reescrito con `LIKE` y scoring en `CASE WHEN`, placeholders únicos |
| 500 en todas las páginas | `Security` consultaba tablas de la migración 005 sin aplicar | Todo envuelto en try/catch, falla abierto |
| 500 en artículos | `View::partial()` no inyectaba `$site` | Auto-inyección desde el singleton |
| Título duplicado en SERP | "ciberseguridad \| ciberseguridad" | `SEO::title()` omite el template si coincide con el nombre del sitio |
| Layout roto | Logo sin límite de tamaño | `max-height: 36px`, `object-fit: contain` |
| Botón con franja de color | `background-size: 200%` en el gradiente | Quitado, hover con `filter: brightness()` |
| Botón de IndexNow invisible | Estaba detrás del check de "GSC configurado" | Son independientes, se separó |

---

## 14. Cómo retomar

1. Leer este archivo y `content/README.md`
2. Confirmar con el usuario qué migraciones están aplicadas
3. Preguntar si ya tiene links de afiliado para cargar
4. Si toca escribir contenido: crear el `.md` en `content/capacero.online/articulos/`,
   seguir el modelo editorial de la sección 8, validar que renderice, commit y push

### Validar un artículo antes de commitear

```bash
php -r '
require "core/Autoloader.php";
\Core\Autoloader::register();
\Core\Autoloader::addNamespace("Core", __DIR__."/core");
require "core/helpers/slug.php";
$raw  = file_get_contents("content/capacero.online/articulos/ARCHIVO.md");
$body = preg_replace("/^---\n.*?\n---\n/s", "", $raw);
$html = \Core\Markdown::toHtml($body);
$toc  = \Core\Toc::process($html);
printf("h2:%d h3:%d tabla:%d toc:%d palabras:%d\n",
  substr_count($html,"<h2>"), substr_count($html,"<h3>"),
  substr_count($html,"<table>"), count($toc["items"]),
  str_word_count(strip_tags($html)));
'
```

### Correr los tests

```bash
php tests/run.php     # 8 casos smoke, también corren en CI (.github/workflows/ci.yml)
```
