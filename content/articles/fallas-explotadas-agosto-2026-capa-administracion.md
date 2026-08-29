---
title: "Diez fallas explotadas en agosto y el patrón que las une"
subtitle: Casi ninguna está en la computadora de tu gente. Están en el software que administra la red, y buena parte no la parcheás vos.
excerpt: De las diez vulnerabilidades que CISA confirmó como explotadas en agosto, la mayoría está en la capa de administración. Qué significa para una PyME y qué preguntarle a tu proveedor.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-17
updated: 2026-08-27
products:
  - microsoft-defender-for-endpoint-p2
  - cloudflare-access
  - twingate
  - veeam-data-platform
meta_title: "10 fallas explotadas en agosto: el patrón que las une"
meta_description: "CISA confirmó diez vulnerabilidades explotadas en agosto. La mayoría está en la capa de administración, no en el endpoint. Qué hacer y qué preguntar."
---

El [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) sumó **diez entradas entre el 1 y el 17 de agosto de 2026**. Ese catálogo no lista fallas peligrosas en abstracto: lista las que hay evidencia de que **se están usando ahora** contra sistemas reales.

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

Administración remota, servidor de aplicaciones, plataforma de IA, integración continua, balanceador de carga, firewall de borde, tablero de datos, cómputo distribuido. Una sola de escritorio: la de Windows, que además es de elevación de privilegios y **solo sirve si el atacante ya entró**.

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

## Por qué los atacantes apuntan a consolas como N-able N-central

Es la misma lógica con la que razona cualquier equipo de [red team](/guia/red-teaming-que-es-cuando-contratarlo): no se ataca el objetivo más duro, se ataca el que tiene permiso sobre el objetivo más duro.

Tu notebook tiene antivirus, disco cifrado y un usuario sin privilegios. La consola que la administra tiene credenciales de administrador sobre todas las notebooks de la empresa, corre en un servidor que nadie mira y suele estar publicada en internet porque el proveedor necesita llegar desde afuera.

Un solo bypass de autenticación ahí vale más que cien equipos comprometidos de a uno. Es lo que enseña cualquier [curso serio de pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos): el camino corto casi nunca es el frontal.

**El caso de N-able es el que mejor lo muestra.** N-central es software de administración remota que usan proveedores de servicios de IT para gestionar los equipos de sus clientes. Las dos entradas del catálogo son de bypass de autenticación, y la segunda tiene un detalle que la descripción de NVD dice con todas las letras: **CVE-2026-18577 es "un parche incompleto de CVE-2026-18556"**.

Traducido: se publicó un arreglo, el arreglo no arreglaba, y las dos terminaron en el catálogo de explotación activa con un día de diferencia. Si tu proveedor te dijo el 1 de agosto que ya había parcheado, esa afirmación quedó vencida el 3.

---

## Por qué el CVSS del fabricante y el de NVD no coinciden

Las puntuaciones de severidad de estas mismas fallas, según quién las asigne:

| CVE | Según el fabricante | Según NVD |
|---|---|---|
| CVE-2026-18556 | 8,2 | 7,4 |
| CVE-2026-18577 | 8,2 | 8,1 |
| CVE-2026-8037 | 9,6 | 9,8 |
| CVE-2026-68820 | 7,0 (Microsoft) | sin puntuación propia |

No son errores: son dos organismos evaluando el mismo problema con criterios distintos. **CVSS mide la falla, no tu riesgo**, y una diferencia de casi un punto cambia si entra o no en la cola de "parchear esta semana" de quien prioriza por número.

Las dos fallas de N-able muestran hasta qué punto la brecha es arbitraria: mismo producto, mismo tipo de falla, misma semana, y en una los evaluadores coinciden casi exacto mientras en la otra se separan ocho décimas.

