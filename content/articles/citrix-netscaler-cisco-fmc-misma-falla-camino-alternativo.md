---
title: "Citrix NetScaler y Cisco FMC: la misma falla de autenticación, el mismo día"
subtitle: Dos fabricantes distintos, dos productos distintos y una descripción idéntica en el catálogo de CISA. Es la cuarta vez en sesenta días que aparece esa clase de error, siempre en equipos de borde.
excerpt: CISA sumó cuatro fallas el 9 de septiembre. Dos de ellas —Citrix NetScaler y Cisco Secure Firewall Management Center— comparten la misma clase: un camino alternativo que no pasa por el control de acceso.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-09-10
updated: 2026-09-10
meta_title: "Citrix NetScaler y Cisco FMC: la misma falla"
meta_description: "Citrix NetScaler y Cisco Firewall Management Center entraron al KEV el mismo día con la misma clase de falla. Qué versión corrige cada una y qué revisar."
---

El 9 de septiembre CISA sumó cuatro vulnerabilidades al catálogo de explotación activa. **Tres vencen el 12 de septiembre** y las tres son de equipos que viven en el borde de la red.

Dos de esas tres, de fabricantes distintos, llegaron con **exactamente la misma descripción**: *elusión de autenticación usando un camino o canal alternativo*.

| CVE | Producto | Plazo | Puntaje | Lo asignó |
|---|---|---|---|---|
| CVE-2026-19490 | Citrix NetScaler ADC y Gateway | **12 sep** | 9,3 (CVSS 4.0) | Citrix |
| CVE-2026-20079 | Cisco Secure Firewall Management Center | **12 sep** | 10,0 (CVSS 3.1) | Cisco |
| CVE-2025-25249 | Fortinet FortiOS y FortiSwitchManager | **12 sep** | 8,1 Fortinet / **9,8 NVD** | ambos |
| CVE-2026-87491 | Google Chromium V8 | 23 sep | 8,8 (CVSS 3.1) | CISA |

## El camino alternativo, por cuarta vez en sesenta días en el KEV

Contando sobre el feed oficial, esa descripción exacta aparece **ocho veces en toda la historia del catálogo**. Seis son de 2026, y cuatro de los últimos sesenta días:

| Entró al KEV | CVE | Producto |
|---|---|---|
| 20 de octubre de 2025 | CVE-2025-2746 y CVE-2025-2747 | Kentico Xperience CMS |
| 26 de enero de 2026 | CVE-2026-23760 | SmarterTools SmarterMail |
| 27 de enero de 2026 | CVE-2026-24858 | Fortinet, varios productos |
| 3 de agosto de 2026 | CVE-2026-18577 | N-able N-central |
| 4 de agosto de 2026 | CVE-2026-18556 | N-able N-central |
| 9 de septiembre de 2026 | CVE-2026-19490 | Citrix NetScaler |
| 9 de septiembre de 2026 | CVE-2026-20079 | Cisco Firewall Management Center |

```svg
<svg viewBox="0 0 660 200" role="img" aria-label="Entradas del catálogo KEV con la descripción elusión de autenticación usando un camino o canal alternativo: ocho en total desde octubre de 2025, de las cuales seis son de 2026 y cuatro de los últimos sesenta días, correspondientes a N-able N-central dos veces, Citrix NetScaler y Cisco Firewall Management Center">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">"Camino alternativo" en el catálogo, por período</text>
  <text x="214" y="60" text-anchor="end" font-size="10.5" fill="currentColor">Total histórico</text>
  <rect x="222" y="45" width="320" height="20" rx="3" fill="currentColor" opacity="0.28"/>
  <text x="550" y="60" font-size="10.5" font-weight="700" fill="currentColor">8</text>
  <text x="214" y="96" text-anchor="end" font-size="10.5" fill="currentColor">Durante 2026</text>
  <rect x="222" y="81" width="240" height="20" rx="3" fill="#e23a3a" opacity="0.45"/>
  <text x="470" y="96" font-size="10.5" font-weight="700" fill="#e23a3a">6</text>
  <text x="214" y="132" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">Últimos 60 días</text>
  <rect x="222" y="117" width="160" height="20" rx="3" fill="#e23a3a" opacity="0.7"/>
  <text x="390" y="132" font-size="10.5" font-weight="700" fill="#e23a3a">4</text>
  <text x="20" y="168" font-size="10.5" fill="currentColor" opacity="0.8">Las cuatro recientes: N-able N-central (dos), Citrix NetScaler y Cisco FMC.</text>
  <text x="20" y="190" font-size="11.5" font-weight="600" fill="#e23a3a">La mitad de todo lo registrado ocurrió en los últimos dos meses</text>
</svg>
```

