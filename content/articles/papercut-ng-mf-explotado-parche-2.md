---
title: "PaperCut NG/MF explotado: instalá el parche 2"
subtitle: PaperCut confirma incidentes en clientes. El parche de emergencia del 28 de agosto ya fue reemplazado por una segunda versión, y el fabricante pide instalarla en lugar de la primera.
excerpt: Dos fallas encadenadas en PaperCut NG y MF se están explotando. Hay parche de emergencia, y quien instaló el primero tiene que instalar el segundo.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-08-29
updated: 2026-08-29
products:
  - acronis-cyber-protect
  - veeam-data-platform
  - cloudflare-access
  - twingate
meta_title: "PaperCut NG/MF explotado: instalá el parche 2"
meta_description: "PaperCut confirma explotación activa en NG y MF. Qué revisar en server.log, por qué el primer parche no alcanza y qué hacer si no podés parchear."
---

PaperCut publicó el 27 de agosto un aviso urgente por explotación activa de su servidor de administración de impresión, y lo dice sin rodeos: *"We are aware of confirmed customer incidents and are treating this matter with the highest priority."* Hay incidentes confirmados en clientes.

PaperCut NG y PaperCut MF son el software que controla quién imprime qué en oficinas, escuelas, universidades y organismos. El servidor suele estar publicado para que la gente imprima desde afuera, y corre en la red interna con acceso a un directorio de usuarios.

| Dato | CVE-2026-81578 | CVE-2026-82078 |
|---|---|---|
| Qué es | Control de acceso indebido en la interfaz de administración | Carga dinámica de clases sin validar |
| Puntaje | 8.8, alto | 9.4, crítico |
| Escala y autor | CVSS 4.0, asignado por PaperCut | CVSS 4.0, asignado por PaperCut |
| Privilegios que exige | Ninguno (`PR:N`) | Altos (`PR:H`) |
| Tipo | CWE-305 | CWE-470 |
| Publicadas en NVD | 28 de agosto, estado *Received* | Ídem |
| En el catálogo de CISA | No, al 29 de agosto | No, al 29 de agosto |

Los dos puntajes están en la escala CVSS 4.0 y los asignó PaperCut como autoridad de su propio producto. NVD todavía no las analizó.

---

## Cómo se encadenan CVE-2026-81578 y CVE-2026-82078 en PaperCut

Por separado, ninguna de las dos alcanza. Juntas, sí, y las descripciones oficiales lo dicen casi de manera literal.

La primera permite que **peticiones remotas sin autenticar** disparen acciones administrativas antes de que termine la validación de acceso. El resultado, según la descripción de PaperCut: *"This allows an unauthenticated remote attacker to modify certain system configurations."* Modificar configuración, nada más.

La segunda instancia controladores de base de datos a partir de un nombre configurable, sin contrastarlo contra una lista de permitidos. Su condición de entrada, textual: *"If an attacker can manipulate system configuration parameters, this enables the execution of arbitrary Java bytecode... under the security context of the PaperCut server process."*

Lo que la segunda necesita es exactamente lo que la primera entrega.

```svg
<svg viewBox="0 0 680 190" role="img" aria-label="Cadena de las dos fallas de PaperCut: CVE-2026-81578 permite a un atacante sin autenticar modificar la configuración del sistema; esa configuración manipulada es la condición de entrada de CVE-2026-82078, que exige privilegios altos y permite ejecutar bytecode Java arbitrario con el contexto de seguridad del proceso del servidor PaperCut">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Cada falla aporta lo que a la otra le falta</text>

  <rect x="16" y="48" width="150" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="16" y="48" width="150" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="91" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-81578</text>
  <text x="91" y="93" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">sin autenticarse</text>
  <text x="91" y="109" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">solo cambia config</text>
  <path d="M170 86 L196 86" stroke="#e23a3a" stroke-width="1.4"/>

  <rect x="202" y="48" width="150" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="202" y="48" width="150" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="277" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Configuración</text>
  <text x="277" y="93" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">manipulada: el</text>
  <text x="277" y="109" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">nombre del driver</text>
  <path d="M356 86 L382 86" stroke="#e23a3a" stroke-width="1.4"/>

  <rect x="388" y="48" width="150" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="388" y="48" width="150" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="463" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-82078</text>
  <text x="463" y="93" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">exige privilegios</text>
  <text x="463" y="109" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">que ya tiene</text>
  <path d="M542 86 L554 86" stroke="#e23a3a" stroke-width="1.4"/>

  <rect x="560" y="48" width="104" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="560" y="48" width="104" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="612" y="79" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">Código</text>
  <text x="612" y="99" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">en el servidor</text>

  <text x="340" y="164" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Mirar los puntajes por separado subestima lo que pasa cuando se encadenan</text>
</svg>
```

