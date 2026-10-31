# Sección de noticias — proceso diario

Este documento es autónomo a propósito. La tarea programada que alimenta
`/noticias` arranca cada día en una sesión nueva, sin memoria de las
anteriores: todo lo que hay que saber está acá.

## Qué es esta sección

Un blog de nicho sobre ciberseguridad, escrito desde el punto de vista de Capa
Cero: qué significa cada novedad para una PyME de LATAM que corre software común
y no tiene equipo de seguridad. El **hacking ético y la seguridad ofensiva** son
el eje —la mirada de "cómo se ataca esto para poder defenderlo"— pero no el
único tema. Entra la ciberseguridad en general: brechas y filtraciones,
operaciones contra bandas de ransomware, movimientos de la industria, cambios
regulatorios, informes grandes y campañas nuevas.

**No toda nota lleva un CVE.** Una filtración de datos de una empresa conocida,
la caída de un grupo de ransomware o una ley que entra en vigencia son noticias
válidas y muchas veces mejores que la vulnerabilidad número diez de la semana.
El CVE es un tipo de nota, no el tipo.

No es un agregador. No republicamos titulares. Cada nota tiene que aportar la
traducción que falta: **si esto pasó, ¿me toca a mí, cómo lo compruebo y qué
hago el lunes a la mañana?** Una noticia que no se puede convertir en eso
—porque le pasó a un banco y a una PyME no le cambia nada— no es para esta
sección, por resonante que sea el titular.

La sección aparece sola. `active_sections()` la muestra en la navegación, el
sitemap y `llms.txt` en cuanto existe una nota con `status: published`, y la
esconde si no hay ninguna. No hay nada que activar. Lo mismo vale para la
sección Mundo, que se explica más abajo.

## La regla que define la sección

**Si ningún hallazgo del día llega al estándar, no se publica nada.**

Este es el punto más importante del documento. Una nota floja no es neutral:
gasta presupuesto de rastreo, baja la calidad media del sitio y entrena a los
lectores a saltearse la sección. Un día sin publicar no cuesta nada.

Terminar el día con "revisé, no había nada que valiera una nota" es un
resultado correcto y esperado. Pasa seguido.

## El estándar

Una historia se publica solo si cumple **las cinco**:

1. **Verificable contra fuente primaria.** NVD, el aviso del fabricante, el
   catálogo KEV de CISA, el boletín oficial, el repositorio original, o el
   informe de quien investigó, con nombre y fecha. Un medio que cita a otro
   medio no es fuente.
2. **Le toca a una PyME de LATAM.** Software que esa empresa realmente corre:
   Microsoft 365, Windows, WordPress y sus plugins, firewalls de borde,
   herramientas de backup, navegadores. No un SOC de banco.
3. **Hay algo que hacer.** Si la respuesta es "esperá el parche", solo va si
   además hay mitigación o forma de comprobar exposición.
4. **Engancha con el catálogo.** Idealmente toca un producto o categoría que
   el sitio ya cubre, para que el enlazado interno salga del tema y no forzado.
5. **No está publicada ya.** Antes de escribir, buscá el tema en lo que ya hay:
   si es un CVE, `grep -ril "CVE-2026-XXXXX" content/articles/`; si es una
   brecha, un grupo o una empresa, grepeá ese nombre. Revisá los slugs
   recientes. Un tema ya cubierto solo se retoma si hay novedad sustancial
   (explotación activa confirmada, un parche que no arregla, más alcance, una
   etapa nueva de una investigación en curso).

## Reglas de honestidad — no negociables

Son las mismas del resto del sitio, con dos agregados propios de noticias.

1. **No existe experiencia propia.** Nadie probó, compró, instaló ni explotó
   nada. Prohibido "probé", "compré", "en mi laboratorio", "un cliente mío",
   "vimos en nuestros sistemas". Ni una vez, ni suavizado.
2. **Todo dato duro sale de fuente primaria enlazada.** CVE, CVSS, fecha,
   versión afectada, versión corregida, cantidad de sistemas expuestos.
3. **Lo que no se pudo confirmar se dice.** No se completa con lo que suena
   razonable. Si un dato cae, cae también la conclusión que dependía de él.
4. **Enfoque educativo y defensivo, sin código listo para atacar.** El eje
   ofensivo es entender el mecanismo para defenderse, y eso está bien:
   explicar cómo funciona un ataque, mostrar cómo se detecta, y hasta
   reproducirlo en un laboratorio propio y aislado con marco defensivo. Lo que
   **no** va: un exploit listo para copiar y pegar contra una vulnerabilidad
   que se está explotando en la vida real y que corre el propio lector, nada
   pensado para evadir defensas, y nada que apunte a sistemas de terceros.
   Ante el PoC de un investigador, se **enlaza al original**; no se rearma una
   versión más fácil de usar. La prueba: ¿esto le sirve más a quien se defiende
   o a quien ataca? Si es lo segundo, no va.
