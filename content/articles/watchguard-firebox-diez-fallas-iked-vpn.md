---
title: "WatchGuard Firebox: diez fallas en el mismo proceso VPN, tres críticas"
subtitle: Ninguna está explotada todavía. Pero el mismo demonio entró dos veces al catálogo de explotación activa de CISA en 2025, y la segunda vez el plazo fue de siete días.
excerpt: WatchGuard publicó 28 avisos el 27 de agosto y diez son del proceso iked, el que atiende la VPN. Tres son críticas con 9,3 en CVSS 4.0. Qué versión te salva y por qué el antecedente importa más que el puntaje.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-09-02
updated: 2026-09-02
meta_title: "WatchGuard Firebox: 10 fallas en el proceso VPN"
meta_description: "WatchGuard publicó 28 avisos y diez son del proceso iked de la VPN, tres críticas con 9,3. Qué versión de Fireware corrige y por qué conviene apurarse."
---

WatchGuard publicó **28 avisos de seguridad el 27 de agosto**, y **diez de ellos son del mismo componente**: `iked`, el demonio que atiende las conexiones VPN IKEv2 del Firebox. Todos se disparan **antes de autenticarse**. Tres están calificados como críticos con **9,3 en la escala CVSS 4.0**, asignados por el propio fabricante.

Las versiones que corrigen son **Fireware OS 2026.2.2, 12.12.2 y 12.5.20**. WatchGuard afirma en cada uno de los avisos que **no tiene conocimiento de explotación en la vida real**.

Ese último dato es el que baja la urgencia. El antecedente del mismo proceso es el que la vuelve a subir.

## Qué es iked y por qué diez fallas ahí son un problema

`iked` es el proceso que negocia los túneles IKEv2 del Firebox: el que atiende a los empleados que se conectan por VPN y a los túneles entre sucursales. Por definición **escucha en la interfaz que mira a internet y responde antes de saber quién es el que habla**. No hay contraseña que lo proteja, porque su trabajo es justamente atender a desconocidos hasta averiguar si son legítimos.

Un firewall de borde con un servicio pre-autenticación agujereado es el mismo patrón que dejó a [seis fallas de Citrix NetScaler](/noticia/citrix-netscaler-sql-server-seis-fallas-explotadas) explotándose este mes, y el que convierte a una escalada local como la [del kernel de Linux que usaron los agentes de OpenAI](/noticia/kernel-linux-cve-2026-53362-agentes-openai) en el segundo paso de una cadena que empieza justo acá.

## Qué dice WatchGuard y qué dicen los titulares sobre las tres críticas

Acá conviene leer al fabricante y no el resumen, porque no coinciden.

Varias coberturas dicen que las tres críticas permiten "ejecutar código remotamente". Los avisos de WatchGuard son más cuidadosos. Lo que está **demostrado** en los tres es que el atacante puede **tirar abajo el demonio**; la ejecución de código aparece como posibilidad, no como hecho:

| CVE | Lo demostrado | Lo que el aviso dice sobre ejecución de código |
|---|---|---|
| CVE-2026-19313 | Desborde de montón, caída del demonio | "con el potencial de ejecución remota de código" |
| CVE-2026-19315 | Lectura fuera de límites y `free()` sobre un puntero influido | "puede también presentar potencial para […] ejecución remota de código" |
| CVE-2026-19318 | Desborde de pila, caída y denegación de servicio | "puede acarrear potencial de ejecución remota de código" |

La diferencia no es cosmética. Un demonio que se cae y **vuelve a levantar solo** es una interrupción de la VPN; una ejecución de código es el control del firewall. Hoy lo primero está confirmado y lo segundo es una hipótesis del propio fabricante sobre su propio código.

Hay además una precondición que cambia a quién le toca: **CVE-2026-19318 solo se explota si está activado el registro de diagnóstico de cargas IKE**, que es una opción de solución de problemas, no el estado normal del equipo.

```svg
<svg viewBox="0 0 660 200" role="img" aria-label="Reparto de las diez fallas del proceso iked publicadas el 27 de agosto de 2026: tres tituladas como ejecución remota de código con CVSS 9.3 crítico en escala 4.0, y siete tituladas como denegación de servicio, con puntajes verificados de 8.7 alto y 6.9 medio. Ninguna con explotación conocida">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Las diez fallas de iked, por lo que declara el aviso</text>
  <text x="192" y="61" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">Tituladas RCE</text>
  <rect x="200" y="46" width="150" height="20" rx="3" fill="#e23a3a" opacity="0.65"/>
  <text x="358" y="61" font-size="10.5" font-weight="700" fill="#e23a3a">3 — CVSS 9,3 crítico</text>
  <text x="192" y="97" text-anchor="end" font-size="10.5" fill="currentColor">Tituladas DoS</text>
  <rect x="200" y="82" width="350" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="558" y="97" font-size="10.5" font-weight="700" fill="currentColor">7</text>
  <text x="20" y="132" font-size="10.5" fill="currentColor" opacity="0.8">Puntajes verificados uno por uno: 9,3 crítico las tres de RCE;</text>
  <text x="20" y="150" font-size="10.5" fill="currentColor" opacity="0.8">8,7 alto y 6,9 medio entre las de denegación de servicio. Todos en CVSS 4.0.</text>
  <text x="20" y="180" font-size="11.5" font-weight="600" fill="currentColor">Las diez son pre-autenticación. Ninguna con explotación conocida.</text>
</svg>
```

