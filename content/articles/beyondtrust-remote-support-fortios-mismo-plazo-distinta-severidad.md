---
title: "BeyondTrust Remote Support y FortiOS: un 9,8 y un 6,5 con el mismo plazo"
subtitle: Entraron juntas al catálogo de CISA el 16 de octubre y no se parecen en nada: una no pide credenciales y se publicó anteayer, la otra exige admin y llevaba 185 días publicada. El plazo es el mismo para las dos.
excerpt: Dos vulnerabilidades explotadas con 3,3 puntos de diferencia y el mismo plazo al 19 de octubre. Qué versión mirar en cada una y por qué el puntaje no decide la urgencia.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-10-17
updated: 2026-10-17
products: []
meta_title: "BeyondTrust Remote Support: 9,8 ya explotada"
meta_description: "Dos fallas explotadas con el mismo plazo: BeyondTrust Remote Support 9,8 sin credenciales y FortiOS 6,5 con admin. Qué versión mirar en cada una."
---

El 16 de octubre el catálogo de vulnerabilidades explotadas de CISA sumó **dos entradas**, las dos con plazo al **19 de octubre**, así que hoy quedan **2 días**. Y sirven para ver, en el mismo día y con el mismo plazo, que **el catálogo no está midiendo gravedad**:

- **CVE-2026-102764**, en **BeyondTrust Remote Support**: **9,8** según NVD, **sin credenciales**, y la ficha se publicó **un día antes** de entrar al catálogo.
- **CVE-2025-61624**, en **FortiOS, FortiPAM, FortiProxy y FortiSwitchManager**: **6,5** según NVD, **requiere un administrador ya autenticado**, y llevaba **185 días** publicada.

Son **3,3 puntos** de diferencia y seis meses de distancia entre una ficha y la otra. El plazo es idéntico.

## CVE-2026-102764 y CVE-2025-61624: qué hace falta para usar cada una

```svg
<svg viewBox="0 0 680 258" role="img" aria-label="Comparación de las dos vulnerabilidades que CISA agregó a su catálogo de explotadas el 16 de octubre de 2026, las dos con plazo al 19 de octubre. La primera es CVE-2026-102764 en BeyondTrust Remote Support: puntaje 9,8 crítico asignado por NVD como fuente primaria y 9,3 crítico asignado por BeyondTrust actuando como autoridad de numeración, clasificada como CWE-290 bypass de autenticación por suplantación, y no requiere ninguna credencial. Según la descripción que redactó BeyondTrust, los componentes internos del producto no validaban adecuadamente el origen de los mensajes que recibían, lo que permite a un atacante realizar operaciones de archivos sobre el equipo. Afecta las versiones hasta la 26.2.3 inclusive. Su ficha se publicó el 15 de octubre y entró al catálogo el 16, un solo día después. La segunda es CVE-2025-61624 en FortiOS, FortiPAM, FortiProxy y FortiSwitchManager: puntaje 6,5 medio asignado por NVD y 6,0 medio asignado por Fortinet, clasificada como CWE-22 recorrido de rutas, y requiere un atacante ya autenticado con perfil de administrador y permisos de lectura y escritura para escribir o borrar archivos mediante comandos específicos de la consola. Su ficha se publicó el 14 de abril de 2026 y entró al catálogo 185 días después. Ninguna de las dos figura con uso conocido en campañas de ransomware">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Mismo día, mismo plazo, nada más en común</text>

  <rect x="20" y="36" width="620" height="96" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="36" width="620" height="96" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="56" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-102764 — BeyondTrust Remote Support — hasta la 26.2.3</text>
  <text x="36" y="76" font-size="16" font-weight="700" fill="#e23a3a">9,8</text>
  <text x="74" y="76" font-size="9.5" fill="currentColor" opacity="0.9">de NVD · 9,3 de BeyondTrust · CWE-290</text>
  <text x="36" y="97" font-size="9.5" fill="currentColor" opacity="0.9">No validaba el origen de los mensajes que recibía. Permite operar</text>
  <text x="36" y="112" font-size="9.5" fill="currentColor" opacity="0.9">sobre archivos del equipo.</text>
  <text x="36" y="127" font-size="9.5" font-weight="700" fill="#e23a3a">Sin credenciales. Ficha publicada 1 día antes del alta.</text>

  <rect x="20" y="146" width="620" height="90" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="146" width="620" height="90" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="166" font-size="10.5" font-weight="700" fill="currentColor">CVE-2025-61624 — FortiOS, FortiPAM, FortiProxy, FortiSwitchManager</text>
  <text x="36" y="186" font-size="16" font-weight="700" fill="currentColor">6,5</text>
  <text x="74" y="186" font-size="9.5" fill="currentColor" opacity="0.9">de NVD · 6,0 de Fortinet · CWE-22</text>
  <text x="36" y="207" font-size="9.5" fill="currentColor" opacity="0.9">Escribe o borra archivos con comandos de consola.</text>
  <text x="36" y="222" font-size="9.5" font-weight="700" fill="currentColor">Requiere admin con lectura-escritura. Ficha de hace 185 días.</text>

  <text x="20" y="253" font-size="9" fill="currentColor" opacity="0.65">Feed JSON del catálogo de CISA (versión 2026.10.16), fichas de NVD y avisos de los fabricantes, al 17 de octubre de 2026.</text>
</svg>
```