Ahí está el motivo por el que el 9.4 exige privilegios altos y aun así el conjunto se explota desde internet: los privilegios se los da la otra falla.

---

## Por qué el primer parche de emergencia de PaperCut no alcanza

La respuesta del fabricante fue rápida y todavía se está moviendo, y eso tiene una consecuencia práctica: **importa cuál de los dos parches instalaste**.

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Cronología de la respuesta de PaperCut: el 27 de agosto publica el aviso urgente por explotación activa; el 28 de agosto a las 2:10 de la madrugada hora de Australia publica el parche de emergencia para las versiones 25 y 26; el mismo 28 a las 22:08 publica el Parche de Emergencia Release 2 para las versiones 24, 25 y 26 con endurecimiento adicional; el 29 de agosto reporta problemas posteriores al parche en la búsqueda de tarjetas por base de datos externa y en SAML">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Dos parches en 48 horas, y el segundo reemplaza al primero</text>

  <rect x="330" y="106" width="290" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M40 120 L650 120" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="80" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">27 de agosto</text>
  <text x="80" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">aviso urgente</text>
  <path d="M80 84 L80 112" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="80" cy="120" r="5" fill="#e23a3a"/>

  <circle cx="330" cy="120" r="5" fill="currentColor"/>
  <path d="M330 128 L330 138" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="330" y="152" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">28 de agosto, 02:10</text>
  <text x="330" y="168" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">parche 1: solo v25 y v26</text>

  <text x="480" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">28 de agosto, 22:08</text>
  <text x="480" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">Release 2: v24, v25 y v26</text>
  <path d="M480 84 L480 112" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="480" cy="120" r="5" fill="#e23a3a"/>

  <circle cx="620" cy="120" r="5" fill="currentColor"/>
  <path d="M620 128 L620 138" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="650" y="152" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">29 de agosto</text>
  <text x="650" y="168" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">reportes post-parche</text>

  <text x="20" y="204" font-size="11.5" font-weight="600" fill="currentColor">Horarios de Australia oriental, tal como los publica el fabricante</text>
