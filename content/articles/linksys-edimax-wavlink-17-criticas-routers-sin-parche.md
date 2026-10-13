---
title: "Linksys E1700, Edimax BR-6208AC y Wavlink: 17 críticas sin parche"
subtitle: En tres días se publicaron 17 fallas críticas en routers y cámaras de oficina chica. Para ninguna hay parche, en 15 el exploit ya es público y en 11 el fabricante no contestó. La acción no es actualizar.
excerpt: Diecisiete críticas en routers Linksys, Edimax, Wavlink, Netcore, TRENDnet y D-Link en tres días, sin una sola corrección publicada. Cómo saber si te toca y qué hacer cuando no hay parche.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-10-13
updated: 2026-10-13
products: []
meta_title: "Linksys, Edimax y Wavlink: 17 críticas"
meta_description: "17 fallas críticas en routers de oficina chica en tres días, sin parche para ninguna y con exploit público en 15. Cómo saber si te toca y qué hacer."
---

Entre el 11 y el 13 de octubre se publicaron en NVD **17 fallas críticas** en routers, puntos de acceso y cámaras IP de los que hay en cualquier oficina chica: **Linksys E1700**, **Edimax BR-6208AC**, **Wavlink**, **Netcore NR268**, **TRENDnet**, **D-Link DCS-930L** y **TOZED X300**.

Lo que las distingue no es el puntaje. Es que **para ninguna de las 17 hay una corrección publicada**, y que en **11 de las 17** la ficha dice que el fabricante fue contactado y no respondió de ninguna manera. Eso cambia el consejo: acá no sirve "actualizá el firmware", porque no hay firmware nuevo al que pasar.

## Cuántas son y por qué 3 días no es el ritmo normal

Lo primero que hice fue medir si esto es una tanda real o el ritmo habitual de publicación, porque es fácil confundir las dos cosas.

```svg
<svg viewBox="0 0 680 236" role="img" aria-label="Medición de las fallas críticas en equipos de red de oficina chica publicadas en NVD. En la ventana del 14 de agosto al 13 de octubre de 2026 hubo 241 fallas críticas en total, y solo 21 de ellas afectan a routers, puntos de acceso o cámaras IP de marcas de consumo, es decir el 8,7 por ciento. Esas 21 se concentran en apenas 5 de los 40 días que tuvieron alguna crítica. El reparto por día muestra la concentración: el 12 de octubre de 2026 hubo 12 críticas de este tipo sobre 20 críticas de ese día, el 13 de octubre 4 sobre 12, el 28 de septiembre 3 sobre 7, el 11 de octubre 1 sobre 5 y el 3 de septiembre 1 sobre 8. Sumando el 11, el 12 y el 13 de octubre se llega a 17 de las 21 del período, es decir el 81 por ciento de dos meses concentrado en tres días">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">17 de 21 en dos meses cayeron en tres días</text>

  <text x="20" y="46" font-size="9.5" fill="currentColor" opacity="0.75">Días con críticas de equipos de red de consumo (14 ago – 13 oct), y cuántas tuvo cada uno:</text>

  <text x="20" y="72" font-size="10" font-weight="700" fill="#e23a3a">12 oct</text>
  <rect x="96" y="63" width="264" height="12" rx="2" fill="#e23a3a"/>
  <text x="368" y="73" font-size="10" font-weight="700" fill="#e23a3a">12 de las 20 críticas del día</text>

  <text x="20" y="96" font-size="10" font-weight="700" fill="#e23a3a">13 oct</text>
  <rect x="96" y="87" width="88" height="12" rx="2" fill="#e23a3a"/>
  <text x="192" y="97" font-size="10" font-weight="700" fill="#e23a3a">4 de 12</text>

  <text x="20" y="120" font-size="10" fill="currentColor" opacity="0.9">28 sep</text>
  <rect x="96" y="111" width="66" height="12" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="170" y="121" font-size="10" fill="currentColor" opacity="0.9">3 de 7</text>

  <text x="20" y="144" font-size="10" font-weight="700" fill="#e23a3a">11 oct</text>
  <rect x="96" y="135" width="22" height="12" rx="2" fill="#e23a3a"/>
  <text x="126" y="145" font-size="10" fill="currentColor" opacity="0.9">1 de 5</text>

  <text x="20" y="168" font-size="10" fill="currentColor" opacity="0.9">3 sep</text>
  <rect x="96" y="159" width="22" height="12" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="126" y="169" font-size="10" fill="currentColor" opacity="0.9">1 de 8</text>

  <rect x="20" y="184" width="620" height="28" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="202" font-size="10" fill="currentColor" opacity="0.9">En dos meses hubo 241 críticas en total. Solo 21 son de estos equipos (8,7%), en 5 de 40 días.</text>

  <text x="20" y="230" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre la API de NVD, ventana 14 ago – 13 oct de 2026, severidad crítica.</text>
</svg>
```