Puesto así, el contraste es el dato. Una no pide nada y es de ejecución remota sobre un equipo de soporte; la otra necesita que el atacante **ya sea administrador** del firewall, que es una posición que no se consigue fácil. Si priorizaras por puntaje, atenderías la primera y dejarías la segunda para la semana que viene. El catálogo les da **3 días a las dos**.

Es el mismo razonamiento que ya apareció con [las siete altas de septiembre, donde CISA dio tres días a una falla de 7,8 y catorce a una de 8,2](/noticia/kev-2-septiembre-sonicwall-switchvox-plazo-tres-dias): el plazo no sigue al puntaje porque no mide lo mismo. Mide qué se está usando ahora.

Y es la cuarta nota seguida de esta semana sobre altas del catálogo: [ayer fue la de Atlassian](/noticia/atlassian-cve-2026-21589-lee-archivos-sin-autenticar). Entre el 13 y el 16 de octubre el catálogo sumó **9 entradas**, más del doble de la mediana de 4 para una ventana de 5 días. Pero conviene no exagerarlo: midiendo las 366 ventanas de 5 días de los últimos 12 meses, **36 de ellas llegaron a 9 o más**, casi una de cada diez, y el máximo del período fue 14. Es una semana cargada, no un récord.

## 1 día contra 185: el plazo tampoco sigue a la antigüedad

