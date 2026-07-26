# Contenido

Los artículos viven acá como markdown y se sincronizan solos a la base en cada
deploy. **No hace falta pegar nada en el admin.**

```
content/
└── capacero.online/          ← el dominio, tiene que existir en la tabla `sites`
    └── articulos/
        └── que-es-edr.md     ← el nombre del archivo es el slug de la URL
```

`que-es-edr.md` con `type: guide` se publica en `/guia/que-es-edr`.

## Flujo

1. Se escribe o edita un `.md` acá.
2. `git push`.
3. Hostinger hace pull y corre `bin/post-deploy.php`, que aplica migraciones
   pendientes y después ejecuta `bin/import-content.php`.
4. El importer inserta o actualiza el artículo y avisa a Bing/Yandex por IndexNow.

Es idempotente: guarda el sha256 de cada archivo en la tabla `content_files`.
Si el `.md` no cambió, no toca la fila de `articles` (así no se ensucia el
`lastmod` del sitemap sin motivo).

## Front-matter

Va al principio del archivo, entre `---`:

```yaml
---
title: Qué Es un EDR y En Qué Se Diferencia de un Antivirus
subtitle: Detección y respuesta en endpoints, explicada para quien firma la compra
excerpt: Un EDR no es "un antivirus mejor"...
category: Antivirus y EDR para empresas
author: Ian Poletti Lucero
type: guide
status: published
published_at: 2026-07-26
meta_title: "Qué Es un EDR: Diferencias con Antivirus"
meta_description: Qué hace un EDR, en qué se diferencia...
products: crowdstrike-falcon, 1password-business
featured_image: /uploads/edr.webp
---
```

| Campo | Obligatorio | Notas |
|---|---|---|
| `title` | sí | máx. 255 |
| `subtitle` | no | máx. 300 |
| `excerpt` | no | máx. 500. Es lo que se ve en las tarjetas |
| `category` | no | acepta el slug o el nombre visible |
| `author` | no | acepta el slug o el nombre visible |
| `type` | no | `guide` (default), `review`, `comparison`, `news` |
| `status` | no | `published` (default), `draft`, `archived` |
| `published_at` | no | cualquier formato que entienda `strtotime` |
| `meta_title` | no | máx. 255. Si falta se usa `title` |
| `meta_description` | no | máx. 500 |
| `products` | no | slugs separados por coma → "Productos mencionados" |
| `featured_image` | no | ruta o URL |

Si una categoría, autor o producto referenciado no existe todavía, el importer
avisa por stderr y sigue dejando ese campo sin asignar. No rompe el deploy.

## Cuerpo

Markdown estándar. El parser soporta encabezados, **negrita**, *itálica*,
`código`, bloques con ``` ```, listas, citas con `>`, tablas GFM, links e
imágenes. El HTML crudo se escapa por seguridad.

Convenciones del sitio:

- El `title` del front-matter ya es el `<h1>`. En el cuerpo se arranca en `##`.
- El índice lateral se arma solo con los `##` y `###`, y aparece a partir de
  3 encabezados.
- Los links internos se escriben relativos (`/guias`, `/producto/x`).
  `/admin/link-health` avisa si alguno apunta a un slug que no existe.
- Para links de afiliado, usar siempre `/go/{tracking_slug}` y nunca la URL
  del vendor directo: así el click se trackea y los UTM se agregan solos.

## Comandos

```bash
php bin/import-content.php            # importa lo que cambió
php bin/import-content.php --dry-run  # preview, no escribe nada
php bin/import-content.php --force    # reimporta todo aunque el hash coincida
php bin/import-content.php --site capacero.online
```

## Editar en el admin

Se puede, pero el `.md` es la fuente de verdad: si después se toca el archivo,
el import lo pisa. Para cambios que tienen que perdurar, editar el `.md`.
