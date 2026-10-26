---
title: "CVSS en fallas explotadas: desde abril, 7 de cada 10 no tienen puntaje propio de NVD"
subtitle: Medición propia sobre 140 altas del catálogo KEV. NIST anunció en abril que dejaba de duplicar el puntaje del CNA, y en los datos se ve: de los CVE publicados desde entonces, solo el 28,8% tiene un puntaje calculado por NVD. El número que leés lo puso otro.
excerpt: Medimos 140 altas del catálogo de fallas explotadas de CISA. Desde la política de abril de 2026, el puntaje CVSS que ves casi siempre lo calculó el fabricante o CISA, no NVD.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-10-26
updated: 2026-10-26
products: []
meta_title: "CVSS: 7 de cada 10 sin puntaje propio de NVD"
meta_description: "Medición sobre 140 altas del catálogo KEV: desde abril de 2026 solo el 28,8% de los CVE nuevos tiene puntaje calculado por NVD. Quién pone el número."
---

Cuando leés que una falla "tiene 9,8" y decidís si trabajás el fin de semana, estás confiando en un número. Esta nota es sobre **quién calculó ese número**, y la respuesta cambió en abril de 2026.

**El dato corto:** de las altas del catálogo de fallas explotadas de CISA cuyos CVE se publicaron **desde el 15 de abril de 2026**, solo el **28,8%** tiene un puntaje calculado por NVD. De las publicadas antes de esa fecha, lo tiene el **66,7%**. Son 140 fichas medidas una por una el 26 de octubre.

**Lo que no es:** esto no es un incumplimiento ni un descuido. **Es la política anunciada**, y conviene decirlo antes que nada porque es fácil contarlo al revés. El mismo comunicado de NIST que puso a los CVE del catálogo KEV como máxima prioridad de enriquecimiento anunció también que NVD dejaría de poner su propio puntaje cuando el CNA ya puso uno.

**Lo que sí es, y por eso vale la nota:** el número que usás para priorizar lo produce, cada vez más, la parte interesada. Y la ficha sigue diciendo "Analyzed", que suena a que alguien lo revisó.

## Qué cambió en abril de 2026, en palabras de NIST

```svg
<svg viewBox="0 0 680 236" role="img" aria-label="Porcentaje de altas del catálogo KEV de CISA que tienen un puntaje CVSS calculado por NVD, según la fecha de publicación del CVE respecto del 15 de abril de 2026, cuando entró en vigencia la nueva política de NIST. Medición propia sobre 140 fichas de la API de NVD descargadas el 26 de octubre de 2026, correspondientes a las altas del KEV de enero a marzo de 2026 y de septiembre a octubre de 2026. Para los CVE publicados antes del 15 de abril de 2026: 54 de 81 fichas tienen puntaje propio de NVD, es decir el 66,7%. Para los CVE publicados el 15 de abril de 2026 o después: solo 17 de 59 fichas, es decir el 28,8%. La diferencia es de casi 38 puntos porcentuales. El corte se hace por fecha de publicación del CVE y no por fecha de alta en el catálogo, porque un CVE viejo que entra hoy al catálogo arrastra un puntaje que NVD calculó años antes bajo la política anterior">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Fichas con puntaje calculado por NVD</text>

  <text x="20" y="52" font-size="10" fill="currentColor" opacity="0.9">CVE publicados ANTES del 15-abr-2026</text>
  <rect x="20" y="60" width="413" height="22" rx="3" fill="currentColor" opacity="0.25"/>
  <text x="443" y="76" font-size="11" font-weight="700" fill="currentColor">66,7%</text>
  <text x="500" y="76" font-size="9.5" fill="currentColor" opacity="0.7">54 de 81 fichas</text>

  <text x="20" y="110" font-size="10" fill="currentColor" opacity="0.9">CVE publicados DESDE el 15-abr-2026</text>
  <rect x="20" y="118" width="178" height="22" rx="3" fill="#e23a3a" opacity="0.75"/>
  <text x="208" y="134" font-size="11" font-weight="700" fill="#e23a3a">28,8%</text>
  <text x="265" y="134" font-size="9.5" fill="currentColor" opacity="0.7">17 de 59 fichas</text>

  <line x1="20" y1="156" x2="640" y2="156" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="176" font-size="9.5" fill="currentColor" opacity="0.9">El corte va por fecha de PUBLICACIÓN del CVE, no por fecha de alta en el catálogo: un CVE</text>
  <text x="20" y="190" font-size="9.5" fill="currentColor" opacity="0.9">de 2015 que entra hoy al KEV arrastra un puntaje que NVD calculó con la política vieja.</text>

  <text x="20" y="214" font-size="9" fill="currentColor" opacity="0.65">Medición propia sobre 140 fichas de la API de NVD 2.0, altas del KEV de ene-mar y sep-oct de 2026,</text>
  <text x="20" y="226" font-size="9" fill="currentColor" opacity="0.65">descargadas el 26 de octubre de 2026.</text>
</svg>
```

