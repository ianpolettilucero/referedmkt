---
title: "SonicWall SMA1000 y Sangoma Switchvox: siete fallas al KEV, cinco con plazo de tres días"
subtitle: CISA le dio tres días a una falla de 7,8 y catorce a una de 8,2. El plazo no mide la gravedad: mide qué se está usando ahora.
excerpt: El 2 de septiembre entraron siete vulnerabilidades al catálogo de explotación activa de CISA. Qué producto toca cada una, por qué el plazo no sigue al puntaje y qué tienen en común cuatro de las siete.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-09-03
updated: 2026-09-03
meta_title: "SonicWall SMA1000 y Switchvox: plazo de tres días"
meta_description: "CISA sumó siete fallas al KEV el 2 de septiembre y cinco vencen el 5. SonicWall SMA1000, Switchvox, Artifactory y Kestra: qué versión corrige cada una."
---

El 2 de septiembre CISA sumó **siete vulnerabilidades** al catálogo de explotación activa. **Cinco vencen el 5 de septiembre** —tres días, el tramo más corto que asigna— y dos el 16.

Dos de las cinco urgentes son del mismo equipo: **SonicWall SMA1000**, el aparato de acceso remoto. Otra es de **Sangoma Switchvox**, la central telefónica. Las dos categorías son de PyME, y las dos están en el borde de la red.

| CVE | Producto | Plazo | CVSS 3.1 | Lo asignó | Corregido en |
|---|---|---|---|---|---|
| CVE-2026-83548 | SonicWall SMA1000 | **5 sep** | 10,0 crítico | CISA | 12.4.3-03526 / 12.5.0-02952 |
| CVE-2026-83549 | SonicWall SMA1000 | **5 sep** | 7,8 alto | CISA | 12.4.3-03526 / 12.5.0-02952 |
| CVE-2026-9586 | Sangoma Switchvox | **5 sep** | 9,8 crítico | NVD | 8.4.0.2 |
| CVE-2026-82329 | JFrog Artifactory | **5 sep** | 9,8 crítico | JFrog | 7.161.20 y su rama |
| CVE-2026-49869 | Kestra OSS | **5 sep** | 10,0 crítico | GitHub | 1.0.45 / 1.3.21 |
| CVE-2026-59822 | LiteLLM | 16 sep | 8,2 alto | NVD | 1.84.0 |
| CVE-2026-48710 | Starlette | 16 sep | 6,5 medio | NVD | 1.0.1 |

## Por qué el plazo del KEV no sigue al puntaje CVSS

Mirá dos filas de esa tabla, una al lado de la otra. **CVE-2026-83549 tiene 7,8 y vence en tres días. CVE-2026-59822 tiene 8,2 y vence en catorce.** El puntaje más alto se lleva el plazo más largo.

No es un error. El CVSS mide qué tan grave sería la explotación; el plazo del KEV es otra cosa: es la lectura de CISA sobre **qué está pasando ahora mismo en internet**. Un puntaje se calcula una vez y se congela. Un plazo se asigna mirando la campaña.

```svg
<svg viewBox="0 0 660 210" role="img" aria-label="El plazo del KEV no sigue al puntaje CVSS. CVE-2026-49869 de Kestra tiene 10,0 y plazo de 3 días. CVE-2026-83548 de SonicWall tiene 10,0 y 3 días. CVE-2026-83549 de SonicWall tiene 7,8 y también 3 días. En cambio CVE-2026-59822 de LiteLLM tiene 8,2 y plazo de 14 días, y CVE-2026-48710 de Starlette tiene 6,5 y 14 días">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Puntaje CVSS contra plazo asignado</text>
  <text x="205" y="52" text-anchor="end" font-size="10" fill="currentColor">Kestra 10,0</text>
  <rect x="212" y="39" width="300" height="16" rx="3" fill="#e23a3a" opacity="0.6"/>
  <text x="520" y="52" font-size="10" font-weight="700" fill="#e23a3a">3 días</text>
  <text x="205" y="78" text-anchor="end" font-size="10" fill="currentColor">SonicWall 10,0</text>
  <rect x="212" y="65" width="300" height="16" rx="3" fill="#e23a3a" opacity="0.6"/>
  <text x="520" y="78" font-size="10" font-weight="700" fill="#e23a3a">3 días</text>
  <text x="205" y="104" text-anchor="end" font-size="10" font-weight="700" fill="#e23a3a">SonicWall 7,8</text>
  <rect x="212" y="91" width="234" height="16" rx="3" fill="#e23a3a" opacity="0.6"/>
  <text x="454" y="104" font-size="10" font-weight="700" fill="#e23a3a">3 días</text>
  <text x="205" y="130" text-anchor="end" font-size="10" font-weight="700" fill="currentColor">LiteLLM 8,2</text>
  <rect x="212" y="117" width="246" height="16" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="466" y="130" font-size="10" font-weight="700" fill="currentColor">14 días</text>
  <text x="205" y="156" text-anchor="end" font-size="10" fill="currentColor">Starlette 6,5</text>
  <rect x="212" y="143" width="195" height="16" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="415" y="156" font-size="10" font-weight="700" fill="currentColor">14 días</text>
  <text x="20" y="190" font-size="11.5" font-weight="600" fill="#e23a3a">7,8 con tres días y 8,2 con catorce: el plazo mide la campaña, no la gravedad</text>
</svg>
```

