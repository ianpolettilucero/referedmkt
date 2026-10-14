---
title: "Ivanti Connect Secure y Windows Shell: dos explotadas con plazo al 16"
subtitle: Microsoft publicó 1413 fallas este mes y marcó exactamente una como explotada. La otra está en el equipo que da acceso remoto, y su línea de producto ya tiene cinco antecedentes que terminaron en ransomware.
excerpt: CISA sumó dos vulnerabilidades explotadas el 13 de octubre con plazo de tres días. Qué son, qué versión mirar y por qué el número que importa no es 1413 sino 1.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-10-14
updated: 2026-10-14
products: []
meta_title: "Ivanti Connect Secure y Windows, explotadas"
meta_description: "Dos fallas explotadas entraron al catálogo de CISA con plazo de 3 días: Ivanti Connect Secure y Windows Shell. Qué versión mirar y qué hacer."
---

El 13 de octubre el catálogo de vulnerabilidades explotadas de CISA pasó a la versión **2026.10.13** y sumó **dos entradas**, las dos con plazo al **16 de octubre**: **3 días**. Para comparar, la mediana del plazo de las **305 entradas** de los últimos **12 meses** es de **14 días**, y el plazo más corto que usó el catálogo en ese período fue de **2 días**. Cuando el catálogo da tres, no está midiendo gravedad: está diciendo que lo están usando ahora, como ya pasó con [las siete altas de septiembre](/noticia/kev-2-septiembre-sonicwall-switchvox-plazo-tres-dias).

Las dos:

- **CVE-2026-104935**, en **Ivanti Connect Secure**: bypass de autenticación que permite ejecutar comandos sin credenciales. **8,6**, puntaje asignado por Ivanti como autoridad de numeración.
- **CVE-2026-96951**, en **Windows Shell**: **8,8** según Microsoft, que la marca como explotada el mismo día que publicó su actualización mensual.

## CVE-2026-104935 y CVE-2026-96951: qué hace cada una

```svg
<svg viewBox="0 0 680 246" role="img" aria-label="Las dos vulnerabilidades que CISA agregó a su catálogo de explotadas el 13 de octubre de 2026, las dos con plazo al 16 de octubre, es decir tres días. La primera es CVE-2026-104935 en Ivanti Connect Secure, con puntaje 8,6 alto asignado por Ivanti actuando como autoridad de numeración de CVE, clasificada como CWE-290 bypass de autenticación por suplantación, con vector de red, sin credenciales y sin interacción del usuario, y permite a un atacante remoto no autenticado ejecutar comandos arbitrarios. Ivanti confirma explotación en sus propias palabras: dice estar al tanto de explotación limitada en la práctica. La segunda es CVE-2026-96951 en Windows Shell, con puntaje 8,8 base y 8,2 temporal asignado por Microsoft, clasificada como CWE-77 inyección de comandos, cuyo título oficial de Microsoft es vulnerabilidad de evasión de característica de seguridad, con severidad Important y no Critical, y cuyo vector requiere interacción del usuario. Microsoft declara en su API los valores explotada sí y divulgada públicamente no. Un dato importante: NVD todavía no terminó su análisis de la falla de Windows, así que el 8,8 que aparece en NVD es el de Microsoft, no un puntaje independiente del NIST">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Dos altas el 13 de octubre, las dos con 3 días de plazo</text>

  <rect x="20" y="36" width="620" height="92" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="36" width="620" height="92" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="55" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-104935 — Ivanti Connect Secure — 8,6 (puntaje de Ivanti)</text>
  <text x="36" y="74" font-size="9.5" fill="currentColor" opacity="0.9">Bypass de autenticación por suplantación (CWE-290). Por red, sin credenciales y</text>
  <text x="36" y="90" font-size="9.5" fill="currentColor" opacity="0.9">sin que la víctima haga nada: ejecuta comandos en el equipo.</text>
  <text x="36" y="110" font-size="9.5" font-weight="700" fill="#e23a3a">Ivanti lo confirma: "aware of limited exploitation in the wild".</text>

  <rect x="20" y="142" width="620" height="76" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="142" width="620" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="161" font-size="10.5" font-weight="700" fill="currentColor">CVE-2026-96951 — Windows Shell — 8,8 base y 8,2 temporal (de Microsoft)</text>
  <text x="36" y="180" font-size="9.5" fill="currentColor" opacity="0.9">Inyección de comandos (CWE-77). Microsoft la titula "evasión de característica</text>
  <text x="36" y="196" font-size="9.5" fill="currentColor" opacity="0.9">de seguridad", severidad Important, y su vector requiere que el usuario haga algo.</text>
  <text x="36" y="213" font-size="9.5" font-weight="700" fill="currentColor">En su API: "exploited":"Yes" y "publiclyDisclosed":"No".</text>

  <text x="20" y="240" font-size="9" fill="currentColor" opacity="0.65">NVD todavía no terminó su análisis de la de Windows: el 8,8 que figura ahí es el de Microsoft, no del NIST.</text>
</svg>
```