5. **Sin alarmismo.** "El peor ataque de la historia" y familia, fuera. La
   gravedad la establecen el CVSS y el alcance, con su fuente.
6. **Afiliados.** El único programa activo es Hostinger. No se afirma nada
   sobre la relación comercial con ningún otro fabricante, ni a favor ni en
   contra.

**El error más caro de esta sección es un CVE mal escrito.** Manda al lector a
otra vulnerabilidad, y la nota queda peor que si no existiera. Se verifica
carácter por carácter contra NVD o el aviso del fabricante, siempre.

## Dónde mirar

Seis frentes. Se recorren todos aunque uno ya haya dado material, y el filtro
LATAM se aplica a todos: entre dos historias parecidas, gana la que le toca a
la región.

| Frente | Qué se busca | Fuentes |
|---|---|---|
| Explotación activa | Vulnerabilidades que se están explotando ahora | Catálogo KEV de CISA, NVD, avisos de fabricantes |
| Ofensiva y hacking ético | Técnicas de conferencias, herramientas nuevas, cambios en certificaciones (OSCP, PNPT, CRTO), bug bounty público | Sitios y repos oficiales, blogs de investigación, actas |
| Brechas y operaciones | Filtraciones de datos, incidentes en empresas conocidas, caídas y detenciones de bandas de ransomware, operaciones policiales | Comunicados oficiales, avisos regulatorios, informes de quien investigó |
| Industria y mercado | Adquisiciones, financiamiento, fin de soporte, lanzamientos, cambios de licencia y precio de los fabricantes del catálogo | Sala de prensa, páginas de precios y de ciclo de vida |
| Regulación y política | Leyes de protección de datos con fecha de vigencia, obligaciones de notificación, exigencias de aseguradoras | Boletines oficiales, textos de ley, organismos |
| Informes y tendencias | Estudios grandes con datos citables, campañas nuevas, uso de IA por atacantes, TTP emergentes | Informe original con nombre y fecha, no la nota que lo resume |

Los seis frentes producen notas del mismo valor. Una buena nota sobre una
filtración o una ley nueva vale tanto como una buena nota sobre un CVE: lo que
importa es la traducción a "qué hago yo con esto", no que tenga un identificador
de vulnerabilidad.

## Tipos de nota

La sección publica varios formatos, no uno. Elegí el que le calce a la historia.

- **Alerta de vulnerabilidad.** Un CVE o una tanda, con versiones afectadas,
  cómo comprobar exposición y qué parchear. Es la de la plantilla por defecto.
- **Anatomía de un ataque** (la versión seria de lo que otros llaman "PoC").
  Cómo funciona por dentro una técnica o una falla: el mecanismo, por qué
  funciona, cómo se detecta y cómo se comprueba en un laboratorio propio y
  aislado. Es el formato más alineado con el eje de hacking ético y el que más
  posiciona, **con el límite de la regla 4**: se explica el mecanismo y se
  enlaza al PoC del investigador, no se publica un exploit listo para usar
  contra software que corre el lector. Si dudás de qué lado cae, cae del lado
  de no publicarlo.
- **Brecha o incidente.** Qué se filtró, de quién, y qué tiene que revisar una
  PyME que use ese servicio o comparta ese proveedor. El valor está en la
  acción para el lector, no en el morbo del titular.
- **Movimiento de industria.** Una adquisición, un fin de soporte, un cambio de
  precio de un producto del catálogo: qué obliga a hacer al que lo usa.
- **Cambio regulatorio.** Una ley con fecha de vigencia y qué implica cumplir.
  Acá el estándar de fuente es más estricto todavía: se cita el texto de la ley
  o el boletín oficial, con el artículo, nunca la interpretación de un medio.

Todos comparten la misma columna vertebral: el hecho arriba con su fuente, a
quién le toca y a quién no, y qué hacer en orden.

## La sección Mundo

Desde el 31 de octubre de 2026, Noticias tiene una sección propia para lo que
no es una alerta de vulnerabilidad: **robos y brechas, operaciones policiales,
regulación, movimientos de industria e informes**. Son los frentes 3 a 6 de la
tabla de arriba cuando la historia no gira alrededor de un CVE.

- **Cómo se marca:** `type: news` más `section: mundo` en el front matter. La
  URL sigue siendo `/noticia/{slug}`.