</svg>
```

El fabricante lo dice así: *"An updated Emergency Patch (Release 2) is now available for v24, v25, and v26, including additional hardening. We recommend all customers install Release 2 in place of the original emergency patch."*

Conviene marcar la diferencia entre esa redacción y la de terceros. PaperCut **recomienda** instalar Release 2 en lugar del original; el análisis de [Rapid7](https://www.rapid7.com/blog/post/etr-papercut-ng-mf-critical-zero-day-exploited-in-the-wild/) es más tajante y sostiene que quien aplicó la primera versión no está protegido. No se puede confirmar la formulación fuerte contra el aviso del fabricante. En la práctica los dos caminos terminan igual: hay que instalar Release 2.

Y hay una aclaración del propio fabricante que cambia el orden de las cosas. En sus preguntas frecuentes: *"Is this an official release? No. We have not gone through our usual release process. This is an emergency patch for customers with public-facing PaperCut servers who are unable to take other mitigating action."* No es una versión oficial, no pasó por el proceso de publicación habitual, y está pensada para quien tiene el servidor expuesto y no puede hacer otra cosa.

Coherente con eso, PaperCut informó el 29 de agosto que recibió reportes de que la búsqueda de números de tarjeta contra base de datos externa y SAML **dejaron de funcionar como se esperaba después de aplicar el parche**. Lo está investigando.

---

## ¿A quién afecta el 0-day de PaperCut NG y MF?

A cualquier organización con un servidor PaperCut NG o PaperCut MF propio. Los parches cubren las ramas v24, v25 y v26; Rapid7 cita el aviso diciendo que se considera potencialmente afectada toda versión de ambos productos.

El riesgo se concentra en un caso concreto: **el servidor accesible desde internet**. Es más común de lo que parece, porque publicar la interfaz web es la forma sencilla de que alguien mande a imprimir desde su casa o desde una sede.

En la región el perfil típico no es una empresa grande: son escuelas y universidades que cobran la impresión por hoja, estudios contables, cooperativas y municipios. Todos con un servidor que hace una cosa sola, funciona hace años y no está en el radar de nadie.

## ¿Quién puede ignorar el aviso de PaperCut?

Quien no corra PaperCut. Es software específico de administración de impresión: si en tu empresa las impresoras están conectadas y listo, sin servidor de cuotas ni de seguimiento, esto no aplica.

Tampoco es urgente del mismo modo si el servidor **solo responde dentro de la red interna** y no hay forma de alcanzarlo desde afuera. Sigue habiendo que parchear, pero la ventana es distinta y podés hacerlo con la calma de una ventana de mantenimiento en lugar de un sábado.

## ¿Cómo sé si mi servidor PaperCut fue comprometido?

El fabricante publicó indicadores preliminares. En el registro `server.log` del servidor de aplicaciones, buscá estas dos entradas:

```
ERROR No suitable driver found for jdbc:no:x
ERROR DatabaseUtils - Database error looking up cardID: VALUES CAST
```

Además, tres señales que no son entradas de registro:

- **Archivos `server.log` faltantes, truncados de manera inesperada o borrados.** Un registro que desaparece es un dato, no un accidente.
- **Alertas de tu antivirus o de la red** que involucren al servidor de aplicaciones de PaperCut, en particular actividad posterior a la explotación desde `pc-app.exe`.
- **Fallas nuevas en funciones que antes andaban**, aunque acá hay que descartar primero el efecto del propio parche, que el fabricante ya reconoció.

PaperCut agrega una advertencia que conviene tomar en serio: *"The absence of the above indicators is not confirmation that a system has not been affected."* La ausencia de indicadores no confirma que el sistema esté limpio, y la lista es preliminar.

## ¿Qué hago si tengo PaperCut NG o MF en mi red?

1. **Cerrá el acceso desde internet, hoy y antes que nada.** Es la primera indicación del fabricante, textual: restringir el acceso web a direcciones IP de confianza con reglas de firewall o controles de red, *"even if you have not observed suspicious activity"*. Es lo que corta la exposición sin depender de un parche que todavía se está ajustando.
2. **Instalá el Parche de Emergencia Release 2.** Si ya habías aplicado el primero, este lo reemplaza. Si estás en v24, el primero nunca te cubrió.
3. **Anotá que no es una versión oficial.** Cuando salga la versión formal, va a haber que actualizar de nuevo. Dejá el aviso del fabricante en seguimiento: se actualizó tres veces solo el 29 de agosto.
4. **Revisá los indicadores del punto anterior**, y guardá una copia de los registros antes de tocar nada. Si el servidor fue comprometido, esos archivos son la única reconstrucción posible.
5. **Si encontrás señales, seguí la guía del fabricante.** PaperCut recomienda asegurar las copias actuales, **borrar y reconstruir el servidor de aplicaciones por completo** y restaurar una copia anterior a cualquier comportamiento sospechoso. Que eso sea viable depende de tener copias que restauren de verdad: el orden para armarlas está en [copias de seguridad y recuperación](/productos/backup-y-recuperacion).
6. **Sacá de internet lo que no necesita estar.** Si el motivo por el que el servidor está publicado es que alguien imprima desde afuera, un acceso por identidad como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate) resuelve eso sin dejar la interfaz de administración a la vista; el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).

El patrón se repite: [Zimbra, TrueConf y MLflow](/noticia/zimbra-trueconf-software-autoalojado-parche-propio) y [ownCloud](/noticia/owncloud-cve-2023-49105-plazo-tres-dias) son el mismo caso, software propio que se instaló una vez y quedó publicado. Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre el 0-day de PaperCut

### ¿Es la primera vez que atacan a PaperCut?

No, y por eso conviene tratarlo con seriedad. PaperCut ya tiene tres entradas en el catálogo de vulnerabilidades explotadas de CISA: CVE-2023-27350 desde abril de 2023, CVE-2023-2533 desde julio de 2025 y CVE-2023-27351 desde abril de 2026. La de 2023 fue usada por grupos de ransomware. Un servidor de impresión publicado es un objetivo conocido.

```svg
<svg viewBox="0 0 660 190" role="img" aria-label="PaperCut ya tiene tres entradas previas en el catálogo de vulnerabilidades explotadas de CISA: CVE-2023-27350 agregada en abril de 2023, CVE-2023-2533 en julio de 2025 y CVE-2023-27351 en abril de 2026. Las dos fallas de agosto de 2026 todavía no figuran en el catálogo">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">PaperCut en el catálogo de explotación activa de CISA</text>

  <path d="M40 110 L620 110" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <circle cx="90" cy="110" r="5" fill="currentColor"/>
  <text x="90" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">abr 2023</text>
  <text x="90" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CVE-2023-27350</text>
  <path d="M90 84 L90 102" stroke="currentColor" stroke-width="1" opacity="0.4"/>

  <circle cx="290" cy="110" r="5" fill="currentColor"/>
  <path d="M290 118 L290 130" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="290" y="144" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">jul 2025</text>
  <text x="290" y="160" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CVE-2023-2533</text>

  <circle cx="470" cy="110" r="5" fill="currentColor"/>
  <text x="470" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">abr 2026</text>
  <text x="470" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CVE-2023-27351</text>
  <path d="M470 84 L470 102" stroke="currentColor" stroke-width="1" opacity="0.4"/>

  <circle cx="600" cy="110" r="5" fill="#e23a3a"/>
  <path d="M600 118 L600 130" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <text x="650" y="144" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">ago 2026</text>
  <text x="650" y="160" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">todavía sin figurar</text>

  <text x="20" y="184" font-size="11.5" font-weight="600" fill="currentColor">Tres entradas previas en tres años, y las dos de agosto aún no están</text>
