# Hubs de categoría — proceso trimestral

Documento autónomo. La tarea programada arranca en una sesión nueva, sin
memoria de las anteriores: todo lo que hay que saber está acá.

Se lee junto con `docs/seo-geo.md` y `docs/estilo.md`.

## Qué es esta tarea

Las 8 páginas de `/productos/{slug}` listan los productos de una categoría con
sus filtros. Arriba de la grilla hay un cuerpo de texto que hoy tiene, en la
mayoría, **entre 120 y 800 caracteres**: una frase suelta.

Esas páginas apuntan a la consulta más gruesa y más valiosa de cada tema —"mejor
antivirus para empresas", "backup para ransomware", "VPN o ZTNA"— y hoy no
compiten por ninguna, porque no dicen nada. Un lector que llega ve una grilla
de productos sin criterio para elegir entre ellos.

**Esta tarea convierte ese cuerpo en el texto que decide.** No en una
introducción: en el criterio de elección, la tabla que compara y el camino
hacia el producto o la guía que sigue.

## La regla que define la tarea

**Un hub sin criterio de elección es una grilla con título.**

Lo que justifica la página es la parte que el catálogo no puede mostrar: qué
distingue a un producto de otro *en esta categoría*, qué preguntar antes de
comprar, y a quién le conviene cada opción. Si el texto no dice eso, no
mejora nada por más largo que sea.

Un hub por corrida, bien hecho. No se tocan los ocho de golpe.

## Elegir sobre qué trabajar

```bash
php bin/staleness.php --seccion=hubs --top=4
```

El puntaje sube cuanto más corto está el cuerpo y **cuánto más material tiene
la categoría detrás**: un hub con 6 productos y 4 artículos rinde más que uno
con un producto y nada escrito. Se toma el primero.

Antes de escribir, hay que ver qué hay:

```bash
php bin/list-urls.php | grep <slug-categoria>
grep -l "^category: <slug>" content/products/*.md content/articles/*.md
```

## Qué lleva un hub

El cuerpo vive en `content/categories/{slug}.md`, debajo del front matter, en
markdown. **Empieza en `##`**: el `<h1>` lo pone la plantilla con el nombre de
la categoría, y un `#` en el cuerpo crearía un segundo H1 que `bin/audit.php`
marca como error.

Objetivo: **700 a 1.100 palabras**. Es un hub, no una guía. Si el tema pide
más, probablemente lo que pide es una guía enlazada desde acá.

En este orden:

1. **La respuesta arriba, en dos o tres frases.** Qué resuelve esta categoría
   y cuál es la decisión real que enfrenta el lector. Sin introducción de
   contexto.
2. **Los criterios que separan a los productos**, tres a cinco, cada uno con
   por qué importa. Es el corazón del hub y lo que ninguna grilla muestra.
3. **Una tabla comparativa** de los productos de la categoría sobre esos
   criterios. La forma más corta de comparar, y la que mejor levanta un modelo.
4. **Un diagrama.** Mínimo uno. Casi siempre un árbol de decisión —"si tenés
   X, mirá Y"— o barras con el precio de entrada de cada producto.
5. **A quién le sirve cada opción**, en una línea por producto, enlazando a su
   ficha.
6. **Preguntas frecuentes**, con el encabezado empezando por esas dos palabras
   y el nombre de la categoría después.

## Los enlaces son el punto

Un hub existe para repartir autoridad hacia adentro. Tiene que enlazar:

- **Todas las fichas** de productos de su categoría, sin excepción. Es la
  forma de que ninguna quede huérfana.
- **Las guías y comparativas** de la misma categoría.
- **La comparativa de la categoría**, si existe. Si no existe y hay tres o más
  productos cargados, aparece en `php bin/staleness.php --seccion=huecos` y la
  escribe la tarea de comparativas: acá no se escribe.

Los destinos se verifican contra `php bin/list-urls.php`.

## Datos

Todo número —precio de entrada, cuota de mercado, plazo— lleva fuente enlazada
y fecha de consulta. Ver `docs/seo-geo.md`, sección Datos.

Los precios que se citen en el hub tienen que coincidir con lo que dice la
ficha del producto. Si no coinciden, **la ficha manda**: la verifica la tarea
semanal contra el fabricante. Un hub no corrige precios; si detecta uno
sospechoso, lo deja anotado en el mensaje final y sigue.

## Metadatos

`meta_title` y `meta_description` de la categoría viven en el front matter y
casi siempre hay que rehacerlos: varios superan los 158 caracteres. Los límites
están en `docs/seo-geo.md`.

El `meta_title` lleva la consulta como la escribe la gente —"Antivirus y EDR
para empresas"—, no el nombre interno de la categoría.

## Verificación

```bash
php bin/build-content.php
php tests/run.php
php bin/audit.php                  # 0 errores
php bin/list-urls.php              # destinos de los enlaces
php bin/staleness.php --seccion=hubs
```

Además, sobre el hub tocado:

- `/productos/{slug}` responde 200
- Un solo `<h1>` en la página
- Emite JSON-LD con `"@type": "CollectionPage"` y su `ItemList` de productos
- `meta_description` ≤ 158 caracteres
- El diagrama no se sale del `viewBox` y su `aria-label` lleva los datos

## Publicar

```bash
git fetch origin main
git checkout -B claude/hub-$(date +%Y-%m-%d) origin/main
# escribir, verificar
git add content/categories/{slug}.md
git commit -m "Hub: {categoria}"
git checkout main && git merge --ff-only claude/hub-$(date +%Y-%m-%d)
git push -u origin main
```

Confirmar que `https://capacero.online/healthz` reporte el commit nuevo.

## Qué no hacer

- No escribir una guía disfrazada de hub. Si el texto pasa las 1.100 palabras
  explicando cómo se configura algo, eso es una guía y va en `/guia/`.
- No repetir lo que ya dice la comparativa de la categoría. El hub orienta y
  enlaza; la comparativa compara a fondo.
- No tocar código, tema, tests, fichas ni artículos. Esta tarea escribe en
  `content/categories/` y en ningún otro lado.
- No inventar criterios de elección que no se puedan sostener con un dato.
- No hacer los ocho hubs en una corrida.