Que dos fabricantes que no comparten código publiquen la misma clase de falla el mismo día no es coordinación: es que **quien busca, busca eso**. Y encuentra, porque es un error de diseño que se repite en todos los productos que hacen lo mismo.

## Qué es un "camino alternativo" en un NetScaler o un FMC

Un aparato de borde no tiene una sola puerta. Un NetScaler puede estar configurado como servidor virtual de autenticación, como pasarela SSL VPN, como proxy ICA, como proxy RDP. Una consola de administración de firewalls tiene interfaz web, API, integraciones y, casi siempre, algún endpoint interno que quedó de una versión anterior.

Cada una de esas puertas debería pasar por el mismo control de acceso. **La falla aparece cuando una no lo hace.** No hay que romper la autenticación: hay que encontrar la entrada que no la consulta.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Cómo funciona la elusión de autenticación por camino alternativo: la ruta principal pasa por el control de acceso y llega a la función protegida, mientras una segunda ruta —una interfaz vieja, una API interna o un modo de configuración distinto— llega a la misma función sin pasar por el control. El atacante no rompe la autenticación, encuentra la puerta que no la consulta">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Dos caminos, un solo control</text>
  <rect x="24" y="48" width="150" height="52" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="24" y="48" width="150" height="52" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="99" y="70" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">Ruta principal</text>
  <text x="99" y="88" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">la documentada</text>
  <path d="M176 74 L214 74" stroke="currentColor" stroke-width="1.5" opacity="0.65"/>
  <rect x="218" y="48" width="150" height="52" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="218" y="48" width="150" height="52" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="293" y="70" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">Control de acceso</text>
  <text x="293" y="88" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">verifica quién sos</text>
  <path d="M370 74 L470 74" stroke="currentColor" stroke-width="1.5" opacity="0.65"/>
  <rect x="474" y="48" width="182" height="52" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="474" y="48" width="182" height="52" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="565" y="70" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">Función protegida</text>
  <text x="565" y="88" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">lo que el atacante quiere</text>
  <rect x="24" y="126" width="150" height="52" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="24" y="126" width="150" height="52" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="99" y="148" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">Ruta alternativa</text>
  <text x="99" y="166" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.85">interfaz vieja o API</text>
  <path d="M176 152 L470 152" stroke="#e23a3a" stroke-width="1.6" stroke-dasharray="5 4"/>
  <path d="M470 152 L462 148 M470 152 L462 156" stroke="#e23a3a" stroke-width="1.6"/>
  <path d="M540 104 L540 122" stroke="#e23a3a" stroke-width="1.5" opacity="0.7"/>
  <text x="322" y="145" text-anchor="middle" font-size="10" font-weight="700" fill="#e23a3a">no pasa por el control</text>