```svg
<svg viewBox="0 0 660 205" role="img" aria-label="Divergencia de puntajes en las dos fallas de N-able: en CVE-2026-18556 el fabricante puntúa 8,2 y NVD 7,4, una diferencia de ocho décimas; en CVE-2026-18577 el fabricante puntúa 8,2 y NVD 8,1, prácticamente lo mismo. Escala de 7,0 a 8,5">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">El mismo producto, evaluado por dos</text>
  <circle cx="330" cy="44" r="4.5" fill="currentColor"/>
  <text x="342" y="48" font-size="10.5" fill="currentColor" opacity="0.85">NVD</text>
  <circle cx="410" cy="44" r="4.5" fill="#e23a3a"/>
  <text x="422" y="48" font-size="10.5" fill="#e23a3a">Fabricante</text>

  <text x="110" y="94" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">CVE-2026-18556</text>
  <path d="M253 90 L520 90" stroke="#e23a3a" stroke-width="1.5" opacity="0.4"/>
  <circle cx="253" cy="90" r="5" fill="currentColor"/>
  <text x="245" y="94" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">7,4</text>
  <circle cx="520" cy="90" r="5" fill="#e23a3a"/>
  <text x="530" y="94" font-size="10.5" font-weight="700" fill="#e23a3a">8,2</text>

  <text x="110" y="134" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">CVE-2026-18577</text>
  <path d="M486 130 L520 130" stroke="#e23a3a" stroke-width="1.5" opacity="0.4"/>
  <circle cx="486" cy="130" r="5" fill="currentColor"/>
  <text x="478" y="134" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">8,1</text>
  <circle cx="520" cy="130" r="5" fill="#e23a3a"/>
  <text x="530" y="134" font-size="10.5" font-weight="700" fill="#e23a3a">8,2</text>

  <path d="M120 158 L620 158" stroke="currentColor" stroke-width="1" opacity="0.3"/>
  <text x="120" y="174" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.6">7,0</text>
  <text x="287" y="174" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.6">7,5</text>
  <text x="453" y="174" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.6">8,0</text>
  <text x="620" y="174" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.6">8,5</text>

  <text x="20" y="198" font-size="11.5" font-weight="600" fill="currentColor">Priorizar por el número deja la decisión en manos de quién lo asignó</text>
</svg>
```

La regla práctica es otra: **si está en el catálogo de CISA, se parchea, sin discutir el número**. Para siete de estas diez, el plazo que CISA fija a los organismos federales fue de **tres días**.

Si un organismo con equipo de seguridad tiene tres días, la pregunta para una PyME no es cuánto tarda en parchear. Es quién parchea.

---

## ¿A quién afectan las diez fallas explotadas en agosto de 2026?

**Te toca directo si:**

- Tenés un **Cisco ASA o FTD** haciendo de firewall con VPN SSL de acceso remoto. Es el más común de esta lista en PyMEs de LATAM.
- Corrés **Metabase** para tableros de datos. La falla es inyección SQL sin autenticar en el endpoint de recuperación de contraseña, con puntuación 10,0, la máxima posible.
- Usás **TeamCity** para compilar y desplegar. Ejecución remota sin autenticar.
- Tenés **Windows**, que son todos: CVE-2026-68820 es elevación de privilegios local en el driver de WinSock. No sirve para entrar, sirve para pasar de "entré como usuario común" a "soy administrador del dominio".

**Te toca indirecto, que es peor porque no lo controlás:**

