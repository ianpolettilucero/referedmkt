---
title: "FreeSWITCH, OpenSSH, GPUStack y Aspera: cuatro fallas explotadas con plazo de tres días"
subtitle: CISA las sumó el 9 de noviembre y vencen el 12. La de FreeSWITCH, base de muchas centrales telefónicas IP, deja ejecutar comandos con la configuración de fábrica. La de OpenSSH abre sesión sin contraseña en Alpine Linux. Las cuatro ya tienen versión corregida.
excerpt: Cuatro fallas explotadas con plazo al 12 de noviembre: FreeSWITCH 1.11.3, OpenSSH 10.6p2, GPUStack 2.2.0 y Aspera Faspex 5.0.19 PL1. Cuál te toca y qué hacer.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-11-10
updated: 2026-11-10
products: []
meta_title: "FreeSWITCH y OpenSSH: cuatro fallas al KEV"
meta_description: "Cuatro fallas explotadas con plazo al 12 de noviembre: FreeSWITCH 1.11.3, OpenSSH 10.6p2, GPUStack 2.2.0 y Aspera Faspex 5.0.19 PL1. Cuál te toca y qué hacer."
---

CISA sumó el 9 de noviembre **cuatro fallas** a su catálogo de vulnerabilidades explotadas, las cuatro con plazo al **12 de noviembre**: 3 días, y con triaje forense obligatorio para las agencias federales de Estados Unidos. Son de cuatro productos muy distintos:

| CVE | Producto | Qué permite | Puntaje | Corrige |
|---|---|---|---|---|
| CVE-2026-116779 | **FreeSWITCH**, central telefónica de software | Ejecutar comandos con un mensaje SIP | 9,8, de GitHub como autoridad de numeración | 1.11.3 |
| CVE-2026-123158 | **OpenSSH** en Linux con musl y sin PAM | Iniciar sesión sin la contraseña | 8,1, de MITRE | 10.6p2 |
| CVE-2026-102895 | **GPUStack**, gestor de servidores de IA | Entrar como administrador sin credenciales | 9,8, de GitHub | 2.2.0 |
| CVE-2026-123218 | **IBM Aspera Faspex**, transferencia de archivos | Escalar a administrador | 9,8, de IBM | 5.0.19 PL1 |

Ninguna tiene puntaje propio de NVD: los cuatro números son de la autoridad que registró cada CVE, algo que ya es la norma, como se explicó en [la nota sobre fallas explotadas sin puntaje de NVD](/noticia/cvss-nvd-dejo-de-puntuar-fallas-explotadas-medicion). Para una PyME de la región, las que más pesan son las dos primeras.

```svg
<svg viewBox="0 0 680 420" role="img" aria-label="Las cuatro fallas que CISA sumó a su catálogo de explotadas el 9 de noviembre de 2026, con plazo al 12, y cuándo le tocan a una empresa. FreeSWITCH hasta la versión 1.11.2, corregido en 1.11.3: afecta si un perfil SIP acepta mensajes SIP MESSAGE, que es la configuración por defecto; mientras tanto, enable-chat=false en cada perfil que no use chat. OpenSSH en Alpine u otro Linux con musl, corregido en 10.6p2: afecta si sshd corre sin PAM y con inicio de sesión por contraseña; mientras tanto, PasswordAuthentication no y reiniciar sshd. GPUStack antes de 2.2.0, corregido en 2.2.0: afecta si se llega a él por la red, y con un proxy en el mismo equipo no hace falta ninguna credencial; mientras tanto, force_auth_localhost=true. IBM Aspera Faspex 5.0.0 a 5.0.19, corregido en 5.0.19 PL1: según IBM alcanza con una cuenta de usuario, y no publica una mitigación alternativa.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Las cuatro fallas del 9 de noviembre: cuándo te tocan y qué hacer</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según los avisos de cada proyecto. El rojo marca la que viene expuesta en la configuración por defecto.</text>
  <rect x="16" y="58" width="648" height="72" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="32" y="80" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">FreeSWITCH, hasta 1.11.2</text>
  <text x="632" y="80" class="ch-strong" font-size="10.5" text-anchor="end" font-weight="600" fill="currentColor">Corrige 1.11.3</text>
  <text x="32" y="100" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Te toca si un perfil SIP acepta MESSAGE: viene así por defecto.</text>
  <text x="32" y="118" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Mientras tanto: enable-chat=false en cada perfil que no use chat.</text>
  <rect x="16" y="140" width="648" height="72" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="32" y="162" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">OpenSSH en Alpine u otro Linux con musl</text>
  <text x="632" y="162" class="ch-strong" font-size="10.5" text-anchor="end" font-weight="600" fill="currentColor">Corrige 10.6p2</text>
  <text x="32" y="182" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Te toca si sshd corre sin PAM y con login por contraseña.</text>
  <text x="32" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Mientras tanto: PasswordAuthentication no, y reiniciar sshd.</text>
  <rect x="16" y="222" width="648" height="72" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="32" y="244" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">GPUStack, antes de 2.2.0</text>
  <text x="632" y="244" class="ch-strong" font-size="10.5" text-anchor="end" font-weight="600" fill="currentColor">Corrige 2.2.0</text>
  <text x="32" y="264" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Te toca si se llega a él por la red; con un proxy en el mismo equipo, sin credenciales.</text>
  <text x="32" y="282" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Mientras tanto: force_auth_localhost=true.</text>
  <rect x="16" y="304" width="648" height="72" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="32" y="326" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">IBM Aspera Faspex 5.0.0 a 5.0.19</text>
  <text x="632" y="326" class="ch-strong" font-size="10.5" text-anchor="end" font-weight="600" fill="currentColor">Corrige 5.0.19 PL1</text>
  <text x="32" y="346" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Te toca si tenés Faspex 5: según IBM, alcanza con una cuenta de usuario.</text>
  <text x="32" y="364" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">IBM no publica una mitigación alternativa.</text>
  <text x="20" y="398" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: avisos de FreeSWITCH, OpenSSH, Alpine Linux, GPUStack e IBM.</text>
</svg>
```