La medición dice que sí es una tanda: **17 de las 21** críticas de este tipo de los últimos 2 meses se publicaron en estos 3 días, el **81%**. El 12 de octubre, 12 de las 20 críticas del día fueron de estos equipos. En la ventana completa de 2 meses hubo 241 críticas de todo tipo, y las de estos equipos aparecieron en apenas 5 de los 40 días que tuvieron alguna.

Pero conviene entender de dónde salen, porque eso explica la forma de la tanda. **Las 17 las registró VulDB como autoridad de numeración**, y el puntaje de las 17 lo puso VulDB en la escala CVSS 4.0: **ninguna tiene puntaje propio de NVD**. Son hallazgos de investigadores independientes sobre equipos baratos, publicados juntos. No es que los equipos se hayan vuelto vulnerables esta semana; es que el hallazgo llegó al catálogo esta semana.

Eso no las hace menos ciertas, y hay un dato que las vuelve urgentes de todos modos.

## Para ninguna de las 17 hay parche

```svg
<svg viewBox="0 0 680 220" role="img" aria-label="Estado de las 17 fallas críticas de equipos de red publicadas entre el 11 y el 13 de octubre de 2026, según sus fichas en NVD. Ninguna de las 17 tiene una referencia de parche ni de aviso del fabricante: cero de 17. En 15 de las 17 la ficha declara que el exploit ya es público o fue publicado. En 11 de las 17 la ficha dice que el fabricante fue contactado tempranamente sobre la divulgación pero no respondió de ninguna manera. En 5 de las 17 la ficha agrega explícitamente que la falla solo afecta a productos que el fabricante ya no sostiene, y son una de TRENDnet y las cuatro de Edimax BR-6208AC. Las 17 tienen vector de red, y 9 de ellas no requieren ninguna credencial mientras 8 requieren alguna">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué dicen las 17 fichas sobre la corrección</text>

  <rect x="20" y="36" width="620" height="34" rx="5" fill="#e23a3a" opacity="0.15"/>
  <rect x="20" y="36" width="620" height="34" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="58" font-size="11" font-weight="700" fill="#e23a3a">0 de 17 tienen referencia de parche o aviso del fabricante</text>

  <text x="20" y="92" font-size="10" fill="currentColor" opacity="0.9">Exploit ya público o publicado</text>
  <rect x="270" y="83" width="326" height="12" rx="2" fill="#e23a3a"/>
  <text x="604" y="93" font-size="10" font-weight="700" fill="#e23a3a">15 de 17</text>

  <text x="20" y="116" font-size="10" fill="currentColor" opacity="0.9">El fabricante no respondió</text>
  <rect x="270" y="107" width="239" height="12" rx="2" fill="#e23a3a" opacity="0.75"/>
  <text x="517" y="117" font-size="10" font-weight="700" fill="#e23a3a">11 de 17</text>

  <text x="20" y="140" font-size="10" fill="currentColor" opacity="0.9">Producto ya sin soporte, dicho en la ficha</text>
  <rect x="270" y="131" width="109" height="12" rx="2" fill="#e23a3a" opacity="0.55"/>
  <text x="387" y="141" font-size="10" font-weight="700" fill="currentColor">5 de 17</text>

  <text x="20" y="164" font-size="10" fill="currentColor" opacity="0.9">Sin credenciales para explotar</text>
  <rect x="270" y="155" width="196" height="12" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="474" y="165" font-size="10" font-weight="700" fill="currentColor">9 de 17 (las otras 8 piden alguna)</text>

  <text x="20" y="196" font-size="10.5" font-weight="700" fill="#e23a3a">Las 17 son de vector de red: se alcanzan por la red, no hace falta tocar el equipo.</text>
  <text x="20" y="214" font-size="9" fill="currentColor" opacity="0.65">Fichas de NVD de los 17 CVE, consultadas el 13 de octubre de 2026.</text>
</svg>
```

