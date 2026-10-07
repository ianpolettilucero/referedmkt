---
title: "IBM Langflow: 32 fallas nuevas en 9 días, y 6 ya explotadas"
subtitle: Cinco de las nuevas pasan de 9,0 y la corrección es la versión 1.12.3. Ninguna figura como explotada todavía, pero el producto ya acumula 6 entradas en el catálogo de CISA en 15 meses.
excerpt: El constructor de flujos de IA de IBM sumó 32 CVE en nueve días. Cuántos son críticos, qué versión corrige y por qué su historial en el catálogo de explotadas importa.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-10-07
updated: 2026-10-07
products: []
meta_title: "IBM Langflow: 32 fallas nuevas en 9 días"
meta_description: "Langflow sumó 32 CVE en 9 días, 5 por encima de 9,0, y se corrigen en la 1.12.3. El producto ya tiene 6 entradas en el catálogo de explotadas de CISA."
---

El catálogo de vulnerabilidades explotadas de CISA no suma entradas desde el 4 de octubre, así que la nota de hoy sale de las fichas de NVD y del boletín del fabricante. Y lo que hay ahí es un volumen poco común: **32 vulnerabilidades de Langflow publicadas en 9 días**, entre el 28 de septiembre y el 7 de octubre.

Langflow es la herramienta con la que se arman flujos de inteligencia artificial arrastrando bloques en una pantalla, en lugar de escribir el código a mano. Es de **IBM**, que la adquirió, y la versión afectada es la de código abierto: **de la 1.0.0 a la 1.12.2**. La corrección es la **1.12.3**, y la recomendación del boletín es explícita: *"IBM strongly recommends addressing the vulnerability now by upgrading Langflow OSS to version 1.12.3."*

## Las 32 fallas de Langflow, por severidad

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Distribución por severidad de las 32 vulnerabilidades de Langflow publicadas entre el 28 de septiembre y el 7 de octubre de 2026, según las fichas de NVD consultadas el 7 de octubre. Cinco tienen puntaje de 9,0 o más, con un máximo de 9,9. Veintiuna están entre 7,0 y 8,9. Seis están por debajo de 7,0. Las cinco más altas son CVE-2026-105697 y CVE-2026-105740 con 9,9, y CVE-2026-51886, CVE-2026-104334 y CVE-2026-93674 con 9,8. De las 32, veinticuatro las puntuó el equipo de seguridad de IBM, cinco vinieron por avisos de seguridad de GitHub, dos por CISA y una por VulnCheck">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">32 CVE en 9 días, por puntaje</text>

  <text x="200" y="56" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">9,0 o más</text>
  <rect x="208" y="44" width="70" height="16" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="286" y="56" font-size="10" font-weight="700" fill="#e23a3a">5</text>

  <text x="200" y="84" text-anchor="end" font-size="10.5" fill="currentColor">entre 7,0 y 8,9</text>
  <rect x="208" y="72" width="294" height="16" rx="3" fill="currentColor" opacity="0.35"/>
  <text x="510" y="84" font-size="10" font-weight="700" fill="currentColor">21</text>

  <text x="200" y="112" text-anchor="end" font-size="10.5" fill="currentColor">por debajo de 7,0</text>
  <rect x="208" y="100" width="84" height="16" rx="3" fill="currentColor" opacity="0.35"/>
  <text x="300" y="112" font-size="10" font-weight="700" fill="currentColor">6</text>

  <text x="20" y="148" font-size="11" font-weight="700" fill="currentColor">Las cinco más altas, con 9,9 el máximo</text>
  <text x="20" y="168" font-size="10" fill="currentColor" opacity="0.9">CVE-2026-105697 y CVE-2026-105740 con 9,9. CVE-2026-51886, CVE-2026-104334</text>
  <text x="20" y="183" font-size="10" fill="currentColor" opacity="0.9">y CVE-2026-93674 con 9,8.</text>

  <text x="20" y="208" font-size="9.5" fill="currentColor" opacity="0.7">Fichas de NVD consultadas el 7 de octubre de 2026. Puntajes: 24 del equipo de IBM, 5 de avisos de GitHub, 2 de CISA, 1 de VulnCheck.</text>