Las diez las reportó **McCaulay Hudson, de watchTowr**, según los créditos de los propios avisos.

## Por qué el antecedente de iked pesa más que el puntaje

Si esto fuera la primera vez, la respuesta razonable sería parchear en la próxima ventana. No es la primera vez. **El mismo proceso `iked` puso dos vulnerabilidades en el catálogo KEV de CISA en cinco semanas**, a fines de 2025:

| CVE | Entró al KEV | Plazo | Qué era |
|---|---|---|---|
| CVE-2025-9242 | 12 de noviembre de 2025 | 3 de diciembre | Escritura fuera de límites en `iked` |
| CVE-2025-14733 | 19 de diciembre de 2025 | **26 de diciembre** | Escritura fuera de límites en `iked` |

Siete días de plazo en la segunda. CISA reserva ese tramo para lo que se está explotando en volumen.

Y hubo volumen: cuando se publicó CVE-2025-14733, la fundación Shadowserver contó **más de 124.000 Firebox sin parchear expuestos a internet**, que al día siguiente seguían siendo unos 117.000. La ventana entre el parche y la explotación masiva fue de días, no de meses.

```svg
<svg viewBox="0 0 660 190" role="img" aria-label="Historial del proceso iked: CVE-2025-9242 entró al catálogo KEV el 12 de noviembre de 2025 con plazo al 3 de diciembre, CVE-2025-14733 entró el 19 de diciembre de 2025 con plazo de solo siete días al 26 de diciembre, y el 27 de agosto de 2026 se publicaron diez fallas nuevas del mismo proceso, todavía sin explotación conocida">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">El proceso iked, en los últimos diez meses</text>
  <line x1="30" y1="108" x2="640" y2="108" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <circle cx="110" cy="108" r="5" fill="#e23a3a"/>
  <text x="110" y="82" text-anchor="middle" font-size="10" font-weight="700" fill="#e23a3a">12 nov 2025</text>
  <text x="110" y="96" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.8">CVE-2025-9242</text>
  <text x="110" y="130" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.75">al KEV, plazo 21 días</text>
  <circle cx="330" cy="108" r="5" fill="#e23a3a"/>
  <text x="330" y="146" text-anchor="middle" font-size="10" font-weight="700" fill="#e23a3a">19 dic 2025</text>
  <text x="330" y="160" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.8">CVE-2025-14733, plazo 7 días</text>
  <text x="330" y="174" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.75">124.000 equipos expuestos</text>
  <circle cx="570" cy="108" r="5" fill="currentColor"/>
  <text x="570" y="82" text-anchor="middle" font-size="10" font-weight="700" fill="currentColor">27 ago 2026</text>
  <text x="570" y="96" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.8">10 fallas nuevas</text>
  <text x="570" y="130" text-anchor="middle" font-size="9" fill="currentColor" opacity="0.75">sin explotación conocida</text>
</svg>
```

Nada de esto prueba que las diez de agosto se vayan a explotar. Lo que dice es cuánto tiempo hubo la última vez entre "hay parche" y "hay que apagar el incendio".

## ¿Cómo sé si mi WatchGuard Firebox está afectado?

En el panel del Firebox, la versión de Fireware figura en la pantalla de estado del sistema. Comparala contra esto, que sale de la tabla de estado de producto de los avisos:

| Línea de equipo | Afectada | Corregida en |
|---|---|---|
| Firebox en general | 2025.0 a 2026.2.1 · 12.0 a 12.12.1 | **2026.2.2** o **12.12.2** |
| T15 y T35 | 12.0 a 12.5.19 | **12.5.20** |

Si estás por debajo de la columna de la derecha, te toca.

La segunda comprobación es la que suele encontrar el problema de verdad: **entrá a la configuración de VPN y fijate qué hay levantado que ya no usa nadie**. En la tanda de diciembre de 2025 la superficie vulnerable incluía túneles de sucursal apuntando a un extremo fijo que seguían configurados aunque la sucursal ya no existiera. Es la clase de cosa que nadie apaga porque nadie recuerda que está.