```svg
<svg viewBox="0 0 680 212" role="img" aria-label="Comparación del tiempo que tardó cada una de las dos vulnerabilidades en pasar de publicarse su ficha en NVD a entrar al catálogo de explotadas de CISA. La de BeyondTrust, CVE-2026-102764, se publicó en NVD el 15 de octubre de 2026 y entró al catálogo el 16 de octubre: un solo día. La de Fortinet, CVE-2025-61624, se publicó en NVD el 14 de abril de 2026 y entró al catálogo el 16 de octubre: 185 días, algo más de seis meses. Las dos recibieron el mismo plazo de tres días para remediar. La conclusión es que el momento en que una falla entra al catálogo no depende de cuándo se publicó ni de su puntaje, sino de cuándo aparece evidencia de que se está explotando, y eso puede ocurrir el día siguiente a la publicación o medio año después">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Del día de la ficha al día del catálogo</text>

  <text x="20" y="52" font-size="10" font-weight="700" fill="#e23a3a">BeyondTrust</text>
  <rect x="140" y="43" width="3" height="12" rx="1.5" fill="#e23a3a"/>
  <text x="152" y="52" font-size="10" font-weight="700" fill="#e23a3a">1 día</text>
  <text x="210" y="52" font-size="9.5" fill="currentColor" opacity="0.8">ficha del 15 oct, alta el 16 oct</text>

  <text x="20" y="82" font-size="10" font-weight="700" fill="currentColor">Fortinet</text>
  <rect x="140" y="73" width="420" height="12" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="568" y="82" font-size="10" font-weight="700" fill="currentColor">185 días</text>
  <text x="210" y="98" font-size="9.5" fill="currentColor" opacity="0.8">ficha del 14 de abril, alta el 16 de octubre</text>

  <rect x="20" y="116" width="620" height="34" rx="5" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="116" width="620" height="34" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="137" font-size="11" font-weight="700" fill="#e23a3a">Las dos recibieron el mismo plazo: 3 días.</text>

  <text x="20" y="172" font-size="9.5" fill="currentColor" opacity="0.9">Una falla entra al catálogo cuando aparece evidencia de explotación, no cuando se publica su ficha</text>
  <text x="20" y="188" font-size="9.5" fill="currentColor" opacity="0.9">ni según su puntaje. Eso puede pasar al día siguiente o medio año después.</text>

  <text x="20" y="207" font-size="9" fill="currentColor" opacity="0.65">Fechas de publicación de NVD y fechas de alta del feed del KEV, al 17 de octubre de 2026.</text>
</svg>
```

La de Fortinet es el caso que más conviene entender, porque rompe la intuición de que "si es viejo y nadie lo atacó, ya pasó el peligro". Su ficha se publicó el **14 de abril** y estuvo **185 días** sin figurar como explotada. El 16 de octubre entró. Y el aviso de Fortinet, que sí se puede leer, trae su propio campo de explotación conocida marcado en **"Yes"**: el fabricante lo confirma por su cuenta.

## CWE-290 aparece en 7 de 1748 entradas, y 2 llegaron en 4 días

La falla de BeyondTrust tiene una clasificación poco común, y vale medirla antes de decir que es un patrón.

```svg
<svg viewBox="0 0 680 244" role="img" aria-label="Medición de la clase de falla CWE-290, bypass de autenticación por suplantación, en el catálogo de vulnerabilidades explotadas de CISA, versión 2026.10.16 con 1748 entradas. En toda la historia del catálogo hay solo 7 entradas con esa clasificación, es decir el 0,4 por ciento. Las siete, en orden: Zabbix Frontend en febrero de 2022, Apache APISIX en agosto de 2022, Progress Telerik Report Server en junio de 2024, AMI MegaRAC SPx en junio de 2025, TP-Link TL-WR841N en septiembre de 2025, Ivanti Connect Secure el 13 de octubre de 2026 y BeyondTrust Remote Support el 16 de octubre de 2026. Es decir que dos de las siete llegaron en los últimos cuatro días, y las cinco anteriores se reparten en más de tres años. Las dos nuevas están además en equipos cuya función es dar acceso remoto desde afuera: una VPN y una herramienta de soporte remoto. Ninguna de las siete figura con uso conocido en campañas de ransomware">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Las 7 entradas de CWE-290 en toda la historia del catálogo</text>

  <text x="38" y="50" font-size="10" fill="currentColor" opacity="0.8">feb 2022</text>
  <text x="130" y="50" font-size="10" fill="currentColor" opacity="0.9">Zabbix Frontend</text>

  <text x="38" y="72" font-size="10" fill="currentColor" opacity="0.8">ago 2022</text>
  <text x="130" y="72" font-size="10" fill="currentColor" opacity="0.9">Apache APISIX</text>

  <text x="38" y="94" font-size="10" fill="currentColor" opacity="0.8">jun 2024</text>
  <text x="130" y="94" font-size="10" fill="currentColor" opacity="0.9">Progress Telerik Report Server</text>

  <text x="38" y="116" font-size="10" fill="currentColor" opacity="0.8">jun 2025</text>
  <text x="130" y="116" font-size="10" fill="currentColor" opacity="0.9">AMI MegaRAC SPx</text>

  <text x="38" y="138" font-size="10" fill="currentColor" opacity="0.8">sep 2025</text>
  <text x="130" y="138" font-size="10" fill="currentColor" opacity="0.9">TP-Link TL-WR841N</text>

  <text x="38" y="160" font-size="10" font-weight="700" fill="#e23a3a">13 oct 2026</text>
  <text x="130" y="160" font-size="10" font-weight="700" fill="#e23a3a">Ivanti Connect Secure — una VPN</text>

  <text x="38" y="182" font-size="10" font-weight="700" fill="#e23a3a">16 oct 2026</text>
  <text x="130" y="182" font-size="10" font-weight="700" fill="#e23a3a">BeyondTrust Remote Support — soporte remoto</text>

  <rect x="20" y="196" width="620" height="30" rx="5" fill="#e23a3a" opacity="0.13"/>
  <text x="34" y="216" font-size="10.5" font-weight="700" fill="#e23a3a">2 de las 7 llegaron en 4 días. Las otras 5 se reparten en más de 3 años.</text>

  <text x="20" y="240" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre el campo cwes del feed JSON del KEV, versión 2026.10.16, 1748 entradas.</text>
</svg>
```