Las cifras del diagrama son el centro de la nota, así que vale desglosarlas.

**Ninguna de las 17 tiene referencia de parche ni de aviso del fabricante.** Lo verifiqué sobre las etiquetas de las referencias de cada ficha: no hay una sola marcada como corrección ni como aviso de fabricante. Las referencias apuntan al repositorio del investigador y a VulDB, nada más.

**En 15 de las 17 la ficha declara que el exploit ya es público.** Acá tuve que contar con cuidado, porque VulDB redacta esa frase de nueve maneras distintas —"el exploit se divulgó públicamente", "el exploit ya es público", "el exploit fue publicado"— y dos fichas la traen en una segunda oración, después de otra que solo habla de explotación remota. Mi primer conteo automático dio 5 y estaba mal; el número, leyendo las 17 fichas, es 15. Las dos que no lo dicen son la de TOZED X300 y una de Netcore NR268.

**En 5 de las 17 la propia ficha agrega** que la falla "solo afecta a productos que el fabricante ya no sostiene": la de TRENDnet y las cuatro de Edimax BR-6208AC. Para esas cinco, la pregunta de cuándo sale el parche ya tiene respuesta.

**Las 17 son de vector de red** y **9 no requieren ninguna credencial**. Las otras 8 piden alguna, que en un router de estos suele significar el usuario del panel de administración.

## Las 17 fallas están en la administración del equipo, no en la señal wifi

Este es el dato que decide si te toca o no, y es el que más conviene entender.

```svg
<svg viewBox="0 0 680 252" role="img" aria-label="Dónde viven las 17 fallas y de qué depende la exposición. Las 17 están en el panel de administración web del propio equipo: las direcciones afectadas son del tipo barra goform barra, como formAutoFWupgrade en el Edimax o ssid1MACFilter en el Linksys, y del tipo barra cgi-bin barra, como ExportAllSettings.cgi en el Wavlink, además de manejadores CGI internos como DDNSset.cgi en el Netcore. Los tipos de falla son siete de inyección de comandos del sistema operativo, cinco de desborde de memoria, tres de inyección genérica, una de autenticación incorrecta y una de verificación insuficiente del origen de los datos. La diferencia práctica es la siguiente. Si el panel de administración solo se alcanza desde la red interna de la oficina, el atacante primero tiene que estar dentro de esa red, por ejemplo desde el wifi de invitados o desde una computadora ya comprometida. Si en cambio el equipo tiene activada la administración remota, el panel queda alcanzable desde internet y la falla se puede usar directamente desde afuera. Por eso la comprobación concreta es averiguar si la administración remota está activada">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Las 17 están en el panel web del equipo</text>

  <text x="20" y="44" font-size="9.5" fill="currentColor" opacity="0.85">Direcciones afectadas: /goform/… (formAutoFWupgrade, ssid1MACFilter) y /cgi-bin/… (ExportAllSettings.cgi)</text>
  <text x="20" y="60" font-size="9.5" fill="currentColor" opacity="0.85">Tipos: 7 de inyección de comandos · 5 de desborde de memoria · 3 de inyección · 1 de autenticación · 1 de origen</text>

  <rect x="20" y="76" width="620" height="68" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="76" width="620" height="68" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="95" font-size="10.5" font-weight="700" fill="currentColor">Si el panel solo se alcanza desde la red de la oficina</text>
  <text x="36" y="114" font-size="9.5" fill="currentColor" opacity="0.9">El atacante primero tiene que estar adentro: el wifi de invitados, una máquina ya</text>
  <text x="36" y="131" font-size="9.5" fill="currentColor" opacity="0.9">comprometida. Sigue siendo grave, pero necesita un paso previo.</text>

  <rect x="20" y="158" width="620" height="68" rx="6" fill="#e23a3a" opacity="0.14"/>
  <rect x="20" y="158" width="620" height="68" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="177" font-size="10.5" font-weight="700" fill="#e23a3a">Si el equipo tiene la administración remota activada</text>
  <text x="36" y="196" font-size="9.5" fill="currentColor" opacity="0.9">El panel queda alcanzable desde internet y la falla se usa directo desde afuera,</text>
  <text x="36" y="213" font-size="9.5" font-weight="700" fill="#e23a3a">sin paso previo. Esta es la comprobación que hay que hacer hoy.</text>

  <text x="20" y="246" font-size="9" fill="currentColor" opacity="0.65">Direcciones y clases de falla tomadas de las fichas de NVD de los 17 CVE.</text>
</svg>
```

