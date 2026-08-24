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
esconde si no hay ninguna. No hay nada que activar.

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

## Diagramas

**Mínimo dos por nota**, y tres cuando el tema los pide. Un dato que se puede
dibujar entra mucho mejor dibujado, y son propios: se escriben a mano en un
bloque cercado con lenguaje `svg`, sin librerías ni imágenes externas.

Reglas que no cambian:

- **`currentColor` para trazos y texto**, así funciona en tema claro y oscuro.
  Un color fijo oscuro desaparece en modo oscuro. Para acentos, `#e23a3a`.
- **Siempre `viewBox` y `aria-label`.** La etiqueta describe el diagrama **con
  sus datos**, no "gráfico de barras": es lo que oye un lector de pantalla y es
  también lo único que llega a `llms-full.txt`, donde el SVG se aplana a esa
  etiqueta.
- **El ancho útil es 660-680.** Nada de texto más allá de x=650 ni por debajo
  del alto del `viewBox`, o se recorta.
- **`text-anchor="middle"` centra sobre el punto**, así que una etiqueta larga
  cerca del borde izquierdo se sale. Cerca de los bordes, anclar al inicio.
- El SVG pasa por la lista blanca de `core/Svg.php`. Si algo no se puede
  sanear, el bloque sale como código escapado en vez de romper la página.
- **Un diagrama que no aclara nada, no va.** Es para mostrar el mecanismo o el
  dato, nunca para decorar.

### Qué dibujar según el dato

| Si la nota tiene… | Va este diagrama |
|---|---|
| Porcentajes o magnitudes comparables | Barras horizontales |
| Una cronología: cuándo entró, cuándo lo detectaron | Línea de tiempo |
| Pasos encadenados de un ataque o un flujo | Cadena de cajas con flechas |
| Dos situaciones enfrentadas: con y sin, antes y después | Dos bloques apilados |
| Varios CVE con severidad y plazo distintos | Barras de severidad con la fecha de vencimiento |

### Plantillas

Están probadas y no se desbordan. Se cambian los textos y los valores.

**Barras horizontales.** Ancho de barra = porcentaje × 10.

````markdown
```svg
<svg viewBox="0 0 660 180" role="img" aria-label="Qué muestra, con los números">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Título</text>
  <text x="192" y="61" text-anchor="end" font-size="10.5" fill="currentColor">Etiqueta larga</text>
  <rect x="200" y="46" width="380" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="588" y="61" font-size="10.5" font-weight="700" fill="currentColor">38%</text>
  <text x="192" y="93" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">La que importa</text>
  <rect x="200" y="78" width="210" height="20" rx="3" fill="#e23a3a" opacity="0.65"/>
  <text x="418" y="93" font-size="10.5" font-weight="700" fill="#e23a3a">21%</text>
  <text x="20" y="150" font-size="11.5" font-weight="600" fill="#e23a3a">La conclusión, en una línea</text>
</svg>
```
````

**Cadena de pasos.** Cinco cajas de 118 de ancho en x = 16, 146, 276, 406, 536.

````markdown
```svg
<svg viewBox="0 0 680 190" role="img" aria-label="Qué muestra, paso por paso">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Título</text>
  <rect x="16" y="48" width="118" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="16" y="48" width="118" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="75" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Paso 1</text>
  <text x="75" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">qué deja</text>
  <path d="M136 86 L144 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>
  <text x="340" y="160" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Lo que el lector se lleva</text>
</svg>
```
````

**Línea de tiempo.** Eje en y=120; los hitos, círculos sobre el eje con la
etiqueta arriba o abajo alternada para que no choquen. Una banda `rect` detrás
marca el intervalo que importa.

**Dos bloques enfrentados.** Dos `rect` de 600×66 en y=26 e y=112, el segundo
con `stroke="#e23a3a"`, y una línea de cierre abajo con la diferencia.

### Antes de publicar el diagrama

Que ningún texto se salga del `viewBox`. Se mira a ojo comparando la `x` más
grande contra el ancho, y recordando que `text-anchor="middle"` reparte el
ancho del texto a los dos lados del punto.

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
su nombre genérico**:

| ✗ Nunca así | ✓ Así |
|---|---|
| A quién le toca | ¿A quién afecta la falla de Elementor Pro? |
| A quién NO le toca | ¿Quién puede ignorar este aviso? |
| Cómo comprobar exposición | ¿Cómo sé si mi TrueConf está expuesto a internet? |
| Qué hacer, en orden | ¿Qué hago si tengo Zimbra en mi servidor? |

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