**CWE-290 es "bypass de autenticación por suplantación"**: el sistema decide quién sos a partir de algo que el atacante puede falsificar. En el caso de BeyondTrust, la descripción que redactó el propio fabricante lo dice sin vueltas: los componentes internos **"no validaban adecuadamente el origen de los mensajes que recibían"**.

Medido sobre el catálogo completo, esa clase aparece en **7 de 1748 entradas, el 0,4%**. Y **2 de esas 7 llegaron en los últimos 4 días**: la de Ivanti Connect Secure del 13 de octubre, que [cubrí el martes](/noticia/ivanti-connect-secure-windows-shell-dos-explotadas-plazo-16), y esta. Las cinco anteriores se reparten en más de tres años.

Las dos nuevas tienen algo más en común, y no es casualidad de clasificación: **las dos están en equipos cuya razón de existir es dejar entrar a alguien de afuera**. Una VPN y una herramienta de soporte remoto. Cuando el trabajo del aparato es decidir quién entra, una falla en cómo decide eso es la falla que más duele.

## La herramienta del proveedor de IT tiene el doble de antecedentes

```svg
<svg viewBox="0 0 680 232" role="img" aria-label="Medición sobre el catálogo de CISA, versión 2026.10.16 con 1748 entradas, de las herramientas de soporte remoto y administración de equipos que usan los proveedores de informática. Hay 21 entradas de este tipo: N-able con 5, BeyondTrust con 4, ConnectWise ScreenConnect con 4, SimpleHelp con 4, Kaseya con 3 y TeamViewer con 1. De esas 21 entradas, 9 figuran con uso confirmado en campañas de ransomware, es decir el 42,9 por ciento, contra 361 de 1748 en todo el catálogo, que es el 20,7 por ciento: algo más del doble de la tasa general. Además, 10 de las 21 entradas se agregaron durante 2026, casi la mitad del total histórico en un solo año. Advertencia de método: 21 entradas es una muestra chica, así que la proporción marca una dirección y no una medida precisa">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué proporción se ató a ransomware</text>

  <text x="20" y="52" font-size="10" fill="currentColor" opacity="0.9">Todo el catálogo</text>
  <rect x="210" y="42" width="166" height="13" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="384" y="52" font-size="10" font-weight="700" fill="currentColor">20,7% — 361 de 1748</text>

  <text x="20" y="80" font-size="10" fill="currentColor" opacity="0.9">Soporte remoto y RMM</text>
  <rect x="210" y="70" width="344" height="13" rx="2" fill="#e23a3a"/>
  <text x="562" y="80" font-size="10" font-weight="700" fill="#e23a3a">42,9%</text>

  <rect x="20" y="96" width="620" height="30" rx="5" fill="#e23a3a" opacity="0.13"/>
  <text x="34" y="116" font-size="10.5" font-weight="700" fill="#e23a3a">9 de 21 entradas de estas herramientas se ataron a ransomware: el doble de la tasa general.</text>

  <text x="20" y="148" font-size="10" font-weight="700" fill="currentColor">Las 21 entradas, por fabricante:</text>
  <text x="38" y="168" font-size="10" fill="currentColor" opacity="0.9">N-able 5 · BeyondTrust 4 · ConnectWise 4 · SimpleHelp 4 · Kaseya 3 · TeamViewer 1</text>

  <text x="20" y="194" font-size="10" font-weight="700" fill="#e23a3a">Y 10 de las 21 se agregaron en 2026: casi la mitad del total histórico en un año.</text>

  <text x="20" y="214" font-size="9.5" fill="currentColor" opacity="0.85">Método: 21 entradas es muestra chica. La proporción marca una dirección, no una medida fina.</text>
  <text x="20" y="228" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre el feed JSON del catálogo de CISA, versión 2026.10.16.</text>
</svg>
```

