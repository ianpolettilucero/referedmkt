---
title: "PaperCut NG/MF explotado: ya hay versión oficial"
subtitle: PaperCut publicó el 10 de septiembre las versiones 26.0.5, 25.0.13 y 24.1.10, que reemplazan a los tres parches de emergencia. CISA sumó las dos fallas a su catálogo de explotación activa el 31 de agosto.
excerpt: Dos fallas encadenadas en PaperCut NG y MF se están explotando. Ya hay versiones con QA completo que reemplazan a los tres parches de emergencia de agosto.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-08-29
updated: 2026-09-13
products:
  - acronis-cyber-protect
  - veeam-data-platform
  - cloudflare-access
  - twingate
meta_title: "PaperCut NG/MF explotado: ya hay versión oficial"
meta_description: "PaperCut publicó las versiones 26.0.5, 25.0.13 y 24.1.10, que reemplazan los parches de emergencia. Qué revisar en server.log y qué indicadores buscar."
---

PaperCut publicó el 27 de agosto un aviso urgente por explotación activa de su servidor de administración de impresión, y lo dice sin rodeos: *"We are aware of confirmed customer incidents and are treating this matter with the highest priority."* Hay incidentes confirmados en clientes.

> **Actualización del 13 de septiembre de 2026.** Desde que se publicó esta nota cambiaron las dos cosas que definen qué hacer. PaperCut publicó el 10 de septiembre las versiones de mantenimiento **26.0.5, 25.0.13 y 24.1.10**, que pasaron por el proceso de QA completo y **reemplazan a los tres parches de emergencia** de agosto: si tenés Release 1 o Release 2, actualizá ahora; si tenés Release 3, ya estás cubierto contra estas dos fallas y podés programar la actualización como una tarea normal. Y CISA agregó las dos fallas a su catálogo de explotación activa el **31 de agosto**, con plazo al **14 de septiembre** para los organismos federales de Estados Unidos. El resto de la nota conserva la cronología de agosto, porque explica por qué hubo tres parches en cinco días y por qué importa cuál tenés instalado.

PaperCut NG y PaperCut MF son el software que controla quién imprime qué en oficinas, escuelas, universidades y organismos. El servidor suele estar publicado para que la gente imprima desde afuera, y corre en la red interna con acceso a un directorio de usuarios.

| Dato | CVE-2026-81578 | CVE-2026-82078 |
|---|---|---|
| Qué es | Control de acceso indebido en la interfaz de administración | Carga dinámica de clases sin validar |
| Puntaje que asignó PaperCut | 8.8, alto, en CVSS 4.0 | 9.4, crítico, en CVSS 4.0 |
| Puntaje que asignó NVD | 9.8, crítico, en CVSS 3.1 | 9.1, crítico, en CVSS 3.1 |
| Privilegios que exige | Ninguno (`PR:N`) | Altos (`PR:H`) |
| Tipo | CWE-305 según PaperCut, CWE-306 según CISA | CWE-470 |
| Estado en NVD | *Analyzed* desde el 1 de septiembre | Ídem |
| En el catálogo de CISA | Sí, desde el 31 de agosto | Sí, desde el 31 de agosto |

Acá hay un dato que conviene leer despacio, porque los dos pares de puntajes **se ordenan al revés**. PaperCut califica a CVE-2026-81578 como la menos grave de las dos —8.8 contra 9.4— y NVD la califica como la más grave: 9.8 contra 9.1. No es un error de nadie: es lo que pasa cuando la misma falla se mide con dos escalas distintas.

La diferencia está en que CVSS 4.0 permite declarar por separado el impacto sobre el sistema vulnerable y sobre lo que viene después. PaperCut usa esa distinción y describe a CVE-2026-81578 como algo que modifica configuración y poco más (`VC:L/VI:H/VA:L`, sin impacto en sistemas subsiguientes). CVSS 3.1 no tiene ese matiz: trata la modificación de configuración como compromiso total de confidencialidad, integridad y disponibilidad, y al no exigir autenticación el puntaje se va a 9.8.

