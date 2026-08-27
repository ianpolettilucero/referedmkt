# Guía de estilo

Registro profesional, frases cortas, sin relleno. Cada párrafo tiene que
aportar un dato, una instrucción o un criterio de decisión. Si no hace ninguna
de las tres cosas, se borra.

Aplica a todo el contenido: guías, reseñas, comparativas y noticias.

## Qué se elimina

**Editorializar en primera persona.** El juicio se afirma, no se anuncia.

- ✗ *Mi lectura, y la marco como opinión: el punto 2 rinde más que el 1.*
- ✓ *El punto 2 rinde más que el 1.*
- ✗ *A mi juicio este es el único paso sin contraargumento.*
- ✓ *Es el único paso de la lista sin contraargumento.*

La honestidad no se pierde: una evaluación sigue siendo una evaluación. Lo que
se saca es el andamio que la anuncia. Cuando algo es discutible, se dice por
qué —"depende de X"—, no se firma con "en mi opinión".

**Andamiaje retórico.** Frases que preparan lo que viene en vez de decirlo.

- ✗ *Y acá está el punto que rompe el ataque:* → decir el punto
- ✗ *Vale decirlo. / Conviene entender. / Vale la pena aclarar.* → aclarar
- ✗ *La parte incómoda es que…* → decir el hecho
- ✗ *Sin vueltas: / Dicho de otro modo: / En resumen:*

**Meta-comentario sobre el propio artículo.** El lector ya sabe que está
leyendo un artículo.

- ✗ *Esta guía es para decidir. Lo que sigue no es un tutorial.*
- ✓ Empezar por el contenido.

**Introducciones de contexto.** La primera oración es el hecho más concreto y
útil que se tenga. Nunca "en el mundo digital de hoy" ni equivalentes.

**Repetición.** Si un dato ya se dio, no se recuerda tres secciones después.

## Qué se conserva

- **El voseo y el registro rioplatense.** Es la voz del sitio y lo que lo
  separa del contenido traducido del inglés. Formal no significa neutro.
- **Los datos con su fuente enlazada**, sin excepción.
- **Las tablas**, que son la forma más corta de comparar.
- **El bloque de "a quién no le aplica".** Es información, no relleno.
- **La experiencia propia en las reseñas del dueño.** Es real y es el activo
  diferencial; se acorta, no se borra.

## Frases

- Una idea por frase. Si hay dos verbos principales unidos por "y", suelen ser
  dos frases.
- Los incisos entre guiones largos se usan con moderación: uno por párrafo como
  máximo. Lo demás va en su propia frase o se corta.
- Voz activa. "El fabricante no publica el precio", no "el precio no es
  publicado por el fabricante".
- Nada de listas de tres adjetivos.

## Títulos y encabezados: intención de búsqueda

La prueba que decide: **leído solo, fuera del artículo, ¿se entiende de qué
habla?** Un encabezado aparece sin contexto en el fragmento destacado de Google,
en el bloque de "Otras preguntas" y en la respuesta de un modelo de lenguaje.
Si ahí no se sostiene, no sirve, por bien escrito que esté.

### El título

Lleva el **nombre completo del producto, como lo escribe la gente al buscar**, y
el hecho concreto. Nada de la mitad literaria.

- ✗ *Defender dejó de escanear y no avisó* → no dice qué Defender, y nadie busca
  "dejó de escanear".
- ✓ *Microsoft Defender falló los escaneos sin avisar: cómo verificarlo*
- ✗ *Zimbra y TrueConf: cuando el parche depende de vos*
- ✓ *Zimbra, TrueConf y MLflow: cuatro fallas explotadas sin parche automático*
- ✗ *El 10.0 de Entra ID no fue explotado* → falta el CVE, que es lo que se pega
  en el buscador.

Se escribe el nombre como figura en el producto: "Microsoft Defender", no
"Defender"; "Elementor Pro", no "Elementor". Si hay CVE, va el CVE, porque
pegarlo en el buscador es el primer reflejo de quien lo vio en un aviso.

### Los encabezados

Un encabezado tiene que nombrar su propio sujeto. No puede depender de
ninguna otra línea del artículo para entenderse, porque en la mitad de los
lugares donde aparece no hay artículo alrededor.

**El error que más se repite es la anáfora**: escribir el encabezado como si
el lector viniera leyendo desde arriba. Fuera de contexto no significa nada.