</svg>
```

Vale aclarar de entrada lo que **no** dice este dato, porque es la trampa fácil: **ninguna de las 32 figura como explotada**. El boletín de IBM no afirma que haya ataques y el catálogo de CISA no incluye ninguna de ellas. Lo que hay es una tanda grande de fallas corregidas, no una emergencia en curso.

Otra precisión de método: las 32 no son todas del mismo aviso. **24 de ellas referencian el boletín de IBM** publicado el 2 de octubre, y las otras 8 llegaron por otros caminos, entre ellos los avisos de seguridad de GitHub. Las dos más altas del conjunto, las de 9,9, son de ese segundo grupo.

## Langflow ya tiene 6 entradas en el catálogo de explotadas

Acá está la razón por la que la tanda merece una nota en lugar de un encogimiento de hombros. El historial del producto no es neutro.

```svg
<svg viewBox="0 0 680 235" role="img" aria-label="Las seis entradas de Langflow en el catálogo de vulnerabilidades explotadas de CISA, según el feed consultado el 7 de octubre de 2026. El 5 de mayo de 2025 entró CVE-2025-3248, falta de autenticación en un endpoint que permitía ejecución de código sin credenciales. El 25 de marzo de 2026, CVE-2026-33017, inyección de código. El 21 de mayo de 2026, CVE-2025-34291, error de validación de origen que llevaba a compromiso total del sistema. El 7 de julio de 2026, CVE-2026-55255, salteo de autorización para ejecutar el flujo de otro usuario. El 21 de julio de 2026, CVE-2026-0770, ejecución de código arbitrario. Y el 4 de agosto de 2026, CVE-2026-9198, inyección de código con ejecución remota completa sin autenticar en despliegues por omisión. Las seis terminan en ejecución de código, y van de mayo de 2025 a agosto de 2026, o sea 15 meses">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Seis entradas en 15 meses, todas de ejecución de código</text>

  <text x="38" y="52" font-size="10" font-weight="700" fill="currentColor">5 may 2025</text>
  <text x="150" y="52" font-size="10.5" fill="currentColor" opacity="0.9">CVE-2025-3248 — sin autenticación, código remoto</text>

  <text x="38" y="76" font-size="10" font-weight="700" fill="currentColor">25 mar 2026</text>
  <text x="150" y="76" font-size="10.5" fill="currentColor" opacity="0.9">CVE-2026-33017 — inyección de código</text>

  <text x="38" y="100" font-size="10" font-weight="700" fill="currentColor">21 may 2026</text>
  <text x="150" y="100" font-size="10.5" fill="currentColor" opacity="0.9">CVE-2025-34291 — compromiso total del sistema</text>

  <text x="38" y="124" font-size="10" font-weight="700" fill="currentColor">7 jul 2026</text>
  <text x="150" y="124" font-size="10.5" fill="currentColor" opacity="0.9">CVE-2026-55255 — ejecutar el flujo de otro usuario</text>

  <text x="38" y="148" font-size="10" font-weight="700" fill="currentColor">21 jul 2026</text>
  <text x="150" y="148" font-size="10.5" fill="currentColor" opacity="0.9">CVE-2026-0770 — ejecución de código arbitrario</text>

  <rect x="20" y="162" width="620" height="30" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="162" width="620" height="30" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="182" font-size="10" font-weight="700" fill="#e23a3a">4 ago 2026</text>
  <text x="150" y="182" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-9198 — sin autenticar, en despliegues por omisión</text>

  <text x="20" y="214" font-size="11" font-weight="600" fill="#e23a3a">Las seis terminan en ejecución de código. Ninguna es de las 32 de ahora.</text>
  <text x="20" y="230" font-size="9.5" fill="currentColor" opacity="0.7">Feed del catálogo KEV, consultado el 7 de octubre de 2026.</text>