- Si tu soporte de IT es tercerizado, preguntá hoy si usan **N-able N-central** y qué versión. Es la misma pregunta que te van a hacer tus clientes grandes: cómo responderla está en la [guía de cuestionarios de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

**No te toca:**

- Si no corrés Langflow, Ray, Tomcat ni LoadMaster, esas cuatro son ruido. Perseguir cada CVE que sale en las noticias es la forma más rápida de agotar a la única persona que se ocupa de esto.

---

## ¿Cómo sé si mi panel de administración está expuesto a internet?

Todo esto es verificación desde tu lado, con tus credenciales. Ninguna es una prueba de concepto.

**Firewall de borde.** Mirá en el panel la versión exacta del sistema operativo del equipo, no la del contrato de soporte, y buscala en el aviso oficial de Cisco. Si el VPN SSL de acceso remoto está habilitado y no lo usa nadie, apagalo: es la mitigación más barata que existe.

**Windows.** Que el parche de agosto esté aplicado en todos los equipos, no en el tuyo. Sin consola central no hay forma de saberlo, que es el argumento de la [guía sobre cuándo se justifica el salto a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme).

**Servicios publicados.** Inventariá qué de lo tuyo responde desde internet: paneles de administración, tableros, servidores de integración continua. Lo que esté publicado sin necesidad, sacalo.

**Tu proveedor.** Tres preguntas por escrito: qué herramienta de administración remota usan, en qué versión y cuándo la actualizaron. La falta de respuesta también informa.

---

## ¿Qué hago para cerrar el acceso a mis paneles de administración?

1. **Parcheá lo que esté en la lista y corras**, sin discutir el CVSS. Estar en el catálogo significa que ya lo están usando.
2. **Sacá de internet lo que no necesita estar.** Un panel de administración accesible desde cualquier IP es una falla esperando el próximo CVE. Si necesitás acceso remoto, un modelo por identidad como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate) reemplaza al VPN publicado: el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).
3. **MFA en las consolas de administración antes que en ningún otro lado.** Un bypass de autenticación es peor cuando del otro lado solo hay una contraseña. La [guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) es el camino corto, y [por qué el MFA solo no alcanza](/guia/ya-tenes-mfa-y-no-alcanza) explica cuáles resisten.
4. **Verificá que tus copias sobrevivan a un administrador comprometido.** Si quien controla la consola puede borrar los respaldos, no son respaldos. [Veeam Data Platform](/producto/veeam-data-platform) y la [categoría de backup](/productos/backup-y-recuperacion) cubren la inmutabilidad.
5. **Escribí las tres preguntas a tu proveedor de IT.** Hoy.

Para la mayoría de las PyMEs de la región, el punto 2 rinde más que el 1: parchear es una carrera mensual que a veces se pierde, mientras que reducir lo publicado se hace una vez y baja el piso de riesgo de forma permanente.

La [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) ubica todo esto dentro del programa completo.

---

## Preguntas frecuentes sobre las diez fallas de agosto de 2026

### ¿Qué es el catálogo KEV de CISA y por qué importa más que un CVSS alto?

Es la lista de vulnerabilidades sobre las que la agencia de ciberseguridad de Estados Unidos tiene evidencia de explotación activa. La diferencia con el CVSS es que el CVSS estima qué tan grave sería si alguien la usara; el catálogo confirma que alguien ya la está usando. Al 17 de agosto de 2026 acumula 1.666 entradas.

### Si mi proveedor de IT usa N-central, ¿estoy comprometido?

No necesariamente. Lo que corresponde es confirmar la versión y la fecha de actualización, con un detalle: NVD describe CVE-2026-18577 como un parche incompleto de CVE-2026-18556, así que estar actualizado a principios de agosto no alcanza.

### ¿Por qué el fabricante y NVD dan puntuaciones distintas de la misma falla?

Porque son evaluaciones independientes con supuestos distintos sobre el contexto de explotación. En esta tanda las diferencias van de una décima a casi un punto entero. Por eso conviene decidir por presencia en el catálogo de explotación activa y por si el producto está publicado a internet, no por decimales.

### Una elevación de privilegios local, ¿es grave si el atacante no puede entrar?

Sola no sirve para entrar, y por eso suele bajar de prioridad. Pero casi nunca viene sola: se encadena con un phishing exitoso o una credencial robada, y convierte un acceso de usuario común en control total. Importa la cadena, no cada eslabón por separado.