- **Qué aparece solo:** el listado `/noticias/mundo`, la entrada "Mundo" en el
  menú, la pestaña en `/noticias`, la miga de pan Inicio › Noticias › Mundo y
  `articleSection: "Mundo"` en el schema. Igual que la sección general, se
  muestra en cuanto hay una nota publicada.
- **Qué rechaza el build:** `section` en algo que no sea `type: news`, o un
  valor distinto de `mundo`.

**El estándar es el mismo**, con una lectura distinta del punto 2. Una nota de
Mundo califica aunque el lector no use el producto afectado, siempre que una
empresa de la región se lleve algo concreto: dónde estuvo la puerta de entrada,
qué control revisar, qué riesgo corren sus clientes o sus empleados. El robo a
Binance de octubre de 2026 es el ejemplo: la entrada fue la notebook de un
ingeniero con acceso a producción, y esa lección le sirve a cualquier empresa
con alguien en ese rol.

**Fuentes de una nota de Mundo:** el comunicado de la parte afectada, los
documentos de la policía, la fiscalía o el juzgado, el texto del regulador, y
el informe de quien investigó, con nombre, fecha y **hora de corte**. En un
incidente en curso las cifras cambian de un día para el otro: cada número va
con quién lo midió y cuándo, y si dos fuentes difieren, se dan las dos.

**Comunicados que no cargan.** X exige sesión para leer un post largo, pero
muchas empresas replican sus comunicados en otro lado. En el caso Binance, su
sistema de anuncios y su canal público de Telegram (`t.me/s/<canal>`, que se
sirve como HTML) tenían los textos completos. El endpoint público de embebido
de X (`cdn.syndication.twimg.com/tweet-result?id=<id>&token=4`) devuelve la
fecha exacta y los primeros 280 caracteres de un post.

**Sin alarmismo, también acá.** "El mayor robo de la historia" se escribe "el
mayor robo registrado", con la fuente que lo midió.

## Diagramas

**Mínimo dos por nota**, y tres cuando el tema los pide. Un dato que se puede
dibujar entra mucho mejor dibujado, y son propios: se escriben en un bloque
cercado con lenguaje `svg`, sin librerías ni imágenes externas. Las coordenadas
pueden salir de un script propio en el scratchpad, que es más exacto que
calcularlas a mano; lo que se publica es el SVG autocontenido.

### El sistema de clases

Desde octubre de 2026 los diagramas usan clases `ch-*` que define
`public/theme-assets/default/css/site.css`. Cada clase toma los colores del
tema, así que el mismo SVG se ve bien en claro y en oscuro sin `currentColor`
con opacidades a ojo.

| Clase | Para qué | Respaldo que lleva como atributo |
|---|---|---|
| `ch-title` | título del diagrama | `fill="currentColor"` |
| `ch-sub` | subtítulo: unidad, total, aclaración | `fill="currentColor" opacity="0.8"` |
| `ch-label` | etiqueta de categoría o texto común | `fill="currentColor" opacity="0.8"` |
| `ch-strong` | etiqueta de lo que importa, frase de cierre | `fill="currentColor"` |
| `ch-value` | valor al final de una barra | `fill="currentColor"` |
| `ch-inbar` | texto **dentro** de una barra (blanco en los dos temas) | `fill="currentColor"` |
| `ch-tick` | números del eje | `fill="currentColor" opacity="0.6"` |
| `ch-note` | fuente y corte de los datos | `fill="currentColor" opacity="0.65"` |
| `ch-bar` | barra común (gris) | `fill="currentColor" fill-opacity="0.45"` |
| `ch-bar-hi` | la barra que importa (rojo) | `fill="#e23a3a"` |
| `ch-grid` | grilla: línea fina y apagada | `stroke="currentColor" stroke-opacity="0.15"` |
| `ch-axis` | eje o línea de tiempo | `stroke="currentColor" stroke-opacity="0.3"` |
| `ch-arrow` | flecha entre cajas | `stroke="currentColor" stroke-opacity="0.5"` |
| `ch-box` / `ch-box-hi` | caja de una cadena de pasos | `fill="none" stroke=…` |
| `ch-dot` / `ch-dot-hi` / `ch-dot-open` | hito de una línea de tiempo; el vacío es una fecha anunciada | `fill=…` |
| `ch-mark` | se suma a toda marca con dato: activa el efecto al pasar el mouse | — |

**El atributo de respaldo no es opcional.** Es lo que se ve donde no llega la
hoja de estilos: lectores de RSS y vistas previas. El CSS lo pisa en el sitio.

La paleta está validada con el script de la guía de visualización: el rojo
resalta el dato que importa y el gris es "el resto". Separación para daltonismo
ΔE 10,2 en oscuro y 14,5 en claro; los dos superan 3:1 contra la superficie.