</svg>
```

Las seis llegaron al catálogo por el mismo motivo —evidencia de explotación real— y las seis terminan en lo mismo: **ejecución de código**. La última, de agosto, es la más incómoda de leer porque el catálogo la describe como ejecución remota completa **sin autenticarse y en despliegues por omisión**, o sea en una instalación tal como viene.

## Solo 45 de 699 productos del catálogo llegan a 6 entradas

Para saber si seis es mucho o poco hace falta compararlo, y eso se puede medir sobre el catálogo completo en vez de opinar.

```svg
<svg viewBox="0 0 680 220" role="img" aria-label="Comparación de la velocidad con que Langflow acumuló seis entradas en el catálogo de vulnerabilidades explotadas de CISA frente a otros productos, medido sobre las 1.734 entradas del feed al 7 de octubre de 2026. En el catálogo hay 699 productos distintos y solo 45 de ellos llegan a seis entradas o más. Langflow llegó a seis en 15 meses. Entre los demás productos con seis o más entradas, la mayoría tardó entre cuatro y cinco años: por ejemplo Tomcat en 53 meses, macOS en 57 meses y ColdFusion en 56 meses. La comparación orgánica más cercana es NetScaler, con seis entradas en 13 meses, que es un equipo de acceso remoto expuesto a internet. Hay una advertencia de método: varios productos que figuran llegando a seis entradas en pocos meses lo hicieron en cargas históricas masivas de los primeros años del catálogo, no por una cadencia real">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Cuánto tardan otros productos en llegar a 6 entradas</text>

  <text x="166" y="56" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">Langflow</text>
  <rect x="174" y="44" width="68" height="16" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="250" y="56" font-size="10" font-weight="700" fill="#e23a3a">15 meses</text>

  <text x="166" y="84" text-anchor="end" font-size="10.5" fill="currentColor">NetScaler</text>
  <rect x="174" y="72" width="60" height="16" rx="3" fill="currentColor" opacity="0.45"/>
  <text x="242" y="84" font-size="10" fill="currentColor">13 meses</text>

  <text x="166" y="112" text-anchor="end" font-size="10.5" fill="currentColor">Tomcat</text>
  <rect x="174" y="100" width="239" height="16" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="421" y="112" font-size="10" fill="currentColor">53 meses</text>

  <text x="166" y="140" text-anchor="end" font-size="10.5" fill="currentColor">ColdFusion</text>
  <rect x="174" y="128" width="252" height="16" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="434" y="140" font-size="10" fill="currentColor">56 meses</text>

  <text x="166" y="168" text-anchor="end" font-size="10.5" fill="currentColor">macOS</text>
  <rect x="174" y="156" width="259" height="16" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="441" y="168" font-size="10" fill="currentColor">57 meses</text>

  <text x="20" y="196" font-size="11" font-weight="600" fill="#e23a3a">De 699 productos del catálogo, solo 45 llegan a 6 entradas.</text>
  <text x="20" y="214" font-size="9.5" fill="currentColor" opacity="0.7">Advertencia: varios productos que figuran llegando a 6 en pocos meses lo hicieron en cargas históricas, no por cadencia real.</text>