</svg>
```

En el caso de Cisco, NVD lo describe sin ambigüedad: un atacante remoto sin autenticarse puede **saltear la autenticación y ejecutar archivos de script** en el equipo hasta obtener acceso de root sobre el sistema operativo. Cisco le puso **10,0**. Es el mismo perfil que tenían [las diez fallas del proceso VPN de WatchGuard](/noticia/watchguard-firebox-diez-fallas-iked-vpn): un servicio que atiende antes de saber quién habla.

En el de Citrix, el catálogo precisa la condición: aplica cuando el aparato está configurado **como servidor virtual AAA o como Gateway** —SSL VPN, proxy ICA, CVPN o proxy RDP—. Es la misma familia que dejó [seis fallas de NetScaler explotándose en agosto](/noticia/citrix-netscaler-sql-server-seis-fallas-explotadas).

## Qué versión corrige en NetScaler, FMC y FortiOS

- **Citrix NetScaler ADC y Gateway**: afectadas las ramas **14.1 hasta 73.32** y **13.1 hasta 63.21**. Hay que subir por encima de esos compilados.
- **Cisco Secure Firewall Management Center** y **Security Cloud Control Firewall Management**: la falla se publicó el 4 de marzo, así que la corrección lleva meses disponible en el aviso de Cisco.
- **Fortinet**: alcanza a **FortiOS 7.6.0–7.6.3, 7.4.0–7.4.8, 7.2.0–7.2.11, 7.0.0–7.0.17 y todas las 6.4**, más **FortiSwitchManager 7.2.0–7.2.6 y 7.0.0–7.0.5**. El catálogo menciona además FortiSASE; la lista de versiones de NVD no lo incluye, así que conviene confirmarlo con el fabricante.

Sobre la de Fortinet hay un dato que conviene decir: **Fortinet le asignó 8,1 y NVD le asignó 9,8**, las dos en CVSS 3.1. No es un detalle menor —es la diferencia entre "alto" y "crítico"— y es el tipo de divergencia que aparece cuando el fabricante y el analista independiente pesan distinto el alcance. Al citarla, decí cuál estás usando.

Y una más sobre las fechas: **CVE-2025-25249 se publicó el 13 de enero** y entra al catálogo ocho meses después; **la de Cisco se publicó el 4 de marzo**. Ninguna es nueva. Lo nuevo es que alguien empezó a usarlas.

## Chrome otra vez: segundo cero-día de V8 en seis días

La cuarta incorporación es **CVE-2026-87491**, escritura fuera de límites en el motor V8, con ejecución de código **dentro del sandbox** —el mismo alcance acotado que explicamos [el sábado pasado](/noticia/chrome-cve-2026-85046-exploit-activo-reiniciar)—. Corrige **Chrome 153.0.8010.36**.

Dos datos para ponerla en escala. Primero: es **el segundo V8 en el catálogo en seis días**, lo que refuerza lo que ya contamos, que V8 concentra la mayoría de lo explotado en Chromium. Segundo: **Google la clasifica como severidad Media** en su propio aviso, mientras CISA le carga **8,8**. Otra vez, dos escalas y dos criterios sobre la misma falla.

La acción no cambia: entrar a `chrome://settings/help` y reiniciar el navegador.

## ¿A quién le tocan las fallas del 9 de septiembre?

- **A quien tenga un NetScaler configurado como AAA o Gateway** en las ramas 14.1 o 13.1 por debajo de los compilados indicados.
- **A quien administre firewalls Cisco con FMC o Security Cloud Control.**
- **A quien tenga FortiOS o FortiSwitchManager** en las versiones listadas, que incluyen toda la línea 6.4.
- **A todos, por Chrome**, que se arregla en treinta segundos.

## ¿Quién puede ignorar el aviso del 9 de septiembre?

- **Quien no tenga ninguno de esos cuatro productos.**
- **Quien tenga NetScaler pero no lo use como AAA ni como Gateway.** Conviene confirmarlo en la configuración, no de memoria.
- **Quien esté por encima de las versiones corregidas**, que en el caso de Cisco y Fortinet significa haber actualizado en algún momento de los últimos seis a ocho meses.

## Qué hacer antes del 12 de septiembre, en orden

