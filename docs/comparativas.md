# Comparativas — proceso quincenal

Documento autónomo. La tarea programada arranca en una sesión nueva, sin
memoria de las anteriores: todo lo que hay que saber está acá.

Se lee junto con `docs/seo-geo.md` y `docs/estilo.md`.

## Qué es esta tarea

`/comparativas` tiene 3 artículos contra 25 fichas de producto cargadas. Es la
sección con la peor relación entre material disponible y páginas publicadas.

Una comparativa es la página que captura la consulta de decisión —"1Password vs
Bitwarden", "mejor EDR para PyME"—, que es la que llega más abajo en el embudo
y la que un modelo levanta entero cuando le preguntan cuál conviene.

Es **la única de las cuatro tareas de sección que escribe páginas nuevas**.
Por eso es la que más fácil se degrada en relleno, y la que tiene el estándar
de entrada más alto.

## La regla que define la tarea

**Una comparativa sin veredicto no es una comparativa, es una tabla.**

Lo que la justifica es decir **cuál conviene y a quién**. Tres productos
descritos uno tras otro, cada uno con sus pros y sus contras, y un cierre que
dice "depende de tus necesidades" es exactamente la página que no sirve a
nadie. Si de la comparación no sale un veredicto por perfil de empresa, no se
publica.

Y la regla de siempre: **si no hay material para una comparativa que llegue al
estándar, no se publica nada.** Un día sin publicar no cuesta nada.

## Elegir el tema

```bash
php bin/staleness.php --seccion=huecos
php bin/staleness.php --seccion=comparativas --top=3
```

`huecos` lista las categorías con **tres o más productos cargados y ninguna
comparativa**. Son las candidatas naturales: el catálogo ya tiene los datos y
la página nace enlazada desde el hub y desde las fichas.

Antes de escribir, dos comprobaciones obligatorias:

1. **Que no canibalice.** Si ya hay una comparativa o una guía que responde esa
   consulta, se mejora esa. Ver `docs/seo-geo.md`, sección Canibalización.

```bash
php bin/list-urls.php | grep -i <tema>
grep -ril "<producto>" content/articles/
```

2. **Que las fichas estén al día.** La comparativa hereda los precios de
   `content/products/`. Si las fichas que va a comparar tienen meses sin
   verificar —se ve en `php bin/staleness.php --seccion=fichas`— la comparativa
   va a nacer con precios viejos. En ese caso se verifica el precio contra el
   fabricante como parte del trabajo, y se anota en el mensaje final que la
   ficha necesita corrección: **la ficha la corrige su propia tarea**, no esta.

Cuando `huecos` está vacío, la tarea pasa a mantenimiento: se refresca la
comparativa peor puntuada, con el mismo criterio que las guías —verificar, no
reescribir. Ver `docs/guias.md`.

## Qué lleva una comparativa

**1.500 a 2.200 palabras.** Plantilla base: la de guía,
`content/_plantillas/guia.md`, con `type: comparison`.

En este orden:

1. **El veredicto arriba.** En dos o tres frases: cuál gana para el caso más
   común y en qué caso gana otro. No se guarda para el final; el lector que
   solo lee el primer párrafo se tiene que ir con la respuesta.
2. **Los criterios de comparación**, tres a cinco, elegidos antes de mirar los
   productos y explicados con por qué importan. Es lo que separa una
   comparativa de una lista de fichas pegadas.
3. **La tabla comparativa** sobre esos criterios, con precio, unidad y fecha
   de consulta. Es el bloque que un modelo cita entero.
4. **Un diagrama, mínimo.** Barras de precio por usuario/mes, o árbol de
   decisión por perfil de empresa.
5. **Un bloque por producto**, con qué hace bien y qué no. Del mismo largo
   para todos: dedicarle el triple al favorito se nota y se lee como venta.
6. **El veredicto por perfil**, que es el corazón de la página:

   > **Menos de 20 empleados, sin equipo técnico** → X, porque…
   > **20 a 100 con alguien de sistemas** → Y, porque…
   > **Con requisito de cumplimiento** → Z, porque…

7. **A quién no le sirve ninguno de los tres.** El bloque que casi nadie
   escribe y el que más confianza genera.
8. **Preguntas frecuentes**, con el encabezado empezando por esas dos palabras.

## Honestidad — no negociable

Es la sección con más riesgo de leerse como publicidad. Las reglas:

1. **No existe experiencia propia.** Nadie probó ni instaló los productos que
   se comparan. Prohibido "lo probamos", "en nuestra experiencia", "un cliente
   mío". Lo que hay son datos del fabricante, documentación y trabajo de
   terceros citado. Las reseñas del dueño son otra sección y otra cosa.
2. **Todo dato duro con fuente primaria enlazada y fecha de consulta.** Precio,
   límites, features, certificaciones.
3. **El veredicto se sostiene con los criterios declarados.** Si gana X, tiene
   que ganar por los criterios que se enunciaron arriba, no por simpatía.
4. **Lo que no se pudo confirmar, se dice.** Una feature que el fabricante no
   documenta se marca como no documentada, no se asume.
5. **Afiliados: el único programa activo es Hostinger.** Ninguna otra
   comparativa lleva enlace de afiliado. El veredicto no se inclina hacia un
   producto por su relación comercial, y no se afirma nada sobre esa relación
   con ningún otro fabricante.
6. **Sin alarmismo y sin superlativos.** "La mejor solución del mercado" no
   dice nada. La ventaja se demuestra con el dato que la sostiene.

## Enlazado

- **Todas las fichas** de los productos comparados.
- **El hub de la categoría**, `/productos/{slug}`.
- Las guías y noticias del sitio que toquen el tema.
- 6 a 12 enlaces internos en total, verificados contra
  `php bin/list-urls.php`.
- La comparativa nueva tiene que quedar enlazada **desde al menos dos páginas
  ya publicadas**. Normalmente el hub de la categoría y una guía. Si eso
  implica tocar otro archivo, se toca: es la excepción a la regla de un solo
  archivo por corrida.

## Verificación

```bash
php bin/build-content.php
php tests/run.php
php bin/audit.php                  # 0 errores, las advertencias se leen
php bin/list-urls.php
php bin/staleness.php --seccion=comparativas
```

Además, sobre la comparativa nueva:

- `grep -c '^# '` da 0
- `file` no reporta CRLF
- `meta_title` ≤ 60 y `meta_description` ≤ 158
- `/comparativas` y `/comparativa/{slug}` responden 200
- Emite JSON-LD con `"@type": "Article"` y, con FAQ, `"FAQPage"`
- Los precios de la tabla coinciden con las fichas, o la diferencia está
  anotada en el mensaje final

## Publicar

```bash
git fetch origin main
git checkout -B claude/comparativa-$(date +%Y-%m-%d) origin/main
# escribir, verificar
git add content/articles/{slug}.md
git commit -m "Comparativa: ..."
git checkout main && git merge --ff-only claude/comparativa-$(date +%Y-%m-%d)
git push -u origin main
```

Confirmar que `https://capacero.online/healthz` reporte el commit nuevo.

Si no hay comparativa que llegue al estándar, no se crea rama ni se commitea
nada.

## Qué no hacer

- No publicar una comparativa sin veredicto por perfil.
- No comparar productos de categorías distintas para llegar a tres.
- No escribir sobre productos que no están en el catálogo. Si falta uno que
  el tema pide, se anota en el mensaje final para que lo carguen; no se
  inventa la ficha.
- No dedicarle más espacio a un producto que a otro.
- No agregar enlaces de afiliado.
- No publicar para cumplir con la quincena.