El comunicado del **15 de abril de 2026** tiene dos partes que conviene no mezclar, porque juntas explican todo lo demás.

Una fija prioridades de enriquecimiento, y ahí el catálogo de fallas explotadas va primero: dice que la meta es enriquecer esas fichas dentro de **1 día hábil** desde su recepción —"within one business day of receipt"— y que "el atraso no incluye ningún CVE del catálogo KEV". Conviene leer eso como lo que dice: una meta, no un compromiso contractual, y el reloj arranca cuando NVD recibe el CVE, no cuando CISA lo suma al catálogo.

La otra se titula **"Streamlining Severity Scores"** y dice, textual: *"Hasta ahora, NIST ha provisto su propio puntaje de severidad para todos los CVE enviados, incluso si la Autoridad de Numeración de CVE que lo envió ya había provisto un puntaje de severidad. En adelante, no proveeremos de forma rutinaria un puntaje de severidad separado para esos CVE."*

Las dos cosas son del mismo documento, que lleva poco más de 6 meses en vigencia. Por eso **que una falla del catálogo no tenga puntaje de NVD no prueba que NVD la haya desatendido**: prueba que el CNA ya la había puntuado.

Y hay evidencia directa de que el enriquecimiento sí ocurre: de las 140 fichas, **139 están en estado "Analyzed"** —que es la etiqueta con la que NVD marca su proceso de enriquecimiento como completo— y **139 tienen declaraciones de aplicabilidad CPE**, que es trabajo propio de NVD. La única excepción es CVE-2026-110447, la falla de Collabora que [cubrimos el 24 de octubre](/noticia/collabora-online-nextcloud-cve-2026-110447-code-server), en estado "Deferred" —y que había entrado al catálogo 3 días antes de esta medición, así que no sirve como ejemplo de nada sistemático.

## Por qué NVD puntuó 17 fichas y se abstuvo en 42

```svg
<svg viewBox="0 0 680 244" role="img" aria-label="Mecanismo que explica cuándo NVD agrega su propio puntaje CVSS, medido sobre las 59 fichas cuyos CVE se publicaron el 15 de abril de 2026 o después. NVD agregó su propio puntaje en 17 de esas 59 fichas, y en 15 de esos 17 casos no existía ningún puntaje en la escala CVSS 3.x de un tercero: esos 15 tenían únicamente un puntaje en CVSS 4.0, de modo que NVD cubrió el hueco de la versión 3.1. NVD se abstuvo de puntuar en 42 fichas, y en 41 de esas 42 ya existía un puntaje CVSS 3.x aportado por un tercero, que es exactamente la política de no duplicar. La conclusión es que la presencia o ausencia del puntaje de NVD depende sobre todo de qué escala usó el CNA, y no del esfuerzo que NVD puso en esa ficha. Un dato adicional que refuerza la explicación: en las 140 fichas medidas NVD no emitió ni un solo puntaje en CVSS 4.0, cero casos; cuando puntúa, lo hace en CVSS 3.1, y en algunas fichas viejas en la escala retirada 2.0">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Depende de qué escala usó el CNA, no del esfuerzo</text>

  <rect x="20" y="36" width="620" height="62" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="54" font-size="10" font-weight="700" fill="currentColor">NVD SÍ puntuó: 17 de 59</text>
  <text x="34" y="71" font-size="9.5" fill="currentColor" opacity="0.9">En 15 de esos 17 no había ningún CVSS 3.x de un tercero. Esos mismos 15 tenían</text>
  <text x="34" y="86" font-size="9.5" fill="currentColor" opacity="0.9">solo un 4.0: NVD vino a cubrir el hueco del 3.1.</text>

  <rect x="20" y="110" width="620" height="48" rx="5" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="110" width="620" height="48" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="34" y="128" font-size="10" font-weight="700" fill="#e23a3a">NVD NO puntuó: 42 de 59</text>
  <text x="34" y="146" font-size="9.5" font-weight="700" fill="#e23a3a">En 41 de esas 42 ya había un CVSS 3.x de un tercero. Es la regla de no duplicar.</text>

  <line x1="20" y1="174" x2="640" y2="174" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="194" font-size="9.5" fill="currentColor" opacity="0.9">En las 140 fichas, NVD no emitió ni un puntaje en CVSS 4.0: 0 casos. Cuando puntúa, usa 3.1.</text>
  <text x="20" y="210" font-size="9.5" fill="currentColor" opacity="0.9">Así que un 4.0 del fabricante y un 3.1 de NVD no son el mismo número medido dos veces.</text>

  <text x="20" y="234" font-size="9" fill="currentColor" opacity="0.65">Medición propia sobre las 59 fichas publicadas desde el 15 de abril de 2026, al 26 de octubre.</text>
</svg>
```