### Reglas que no cambian

- **Siempre `viewBox` y `aria-label`.** La etiqueta describe el diagrama **con
  sus datos**, no "gráfico de barras": es lo que oye un lector de pantalla y es
  también lo único que llega a `llms-full.txt`, donde el SVG se aplana a esa
  etiqueta.
- **El ancho útil es 660-680.** Nada de texto más allá de x=650 ni por debajo
  del alto del `viewBox`, o se recorta.
- **`text-anchor="middle"` centra sobre el punto**, así que una etiqueta larga
  cerca del borde se sale. Cerca de los bordes, anclar al inicio o al final.
- El SVG pasa por la lista blanca de `core/Svg.php`. Si algo no se puede
  sanear, el bloque sale como código escapado en vez de romper la página.
- **Un diagrama que no aclara nada, no va.** Es para mostrar el mecanismo o el
  dato, nunca para decorar.
- **Dos diagramas no van pegados**: entre medio va texto.

### Reglas de diseño

- **Barras finas**: 18 px de alto, nunca más de 24. Base recta y **extremo
  redondeado de 4 px**, que se dibuja con un `path` (un `rect` con `rx`
  redondea las dos puntas).
- **Un solo rojo por diagrama**, en la marca que cuenta la historia. El resto,
  gris. Si todo está resaltado, nada lo está.
- **El texto nunca va en el color del dato.** Las etiquetas usan `ch-label` o
  `ch-strong`; la identidad la da la marca de al lado (la barra, el punto). La
  excepción es el texto dentro de una barra, que va con `ch-inbar`.
- **Etiquetas selectivas**: el valor al final de cada barra sí; un número en
  cada punto de una línea, no.
- **Grilla de línea fina, nunca punteada.** Un trazo punteado se lee como
  "umbral" o "proyección".
- **Una serie no lleva leyenda**: el título dice qué se dibuja. Dos o más
  series llevan leyenda, además de las etiquetas directas.
- **Un solo eje.** Dos magnitudes de escala distinta van en dos diagramas.
- **Cada marca con dato lleva su `<title>`** adentro. El navegador lo muestra
  al pasar el mouse, sin JavaScript. Ejemplo:
  `<path d="…" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>Ethereum: 3.790 millones (59,6%)</title></path>`
- **Fuente y corte en la última línea** (`ch-note`): quién lo midió y a qué
  hora o fecha, porque los números de un incidente en curso cambian.

### Qué dibujar según el dato

| Si la nota tiene… | Va este diagrama |
|---|---|
| Magnitudes comparables (montos, cantidades, porcentajes) | Barras horizontales, ordenadas de mayor a menor |
| Una cronología: cuándo entró, cuándo lo detectaron | Línea de tiempo vertical, con fecha a la izquierda |
| Pasos encadenados de un ataque o un flujo | Cadena de cajas con flechas |
| Dos situaciones enfrentadas: con y sin, antes y después | Dos bloques apilados |
| Plazos o ventanas de tiempo que se superponen | Barras de rango sobre un eje de horas o días |
| Un solo número que es la noticia | No es un diagrama: va en el texto, en negrita |

### Plantillas

**Barra horizontal** (una fila). Etiqueta anclada al final en x=198, barras
desde x=208, valor 6 px después de la punta.

````markdown
```svg
<svg viewBox="0 0 680 160" role="img" aria-label="Qué muestra, con todos los números">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Título que nombra la entidad</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Unidad y total</text>
  <path d="M208 58 V96" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="198" y="77" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">La que importa</text>
  <path d="M208,64 H571.8 A4,4 0 0 1 575.8,68 V78 A4,4 0 0 1 571.8,82 H208 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>La que importa: 3.790</title></path>
  <text x="581.8" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">3.790</text>
  <text x="20" y="150" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: quién lo midió, corte del día y hora.</text>
</svg>
```
````

**Hito de línea de tiempo** (una fila). Fecha en x=20, eje en x=118, texto en
x=134, 26 px entre filas.

````markdown
```svg
  <text x="20" y="70" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">29 oct. 20:05</text>
  <circle cx="118" cy="66" r="5" class="ch-dot-hi" fill="#e23a3a"/>
  <text x="134" y="70" class="ch-strong" font-size="10.5" fill="currentColor">Lo que pasó, con la entidad</text>
```
````

Ejemplos completos y vistos en los dos temas: los cuatro diagramas de la nota
del hackeo a Binance y los tres de la de Ivanti Connect Secure del 31 de
octubre de 2026.

### Antes de publicar el diagrama

