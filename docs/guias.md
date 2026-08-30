# Guías — proceso semanal

Documento autónomo. La tarea programada arranca en una sesión nueva, sin
memoria de las anteriores: todo lo que hay que saber está acá.

Se lee junto con `docs/seo-geo.md` y `docs/estilo.md`.

## Qué es esta tarea

`/guias` tiene 20 artículos: es la sección más grande del sitio y la que trae
tráfico sostenido, porque una guía sirve igual seis meses después. También es
la que envejece sin que nadie lo note: **13 de las 20 no tienen ni un
diagrama**, varias arrastran metadatos fuera de rango y las más viejas citan
precios, versiones y plazos de hace meses.

La tarea es, por orden de prioridad:

1. **Refrescar una guía existente** — verificar sus datos, corregir lo que
   cambió, agregarle el diagrama que le falta, arreglar encabezados y
   metadatos.
2. **Escribir una guía nueva** — solo cuando hay un tema que el sitio no cubre
   y que alguien busca de verdad.

El default es refrescar. Una guía que ya rankea y se pone al día rinde más que
una guía nueva que empieza de cero, y no canibaliza nada.

## La regla que define la tarea

**Refrescar es verificar, no reescribir.**

Cambiar palabras sin cambiar información no mejora nada, quema la señal de
`updated:` y arriesga romper lo que ya funcionaba. Si se revisó una guía y
todos sus datos siguen siendo correctos, el resultado correcto es **no tocar
nada** —ni la fecha— y decirlo en el mensaje final.

Lo que sí justifica tocarla: un dato que cambió, un diagrama que falta, un
encabezado que no se sostiene leído solo, un metadato fuera de rango, un
enlace roto, o una parte del tema que quedó sin cubrir.

## Elegir sobre qué trabajar

```bash
php bin/staleness.php --seccion=guias --top=6
```

Ordenado por urgencia: pesa la falta de diagramas, los metadatos fuera de
rango, los encabezados anafóricos, el enlazado pobre y la antigüedad. Se toma
**la primera**, una por corrida.

Para escribir una guía nueva en vez de refrescar, hacen falta las dos cosas:

- La lista de arriba no tiene nada urgente (ninguna guía con puntaje alto).
- Hay un tema con demanda real que el sitio no cubre, y **no canibaliza**
  ninguna página existente. Se comprueba:

```bash
php bin/list-urls.php | grep -i <tema>
grep -ril "<entidad>" content/articles/
```

Si ya hay una página para esa consulta, se mejora esa. Ver `docs/seo-geo.md`,
sección Canibalización.

## Qué se verifica al refrescar

Con la guía abierta, se recorre entera y se comprueba contra fuente primaria:

1. **Precios y planes.** Si la guía cita el precio de un producto del
   catálogo, tiene que coincidir con su ficha en `content/products/`. Si no
   coinciden, manda la ficha.
2. **Versiones y nombres de producto.** Los fabricantes renombran cosas. Un
   panel que ya no se llama así deja la instrucción inservible.
3. **Comandos y rutas de menú.** Es lo que más envejece en una guía práctica
   y lo que más frustra cuando falla.
4. **Plazos y fechas normativas.** Una fecha de vigencia que ya pasó cambia el
   sentido del párrafo.
5. **Enlaces externos.** Los que devuelven 404 se reemplazan por la fuente
   viva o se sacan.
6. **Enlaces internos.** Verificados contra `php bin/list-urls.php`. Si desde
   que se escribió la guía aparecieron páginas nuevas que le corresponden
   —una noticia sobre el mismo CVE, una comparativa de la categoría— se
   agregan.

## Diagramas

**Mínimo uno por guía.** Es el trabajo pendiente más grande de la sección: 13
guías no tienen ninguno.

No es decoración. `llms-full.txt` aplana cada SVG a su `aria-label`, así que
la etiqueta **lleva los datos**: un diagrama de tres precios tiene los tres
números en la etiqueta. Ver `docs/seo-geo.md`, sección Diagramas.