</svg>
```

### Si las fallas no están en el catálogo de CISA, ¿es menos grave?

No. El catálogo registra explotación confirmada por la agencia, y ese trámite lleva días. Acá el fabricante ya declaró incidentes confirmados en sus propios clientes, que es una fuente más directa y más rápida que el catálogo. Esperar a que aparezca ahí sería usar el indicador más lento disponible.

### ¿Puedo aplicar solo el bloqueo por IP y dejar el parche para después?

Es exactamente lo que propone el fabricante como primer paso, y para un servidor que nunca necesitó estar publicado puede ser la solución definitiva. Con una salvedad: la restricción por IP no protege de alguien que ya esté dentro de la red, y tampoco de un equipo comprometido en la oficina. Sirve para cerrar la puerta principal, no para dar el tema por terminado.

### Apliqué el parche y se rompió el inicio de sesión con SAML. ¿Es normal?

Es un problema conocido. El 29 de agosto PaperCut informó reportes de que la búsqueda de números de tarjeta contra base de datos externa y SAML no funcionan como se esperaba después de aplicar el parche, y dijo que lo está investigando. Revertir el parche para recuperar esas funciones deja el servidor expuesto de nuevo: la combinación razonable mientras tanto es mantener el parche y cerrar el acceso desde internet.

### Un cliente me pregunta si estamos afectados. ¿Qué le respondo?

Con cuatro datos: si corrés PaperCut y en qué versión, si el servidor está o estuvo accesible desde internet, qué parche tenés instalado —primero o Release 2— y el resultado de revisar los indicadores en `server.log`. Si no corrés PaperCut, la respuesta es que el aviso no aplica. El formato para ese tipo de respuestas está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