Las herramientas con las que un proveedor de informática entra a las máquinas de sus clientes suman **21 entradas** en el catálogo, y **9 de esas 21 figuran con uso confirmado en campañas de ransomware: el 42,9%**, contra el **20,7%** del catálogo completo. Es algo más del doble, con la salvedad de que 21 entradas es una muestra chica y la proporción marca una dirección, no una medida fina.

El dato que más preocupa de ese grupo es otro: **10 de las 21 se agregaron en 2026**. Casi la mitad del total histórico en un solo año, y el sitio ya cubrió tres de esas altas —[ScreenConnect](/noticia/screenconnect-cve-2026-84869-infecta-al-tecnico) y [N-able N-central](/noticia/n-able-n-central-cve-2026-86218-proveedor-it) entre ellas—.

**BeyondTrust tiene 4 entradas propias**, todas en su línea de soporte y acceso remoto privilegiado, y una de ellas —de febrero de este año— figura con ransomware confirmado. No es la primera vez que este producto aparece.

## ¿A quién afecta CVE-2026-102764 de BeyondTrust?

A quien tenga un **appliance de BeyondTrust Remote Support en la versión 26.2.3 o anterior**. Ese dato sale del registro oficial del CVE, que BeyondTrust redacta porque actúa como autoridad de numeración: marca como afectado todo hasta la 26.2.3 inclusive.

Y como en el caso de ayer con Ahsay, **lo más probable es que el equipo no sea tuyo sino de quien te da soporte**. BeyondTrust Remote Support es la herramienta con la que un área de sistemas o un proveedor externo toma control de las máquinas para resolver problemas. Si alguien se conecta a tu computadora para arreglarte algo, la pregunta de qué herramienta usa ahora tiene consecuencias.

La de **Fortinet** afecta a FortiOS, FortiPAM, FortiProxy y FortiSwitchManager en una lista larga de versiones, pero con una condición que cambia todo: **requiere que el atacante ya tenga un perfil de administrador con permisos de lectura y escritura**. No es una puerta de entrada; es algo que se usa después de haber entrado. Es el mismo encuadre de [la falla de FortiMail de este mes](/noticia/fortinet-fortimail-cve-2026-104286-apagar-ibe), donde la condición previa definía a quién le tocaba.

## ¿Quién puede ignorar el aviso de BeyondTrust Remote Support?

Quien no tenga BeyondTrust en ninguna parte de su cadena de soporte, directa o a través de un proveedor.

Quien tenga el appliance en una versión **posterior a la 26.2.3**, que es el corte que marca el registro del fabricante.