Acá está lo que más me sorprendió al medirlo: **la regla es casi determinista**.

De las 59 fichas cuyos CVE se publicaron desde el 15 de abril, NVD agregó su propio puntaje en **17**. En **15 de esos 17** no existía ningún puntaje en la escala 3.x: tenían solamente un **CVSS 4.0** del tercero. O sea que NVD no apareció a dar una segunda opinión, apareció a **llenar el casillero del 3.1** que faltaba.

Y de las **42** en las que se abstuvo, en **41** ya había un 3.x de un tercero. Exactamente lo anunciado.

Hay un detalle que se desprende de eso y que vale para leer cualquier ficha: en las 140 que medí, **NVD no emitió ni un solo puntaje en CVSS 4.0**. Cero. Cuando puntúa, puntúa en 3.1. Así que cuando ves dos números distintos en una misma ficha, muchas veces no son dos mediciones del mismo hecho sino **dos escalas diferentes**, y compararlas no significa nada. Es el mismo punto que ya apareció con [los dos puntajes de F5 y Arista](/noticia/check-point-f5-arista-cuatro-criticas-mismo-dia) y con [los puntajes del kernel de Linux](/noticia/kernel-linux-tres-fallas-explotadas-puntajes).

## Quién puso el número cuando no lo puso NVD