Las 17 fallas están en direcciones del panel de administración del equipo: `/goform/formAutoFWupgrade` en el Edimax, `/goform/ssid1MACFilter` en el Linksys, `/cgi-bin/ExportAllSettings.cgi` en el Wavlink. Siete de las 17 son inyección de comandos del sistema operativo, que es la clase que termina en control del equipo.

Y de ahí sale la pregunta que importa: **si ese panel se alcanza desde internet o solo desde la red interna**. Es la misma distinción que en [las fallas de los switches Zyxel que solo se explotan desde la red local](/noticia/zyxel-gs1900-cve-2026-7273-switches-desde-la-lan): no cambia la gravedad de la falla, cambia cuánta gente puede intentarla.

Que aparezcan muchas fallas juntas en un mismo equipo tampoco es inédito, y conviene no leerlo como que el equipo "se rompió" de golpe: pasó lo mismo con [las diez fallas del componente de VPN de WatchGuard Firebox](/noticia/watchguard-firebox-diez-fallas-iked-vpn). Cuando alguien se sienta a revisar un componente en serio, encuentra varias de una vez.

## ¿A quién afectan las 17 fallas de routers de octubre?

A quien tenga uno de estos equipos haciendo de router, punto de acceso o cámara en una oficina, un local o una sucursal chica. Los modelos y versiones exactos de las fichas:

- **Linksys E1700**, versión 1.0.0.4.003 — **5 CVE**, la mayor concentración de la tanda: CVE-2026-108892, CVE-2026-108893, CVE-2026-108894, CVE-2026-108895 y CVE-2026-108896.
- **Edimax BR-6208AC**, versión 1.32 — **4 CVE**, las cuatro declaradas sobre producto sin soporte: CVE-2026-108914, CVE-2026-108915, CVE-2026-108917 y CVE-2026-108918.
- **Netcore NR268**, versión 1.7.121109 — **2 CVE**: CVE-2026-108907 y CVE-2026-108929.
- **Wavlink WL-WN530BE1-A** (versión 251117) y **WL-WN592AX6** (versión 250422) — **3 CVE** entre los dos: CVE-2026-109108, CVE-2026-109109 y CVE-2026-109110.
- **TRENDnet TEW-751DR, TEW-752DRU y TEW733GR**, versiones 1.03B01 y 1.03B03 — **1 CVE**: CVE-2026-108804, también declarado sobre producto sin soporte.
- **D-Link DCS-930L**, versión B1.08B4 — **1 CVE**, que es una cámara IP: CVE-2026-108971.
- **TOZED X300**, hasta la 6.01.3 — **1 CVE**, con el puntaje más alto de la tanda: **CVE-2026-108576, 10,0 asignado por VulDB**, inyección de comandos sin credenciales.

Si lo que tenés a mano es un número de CVE que te reportó un escáner y no el modelo del equipo, esa lista sirve al revés: buscá el número ahí y te dice de qué equipo habla.

El perfil es bastante claro: equipos de entre USD 20 y USD 100, comprados hace años, que siguen andando y por eso nadie los mira. Dos de los modelos de la tanda tienen versiones fechadas en 2025 —el Wavlink WL-WN530BE1-A en la 251117 y el WL-WN592AX6 en la 250422—, así que no todo es hardware de hace una década. Es el mismo problema de fondo que [las cinco fallas viejas que CISA sumó la semana pasada](/noticia/proftpd-bind-struts-cinco-fallas-viejas-kev), y que el soporte de Microsoft que [vence justamente hoy](/noticia/office-2021-windows-server-2012-fin-soporte-13-octubre): software que sigue funcionando no es lo mismo que software sostenido.