Para la de Fortinet, quien tenga **FortiPAM 1.8**, que el aviso de Fortinet lista explícitamente como no afectada, o cualquiera de las versiones corregidas.

Y la puede bajar de prioridad quien tenga la administración de sus equipos Fortinet **limitada a unas pocas cuentas controladas**, porque sin un administrador comprometido esta falla no arranca. Lo que **no** alcanza: suponer que "nadie tiene admin de afuera" sin haberlo verificado. La falla pide admin, y la pregunta es cuántas cuentas con ese perfil existen y quién las usa.

## ¿Cómo sé qué versión de BeyondTrust o de FortiOS tengo?

1. **Para BeyondTrust**, la versión del appliance figura en su interfaz de administración. Compará contra **26.2.3**: igual o anterior está afectada. Si el equipo lo opera tu proveedor, la pregunta por escrito es en qué versión está y cuándo la actualiza.
2. **Para los equipos Fortinet**, la versión se ve en el panel o con el comando de estado del sistema. Y acá hay un detalle que importa más que el número: **para FortiOS 7.2, 7.0 y 6.4, y para FortiPAM 1.6 y anteriores, el aviso no da una versión corregida sino que dice "migrá a una release corregida"**. Es decir que en esas ramas no hay parche: hay que cambiar de rama.
3. **Las versiones con el arreglo**, según el aviso de Fortinet: FortiOS 7.6 a la **7.6.5 o superior**, FortiOS 7.4 a la **7.4.10 o superior**, FortiPAM 1.7 a la **1.7.1 o superior**, FortiProxy 7.6 a la **7.6.5 o superior**, FortiProxy 7.4 a la **7.4.12 o superior**, FortiSwitchManager 7.2 a la **7.2.8 o superior** y FortiSwitchManager 7.0 a la **7.0.7 o superior**.

Para saber si ya las usaron, lo que corresponde revisar en cada caso es distinto:

- **En el appliance de BeyondTrust**, archivos nuevos o modificados sin explicación y sesiones de soporte que nadie reconoce. La falla termina en operaciones sobre archivos del equipo, así que el rastro está ahí.
- **En los equipos Fortinet**, el historial de comandos de consola de las cuentas con perfil de administrador, y archivos de configuración que cambiaron de fecha. Como la falla necesita admin, la pregunta real es si alguna de esas cuentas hizo algo que su dueño no hizo.
- **En los dos casos**, cuentas de administración que aparecieron sin que nadie las creara.

Ninguna de las dos figura con uso en ransomware en el catálogo, así que **no hay indicadores publicados que buscar**. Esto es revisión general.

## ¿Qué hago si mi soporte remoto pasa por BeyondTrust?

En este orden, con el plazo venciendo el 19 de octubre:

1. **Preguntá por escrito en qué versión está el appliance**, si no es tuyo. Dos líneas: qué versión de BeyondTrust Remote Support usan y si es posterior a la 26.2.3.
2. **Si es tuyo y está en 26.2.3 o anterior, actualizá.** No hay mitigación publicada que yo haya podido leer, y la falla no pide credenciales.
3. **Para Fortinet, mirá primero en qué rama estás.** Si estás en 7.6 o 7.4 de FortiOS, es un salto dentro de la rama. Si estás en 7.2, 7.0 o 6.4, el aviso te está diciendo que esa rama no tiene arreglo, y eso es un proyecto, no una tarea de la tarde.
4. **Revisá cuántas cuentas de administrador existen en los equipos Fortinet** y quitá las que no hagan falta. Es la medida que más baja el riesgo de esta falla concreta, porque sin admin no arranca, y sirve para cualquier otra que pida lo mismo.
5. **Anotá qué herramienta de soporte remoto usa cada proveedor que entra a tus máquinas.** Con 10 de 21 altas de este tipo en un solo año, esa lista va a volver a hacer falta.

El encuadre de qué dejar expuesto y cómo dar acceso remoto está en [la sección de VPN y acceso remoto](/productos/vpn-y-acceso-remoto), y los términos, en [el glosario](/guia/glosario-ciberseguridad-pymes).