Vale detenerse en ese último renglón, porque es el tipo de dato que se lee mal. En la falla de Windows, **NVD figura con estado "Undergoing Analysis"**: el 8,8 que aparece en su ficha tiene como fuente a Microsoft. No hay todavía una segunda opinión del NIST, ni rangos de versiones validados por NVD. En la de Ivanti pasa algo parecido: el 8,6 lo puso **Ivanti actuando como autoridad de numeración**, y lo verifiqué contra el registro oficial del programa CVE, donde el campo que identifica a quien lo firma dice `ivanti`.

Y un detalle del propio catálogo que conviene saber antes de hacer clic: **la URL del aviso de Ivanti que publica el KEV está mal escrita**, con una sola barra en `https:/forums.ivanti.com/...`. Además `forums.ivanti.com` ahora redirige a `hub.ivanti.com`. Si copiás el enlace tal como viene, no llegás.

## Microsoft publicó 1413 fallas en octubre y una se está usando

```svg
<svg viewBox="0 0 680 248" role="img" aria-label="Composición de la actualización mensual de Microsoft de octubre de 2026, medida sobre la API de MSRC. El documento se llama October 2026 Security Updates, con fecha inicial 13 de octubre de 2026, y su encabezado dice textualmente que la entrega consiste en 1413 CVE de Microsoft, aunque la fila Total de su propia tabla dice 1414: el fabricante no coincide consigo mismo. De todas esas fallas, exactamente una está marcada como explotada, y es CVE-2026-96951, con la cadena literal publicly disclosed no, exploited sí, latest software release exploitation detected. Exactamente dos están marcadas como divulgadas públicamente, una de ellas CVE-2026-108398 del .NET Framework. El reparto por tipo de impacto sobre las fallas de productos Microsoft es: 677 de elevación de privilegios, 317 de ejecución remota de código, 254 de divulgación de información, 73 de denegación de servicio, 40 de evasión de característica de seguridad, 29 de manipulación y 28 de suplantación. Una advertencia de método: el documento incluye además 341 CVE de la familia Mariner o Azure Linux, que son paquetes de terceros reempaquetados por Microsoft, y esas 341 no traen campo de estado de explotación, así que el dato de exactamente una explotada vale sobre las entradas que sí lo traen">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Octubre de Microsoft: el número que importa no es 1413</text>

  <text x="20" y="46" font-size="10" fill="currentColor" opacity="0.9">Elevación de privilegios</text>
  <rect x="210" y="37" width="300" height="11" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="518" y="46" font-size="10" font-weight="700" fill="currentColor">677</text>

  <text x="20" y="66" font-size="10" fill="currentColor" opacity="0.9">Ejecución remota de código</text>
  <rect x="210" y="57" width="141" height="11" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="359" y="66" font-size="10" font-weight="700" fill="currentColor">317</text>

  <text x="20" y="86" font-size="10" fill="currentColor" opacity="0.9">Divulgación de información</text>
  <rect x="210" y="77" width="113" height="11" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="331" y="86" font-size="10" font-weight="700" fill="currentColor">254</text>

  <text x="20" y="106" font-size="10" fill="currentColor" opacity="0.9">Denegación de servicio</text>
  <rect x="210" y="97" width="32" height="11" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="250" y="106" font-size="10" font-weight="700" fill="currentColor">73</text>

  <text x="20" y="126" font-size="10" fill="currentColor" opacity="0.9">Evasión de seguridad</text>
  <rect x="210" y="117" width="18" height="11" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="236" y="126" font-size="10" font-weight="700" fill="currentColor">40</text>

  <line x1="20" y1="142" x2="640" y2="142" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <rect x="20" y="152" width="620" height="38" rx="5" fill="#e23a3a" opacity="0.15"/>
  <rect x="20" y="152" width="620" height="38" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="168" font-size="11" font-weight="700" fill="#e23a3a">Marcadas como explotadas: 1 de todas. Es CVE-2026-96951.</text>
  <text x="36" y="184" font-size="9.5" fill="currentColor" opacity="0.9">Divulgadas públicamente antes del parche: 2. Todo lo demás, ninguna de las dos cosas.</text>

  <text x="20" y="210" font-size="9.5" fill="currentColor" opacity="0.85">Microsoft no coincide consigo mismo: su encabezado dice 1413 CVE y la fila Total de su tabla dice 1414.</text>
  <text x="20" y="226" font-size="9.5" fill="currentColor" opacity="0.85">Las 341 fallas de Azure Linux del mismo documento no traen campo de explotación: el "1" vale sobre las que sí.</text>
  <text x="20" y="243" font-size="9" fill="currentColor" opacity="0.65">Medido sobre la API de MSRC, documento 2026-Oct, consultado el 14 de octubre de 2026.</text>
</svg>
```

