---
title: "FaceStealer: Corea del Norte usa Zoom y Teams falsos con videos de tus contactos"
subtitle: El FBI describe un esquema que ya robó más de USD 400 millones en 18 meses. Te escribe un contacto real desde su cuenta robada, la reunión muestra su video grabado y un falso problema de audio termina con malware en tu computadora. Después, tu video es el señuelo.
excerpt: El FBI alerta por FaceStealer: reuniones falsas de Zoom y Teams con videos grabados de contactos reales. Cómo funciona, las señales y qué hacer si caíste.
type: news
section: mundo
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-11-15
updated: 2026-11-15
products: []
meta_title: "FaceStealer: reuniones falsas de Zoom y Teams"
meta_description: "El FBI alerta por FaceStealer: reuniones falsas de Zoom y Teams con videos grabados de contactos reales. Cómo funciona, las señales y qué hacer si caíste."
---

El FBI publicó el 12 de noviembre [una alerta FLASH](https://www.ic3.gov/CSA/2026/261112.pdf) sobre un esquema de ingeniería social que llama **FaceStealer** y atribuye a hackers del Estado de Corea del Norte, los mismos que se conocen como Sapphire Sleet, BlueNoroff o APT38. Según el FBI, con este método robaron *"more than $400 million in cryptocurrency over the past 18 months"*: más de USD 400 millones en criptomonedas en 18 meses.

Lo que lo vuelve eficaz es que no hay nada raro a primera vista. Te escribe alguien que conocés, desde su cuenta de siempre. La reunión es en Zoom o en Teams, o eso parece, y en la pantalla aparece la cara de esa persona. El FBI identificó e incautó *"over 900 victim webcam videos"*: grabaciones de víctimas anteriores que los atacantes usaban para hacerse pasar por ellas.

```svg
<svg viewBox="0 0 680 292" role="img" aria-label="Cómo funciona FaceStealer, el esquema de Corea del Norte que describe el FBI en su alerta del 12 de noviembre de 2026. Primero, una víctima anterior ya está infectada: los atacantes le robaron la cuenta de Telegram u otra app de mensajería y le grabaron la cámara. Segundo, desde esa cuenta escriben a sus contactos y proponen una reunión. Tercero, mandan una invitación de Calendly sin link, y el link de Zoom o Teams llega poco antes, desde un dominio parecido al real. Cuarto, en la reunión falsa pasan el video grabado de la víctima anterior, a veces con más participantes falsos. Quinto, inventan un problema de audio y piden correr un script, pegar código en la terminal o instalar una actualización; ese es el paso que el FBI pide no hacer nunca. Sexto, ese arreglo instala malware que roba cookies del navegador, teclas y pantalla, y deja una puerta trasera; la persona pasa a ser la víctima siguiente y su cámara ya quedó grabada.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">FaceStealer: cada víctima se vuelve el señuelo de la siguiente</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según el FBI. El rojo marca el paso que nunca hay que hacer.</text>
  <rect x="16" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Víctima anterior: cuenta robada y cámara grabada</title></rect>
  <text x="118" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Víctima anterior</text>
  <text x="118" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">cuenta robada y cámara grabada</text>
  <rect x="238" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Te escribe tu contacto: desde su Telegram o LinkedIn</title></rect>
  <text x="340" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Te escribe tu contacto</text>
  <text x="340" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">desde su Telegram o LinkedIn</text>
  <rect x="460" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Link de Zoom o Teams: llega poco antes, con dominio falso</title></rect>
  <text x="562" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Link de Zoom o Teams</text>
  <text x="562" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">llega poco antes, con dominio falso</text>
  <path d="M222 91 H234 M229 86 L234 91 L229 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 91 H456 M451 86 L456 91 L451 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 120 V138 H118 V154 M113 149 L118 154 L123 149" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="16" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Reunión falsa: con el video grabado de tu contacto</title></rect>
  <text x="118" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Reunión falsa</text>
  <text x="118" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">con el video grabado de tu contacto</text>
  <rect x="238" y="156" width="204" height="58" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>"No te escuchamos": corré este script para arreglarlo</title></rect>
  <text x="340" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">&quot;No te escuchamos&quot;</text>
  <text x="340" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">corré este script para arreglarlo</text>
  <rect x="460" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Malware instalado: y ahora tu video es el señuelo</title></rect>
  <text x="562" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Malware instalado</text>
  <text x="562" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y ahora tu video es el señuelo</text>
  <path d="M222 185 H234 M229 180 L234 185 L229 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 185 H456 M451 180 L456 185 L451 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="20" y="246" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">El FBI incautó más de 900 videos de víctimas grabados así.</text>
  <text x="20" y="276" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: alerta FLASH AC-111226-MS del FBI, 12 de noviembre de 2026.</text>
</svg>
```

## ¿Cómo funciona FaceStealer, paso a paso?

La alerta del FBI describe seis etapas, y cada una alimenta a la siguiente:

1. **Ya hay una víctima.** Los atacantes controlan su computadora, le robaron la cuenta de Telegram u otra app de mensajería, leyeron sus chats y la grabaron con la cámara mientras esperaba que empezara una videollamada.
2. **Escriben a sus contactos.** Desde esa cuenta proponen una reunión para *"catch up"*, hablar de una colaboración o de una inversión. Como tienen el historial de chats, pueden escribir como esa persona. El FBI agrega que también usan cuentas robadas o nuevas de LinkedIn, Slack y Discord.
3. **Mandan una invitación de calendario sin link.** Usan Calendly u otro servicio, y el link de la reunión llega recién poco antes, desde un dominio parecido al real. El FBI da dos ejemplos: `uu01webzoom.us` y `teams.livemeet.us`.
4. **La reunión es una copia.** El link lleva a un servidor de los atacantes con una imitación de Zoom o Teams. Cuando te admiten, pasan el video grabado de tu contacto, y a veces otros videos para que parezca que hay más gente.
5. **Inventan un problema.** Tu contacto falso te escribe que no te escuchan, y te guía para *"click an 'update' prompt or link, run a script, or copy and paste code into the target's command line or terminal"*. Ese arreglo instala programas que roban las cookies del navegador, registran lo que tecleás, capturan la pantalla y dejan una puerta trasera.
6. **Ahora sos el señuelo.** Desde que hiciste clic en el link, la cámara te estuvo grabando. Con tu video y tus contactos, el ciclo vuelve a empezar.

El paso 5 es la técnica que se conoce como ClickFix, que ya apareció en [la campaña que usaba el Siemens Software Center](/noticia/siemens-software-center-cve-2026-110936-clickfix-dll): convencer a la víctima de que ejecute ella misma el código del atacante.

## ¿A quién apunta FaceStealer?

La alerta va dirigida a la industria de criptomonedas y web3 y a quienes mantienen proyectos de software abierto. El FBI cuenta un caso de este año: los atacantes metieron en una reunión falsa de Teams al responsable de una biblioteca de JavaScript con *"tens of millions of downloads per week"*, le infectaron la computadora y usaron su acceso para publicar dependencias maliciosas. Lo frenaron a tiempo. El FBI dice que hubo otros intentos contra paquetes de npm y, hace poco, de Rust.

Pero el método sirve contra cualquiera que tenga algo que valga la pena robar, y el FBI lo dice: con la computadora de la víctima, los atacantes pueden *"exploit the victim's access to external infrastructure, such as an employer's network"*. Una persona de finanzas, de sistemas o de la dirección de una empresa chica es un buen blanco. Es el mismo país detrás del [robo a Binance](/noticia/hackeo-binance-6300-millones-tradertraitor-devops), que también empezó por la computadora de un empleado.

Hay un detalle que cambia la urgencia: en varios casos, el robo llegó *"months to over a year after the initial compromise"*. Los atacantes copian las frases de recuperación de las billeteras y esperan a que el saldo crezca. Una infección de hace meses puede seguir activa.

## ¿Cómo sé si una reunión de Zoom o Teams es FaceStealer?

```svg
<svg viewBox="0 0 680 300" role="img" aria-label="Señales de una reunión FaceStealer y qué hacer, según la alerta del FBI. Señales: el link de la reunión no estaba en la invitación del calendario y llega poco antes; el dominio se parece al real, como uu01webzoom.us o teams.livemeet.us; aparece un problema de audio que solo te pasa a vos; te piden correr un script, pegar código en la terminal o instalar una actualización desde la reunión. Qué hacer: entrar desde la app oficial de Zoom o Teams con el código de la reunión; no prender la cámara hasta ver y hablar con quien te invitó; confirmar la reunión por otro canal, como una llamada; y si la app pide actualizarse, actualizarla aparte, nunca con un comando que te pasen en la reunión.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">FaceStealer: las señales y qué hacer, según el FBI</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">El rojo marca las señales de que estás en una reunión falsa.</text>
  <rect x="16" y="58" width="648" height="112" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>Señales de una reunión FaceStealer</title></rect>
  <text x="32" y="80" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">Señales de una reunión falsa</text>
  <text x="32" y="100" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">El link no estaba en la invitación del calendario y llega poco antes.</text>
  <text x="32" y="118" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">El dominio se parece al real: uu01webzoom.us o teams.livemeet.us.</text>
  <text x="32" y="136" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Hay un problema de audio que solo te pasa a vos.</text>
  <text x="32" y="154" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Te piden correr un script, pegar código o instalar algo desde la reunión.</text>
  <rect x="16" y="180" width="648" height="94" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Qué hacer, según el FBI</title></rect>
  <text x="32" y="202" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">Qué hacer</text>
  <text x="32" y="222" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Entrá desde la app oficial de Zoom o Teams, con el código de la reunión.</text>
  <text x="32" y="240" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">No prendas la cámara hasta ver y hablar con quien te invitó.</text>
  <text x="32" y="258" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Confirmá la reunión por otro canal, y actualizá la app aparte, nunca con un comando.</text>
  <text x="20" y="292" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: alerta FLASH AC-111226-MS del FBI, 12 de noviembre de 2026.</text>
</svg>
```

Las recomendaciones del FBI apuntan a romper la cadena en el paso que depende de vos:

1. **Entrá desde la aplicación oficial, con el código de la reunión.** No uses el link que te mandaron, sobre todo si viene de un contacto con el que no hablás seguido.
2. **No prendas la cámara hasta ver y hablar con quien te invitó.** Si la reunión es falsa, tu video no queda grabado.
3. **Desconfiá del calendario sin link.** Según el FBI, los atacantes suelen mandar la invitación sin el link y el link llega cerca de la hora, para exponer menos su infraestructura.
4. **Confirmá por otro canal.** Si te invitaron por Telegram, confirmá con un mensaje de texto o una llamada.
5. **Nunca ejecutes nada para arreglar una reunión.** Si la aplicación pide actualizarse, actualizala aparte o entrá por la versión web con el código. Ninguna reunión legítima necesita que pegues un comando en la terminal.

El FBI avisa además que los atacantes empezaron a usar inteligencia artificial, que la alerta llama *"super intelligence (SI)"*, para fabricar videos falsos de figuras conocidas del mundo cripto. Aun así, el esquema todavía depende sobre todo de videos reales robados.

## ¿Qué hago si creo que caí en FaceStealer?

La alerta del FBI da este orden:

1. **Mové las criptomonedas a una billetera nueva**, con frases de recuperación nuevas, creada en un equipo que no usaste para hablar con los atacantes.
2. **Asegurá las apps de mensajería y las cuentas sensibles**, incluidas las de desarrollo: cambiá las contraseñas, cerrá todas las sesiones abiertas y activá el segundo factor. Cerrar sesiones importa porque el programa roba cookies, y con una cookie se entra sin contraseña, como explica [la guía sobre el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).
3. **Avisá a tus contactos** de que pueden recibir mensajes y videos tuyos que no mandaste. Si usaste un equipo del trabajo, o tenés archivos del trabajo en el tuyo, avisá a la empresa. El FBI es directo: no avisar *"puts them at risk of compromise"*.
4. **Cambiá todas las contraseñas**, desde un equipo limpio.
5. **Si perdiste una cuenta, pedile a la plataforma que la bloquee o te la devuelva.**

Para prevenir el robo de cuentas, el FBI recomienda segundo factor, alertas de inicio de sesión y mensajes temporales en Telegram, WhatsApp y Slack. Así se configura el segundo factor en toda la empresa: [la guía para hacerlo en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana). Y si la computadora estuvo infectada, revisá también si tus contraseñas aparecen en los datos de la [Operación Endgame](/noticia/operacion-endgame-castlerat-gootloader-stealc-credenciales).

## ¿Quién puede ignorar la alerta de FaceStealer?

Nadie que use videollamadas con gente de afuera de la empresa. El blanco preferido son las personas con criptomonedas o con acceso a código que usan muchos otros, pero el engaño no necesita que tengas nada de eso: necesita que confíes en un contacto. Las prioridades generales de seguridad para una empresa chica están en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** la alerta FLASH AC-111226-MS del FBI, del 12 de noviembre de 2026. El FBI cita además los informes de [Arctic Wolf](https://arcticwolf.com/resources/blog/still-in-the-meeting-bluenoroff-six-months-on/), [JUMPSEC](https://www.jumpsec.com/guides/inside-a-dprk-bluenoroff-clickfix-kit/) y Microsoft Threat Intelligence, con indicadores técnicos. **Sin confirmar:** cuántas víctimas hay en América Latina, porque el FBI no da un desglose por país; qué biblioteca de JavaScript fue la atacada, porque la alerta no la nombra; y si hubo incautación de servidores, porque la alerta solo menciona los videos.

## Preguntas frecuentes sobre FaceStealer

### ¿Qué es FaceStealer?

Es el nombre que el FBI le da a un esquema de Corea del Norte que usa reuniones falsas de Zoom, Teams o Google Meet. Muestran videos grabados de contactos reales de la víctima y la convencen de ejecutar un supuesto arreglo que instala malware.

### ¿Cómo distingo una reunión de Zoom o Teams falsa?

El link llega poco antes de la reunión, el dominio se parece al real pero no es, aparece un problema que solo te pasa a vos y te piden correr un script o pegar código. Entrar desde la app oficial con el código de la reunión evita el engaño.

### ¿Por qué el FBI recomienda no prender la cámara al entrar a una reunión?

Porque FaceStealer graba la cámara de cada víctima desde que entra al link falso, y usa ese video para engañar a sus contactos. Esperar a ver y hablar con quien te invitó evita que tu cara se vuelva el próximo señuelo.

### ¿Qué hago si ejecuté un comando en una reunión sospechosa?

Mové tus criptomonedas a una billetera nueva creada en otro equipo, cerrá todas las sesiones, cambiá las contraseñas desde un equipo limpio, activá el segundo factor y avisá a tus contactos y a tu empleador.

### ¿Quién está detrás de FaceStealer?

El FBI lo atribuye a hackers del Estado de Corea del Norte, conocidos como Sapphire Sleet, UNC1069, BlueNoroff, APT38 y Stardust Chollima, entre otros nombres.