**Renderizalo y miralo en los dos temas.** El chequeo de coordenadas no ve
superposiciones ni contraste. Con el servidor local levantado
(`DEV_SITE_DOMAIN=capacero.online php -S 127.0.0.1:8123 -t public bin/dev-server.php`),
un script de Playwright que ponga `data-theme="light"` y después `"dark"` en
`<html>`, tome `getBBox()` de cada `<text>` y saque una captura de cada
`figure.article-diagram` alcanza. Ningún texto puede pasar de x=650 ni salirse
del `viewBox`, y dos textos no pueden pisarse.

El detalle completo está en el README, sección Diagramas.

## Cómo se escribe

Plantilla en `content/_plantillas/noticia.md`, con la estructura sugerida y el
checklist previo a publicar.

Lo que distingue una nota de Capa Cero de un titular reciclado:

- **Arranca con el hecho.** El lector quiere saber si le toca. Nada de
  introducción de contexto.
- **Un bloque de "a quién NO le toca".** Es el párrafo que casi nadie escribe
  y el que más confianza genera.
- **Cómo comprobar exposición**, con el comando o el lugar del panel.
- **Qué hacer, en orden**, primero lo que frena el sangrado.

### Título y encabezados

La regla completa está en `docs/estilo.md`, sección "Títulos y encabezados", y
es de las que más pesan porque decide si la nota aparece en una búsqueda.

El título lleva **el nombre del producto como lo escribe la gente** —"Microsoft
Defender", no "Defender"— y el CVE si lo hay. Los encabezados tienen que
sostenerse leídos solos, porque así aparecen en el fragmento destacado de Google
y en la respuesta de un modelo. Los cuatro bloques de arriba **no se titulan con
su nombre genérico**, y **ninguno puede remitir al texto de más arriba**:

| ✗ Nunca así | ✓ Así |
|---|---|
| A quién le toca | ¿A quién afecta la falla de Elementor Pro? |
| A quién NO le toca | ¿Quién puede ignorar el aviso de Elementor Pro? |
| Cómo comprobar exposición | ¿Cómo sé si mi TrueConf está expuesto a internet? |
| Qué hacer, en orden | ¿Qué hago si tengo Zimbra en mi servidor? |
| ¿A quién afectan estas cuatro fallas? | ¿A quién afectan las fallas de Zimbra, TrueConf y MLflow? |

Las dos últimas filas son la misma regla vista de dos maneras: "A quién NO le
toca" es genérico, y "estas cuatro fallas" es anafórico. Los dos fallan la
misma prueba, porque los dos necesitan el artículo alrededor para significar
algo. `bin/audit.php` marca los dos casos en las notas publicadas.

Si el encabezado sirve igual para cualquier otra nota del sitio, está mal.
- Segunda persona, frases de largo desparejo, opinión marcada como opinión.
- 1.200 a 1.900 palabras. Es una nota, no una guía.
- 6 a 12 enlaces internos con anchor descriptivo, todos verificados contra
  `php bin/list-urls.php`.

## Verificación — todas tienen que pasar

```bash
php bin/build-content.php          # compila y valida
php tests/run.php                  # suite completa
php bin/audit.php                  # 0 errores, las advertencias se leen
php bin/list-urls.php              # los destinos de los enlaces internos
```

Además, sobre el archivo nuevo:

- `grep -c '^# '` tiene que dar 0 (el H1 es el título del front-matter)
- `file` no puede reportar CRLF
- `meta_title` ≤ 60 y `meta_description` ≤ 158 caracteres
- `/noticias` y `/noticia/{slug}` responden 200
- La nota emite JSON-LD con `"@type": "NewsArticle"`

Si algo falla y no se puede arreglar, **no se publica**. Ver la regla que
define la sección.

## Publicar

```bash
git fetch origin main
git checkout -B claude/noticias-$(date +%Y-%m-%d) origin/main
# escribir, verificar
git add content/articles/{slug}.md
git commit -m "Noticia: ..."
git checkout main && git merge --ff-only claude/noticias-$(date +%Y-%m-%d)
git push -u origin main
```

El push a `main` dispara el CI y, si queda verde, el deploy automático a
Hostinger. Confirmar mirando que `https://capacero.online/healthz` reporte el
commit nuevo.

Si no hay nota que publicar, no se crea rama ni se commitea nada.

## Qué no hacer

- No tocar código, tema, tests ni otros artículos. Una nota es **un** archivo
  en `content/articles/`.
- No republicar un CVE ya cubierto sin novedad sustancial.
- No publicar para cumplir con la frecuencia diaria.
- No inventar una cifra para redondear un párrafo.