1. **Anotá qué equipos de borde tenés y en qué versión están.** Sin esa lista, el resto de los pasos no se puede hacer. Es el mismo inventario que faltaba en [la nota sobre el proveedor de IT](/noticia/n-able-n-central-cve-2026-86218-proveedor-it).
2. **Actualizá NetScaler, FMC y FortiOS** según corresponda, con la consola física o el acceso alternativo a mano.
3. **Revisá qué interfaces de administración están publicadas a internet.** La clase de falla de hoy vive en las puertas secundarias: cuantas menos estén expuestas, menos superficie hay.
4. **Actualizá Chrome y reiniciá el navegador**, en todos los equipos. Si además usás Edge, Brave u Opera, cada uno va por su propio canal, como está detallado en [la nota del sábado](/noticia/chrome-cve-2026-85046-exploit-activo-reiniciar).
5. **Mirá los registros de autenticación de esos aparatos** de las últimas semanas. En una elusión por camino alternativo no hay intentos fallidos de login: hay accesos que simplemente no tienen un inicio de sesión detrás. Esa ausencia es la señal.

Si el esquema completo de acceso remoto está en revisión, la alternativa de fondo sigue siendo [mover el acceso a un modelo sin servicios pre-autenticación publicados](/guia/acceso-remoto-seguro-sin-vpn).

## Preguntas frecuentes sobre las fallas de Citrix, Cisco y Fortinet

### ¿Citrix y Cisco fueron atacados por el mismo grupo?
Nada en el catálogo ni en los avisos lo indica, y no lo afirmamos. Lo comprobable es que las dos fallas comparten clase y entraron el mismo día. La explicación más simple es que quien investiga esta categoría de producto busca justamente este tipo de error.

### Fortinet dice 8,1 y NVD dice 9,8. ¿Cuál uso?
Las dos son CVSS 3.1 sobre la misma falla, calculadas por evaluadores distintos. Para priorizar internamente conviene usar la más alta y, sobre todo, mirar el plazo del catálogo: son tres días, que es lo que dice cuánta prisa hay de verdad.

### ¿Cómo sé si mi NetScaler está configurado como Gateway?
En la consola, revisando si hay servidores virtuales de tipo AAA o Gateway definidos. Si el equipo publica SSL VPN, proxy ICA, CVPN o proxy RDP para usuarios remotos, la respuesta es sí.

### Estas fallas son de enero y marzo. ¿Por qué recién ahora?
Porque el catálogo registra explotación observada, no publicación. Que una falla sea vieja no la vuelve inofensiva: la vuelve más probable de encontrar sin parchear, que es exactamente lo que buscan los barridos automáticos.

### ¿Otro cero-día de Chrome en menos de una semana es normal?
En V8, sí. Es el componente que concentra la mayoría de lo explotado en Chromium, y por eso también el más auditado. La conclusión práctica no cambia: el navegador se repara solo, pero recién cuando lo reiniciás.

---

**Fuentes primarias.** Catálogo KEV de CISA, versión 2026.09.09, del que salen las descripciones, los plazos y el recuento histórico de la clase "camino alternativo" · Registros de NVD para [CVE-2026-19490](https://nvd.nist.gov/vuln/detail/CVE-2026-19490), [CVE-2026-20079](https://nvd.nist.gov/vuln/detail/CVE-2026-20079), [CVE-2025-25249](https://nvd.nist.gov/vuln/detail/CVE-2025-25249) y [CVE-2026-87491](https://nvd.nist.gov/vuln/detail/CVE-2026-87491).

Consultadas el 10 de septiembre de 2026. El catálogo menciona FortiSASE entre los productos afectados y la lista de versiones de NVD no lo incluye; queda como dato a confirmar con el fabricante. Para el vocabulario, [el glosario](/guia/glosario-ciberseguridad-pymes); para el orden general de prioridades, [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).