## ¿Cómo funciona CVE-2026-116779 en FreeSWITCH?

FreeSWITCH es el motor de muchas centrales telefónicas IP, como FusionPBX, y de proveedores de telefonía por internet. El [aviso de FreeSWITCH](https://github.com/signalwire/freeswitch/security/advisories/GHSA-mg63-h38w-8p9p) explica la falla: cuando llega un mensaje de chat por SIP, el tipo `MESSAGE`, la central lee parte del destinatario como el nombre del servicio que debe recibirlo. Uno de esos servicios ejecuta comandos de administración de FreeSWITCH. Quien elige ese servicio, elige qué comando corre la central.

El impacto, según el aviso: *"Command execution with the privileges of the FreeSWITCH process: arbitrary OS commands, disclosure of configuration and credentials, and full call control."* En una central telefónica, el control de las llamadas puede abrir la puerta al fraude con llamadas a cargo de la empresa. Y la frase que importa: *"Exploitation of this path has been observed in the wild."*

Lo grave es que no hace falta tocar nada para estar expuesto. El aviso lo dice así: *"No non-default setting is required."* El chat está activado por defecto en los perfiles interno y externo, y la lista de acceso de entrada no cubre los mensajes `MESSAGE`. Si la central exige autenticación para los mensajes, alcanza con cualquier cuenta del perfil: un interno, un teléfono o una troncal. Y las instalaciones que conservan el usuario de ejemplo `default`, que viene sin contraseña, ni siquiera necesitan eso.

## ¿Qué hago si tengo una central con FreeSWITCH?

1. **Actualizá a FreeSWITCH 1.11.3.** La versión nueva agrega un parámetro que cierra el camino en todos los perfiles por defecto y registra cada intento con su origen y el comando pedido. Si usás una central armada sobre FreeSWITCH, como FusionPBX, preguntale a quien la mantiene qué versión de FreeSWITCH trae.
2. **Si no podés actualizar hoy, apagá el chat.** El aviso indica `enable-chat=false` en cada perfil SIP que no necesite chat entrante. Corta el mensaje antes de que se lea el destinatario.
3. **No te quedes con las medidas a medias.** El aviso aclara que exigir autenticación a los mensajes *"is not sufficient on its own"*, y que `disable_system_api_commands=true` quita los comandos del sistema operativo pero deja los demás. Sirven como capa extra, no como solución.
4. **Borrá los usuarios sin contraseña.** La configuración de ejemplo trae dos, el `default` y el de Skinny. Según el aviso, un usuario de directorio sin contraseña ni hash se autentica, salvo que tenga `allow-empty-password` en `false`.
5. **Revisá la central como comprometida si estuvo expuesta.** Cuentas o configuración que no pusiste, llamadas a destinos raros en el registro de llamadas y procesos que no corresponden. El plazo de CISA trae triaje forense por algo. Las centrales ya tuvieron su turno este año con [Sangoma Switchvox](/noticia/switchvox-cve-2026-9586-explotacion-confirmada-shells).

## ¿Cómo sé si mi servidor con OpenSSH está expuesto a CVE-2026-123158?

La falla no es de todos los Linux. OpenSSH la describe en las [notas de la versión 10.6p2](https://www.openssh.org/releasenotes.html#10.6p2) como *"a critical security workaround that affects Linux systems that use the musl libc and PAM disabled"*. El caso típico es **Alpine Linux**, que usa musl. El [aviso de Alpine](https://alpinelinux.org/posts/2026-11-05-openssh-password-authentication-bypass/) es directo: *"An attacker who can reach your SSH port can log in without knowing any password."*

Según Alpine, te toca si se cumple todo esto:

1. Usás el paquete `openssh-server` de Alpine, que viene sin PAM, o `openssh-server-pam` con `UsePAM no`.
2. El inicio de sesión por contraseña está activado, `PasswordAuthentication yes`, que es lo que trae OpenSSH por defecto.
3. Hay una cuenta sin contraseña, marcada con `*`: root desde Alpine 3.16, los usuarios que solo entran con clave SSH o los que vienen de un directorio.
4. Hay al menos otra cuenta local con contraseña.

Alpine aclara a quién no le toca: *"Docker and other container users that do not run sshd in the container."* Y revisó todas sus versiones, de la 3.16 a la 3.24, y las encontró afectadas.

## ¿Qué hago si tengo Alpine Linux con SSH?

1. **Actualizá el paquete y reiniciá sshd.** Alpine lo remarca: *"Upgrading the package alone does not replace the running daemon."* Sin el reinicio, el proceso viejo sigue atendiendo.
2. **Si no podés actualizar, apagá el inicio de sesión por contraseña.** `PasswordAuthentication no` en la configuración de sshd, y reiniciar. Según Alpine, *"Either one closes the hole."*
3. **Revisá los ingresos con contraseña.** En el registro de autenticación, un ingreso por contraseña de una cuenta que solo debería entrar con clave SSH es la señal a buscar.
4. **No dejes el puerto SSH abierto a internet si no hace falta.** Las alternativas para llegar a un servidor sin exponerlo están en [la guía de acceso remoto seguro](/guia/acceso-remoto-seguro-sin-vpn) y en [la comparativa de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

Las notas de OpenSSH traen además una advertencia: el equipo recibe muchos reportes encontrados con IA, y vio fallas descubiertas por una IA que después otro investigador encontró por su cuenta. Por eso concluye que *"adversaries who do not report bugs to OSS projects are likely to be able to discover these bugs too"* y que va a publicar versiones más seguido.

## GPUStack y Aspera Faspex: quién tiene que mirarlas

**GPUStack** es un gestor de código abierto para servir modelos de IA en servidores con GPU. El [aviso de GPUStack](https://github.com/gpustack/gpustack/security/advisories/GHSA-gj9m-f8ph-pmhr) explica que, antes de la 2.2.0, cualquier pedido que pareciera venir del propio equipo entraba como administrador. Con un proxy inverso en el mismo servidor, la forma más común de publicarlo, todo pedido de afuera parece local. El aviso cuenta un caso real: *"observed exploited in the wild against a v2.1.1 deployment (attacker-created custom backends running reverse shells)"*. Si lo usás, actualizá a la 2.2.0; mientras tanto, `force_auth_localhost=true`. Revisá si hay backends de inferencia o claves de API de administrador que no creaste.

**IBM Aspera Faspex** es una plataforma de transferencia de archivos grandes, más de empresa grande que de PyME. El [boletín de IBM](https://www.ibm.com/support/pages/node/7295921) dice que un usuario autenticado puede escalar a administrador, y la corrige en la 5.0.19 PL1. Hay una incoherencia: el texto habla de un usuario autorizado, pero el vector del 9,8 que IBM cargó en el registro del CVE dice que no hace falta ningún privilegio.

## Del CVE al plazo de CISA: 8 días como máximo

```svg
<svg viewBox="0 0 680 244" role="img" aria-label="Cronología de las cuatro fallas, en noviembre de 2026, según NVD y los avisos. 4 de noviembre: se publica el CVE de GPUStack, que corrige la versión 2.2.0. 5 de noviembre: sale OpenSSH 10.6p2 y Alpine Linux publica su aviso, todavía sin número de CVE; el mismo día se publica el CVE de FreeSWITCH, que corrige la versión 1.11.3. 7 de noviembre: se publica el CVE de IBM Aspera Faspex. 9 de noviembre: CISA suma las cuatro a su catálogo de vulnerabilidades explotadas. 12 de noviembre: vence el plazo para las agencias federales de Estados Unidos, con triaje forense obligatorio.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">De la publicación del CVE al plazo de CISA: 8 días como máximo</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Noviembre de 2026. El rojo marca el vencimiento del plazo.</text>
  <path d="M84 58 V206" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">4 nov.</text>
  <circle cx="84" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Se publica el CVE de GPUStack; corrige la 2.2.0</text>
  <text x="20" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov.</text>
  <circle cx="84" cy="92" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">OpenSSH 10.6p2 y aviso de Alpine Linux, todavía sin CVE</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov.</text>
  <circle cx="84" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Se publica el CVE de FreeSWITCH; corrige la 1.11.3</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">7 nov.</text>
  <circle cx="84" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Se publica el CVE de IBM Aspera Faspex</text>
  <text x="20" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">9 nov.</text>
  <circle cx="84" cy="170" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">CISA suma las cuatro al catálogo de explotadas</text>
  <text x="20" y="200" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">12 nov.</text>
  <circle cx="84" cy="196" r="5" class="ch-dot-hi" fill="#e23a3a"/>
  <text x="100" y="200" class="ch-strong" font-size="10.5" fill="currentColor">Vence el plazo para las agencias federales, con triaje forense</text>
  <text x="20" y="234" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: NVD, catálogo KEV de CISA, OpenSSH y Alpine Linux.</text>
</svg>
```

El plazo de 3 días con triaje forense es el que fija la directiva BOD 26-04 para las fallas explotadas, de impacto total y expuestas o automatizables, como se explicó con [la falla de Ivanti Connect Secure](/noticia/ivanti-connect-secure-cve-2026-111357-118527-triage-forense). Para una empresa privada no es obligatorio, pero es la mejor señal de urgencia disponible: entre la publicación del CVE y el vencimiento pasaron 5 días en el caso de Aspera y 8 días en el de GPUStack.

## ¿Quién puede ignorar las cuatro fallas del 9 de noviembre?

Quien no tenga una central con FreeSWITCH, ni servidores con Alpine u otro Linux con musl que corran sshd con contraseña, ni GPUStack, ni Aspera Faspex 5. Una central de otra marca, un Linux con glibc como Debian, Ubuntu o Red Hat, o un contenedor de Alpine sin sshd quedan afuera. El orden general de prioridades para lo demás está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** el catálogo de vulnerabilidades explotadas de CISA; las fichas de NVD de los cuatro CVE; el aviso de seguridad de FreeSWITCH; las notas de OpenSSH 10.6p2 y el aviso de Alpine Linux del 5 de noviembre; el aviso de GPUStack; y el boletín de IBM. **Sin confirmar:** quién explota cada falla y contra cuántos equipos, porque ninguno de los avisos da cifras; y si FusionPBX u otras centrales armadas sobre FreeSWITCH ya publicaron versiones con la 1.11.3.

## Preguntas frecuentes sobre FreeSWITCH, OpenSSH, GPUStack y Aspera

### ¿Qué versión de FreeSWITCH corrige CVE-2026-116779?

La 1.11.3. Las versiones hasta la 1.11.2 están afectadas con la configuración de fábrica. Si no podés actualizar, el aviso recomienda enable-chat=false en cada perfil SIP que no use chat entrante.

### ¿Me afecta CVE-2026-123158 de OpenSSH si uso Ubuntu o Debian?

No. La falla afecta a Linux con la biblioteca musl y sin PAM, como Alpine Linux. Ubuntu, Debian y Red Hat usan glibc. Tampoco afecta a contenedores de Alpine que no corren sshd.

### ¿Alcanza con actualizar el paquete openssh en Alpine?

No del todo. Alpine aclara que hay que reiniciar sshd, porque el proceso que ya estaba corriendo sigue siendo el viejo. Si no podés actualizar, apagar el inicio de sesión por contraseña también cierra la falla.

### ¿Por qué la falla de FreeSWITCH es tan grave para una central telefónica?

Porque viene expuesta con la configuración de fábrica y da ejecución de comandos y control total de las llamadas. Alcanza con que la central acepte un mensaje SIP, y en algunas instalaciones ni siquiera hace falta una cuenta.

### ¿Hasta cuándo hay que actualizar?

CISA les dio a las agencias federales hasta el 12 de noviembre de 2026, 3 días, con triaje forense. Para una empresa privada no hay un plazo legal, pero las cuatro ya se explotan y las cuatro tienen versión corregida.
