---
title: "Diez fallas explotadas en agosto y el patrón que las une"
subtitle: Casi ninguna está en la computadora de tu gente. Están en el software que administra la red, y buena parte no la parcheás vos.
excerpt: De las diez vulnerabilidades que CISA confirmó como explotadas en agosto, la mayoría está en la capa de administración. Qué significa para una PyME y qué preguntarle a tu proveedor.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-17
updated: 2026-08-17
products:
  - microsoft-defender-for-endpoint-p2
  - cloudflare-access
  - twingate
  - veeam-data-platform
meta_title: "10 fallas explotadas en agosto: el patrón que las une"
meta_description: "CISA confirmó diez vulnerabilidades explotadas en agosto. La mayoría está en la capa de administración, no en el endpoint. Qué hacer y qué preguntar."
---

El [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) sumó **diez entradas entre el 1 y el 17 de agosto de 2026**. Ese catálogo no lista fallas peligrosas en abstracto: lista las que hay evidencia de que **se están usando ahora mismo** contra sistemas reales.

Una por una son diez avisos sueltos. Juntas muestran un patrón: **casi ninguna está en la computadora de tu gente**.

| Agregada | CVE | Producto | Qué es |
|---|---|---|---|
| 3 ago | [CVE-2026-18577](https://nvd.nist.gov/vuln/detail/CVE-2026-18577) | N-able N-central | Bypass de autenticación |
| 4 ago | [CVE-2026-18556](https://nvd.nist.gov/vuln/detail/CVE-2026-18556) | N-able N-central | Bypass de autenticación |
| 4 ago | [CVE-2026-34486](https://nvd.nist.gov/vuln/detail/CVE-2026-34486) | Apache Tomcat | Datos sensibles sin cifrar |
| 4 ago | [CVE-2026-9198](https://nvd.nist.gov/vuln/detail/CVE-2026-9198) | IBM Langflow | Inyección de código |
| 5 ago | [CVE-2026-63077](https://nvd.nist.gov/vuln/detail/CVE-2026-63077) | JetBrains TeamCity | Ejecución remota sin autenticar |
| 7 ago | [CVE-2026-8037](https://nvd.nist.gov/vuln/detail/CVE-2026-8037) | Progress LoadMaster | Inyección de comandos |
| 11 ago | [CVE-2026-20349](https://nvd.nist.gov/vuln/detail/CVE-2026-20349) | Cisco ASA y FTD | Falla en el VPN SSL de acceso remoto |
| 11 ago | [CVE-2026-68820](https://nvd.nist.gov/vuln/detail/CVE-2026-68820) | Windows (AFD para WinSock) | Elevación de privilegios local |
| 11 ago | [CVE-2026-72898](https://nvd.nist.gov/vuln/detail/CVE-2026-72898) | Metabase | Inyección SQL sin autenticar |
| 17 ago | [CVE-2025-62593](https://nvd.nist.gov/vuln/detail/CVE-2025-62593) | Ray | Inyección de código |

Herramienta de administración remota, servidor de aplicaciones, plataforma de IA, servidor de integración continua, balanceador de carga, firewall de borde, tablero de datos, motor de cómputo distribuido. Y una sola de escritorio: la de Windows, que además es de elevación de privilegios, o sea que **solo sirve si el atacante ya entró**.

```svg
<svg viewBox="0 0 660 240" role="img" aria-label="Diagrama en capas: nueve de las diez vulnerabilidades explotadas en agosto están en la capa de administración e infraestructura, y solo una en el escritorio del usuario">
  <rect x="30" y="30" width="600" height="86" rx="6" fill="#e23a3a" opacity="0.1"/>
  <rect x="30" y="30" width="600" height="86" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="46" y="54" font-size="13" font-weight="700" fill="#e23a3a">Capa de administración e infraestructura</text>
  <text x="614" y="54" text-anchor="end" font-size="13" font-weight="700" fill="#e23a3a">9 de 10</text>
  <text x="46" y="78" font-size="11.5" fill="currentColor" opacity="0.85">N-central · Tomcat · Langflow · TeamCity · LoadMaster</text>
  <text x="46" y="98" font-size="11.5" fill="currentColor" opacity="0.85">Cisco ASA · Metabase · Ray</text>

  <rect x="30" y="140" width="600" height="66" rx="6" fill="currentColor" opacity="0.06"/>
  <rect x="30" y="140" width="600" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.5"/>
  <text x="46" y="164" font-size="13" font-weight="700" fill="currentColor">Escritorio del usuario</text>
  <text x="614" y="164" text-anchor="end" font-size="13" font-weight="700" fill="currentColor">1 de 10</text>
  <text x="46" y="188" font-size="11.5" fill="currentColor" opacity="0.85">Windows AFD — elevación local: solo sirve si el atacante ya entró</text>

  <text x="330" y="230" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.75">No se ataca el objetivo más duro: se ataca el que tiene permiso sobre él</text>
</svg>
```

---

## Por qué el atacante mira ahí

Es la misma lógica con la que razona cualquier equipo de [red team](/guia/red-teaming-que-es-cuando-contratarlo): no se ataca el objetivo más duro, se ataca el que tiene permiso sobre el objetivo más duro.

Tu notebook tiene antivirus, disco cifrado y un usuario sin privilegios. La consola que administra tu notebook, en cambio, tiene credenciales de administrador sobre todas las notebooks de la empresa, corre en un servidor que nadie mira, y muchas veces está publicada en internet porque el proveedor necesita llegar desde afuera.

Un solo bypass de autenticación ahí vale más que cien equipos comprometidos de a uno. Es exactamente lo que enseña cualquier [curso serio de pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos): el camino corto casi nunca es el frontal.

**El caso de N-able es el que mejor lo muestra.** N-central es software de administración remota que usan proveedores de servicios de IT para gestionar los equipos de sus clientes. Las dos entradas del catálogo son de bypass de autenticación, y la segunda tiene un detalle que la descripción de NVD dice con todas las letras: **CVE-2026-18577 es "un parche incompleto de CVE-2026-18556"**.

Traducido: se publicó un arreglo, el arreglo no arreglaba, y las dos terminaron en el catálogo de explotación activa con un día de diferencia. Si tu proveedor te dijo el 1 de agosto que ya había parcheado, esa afirmación quedó vencida el 3.

---

## El dato que no aparece en los titulares

Miren las puntuaciones de severidad de estas mismas fallas, según quién las asigne:

| CVE | Según el fabricante | Según NVD |
|---|---|---|
| CVE-2026-18556 | 8,2 | 7,4 |
| CVE-2026-18577 | 8,2 | 8,1 |
| CVE-2026-8037 | 9,6 | 9,8 |
| CVE-2026-68820 | 7,0 (Microsoft) | sin puntuación propia |

No son errores: son dos organismos evaluando el mismo problema con criterios distintos, y **CVSS mide la falla, no tu riesgo**. Una diferencia de casi un punto entero en CVE-2026-18556 cambia por completo si entra o no en la cola de "parchear esta semana" de una empresa que prioriza por número.

Por eso la regla práctica es otra: **si está en el catálogo de CISA, se parchea, sin discutir el número**. Ese catálogo no mide teoría, mide que ya lo están usando. Y los plazos que CISA le fija a los organismos federales lo dejan claro: para siete de estas diez, el plazo fue de **tres días**.

Si un organismo con equipo de seguridad tiene tres días, la pregunta interesante para una PyME no es cuánto tarda en parchear. Es quién parchea.

---

## A quién le toca de verdad

**Te toca directo si:**

- Tenés un **Cisco ASA o FTD** haciendo de firewall con VPN SSL de acceso remoto. Es el más común de esta lista en PyMEs de LATAM.
- Corrés **Metabase** para tableros de datos. La falla es inyección SQL sin autenticar en el endpoint de recuperación de contraseña, con puntuación 10,0, la máxima posible.
- Usás **TeamCity** para compilar y desplegar. Ejecución remota sin autenticar.
- Tenés **Windows**, que son todos: CVE-2026-68820 es elevación de privilegios local en el driver de WinSock. No sirve para entrar, sirve para pasar de "entré como usuario común" a "soy administrador del dominio".

**Te toca indirecto, que es peor porque no lo controlás:**

- Si tu soporte de IT es tercerizado, preguntá hoy si usan **N-able N-central** y qué versión. No es una pregunta agresiva: es la misma que te van a hacer a vos tus clientes grandes, y sobre eso escribimos la [guía para responder cuestionarios de seguridad de clientes](/guia/cuestionario-seguridad-cliente-como-responder).

**No te toca:**

- Si no corrés Langflow, Ray, Tomcat ni LoadMaster —y la mayoría de las PyMEs no—, esas cuatro son ruido para vos. Perseguir cada CVE que sale en las noticias es la forma más rápida de agotar a la única persona que se ocupa de esto.

---

## Cómo comprobar si estás expuesto

Todo lo de acá abajo es verificación desde tu lado, con tus credenciales. Nada de esto es una prueba de concepto.

**Firewall de borde.** Entrá al panel y mirá la versión exacta del sistema operativo del equipo, no la del contrato de soporte. Después buscá esa versión en el aviso oficial de Cisco. Si tenés el VPN SSL de acceso remoto habilitado y no lo usa nadie desde que migraron a otra cosa, apagalo: es la mitigación más barata que existe.

**Windows.** Que el parche de agosto esté aplicado en todos los equipos, no en el tuyo. Sin una consola central no tenés forma de saberlo, y ese es justamente el argumento de la [guía sobre cuándo se justifica el salto de antivirus a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme).

**Servicios publicados.** Hacé el inventario que casi nadie tiene: qué de lo tuyo responde desde internet. Paneles de administración, tableros, servidores de integración continua. Si algo de eso está publicado sin necesidad, sacarlo de internet vale más que parchearlo.

**Tu proveedor.** Tres preguntas, por escrito: qué herramienta de administración remota usan, en qué versión, y cuándo la actualizaron por última vez. La respuesta —o la falta de respuesta— te dice bastante.

---

## Qué hacer, en orden

1. **Parcheá lo que esté en la lista y corras.** Sin discutir el CVSS. Que esté en el catálogo de CISA significa que ya lo están usando.
2. **Sacá de internet lo que no necesita estar.** Un panel de administración accesible desde cualquier IP del mundo es una falla esperando el próximo CVE. Si necesitás acceso remoto, un modelo de acceso por identidad como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate) reemplaza al VPN publicado; el razonamiento completo está en la guía de [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).
3. **MFA en las consolas de administración, primero que en ningún otro lado.** Un bypass de autenticación es peor cuando del otro lado solo hay una contraseña. Si todavía no lo tenés, la [guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) es el camino corto, y [por qué el MFA solo no alcanza](/guia/ya-tenes-mfa-y-no-alcanza) explica por qué no todos los segundos factores resisten igual.
4. **Verificá que tus copias sobrevivan a un administrador comprometido.** Si quien controla la consola puede borrar los respaldos, no son respaldos. [Veeam Data Platform](/producto/veeam-data-platform) y el resto de la [categoría de backup y recuperación](/productos/backup-y-recuperacion) cubren el requisito de inmutabilidad.
5. **Escribí las tres preguntas a tu proveedor de IT.** Hoy, no el mes que viene.

Para la mayoría de las PyMEs de la región, el punto 2 rinde más que el 1. Parchear es una carrera que se corre todos los meses y se pierde algunas veces. Reducir lo que está publicado se hace una vez y baja el piso de riesgo de forma permanente.

Si estás armando el programa completo y no sabés en qué orden va cada cosa, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) ubica todo esto dentro del resto.

---

## Preguntas frecuentes

### ¿Qué es el catálogo KEV de CISA y por qué importa más que un CVSS alto?

Es la lista de vulnerabilidades sobre las que la agencia de ciberseguridad de Estados Unidos tiene evidencia de explotación activa. La diferencia con el CVSS es que el CVSS estima qué tan grave sería si alguien la usara; el catálogo confirma que alguien ya la está usando. Al 17 de agosto de 2026 acumula 1.666 entradas.

### Si mi proveedor de IT usa N-central, ¿estoy comprometido?

No necesariamente, y no hay que saltar a esa conclusión. Lo que sí corresponde es confirmar la versión y la fecha de actualización, con el detalle de que el primer parche resultó incompleto: NVD describe CVE-2026-18577 como un parche incompleto de CVE-2026-18556. Estar actualizado a principios de agosto no alcanza.

### ¿Por qué el fabricante y NVD dan puntuaciones distintas de la misma falla?

Porque son evaluaciones independientes con supuestos distintos sobre el contexto de explotación. En esta tanda las diferencias van de una décima a casi un punto entero. Por eso conviene decidir por presencia en el catálogo de explotación activa y por si el producto está publicado a internet, no por decimales.

### Una elevación de privilegios local, ¿es grave si el atacante no puede entrar?

Sola no sirve para entrar, y por eso suele bajar en la lista de prioridades. El problema es que casi nunca viene sola: se encadena con un phishing exitoso o una credencial robada, y es lo que convierte un acceso de usuario común en control total. La cadena completa es lo que importa, no cada eslabón por separado.