```svg
<svg viewBox="0 0 680 258" role="img" aria-label="Quién aporta el único puntaje CVSS en las fichas del catálogo KEV que no tienen puntaje propio de NVD, medido sobre 140 fichas. De las 140, 69 no tienen puntaje calculado por NVD. De esas 69, en 56 el puntaje lo aporta el CNA de la propia ficha, es decir habitualmente el fabricante del producto. Y en 13 el único puntaje lo aporta CISA, la misma agencia que publica el catálogo de fallas explotadas, por dos mecanismos distintos. El primer mecanismo, 4 casos, es que CISA sea directamente el CNA del registro y además lo puntúe: son las cuatro fallas de Plex Media Server dadas de alta el 20 de octubre de 2026, cuyo CNA es la entidad CISA U.S. Civilian Government. El segundo mecanismo, 9 casos, es que el fabricante sea el CNA pero no publique ningún CVSS, y entonces CISA-ADP, que es un publicador de datos autorizado, agregue el puntaje: pasa con dos fallas de Apple, tres de Google incluidas dos de Chrome, dos de SonicWall, una de WordPress cuyo CNA es HackerOne y una de Synacor cuyo CNA es MITRE. Ninguna de las 140 fichas se queda sin ningún puntaje de nadie">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">69 fichas sin puntaje de NVD: quién lo puso</text>

  <text x="20" y="50" font-size="10" fill="currentColor" opacity="0.9">El CNA de la propia ficha (casi siempre, el fabricante)</text>
  <rect x="20" y="58" width="455" height="20" rx="3" fill="currentColor" opacity="0.25"/>
  <text x="485" y="73" font-size="10.5" font-weight="700" fill="currentColor">56</text>

  <text x="20" y="100" font-size="10" fill="currentColor" opacity="0.9">CISA, la misma agencia que publica el catálogo</text>
  <rect x="20" y="108" width="106" height="20" rx="3" fill="#e23a3a" opacity="0.75"/>
  <text x="136" y="123" font-size="10.5" font-weight="700" fill="#e23a3a">13</text>

  <line x1="20" y1="144" x2="640" y2="144" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="164" font-size="10" font-weight="700" fill="currentColor">Los 13 de CISA, por mecanismo:</text>
  <text x="38" y="183" font-size="9.5" fill="currentColor" opacity="0.9">4 — CISA es el CNA del registro y lo puntúa: las cuatro fallas de Plex del 20 de octubre.</text>
  <text x="38" y="199" font-size="9.5" fill="currentColor" opacity="0.9">9 — el fabricante es el CNA pero no publica CVSS, y CISA-ADP lo suple: Apple (2),</text>
  <text x="38" y="213" font-size="9.5" fill="currentColor" opacity="0.9">    Google (3, dos de Chrome), SonicWall (2), WordPress (1) y Synacor (1).</text>

  <rect x="20" y="224" width="620" height="18" rx="4" fill="currentColor" opacity="0.07"/>
  <text x="34" y="237" font-size="9.5" fill="currentColor" opacity="0.9">Ninguna de las 140 fichas se queda sin puntaje de nadie.</text>

  <text x="20" y="252" font-size="9" fill="currentColor" opacity="0.65">Identificadores de fuente resueltos contra la API de fuentes de NVD, el 26 de octubre de 2026.</text>
</svg>
```

De las 140 fichas, **69 no tienen puntaje de NVD**. En **56** lo puso el CNA de la ficha, que es lo que uno espera: el fabricante. Pero en **13** el único puntaje lo puso **CISA**, y eso merece separarse en dos mecanismos distintos porque no son lo mismo.

En **4 casos, CISA es directamente el CNA del registro** y además lo puntúa. Son las cuatro fallas de Plex Media Server del 20 de octubre: el identificador de fuente de esas fichas resuelve, contra la API de fuentes de NVD, a "Cybersecurity and Infrastructure Security Agency (CISA) U.S. Civilian Government".

**Acá me corrijo a mí mismo.** Cuando [cubrí esas cuatro fallas de Plex el 22 de octubre](/noticia/plex-media-server-cuatro-fallas-cadena-1-43-3-10861) escribí que "los cuatro puntajes los puso el CNA". Es literalmente cierto —CISA es el CNA de esos registros— pero cualquier lector entendió "Plex", y no fue Plex: los puso CISA. La frase no era falsa, era engañosa, y la precisión correcta es esta.

En los otros **9 casos** el fabricante sí es el CNA, pero **no publica ningún CVSS**, y entonces lo suple **CISA-ADP**, que es un publicador de datos autorizado. Pasa con dos fallas de Apple, tres de Google —dos de ellas de Chrome—, dos de SonicWall, una de WordPress cuyo CNA es HackerOne y una de Synacor cuyo CNA es MITRE.

Queda una ironía que conviene notar sin exagerarla: en esas 13 fichas, **la agencia que dice "esto se está explotando, parchen antes de tal fecha" es también la que pone el número de gravedad**. No hay nada impropio en eso —alguien tiene que poner un número y CISA tiene el contexto—, pero no es una segunda opinión independiente, y ahí está el punto de toda la nota.

## ¿A quién le cambia algo que NVD ya no calcule el puntaje?

**Si priorizás parches por el número**, te cambia el procedimiento. El 9,8 de una ficha reciente probablemente lo calculó el fabricante del producto, sin que nadie externo lo haya revisado. Eso no lo vuelve falso; lo vuelve **una sola fuente**.

**Si tenés que justificar una ventana de mantenimiento ante un jefe o un cliente**, te conviene saber de dónde sale el número que estás citando, porque la pregunta "¿quién dice que es 9,8?" ahora tiene una respuesta más interesante que "NVD". Cómo se contestan esas preguntas por escrito está en [cómo responder un cuestionario de seguridad de un cliente](/guia/cuestionario-seguridad-cliente-como-responder).