La lectura práctica es la de siempre en esta clase de desacuerdos: **el número no decide, el vector sí**. La falla que se puede disparar sin credenciales es la puerta, y es la que importa si el servidor está publicado, tenga 8.8 o 9.8. Cómo leer un vector CVSS está en el [glosario](/guia/glosario-ciberseguridad-pymes).

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

## Por qué importa cuál de los parches de PaperCut tenés instalado

La respuesta del fabricante fue rápida y se movió cuatro veces en dos semanas, y eso tiene una consecuencia práctica: **importa cuál de las cuatro versiones instalaste**.

```svg
<svg viewBox="0 0 680 220" role="img" aria-label="Cronología de la respuesta de PaperCut: el 27 de agosto publica el aviso urgente por explotación activa; el 28 de agosto a las 2:10 de la madrugada hora de Australia publica el parche de emergencia para las versiones 25 y 26; el mismo 28 a las 22:08 publica el Parche de Emergencia Release 2, que agrega la versión 24 y endurecimiento adicional; el 1 de septiembre a las 18:22 publica el Parche de Emergencia Release 3, que corrige la regresión de SAML y la de los drivers de SQL Server; el 10 de septiembre a las 14:00 publica las versiones de mantenimiento 26.0.5, 25.0.13 y 24.1.10, con QA completo, que reemplazan a los tres parches de emergencia. Del aviso a la versión oficial pasaron 14 días y los tres parches de emergencia salieron en los primeros cinco">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Tres parches de emergencia antes de la versión oficial</text>

  <rect x="150" y="106" width="340" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M40 120 L650 120" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="70" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">27 de agosto</text>
  <text x="70" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">aviso urgente</text>
  <path d="M70 84 L70 112" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="70" cy="120" r="5" fill="#e23a3a"/>

  <circle cx="200" cy="120" r="5" fill="currentColor"/>
  <path d="M200 128 L200 138" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="200" y="152" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">28 ago, 02:10</text>
  <text x="200" y="168" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">parche 1: v25 y v26</text>

  <text x="330" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">28 ago, 22:08</text>
  <text x="330" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">Release 2: suma v24</text>
  <path d="M330 84 L330 112" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="330" cy="120" r="5" fill="#e23a3a"/>

  <circle cx="460" cy="120" r="5" fill="#e23a3a"/>
  <path d="M460 128 L460 138" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <text x="460" y="152" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">1 sep, 18:22</text>
  <text x="460" y="168" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">Release 3: arregla SAML</text>

  <text x="650" y="60" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">10 sep, 14:00</text>
  <text x="650" y="76" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">26.0.5 / 25.0.13 / 24.1.10</text>
  <path d="M610 84 L610 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="610" cy="120" r="5" fill="currentColor"/>

  <text x="20" y="196" font-size="11.5" font-weight="600" fill="currentColor">Del aviso a la versión oficial, 14 días; los tres de emergencia, en los primeros cinco</text>
  <text x="20" y="212" font-size="10.5" fill="currentColor" opacity="0.75">Horarios de Australia oriental, tal como los publica el fabricante</text>
</svg>
```

El fabricante lo dice así: *"An updated Emergency Patch (Release 2) is now available for v24, v25, and v26, including additional hardening. We recommend all customers install Release 2 in place of the original emergency patch."*