Ese es el dato con el que conviene quedarse si administrás equipos y no tenés un área de IT: **1413 fallas publicadas, 1 que alguien está usando**. El resto se parchea con el ciclo normal. La que corre es la de Windows Shell, y es la que entró al catálogo de CISA el mismo día.

Sobre el mecanismo hay que ser preciso con lo que el fabricante dice y lo que no. Microsoft la titula **"Windows Shell Security Feature Bypass Vulnerability"** y define el impacto como evasión de una característica de seguridad, pero **no dice cuál característica**: el campo que listaría los binarios corregidos viene vacío y no hay guía de detección, ni artefactos de registro, ni indicadores. El vector sí aclara algo importante: **requiere interacción del usuario**, así que no es un gusano que entre solo. Lo que no se puede afirmar es qué tiene que hacer exactamente la víctima, porque Microsoft no lo publica.

Tampoco hay **workaround**: verifiqué que no publica ninguna mitigación provisoria. O se aplica el parche, o se queda expuesto.

## Ivanti dice que la 22.7R2.14 y las anteriores no están afectadas

Acá está la parte contraintuitiva, y es fácil publicarla al revés. De hecho yo la tenía mal redactada antes de verificarla.

```svg
<svg viewBox="0 0 680 254" role="img" aria-label="Mapa de versiones de Ivanti Connect Secure frente a CVE-2026-104935, según el registro oficial del programa CVE firmado por Ivanti como autoridad de numeración. El registro pone estado por omisión afectado y lista exactamente cuatro versiones como no afectadas: la 25.1.3.0, la 25.1.3.1, la 22.8R2.5 y la 22.7R2.14. La descripción redactada por Ivanti dice textualmente que la falla está en versiones anteriores a la 25.1.3.0 y que no impacta a la versión 22.7R2.14 o anteriores, es decir que la 22.7R2.14 misma queda incluida entre las no afectadas. El análisis de versiones de NVD, que es trabajo de analista del NIST y no texto de Ivanti, marca como afectados dos tramos de la línea 25.1, desde la 25.1.0.0 hasta antes de la 25.1.1.2 y desde la 25.1.2.0 hasta antes de la 25.1.3.0, más las versiones puntuales 22.8R2 a 22.8R2.4. Es decir que el rango afectado no es una banda continua. Hay además una inconsistencia entre dos fuentes del propio Ivanti: el blog de la empresa lista la versión 25.1.1.2 entre los arreglos, pero esa versión no figura en la lista de no afectadas del registro oficial, y no se pudo resolver cuál manda">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué versión queda fuera de alcance, según Ivanti</text>

  <rect x="20" y="36" width="620" height="60" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="36" width="620" height="60" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="55" font-size="10.5" font-weight="700" fill="currentColor">Las 4 que el registro de Ivanti marca "no afectada"</text>
  <text x="36" y="74" font-size="10" fill="currentColor" opacity="0.9">25.1.3.0 · 25.1.3.1 · 22.8R2.5 · 22.7R2.14</text>
  <text x="36" y="90" font-size="9.5" fill="currentColor" opacity="0.8">Todo lo demás queda afectado por omisión, según el propio registro.</text>

  <rect x="20" y="110" width="620" height="58" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="110" width="620" height="58" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="129" font-size="10.5" font-weight="700" fill="#e23a3a">Lo que dice textual, y que se lee mal con facilidad</text>
  <text x="36" y="147" font-size="9.5" fill="currentColor" opacity="0.9">"This does not impact ICS version 22.7R2.14 or prior": la 22.7R2.14 está</text>
  <text x="36" y="163" font-size="9.5" font-weight="700" fill="#e23a3a">incluida entre las que NO están afectadas, no excluida.</text>

  <text x="20" y="190" font-size="10" font-weight="700" fill="currentColor">El rango afectado no es una banda continua (análisis de NVD):</text>
  <text x="38" y="208" font-size="9.5" fill="currentColor" opacity="0.9">25.1.0.0 hasta antes de 25.1.1.2 · 25.1.2.0 hasta antes de 25.1.3.0</text>
  <text x="38" y="224" font-size="9.5" fill="currentColor" opacity="0.9">más las puntuales 22.8R2, 22.8R2.1, 22.8R2.2, 22.8R2.3 y 22.8R2.4</text>

  <text x="20" y="248" font-size="9" fill="currentColor" opacity="0.65">Registro CNA del programa CVE firmado por Ivanti, y análisis de versiones de NVD, al 14 de octubre de 2026.</text>
</svg>
```

