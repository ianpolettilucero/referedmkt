---
title: "Barracuda: la clave olvidada de un proveedor de IA abrió 10 cuentas en 44 horas"
subtitle: Barracuda le había dado una clave de su nube a un proveedor y no la revocó al terminar el trabajo. Cuando hackearon al proveedor, un atacante la encontró y, con una herramienta de IA, recorrió unas 10 cuentas en menos de dos días. Lo detectaron cuando ya había terminado.
excerpt: Barracuda contó cómo una clave de nube que le dio a un proveedor de IA y nunca revocó abrió 10 cuentas en 44 horas. A quién le toca y qué revisar.
type: news
section: mundo
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-11-18
updated: 2026-11-18
products: []
meta_title: "Barracuda: la clave olvidada de un proveedor"
meta_description: "Barracuda contó cómo una clave de nube que le dio a un proveedor de IA y nunca revocó abrió 10 cuentas en 44 horas. A quién le toca y qué revisar."
---

Barracuda, la empresa de seguridad, publicó el 11 de noviembre [el análisis de un ataque contra su propia nube](https://blog.barracuda.com/2026/11/11/anatomy-of-a-machine-speed-intrusion), firmado por su jefe de seguridad, Arve Kjoelen. La primera frase sobre el origen es poco habitual en un comunicado de una empresa atacada: *"The attacker did not break down our door. It gained access using a valid cloud access key."* Y la siguiente: Barracuda le había dado esa clave a un proveedor que trabaja en inteligencia artificial y no la retiró cuando el proyecto terminó. *"That is on us."*

En octubre hackearon el entorno de desarrollo del proveedor, y según [el aviso de Barracuda](https://trust.barracuda.com/security/barracuda-xdr-integration-credentials), la clave estaba guardada en un archivo de configuración de su repositorio de GitHub. El 1 de noviembre a las 04:33 UTC, el atacante la usó por primera vez. Cuando terminó, cerca de 44 horas después, había tocado unas 10 cuentas de nube.

```svg
<svg viewBox="0 0 680 270" role="img" aria-label="Cronología del ataque a la nube de Barracuda en 2026, en hora UTC, según el propio Barracuda. Octubre: hackean el entorno de desarrollo de un proveedor de inteligencia artificial, que guardaba en su repositorio de GitHub una clave de nube de Barracuda que nunca se había revocado. 1 de noviembre a las 04:33: el atacante usa la clave por primera vez y en minutos mapea lo que abre. Entre el 1 y el 2 de noviembre: salta de las cuentas de nube a un sistema de análisis de datos, a un token del código fuente y a clusters de Kubernetes. 2 de noviembre a las 23:31: última actividad registrada, después de tocar unas 10 cuentas de nube en unas 44 horas. 4 de noviembre: un ingeniero nota actividad rara en su cuenta y avisa; para entonces el ataque ya había terminado. 10 de noviembre: Barracuda publica su aviso y contacta a los clientes de XDR posiblemente afectados. 11 de noviembre: publica el análisis completo.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Barracuda: lo detectaron cuando el ataque ya había terminado</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">2026, hora UTC. El rojo marca la detección.</text>
  <path d="M106 58 V232" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">oct.</text>
  <circle cx="106" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>oct.: Hackean al proveedor de IA; la clave estaba en su GitHub</title></circle>
  <text x="122" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Hackean al proveedor de IA; la clave estaba en su GitHub</text>
  <text x="20" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">1 nov. 04:33</text>
  <circle cx="106" cy="92" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>1 nov. 04:33: Primer uso de la clave: en minutos mapea lo que abre</title></circle>
  <text x="122" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Primer uso de la clave: en minutos mapea lo que abre</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">1-2 nov.</text>
  <circle cx="106" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>1-2 nov.: Salta de las cuentas de nube al código y a Kubernetes</title></circle>
  <text x="122" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Salta de las cuentas de nube al código y a Kubernetes</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">2 nov. 23:31</text>
  <circle cx="106" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>2 nov. 23:31: Última actividad registrada: unas 10 cuentas en 44 horas</title></circle>
  <text x="122" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Última actividad registrada: unas 10 cuentas en 44 horas</text>
  <text x="20" y="174" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">4 nov.</text>
  <circle cx="106" cy="170" r="5" class="ch-dot-hi" fill="#e23a3a"><title>4 nov.: Un ingeniero ve algo raro en su cuenta y avisa</title></circle>
  <text x="122" y="174" class="ch-strong" font-size="10.5" fill="currentColor">Un ingeniero ve algo raro en su cuenta y avisa</text>
  <text x="20" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">10 nov.</text>
  <circle cx="106" cy="196" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>10 nov.: Barracuda publica el aviso y contacta a clientes de XDR</title></circle>
  <text x="122" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Barracuda publica el aviso y contacta a clientes de XDR</text>
  <text x="20" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">11 nov.</text>
  <circle cx="106" cy="222" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>11 nov.: Publica el análisis completo del ataque</title></circle>
  <text x="122" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Publica el análisis completo del ataque</text>
  <text x="20" y="260" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: aviso de Barracuda del 10 y 13 de noviembre, y análisis del 11.</text>
</svg>
```

## ¿Qué hizo el atacante dentro de la nube de Barracuda?

Buscó credenciales y las usó una tras otra. Con la clave leyó secretos guardados, como claves de acceso de AWS y de Azure. Con esos secretos saltó a un sistema de análisis de datos, de ahí a un token de acceso al código fuente y de ahí a clusters de Kubernetes. En menos de cuatro horas, el 2 de noviembre, creó ramas en varios repositorios para que el sistema de compilación imprimiera sus propios secretos en el registro, codificados para que el sistema no los ocultara.

Antes de irse dejó una puerta trasera: un contenedor en Kubernetes que podía leer los archivos del servidor y sus credenciales. También intentó crearse una identidad de administrador en la nube, y eso se bloqueó. Barracuda dice que buscaba dos cosas: más credenciales y criptomonedas, con comandos que rastreaban billeteras, frases de recuperación y claves privadas. No había ninguna en esos sistemas. Las billeteras también fueron el botín en [el robo a Binance](/noticia/hackeo-binance-6300-millones-tradertraitor-devops), que empezó por la computadora de un ingeniero con acceso a producción. Y no descarta un tercer objetivo: algunas de las credenciales al alcance eran de servicios de IA.

Lo detectó un ingeniero el 4 de noviembre, al ver actividad rara en su propia cuenta. Ese mismo día, el proveedor le avisó de su incidente, aunque no creía que Barracuda estuviera afectada. Para entonces el ataque había terminado. Barracuda lo resume así: *"That gap is the most important lesson of this incident."*

```svg
<svg viewBox="0 0 680 292" role="img" aria-label="Cómo pasó el atacante de una clave olvidada a la infraestructura de Barracuda, según su análisis. Primero, una clave de acceso a la nube que Barracuda le había dado a un proveedor de inteligencia artificial y no revocó al terminar el proyecto; ese es el paso que Barracuda reconoce como propio. Segundo, el atacante la encuentra en un archivo de configuración del GitHub del proveedor, hackeado en octubre. Tercero, con la clave lee secretos guardados, como claves de AWS y de Azure. Cuarto, salta a un sistema de análisis de datos. Quinto, consigue un token de acceso al código fuente y hace que el sistema de compilación imprima sus secretos. Sexto, llega a clusters de Kubernetes y deja un contenedor como puerta trasera. Cada credencial abría la siguiente puerta.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Barracuda: cada credencial abría la puerta siguiente</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según el análisis de Barracuda. El rojo marca el paso que Barracuda reconoce como propio.</text>
  <rect x="16" y="62" width="204" height="58" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>Clave dada a un proveedor: y nunca revocada</title></rect>
  <text x="118" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Clave dada a un proveedor</text>
  <text x="118" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y nunca revocada</text>
  <rect x="238" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Robada de su GitHub: el proveedor fue hackeado</title></rect>
  <text x="340" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Robada de su GitHub</text>
  <text x="340" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">el proveedor fue hackeado</text>
  <rect x="460" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Secretos guardados: claves de AWS y de Azure</title></rect>
  <text x="562" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Secretos guardados</text>
  <text x="562" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">claves de AWS y de Azure</text>
  <path d="M222 91 H234 M229 86 L234 91 L229 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 91 H456 M451 86 L456 91 L451 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 120 V138 H118 V154 M113 149 L118 154 L123 149" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="16" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Análisis de datos: otro sistema, otra clave</title></rect>
  <text x="118" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Análisis de datos</text>
  <text x="118" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">otro sistema, otra clave</text>
  <rect x="238" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Código fuente: la compilación imprime secretos</title></rect>
  <text x="340" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Código fuente</text>
  <text x="340" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">la compilación imprime secretos</text>
  <rect x="460" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Kubernetes: y un contenedor de puerta trasera</title></rect>
  <text x="562" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Kubernetes</text>
  <text x="562" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y un contenedor de puerta trasera</text>
  <path d="M222 185 H234 M229 180 L234 185 L229 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 185 H456 M451 180 L456 185 L451 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="20" y="246" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Barracuda: una clave de vida corta y alcance limitado lo habría frenado en el primer paso.</text>
  <text x="20" y="276" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: &quot;Forty-four hours: Anatomy of a machine-speed intrusion&quot;, Barracuda, 11 de noviembre.</text>
</svg>
```

## ¿Qué es ARTEX y por qué el ataque fue tan rápido?

Barracuda identificó la herramienta por las huellas que dejó: el atacante nombraba los recursos que creaba con la letra "i" seguida de números, como `i31911` o `i19engx1`. Ese patrón, el orden del recorrido y las rutinas para buscar credenciales y billeteras coinciden con **ARTEX**, un sistema de agentes de IA para pruebas de penetración publicado como código abierto en julio de 2026. Barracuda aclara que eso dice cómo operó el atacante, no quién era, porque muchos grupos lo usan.

El FBI publicó el 13 de noviembre [una alerta FLASH sobre ARTEX](https://www.ic3.gov/CSA/2026/261113.pdf). Según el FBI, distintos grupos lo usan desde agosto de 2026 contra organizaciones de todo el mundo, y algunas intrusiones pasaron *"from initial targeting to privileged access in under three hours"*. El desarrollador retiró el repositorio en octubre, pero sigue circulando en copias. Entre los casos que cita el FBI está el ataque a ConnectWise Cloud Backup, un servicio de backup de Microsoft 365, el tipo de copia que explica [la guía de backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta), con intentos de inicio de sesión en más de 2.250 organizaciones clientes. Ya habíamos visto a ARTEX buscando secretos expuestos en GitHub en [la campaña contra Entra ID](/noticia/entra-id-secretos-de-apps-robados-proveedores-it).

## ¿Me toca si soy cliente de Barracuda?

Solo si usás una integración por API de **Barracuda Managed XDR Cloud** o **Monitored Endpoint**. Uno de los sistemas a los que llegó el atacante guarda las credenciales con las que XDR se conecta a los sistemas de los clientes, como Microsoft 365 o Google Workspace. Barracuda dice que no encontró evidencia directa de que se hayan visto, pero que sus registros no alcanzaban para descartarlo, y avisó a los clientes posiblemente afectados.

Si recibiste ese aviso, Barracuda pide revocar y volver a crear las credenciales de integración, y revisar la actividad de los sistemas conectados desde el 1 de noviembre. El aviso publica como indicadores las direcciones 180.178.56.10, 52.128.238.126 y 221.146.68.107, una dirección IPv6 y varios agentes de usuario. Si no recibiste ningún aviso, según Barracuda no tenés que hacer nada. Este incidente no tiene relación con [la falla de Barracuda Email Security Gateway](/noticia/barracuda-esg-cve-2026-116606-111560-root-sin-contrasena) de la semana pasada.

## ¿Qué reviso en mi empresa después del ataque a Barracuda?

La lección sirve aunque no tengas nada de Barracuda. Cualquier empresa le da accesos a proveedores: el que armó la web, el estudio contable, el que integró el sistema de facturación, el consultor de IA. Barracuda propone cambiar el foco de *"detect-and-respond"* a prevenir y poder recuperarse:

1. **Hacé la lista de claves y accesos que tienen tus proveedores.** Claves de API, cuentas de servicio, usuarios en la nube, tokens de GitHub. Si no sabés cuántas hay, ya encontraste el problema.
2. **Revocá las de proyectos terminados.** Es exactamente lo que falló en Barracuda, que ahora dice que va a vencer las credenciales de los proveedores cuando termine cada trabajo.
3. **Preferí claves de vida corta y alcance mínimo.** Según Barracuda, *"Short-lived, narrowly scoped credentials would have stopped this intrusion at its first step."* Si una clave tiene que existir, que venza sola y que no abra más de lo necesario.
4. **No guardes secretos en el código ni en archivos de configuración.** El FBI recomienda buscar credenciales en la configuración, los scripts y los repositorios, y cambiar en el momento las que aparezcan. Un gestor de contraseñas para equipos ayuda a sacarlas de ahí; las opciones están en [la comparativa de gestores de contraseñas](/comparativa/comparativa-1password-vs-bitwarden-vs-dashlane).
5. **Que tus proveedores te avisen, y vos a ellos.** El aviso del proveedor llegó cuando el ataque ya había terminado, y aun así ayudó. Pedí en el contrato que te informen de cualquier incidente, aunque no crean que te afecte.

Si tu proveedor de IT administra tus sistemas con herramientas propias, el riesgo es el mismo, como se vio con [N-able N-central](/noticia/n-able-n-central-cve-2026-86218-proveedor-it). Las prioridades generales están en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** el análisis de Barracuda del 11 de noviembre; su aviso del 10 de noviembre, con la actualización del 13; y la alerta FLASH-20261113-001 del FBI sobre ARTEX. **Sin confirmar:** quién es el proveedor de IA, porque Barracuda no lo nombra; quién está detrás del ataque, porque ARTEX lo usan muchos grupos; y si el atacante llegó a ver las credenciales de integración de los clientes de XDR, que Barracuda no puede descartar.

## Preguntas frecuentes sobre el ataque a la nube de Barracuda

### ¿Cómo entró el atacante a la nube de Barracuda?

Con una clave de acceso válida. Barracuda se la había dado a un proveedor de inteligencia artificial y no la revocó al terminar el proyecto. La clave estaba en el repositorio de GitHub del proveedor, que fue hackeado en octubre.

### ¿Tengo que hacer algo si soy cliente de Barracuda?

Solo si Barracuda te avisó. El aviso alcanza a quienes usan una integración por API de Barracuda Managed XDR Cloud o Monitored Endpoint, que tienen que revocar y volver a crear esas credenciales y revisar la actividad desde el 1 de noviembre de 2026.

### ¿Qué es ARTEX?

Es un sistema de agentes de inteligencia artificial para pruebas de penetración, publicado como código abierto en julio de 2026. Según el FBI, distintos grupos lo usan en ataques reales desde agosto, y algunas intrusiones llegaron a accesos privilegiados en menos de 3 horas.

### ¿Cuánto duró el ataque a Barracuda?

Unas 44 horas, entre el 1 y el 2 de noviembre de 2026. El atacante tocó unas 10 cuentas de nube. Barracuda lo detectó el 4 de noviembre, cuando la actividad ya había terminado.

### ¿Qué puede hacer una PyME para que no le pase lo mismo?

Listar las claves y accesos que tienen sus proveedores, revocar los de proyectos terminados, usar claves que venzan solas y con el menor alcance posible, y no guardar secretos en el código ni en archivos de configuración.