Para una PyME de LATAM el catálogo no es una obligación legal —eso rige para las agencias federales de Estados Unidos—, pero **el plazo sigue siendo la mejor señal de prioridad gratuita que hay**. Si tenés dos parches y una sola ventana, empezá por el de tres días aunque su número sea más bajo.

## La cadena de SonicWall SMA1000: una entra, la otra ejecuta

Las dos del SMA1000 se entienden juntas, y por eso llegaron juntas.

**CVE-2026-83548** es un SSRF **previo a la autenticación** en la interfaz Work Place, y NVD lo describe como debido a "una ruta de acceso alternativa no intencionada". Es lo que le da 10,0: el vector incluye cambio de alcance, o sea que lo que el atacante alcanza excede el componente que falló.

**CVE-2026-83549** es inyección de comandos del sistema operativo en la consola de administración (AMC) y **requiere ser administrador**. Sola vale 7,8 porque ese requisito es alto. Detrás de la primera, el requisito es justo lo que la primera te ayuda a conseguir.

```svg
<svg viewBox="0 0 680 190" role="img" aria-label="Las dos fallas del SonicWall SMA1000 encadenadas: CVE-2026-83548 es un SSRF sin autenticar en la interfaz Work Place con puntaje 10,0 que da acceso a funciones internas, y CVE-2026-83549 es inyección de comandos en la consola de administración con puntaje 7,8 que requiere ser administrador y termina en ejecución de código">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Por qué las dos del SMA1000 llegaron el mismo día</text>
  <rect x="30" y="48" width="270" height="82" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="30" y="48" width="270" height="82" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="165" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-83548 — 10,0</text>
  <text x="165" y="92" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">SSRF sin autenticar, Work Place</text>
  <text x="165" y="112" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">llega a funciones internas</text>
  <path d="M302 89 L376 89" stroke="currentColor" stroke-width="1.6" opacity="0.7"/>
  <path d="M376 89 L368 85 M376 89 L368 93" stroke="currentColor" stroke-width="1.6" opacity="0.7"/>
  <rect x="380" y="48" width="270" height="82" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="380" y="48" width="270" height="82" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="515" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">CVE-2026-83549 — 7,8</text>
  <text x="515" y="92" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">comandos en la consola AMC</text>
  <text x="515" y="112" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">pide ser administrador</text>
  <text x="340" y="162" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">El requisito de la segunda es lo que otorga la primera</text>
</svg>
```

Un detalle que conviene decir en voz alta: **el vector de CVE-2026-83549 en NVD es `AV:L`, o sea local**, mientras la descripción habla de un atacante remoto autenticado. Los dos puntajes los cargó CISA, no SonicWall. La discrepancia no cambia qué hay que hacer, pero sí desaconseja tomar el 7,8 como medida del riesgo real.

No pude leer el aviso propio de SonicWall, `SNWLID-2026-0016`: su portal es una aplicación de una sola página y desde este entorno devuelve el esqueleto vacío. Las versiones de arriba salen de los rangos que publica NVD, no del fabricante.

## Sangoma Switchvox: la central telefónica también es un servidor

