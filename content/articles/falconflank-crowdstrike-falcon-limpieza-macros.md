---
title: "FalconFlank: publican un exploit contra la limpieza de macros de CrowdStrike Falcon"
subtitle: No hay CVE, no hay explotación confirmada y CrowdStrike dice que está investigando. Pero ya publicó una mitigación concreta, y eso es lo que hay que decidir esta semana.
excerpt: Un investigador publicó código de prueba que abusa de la función de Falcon que elimina macros maliciosas de Office. Qué está confirmado, qué no, cuál es la mitigación del fabricante y qué se pierde al aplicarla.
type: news
status: published
category: antivirus-y-edr
author: ian-poletti-lucero
published: 2026-09-07
updated: 2026-09-07
products:
  - crowdstrike-falcon
meta_title: "FalconFlank: qué hacer con CrowdStrike Falcon"
meta_description: "Publican un exploit contra la limpieza de macros de CrowdStrike Falcon. Sin CVE ni explotación confirmada, pero con mitigación del fabricante. Qué hacer."
---

El 3 de septiembre un investigador que firma como **Chaotic Eclipse** publicó código de prueba, bautizado **FalconFlank**, que según él abusa de la función del **sensor de CrowdStrike Falcon** que elimina macros maliciosas de archivos de Office para **escalar privilegios** en un equipo Windows.

CrowdStrike respondió con una declaración breve, que dos coberturas independientes reproducen igual:

> Estamos investigando activamente estas afirmaciones y aconsejamos a los clientes deshabilitar la política de Windows "Microsoft Office File Suspicious Macro Removal".

Y agregó que **"los clientes siguen protegidos mediante la configuración de Cloud Anti-malware para archivos de Microsoft Office"**.

Eso es todo lo que hay: una afirmación con código público, una respuesta del fabricante y una mitigación. Es poco para entrar en pánico y suficiente para tomar una decisión el lunes.

## Qué está confirmado y qué no sobre FalconFlank

La honestidad sobre el estado de este caso vale más que cualquier análisis técnico, así que va primero:

| Afirmación | Estado |
|---|---|
| Existe código de prueba público | **Confirmado** — publicado el 3 de septiembre |
| CrowdStrike emitió una mitigación | **Confirmado** — deshabilitar esa política |
| Tiene CVE asignado | **No**, al 7 de septiembre |
| CrowdStrike confirmó la vulnerabilidad | **No** — dice que investiga las afirmaciones |
| Hay explotación en la vida real | **No se reportó ninguna** |
| Se conoce la causa raíz exacta | **No** — no está documentada públicamente |

Un fabricante que recomienda apagar una función mientras investiga está diciendo algo, aunque no lo diga con todas las letras. Pero recomendar una mitigación no es confirmar una vulnerabilidad, y no conviene reportarlo como si lo fuera.

```svg
<svg viewBox="0 0 660 200" role="img" aria-label="Estado de FalconFlank al 7 de septiembre de 2026: confirmado que existe código de prueba público desde el 3 de septiembre y que CrowdStrike emitió una mitigación. No confirmado: no hay CVE asignado, el fabricante no confirmó la vulnerabilidad sino que dice investigar, no se reportó explotación en la vida real y la causa raíz no está documentada">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Lo que se sabe y lo que no, al 7 de septiembre</text>
  <rect x="20" y="40" width="620" height="60" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="40" width="620" height="60" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="34" y="60" font-size="10.5" font-weight="700" fill="currentColor">Confirmado</text>
  <text x="34" y="79" font-size="10.5" fill="currentColor" opacity="0.85">Hay código de prueba público, y CrowdStrike emitió una mitigación</text>
  <text x="34" y="94" font-size="10" fill="currentColor" opacity="0.65">Deshabilitar la política de eliminación de macros sospechosas</text>
  <rect x="20" y="112" width="620" height="60" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="112" width="620" height="60" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="34" y="132" font-size="10.5" font-weight="700" fill="#e23a3a">Sin confirmar</text>
  <text x="34" y="151" font-size="10.5" fill="currentColor" opacity="0.85">Sin CVE, sin confirmación del fabricante, sin explotación reportada</text>
  <text x="34" y="166" font-size="10" fill="currentColor" opacity="0.65">La causa raíz exacta no está documentada públicamente</text>
  <text x="20" y="192" font-size="11.5" font-weight="600" fill="currentColor">Alcanza para actuar. No alcanza para llamarlo vulnerabilidad confirmada.</text>
</svg>
```

## Cómo sería el abuso de la limpieza de macros de Falcon

Sin causa raíz publicada, lo que se puede describir es la **clase** de problema, que es vieja y conocida.