## ¿Quién puede ignorar el aviso de Linksys E1700 y Edimax BR-6208AC?

Quien no tenga ninguno de los siete modelos nombrados, que es la mayoría. Esta tanda es específica: son modelos concretos en versiones concretas, no familias enteras.

Quien tenga el equipo **únicamente como punto de acceso detrás de otro router**, sin administración remota y en una red separada de las máquinas de trabajo, tiene el riesgo acotado a quien ya esté adentro de esa red. No desaparece, pero deja de ser una urgencia del día.

Quien tenga uno de estos equipos **sin ninguna conexión a internet** —una cámara en una red aislada, por ejemplo— puede bajar la prioridad, siempre que "aislada" sea un hecho verificado y no una suposición.

Lo que **no** alcanza para ignorarlas: que el equipo tenga una contraseña de administrador fuerte. **Nueve de las 17 no piden credencial alguna**, así que la contraseña no entra en juego. Y tampoco alcanza que el modelo "nunca dio problemas": estas fallas son nuevas en el catálogo, no nuevas en el equipo.

## ¿Cómo sé si el panel de mi Linksys o Edimax se alcanza desde internet?

Tres pasos, y el primero resuelve la mayoría de los casos:

1. **Identificá el equipo.** La marca y el modelo están en la etiqueta de abajo o atrás, y la versión de firmware aparece en la pantalla de inicio del panel de administración. Compará con la lista de arriba: si el modelo no está, terminaste.
2. **Mirá si la administración remota está activada.** En el panel se llama "Remote Management", "Administración remota" o "Acceso desde WAN", y suele estar en la sección de administración o de sistema. Lo normal es que venga desactivada; el problema aparece cuando alguien la activó para poder entrar de afuera y nunca la apagó.
3. **Comprobalo desde afuera, no desde la oficina.** Desde una conexión que no sea la de la oficina —datos del celular, por ejemplo—, probá entrar a la dirección IP pública de la oficina. Si aparece la pantalla de inicio del router, el panel está expuesto. Si no responde, no lo está. Hacerlo desde la red interna no sirve: desde adentro siempre responde.

Para saber si ya lo usaron, en estos equipos no hay mucho registro donde mirar, y conviene ser honesto al respecto. Las señales posibles:

- **Configuración que cambió sin que nadie la cambiara**: servidores DNS distintos de los que pusiste, reglas de redirección de puertos nuevas, una cuenta de administración que no reconocés.
- **El equipo reiniciándose solo** o quedándose sin responder, que es el síntoma típico de las cinco fallas de desborde de memoria.
- **DNS raro** es la señal más valiosa de todas: cambiar el DNS del router es la forma más silenciosa de aprovechar uno de estos equipos, porque redirige el tráfico de toda la oficina sin tocar ninguna computadora.
- **Una cuenta de administración agregada** en el equipo, que es exactamente lo que se vio en [las fallas de firmware de MikroTik RouterOS](/noticia/mikrotik-routeros-mikrotrick-usuario-ops): el atacante no necesita volver a usar la falla si se dejó un usuario propio.

Ninguna de las 17 figura en el catálogo de explotadas de CISA, así que **no hay indicadores publicados que buscar**. Esto es revisión general.

## ¿Qué hago si tengo un Linksys E1700 o un Edimax BR-6208AC?

El orden importa, porque la opción "actualizar" no está disponible:

1. **Apagá la administración remota** si está activada. Es gratis, tarda un minuto y es lo único que corta la exposición desde internet hoy mismo. Si hace falta entrar al equipo desde afuera, que sea por una conexión privada y no abriendo el panel, como plantea [la guía de acceso remoto](/guia/acceso-remoto-seguro-sin-vpn).
2. **Buscá firmware nuevo una vez, para confirmar.** Entrá al sitio de soporte del fabricante con el modelo exacto. Según las fichas no hay corrección para ninguna de las 17, y en cinco casos el producto ya no tiene soporte, pero vale confirmarlo vos: si apareció firmware con fecha posterior al 13 de octubre, aplicalo. Si el último firmware tiene 3 años o más, eso ya es la respuesta.
3. **Si no hay firmware, decidí entre reemplazar o aislar.** Reemplazar un router de oficina chica cuesta bastante menos que un incidente, y es la única solución real cuando el fabricante no contesta. Si no se puede ahora, el equipo pasa a una red separada de las máquinas de trabajo y sin administración remota.
4. **Dejá anotado el modelo, la versión y la fecha de la revisión.** La próxima tanda va a llegar igual, y la mitad del trabajo de hoy fue averiguar qué equipo hay.
5. **Si el equipo es el que da internet a toda la oficina**, sumá una revisión de los DNS configurados y de las redirecciones de puertos. Son las dos cosas que un atacante cambia primero y las dos que nadie mira nunca.

Los términos técnicos están en [el glosario](/guia/glosario-ciberseguridad-pymes), y las opciones para entrar a la red de la oficina sin dejar paneles expuestos, en [la sección de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

Una aclaración sobre el alcance de esta nota. **Los puntajes de las 17 los puso VulDB, no NVD ni los fabricantes**, y eso conviene tenerlo en cuenta al comparar con otras fallas: no hay una segunda opinión sobre la severidad, como sí hay cuando NVD o el fabricante también puntúan. Tampoco verifiqué los hallazgos técnicos por mi cuenta: lo que verifiqué es qué dicen las 17 fichas, cuántas tienen parche, cuántas declaran exploit público y cuántas dicen que el producto ya no tiene soporte. Y por una cuestión de criterio, no enlazo ni describo los repositorios con los exploits, aunque las fichas los listen.

---

## Preguntas frecuentes sobre las 17 fallas de routers de octubre

### ¿Por qué no hay parche para ninguna de las 17 fallas?

Porque son equipos baratos de ciclo corto, y en 11 de las 17 fichas consta que el fabricante fue contactado antes de la divulgación y no respondió de ninguna manera. En 5 de las 17 la ficha agrega que el producto ya no tiene soporte del fabricante, así que para esos el parche no va a llegar. Verifiqué las referencias de las 17 fichas una por una: ninguna tiene una referencia marcada como corrección ni como aviso de fabricante.

### Si no hay parche, ¿sirve de algo cambiar la contraseña del router?

Para estas fallas puntuales, poco: 9 de las 17 no requieren ninguna credencial, así que la contraseña no participa. Para las otras 8, que piden algún acceso, una contraseña fuerte y distinta de la de fábrica sí suma. Conviene hacerlo igual porque protege contra otras cosas, pero no se puede presentar como la solución a esta tanda. Lo que corta la exposición desde internet es apagar la administración remota.

### ¿Tengo que reemplazar el router ya mismo?

Depende de una sola cosa: si el panel de administración se alcanza desde internet. Si la administración remota está activada, apagala hoy y eso baja mucho la urgencia; después planificá el reemplazo con calma. Si no está activada y el equipo está detrás de otro router, el riesgo queda limitado a quien ya esté dentro de la red, y el reemplazo puede entrar en el presupuesto del trimestre. Un equipo sin soporte del fabricante igual tiene fecha de vencimiento.

### ¿Por qué el puntaje lo pone VulDB y no NVD?

Porque VulDB es la autoridad de numeración que registró estos 17 CVE, y la autoridad que registra es la que asigna el puntaje inicial. En las 17 fichas el puntaje está en CVSS 4.0 y la fuente es VulDB; ninguna tiene todavía un puntaje propio de NVD. Eso es normal en fallas recién publicadas, y es un dato que vale conocer: cuando hay dos puntajes suelen diferir, y acá hay uno solo.

### Tengo una cámara IP D-Link vieja, ¿corre el mismo riesgo?

La falla de esta tanda es específica de la D-Link DCS-930L en la versión B1.08B4, así que lo primero es ver si tu modelo y versión coinciden. Dicho eso, el criterio general sí aplica a cualquier cámara IP vieja: son equipos con un panel web, de ciclo de soporte corto, que casi nunca se actualizan. La regla práctica es que una cámara no debería ser alcanzable desde internet ni estar en la misma red que las computadoras de trabajo, con falla publicada o sin ella.