**CVE-2026-9586** es inyección SQL **sin autenticar** en Switchvox SMB Edition 8.3. Según NVD, el endpoint `/pa` procesa contenido XML que empieza con `<PolycomIPPhone>` y concatena el valor `PhoneIP` —controlado por quien hace el pedido— directamente en consultas a PostgreSQL, sin sanear. Una sola petición armada alcanza para operar contra la base y llegar a ejecución de código.

Corrige la versión **8.4.0.2**.

Vale la pena detenerse en la categoría: una central telefónica IP se compra como si fuera un teléfono, se instala una vez y **no entra en el inventario de servidores de nadie**. Pero es un servidor Linux con una base PostgreSQL, publicado para que los teléfonos y los anexos remotos lleguen. Es el mismo punto ciego de [Zimbra y TrueConf](/noticia/zimbra-trueconf-software-autoalojado-parche-propio): el software que se instaló solo una vez y quedó ahí.

## Kestra, Starlette, SonicWall y LiteLLM: el mismo error de interpretación

Leídas juntas, cuatro comparten mecanismo: **el sistema leyó la misma petición de dos maneras distintas, y la diferencia entre las dos lecturas es el agujero.**

- **Kestra (CVE-2026-49869):** el filtro de autenticación usaba `request.getPath().endsWith("/configs")` para dejar pasar sin credenciales el endpoint público de configuración. Al ser una comparación por sufijo y no exacta, cualquier ruta terminada en `/configs` entraba gratis.
- **Starlette (CVE-2026-48710):** el encabezado `Host` no se validaba antes de reconstruir `request.url`. El enrutamiento usa la ruta cruda y la autenticación mira la URL reconstruida: dos verdades para el mismo pedido.
- **SonicWall (CVE-2026-83548):** NVD lo clasifica como CWE-441, *proxy intermediario no intencionado*, además de SSRF. Una ruta de acceso que nadie diseñó.
- **LiteLLM (CVE-2026-59822):** un encabezado de autorización fabricado disparaba un camino alternativo de OAuth2 que terminaba aceptando la sesión.

Es exactamente la familia del cruce de caché de Artifactory que vimos en [la nota sobre los agentes de OpenAI](/noticia/kernel-linux-cve-2026-53362-agentes-openai), y de la confusión de rutas que abrió el camino en [el caso de Aurora](/noticia/aurora-ransomware-cursor-ia-planear-intrusiones). Nada de esto es memoria corrupta ni desbordes: son **desacuerdos de interpretación** entre dos partes del mismo programa. La lección defensiva es que la autenticación tiene que decidir sobre exactamente el mismo objeto que usa el enrutamiento, no sobre una reconstrucción.

## ¿Cómo sé si tengo afectado el SMA1000, Switchvox o Artifactory?

Ninguna se comprueba desde afuera: se comprueba en el inventario.

1. **SonicWall SMA1000** — la versión está en la consola de administración. Si estás por debajo de 12.4.3-03526 en la rama 12.4, o de 12.5.0-02952 en la 12.5, te toca. Alcanza a SMA 6210, 7210 y al virtual 8200v.
2. **Sangoma Switchvox** — versión 8.3 afectada, corrige 8.4.0.2.
3. **JFrog Artifactory** — si lo autohospedás. La corrección para la rama actual es 7.161.20; hay parche para cada rama soportada.
4. **Kestra, LiteLLM, Starlette** — son componentes de desarrollo. Aparecen en el archivo de dependencias, no en la lista de servidores. Starlette es la base de FastAPI, así que puede estar sin que nadie lo haya instalado a propósito.

## ¿Quién puede ignorar el aviso del 2 de septiembre?

- **Quien no tiene ninguno de los siete productos.** No es un fallo de protocolo ni de sistema operativo: son siete productos concretos.
- **Quien ya está por encima de las versiones de la tabla.**
- **Quien usa Switchvox en la nube de Sangoma** y no una central propia: la falla es del aparato que se instala.

Que no te toquen estas siete no dice nada sobre el resto del equipo. En el mismo borde de red están [las diez fallas del proceso VPN de WatchGuard](/noticia/watchguard-firebox-diez-fallas-iked-vpn) y [las seis de Citrix NetScaler](/noticia/citrix-netscaler-sql-server-seis-fallas-explotadas), de las últimas dos semanas.

## Qué hacer antes del 5 de septiembre, en orden

