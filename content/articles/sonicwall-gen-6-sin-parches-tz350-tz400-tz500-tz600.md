---
title: "SonicWall Gen 6: TZ350, TZ400, TZ500 y TZ600 ya no reciben parches, ni para fallas críticas"
subtitle: SonicWall pidió el 18 de noviembre reemplazar ya todos sus firewalls de generación 6 y sacarlos del borde de internet. Desde el 1 de octubre no queda ningún modelo Gen 6 con soporte, el soporte no se puede comprar y no habrá excepciones para fallas críticas. Qué modelos son, cómo reconocerlos y qué apagar mientras llega el reemplazo.
excerpt: SonicWall pide reemplazar ya los firewalls Gen 6, como el TZ400 o el TZ500: no reciben parches ni ante fallas críticas. Qué modelos son y qué hacer ya.
type: news
section: mundo
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-11-23
updated: 2026-11-23
products: []
meta_title: "SonicWall Gen 6 sin parches: qué modelos cambiar"
meta_description: "SonicWall pide reemplazar ya los firewalls Gen 6, como el TZ400 o el TZ500: no reciben parches ni ante fallas críticas. Qué modelos son y qué hacer ya."
---

SonicWall publicó el 18 de noviembre un [aviso de producto](https://www.sonicwall.com/support/notices/product-advisory-retire-unsupported-gen-6-firewalls/kA1VN000002iouP0AQ) con un pedido poco habitual para un fabricante: que todos los clientes que todavía usan firewalls de **generación 6** los reemplacen de inmediato y dejen de tenerlos en el borde de internet. Ya no hay firmware ni parches para esos equipos, *"whatever the severity of a newly found vulnerability, including Critical"*, es decir, ni siquiera ante una falla crítica.

El aviso agrega una advertencia directa: *"We expect internet-facing Gen 6 firewalls to be actively targeted"*. SonicWall espera que los ataquen. El soporte **no se puede comprar ni renovar**, y el propio aviso responde que no habrá excepciones.

| Modelos Gen 6 y 6.5 | Sin soporte desde |
|---|---|
| TZ350, TZ350W, TZ500, TZ500W, SOHO 250 y 250W | 1 de octubre de 2026 |
| NSA 4600, 5600 y 6600; NSa 4650, 5650 y 6650 | 1 de octubre de 2026 |
| TZ400, TZ400W, TZ600, TZ600P, NSA 3600 y NSa 3650 | 1 de agosto de 2026 |
| NSa 2650 | 1 de mayo de 2026 |
| SOHO, SOHO W, TZ300P, NSa 9250, 9450 y 9650; NSv 10 a 1600 (virtuales) | 16 de abril de 2026 |
| SuperMassive 9200 a 9800; NSsp 12400 y 12800 | 20 de febrero de 2026 |
| TZ300 y TZ300W | 23 de enero de 2025 |
| NSA 2600 | 8 de marzo de 2024 |

Todos corren **SonicOS 6.x**. Las fechas son las de las [tablas de ciclo de vida de SonicWall](https://www.sonicwall.com/support/product-lifecycle-tables).

## ¿Qué modelos de SonicWall Gen 6 se quedaron sin parches?

Todos. Los últimos en perder el soporte fueron el TZ350, el TZ500, el SOHO 250 y las series NSA y NSa medianas, el **1 de octubre**: hoy llevan 53 días sin parches. El más antiguo de la lista, el NSA 2600, lleva 990 días. Los TZ y los SOHO son la gama de entrada de SonicWall, la pensada para oficinas chicas y sucursales.

```svg
<svg viewBox="0 0 680 304" role="img" aria-label="Firewalls SonicWall de generación 6 y 6.5, agrupados por fecha de fin de soporte, con los días que llevan sin parches al 23 de noviembre de 2026, de mayor a menor: NSA 2600, sin soporte desde el 8 de marzo de 2024, 990 días; TZ300 y TZ300W, sin soporte desde el 23 de enero de 2025, 669 días; SuperMassive y NSsp 12400/12800, sin soporte desde el 20 de febrero de 2026, 276 días; SOHO, TZ300P, NSa 9250-9650 y NSv, sin soporte desde el 16 de abril de 2026, 221 días; NSa 2650, sin soporte desde el 1 de mayo de 2026, 206 días; TZ400, TZ600, NSA 3600 y NSa 3650, sin soporte desde el 1 de agosto de 2026, 114 días; NSA 4600-6600 y NSa 4650-6650, sin soporte desde el 1 de octubre de 2026, 53 días; TZ350, TZ500 y SOHO 250, sin soporte desde el 1 de octubre de 2026, 53 días. La marca roja señala el TZ350, el TZ500 y el SOHO 250, los últimos en perder el soporte: desde el 1 de octubre de 2026 no queda ningún modelo Gen 6 con parches.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">SonicWall Gen 6: días sin parches de cada modelo</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Al 23 de noviembre de 2026. El rojo marca los últimos en perder el soporte.</text>
  <path d="M266.0 58 V256" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="266" y="271" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">0</text>
  <path d="M345.5 58 V256" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="345.5" y="271" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">250</text>
  <path d="M425.0 58 V256" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="425" y="271" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">500</text>
  <path d="M504.5 58 V256" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="504.5" y="271" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">750</text>
  <path d="M584.0 58 V256" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="584" y="271" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">1.000</text>
  <text x="256" y="77" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">NSA 2600</text>
  <path d="M266,64 H576.8 A4,4 0 0 1 580.8,68 V78 A4,4 0 0 1 576.8,82 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>NSA 2600: sin soporte desde el 8 de marzo de 2024, 990 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="586.8" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">990 días</text>
  <text x="256" y="101" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">TZ300 y TZ300W</text>
  <path d="M266,88 H474.7 A4,4 0 0 1 478.7,92 V102 A4,4 0 0 1 474.7,106 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>TZ300 y TZ300W: sin soporte desde el 23 de enero de 2025, 669 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="484.7" y="101" class="ch-value" font-size="11" font-weight="600" fill="currentColor">669 días</text>
  <text x="256" y="125" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">SuperMassive y NSsp 12400/12800</text>
  <path d="M266,112 H349.8 A4,4 0 0 1 353.8,116 V126 A4,4 0 0 1 349.8,130 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>SuperMassive y NSsp 12400/12800: sin soporte desde el 20 de febrero de 2026, 276 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="359.8" y="125" class="ch-value" font-size="11" font-weight="600" fill="currentColor">276 días</text>
  <text x="256" y="149" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">SOHO, TZ300P, NSa 9250-9650 y NSv</text>
  <path d="M266,136 H332.3 A4,4 0 0 1 336.3,140 V150 A4,4 0 0 1 332.3,154 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>SOHO, TZ300P, NSa 9250-9650 y NSv: sin soporte desde el 16 de abril de 2026, 221 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="342.3" y="149" class="ch-value" font-size="11" font-weight="600" fill="currentColor">221 días</text>
  <text x="256" y="173" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">NSa 2650</text>
  <path d="M266,160 H327.5 A4,4 0 0 1 331.5,164 V174 A4,4 0 0 1 327.5,178 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>NSa 2650: sin soporte desde el 1 de mayo de 2026, 206 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="337.5" y="173" class="ch-value" font-size="11" font-weight="600" fill="currentColor">206 días</text>
  <text x="256" y="197" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">TZ400, TZ600, NSA 3600 y NSa 3650</text>
  <path d="M266,184 H298.3 A4,4 0 0 1 302.3,188 V198 A4,4 0 0 1 298.3,202 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>TZ400, TZ600, NSA 3600 y NSa 3650: sin soporte desde el 1 de agosto de 2026, 114 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="308.3" y="197" class="ch-value" font-size="11" font-weight="600" fill="currentColor">114 días</text>
  <text x="256" y="221" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">NSA 4600-6600 y NSa 4650-6650</text>
  <path d="M266,208 H278.9 A4,4 0 0 1 282.9,212 V222 A4,4 0 0 1 278.9,226 H266 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>NSA 4600-6600 y NSa 4650-6650: sin soporte desde el 1 de octubre de 2026, 53 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="288.9" y="221" class="ch-value" font-size="11" font-weight="600" fill="currentColor">53 días</text>
  <text x="256" y="245" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">TZ350, TZ500 y SOHO 250</text>
  <path d="M266,232 H278.9 A4,4 0 0 1 282.9,236 V246 A4,4 0 0 1 278.9,250 H266 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>TZ350, TZ500 y SOHO 250: sin soporte desde el 1 de octubre de 2026, 53 días sin parches al 23 de noviembre de 2026</title></path>
  <text x="288.9" y="245" class="ch-value" font-size="11" font-weight="600" fill="currentColor">53 días</text>
  <text x="20" y="294" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: aviso de SonicWall del 18 de noviembre de 2026 y tablas de ciclo de vida.</text>
</svg>
```

El aviso no dice cuántos firewalls Gen 6 siguen conectados, y no encontramos una medición pública reciente. Lo que sí está claro es la consecuencia de las fechas: desde el 1 de octubre, **cualquier falla nueva en SonicOS 6 queda abierta para siempre**.

## ¿Por qué SonicWall pide reemplazar los Gen 6 ahora?

El aviso da tres razones. La primera es que los equipos de borde se volvieron una puerta de entrada preferida: cita el informe DBIR 2025 de Verizon, según el cual los equipos de borde y las VPN fueron el blanco en el 22% de las brechas que empezaron por una vulnerabilidad explotada, frente al 3% del año anterior. La segunda, que la explotación es cada vez más rápida. La tercera, que los atacantes usan automatización e IA para encontrar equipos sin soporte.

Pero el argumento más fuerte es otro, y SonicWall lo resume en una frase: *"Every fix is a map"*. Cuando una falla afecta a varias generaciones, las que tienen soporte reciben la corrección y Gen 6 no. El aviso público les dice a los atacantes dónde buscar.

```svg
<svg viewBox="0 0 680 328" role="img" aria-label="Qué pasa cuando aparece una falla nueva en SonicOS que afecta a varias generaciones, según el aviso de SonicWall del 18 de noviembre de 2026. En un firewall Gen 7 o Gen 8, con soporte: SonicWall publica el aviso, sale el firmware corregido y, al actualizar, la falla se cierra. En un firewall Gen 6, sin soporte: el aviso público es el mismo, pero no hay firmware para Gen 6 ni lo va a haber, aunque la falla sea crítica, así que queda abierta para siempre y el aviso les indica a los atacantes dónde buscar.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">SonicWall: la misma falla nueva, en Gen 7 u 8 y en Gen 6</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según el aviso de SonicWall. El rojo marca el firewall que queda abierto.</text>
  <rect x="16" y="58" width="648" height="112" rx="8" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Gen 7 y Gen 8, con soporte</title></rect>
  <text x="32" y="82" class="ch-strong" font-size="11.5" font-weight="600" fill="currentColor">Gen 7 y Gen 8, con soporte</text>
  <rect x="32" y="96" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>SonicWall publica el aviso: con la falla y su puntaje</title></rect>
  <text x="127" y="119" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">SonicWall publica el aviso</text>
  <text x="127" y="137" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">con la falla y su puntaje</text>
  <path d="M227 124 H239 M234 119 L239 124 L234 129" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="245" y="96" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>Sale el firmware corregido: para cada generación</title></rect>
  <text x="340" y="119" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">Sale el firmware corregido</text>
  <text x="340" y="137" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">para cada generación</text>
  <path d="M440 124 H452 M447 119 L452 124 L447 129" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="458" y="96" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>Actualizás el firewall: y la falla se cierra</title></rect>
  <text x="553" y="119" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">Actualizás el firewall</text>
  <text x="553" y="137" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y la falla se cierra</text>
  <rect x="16" y="182" width="648" height="112" rx="8" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>Gen 6, sin soporte</title></rect>
  <text x="32" y="206" class="ch-strong" font-size="11.5" font-weight="600" fill="currentColor">Gen 6, sin soporte</text>
  <rect x="32" y="220" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>SonicWall publica el aviso: el mismo, a la vista de todos</title></rect>
  <text x="127" y="243" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">SonicWall publica el aviso</text>
  <text x="127" y="261" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">el mismo, a la vista de todos</text>
  <path d="M227 248 H239 M234 243 L239 248 L234 253" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="245" y="220" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>No hay firmware para Gen 6: ni lo va a haber</title></rect>
  <text x="340" y="243" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">No hay firmware para Gen 6</text>
  <text x="340" y="261" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">ni lo va a haber</text>
  <path d="M440 248 H452 M447 243 L452 248 L447 253" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="458" y="220" width="190" height="56" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.3"><title>La falla queda abierta: y el aviso dice dónde buscar</title></rect>
  <text x="553" y="243" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">La falla queda abierta</text>
  <text x="553" y="261" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y el aviso dice dónde buscar</text>
  <text x="20" y="318" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: aviso de SonicWall del 18 de noviembre de 2026.</text>
</svg>
```

No es una hipótesis. En el [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) hay dos fallas de SonicOS que alcanzaban a Gen 6, y las dos figuran con uso conocido en campañas de ransomware:

- [CVE-2020-5135](https://nvd.nist.gov/vuln/detail/CVE-2020-5135), un desbordamiento de memoria con 9,8 según NVD, que entró al catálogo en marzo de 2022.
- [CVE-2024-40766](https://nvd.nist.gov/vuln/detail/CVE-2024-40766), una falla de control de acceso en la administración de SonicOS, con 9,8 según NVD, que afectaba a Gen 5, Gen 6 y los primeros Gen 7, y entró al catálogo en septiembre de 2024.

En los dos casos, Gen 6 recibió su parche. Con una tercera, ya no lo recibiría. El aviso de SonicOS de este mes, con [siete fallas, una de ellas ya explotada en el SSL VPN](/noticia/sonicwall-cve-2026-19868-ssl-vpn-saml-mfa), trajo las versiones 7.3.4 y 8.2.3, solo para Gen 7 y Gen 8; para Gen 6, SonicWall se limitó a indicar que hay que pasar a Gen 8. No aclara si esas fallas también estaban en SonicOS 6.

## ¿Cómo sé si mi firewall SonicWall es Gen 6?

1. **Mirá el modelo.** Está en la etiqueta del equipo y en la pantalla de estado del panel de administración. Si aparece en la tabla de arriba, es Gen 6 o 6.5.
2. **Mirá la versión de firmware.** Si empieza con 6, es Gen 6: los Gen 7 corren SonicOS 7 y los Gen 8, SonicOS 8.
3. **Revisá tu cuenta de MySonicWall.** Las tablas públicas de SonicWall no muestran los modelos más viejos y remiten a MySonicWall, donde figuran los equipos registrados a tu nombre.
4. **Buscá los que no están a la vista.** El aviso pide incluir los equipos de repuesto y los de sucursales, que son los que se olvidan.
5. **Si te administra la red un proveedor**, pedile por escrito qué modelo y qué firmware tiene cada sede.

## ¿Qué hago si tengo un SonicWall Gen 6?

SonicWall pide reemplazarlo por un Gen 8 con SonicOS 8, y empezar por los que están expuestos a internet o dan acceso remoto. Mientras tanto, el aviso lista cinco medidas que **reducen la exposición, pero no hacen seguro al equipo**:

1. **Apagá la administración desde internet**: HTTPS, SSH y SNMP en las interfaces WAN. Es lo primero.
2. **Apagá el SSL VPN, o limitalo a direcciones de origen conocidas.** Si el VPN es la forma de trabajar desde afuera, es buen momento para revisar [alternativas de acceso remoto sin VPN](/guia/acceso-remoto-seguro-sin-vpn).
3. **Exigí verificación en dos pasos** en todas las cuentas de acceso remoto y de administración que queden, y cambiá las contraseñas locales. Si todavía no la tenés, la [guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) sirve de punto de partida.
4. **Apagá los servicios e interfaces que no uses.**
5. **Mirá los registros**: inicios de sesión raros, cambios de configuración y tráfico inusual.

Dos datos del aviso ayudan a planificar. La configuración **no hay que rehacerla**: lo soportado se migra desde SonicOS 6.5 a Gen 8 con la importación del propio equipo o con la herramienta de migración de NSM. Y el aviso recomienda pasar a Gen 8. Si estás evaluando un Gen 7, tené en cuenta que en las tablas de SonicWall el TZ270 y el TZ470 ya pasaron su último día de venta, el 30 de junio de 2026. Según su política, a ese día le siguen 2 años de retiro activo con correcciones y 3 años de retiro limitado, solo con correcciones críticas.

## ¿Quién puede ignorar el aviso de SonicWall Gen 6?

- **Quien tiene Gen 7 o Gen 8**, como los TZ270 a TZ670 o los TZ80 a TZ680. Siguen con soporte, aunque tienen que estar en SonicOS 7.3.4 u 8.2.3 o posterior por las fallas de noviembre.
- **Quien usa los equipos de acceso remoto SMA 100 o SMA 1000.** Son otra línea de producto, con su propio calendario; los SMA 1000 tuvieron [sus propias fallas explotadas en septiembre](/noticia/kev-2-septiembre-sonicwall-switchvox-plazo-tres-dias).
- **Quien no usa SonicWall.** Pero la sección siguiente le toca igual.

## ¿Qué enseña el fin de soporte de SonicWall Gen 6 aunque no uses SonicWall?

Que la fecha de fin de soporte es un dato de seguridad, no de compras. En los últimos días lo vimos dos veces: [vCenter 7.0 quedó sin parche público para una falla explotada](/noticia/vmware-vcenter-cve-2026-123890-explotada-desde-septiembre) porque su soporte general terminó en 2025, y ahora un fabricante dice por escrito que sus firewalls viejos van a ser blanco. Antes pasó con [routers de consumo con fallas críticas que nunca van a tener parche](/noticia/linksys-edimax-wavlink-17-criticas-routers-sin-parche).

El ejercicio sirve para cualquier empresa: una planilla con cada equipo de red, su modelo, su firmware y su fecha de fin de soporte. El firewall va primero, porque es lo que da a internet. Y tiene una consecuencia que no es técnica: correr equipos sin soporte es de las cosas que [preguntan las aseguradoras](/guia/ciberseguro-pyme-que-exigen-las-aseguradoras) al cotizar o al evaluar un siniestro.

## Preguntas frecuentes sobre SonicWall Gen 6

### ¿Puedo comprar soporte extendido para un SonicWall Gen 6?

No. Según el aviso, el soporte para Gen 6 ya no se puede comprar ni renovar, y no va a salir más firmware.

### ¿SonicWall va a sacar un parche para Gen 6 si aparece una falla crítica?

No. El aviso lo responde de forma explícita: no habrá actualizaciones de firmware ni parches para Gen 6, sea cual sea la gravedad de la falla.

### ¿Mi SonicWall Gen 6 deja de funcionar?

No. Sigue filtrando tráfico como antes. Lo que cambia es que cualquier falla que se descubra desde ahora queda abierta, y SonicWall espera que los equipos expuestos a internet sean atacados.

### ¿Tengo que rehacer la configuración al pasar de Gen 6 a Gen 8?

No, según SonicWall. Lo soportado se migra desde SonicOS 6.5 con la importación del propio equipo o con la herramienta de migración de NSM.

### ¿Qué hago con el SonicWall Gen 6 hasta que llegue el reemplazo?

Apagar la administración desde internet y el SSL VPN, o limitar el VPN a direcciones conocidas, exigir verificación en dos pasos, cambiar las contraseñas locales y mirar los registros. Reduce el riesgo, pero no lo elimina.