</svg>
```

La advertencia del pie importa y conviene desarrollarla, porque sin ella el dato se exagera. En el catálogo hay productos que aparecen alcanzando seis entradas en semanas —software de Cisco, Java, el lector de Adobe— pero eso ocurrió porque CISA cargó tandas históricas enteras el mismo día en los primeros años del catálogo. **No son cadencias reales, son días de carga.**

Descontando esos casos, **la comparación orgánica más cercana a Langflow es NetScaler**, con seis entradas en 13 meses. Y ahí está lo que vale la pena pensar: NetScaler es un equipo de acceso remoto expuesto a internet, el blanco más codiciado que existe, sobre el que [este sitio escribió cuatro veces en cinco semanas](/noticia/citrix-netscaler-cve-2026-88779-otra-actualizacion). **Una herramienta para armar flujos de IA igualó esa cadencia.**

## Por qué una herramienta de IA acumula fallas de ejecución de código

No hace falta especular: está en lo que hace el producto. Langflow existe para que alguien defina un flujo y el sistema lo ejecute, lo que significa que **tomar una definición del usuario y convertirla en código que corre es su función, no un accidente**. Dos de las fallas de esta tanda están clasificadas como control indebido de la generación de código, y otra como inyección de comandos del sistema operativo.

Es exactamente el mismo mecanismo que [la falla del AI Gateway de GitLab que el sitio cubrió hace tres días](/noticia/gitlab-ai-gateway-cve-2026-90970-plantilla-de-prompt): un dato del usuario que termina interpretado como instrucción. Ahí escribí que lo nuevo no es la clase de falla sino el lugar donde aparece. **Langflow es el caso extremo de ese argumento**, y le pone números: en una herramienta cuyo trabajo es convertir configuración en ejecución, la frontera entre dato e instrucción es el producto entero.

Eso no significa que la IA sea insegura por naturaleza. Significa algo más útil y más concreto: **esta categoría de herramienta tiene una cadencia de parches que hay que presupuestar**, igual que un equipo de borde. Es la continuación de lo que planteó la nota de [Zimbra y TrueConf](/noticia/zimbra-trueconf-software-autoalojado-parche-propio): lo que instalás vos, lo mantenés vos, y conviene saber cuánto mantenimiento implica antes de instalarlo.

## ¿A quién afecta la tanda de Langflow?

A quien tenga **Langflow OSS entre la 1.0.0 y la 1.12.2** corriendo en algún servidor propio.

Conviene ser honesto sobre el perfil: esto no está en la PyME promedio. Donde aparece es en el equipo de desarrollo que armó un prototipo de asistente, en la agencia que probó automatizar algo con IA, o en la consultora que lo levantó para una demostración. **Y ahí está el riesgo real, que no es técnico sino organizativo: Langflow suele entrar como experimento.**

Un experimento no tiene dueño asignado, no está en el inventario de cosas que alguien actualiza, y si quedó escuchando en un puerto, sigue escuchando. Es la misma categoría de problema que [JFrog Artifactory](/noticia/jfrog-artifactory-cve-2026-42018-admin-en-minutos): infraestructura que instaló el equipo de desarrollo y que nadie del lado de seguridad sabe que existe. Con un historial de seis fallas explotadas, una de ellas sin autenticación en configuración por omisión, un prototipo olvidado es un problema distinto de un prototipo olvidado de cualquier otra cosa.

## ¿Quién puede ignorar el boletín de Langflow?

Quien no tenga Langflow instalado, que es la mayoría. No es un componente que venga con otra cosa: alguien lo instaló a propósito.

Quien use un servicio de IA en la nube en lugar de armar flujos en su propio servidor.

Y quien ya esté en la **1.12.3** o posterior.

Lo que **no** alcanza para ignorarlo: que sea un prototipo, que no esté en producción o que "lo usa una sola persona". Ninguna de esas tres cosas cambia si el servicio está escuchando en la red, que es la única condición que importa para las fallas que no necesitan credenciales.

## ¿Cómo sé si tengo Langflow corriendo en algún lado?

Esta es la pregunta útil de la nota, porque la respuesta más común va a ser "no sé":

1. **Preguntá al equipo de desarrollo** si alguien levantó Langflow para una prueba, y cuándo. Es la vía más rápida y suele resolverlo.
2. **Buscá el proceso o el contenedor.** Si usan contenedores, buscá imágenes cuyo nombre incluya `langflow` en los servidores y en las máquinas de desarrollo. La versión está en la etiqueta de la imagen.
3. **Mirá qué está escuchando** en los servidores donde se hacen pruebas. Un servicio de IA levantado para una demostración y nunca apagado es el caso típico, y aparece como un puerto abierto que nadie reclama.
4. **Si está expuesto a internet**, dalo por escaneado y tratá la revisión como urgente, no como tarea de mantenimiento.

Sobre si alguien ya entró: como ninguna de las 32 figura como explotada, no hay indicadores publicados para buscar. Lo que sí tiene sentido revisar, por el historial del producto, es si el servidor donde corre tiene procesos, tareas programadas o conexiones de salida que nadie reconozca, porque las seis fallas del catálogo terminan en ejecución de código y eso es lo que deja rastro.

## ¿Qué hago si tengo Langflow?

1. **Actualizá a la 1.12.3**, que es la versión que indica el boletín de IBM y la que cierra la tanda completa.
2. **Si no lo podés actualizar hoy, sacalo de la red.** Para un prototipo esto casi nunca tiene costo operativo, y es la medida más efectiva disponible en minutos.
3. **Decidí si se queda o se va.** Si entró como experimento y nadie lo usa, apagarlo es la mejor corrección posible y la única que no genera trabajo futuro.
4. **Si se queda, ponelo en el inventario con dueño**, porque la cadencia medida de este producto es de varias fallas por trimestre y eso necesita a alguien mirando.
5. **Revisá quién tiene acceso**, por las fallas de la tanda que dependen de un usuario autenticado y de saltear autorizaciones entre usuarios.

El encuadre de por qué la capa de IA entra al inventario como cualquier otro software está en [la nota del AI Gateway de GitLab](/noticia/gitlab-ai-gateway-cve-2026-90970-plantilla-de-prompt), y el del otro lado del mismo asunto —qué hacen los atacantes con la IA— en [Aurora y el asistente de código](/noticia/aurora-ransomware-cursor-ia-planear-intrusiones) y en [la falla del kernel que afectó a los agentes](/noticia/kernel-linux-cve-2026-53362-agentes-openai). Los términos, en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes sobre las fallas de Langflow

### ¿Están explotando estas 32 fallas?

No, según lo que se puede verificar hoy. El boletín de IBM no afirma que haya ataques y ninguna de las 32 figura en el catálogo de vulnerabilidades explotadas de CISA. Lo que sí es cierto, y es distinto, es que el mismo producto tiene **seis** fallas anteriores en ese catálogo, todas de ejecución de código y la última de agosto de 2026. El historial es el argumento para actualizar rápido, no una afirmación sobre estas 32.

### ¿Por qué un producto nuevo acumula tantas fallas?

Hay dos explicaciones y las dos aportan. Una es benigna: un producto que recibe atención de investigadores acumula CVE porque alguien lo está mirando, y 24 de estas 32 vinieron del propio equipo de seguridad de IBM, lo que indica revisión interna. La otra es estructural: Langflow convierte configuración del usuario en código que se ejecuta, así que la frontera entre dato e instrucción es su funcionamiento normal, y ahí es donde viven estas fallas.

### Lo tenemos solo en una máquina de desarrollo. ¿Importa?

Importa si está escuchando en la red, y es el caso más frecuente de todos. Las fallas que no necesitan credenciales no distinguen entre un servidor de producción y la máquina de alguien que hizo una prueba: distinguen entre alcanzable y no alcanzable. Si ese equipo está en la red de la oficina, cualquier cosa comprometida de esa red llega. Si está publicado a internet, ya fue escaneado.

### ¿Esto quiere decir que no conviene usar herramientas de IA?

No es la conclusión. La conclusión es más aburrida y más útil: estas herramientas son software con versiones, fallas y parches, y la categoría tiene por ahora una cadencia alta. Antes de adoptar una conviene preguntarse quién la va a mantener y cómo te vas a enterar de los avisos, que son exactamente las mismas preguntas que corresponden a cualquier cosa que instalás en tu servidor.

### ¿Cómo comparo este historial con el de otros productos?

Con una medición y una advertencia. La medición: de 699 productos distintos en el catálogo de explotadas, solo 45 llegan a seis entradas o más, y la mayoría de esos 45 tardó entre cuatro y cinco años. La advertencia: varios productos figuran llegando a seis en pocas semanas, pero eso ocurrió porque CISA cargó tandas históricas enteras el mismo día en los primeros años del catálogo, así que no son cadencias reales. Descontando esos casos, el comparable más cercano a Langflow es NetScaler, con seis entradas en 13 meses.