Una aclaración sobre lo que pude leer y lo que no, porque las dos mitades de esta nota no están igual de respaldadas. **El aviso de Fortinet sí se pudo leer**: de ahí salen las versiones corregidas, la condición de administrador, el campo de explotación conocida en "Yes" y el dato de que algunas ramas no tienen parche. **El de BeyondTrust no**: su página de aviso devuelve error 403 desde este entorno, incluso con identificación de navegador, y el artículo de su base de conocimiento está detrás de un inicio de sesión. Lo que digo de BeyondTrust sale del **registro oficial del programa CVE, que el propio fabricante redacta** —verifiqué que la entrada está asignada por BeyondTrust como autoridad de numeración— y del feed del catálogo de CISA. Por eso no puedo decir si BeyondTrust publica mitigación, si su versión en la nube ya fue corregida, ni desde cuándo se explota. También hay que aclarar que entre las referencias de la ficha de Fortinet figura un análisis de una campaña de malware sobre **otro** CVE de FortiManager, no sobre este: no son el mismo hecho.

---

## Preguntas frecuentes sobre CVE-2026-102764 y CVE-2025-61624

### ¿Por qué CISA le da el mismo plazo a una falla de 9,8 y a una de 6,5?

Porque el plazo no mide gravedad sino explotación. Una entra al catálogo cuando hay evidencia de que alguien la está usando, y a partir de ahí el plazo depende de la política del catálogo, no del puntaje. Estas dos lo muestran bien: 3,3 puntos de diferencia, condiciones de uso completamente distintas —una sin credenciales, la otra con admin— y el mismo plazo de 3 días. Si tu proceso de parches ordena por puntaje, la de Fortinet se te cae al fondo de la lista justo cuando el catálogo dice que se está usando.

### La de Fortinet es de abril. ¿Por qué aparece ahora?

Porque su ficha se publicó el 14 de abril pero la evidencia de explotación apareció después: entró al catálogo el 16 de octubre, 185 días más tarde. Es el contraejemplo de la idea de que una falla vieja que nadie atacó ya dejó de ser peligrosa. El propio aviso de Fortinet trae su campo de explotación conocida marcado en "Yes", así que no es solo la lectura de CISA.

### ¿Qué es un bypass de autenticación por suplantación?

Es cuando el sistema decide quién sos a partir de algo que el atacante puede falsificar, en lugar de verificarlo. En esta falla, según la descripción que redactó BeyondTrust, los componentes internos del producto no validaban adecuadamente el origen de los mensajes que recibían, así que un mensaje que parecía venir de un componente legítimo era tratado como tal. Es una clase poco común en el catálogo de CISA: aparece en 7 de sus 1748 entradas, y 2 de esas 7 se agregaron en los últimos 4 días.

### Mi proveedor de IT entra a mis máquinas. ¿Cómo sé si corro riesgo?

La pregunta concreta es qué herramienta usa y en qué versión está, y conviene hacerla por escrito. No es una pregunta invasiva: es la misma que haría cualquier cliente sobre un proveedor que tiene llaves de su casa. El dato que la justifica: las herramientas de soporte remoto y administración suman 21 entradas en el catálogo de explotadas, 9 de ellas con uso confirmado en ransomware, y 10 de las 21 se agregaron en 2026.

### No puedo actualizar mi FortiOS 7.0 esta semana. ¿Qué hago?

Lo primero es saber que el aviso de Fortinet no ofrece una versión corregida para la rama 7.0: dice migrar a una release corregida, o sea cambiar de rama, que es un proyecto con pruebas y ventana de corte. Mientras eso se planifica, la medida que más sirve para esta falla puntual es reducir y controlar las cuentas con perfil de administrador y permisos de escritura, porque sin una de esas cuentas la falla no se puede usar. No es una corrección, es acotar quién podría llegar a ejecutarla.