Lo que dice el registro oficial, que **Ivanti redacta porque actúa como autoridad de numeración**, es textual: *"Authentication bypass by spoofing in ICS before version 25.1.3.0 allows a remote unauthenticated attacker to execute arbitrary commands. This does not impact ICS version 22.7R2.14 or prior."*

El "**or prior**" es inclusivo: **la 22.7R2.14 está entre las no afectadas**, no afuera. Y el registro la lista explícitamente junto a los parches, lo que dice algo sobre qué es: no es una versión atrasada, es el nivel de parche vigente de su línea de código. Por eso **no corresponde la conclusión fácil de que "estar atrasado te salva"**. El rango afectado tampoco es una banda continua, como muestra el diagrama.

Hay una inconsistencia que conviene conocer antes de planificar la actualización: **el blog de Ivanti lista la 25.1.1.2 entre los arreglos**, pero esa versión **no figura** en la lista de no afectadas del registro oficial. Son dos fuentes del mismo fabricante que no coinciden, y no pude resolver cuál manda. Lo prudente es apuntar a **25.1.3.0 o posterior**, que es la única rama que las dos fuentes tratan como corregida.

Dos cosas más que Ivanti dice y que cambian la planificación. La primera: **hay un segundo bug encadenado todavía sin parche**. En sus palabras, el arreglo de "una falla de escalada de privilegios relacionada, usada en la misma cadena", llega "en las próximas semanas". La segunda: **las líneas 22.x dejan de tener soporte el 31 de enero de 2027**, y el fabricante recomienda pasar a 25.x por eso.

