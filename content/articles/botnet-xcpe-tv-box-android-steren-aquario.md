---
title: "Botnet XCPE: TV boxes Android infectados de fábrica, con modelos Steren y Aquario"
subtitle: El fabricante Videostrong metió la puerta trasera en el firmware de más de 1,3 millones de aparatos y vendió el acceso a redes de proxies y de fraude publicitario. Google cortó los servidores, pero el implante sigue en cada equipo. Brasil y México están entre los cinco países con más aparatos.
excerpt: Google desarmó XCPE, 1,3 millones de TV boxes Android con puerta trasera de fábrica. Brasil y México, en el top 5. Cómo revisar los de tu oficina.
type: news
section: mundo
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-11-03
updated: 2026-11-03
products: []
meta_title: "Botnet XCPE: TV boxes Android infectados"
meta_description: "Google desarmó XCPE: 1,3 millones de TV boxes Android con puerta trasera de fábrica, con Brasil y México en el top 5. Cómo revisar los de tu oficina."
---

**Google** desarmó junto con otras empresas y la Policía Nacional de Japón la botnet **XCPE**: más de **1,3 millones** de aparatos Android, casi todos TV boxes, que salían de fábrica con una puerta trasera. El [informe del Grupo de Inteligencia de Amenazas de Google](https://cloud.google.com/blog/topics/threat-intelligence/shutting-the-door-on-the-xcpe-botnet/) (GTIG), publicado el 2 de noviembre, se la atribuye a **Videostrong Technology**, un fabricante que arma TV boxes para muchas marcas blancas: *"We assess with high confidence that the backdoor and botnet were intentionally developed, deployed, and operated for financial gain by Videostrong Technology Co."*

La región está en el centro. Según la [telemetría de TrendAI](https://www.trendaisecurity.com/en-us/resources-insights/trendai-security-blog/trendaitm-research-helps-disrupt-android-tv-botnet-of-more-than-13-million-pre-infected-devices), entre el 3 de junio y el 1 de octubre **Brasil fue el segundo país con más equipos infectados (150.080) y México el cuarto (94.588)**. En la tabla de marcas del [informe de Plume](https://www.plume.com/xcpe-botnet-whitepaper-pdf) aparecen modelos que se venden acá: el **INTV-110** y el **INTV-1000** de **Steren**, y el **STV-3000 Plus** de **Aquario**, que Plume describe como un equipo del comercio minorista brasileño y del que contó 40.087 aparatos.

Google tumbó los servidores de control la semana pasada. El implante no se fue: sigue en el firmware de cada aparato, y desde el aparato no se puede borrar.

## Qué hacía la botnet XCPE en cada TV box

El componente se llama **x.cpe** y viene preinstalado en la partición del firmware, con el privilegio del sistema operativo. Si el dueño lo desinstala, vuelve a instalarse solo desde la partición del fabricante en el siguiente reinicio. Primero pide su configuración a un servidor en `anyevonline.com` y después queda conectado de forma permanente a `mqtt.szbboys.com` por MQTT, un protocolo liviano de mensajería. Según Google, Videostrong lo fue cambiando al menos desde fines de 2024, casi 2 años, para esquivar los controles.

Por ese canal, el operador podía instalar aplicaciones sin aviso, ejecutar comandos con privilegios de sistema, capturar la pantalla, inyectar teclas y mapear la red local. Antes de instalar, el implante apagaba la verificación de **Google Play Protect**. Google dice que no fue solo capacidad: *"We also observed Videostrong operators executing sensitive actions on the device, including capturing live screenshots; injecting keystrokes; exfiltrating sensitive data including account information, device information, package inventories, and system logs; and executing shell scripts and commands, including to establish reverse shells on devices."*

```svg
<svg viewBox="0 0 680 318" role="img" aria-label="Cómo un TV box Android pasa a manos de otro en la botnet XCPE, según Google. Primero, el firmware de fábrica trae el componente x.cpe con privilegio de sistema; si se lo desinstala, vuelve a instalarse al reiniciar. Segundo, el implante pide su configuración a un servidor en anyevonline.com. Tercero, abre un canal permanente por MQTT con mqtt.szbboys.com. Por ese canal, el operador apaga la verificación de Google Play Protect e instala módulos sin aviso, que hacen tres cosas: convierten la conexión del dueño en un proxy residencial que usan otros, generan fraude publicitario, y dan control remoto con comandos de shell, capturas de pantalla y mapeo de la red local. Google cortó los servidores de control a fines de octubre de 2026, pero x.cpe sigue en el firmware de cada equipo.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Botnet XCPE: cómo un TV box Android pasa a manos de otro</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">El recorrido que describe Google. El rojo marca dónde empieza: en la fábrica.</text>
  <rect x="16" y="62" width="204" height="62" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="118" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Firmware de fábrica</text>
  <text x="118" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">x.cpe con privilegio de sistema</text>
  <rect x="238" y="62" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="340" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Configuración</text>
  <text x="340" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">la pide a anyevonline.com</text>
  <rect x="460" y="62" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="88" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Canal permanente</text>
  <text x="562" y="106" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">MQTT con mqtt.szbboys.com</text>
  <path d="M222 93 H234 M229 88 L234 93 L229 98" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 93 H456 M451 88 L456 93 L451 98" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 124 V168 M557 163 L562 168 L567 163" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="552" y="146" class="ch-label" font-size="9.5" text-anchor="end" fill="currentColor" opacity="0.8">apaga Play Protect e instala módulos sin aviso</text>
  <rect x="16" y="174" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="118" y="200" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Proxy residencial</text>
  <text x="118" y="218" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">tu conexión, alquilada a otros</text>
  <rect x="238" y="174" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="340" y="200" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Fraude publicitario</text>
  <text x="340" y="218" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">anuncios que nadie ve</text>
  <rect x="460" y="174" width="204" height="62" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="200" class="ch-strong" font-size="11.5" text-anchor="middle" font-weight="600" fill="currentColor">Control remoto</text>
  <text x="562" y="218" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">shell, capturas, mapeo de red</text>
  <text x="20" y="266" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Google cortó los servidores a fines de octubre. x.cpe sigue en el firmware de cada equipo.</text>
  <text x="20" y="284" class="ch-label" font-size="11" fill="currentColor" opacity="0.8">Si se lo desinstala, vuelve a instalarse desde la partición del fabricante al reiniciar.</text>
  <text x="20" y="306" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: Google Threat Intelligence Group, 2 de noviembre de 2026.</text>
</svg>
```

El negocio era vender el acceso. Videostrong cargaba en los aparatos módulos de terceros que los convertían en **nodos de proxy residencial**: la conexión del dueño se alquila para que otros naveguen como si estuvieran en esa casa o esa oficina. Otros módulos hacían **fraude publicitario**, con anuncios cargados en segundo plano que nadie veía. TrendAI contó **3.849.226 direcciones IP residenciales distintas** usadas como salida en 4 meses, con un promedio de 182.025 disponibles por día, y vio al operador poner a trabajar cerca del 90% de los aparatos que controlaba.

La mayoría eran TV boxes, pero Google encontró el mismo implante en equipos de telemedicina, karaokes y reproductores de cartelería digital. TrendAI suma proyectores.

## Brasil y México, entre los cinco países con más TV boxes infectados

```svg
<svg viewBox="0 0 680 292" role="img" aria-label="Botnet XCPE: países con más TV boxes y otros aparatos Android infectados, según la telemetría de TrendAI entre el 3 de junio y el 1 de octubre de 2026. Malasia, 300.390 equipos. Brasil, 150.080. Irak, 124.298. México, 94.588. Taiwán, 88.350. Estados Unidos, 43.871. Brasil es el segundo país y México el cuarto. De América Latina, solo Brasil y México superan los 25.000 equipos. TrendAI contó 1.472.554 equipos en más de 170 países, y aclara que es un piso porque la recolección fue intermitente.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Botnet XCPE: los países con más TV boxes infectados</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Equipos observados por TrendAI entre el 3 de junio y el 1 de octubre de 2026.</text>
  <path d="M160.0 58 V244" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="160" y="259" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">0</text>
  <path d="M281.2 58 V244" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="281.2" y="259" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">100.000</text>
  <path d="M402.4 58 V244" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="402.4" y="259" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">200.000</text>
  <path d="M523.6 58 V244" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="523.6" y="259" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">300.000</text>
  <text x="150" y="77" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Malasia</text>
  <path d="M160,64 H520.1 A4,4 0 0 1 524.1,68 V78 A4,4 0 0 1 520.1,82 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Malasia: 300.390 equipos infectados observados</title></path>
  <text x="530.1" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">300.390</text>
  <text x="150" y="107" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">Brasil</text>
  <path d="M160,94 H337.9 A4,4 0 0 1 341.9,98 V108 A4,4 0 0 1 337.9,112 H160 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>Brasil: 150.080 equipos infectados observados</title></path>
  <text x="347.9" y="107" class="ch-value" font-size="11" font-weight="600" fill="currentColor">150.080</text>
  <text x="150" y="137" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Irak</text>
  <path d="M160,124 H306.7 A4,4 0 0 1 310.7,128 V138 A4,4 0 0 1 306.7,142 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Irak: 124.298 equipos infectados observados</title></path>
  <text x="316.7" y="137" class="ch-value" font-size="11" font-weight="600" fill="currentColor">124.298</text>
  <text x="150" y="167" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">México</text>
  <path d="M160,154 H270.7 A4,4 0 0 1 274.7,158 V168 A4,4 0 0 1 270.7,172 H160 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>México: 94.588 equipos infectados observados</title></path>
  <text x="280.7" y="167" class="ch-value" font-size="11" font-weight="600" fill="currentColor">94.588</text>
  <text x="150" y="197" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Taiwán</text>
  <path d="M160,184 H263.1 A4,4 0 0 1 267.1,188 V198 A4,4 0 0 1 263.1,202 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Taiwán: 88.350 equipos infectados observados</title></path>
  <text x="273.1" y="197" class="ch-value" font-size="11" font-weight="600" fill="currentColor">88.350</text>
  <text x="150" y="227" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Estados Unidos</text>
  <path d="M160,214 H209.2 A4,4 0 0 1 213.2,218 V228 A4,4 0 0 1 209.2,232 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Estados Unidos: 43.871 equipos infectados observados</title></path>
  <text x="219.2" y="227" class="ch-value" font-size="11" font-weight="600" fill="currentColor">43.871</text>
  <text x="20" y="282" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: TrendAI. De América Latina, solo Brasil y México pasan de 25.000 equipos.</text>
</svg>
```

En los 120 días que van del 3 de junio al 1 de octubre, TrendAI contó **1.472.554 equipos infectados de 206 modelos en más de 170 países**, y aclara que es un piso: la recolección fue intermitente y cada socio vio una parte distinta, por eso la cifra conjunta que da Google es "más de 1,3 millones". Sobre ese total, Malasia concentra el 20%, Brasil el 10% y México el 6%, por cálculo propio con las cifras de TrendAI. En su gráfico de países con al menos 25.000 equipos, los únicos de América Latina son Brasil y México. El resto de la región no tiene cifras publicadas.

La tabla de Plume agrupa los aparatos por el fabricante que declara el firmware. Ahí está Steren en dos filas: el INTV-110 dentro de 59.031 equipos que se identifican como "Google", y el INTV-1000 entre los que declaran a Videostrong. La lista pública de [Synthient con 182 nombres de modelo](https://github.com/synthient/public-research/tree/main/2026/11/Videostrong) incluye además el INTV_ASIST de Steren y varias variantes de MECOOL, la marca del equipo que Synthient analizó. Synthient advierte que **el nombre del modelo solo no confirma la infección**.

La certificación de Google no alcanzó. Según Plume, la flota mezclaba aparatos con Android de código abierto y aparatos certificados por Google. Google reconoce que Videostrong aprovechó una brecha en sus pruebas para cargar el código malicioso después del primer arranque, y dice que ya cambió su proceso de revisión. También le prohibió a Videostrong, y a cualquier filial futura, fabricar dispositivos Android certificados.

## ¿Por qué un TV box infectado es un problema para una PyME?

Los TV boxes no viven solo en el living. Están en la sala de espera del consultorio, en el bar, en el gimnasio, en la habitación del hotel y detrás de la pantalla de cartelería del local. Si comparten la red con la caja, las computadoras y la impresora, un aparato de la botnet XCPE trae dos problemas:

1. **Tu conexión la usa otro.** Un proxy residencial existe para que el tráfico de un tercero salga con tu IP. TrendAI explica para qué sirve: el tráfico desde una IP hogareña real *"passes IP reputation and geolocation checks that banks, retailers and online services rely on"*. HUMAN, socio de la operación, había documentado que los compradores de un servicio parecido lo usaban para tomar cuentas ajenas y crear cuentas falsas. Lo que hagan, sale con la dirección de tu local.
2. **Hay un equipo con control remoto adentro de tu red.** TrendAI lo dice sin vueltas: *"If the devices were part of an organizational network, the attacker could gain access to the internal network as well."* Los aparatos además juntaban datos del entorno, como los de la red Wi-Fi y la configuración de Bluetooth. Y desde la LAN se explotan fallas que desde internet no se alcanzan, como la de [los switches Zyxel GS1900](/noticia/zyxel-gs1900-cve-2026-7273-switches-desde-la-lan), que se ataca sin contraseña desde la red interna.

Para quien trabaja desde casa vale lo mismo: el TV box del living comparte red con la notebook de la empresa. TrendAI recomienda tratar esos aparatos del hogar como parte de la superficie de exposición y dejar el acceso corporativo solo para equipos administrados. Cómo darlo sin abrir la red de la oficina está en [la guía de acceso remoto sin VPN](/guia/acceso-remoto-seguro-sin-vpn).

## ¿Cómo sé si un TV box de mi oficina está en la botnet XCPE?

1. **Mirá el modelo.** En un TV box con Android TV, el nombre suele estar en *Configuración > Preferencias del dispositivo > Información*. Comparalo con la lista de Synthient. De las marcas que se venden en la región figuran Steren, con los INTV, y Aquario, con el STV-3000 Plus; además aparecen MECOOL y los genéricos "4K Android TV Box". Una coincidencia no prueba la infección, pero alcanza para aislarlo como se explica más abajo.
2. **Buscá los dominios en los registros de la red.** Si tu router, tu firewall o tu filtro de DNS guardan consultas, buscá `anyevonline.com` y `szbboys.com`, con sus subdominios. Según Google, x.cpe pide al primero su configuración inicial, antes de conectarse al canal de control.
3. **Buscá las conexiones al canal de control.** Google identificó el servidor MQTT en las IP `219.134.163.210` y `219.134.163.211`, en los puertos TCP 3885 a 3887, que no son los habituales de MQTT. Una conexión saliente a esas IP y puertos desde tu red señala un equipo infectado.

## ¿Qué hago si tengo un TV box Android en la red de la empresa?

1. **Sacalo hoy de la red de trabajo.** Pasalo a la red de invitados con aislamiento entre clientes, o a una VLAN propia, sin acceso a las computadoras, la caja, el NAS ni la impresora. Cómo armar esa separación está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).
2. **Si viste tráfico a esos dominios o IP, reemplazalo.** Si solo coincide el modelo, tenerlo aislado es lo mínimo y reemplazarlo es lo seguro. Plume recomienda un reset de fábrica para sacar lo que los módulos hayan instalado, pero x.cpe vive en el firmware y vuelve en el siguiente arranque. TrendAI lo resume: la limpieza *"needs vendor action, platform protection or replacement of the device"*. Synthient recomienda descartarlo.
3. **Bloqueá los dominios.** Agregá `anyevonline.com` y `szbboys.com` a la lista de bloqueo del DNS o del firewall. Los servidores ya cayeron, pero el bloqueo te cubre si los operadores los recuperan; según TrendAI, operadores de este tipo ya montaron infraestructura nueva antes.
4. **Comprá con otra vara.** Plume advierte que pedir un TV box de menos de 30 USD en una tienda online tiene su riesgo. Google recomienda comprar aparatos solo a fabricantes conocidos y publica la [lista de socios de Android TV](https://www.android.com/certified/partners/). Evitá los TV boxes sin marca y las apps que pagan por "compartir tu internet": son la otra puerta de entrada a estas redes, según Google y TrendAI.
5. **No confíes en la IP para dejar entrar a nadie.** Si alguna aplicación de la empresa permite el ingreso según el país o la IP de origen, un proxy residencial la pasa. Lo que frena el robo de cuentas es [una autenticación en dos pasos resistente al phishing](/guia/ya-tenes-mfa-y-no-alcanza), no la geolocalización.

En un TV box no suele haber antivirus ni EDR, así que nadie lo vigila desde adentro; las computadoras de la misma red sí pueden tenerlo, y [las opciones están comparadas acá](/productos/antivirus-y-edr). Cuándo se justifica un EDR está en [la guía de EDR o antivirus para PyMEs](/guia/edr-o-antivirus-cuando-se-justifica-pyme). Con los routers baratos pasa algo parecido: hubo [17 fallas críticas sin parche en routers Linksys, Edimax y Wavlink](/noticia/linksys-edimax-wavlink-17-criticas-routers-sin-parche), entre otros.

## ¿Quién puede ignorar la botnet XCPE?

Los informes describen sobre todo TV boxes, y además sticks, proyectores y equipos de cartelería, telemedicina y karaoke con Android. Quien no tenga ninguno de esos aparatos en la red de la empresa puede ignorar esta botnet. Si en la oficina solo hay computadoras, teléfonos y una impresora, no te toca.

**Fuentes leídas:** el informe del Grupo de Inteligencia de Amenazas de Google del 2 de noviembre, con sus indicadores; el análisis de TrendAI, con la cuenta por país; el informe técnico de Plume Security Labs, con la tabla por fabricante; la lista de modelos e indicadores de Synthient; y la nota de KrebsOnSecurity del 2 de noviembre. **Sin confirmar:** cuántos equipos hay en los demás países de América Latina, que no superan el umbral de 25.000 del gráfico de TrendAI; si todos los lotes de los modelos de Steren y Aquario traen x.cpe; y si esas marcas ofrecen reemplazo o firmware nuevo.

## Preguntas frecuentes sobre la botnet XCPE

### ¿Qué es la botnet XCPE?

Una red de más de 1,3 millones de aparatos Android, casi todos TV boxes, que traían de fábrica un componente llamado x.cpe. Según Google, lo puso el fabricante Videostrong para vender el acceso a redes de proxies residenciales y de fraude publicitario. Google y sus socios tumbaron los servidores de control a fines de octubre de 2026.

### ¿Mi TV box Steren o Aquario está infectado?

Puede estarlo. Plume encontró los Steren INTV-110 e INTV-1000 y el Aquario STV-3000 Plus entre los equipos de la botnet, y Synthient suma el INTV_ASIST. El modelo solo no lo confirma, advierte Synthient. Si tenés uno, sacalo de la red de trabajo y revisá los registros del router en busca de anyevonline.com y szbboys.com.

### ¿Un reset de fábrica elimina XCPE?

No. Plume lo recomienda para sacar lo que los módulos hayan instalado, pero x.cpe está en la partición del firmware y vuelve a instalarse al reiniciar. TrendAI dice que la limpieza requiere una acción del fabricante o reemplazar el equipo.

### ¿La botnet XCPE sigue activa?

Los servidores de control cayeron, así que los operadores perdieron el acceso a los aparatos. El implante sigue en cada uno, y TrendAI advierte que operadores de este tipo ya montaron infraestructura nueva antes. Google estima además que, al tumbar los dominios de los módulos, les quitó a esos operadores el acceso a al menos otros 10 millones de equipos.

### ¿Por qué me importa si el TV box está en la sala de espera?

Si comparte la red con tus computadoras, un aparato de XCPE presta tu conexión para tráfico ajeno, que sale con la IP de tu local, y le da al operador un equipo con control remoto adentro de tu red. Ponelo en una red separada, sin acceso a nada más.