## ¿Quién puede ignorar el aviso de WatchGuard?

- **Quien no tiene un Firebox.** El fallo es del sistema operativo del equipo, no de un protocolo.
- **Quien ya corre 2026.2.2, 12.12.2 o 12.5.20.**
- **Quien tiene un Firebox sin ninguna VPN IKEv2 ni túnel de sucursal configurado.** Sin nada que negocie, `iked` no tiene con qué hablar. Vale confirmarlo mirando la configuración, no de memoria.

Que no te toque este aviso no significa que el equipo esté al día: de los 28 avisos del 27 de agosto, dieciocho son de otros componentes.

## Qué hacer con el Firebox, en orden

1. **Anotá la versión de Fireware** y comparala contra la tabla. Es un minuto.
2. **Si estás afectado, programá la actualización.** Un firewall de borde se actualiza en ventana, con la consola física o el acceso alternativo a mano: si algo sale mal, perdés el camino por el que te ibas a conectar a arreglarlo.
3. **Limpiá la configuración de VPN.** Túneles de sucursal muertos, perfiles de usuarios que ya no están, el registro de diagnóstico de cargas IKE que alguien dejó prendido en un caso de soporte de hace un año —ese último es, literalmente, la precondición de CVE-2026-19318.
4. **Restringí desde dónde se puede negociar la VPN**, si tu esquema lo permite. Reduce la superficie sin depender del parche.
5. **Revisá que la administración del Firebox no esté publicada** a internet. Es otra cosa distinta, y es la que convierte un problema en una catástrofe.

Si estás reevaluando el esquema completo, la alternativa de fondo está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn): mover el acceso a un modelo donde no haya un servicio pre-autenticación escuchando en el borde. Es el camino que proponen productos como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate), donde el usuario no llega nunca a hablarle a un demonio publicado. No es un cambio de una semana, pero es la respuesta estructural a esta clase repetida de falla.

## Preguntas frecuentes sobre las fallas de WatchGuard Firebox

### ¿Están explotando estas fallas del Firebox ahora mismo?
No, según WatchGuard. Cada uno de los avisos dice que el fabricante no tiene conocimiento de explotación en la vida real, y ninguna de las diez figura en el catálogo KEV de CISA al 2 de septiembre de 2026. Lo que sí está en el catálogo son dos fallas anteriores del mismo proceso, de noviembre y diciembre de 2025.

### ¿El puntaje 9,3 se compara con los CVSS que veo en otros avisos?
Solo si el otro también es CVSS 4.0. WatchGuard usa la escala 4.0 en estos avisos, y no es directamente comparable con la 3.1, que es la que sigue publicando NVD para la mayoría de los CVE. Al citar el número conviene decir la escala.

### ¿Alcanza con bloquear la VPN en el firewall perimetral?
En este caso el firewall perimetral es el equipo afectado, así que no hay dónde bloquearlo por delante. Se puede limitar desde qué direcciones se acepta la negociación, que reduce la exposición, pero la corrección es la actualización de Fireware.

### ¿Qué pasa si solo uso la VPN SSL y no IKEv2?
Las diez fallas son del proceso `iked`, que atiende IKEv2. Si no tenés IKEv2 ni túneles de sucursal levantados, esa superficie no está expuesta. Conviene verificarlo en la configuración: los túneles viejos que quedaron activos fueron parte del problema en la tanda anterior.

### ¿Por qué salieron diez fallas del mismo componente juntas?
Las diez las reportó el mismo investigador, McCaulay Hudson de watchTowr. Cuando alguien audita a fondo un componente concreto, es habitual que salga un lote entero de una vez en lugar de goteo. Que aparezcan juntas no significa que sean parte de un ataque.

---

**Fuentes primarias.** Avisos del PSIRT de WatchGuard para [CVE-2026-19313](https://psirt.watchguard.com/CVE-2026-19313/), [CVE-2026-19315](https://psirt.watchguard.com/CVE-2026-19315/) y [CVE-2026-19318](https://psirt.watchguard.com/CVE-2026-19318/), y el [índice completo del PSIRT](https://psirt.watchguard.com/) · Catálogo KEV de CISA, versión 2026.09.01, para CVE-2025-9242 y CVE-2025-14733 · Conteo de equipos expuestos: Shadowserver, diciembre de 2025.

Consultadas el 2 de septiembre de 2026. Para el vocabulario, [el glosario](/guia/glosario-ciberseguridad-pymes); para el orden general de prioridades, [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026). El mismo patrón de software propio que se parchea a mano apareció en [Zimbra y TrueConf](/noticia/zimbra-trueconf-software-autoalojado-parche-propio).