**Si comparás puntajes entre productos** para decidir qué atender primero, el riesgo es concreto: podés estar comparando un 4.0 del fabricante A contra un 3.1 de NVD del producto B. No son la misma vara.

**Si administrás pocos equipos y no vivís de esto**, la versión corta alcanza: el número orienta, no decide. Lo que decide es si el producto está expuesto y si hay explotación confirmada, que es el criterio que usa el propio catálogo. El encuadre general está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y los términos en [el glosario](/guia/glosario-ciberseguridad-pymes).

## ¿Quién puede ignorar quién calculó el puntaje CVSS?

Quien **no use el puntaje para decidir**. Si tu criterio es "está en el catálogo de explotadas y lo tengo instalado, entonces lo parcheo", el número es decorativo y esta nota no te cambia nada. Es, además, un criterio razonable.

Y quien trabaje con **fallas viejas**: las fichas publicadas hace más de 6 meses, antes del cambio, tienen puntaje de NVD en dos de cada tres casos, así que ahí la foto es la de siempre.

Lo que **no** corresponde concluir es que NVD dejó de trabajar, ni que los CVE del catálogo quedaron abandonados. Los datos dicen lo contrario: 139 de 140 fichas enriquecidas y con aplicabilidad CPE puesta por NVD.

## ¿Cómo sé quién calculó el puntaje de un CVE?

Está en la ficha, y se puede ver sin herramientas:

1. **En la web de NVD**, la sección de métricas CVSS muestra la fuente de cada puntaje. Si dice `nvd@nist.gov`, lo calculó NVD. Si dice el correo del fabricante —`psirt@arista.com`, `security@atlassian.com`, `productcert@siemens.com`— lo calculó el fabricante.
2. **En la API**, cada entrada de `metrics` trae un campo `source`. Ese campo es la respuesta. Un ejemplo que podés pegar en el navegador, cambiando el identificador: `https://services.nvd.nist.gov/rest/json/cves/2.0?cveId=CVE-2026-114625`.
3. **Si la fuente es un UUID** en lugar de un correo, se resuelve contra la API de fuentes de NVD, que devuelve el nombre de la organización. Es lo que hice para identificar a las dos entidades de CISA de esta nota.

Un detalle técnico para quien vaya a repetir la medición: en cada entrada de métricas hay también un campo `type`, con valores `Primary` o `Secondary`, y **es tentador usarlo para identificar a NVD. No sirve para eso**: `type` describe el rol de ese puntaje dentro de su propia versión de escala, no quién lo escribió. Un CNA puede figurar como `Primary` y NVD puede figurar como `Secondary`. Lo que identifica al autor es `source`, y nada más.

## ¿Qué hago distinto el lunes al leer un puntaje CVSS?

Tres cosas chicas, en orden de cuánto rinden:

1. **Antes de citar un puntaje, mirá el campo `source`.** Es un clic y cambia el peso de la frase: "el fabricante lo puntúa 9,8" no es lo mismo que "NVD lo puntúa 9,8".
2. **No compares números de escalas distintas.** Si uno es 4.0 y el otro 3.1, no hay comparación posible. Y si un fabricante publica los dos, usá el que coincida con la escala del otro producto que estás evaluando.
3. **No uses el puntaje como único criterio de prioridad.** Lo que mejor ordena el trabajo sigue siendo: está expuesto a internet, hay explotación confirmada, y qué pasa si cae ese equipo. Las notas de esta semana sobre [Plex](/noticia/plex-media-server-cuatro-fallas-cadena-1-43-3-10861), [Siemens Software Center](/noticia/siemens-software-center-cve-2026-110936-clickfix-dll) y [el orquestador de Arista](/noticia/arista-velocloud-orchestrator-cve-2026-114625-tercer-parche) son tres casos donde el puntaje era lo menos informativo del aviso.