Un EDR que elimina una macro maliciosa tiene que **borrar o modificar un archivo que está en la carpeta de un usuario**. Para eso corre con privilegios altos, porque tiene que poder tocar archivos de cualquiera. Ahí aparece el patrón: **un proceso privilegiado operando sobre una ruta que un usuario sin privilegios controla**.

Si ese usuario puede cambiar, entre el momento en que el producto decide actuar y el momento en que actúa, a qué apunta esa ruta —con un enlace o un punto de reanálisis del sistema de archivos—, entonces la operación privilegiada termina ejecutándose sobre otro destino. Es la familia de fallas conocida como "de tiempo de comprobación a tiempo de uso", y el análisis público del código de prueba menciona justamente tuberías con nombre, puntos de reanálisis y operaciones con bibliotecas.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Clase de falla que describe FalconFlank: el sensor decide eliminar una macro sospechosa en la carpeta del usuario, corre con privilegios altos para poder hacerlo, y entre la decisión y la acción el usuario sin privilegios cambia a qué apunta esa ruta, de modo que la operación privilegiada termina actuando sobre otro destino">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">La clase de problema, sin entrar en el exploit</text>
  <rect x="20" y="48" width="192" height="82" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="48" width="192" height="82" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="116" y="74" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">1. El sensor decide</text>
  <text x="116" y="94" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">limpiar una macro en la</text>
  <text x="116" y="109" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">carpeta del usuario</text>
  <path d="M214 89 L232 89" stroke="currentColor" stroke-width="1.5" opacity="0.65"/>
  <rect x="234" y="48" width="192" height="82" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="234" y="48" width="192" height="82" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="330" y="74" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">2. La ruta cambia</text>
  <text x="330" y="94" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.85">el usuario la redirige entre</text>
  <text x="330" y="109" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.85">la decisión y la acción</text>
  <path d="M428 89 L446 89" stroke="currentColor" stroke-width="1.5" opacity="0.65"/>
  <rect x="448" y="48" width="200" height="82" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="448" y="48" width="200" height="82" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="548" y="74" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">3. Actúa el privilegio</text>
  <text x="548" y="94" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.85">la operación se ejecuta</text>
  <text x="548" y="109" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.85">sobre otro destino</text>
  <text x="340" y="160" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Todo lo que corre elevado y toca rutas del usuario es candidato a esto</text>
  <text x="340" y="182" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.75">Vale para un EDR, un instalador, un antivirus o una tarea programada</text>