1. **Si tenés SMA1000, actualizá hoy.** Es acceso remoto publicado a internet, con una falla de 10,0 previa a autenticación y una segunda que la continúa. Es el caso más claro de los siete.
2. **Switchvox a 8.4.0.2.** Y de paso mirá si la central necesita estar publicada, o si le alcanza con la VPN.
3. **Artifactory a la corrección de tu rama**, si lo autohospedás. Es la segunda vez en una semana que Artifactory entra al catálogo.
4. **Kestra a 1.0.45 o 1.3.21**, si corrés orquestación de flujos.
5. **Actualizá `starlette` y `litellm` en los proyectos.** Tienen el plazo largo, pero son un cambio de una línea en el archivo de dependencias.
6. **Anotá quién es dueño de cada aparato de borde.** El problema de fondo de Switchvox y del SMA1000 es el mismo: cuando nadie tiene asignado el equipo, nadie mira si salió parche.

## Por qué la fecha del CVE no sirve para priorizar parches

Las siete entraron al catálogo el mismo día, pero se publicaron en momentos muy distintos: Starlette el 26 de mayo, Kestra el 26 de junio, LiteLLM el 8 de julio, Switchvox el 17 de julio, Artifactory el 28 de agosto y las dos de SonicWall el 1 de septiembre.

Entre la publicación de la falla de Starlette y su entrada al catálogo pasaron **más de tres meses**. Quien prioriza parches por fecha de publicación del CVE ya había archivado esa como vieja. La explotación no llegó cuando se publicó: llegó cuando alguien encontró para qué servía.

## Preguntas frecuentes sobre las siete fallas del 2 de septiembre

### ¿El catálogo KEV me obliga a algo si tengo una PyME en Argentina?
No. Es vinculante para las agencias civiles federales de Estados Unidos bajo la directiva BOD 26-04. Para el resto es información: la lista de lo que se está explotando de verdad, con fecha y con plazo. Sirve como orden de prioridad, no como obligación.

### ¿Por qué una falla de 7,8 tiene menos plazo que una de 8,2?
Porque miden cosas distintas. El CVSS estima la gravedad si se explota; el plazo refleja lo que CISA observa en explotación activa. Una falla de puntaje medio que forma parte de una cadena en uso es más urgente que una alta que nadie está usando.

### ¿Cómo sé si mi proyecto usa Starlette sin haberlo instalado?
Es la base de FastAPI, así que entra como dependencia indirecta. Revisá el archivo de bloqueo de dependencias del proyecto, no la lista de paquetes que instalaste a mano.

### ¿Alcanza con poner el SMA1000 detrás de otro firewall?
No como solución. El aparato existe para estar publicado y atender conexiones de afuera; ponerle algo delante que igual deje pasar el tráfico de acceso remoto no cambia la exposición. Se puede limitar desde qué direcciones se acepta, que reduce la superficie, pero la corrección es la actualización.

### ¿Estas siete fallas están relacionadas entre sí?
No. Entraron el mismo día al catálogo, que es un trámite administrativo, y las publicaron investigadores y fabricantes distintos entre mayo y septiembre. La coincidencia de fecha no implica una campaña común.

---

**Fuentes primarias.** Catálogo KEV de CISA, versión 2026.09.02 · Registros de NVD para [CVE-2026-83548](https://nvd.nist.gov/vuln/detail/CVE-2026-83548), [CVE-2026-83549](https://nvd.nist.gov/vuln/detail/CVE-2026-83549), [CVE-2026-9586](https://nvd.nist.gov/vuln/detail/CVE-2026-9586), [CVE-2026-82329](https://nvd.nist.gov/vuln/detail/CVE-2026-82329), [CVE-2026-49869](https://nvd.nist.gov/vuln/detail/CVE-2026-49869), [CVE-2026-59822](https://nvd.nist.gov/vuln/detail/CVE-2026-59822) y [CVE-2026-48710](https://nvd.nist.gov/vuln/detail/CVE-2026-48710) · [Avisos de seguridad de JFrog](https://docs.jfrog.com/releases/docs/jfrog-security-advisories).

Consultadas el 3 de septiembre de 2026. El aviso propio de SonicWall no se pudo leer desde este entorno y queda citado sin verificar de primera mano. Para el vocabulario, [el glosario](/guia/glosario-ciberseguridad-pymes); para decidir qué exponer y qué no, [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn) y [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).