Una aclaración sobre el método y sus límites, porque esta nota es una medición y conviene poder auditarla. **Universo:** las 140 altas del catálogo KEV de CISA de enero a marzo de 2026 y de septiembre a octubre de 2026 —una ventana de 3 meses bastante anterior al cambio y otra de 2 meses posterior—, con su ficha de la API de NVD 2.0 descargada el **26 de octubre de 2026**; las 140 tenían ficha legible. **Criterio:** un puntaje es "propio de NVD" si el campo `source` de la entrada CVSS es `nvd@nist.gov`; solo se miraron las claves `cvssMetricV*`, nunca `ssvcV203`, que no es un puntaje de severidad y en estas fichas viene siempre de CISA. **Corte:** por fecha de publicación del CVE frente al 15 de abril de 2026, no por fecha de alta en el catálogo. **Límites, los tres que más pesan:** el universo son dos ventanas y no una serie completa, así que no mide el momento exacto del cambio; con 59 y 81 fichas por grupo el margen de error es de varios puntos y no conviene leer un mes aislado como señal; y los campos `vulnStatus` y las métricas cambian con el tiempo, así que una ficha sin puntaje hoy puede tenerlo más adelante —NIST acepta pedidos de puntaje por correo—. **Lo que no pude leer:** la página de proceso de NVD que describe en detalle qué incluye el enriquecimiento devuelve una página vacía desde este entorno, así que el mecanismo que describo sale de la medición y del comunicado, no de esa página. Los dos textos de NIST que cito son del listado de novedades de NVD, consultado hoy.

---

## Preguntas frecuentes sobre el puntaje CVSS y NVD

### ¿Significa que NVD abandonó los CVE que se están explotando?

No, y los datos de esta medición dicen lo contrario. De las 140 fichas, **139 están en estado "Analyzed"**, que es la etiqueta con la que NVD marca su propio proceso de enriquecimiento como completo, y **139 tienen declaraciones de aplicabilidad CPE**, que las produce NVD. Lo que falta en la mayoría de las fichas nuevas es el puntaje, no el enriquecimiento. Y falta porque NIST anunció en abril de 2026 que no iba a duplicar un puntaje que el CNA ya había puesto.

### Entonces, ¿el puntaje del fabricante no sirve?

Sirve, y en general es el que mejor conoce el producto. El punto no es que esté mal, es que es **una sola fuente** y la parte interesada. Antes de abril de 2026 lo habitual era tener dos números calculados por separado sobre el mismo hecho; ahora, en las fichas nuevas, lo habitual es tener uno. Para el 28,8% de los casos donde NVD sí puntuó, vale la pena mirar si los dos números coinciden: cuando no coinciden, la diferencia te dice algo.

### ¿Por qué algunas fichas nuevas sí tienen puntaje de NVD?

Casi siempre porque faltaba el de la escala 3.1. De las 17 fichas publicadas desde abril que tienen puntaje de NVD, en **15** no había ningún CVSS 3.x de un tercero, y esos 15 tenían solo un CVSS 4.0. NVD vino a cubrir ese hueco. Visto al revés: de las 42 fichas en las que NVD no puntuó, en **41** ya existía un 3.x ajeno. La presencia del puntaje de NVD depende más de qué escala usó el CNA que de cualquier otra cosa.

### ¿Cómo veo quién puso el puntaje, sin instalar nada?

Mirá el campo `source` de cada métrica CVSS, que está tanto en la página web de NVD como en la respuesta de su API. Si dice `nvd@nist.gov`, es de NVD; si dice el correo de un fabricante, es del fabricante; si es un UUID, se resuelve contra la API de fuentes de NVD. Lo que **no** hay que usar es el campo `type`: sus valores `Primary` y `Secondary` describen el rol del puntaje dentro de su versión de escala, no el autor. Un CNA puede aparecer como `Primary` y NVD como `Secondary`.

### ¿Esto cambia cómo debería priorizar los parches?

Cambia el peso que le das al número, no el orden general. Lo que sigue ordenando mejor el trabajo es si el equipo está alcanzable desde internet, si hay explotación confirmada —que es justamente lo que significa estar en el catálogo de CISA— y qué se cae si ese equipo se cae. El puntaje es un insumo más, y ahora sabés que en las fichas nuevas suele venir de una sola fuente. Lo que conviene abandonar es comparar un 4.0 contra un 3.1 como si fueran la misma vara.