</svg>
```

Un detalle que baja bastante la urgencia: según el análisis público, **el atacante necesita poder ejecutar código local en el equipo antes de intentar esto**. No es algo que llegue por internet ni por un correo: es un segundo paso, después de que algo ya salió mal.

Las pruebas se hicieron sobre **Windows 11 25H2 y Windows Server 2025**, con Falcon en configuración de protección "Phase 3 Optimal". No hay versiones de sensor confirmadas.

## La mitigación de CrowdStrike y lo que cuesta aplicarla

La recomendación es deshabilitar la política **"Microsoft Office File Suspicious Macro Removal"** en Windows. Conviene entender qué se apaga.

Esa política es la que hace que el sensor **borre del disco** la macro sospechosa. Apagarla no deja el equipo sin defensa: CrowdStrike afirma que la detección sigue funcionando por **Cloud Anti-malware para archivos de Office**. La diferencia es entre **detectar y bloquear** —que sigue— y **limpiar el archivo automáticamente** —que se suspende.

Para la mayoría de las empresas ese intercambio es aceptable por unos días: el archivo malicioso queda ahí pero bloqueado, en vez de desaparecer solo. Para quien depende de la limpieza automática porque nadie revisa las detecciones, el costo es más real, y conviene compensarlo mirando la consola.

## ¿Cómo detecto un intento de FalconFlank en mi red?

Sin causa raíz publicada no hay una firma exacta, pero sí hay un patrón que buscar en la consola de Falcon o en el registro de eventos:

1. **Eventos de remediación de macros de Office seguidos de creación de procesos inusual** en el mismo equipo y en la misma ventana de tiempo. Esa secuencia es la señal.
2. **Cambios de privilegio inmediatamente después** de una acción de remediación.
3. **Operaciones sospechosas con bibliotecas** asociadas a la actividad de remediación.

Que no aparezca nada no prueba mucho: no hay explotación reportada en la vida real, así que lo esperable es no encontrar nada.

## ¿A quién afecta FalconFlank?

- **A quien corre el sensor de CrowdStrike Falcon en Windows** con la política de eliminación de macros activada.
- Con la advertencia de siempre: hace falta que el atacante ya tenga ejecución de código en ese equipo.

## ¿Quién puede ignorar el aviso de FalconFlank?

- **Quien no usa CrowdStrike Falcon.** Es una función específica de ese producto; no aplica a [Microsoft Defender for Endpoint](/producto/microsoft-defender-for-endpoint-p2), [SentinelOne](/producto/sentinelone-singularity) ni a los antivirus de la [comparativa de Bitdefender, Kaspersky y ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset).
- **Quien ya tiene esa política deshabilitada.**
- **Quien no tiene usuarios que puedan ejecutar código en los equipos**, que en la práctica es casi nadie.

## Qué hacer con CrowdStrike Falcon, en orden

1. **Aplicá la mitigación del fabricante**: deshabilitar la política de eliminación de macros sospechosas, dejando activo Cloud Anti-malware para archivos de Office.
2. **Buscá el aviso técnico en el portal de soporte de CrowdStrike.** Publicaron uno específico sobre FalconFlank, y va a ser el primero en decir si hay parche o si la afirmación se descarta.
3. **Revisá quién puede ejecutar código en los equipos.** Es la precondición de esta y de casi cualquier escalada local. Usuarios sin privilegios de administrador local es la medida más barata que existe.
4. **No desinstales nada.** Cambiar de EDR por una afirmación sin CVE ni explotación es una reacción cara y probablemente equivocada; el criterio para elegir producto está en [la comparativa de seguridad de correo](/comparativa/comparativa-seguridad-email-pymes) y en la guía de EDR, no en una noticia de una semana.
5. **Volvé a mirar en una semana.** El estado de este caso puede cambiar en cualquier dirección: que aparezca un CVE y un parche, o que CrowdStrike descarte la afirmación.

## Por qué un EDR es también superficie de ataque

El caso deja una lección que sobrevive a cómo termine.

Un EDR es, por diseño, el software más privilegiado del equipo: ve todos los archivos, todos los procesos y toda la red, y puede borrar y matar cosas. Ese privilegio es lo que lo hace útil, y también lo que lo convierte en un objetivo valioso. **Cada función que actúa automáticamente sobre archivos del usuario es una superficie más.**

No es un argumento contra tener EDR — [cuándo se justifica sobre un antivirus](/guia/edr-o-antivirus-cuando-se-justifica-pyme) sigue teniendo la misma respuesta. Es un argumento a favor de tratarlo como a cualquier otro software crítico: mantenerlo al día, leer sus avisos y no asumir que lo que protege no puede fallar. Ya lo vimos cuando [Microsoft Defender dejó de escanear sin avisar](/noticia/defender-dejo-de-escanear-sin-aviso).

## Preguntas frecuentes sobre FalconFlank y CrowdStrike Falcon

### ¿Es una vulnerabilidad confirmada de CrowdStrike Falcon?
No al 7 de septiembre. Hay código de prueba público y una respuesta del fabricante que dice estar investigando las afirmaciones. No hay CVE asignado ni confirmación de la vulnerabilidad, y tampoco explotación reportada en la vida real.

### Si aplico la mitigación, ¿quedo desprotegido contra macros maliciosas?
No. Lo que se apaga es la eliminación automática del archivo. CrowdStrike afirma que la detección sigue activa mediante Cloud Anti-malware para archivos de Office. Se pierde la limpieza automática, no la detección.

### ¿Un atacante puede usar esto desde internet?
Según el análisis público, no. Hace falta que ya pueda ejecutar código en el equipo. Es una escalada de privilegios, es decir un segundo paso de una cadena, no una vía de entrada.

### ¿Conviene cambiar de EDR por esto?
No con la información disponible. Todos los productos de esta categoría corren con privilegios altos y todos han tenido fallas. Cambiar de proveedor tiene un costo alto y no elimina la clase de riesgo.

### ¿Por qué el investigador publicó el código si no hay parche?
No lo explicó públicamente en el material revisado, y no vamos a suponerlo. Lo que sí cambia para quien se defiende es el resultado: con código público disponible, la ventana entre la publicación y los primeros intentos se acorta, y eso vuelve más urgente aplicar la mitigación.

---

**Fuentes.** Declaración de CrowdStrike, reproducida de forma coincidente por [The Hacker News](https://thehackernews.com/2026/09/researcher-releases-falconflank-poc.html) y [SOCRadar](https://socradar.io/blog/falconflank-crowdstrike-falcon-0day-poc/), ambos del 3 de septiembre de 2026.

Consultadas el 7 de septiembre de 2026. **No pudimos leer el aviso técnico propio de CrowdStrike**: está en su portal de soporte, que requiere cuenta de cliente. La declaración del fabricante se cita como la reprodujeron esas dos coberturas, y el código de prueba no se enlaza ni se describe paso a paso. Para el vocabulario, [el glosario](/guia/glosario-ciberseguridad-pymes); la ficha del producto está en [CrowdStrike Falcon](/producto/crowdstrike-falcon).