## Connect Secure: cinco de cinco antecedentes terminaron en ransomware

Esto lo calculé sobre el feed del catálogo, y hay una trampa de método que conviene declarar antes del número.

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Historial de Ivanti Connect Secure en el catálogo de vulnerabilidades explotadas de CISA, calculado sobre el feed versión 2026.10.13 que tiene 1741 entradas. Ivanti tiene 36 entradas en el catálogo y queda octavo entre 288 fabricantes, por delante de Fortinet con 31, Linux con 31 y Citrix con 27. De esas 36 entradas de Ivanti, 19 son de la línea Endpoint Manager, MobileIron y Cloud Services Appliance, 14 son de Connect Secure, 2 de Sentry y 1 de Virtual Traffic Manager. Advertencia de método importante: 8 de las 14 entradas de Connect Secure se cargaron todas el mismo día, el 3 de noviembre de 2021, que es el día de la carga inicial del catálogo con 287 entradas, así que no son hechos secuenciales. Descontando esa carga masiva quedan 6 altas secuenciales: dos el 10 de enero de 2024, una el 31 de enero de 2024, una el 8 de enero de 2025, una el 4 de abril de 2025 y la nueva del 13 de octubre de 2026. De las cinco anteriores a la nueva, las cinco figuran con uso conocido en campañas de ransomware. La nueva figura como desconocido, que no es lo mismo que no. Además hay una entrada más de la familia bajo el fabricante antiguo Pulse Secure, así que la familia completa suma 15">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Altas secuenciales de Connect Secure, sin la carga inicial</text>

  <text x="38" y="48" font-size="10" font-weight="700" fill="currentColor">10 ene 2024</text>
  <text x="150" y="48" font-size="10" fill="currentColor" opacity="0.9">CVE-2023-46805 y CVE-2024-21887</text>
  <text x="430" y="48" font-size="10" font-weight="700" fill="#e23a3a">ransomware: sí</text>

  <text x="38" y="70" font-size="10" font-weight="700" fill="currentColor">31 ene 2024</text>
  <text x="150" y="70" font-size="10" fill="currentColor" opacity="0.9">CVE-2024-21893</text>
  <text x="430" y="70" font-size="10" font-weight="700" fill="#e23a3a">ransomware: sí</text>

  <text x="38" y="92" font-size="10" font-weight="700" fill="currentColor">8 ene 2025</text>
  <text x="150" y="92" font-size="10" fill="currentColor" opacity="0.9">CVE-2025-0282</text>
  <text x="430" y="92" font-size="10" font-weight="700" fill="#e23a3a">ransomware: sí</text>

  <text x="38" y="114" font-size="10" font-weight="700" fill="currentColor">4 abr 2025</text>
  <text x="150" y="114" font-size="10" fill="currentColor" opacity="0.9">CVE-2025-22457</text>
  <text x="430" y="114" font-size="10" font-weight="700" fill="#e23a3a">ransomware: sí</text>

  <text x="38" y="136" font-size="10" font-weight="700" fill="#e23a3a">13 oct 2026</text>
  <text x="150" y="136" font-size="10" font-weight="700" fill="#e23a3a">CVE-2026-104935</text>
  <text x="430" y="136" font-size="10" fill="currentColor" opacity="0.9">ransomware: desconocido</text>

  <rect x="20" y="152" width="620" height="38" rx="5" fill="#e23a3a" opacity="0.15"/>
  <rect x="20" y="152" width="620" height="38" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="168" font-size="11" font-weight="700" fill="#e23a3a">Las 5 anteriores terminaron con uso confirmado en ransomware. Las 5 de 5.</text>
  <text x="36" y="184" font-size="9.5" fill="currentColor" opacity="0.9">La nueva dice "desconocido", que en este historial se lee como "todavía no".</text>

  <text x="20" y="212" font-size="9.5" fill="currentColor" opacity="0.85">Método: otras 8 entradas de Connect Secure son del 3 nov 2021, el día de la carga inicial (287 de una vez).</text>
  <text x="20" y="228" font-size="9.5" fill="currentColor" opacity="0.85">No se cuentan como hechos secuenciales. Ivanti es 8º de 288 fabricantes del catálogo, con 36 entradas.</text>
  <text x="20" y="245" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre el feed JSON del KEV de CISA, versión 2026.10.13, 1741 entradas.</text>