Conviene marcar la diferencia entre esa redacción y la de terceros. PaperCut **recomienda** instalar Release 2 en lugar del original; el análisis de [Rapid7](https://www.rapid7.com/blog/post/etr-papercut-ng-mf-critical-zero-day-exploited-in-the-wild/) es más tajante y sostiene que quien aplicó la primera versión no está protegido. No se puede confirmar la formulación fuerte contra el aviso del fabricante. En la práctica, en ese momento los dos caminos terminaban en el mismo lugar: había que instalar Release 2. Hoy los dos terminan en la versión de mantenimiento.

Y hay una aclaración del propio fabricante que, en agosto, cambiaba el orden de las cosas. En sus preguntas frecuentes de entonces: *"Is this an official release? No. We have not gone through our usual release process. This is an emergency patch for customers with public-facing PaperCut servers who are unable to take other mitigating action."* No era una versión oficial, no había pasado por el proceso de publicación habitual, y estaba pensada para quien tenía el servidor expuesto y no podía hacer otra cosa.

Esa es, exactamente, la pregunta que hoy tiene la respuesta opuesta. En el aviso del 10 de septiembre la misma entrada dice *"Yes. Unlike the emergency patches, these are Regular Maintenance Releases (MR) that have gone through complete QA testing."* Las versiones 26.0.5, 25.0.13 y 24.1.10 tienen número de versión nuevo, notas de publicación y pasaron por el proceso completo.

Entre una respuesta y la otra pasó algo que la nota original no alcanzó a ver. El 1 de septiembre a las 18:22 PaperCut publicó un **Parche de Emergencia Release 3**, y corrigió justamente los dos problemas que habían quedado abiertos: los inicios de sesión con SAML y el soporte de los drivers viejos de Microsoft SQL Server para la búsqueda de tarjetas. El fabricante lo describe como acumulativo —no hace falta instalar los anteriores— y dice que agrega endurecimiento *"against potential attack chains"*.

Ese detalle de la numeración explica además una molestia concreta. Los parches de emergencia **declaraban el mismo número de versión que la build sin parchear** sobre la que se aplicaban, así que el propio aviso de seguridad dentro del producto no podía distinguir un servidor parcheado de uno que no lo estaba: seguía apareciendo igual. Las versiones de mantenimiento traen número nuevo, y con ellas el aviso desaparece. Si estás en un parche de emergencia y todavía ves el cartel, estás cubierto; el cartel se va cuando pasás a una versión de mantenimiento.

Hay un límite que conviene saber antes de planificar: **para la versión 23 y anteriores no hay parche ni versión de mantenimiento**. No los va a haber. Para llegar a una build corregida hay que subir a una rama soportada —24, 25 o 26—, y hasta que eso pase la restricción por IP deja de ser una mitigación temporal y pasa a ser la única defensa. PaperCut agrega un detalle que saca una excusa del camino: si la licencia está vencida o en trámite de renovación, ofrece emitir una licencia temporal de emergencia para que eso no demore la actualización.

---

## ¿A quién afecta el 0-day de PaperCut NG y MF?

A cualquier organización con un servidor PaperCut NG o PaperCut MF propio. Los parches cubren las ramas v24, v25 y v26; Rapid7 cita el aviso diciendo que se considera potencialmente afectada toda versión de ambos productos.

El riesgo se concentra en un caso concreto: **el servidor accesible desde internet**. Es más común de lo que parece, porque publicar la interfaz web es la forma sencilla de que alguien mande a imprimir desde su casa o desde una sede.

En la región el perfil típico no es una empresa grande: son escuelas y universidades que cobran la impresión por hoja, estudios contables, cooperativas y municipios. Todos con un servidor que hace una cosa sola, funciona hace años y no está en el radar de nadie.

## ¿Quién puede ignorar el aviso de PaperCut?

Quien no corra PaperCut. Es software específico de administración de impresión: si en tu empresa las impresoras están conectadas y listo, sin servidor de cuotas ni de seguimiento, esto no aplica.

Tampoco es urgente del mismo modo si el servidor **solo responde dentro de la red interna** y no hay forma de alcanzarlo desde afuera. Sigue habiendo que parchear, pero la ventana es distinta y podés hacerlo con la calma de una ventana de mantenimiento en lugar de un sábado.

## ¿Cómo sé si mi servidor PaperCut fue comprometido?

El fabricante publicó indicadores preliminares el 27 de agosto y los amplió el 30. En el registro `server.log` del servidor de aplicaciones, buscá estas entradas:

```
ERROR No suitable driver found for jdbc:no:x
ERROR DatabaseUtils - Database error looking up cardID: VALUES CAST
DB URL: jdbc:derby:memory:pwn;create=true
DB URL: jdbc:no:x DB Driver: <nombre aleatorio de 5 caracteres>
Database error looking up cardID: VALUES CAST(X'cafebabe
```

Esa última línea merece una nota, porque es el indicador más específico de todos. `cafebabe` son los cuatro bytes con los que arranca cualquier archivo `.class` de Java: es la firma del formato. Encontrarla dentro de una consulta a la base de datos de tarjetas significa que alguien empujó bytecode por un campo que esperaba un número de tarjeta.

En el disco, PaperCut señala tres archivos con el mismo patrón de nombre aleatorio de cinco caracteres:

```
<instalación>\server\lib\<5-caracteres>.class
<instalación>\server\data\content\<5-caracteres>.cmd
<instalación>\server\data\content\<5-caracteres>.out
```

Con una advertencia del fabricante que conviene leer entera: esos archivos **pueden haber sido borrados por el atacante** a medida que avanzaba, así que su ausencia no descarta nada.

Además, tres señales que no son entradas de registro:

- **Archivos `server.log` faltantes, truncados de manera inesperada o borrados.** Un registro que desaparece es un dato, no un accidente.
- **Alertas de tu antivirus o de la red** que involucren al servidor de aplicaciones de PaperCut, en particular actividad posterior a la explotación desde `pc-app.exe`.
- **Fallas nuevas en funciones que antes andaban**, aunque acá hay que descartar primero el efecto del propio parche, que el fabricante ya reconoció.

PaperCut agrega una advertencia que conviene tomar en serio: *"The absence of the above indicators is not confirmation that a system has not been affected."* La ausencia de indicadores no confirma que el sistema esté limpio, y la lista es preliminar.

## Qué hace el atacante en los 28 minutos posteriores a entrar

Este es el aporte más útil del aviso del 10 de septiembre, y es raro que un fabricante lo publique: PaperCut documentó la secuencia de comandos que observó después de la explotación, con los tiempos medidos desde el primer comando. No es un caso hipotético, es lo que pasó en clientes.

El proceso `pc-app` —el servidor de PaperCut— lanza procesos hijo de `cmd.exe`, y desde ahí:

| Tiempo transcurrido | Qué corre | Para qué |
|---|---|---|
| 00:00:00 | `whoami & ver` | Confirmar que ejecuta y con qué cuenta |
| 00:01:19 | `tasklist` | Ver qué protección hay instalada |
| 00:04:42 | `nltest /dclist:` | Buscar los controladores de dominio |
| 00:06:09 | `quser & dir c:\users` | Ver sesiones y usuarios del equipo |
| 00:16:07 | descarga de un ejecutable a `C:\ProgramData` | Traer herramienta propia |
| 00:19:27 | ejecución silenciosa de ese binario | Instalarla sin interacción |
| 00:21:29 | servicio **"Remote Access Service"** | Persistencia: agente SimpleHelp |
| 00:27:37 | descarga de AnyDesk | Segundo canal de acceso remoto |

Veintiocho minutos desde el primer comando hasta tener dos herramientas de acceso remoto instaladas. Y hay un detalle que vale más que el resto: **ninguno de esos comandos es un exploit**. Son comandos de administración normales, corriendo desde un proceso que no tiene por qué lanzar `cmd.exe` nunca. Ese es el punto de detección: no el comando, sino **quién lo llama**. Un servidor de impresión que abre una consola es la anomalía, independientemente de lo que escriba en ella.

De ahí salen dos búsquedas concretas, y son rápidas de hacer:

- **Un servicio de Windows llamado "Remote Access Service"** que ejecute `SimpleService.exe` desde `C:\ProgramData\JWrapper-Remote Access\JWAppsSharedConfig\restricted\`, corriendo como `LocalSystem` y con inicio automático. El nombre es deliberadamente aburrido: parece parte del sistema y no lo es.
- **Instalaciones de AnyDesk que nadie pidió.** Es una herramienta legítima, y por eso funciona como acceso persistente sin levantar sospechas.

En algunos casos la protección del endpoint cortó la cadena en el primer paso y aisló la máquina, lo que dice algo sobre dónde conviene invertir: el [antivirus de nueva generación](/productos/antivirus-y-edr) no evitó la explotación, pero sí evitó lo que venía después.

PaperCut también señala que hay indicadores publicados por terceros, entre ellos un análisis de GreyNoise, y aclara que **no los verificó de forma independiente** ni salen de reportes de sus clientes. Vale la misma cautela acá: son pistas para validar contra tu propio entorno, no listas para bloquear a ciegas.

Un dato más, del mismo aviso: los reportes de nuevos compromisos *"have slowed considerably over the past week"*, y la mayoría de los clientes ya tiene el servidor detrás de un firewall o en una build parcheada. Pero el fabricante advierte que los servidores que siguen publicados y sin parchear continúan siendo atacados, y que el comportamiento posterior al compromiso en esta segunda ola fue **más sofisticado** que en los primeros días.

## ¿Qué hago si tengo PaperCut NG o MF en mi red?

1. **Cerrá el acceso desde internet, hoy y antes que nada.** Es la primera indicación del fabricante, textual: restringir el acceso web a direcciones IP de confianza con reglas de firewall o controles de red, *"even if you have not observed suspicious activity"*. Es lo que corta la exposición sin depender de una ventana de mantenimiento.
2. **Actualizá a la versión de mantenimiento de tu rama: 26.0.5, 25.0.13 o 24.1.10.** Reemplazan a los tres parches de emergencia, se instalan con el procedimiento de actualización habitual y traen notas de publicación. Si estás en Release 1 o 2, esto es ahora; si estás en Release 3, ya estás cubierto y podés programarlo. Si estás en la versión 23 o anterior no hay build corregida y nunca va a haberla: el camino es subir a una rama soportada.
3. **Actualizá también los Site Servers y los servidores de impresión secundarios**, no solo el servidor de aplicaciones principal. Mobility Print, Print Deploy y el software de cliente —incluido el User Client— no están afectados y no hay que tocarlos.
4. **Revisá los indicadores del punto anterior**, y guardá una copia de los registros antes de tocar nada. Si el servidor fue comprometido, esos archivos son la única reconstrucción posible.
5. **Si encontrás señales, seguí la guía del fabricante.** PaperCut recomienda asegurar las copias actuales, **borrar y reconstruir el servidor de aplicaciones por completo** y restaurar una copia anterior a cualquier comportamiento sospechoso. Que eso sea viable depende de tener copias que restauren de verdad: el orden para armarlas está en [copias de seguridad y recuperación](/productos/backup-y-recuperacion).
6. **Sacá de internet lo que no necesita estar.** Si el motivo por el que el servidor está publicado es que alguien imprima desde afuera, un acceso por identidad como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate) resuelve eso sin dejar la interfaz de administración a la vista; el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).

El patrón se repite: [Zimbra, TrueConf y MLflow](/noticia/zimbra-trueconf-software-autoalojado-parche-propio) y [ownCloud](/noticia/owncloud-cve-2023-49105-plazo-tres-dias) son el mismo caso, software propio que se instaló una vez y quedó publicado. Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre el 0-day de PaperCut

### ¿Es la primera vez que atacan a PaperCut?

No, y por eso conviene tratarlo con seriedad. Con las dos de agosto, PaperCut acumula **cinco entradas** en el catálogo de vulnerabilidades explotadas de CISA: CVE-2023-27350 desde abril de 2023, CVE-2023-2533 desde julio de 2025, CVE-2023-27351 desde abril de 2026 y las dos de este episodio desde el 31 de agosto de 2026. Las dos de 2023 figuran con uso confirmado por grupos de ransomware. Un servidor de impresión publicado es un objetivo conocido, y este producto lo viene demostrando hace tres años.

```svg
<svg viewBox="0 0 660 190" role="img" aria-label="PaperCut acumula cinco entradas en el catálogo de vulnerabilidades explotadas de CISA: CVE-2023-27350 agregada en abril de 2023, CVE-2023-2533 en julio de 2025, CVE-2023-27351 en abril de 2026, y CVE-2026-81578 junto con CVE-2026-82078 agregadas el 31 de agosto de 2026 con plazo de corrección al 14 de septiembre">
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

  <circle cx="600" cy="110" r="7" fill="#e23a3a"/>
  <path d="M600 118 L600 130" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <text x="650" y="144" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">31 ago 2026</text>
  <text x="650" y="160" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">dos de una vez</text>

  <text x="20" y="184" font-size="11.5" font-weight="600" fill="currentColor">Cinco entradas en tres años; las dos últimas entraron juntas, con plazo al 14 de septiembre</text>
</svg>
```

### ¿Qué cambia que las fallas estén en el catálogo de CISA?

Entraron el 31 de agosto, cuatro días después del aviso del fabricante y dos días después de que se publicara esta nota, con plazo de corrección al 14 de septiembre para los organismos federales de Estados Unidos. Ese plazo no obliga a una PyME de la región, pero sirve como referencia de urgencia ajena: catorce días es lo que CISA consideró razonable.

Lo que muestra el orden de las fechas es que el catálogo **confirma, no descubre**. El 27 de agosto PaperCut ya declaraba incidentes confirmados en clientes; CISA llegó cuatro días más tarde. Quien esperó la entrada en el catálogo para empezar a moverse perdió esos cuatro días, y en este caso el aviso del fabricante era la señal más rápida y más directa disponible.

### ¿Puedo aplicar solo el bloqueo por IP y dejar el parche para después?

Es exactamente lo que propone el fabricante como primer paso, y para un servidor que nunca necesitó estar publicado puede ser la solución definitiva. Con una salvedad: la restricción por IP no protege de alguien que ya esté dentro de la red, y tampoco de un equipo comprometido en la oficina. Sirve para cerrar la puerta principal, no para dar el tema por terminado.

### Apliqué el parche y se rompió el inicio de sesión con SAML. ¿Es normal?

Era un problema conocido de los primeros parches, y ya está resuelto. El 29 de agosto PaperCut informó reportes de que la búsqueda de números de tarjeta contra base de datos externa y el inicio de sesión con SAML dejaban de funcionar después de aplicar el parche. El 1 de septiembre publicó el Parche de Emergencia Release 3, que corrige las dos regresiones, y el 10 de septiembre las versiones de mantenimiento, que las incluyen. Si seguís con SAML roto, estás en Release 1 o 2: la salida es actualizar hacia adelante, nunca revertir el parche, porque eso vuelve a exponer el servidor.

Queda un caso aparte: si usás SQL Server para la búsqueda de tarjetas con el viejo driver jTDS de Sourceforge, PaperCut recomienda pasar al driver JDBC de Microsoft soportado, y aclara que aun así puede no resolver todos los casos.

### Un cliente me pregunta si estamos afectados. ¿Qué le respondo?

Con cuatro datos: si corrés PaperCut y en qué versión, si el servidor está o estuvo accesible desde internet, qué parche tenés instalado —primero o Release 2— y el resultado de revisar los indicadores en `server.log`. Si no corrés PaperCut, la respuesta es que el aviso no aplica. El formato para ese tipo de respuestas está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
