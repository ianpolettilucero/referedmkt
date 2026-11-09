---
title: "Hackeo a refrigeradores de supermercados: 291 equipos expuestos en América Latina"
subtitle: Desde octubre, atacantes cambian a distancia la temperatura de cámaras y vitrinas en supermercados europeos con controladores Danfoss, Copeland y Dixell. Estados Unidos, la Unión Europea y Chile emitieron alertas. En la región hay 291 equipos de ese tipo a la vista desde internet, 203 en Brasil.
excerpt: Atacan controladores de refrigeración Danfoss, Copeland y Dixell en Europa. En América Latina hay 291 expuestos a internet. Cómo saber si el tuyo lo está.
type: news
section: mundo
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-11-09
updated: 2026-11-09
products: []
meta_title: "Ataques a refrigeración: 291 equipos en LATAM"
meta_description: "Atacan controladores de refrigeración Danfoss, Copeland y Dixell en supermercados. En América Latina hay 291 expuestos a internet. Cómo saber si el tuyo está."
---

Desde el 11 de octubre, atacantes entran a distancia a los controladores que manejan la refrigeración de supermercados europeos y fuerzan a las cámaras y vitrinas a calentarse. El primer golpe fue en Francia, donde unos **700 supermercados** resultaron afectados, según [SecurityWeek](https://www.securityweek.com/supermarket-refrigeration-attack-widens-to-copeland-dixell-controllers/). La segunda ola, 3 semanas después, el 1 y 2 de noviembre, alcanzó tiendas de otros países europeos: Suecia, según su CERT nacional, y Bélgica, Países Bajos, Irlanda e Italia, según la prensa de cada país. Nadie se atribuyó los ataques.

En 25 días, el problema pasó de una alerta francesa a una de cinco agencias de Estados Unidos. El 5 de noviembre, **CISA, el FBI y los departamentos de Salud, Agricultura y Transporte de Estados Unidos** publicaron una [hoja informativa conjunta](https://www.cisa.gov/resources-tools/resources/us-government-partners-urge-hardening-industrial-refrigeration-and-cold-chain-monitoring-systems). El mismo día, la Agencia Nacional de Ciberseguridad de **Chile** (ANCI) emitió una alerta, y el 6, ENISA y la Comisión Europea pidieron a las organizaciones actuar de inmediato.

Para la región, el dato lo puso la empresa chilena NIVEL4 Labs: en América Latina hay **291 controladores de refrigeración expuestos a internet**, **203 de ellos en Brasil**. No significa que estén comprometidos. Significa que tienen la misma puerta que usaron en Europa: la interfaz de administración a la vista de cualquiera.

## Qué pasó en los supermercados de Francia y del resto de Europa

El [CERT-FR francés](https://cert.cyber.gouv.fr/alerte/CERTFR-2026-ALE-012/) alertó el 12 de octubre que tenía *"connaissance de nombreuses compromissions"* de controladores remotos de refrigeración. Al día siguiente amplió la lista. Los equipos nombrados son cuatro:

| Fabricante | Equipos que nombra el CERT-FR |
|---|---|
| Danfoss | Controladores de las series AK-SM y AK-SC |
| Copeland | Supervisores xweb |
| Dixell, marca de Copeland | Supervisores xweb |

Son los equipos que centralizan el control de las cámaras frigoríficas, las vitrinas, las alarmas y los ciclos de descongelación de un local. Los atacantes cambiaban esa configuración a distancia. La hoja de CISA describe el objetivo: *"force units into continuous heating and defrost cycles, which can cause widespread inventory loss and equipment hazards."* Entre los efectos posibles enumera el riesgo de incendio, la pérdida de mercadería y que el operador deje de ver las temperaturas reales.

En la segunda ola, un vocero de Danfoss dijo a la televisión pública neerlandesa NOS que los sistemas atacados usaban una contraseña estándar y no estaban en una red protegida, según [Security.NL](https://www.security.nl/posting/959992/Koelsystemen+supermarkten+via+standaard+wachtwoord+gehackt). Según la recopilación de NIVEL4 Labs, en los Países Bajos Danfoss contó al menos 17 empresas atacadas, entre ellas carnicerías y almacenes de barrio. Copeland reconoció una vulnerabilidad: *"We are currently working with impacted customers to mitigate the risk."*

```svg
<svg viewBox="0 0 680 296" role="img" aria-label="Cronología de los ataques a sistemas de refrigeración en 2026. 9 de agosto: Claroty publica fallas en el controlador Danfoss AK-SM 800A y cuenta 2.765 equipos expuestos según Censys. 11 de octubre: empiezan los incidentes en la Unión Europea, según ENISA. 12 de octubre: el CERT-FR alerta por controladores Danfoss comprometidos en Francia. 13 de octubre: el CERT-FR suma los supervisores xweb de Copeland y Dixell. 1 y 2 de noviembre: segunda ola en supermercados de otros países europeos. 5 de noviembre: CISA, el FBI y los departamentos de Salud, Agricultura y Transporte de Estados Unidos publican una hoja informativa conjunta, y la Agencia Nacional de Ciberseguridad de Chile emite la alerta AIA26-00270. 6 de noviembre: ENISA y la Comisión Europea publican una declaración conjunta que pide actuar de inmediato.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Ataques a la refrigeración: de Francia al resto de Europa y las alertas</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">2026. El rojo marca la segunda ola, que sacó el problema de Francia.</text>
  <path d="M84 58 V258" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">9 ago.</text>
  <circle cx="84" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Claroty publica fallas en el Danfoss AK-SM 800A: 2.765 expuestos</text>
  <text x="20" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">11 oct.</text>
  <circle cx="84" cy="92" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Empiezan los incidentes en la Unión Europea, según ENISA</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">12 oct.</text>
  <circle cx="84" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Alerta del CERT-FR por controladores Danfoss en Francia</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">13 oct.</text>
  <circle cx="84" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">El CERT-FR suma los supervisores xweb de Copeland y Dixell</text>
  <text x="20" y="174" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">1-2 nov.</text>
  <circle cx="84" cy="170" r="5" class="ch-dot-hi" fill="#e23a3a"/>
  <text x="100" y="174" class="ch-strong" font-size="10.5" fill="currentColor">Segunda ola en supermercados de otros países europeos</text>
  <text x="20" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov.</text>
  <circle cx="84" cy="196" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">CISA, el FBI y otras tres agencias publican una hoja conjunta</text>
  <text x="20" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov.</text>
  <circle cx="84" cy="222" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">La ANCI de Chile emite la alerta AIA26-00270</text>
  <text x="20" y="252" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">6 nov.</text>
  <circle cx="84" cy="248" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="100" y="252" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">ENISA y la Comisión Europea piden actuar de inmediato</text>
  <text x="20" y="286" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: Claroty, CERT-FR, ENISA, CISA y NIVEL4 Labs sobre la ANCI.</text>
</svg>
```

## Cómo entraron los atacantes a los controladores Danfoss, Copeland y Dixell

No está confirmado. El CERT-FR no vincula los ataques con ninguna falla concreta, y la hoja de CISA habla en general de *"authentication bypass"*, inyección de código y configuraciones débiles. Las fallas conocidas existen. En agosto, 2 meses antes de los primeros ataques, [Claroty documentó tres en el Danfoss AK-SM 800A](https://claroty.com/team82/research/freeze-the-controller-defrost-the-food-uncovering-vulnerabilities-in-danfoss-refrigeration-controllers). La más grave, **CVE-2025-41450**, es un mecanismo oculto de *"code-of-the-day"* que permite entrar sin la contraseña del administrador; afecta a las versiones anteriores a la 4.2, tiene 8,2 en la ficha de NVD y su ficha lleva publicada más de 1 año. Pero ni Danfoss ni las autoridades francesas atribuyeron los ataques a esas fallas; algunos medios lo dan por hecho, sin una fuente oficial.

Lo que sí coincide en todas las fuentes es la condición previa: **la interfaz de administración del controlador estaba accesible desde internet**. Claroty contó **2.765** controladores AK-SM 800A expuestos según el buscador Censys. El CERT-FR es tajante con los equipos sin soporte del fabricante: mantenerlos expuestos es dejarlos ante *"une très forte probabilité de compromission"*, y un reseteo de fábrica no alcanza porque el equipo sigue vulnerable.

## Brasil y Chile, los países de la región con más controladores expuestos

```svg
<svg viewBox="0 0 680 322" role="img" aria-label="Controladores de refrigeración expuestos a internet en América Latina, según un análisis de NIVEL4 Labs publicado el 2 de noviembre de 2026. Total: 291. Brasil, 203, el 69,8 por ciento. Chile, 29. Colombia, 18. México, 13. Argentina, 13. Guatemala, 5. Otros seis países suman 10: Costa Rica, Panamá, Honduras y Nicaragua con 2 cada uno, y República Dominicana y Perú con 1 cada uno. Estar expuesto no significa estar comprometido ni ser vulnerable; NIVEL4 Labs no publicó su método.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Controladores de refrigeración expuestos a internet en América Latina</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Total: 291. Análisis de NIVEL4 Labs, 2 de noviembre de 2026. Expuesto no es comprometido.</text>
  <path d="M160.0 58 V274" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="160" y="289" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">0</text>
  <path d="M250.9 58 V274" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="250.9" y="289" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">50</text>
  <path d="M341.8 58 V274" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="341.8" y="289" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">100</text>
  <path d="M432.7 58 V274" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="432.7" y="289" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">150</text>
  <path d="M523.6 58 V274" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="523.6" y="289" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">200</text>
  <text x="150" y="77" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">Brasil</text>
  <path d="M160,64 H525.1 A4,4 0 0 1 529.1,68 V78 A4,4 0 0 1 525.1,82 H160 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>Brasil: 203 controladores expuestos</title></path>
  <text x="535.1" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">203</text>
  <text x="150" y="107" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Chile</text>
  <path d="M160,94 H208.7 A4,4 0 0 1 212.7,98 V108 A4,4 0 0 1 208.7,112 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Chile: 29 controladores expuestos</title></path>
  <text x="218.7" y="107" class="ch-value" font-size="11" font-weight="600" fill="currentColor">29</text>
  <text x="150" y="137" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Colombia</text>
  <path d="M160,124 H188.7 A4,4 0 0 1 192.7,128 V138 A4,4 0 0 1 188.7,142 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Colombia: 18 controladores expuestos</title></path>
  <text x="198.7" y="137" class="ch-value" font-size="11" font-weight="600" fill="currentColor">18</text>
  <text x="150" y="167" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">México</text>
  <path d="M160,154 H179.6 A4,4 0 0 1 183.6,158 V168 A4,4 0 0 1 179.6,172 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>México: 13 controladores expuestos</title></path>
  <text x="189.6" y="167" class="ch-value" font-size="11" font-weight="600" fill="currentColor">13</text>
  <text x="150" y="197" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Argentina</text>
  <path d="M160,184 H179.6 A4,4 0 0 1 183.6,188 V198 A4,4 0 0 1 179.6,202 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Argentina: 13 controladores expuestos</title></path>
  <text x="189.6" y="197" class="ch-value" font-size="11" font-weight="600" fill="currentColor">13</text>
  <text x="150" y="227" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Guatemala</text>
  <path d="M160,214 H165.1 A4,4 0 0 1 169.1,218 V228 A4,4 0 0 1 165.1,232 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Guatemala: 5 controladores expuestos</title></path>
  <text x="175.1" y="227" class="ch-value" font-size="11" font-weight="600" fill="currentColor">5</text>
  <text x="150" y="257" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Otros 6 países</text>
  <path d="M160,244 H174.2 A4,4 0 0 1 178.2,248 V258 A4,4 0 0 1 174.2,262 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Costa Rica, Panamá, Honduras y Nicaragua, 2 cada uno; República Dominicana y Perú, 1 cada uno</title></path>
  <text x="184.2" y="257" class="ch-value" font-size="11" font-weight="600" fill="currentColor">10</text>
  <text x="20" y="312" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: NIVEL4 Labs. El análisis no publica su método ni los modelos contados.</text>
</svg>
```

El análisis de NIVEL4 Labs, publicado el 2 de noviembre en [su blog](https://blog.nivel4.com/ciberataque/ciberataque-refrigeradores-supermercados-europa), cuenta 291 dispositivos de este tipo visibles desde internet en América Latina. Brasil concentra el **69,8%**. Le siguen Chile con 29, Colombia con 18, y México y Argentina con 13 cada uno.

Dos advertencias sobre la cifra. NIVEL4 Labs no publicó su método ni qué modelos contó. Y la propia empresa aclara que la visibilidad *"no significa que sean vulnerables"*, aunque sea la misma puerta de entrada que usaron los atacantes en Europa. No hay reportes públicos de ataques a supermercados de la región.

## ¿Qué dice la alerta de Chile sobre los ataques a la refrigeración?

Según el resumen de NIVEL4 Labs, la alerta **AIA26-00270** del CSIRT Nacional de Chile, del 5 de noviembre, nombra los mismos equipos que el CERT-FR. Recomienda instalar la versión 4.3.1 o posterior en los Danfoss y revisar cambios de temperatura no autorizados. También advierte que *"el cambio de contraseñas no corrige las vulnerabilidades que permiten eludir la autenticación"*. Recuerda además que las entidades alcanzadas por la **Ley 21.663**, la Ley Marco de Ciberseguridad, deben notificar los incidentes. La página de la alerta no se pudo leer directamente desde este entorno, así que estos datos salen del resumen de NIVEL4 Labs.

## ¿Cómo sé si el controlador Danfoss, Copeland o Dixell de mi negocio está expuesto?

Si tenés un supermercado, una carnicería, una farmacia, un restaurante con cámara o un depósito refrigerado, es probable que no sepas qué controlador tiene: lo instaló y lo atiende el técnico de refrigeración. La pregunta es para él.

1. **Preguntale al técnico qué controlador o supervisor usa** y de qué marca: Danfoss AK-SM o AK-SC, Copeland o Dixell xweb, u otro.
2. **Preguntale cómo lo administra a distancia.** Si entra con una dirección IP pública, con un nombre de dominio o con un módem 4G conectado directo al equipo, la interfaz está expuesta. Si entra por una VPN o una red protegida, no.
3. **Probá desde afuera.** Con el teléfono en datos móviles, fuera del wifi del local, intentá abrir la dirección con la que el técnico entra al panel. Si aparece la pantalla de inicio de sesión, cualquiera en internet la ve.
4. **Revisá las temperaturas registradas.** Cambios bruscos de madrugada, ciclos de descongelación que nadie programó o alarmas desactivadas son las señales que describen el CERT-FR y la ANCI.

## ¿Qué hago si mi controlador Danfoss, Copeland o Dixell está en internet?

1. **Sacalo de internet.** Es la primera recomendación de todas las agencias. El acceso remoto del técnico tiene que pasar por una VPN o una red protegida. El CERT-FR acepta, como medida de urgencia y no permanente, filtrar en el firewall para que solo entren las direcciones IP del técnico. Las opciones para dar acceso remoto sin exponer el equipo están en [la guía de acceso remoto seguro](/guia/acceso-remoto-seguro-sin-vpn) y en [la comparativa de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).
2. **Antes de cortar el envío de datos al técnico, revisá el contrato del seguro.** Lo recomienda el CERT-FR en su alerta. Qué piden las aseguradoras en general está en [la guía sobre ciberseguros para PyMEs](/guia/ciberseguro-pyme-que-exigen-las-aseguradoras).
3. **Cambiá la contraseña de fábrica y actualizá el firmware.** Para Danfoss, el CERT-FR pide además el nivel de seguridad en modo *Strict*, y la ANCI la versión 4.3.1 o posterior. Pero cambiar la contraseña no cierra una falla que se saltea la autenticación.
4. **Separá el controlador del resto de la red.** CISA pide una red virtual propia para los equipos de operación, separada de la caja y las computadoras. Cómo armar esa separación está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).
5. **Poné límites y alarmas a las temperaturas.** CISA recomienda fijar rangos seguros en los equipos y alertas ante desviaciones, y la ANCI asegurar que los equipos se puedan operar a mano.
6. **Si el equipo ya no recibe actualizaciones del fabricante, planificá el reemplazo.** El CERT-FR advierte que un equipo sin soporte y expuesto va a volver a ser comprometido aunque se lo resetee.

El patrón es el de cualquier aparato conectado que nadie mira: lo instala un proveedor, queda accesible desde afuera y la contraseña de fábrica sigue ahí. Pasó con [los TV boxes Android de la botnet XCPE](/noticia/botnet-xcpe-tv-box-android-steren-aquario) y con [los routers baratos sin parche](/noticia/linksys-edimax-wavlink-17-criticas-routers-sin-parche).

## ¿Quién puede ignorar las alertas de CISA y la ANCI sobre refrigeración?

Quien no tenga equipos de refrigeración comercial con controlador conectado a internet. Una heladera o un freezer sin conexión a la red no están en juego. Y quien sí tenga uno, pero administrado solo por VPN o red protegida y con firmware al día, tiene cubierto lo principal que piden todas las alertas.

**Fuentes leídas:** la alerta CERTFR-2026-ALE-012 del CERT-FR, con sus tres versiones; la hoja informativa conjunta de CISA, el FBI, HHS, USDA y el Departamento de Transporte del 5 de noviembre; la declaración conjunta de ENISA y la Comisión Europea del 6 de noviembre y el aviso del CERT-SE sueco del 2 de noviembre; el análisis de Claroty sobre el Danfoss AK-SM 800A; la ficha de NVD de CVE-2025-41450; las notas de SecurityWeek y Security.NL; y los artículos de NIVEL4 Labs sobre la segunda ola, la exposición en la región y la alerta de la ANCI. **Sin confirmar:** cómo entraron los atacantes, porque ninguna autoridad vinculó los ataques con una falla concreta; quién está detrás; el método del conteo de NIVEL4 Labs; y el texto completo de la alerta de la ANCI, que no se pudo leer directamente.

## Preguntas frecuentes sobre el hackeo a refrigeradores Danfoss, Copeland y Dixell

### ¿Qué equipos de refrigeración fueron atacados?

Según el CERT-FR francés, controladores Danfoss de las series AK-SM y AK-SC y supervisores xweb de Copeland y de Dixell. Son los equipos que centralizan el control de las cámaras, las vitrinas y los ciclos de descongelación de un local.

### ¿Hubo ataques a supermercados en América Latina?

No hay reportes públicos. Lo que hay es exposición: NIVEL4 Labs contó 291 controladores de refrigeración visibles desde internet en la región, 203 en Brasil y 29 en Chile. Estar expuesto no significa estar comprometido.

### ¿Cómo entraron los atacantes a los refrigeradores?

No está confirmado. Todas las fuentes coinciden en que los equipos tenían la interfaz de administración accesible desde internet, y Danfoss dijo que en la segunda ola usaban una contraseña estándar. Hay fallas conocidas, como CVE-2025-41450 en el Danfoss AK-SM 800A, pero nadie las vinculó oficialmente con los ataques.

### ¿Alcanza con cambiar la contraseña del controlador?

No. Ayuda contra el acceso con la clave de fábrica, pero la ANCI advierte que no corrige las fallas que se saltean la autenticación. Lo principal es sacar el equipo de internet y actualizar el firmware.

### ¿Qué le pregunto al técnico de refrigeración?

Qué controlador tiene el local, cómo entra a distancia y si la interfaz se ve desde internet. Si entra por una IP pública o un módem 4G conectado directo al equipo, pedile que pase el acceso a una VPN y cambie la contraseña de fábrica.