| ✗ Anafórico | Por qué falla |
|---|---|
| Citrix NetScaler: la única de este año y la que vence antes | ¿La única *qué*? |
| Por qué cinco de las seis tienen entre 4 y 11 años | Cinco de las seis *qué* |
| ¿Quién puede ignorar este aviso? | *Cuál* aviso |
| Qué la separa de RoguePlanet | *Qué* cosa la separa |
| ¿A quién afectan estas cuatro fallas? | *Cuáles* cuatro fallas |
| El dato que no aparece en los titulares | Sirve para cualquier nota del sitio |

Lo prohibido, en concreto:

- **Demostrativos que remiten hacia atrás**: "estas fallas", "este aviso",
  "estos servidores", "esta tanda", "dicha versión".
- **Artículos que reemplazan al sujeto**: "la única", "el dato", "la que
  vence antes", "lo que cambió".
- **Pronombres sin referente propio**: "qué la separa", "por qué importa".

### Cómo se escribe bien

Los encabezados de **algo que el lector quiere hacer** van en forma de
pregunta, porque es la forma en que lo busca, con la entidad adentro.

| ✗ Genérico o anafórico | ✓ Se sostiene solo |
|---|---|
| Cómo comprobar cada equipo | ¿Cómo verifico si Microsoft Defender escaneó mis equipos? |
| A quién le toca | ¿A quién afecta la falla de Elementor Pro? |
| A quién NO le toca | ¿Quién puede ignorar el aviso de Elementor Pro? |
| Qué hacer, en orden | ¿Qué hago si tengo Zimbra en mi servidor? |
| Cómo comprobar exposición | ¿Cómo sé si mi TrueConf está expuesto a internet? |

Los de **análisis o argumento** no se fuerzan a pregunta —queda cargoso tener
ocho seguidas— pero igual nombran su sujeto y dicen algo concreto.

- ✗ *Por qué este lote es distinto*
  → ✓ *Por qué SharePoint, macOS y vCenter se parchean solos y Zimbra no*
- ✗ *Por qué estas cuatro fallas están en el escritorio y no en el servidor*
  → ✓ *Por qué las fallas de agosto apuntan al escritorio y no al servidor*
- ✗ *El mecanismo: dos reglas para el mismo envío*
  → ✓ *Cómo funciona CVE-2026-32475: dos reglas para el mismo envío*

### La prueba, antes de publicar

Tapá el artículo entero y leé la lista de encabezados sola, como la leería
alguien que llega desde un buscador. Cada uno tiene que pasar las tres:

1. **¿Se entiende de qué habla?** Sin mirar nada más.
2. **¿Nombra su sujeto?** Producto como lo escribe la gente al buscar
   —"Microsoft Defender", no "Defender"—, CVE completo, o la entidad de la
   que trata esa sección.
3. **¿Serviría igual en otra nota del sitio?** Si la respuesta es sí, está
   mal. Dos notas no pueden compartir un encabezado palabra por palabra.

Esto no es cosmética: `bin/audit.php` marca los encabezados de `/noticia/`
que traen anáfora, que se repiten entre notas o que no nombran ninguna
entidad. Las advertencias se leen.

**Excepción de una sola línea.** El encabezado de preguntas frecuentes tiene
que empezar con las palabras "Preguntas frecuentes", porque `core/Faq.php`
ancla ahí el `FAQPage`. Se lo hace específico agregando texto después:
*Preguntas frecuentes sobre Elementor Pro*, no *Dudas sobre Elementor Pro*.

## Palabras que no se usan

sumergirse, aprovechar el poder de, solución integral, en la era digital,
cabe destacar, es importante mencionar, sin lugar a dudas, a modo de conclusión.

Ojo con las listas de palabras prohibidas: "panorama" es relleno en "el panorama
de la ciberseguridad actual" y es la palabra correcta en "panorama de las
herramientas de pentesting". Lo mismo con "robusto" o "en resumen". La regla es
el uso, no la palabra: si aporta precisión, se queda.

## Largo

| Tipo | Objetivo |
|---|---|
| Noticia | 1.100 – 1.500 palabras |
| Guía | 1.800 – 2.600 palabras |
| Comparativa | 1.500 – 2.200 palabras |
| Reseña | 1.800 – 2.500 palabras |

Son objetivos, no límites duros: un tema que necesita más, lleva más. Pero un
artículo que pasa el rango suele tener relleno, no profundidad.

## La prueba

Leer cada párrafo y preguntarse: **¿qué se lleva el lector de acá?** Si la
respuesta es "que el autor tiene una opinión" o "que ahora viene algo
importante", el párrafo sobra.
