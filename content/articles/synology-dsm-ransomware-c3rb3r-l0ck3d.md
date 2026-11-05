---
title: "Synology DSM: el ransomware C3RB3R cifra NAS desactualizados; qué versión instalar"
subtitle: Synology confirmó los ataques el 29 de octubre y dice que la entrada es una falla corregida en diciembre de 2024. Qihoo 360, que analizó equipos cifrados, apunta a dos fallas de 9,8 de septiembre de 2026. En las ramas con soporte, las dos se resuelven con la misma actualización.
excerpt: El ransomware C3RB3R cifra NAS Synology con DSM viejo y deja archivos .L0CK3D. Qué versión de DSM corrige, cómo saber si el tuyo está expuesto y qué hacer.
type: news
status: published
category: backup-y-recuperacion
author: ian-poletti-lucero
published: 2026-11-05
updated: 2026-11-05
products: []
meta_title: "Ransomware C3RB3R en Synology: qué DSM instalar"
meta_description: "El ransomware C3RB3R cifra NAS Synology con DSM viejo y deja archivos .L0CK3D. Qué versión corrige, cómo saber si tu NAS está expuesto y qué hacer hoy."
---

**Synology** confirmó el 29 de octubre que hay atacantes cifrando NAS que corren versiones viejas de su sistema, **DSM**. El [comunicado de Synology](https://www.synology.com/en-global/company/news/article/C3RB3R/Synology%20urges%20users%20to%20update%20DSM%20following%20ransomware%20attacks%20on%20outdated%20DSM%20versions) es corto: *"Synology has identified threat actors exploiting a vulnerability patched in December 2024 to target devices running outdated versions of DSM. These attacks aim to encrypt data and extort ransom."* Es decir, una falla con casi 2 años de corrección disponible.

El mismo día, la empresa china de seguridad **Qihoo 360** publicó una [alerta con el análisis de equipos atacados](https://news.safe.360.cn/n/13055.html). Dice que desde octubre recibió varios pedidos de ayuda, que el ransomware es **C3RB3R**, una variante de la familia Cerber, y que la entrada son dos fallas que Synology corrigió el **18 de septiembre de 2026**: **CVE-2026-13639** y **CVE-2026-13684**. Lo describe como un ataque de día uno: las fallas tenían apenas 1 mes de publicadas y muchos equipos seguían sin actualizar.

Las dos fuentes no coinciden en la falla. Para el dueño del NAS cambia poco: las versiones actuales de las ramas con soporte corrigen las dos. Lo que importa es cuál es la tuya y si el NAS se ve desde internet.

| Dato | Ransomware C3RB3R en Synology |
|---|---|
| Qué hace | Cifra los archivos del NAS, les agrega `.L0CK3D` y deja `read-me3.txt` en cada carpeta |
| Entrada, según Synology | Una falla corregida en diciembre de 2024, del aviso SA-24:20 |
| Entrada, según Qihoo 360 | CVE-2026-13639 y CVE-2026-13684, del aviso SA-26:13 |
| Puntaje | 9,8, crítico, en CVSS 3.1, asignado por Synology a las dos de 2026 |
| NVD | Sin análisis propio de las dos de 2026 al 5 de noviembre |
| Catálogo de CISA | Ninguna de las tres fallas citadas figura al 5 de noviembre |

## Synology DSM: qué dicen los avisos SA-24:20 y SA-26:13

```svg
<svg viewBox="0 0 680 270" role="img" aria-label="Cronología del ransomware C3RB3R contra NAS Synology. 1 de octubre de 2024: DSM 6.2 sale del soporte extendido de Synology. 5 de noviembre de 2024: Synology publica el aviso SA-24:20, por fallas mostradas en Pwn2Own 2024, con las primeras correcciones. 5 de diciembre de 2024: llega la corrección de ese aviso para DSM 6.2. 18 de septiembre de 2026: aviso SA-26:13, con CVE-2026-13684 y CVE-2026-13639, de 9,8 cada una. Octubre de 2026: la empresa Qihoo 360 recibe varios pedidos de ayuda por NAS cifrados. 29 de octubre de 2026: Synology dice que los atacantes explotan una falla corregida en diciembre de 2024. El mismo día, Qihoo 360 dice que la entrada son las dos fallas de septiembre de 2026. Las dos versiones no coinciden; actualizar a la última versión de cada rama cubre ambos avisos.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Synology DSM y C3RB3R: dos avisos y dos versiones de la entrada</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">El rojo marca el comunicado de Synology. Synology y Qihoo 360 no señalan la misma falla.</text>
  <path d="M128 58 V232" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">1 oct. 2024</text>
  <circle cx="128" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">DSM 6.2 sale del soporte extendido de Synology</text>
  <text x="20" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov. 2024</text>
  <circle cx="128" cy="92" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Aviso SA-24:20 (fallas de Pwn2Own 2024): primeras correcciones</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 dic. 2024</text>
  <circle cx="128" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Llega la corrección de SA-24:20 para DSM 6.2</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">18 sep. 2026</text>
  <circle cx="128" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Aviso SA-26:13: CVE-2026-13684 y CVE-2026-13639, 9,8 cada una</text>
  <text x="20" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">oct. 2026</text>
  <circle cx="128" cy="170" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Qihoo 360 recibe varios pedidos de ayuda por NAS cifrados</text>
  <text x="20" y="200" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">29 oct. 2026</text>
  <circle cx="128" cy="196" r="5" class="ch-dot-hi" fill="#e23a3a"/>
  <text x="144" y="200" class="ch-strong" font-size="10.5" fill="currentColor">Synology: atacan una falla corregida en diciembre de 2024</text>
  <text x="20" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">29 oct. 2026</text>
  <circle cx="128" cy="222" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="144" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Qihoo 360: la entrada son las dos fallas de septiembre de 2026</text>
  <text x="20" y="260" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: avisos SA-24:20 y SA-26:13 de Synology, comunicado del 29 de octubre y alerta de Qihoo 360.</text>
</svg>
```

El comunicado remite al [aviso Synology-SA-24:20](https://www.synology.com/en-global/security/advisory/Synology_SA_24_20), publicado en noviembre de 2024 por fallas que se mostraron en el concurso Pwn2Own. La más grave es **CVE-2024-10441**, de 9,8, que permite a un atacante remoto *"execute arbitrary code via unspecified vectors"*. En ese aviso, las correcciones salieron por partes: la mayoría en noviembre y la de **DSM 6.2 el 5 de diciembre de 2024**, la única de diciembre. Synology no aclara si se refiere a esa.

El [aviso Synology-SA-26:13](https://www.synology.com/en-global/security/advisory/Synology_SA_26_13) es más reciente y corrige ocho fallas. Las dos que cita Qihoo 360 permiten, según Synology, que un atacante remoto sin cuenta lea o escriba archivos arbitrarios: CVE-2026-13684 está en el componente SCGI de DSM, y CVE-2026-13639 es un problema de aleatoriedad insuficiente en el inicio de sesión. Las dos tienen 9,8 y el aviso dice *"Mitigation: None"*: no hay forma de cerrarlas sin actualizar.

## ¿Qué versión de Synology DSM corrige las fallas que usa C3RB3R?

| Rama de DSM | Versión mínima | Aviso SA-24:20 | Aviso SA-26:13 |
|---|---|---|---|
| 7.4 | 7.4-90075 | Fuera del rango afectado | Corregida |
| 7.3 | 7.3.2-86009-4 | Fuera del rango afectado | Corregida |
| 7.2.2 | 7.2.2-72806-9 | Corregida desde 7.2.2-72806-1 | Corregida |
| 7.2.1 | 7.2.1-69057-12 | Corregida desde 7.2.1-69057-6 | Corregida |
| 7.2 | 7.2-64570-4 | Corregida | No figura en el aviso |
| 7.1 | 7.1.1-42962-7 | Corregida | No figura en el aviso |
| 6.2 | 6.2.4-25556-8 | Corregida | No figura; sin soporte desde el 1 de octubre de 2024 |

"Fuera del rango afectado" sale de las fichas de los CVE de SA-24:20, que afectan a las versiones anteriores a las corregidas de 7.2.2 y menores. "No figura" quiere decir que el aviso SA-26:13 no lista esa rama: Synology no dice si está afectada ni publica una corrección para ella. Si tu NAS está en DSM 7.2, 7.1 o 6.2, lo seguro es pasar a una rama con corrección, si el modelo lo admite. Synology publica qué modelos soporta cada versión en su [página de estado de productos](https://www.synology.com/en-global/products/status). Para DSM 6.2, la misma página dice que desde el 1 de octubre de 2024 ya no recibe *"functionality, security, and package updates"*: lleva más de 2 años sin soporte, salvo la corrección excepcional de diciembre de 2024.

## ¿Cómo sé si mi NAS Synology está expuesto a C3RB3R?

1. **Mirá la versión.** En DSM, *Panel de control > Centro de información* muestra la versión exacta. Comparala con la tabla de arriba.
2. **Mirá si se ve desde internet.** Las tres fallas son remotas: el atacante tiene que llegar a la interfaz de DSM. Revisá en el router si hay puertos reenviados al NAS, en especial 5000 y 5001, los de la interfaz web de DSM por defecto. Y probá desde un teléfono con datos móviles, fuera de la red de la oficina, si la página de inicio de sesión del NAS carga con la IP pública o con el nombre de DDNS.
3. **Buscá las señales del ataque.** Archivos con la extensión `.L0CK3D` o notas `read-me3.txt` en las carpetas compartidas. Con acceso por SSH, `find /volume1 -name 'read-me3.txt'` lo encuentra. En el *Centro de registros* de DSM, Qihoo 360 pide mirar inicios de sesión raros y modificaciones masivas de archivos.

## Qué hace el ransomware C3RB3R adentro de un NAS Synology

```svg
<svg viewBox="0 0 680 262" role="img" aria-label="Qué hace el ransomware C3RB3R en un NAS Synology, según el análisis de Qihoo 360. Primero entra por una falla remota de DSM, sin necesidad de cuenta. Después recorre los volúmenes, salvo 27 carpetas del sistema y los archivos ejecutables. Luego cifra cada archivo con ChaCha8 y protege la clave con RSA-2048, y le agrega la extensión .L0CK3D. Por último deja una nota llamada read-me3.txt en cada carpeta cifrada. Las copias de respaldo guardadas en el mismo NAS se cifran junto con lo demás, por eso Qihoo 360 pide una copia desconectada.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Ransomware C3RB3R: qué hace en un NAS Synology</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según el análisis de Qihoo 360. El rojo marca por dónde entra.</text>
  <rect x="16" y="62" width="204" height="62" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="118" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Entra por DSM</text>
  <text x="118" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">falla remota, sin cuenta</text>
  <rect x="238" y="62" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="340" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Recorre los volúmenes</text>
  <text x="340" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">salvo 27 carpetas del sistema</text>
  <rect x="460" y="62" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Cifra cada archivo</text>
  <text x="562" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">ChaCha8 + RSA-2048, .L0CK3D</text>
  <path d="M222 93 H234 M229 88 L234 93 L229 98" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 93 H456 M451 88 L456 93 L451 98" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 124 V152 M557 147 L562 152 L567 147" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="460" y="158" width="204" height="46" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="186" class="ch-strong" font-size="10.5" text-anchor="middle" font-weight="600" fill="currentColor">Deja read-me3.txt por carpeta</text>
  <text x="20" y="178" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Las copias que viven en el mismo NAS</text>
  <text x="20" y="196" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">se cifran junto con lo demás.</text>
  <text x="20" y="226" class="ch-label" font-size="11" fill="currentColor" opacity="0.8">Por eso Qihoo 360 pide al menos una copia desconectada del NAS.</text>
  <text x="20" y="250" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: alerta de Qihoo 360 del 29 de octubre de 2026.</text>
</svg>
```

Según el análisis de Qihoo 360, C3RB3R recorre todo el sistema de archivos salvo 27 carpetas del sistema, para no romper el NAS y poder seguir cifrando. Saltea los ejecutables y los archivos que ya cifró. Cifra cada archivo con ChaCha8, una clave distinta por archivo, y protege esas claves con una clave pública RSA-2048 que trae adentro. Al final agrega `.L0CK3D` al nombre y deja `read-me3.txt` en cada carpeta, con un identificador del equipo de seis caracteres.

Para una PyME, lo grave no es el NAS en sí: es lo que guarda. Si el NAS es también el destino del backup de las computadoras, el ransomware cifra el original y la copia en la misma pasada. Es el mismo riesgo que se vio con [las dos fallas explotadas en los servidores de backup Ahsay](/noticia/ahsay-ahsaycbs-dos-explotadas-servidor-de-backup).

## ¿Qué hago si tengo un NAS Synology en la empresa?

1. **Actualizá DSM hoy.** En *Panel de control > Actualizar y restaurar*, instalá la versión de la tabla para tu rama. Synology recomienda además activar las actualizaciones automáticas. Antes, verificá que tengas una copia de los datos fuera del NAS.
2. **Sacá DSM de internet.** Borrá los reenvíos de puertos del router hacia el NAS. Qihoo 360 recomienda no publicar los puertos de administración y, si hace falta acceso externo, usar el servicio de acceso del fabricante o una VPN. Una red privada como [Tailscale, que tiene cliente para Synology](/producto/tailscale-business), permite llegar al NAS sin abrir puertos; las alternativas están en [la guía de acceso remoto seguro sin VPN tradicional](/guia/acceso-remoto-seguro-sin-vpn).
3. **Tené una copia que el NAS no pueda tocar.** Qihoo 360 pide al menos una copia desconectada, que no esté unida al NAS mientras no se usa. Synology ofrece además [instantáneas inmutables](https://www.synology.com/en-global/dsm/7.4/software_spec/snapshot_replication) en las versiones recientes de DSM y en los modelos compatibles: durante el período de protección no se pueden borrar. Las opciones de copia externa están en [la comparativa de backup y recuperación](/productos/backup-y-recuperacion), y es uno de los requisitos de [las aseguradoras de ciberriesgo](/guia/ciberseguro-pyme-que-exigen-las-aseguradoras).
4. **Cerrá las cuentas.** Qihoo 360 recomienda desactivar la cuenta de administrador de fábrica, usar contraseñas fuertes, activar la verificación en dos pasos y el bloqueo automático de IP. Para la verificación en dos pasos en el resto de la empresa está [la guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).
5. **Si ya ves archivos `.L0CK3D`,** desconectá el NAS de la red, como pide Qihoo 360, y no lo vuelvas a conectar hasta actualizarlo. Restaurá desde la copia externa, no desde una carpeta del mismo NAS.

Si en el NAS corre además Plex, sumá a la misma ventana [las cuatro fallas de Plex Media Server explotadas en cadena](/noticia/plex-media-server-cuatro-fallas-cadena-1-43-3-10861). Si el modelo no admite una rama con corrección, el NAS no debería verse desde internet bajo ninguna circunstancia. Y conviene planear el reemplazo: un equipo que no recibe parches va a seguir siendo blanco.

## ¿Quién puede ignorar el aviso de Synology DSM?

Quien no tenga un NAS Synology. Y quien tenga uno en la versión de la tabla o posterior, sin puertos abiertos a internet y con una copia fuera del equipo. El aviso SA-24:20 también afectaba a BeeStation OS antes de la 1.1-65374, pero el comunicado del 29 de octubre habla solo de DSM. Los NAS de otras marcas, como QNAP, tienen sus propios avisos y no entran en este.

**Fuentes leídas:** el comunicado de Synology del 29 de octubre de 2026; los avisos Synology-SA-24:20 y Synology-SA-26:13, con sus tablas de versiones y revisiones; la página de estado de productos de Synology; la alerta de Qihoo 360 del 29 de octubre, en chino; las fichas de NVD de CVE-2024-10441, CVE-2026-13639 y CVE-2026-13684; y el catálogo de vulnerabilidades explotadas de CISA. **Sin confirmar:** cuál es la falla que usan los atacantes, porque Synology y Qihoo 360 dicen cosas distintas; cuántos equipos fueron cifrados; y en qué países, porque ninguna de las dos fuentes da cifras.

## Preguntas frecuentes sobre C3RB3R y Synology DSM

### ¿Qué versión de DSM me protege de C3RB3R?

La actual de cada rama con soporte: 7.4-90075, 7.3.2-86009-4, 7.2.2-72806-9 o 7.2.1-69057-12, o posteriores. Esas cubren tanto la falla que señala Synology como las dos que señala Qihoo 360. DSM 7.2, 7.1 y 6.2 no figuran en el aviso de septiembre de 2026.

### ¿Cómo sé si mi NAS Synology fue cifrado por C3RB3R?

Los archivos cifrados terminan en .L0CK3D y en cada carpeta aparece una nota llamada read-me3.txt. Si ves cualquiera de las dos cosas, desconectá el NAS de la red y restaurá desde una copia que esté fuera del equipo.

### ¿Me afecta si mi NAS Synology no está expuesto a internet?

El riesgo baja mucho, porque las fallas son remotas y el atacante tiene que llegar a la interfaz de DSM. Igual conviene actualizar: un equipo infectado dentro de la red de la oficina también llega al NAS.

### ¿Por qué Synology y Qihoo 360 hablan de fallas distintas?

Ninguna de las dos lo explica. Synology remite a un aviso de noviembre de 2024 y Qihoo 360 a uno de septiembre de 2026, basado en equipos que analizó. Puede que haya más de una vía de entrada. Actualizar a la última versión de la rama cubre las dos.

### ¿Sirven las instantáneas de Synology contra el ransomware?

Las instantáneas comunes pueden borrarse si el atacante toma el control del NAS. Las inmutables, que DSM ofrece en los modelos compatibles, no se pueden borrar durante su período de protección. Igual hace falta una copia fuera del equipo, que es la que pide Qihoo 360.
