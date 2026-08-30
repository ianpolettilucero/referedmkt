# Manuales editoriales

Cada tarea programada arranca en una sesión nueva, sin memoria de las
anteriores. Estos documentos son lo único que sabe. Por eso son autónomos y se
repiten entre sí donde hace falta.

## Qué lee cada tarea

| Tarea | Frecuencia | Escribe en | Manual |
|---|---|---|---|
| Noticia diaria | diaria | `content/articles/` | `noticias.md` |
| Guías | semanal | `content/articles/` | `guias.md` |
| Fichas de producto | semanal | `content/products/` | `fichas.md` |
| Comparativas | quincenal | `content/articles/` | `comparativas.md` |
| Hubs de categoría | trimestral | `content/categories/` | `hubs.md` |

**Los tres que lee todo el mundo**, además del suyo:

- `estilo.md` — voz, frases, títulos y encabezados.
- `seo-geo.md` — lo que se mide antes de publicar, en todas las secciones.
- el propio, de la tabla de arriba.

## La regla que comparten todas

**Si nada llega al estándar, no se publica nada.** Vale para las cinco. Una
página floja no es neutral: gasta presupuesto de rastreo, baja la calidad
media del sitio y entrena al lector a saltearse la sección.

Terminar una corrida con "revisé, no había nada que corregir" es un resultado
correcto y frecuente.

## Herramientas

```bash
php bin/staleness.php                  # qué pide trabajo, por sección
php bin/staleness.php --seccion=huecos # qué página falta que el catálogo justifica
php bin/build-content.php              # compila y valida
php tests/run.php                      # suite completa
php bin/audit.php                      # SEO/GEO sobre el HTML servido, 0 errores
php bin/list-urls.php                  # destinos válidos de enlaces internos
php bin/check-updated.php              # que `updated:` no mienta (corre en CI)
```

`staleness.php` es el que decide sobre qué trabajar. Sin él, una tarea sin
memoria vuelve siempre sobre lo mismo.

## Quién manda cuando dos manuales se pisan

1. `estilo.md` en cuestiones de voz y encabezados.
2. `seo-geo.md` en límites medibles: metadatos, enlaces, diagramas, fechas.
3. El manual de la sección en todo lo demás.

Si algo de un manual de sección contradice a `estilo.md` o a `seo-geo.md`, el
que está mal es el manual de sección: se corrige ahí y se avisa en el mensaje
final de la corrida.

## Reparto: quién toca qué

Ninguna tarea escribe fuera de su carpeta. Es lo que evita que dos corridas
del mismo día se pisen.

- Un precio equivocado en una guía → lo corrige la tarea de guías **en la
  guía**; la ficha la corrige la tarea de fichas.
- Una comparativa que necesita quedar enlazada desde el hub → es la única
  excepción, y está escrita en `comparativas.md`.
- Nadie toca `core/`, `controllers/`, `themes/` ni `tests/`. Un problema de
  código se reporta en el mensaje final, no se arregla desde una tarea
  editorial.