</svg>
```

La trampa es la **carga inicial del catálogo**: el 3 de noviembre de 2021 entraron **287 entradas de una sola vez**, y 8 de las 14 de Connect Secure son de ese día. Contarlas como si fueran incidentes separados a lo largo del tiempo infla el historial. Descontándolas quedan **6 altas secuenciales**, y ahí el patrón es nítido: **las 5 anteriores a la de ahora figuran con uso confirmado en campañas de ransomware**. La nueva dice "desconocido", que no es lo mismo que "no".

Un detalle de conteo, por si alguien reproduce el cálculo: hay **una entrada más de la familia bajo el fabricante antiguo "Pulse Secure"**, separado de "Ivanti" en el catálogo, así que la familia completa suma **15** y no 14.

Y el contexto más amplio: los equipos de acceso remoto y VPN de 6 fabricantes suman **90 de las 1741 entradas, el 5,2%** —Citrix 21, Cisco 16, Fortinet 15, Ivanti 14, Palo Alto 12 y SonicWall 12—. El aparato que existe para dejar entrar gente de afuera es el que acumula historial, y es el mismo razonamiento de [la falla de NetScaler donde parchear no alcanzaba](/noticia/citrix-netscaler-cve-2026-88771-reconstruir-no-parchear) y de [las diez fallas del componente de VPN de WatchGuard](/noticia/watchguard-firebox-diez-fallas-iked-vpn).

## ¿A quién afectan CVE-2026-104935 y CVE-2026-96951?

**La de Ivanti** toca a quien tenga un **Ivanti Connect Secure** publicado para dar acceso remoto, en las líneas 25.1 o 22.8R2. No es el equipo de la PyME de cinco personas: es el de la empresa con sucursales, con personal que entra desde afuera, o la que lo heredó de un proveedor de IT. Si no sabés si tenés uno, la pregunta concreta a quien administra la red es si el acceso remoto pasa por un appliance de Ivanti, Pulse o alguna de sus marcas anteriores.

**La de Windows Shell** toca a mucho más gente, porque son 31 productos y versiones de Windows. Pero necesita que el usuario haga algo, así que su camino de entrada es el de siempre: un archivo o un adjunto que alguien abre. Es el terreno de [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa), donde el eslabón es la persona y no el servidor.

## ¿Quién puede ignorar el aviso de Ivanti Connect Secure?

Quien no tenga ningún appliance de acceso remoto de Ivanti, que es la mayoría de las PyMEs chicas.

Quien tenga Connect Secure en **25.1.3.0, 25.1.3.1 o 22.8R2.5**, que son las versiones que el registro oficial de Ivanti marca como no afectadas.

Quien tenga Connect Secure en **22.7R2.14**, porque el fabricante la lista explícitamente como no afectada. Con una advertencia que no es menor: esa línea **pierde soporte el 31 de enero de 2027**, así que no estar afectado hoy no significa poder quedarse ahí.

Para la de Windows, en cambio, prácticamente nadie puede ignorarla: alcanza con haber aplicado la actualización de octubre. Lo que **no** alcanza es tener antivirus o EDR, porque Microsoft no publica indicadores ni guía de detección para esta falla, así que no hay nada específico que un producto pueda estar buscando.

## ¿Cómo sé qué versión de Ivanti Connect Secure tengo?

1. **Entrá a la consola de administración del appliance.** La versión figura en la pantalla de estado del sistema. Compará contra la lista de cuatro versiones no afectadas: 25.1.3.0, 25.1.3.1, 22.8R2.5 y 22.7R2.14.
2. **Para Windows, mirá el número de compilación**, que es más confiable que "¿se actualizó?". Los parches de esta falla son **KB5127648** para las versiones 24H2, 25H2 y 26H2, **KB5127646** para 26H1 y **KB5126653** para 23H2. Las compilaciones corregidas terminan en **.9711** para 24H2, 25H2 y 26H2, y en **10.0.22631.7652** para 23H2.
3. **Si el appliance estuvo expuesto y no sabés desde cuándo**, el orden importa: Ivanti recomienda correr su **Integrity Checker Tool** de forma regular. No pude confirmar si conviene correrlo antes o después de actualizar, ni si la actualización borra evidencia, y es un detalle que para una PyME cambia el procedimiento. Si hay sospecha de compromiso, esa pregunta va al fabricante o a un tercero antes de tocar nada.

Sobre qué buscar como señal de uso, hay que ser honesto: para las dos fallas **no hay indicadores publicados**. Ni Microsoft ni Ivanti publican hashes, dominios ni artefactos de registro, y el catálogo de CISA marca las dos con uso en ransomware "desconocido". Lo que sí corresponde es lo general en un appliance de borde: cuentas de administración que nadie creó, reglas de acceso cambiadas, sesiones desde países donde no tenés gente, y validación de integridad del equipo. El encuadre largo de qué dejar expuesto está en [la guía de acceso remoto](/guia/acceso-remoto-seguro-sin-vpn).

## ¿Qué hago si tengo un Ivanti Connect Secure publicado?

En este orden, teniendo en cuenta que el plazo del catálogo vence el 16 de octubre, es decir en 2 días:

1. **Mirá la versión hoy.** Es una pantalla y define todo lo demás.
2. **Si está en una versión afectada, actualizá a 25.1.3.0 o posterior.** Es la rama que las dos fuentes de Ivanti tratan como corregida. **No hay workaround**: ninguna fuente legible del fabricante ofrece una mitigación para quien no puede actualizar, así que no existe la opción de esperar con una regla puesta.
3. **Tratá el appliance como potencialmente comprometido si estuvo expuesto en una versión afectada.** Con cinco de cinco antecedentes de esta línea terminando en ransomware, y con el fabricante pidiendo "validación continua de la integridad del equipo", la hipótesis prudente no es "lo parcheé, listo". Es la misma lógica de reconstruir en vez de parchear que ya aplicó a NetScaler.
4. **Aplicá la actualización de octubre de Windows** en las máquinas, por la otra falla. Es la única de 1413 que alguien está usando.
5. **Anotá la fecha de fin de soporte de la línea 22.x: 31 de enero de 2027.** Si vas a quedarte en 22.7R2.14 porque no está afectada, al menos que la salida esté planificada y no sea una sorpresa en enero.

Los términos están en [el glosario](/guia/glosario-ciberseguridad-pymes), y las alternativas de acceso remoto, en [la sección de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

Una aclaración sobre lo que no se pudo leer, porque cambia el peso de lo que afirmo. **El aviso de seguridad de Ivanti para este CVE no se pudo leer**: `hub.ivanti.com` sirve una página de comunidad que solo devuelve un cascarón de JavaScript, y la cadena "CVE-2026-104935" no aparece ni una vez en el HTML servido. Todo lo que digo de Ivanti sale de tres fuentes que sí se pudieron leer: **el registro oficial del programa CVE firmado por Ivanti**, **dos entradas del blog propio del fabricante** fechadas el 13 de octubre, y **el feed JSON del catálogo de CISA**. Por eso no publico la tabla oficial de versiones afectadas: no la pude ver. Y una precisión para evitar una confusión fácil: el blog de Ivanti menciona al grupo **Flax Typhoon**, pero **por las otras cinco fallas que entraron al catálogo el 8 de octubre**, no por esta. Para esta no hay atribución publicada; conté las de esa tanda en [la nota del 12 de octubre](/noticia/proftpd-bind-struts-cinco-fallas-viejas-kev).

---

## Preguntas frecuentes sobre CVE-2026-104935 y CVE-2026-96951

### ¿Por qué Microsoft publicó 1413 fallas y solo una importa hoy?

Porque el volumen y la urgencia son cosas distintas. De todo el paquete de octubre, exactamente una está marcada con "exploited":"Yes" en la API de Microsoft, y es CVE-2026-96951. Dos más figuran como divulgadas públicamente antes del parche. El resto se aplica con el ciclo normal de actualizaciones. Una advertencia de método: el documento incluye además 341 fallas de Azure Linux que son paquetes de terceros reempaquetados y no traen campo de estado de explotación, así que ese "una" vale sobre las entradas que sí lo traen. Y el propio Microsoft no coincide consigo mismo en el total: su encabezado dice 1413 y la fila Total de su tabla dice 1414.

### ¿Es verdad que una versión más vieja de Ivanti Connect Secure está más segura?

Es más sutil que eso, y conviene no sacar la conclusión fácil. Ivanti dice textual que la falla "no impacta la versión 22.7R2.14 o anteriores", y el "o anteriores" incluye a la 22.7R2.14 misma. Pero esa versión no es una versión atrasada: el registro oficial la lista como no afectada junto a los parches, o sea que es el nivel de parche vigente de su línea de código. Y el rango afectado no es una banda continua, sino dos tramos de la línea 25.1 más las versiones 22.8R2 a 22.8R2.4. Así que no se sostiene que "estar atrasado protege": lo que hay es un alcance acotado a ciertas líneas.

### ¿Hay alguna mitigación si no puedo actualizar el appliance ya mismo?

No pude encontrar ninguna, y es importante decirlo con esas palabras. Ninguna fuente legible de Ivanti ofrece un workaround: el blog solo dice que hay que pasar a la versión corregida lo antes posible y recomienda un enfoque por capas, con controles de acceso, monitoreo de red y revisión de registros. Eso es buena práctica general, no una mitigación para esta falla. Microsoft tampoco publica mitigación provisoria para la de Windows Shell. En los dos casos, la respuesta del fabricante es aplicar el parche.

### Si actualizo el Ivanti, ¿alcanza con eso?

El fabricante reconoce que hay un segundo problema todavía sin arreglo: dice que el parche de "una falla de escalada de privilegios relacionada, usada en la misma cadena" llega "en las próximas semanas". Así que al 14 de octubre la cadena completa no está cerrada. Sumado a que las cinco altas anteriores de esta línea de producto terminaron con uso confirmado en ransomware, lo prudente si el equipo estuvo expuesto en una versión afectada es tratarlo como potencialmente comprometido y validar su integridad, no solo actualizarlo.

### ¿Qué característica de seguridad evade la falla de Windows Shell?

Microsoft no lo dice, y preferimos dejarlo así antes que suponerlo. El título oficial es "Windows Shell Security Feature Bypass Vulnerability" y el impacto declarado es evasión de una característica de seguridad, pero el campo que listaría los binarios corregidos viene vacío y no hay guía de detección ni descripción del componente. Lo que sí se sabe por el vector es que requiere interacción del usuario, es decir que alguien tiene que abrir o ejecutar algo. Qué exactamente, no está publicado.