Qué dibujar según lo que tenga la guía —plantillas probadas, restricciones de
ancho y reglas de color en `docs/noticias.md`, sección Diagramas:

| Si la guía tiene… | Va este diagrama |
|---|---|
| Un proceso por etapas | Cadena de cajas con flechas |
| Una decisión con ramas | Árbol de decisión |
| Precios o magnitudes comparables | Barras horizontales |
| Un calendario de implementación | Línea de tiempo |
| Configuración correcta contra incorrecta | Dos bloques enfrentados |

Un diagrama que no aclara nada no va. Antes de publicar: que ningún texto se
salga del `viewBox`.

## Encabezados y metadatos

Es donde más guías fallan hoy. La regla completa está en `docs/estilo.md`,
sección "Títulos y encabezados".

- Cada `##` se tiene que sostener **leído solo**, fuera del artículo.
- Nada de anáfora: "estas herramientas", "el dato", "la primera".
- Nada que sirva igual en otra página del sitio.
- `meta_title` ≤ 60 caracteres, `meta_description` entre 110 y 158.

Varias guías vienen de una época con títulos en Mayúsculas De Cada Palabra.
Se normalizan a mayúscula inicial nada más.

`bin/audit.php` marca los encabezados anafóricos, los repetidos entre páginas
y los que no nombran ninguna entidad.

## Si se escribe una guía nueva

Plantilla en `content/_plantillas/guia.md`.

- **1.800 a 2.600 palabras.** Es un objetivo, no una cuota: rellenar para
  llegar se nota.
- La respuesta arriba, en el primer párrafo. Ver `docs/seo-geo.md`.
- Un bloque de **a quién no le aplica**. Es el que más confianza genera.
- Pasos accionables con el comando o la ruta de menú concreta.
- Mínimo un diagrama.
- Preguntas frecuentes al final, con el encabezado empezando por esas dos
  palabras.
- 6 a 12 enlaces internos, y **al menos dos páginas ya publicadas tienen que
  enlazar la guía nueva**, o nace huérfana.
- `category` de las que existen en `content/categories/`.

## Honestidad

Las mismas reglas del resto del sitio:

- **No existe experiencia propia** fuera de las reseñas del dueño. Prohibido
  "probé", "en mi laboratorio", "un cliente mío".
- Todo dato duro con fuente primaria enlazada y fecha de consulta.
- Lo que no se pudo confirmar, se dice. No se completa con lo que suena
  razonable.
- Enfoque defensivo: se explica el mecanismo y se enlaza al trabajo original,
  no se publica un exploit listo para usar.
- Sin alarmismo.
- El único programa de afiliados activo es Hostinger.

## Verificación

```bash
php bin/build-content.php
php tests/run.php
php bin/audit.php                  # 0 errores, las advertencias se leen
php bin/list-urls.php
php bin/staleness.php --seccion=guias --top=6
```

Además, sobre la guía tocada:

- `grep -c '^# '` da 0 (el H1 sale del front matter)
- `file` no reporta CRLF
- `meta_title` ≤ 60 y `meta_description` ≤ 158
- `/guia/{slug}` responde 200
- Emite JSON-LD con `"@type": "Article"` y, si hay FAQ, `"FAQPage"`

## Publicar

```bash
git fetch origin main
git checkout -B claude/guia-$(date +%Y-%m-%d) origin/main
# escribir, verificar
git add content/articles/{slug}.md
git commit -m "Guía: ..."
git checkout main && git merge --ff-only claude/guia-$(date +%Y-%m-%d)
git push -u origin main
```

Confirmar que `https://capacero.online/healthz` reporte el commit nuevo.

Si se revisó y no había nada que corregir, no se crea rama ni se commitea nada.

## Qué no hacer

- No reescribir prosa que ya funciona. Refrescar es verificar.
- No mover `updated:` de una guía que no cambió.
- No escribir una guía nueva sobre un tema que el sitio ya cubre.
- No tocar código, tema, tests ni fichas. Esta tarea escribe **un** archivo en
  `content/articles/`.
- No agregar un diagrama decorativo para cumplir con el mínimo.
- No inventar un número para redondear un párrafo.
