# SEO y GEO — reglas comunes a todas las secciones

Lo que aplica a cualquier página del sitio: noticias, guías, comparativas,
reseñas, fichas de producto y hubs de categoría. Cada manual de sección agrega
lo suyo; nada de lo de acá se puede saltear.

**SEO** es aparecer en un buscador. **GEO** es que un modelo de lenguaje pueda
citar la página cuando alguien le pregunta. Las dos cosas premian lo mismo —un
dato concreto, atribuido y fácil de extraer— y castigan lo mismo: el párrafo
que rodea sin afirmar.

## La diferencia que importa

Un buscador manda tráfico a la página. Un modelo, casi nunca: lee, resume y
responde. Por eso la unidad que hay que optimizar **no es el artículo, es el
pasaje**. Cada bloque de 40 a 80 palabras tiene que poder arrancarse del
artículo y seguir siendo verdadero, entendible y atribuible.

La prueba, para cualquier párrafo: **copiado solo en un chat, ¿sirve?** Si
depende de haber leído lo anterior, no sirve.

De ahí salen casi todas las reglas que siguen.

## Lo que se mide antes de publicar

| Campo | Límite | Por qué |
|---|---|---|
| `meta_title` | ≤ 60 caracteres | Más largo, Google lo corta y reescribe el título |
| `meta_description` | 110 – 158 caracteres | Corta desperdicia espacio; larga se trunca |
| `title` | lleva la entidad completa | Es lo que la gente pega en el buscador |
| Enlaces internos | 6 – 12, anchor descriptivo | Menos deja la página aislada; más diluye |
| Diagramas | mínimo 1, 2 en noticias | Ver más abajo: es la parte que llega a `llms-full.txt` |

`php bin/staleness.php` reporta cada uno de estos incumplimientos por página, y
`php bin/audit.php` los vuelve a medir sobre el HTML servido. No hace falta
contar caracteres a mano.

## Los diagramas son GEO, no decoración

`llms-full.txt` aplana cada SVG a su `aria-label`. Es literalmente lo único del
diagrama que lee un modelo. Un `aria-label="gráfico de barras"` tira el dato a
la basura; `aria-label="Precio por usuario/mes: 1Password 7,99 USD, Dashlane
5,00 USD, Bitwarden 4,00 USD"` lo deja citable.

La regla: **la etiqueta lleva los números**. Si el diagrama muestra tres
valores, los tres van en la etiqueta.

Las plantillas probadas y las restricciones de ancho están en
`docs/noticias.md`, sección Diagramas. Valen igual para las otras secciones.

## Datos: fechados y atribuidos

Un dato sin fecha ni fuente no lo cita nadie y envejece sin que se note.

- ✗ *1Password cuesta USD 7,99 por usuario.*
- ✓ *1Password Business cuesta **USD 7,99 por usuario/mes**, según la
  [página de precios de 1Password](https://1password.com/business-pricing)
  consultada el 30 de agosto de 2026.*

Todo precio, porcentaje, versión y fecha lleva las tres cosas: **el número, de
dónde salió y cuándo se miró**. Un modelo que cita la segunda versión puede
decir de dónde viene; la primera es una afirmación huérfana.

Cuando un dato no se pudo confirmar, se dice. No se completa con lo que suena
razonable.

## Encabezados

La regla completa está en `docs/estilo.md`. Lo que hay que recordar acá es
**por qué** existe: un encabezado aparece sin contexto en el fragmento
destacado de Google y en la respuesta de un modelo. Si no se sostiene leído
solo, no sirve.

Lo prohibido: anáfora —"estas fallas", "el dato", "la única"— y encabezados
genéricos que servirían igual en cualquier otra página del sitio.
`bin/audit.php` marca los dos casos.

## La respuesta va arriba

El primer párrafo responde la pregunta del título. No lo prepara: lo responde.
Es lo que lee un modelo cuando decide si la página contesta la consulta, y es
el fragmento que Google levanta.

- ✗ *El backup de Microsoft 365 es un tema que genera muchas dudas.*
- ✓ *Microsoft no respalda tu correo de Microsoft 365 más allá de la papelera.
  El modelo de responsabilidad compartida deja el dato del lado del cliente,
  y eso está escrito en el contrato de servicio.*

## Preguntas frecuentes

Un bloque de preguntas frecuentes al final, con las preguntas **como las
escribe la gente**, no como las escribiría un manual. `core/Faq.php` las
convierte en `FAQPage`, que es de los pocos formatos que un modelo puede
levantar entero.

El encabezado tiene que empezar con las palabras "Preguntas frecuentes" —ahí
ancla `core/Faq.php`— y se hace específico agregando texto después: *Preguntas
frecuentes sobre 1Password Business*, no *Dudas comunes*.

Dentro de una respuesta de FAQ **no se meten diagramas ni bloques cercados**:
`core/Faq.php` los descarta y la respuesta queda coja.

## Enlazado interno

Cada página nueva o actualizada tiene que quedar conectada en las dos
direcciones:

- **Hacia afuera**: 6 a 12 enlaces a páginas del sitio que el tema pida de
  verdad. El anchor describe el destino —*[cómo configurar SPF, DKIM y
  DMARC](/guia/...)*—, nunca "hacé clic acá" ni la URL pelada.
- **Hacia adentro**: si la página es nueva, al menos otras dos páginas ya
  publicadas tienen que enlazarla. Una página que nadie enlaza no la
  encuentra ni el crawler ni el lector.

Los destinos se verifican contra `php bin/list-urls.php`. Un enlace interno
roto es peor que no ponerlo.

## Fechas

`updated:` es una promesa: dice que alguien miró la página ese día. Moverla sin
tocar el contenido es mentirle al buscador, y Google descuenta la señal cuando
detecta el patrón.

- Se toca el contenido → se mueve `updated:`.
- No se toca el contenido → **no** se mueve `updated:`.
- Se revisó y estaba todo bien → no se mueve nada. Revisar no es actualizar.

`php bin/check-updated.php` corre en CI y falla si un cambio de contenido no
movió la fecha.

## Canibalización

Dos páginas que responden la misma consulta compiten entre ellas y pierden las
dos. Antes de crear algo nuevo:

```bash
php bin/list-urls.php | grep -i <tema>
grep -ril "<entidad>" content/articles/
```

Si ya existe una página para esa consulta, **se mejora esa**, no se escribe otra.
Ampliar lo que ya rankea rinde más que sumar una página que le compite.

## Indexación

No hay que hacer nada a mano. Al pushear a `main`, el deploy ejecuta
`bin/indexnow.php`, que avisa a IndexNow (Bing, Yandex, Seznam, Naver) las URLs
que cambiaron. Google no acepta ping desde 2023: descubre por `sitemap.xml`,
que se regenera solo.

Lo único que puede romper esto es una fecha `updated:` que no se movió, porque
el sitemap declara un `lastmod` viejo para contenido nuevo.

## Lo que no se hace

- **Publicar para cumplir una frecuencia.** Vale igual acá que en noticias: si
  no hay nada que llegue al estándar, no se publica. Una página floja gasta
  presupuesto de rastreo y baja la calidad media del sitio.
- **Reescribir para "refrescar".** Cambiar palabras sin cambiar información no
  mejora nada y quema la señal de `updated:`.
- **Inventar experiencia propia.** Nadie probó, compró ni instaló nada, salvo
  en las reseñas del dueño, donde es real. En el resto del sitio está prohibido.
- **Rellenar para llegar a un largo.** El rango es un objetivo, no una cuota.
- **Palabras clave repetidas a propósito.** No sirve desde hace quince años.
