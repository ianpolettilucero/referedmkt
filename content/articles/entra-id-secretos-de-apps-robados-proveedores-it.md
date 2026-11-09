---
title: "Microsoft Entra ID: entran a Microsoft 365 como las apps del proveedor de IT"
subtitle: Con secretos de aplicaciones robados, un atacante inicia sesión como las apps de proveedores de servicios gestionados y llega a los tenants de sus clientes. Todyl contó 27 organizaciones alcanzadas y Microsoft vio el primer acceso el 28 de octubre. No hay ninguna falla de Microsoft de por medio.
excerpt: Un atacante entra a Microsoft 365 como las apps de proveedores de IT, con secretos robados. Qué contaron Microsoft y Todyl y qué revisar en tu tenant.
type: news
section: mundo
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-11-09
updated: 2026-11-09
products: []
meta_title: "Entra ID: entran como las apps del proveedor"
meta_description: "Un atacante entra a Microsoft 365 como las apps de proveedores de IT con secretos robados. Qué contaron Microsoft y Todyl y qué revisar en tu tenant hoy."
---

Desde el 28 de octubre, un atacante inicia sesión en **Microsoft Entra ID** como si fuera una aplicación: con el secreto de una app registrada, sin usuario y sin verificación en dos pasos. Varias de esas apps son de **proveedores de servicios gestionados** (MSP), las empresas que administran el Microsoft 365 de sus clientes. Con ellas, el atacante llegó a los tenants de esos clientes, descargó archivos y leyó correos.

La empresa de seguridad Todyl lo [documentó el 3 de noviembre](https://www.todyl.com/blog/stolen-app-credentials-real-access-an-actor-is-signing-into-entra-id-and-azure-devops-as-your-workloads) y lo resume así: *"one operator has been signing in to MSPs' Microsoft 365 and Azure environments as their own applications, using stolen credentials that are still valid."* Microsoft publicó [su propio análisis](https://www.microsoft.com/en-us/security/blog/2026/11/05/threat-actors-use-agentic-ai-tooling-to-find-and-abuse-exposed-secrets/) el 5 de noviembre, con la misma infraestructura. Todyl lo dice explícitamente y Microsoft lo describe igual: **no hay una vulnerabilidad de Microsoft**. Todo se hace con credenciales válidas.

Para una PyME que tiene su Microsoft 365 en manos de un proveedor, la pregunta es directa: qué apps de terceros tienen permisos en tu tenant, y si alguna de ellas se usó en estos días.

## Qué contaron Todyl y Microsoft sobre la campaña en Entra ID

Las cifras de Todyl son un piso, porque solo cubren a las organizaciones que le mandan registros:

| Dato | Según Todyl |
|---|---|
| Registros de aplicaciones atacados | Unos 45, en unas 18 organizaciones |
| Que lograron autenticarse | Más de 33 |
| Organizaciones alcanzadas, contando clientes de los MSP | 27 |
| Tenants donde hizo búsquedas | Unos 25 |
| Archivos descargados | 81 |
| Elementos de buzón de correo accedidos | Unos 2.279 |
| Intentos de inicio de sesión desde la IP del operador | 412 en siete días |

Todyl describe a **un solo operador** que trabaja desde un servidor alojado en Polonia, con un acopio de secretos robados que ya tenía antes del 1 de noviembre. Lo que hace parecer que el ataque se propaga solo es la velocidad: *"Scripted clients made 95% of sign-ins, with bursts of up to 24 sign-ins in 10 seconds."* En un caso, el atacante bajó el archivo de un conector y entró como la app de ese conector unos 2,5 minutos después.

Microsoft agrega el origen. Desde agosto, ve a atacantes usar un sistema de agentes de IA de ataque, ARTEX, para buscar en repositorios públicos de GitHub *"exposed secrets, including credentials, application credentials, and AI inference-related API keys"*. Y describe el caso más grave: una credencial de una app multicliente comprometida en el origen, la del proveedor, y usada para entrar a varios tenants de clientes. Todyl, en cambio, dice que cómo consiguió el atacante los primeros secretos *"is still unknown"*.

```svg
<svg viewBox="0 0 680 282" role="img" aria-label="Cómo funciona la campaña con credenciales de aplicaciones de Microsoft Entra ID robadas, según Microsoft y Todyl. Primero, el atacante consigue secretos de aplicaciones expuestos, por ejemplo en repositorios públicos de GitHub. Segundo, inicia sesión como la aplicación del proveedor de IT, sin usuario ni verificación en dos pasos. Tercero, pide en segundos tokens de Azure Resource Manager, Key Vault y Microsoft Graph. Cuarto, lee más secretos del Key Vault y entra con ellos a aplicaciones que nunca se habían usado. Quinto, con las aplicaciones multicliente del proveedor llega a los tenants de sus clientes. Sexto, descarga archivos de SharePoint y OneDrive y accede a correos. No hay ninguna falla de Microsoft: todo se hace con credenciales válidas.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Entra ID: cómo se pasa de un secreto expuesto a los correos de los clientes</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según Microsoft y Todyl. El rojo marca el paso que abre la puerta a los clientes del proveedor.</text>
  <rect x="16" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="118" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Secreto expuesto</text>
  <text x="118" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">por ejemplo, en GitHub</text>
  <rect x="238" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="340" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Sesión como la app</text>
  <text x="340" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">sin usuario ni MFA</text>
  <rect x="460" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Tokens en segundos</text>
  <text x="562" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">Azure, Key Vault, Graph</text>
  <path d="M222 91 H234 M229 86 L234 91 L229 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 91 H456 M451 86 L456 91 L451 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 120 V138 H118 V154 M113 149 L118 154 L123 149" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="16" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="118" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Más secretos</text>
  <text x="118" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">leídos del Key Vault</text>
  <rect x="238" y="156" width="204" height="58" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="340" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Apps multicliente</text>
  <text x="340" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">llegan a los clientes</text>
  <rect x="460" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="562" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Descargas</text>
  <text x="562" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">archivos y correos</text>
  <path d="M222 185 H234 M229 180 L234 185 L229 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 185 H456 M451 180 L456 185 L451 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="20" y="246" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Ninguna falla de Microsoft: todo se hace con credenciales válidas.</text>
  <text x="20" y="268" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: Microsoft Threat Intelligence, 5 de noviembre, y Todyl, 3 y 6 de noviembre de 2026.</text>
</svg>
```

## Por qué una app del proveedor de IT es la puerta a sus clientes

Un MSP administra a sus clientes con aplicaciones registradas en Entra ID: herramientas de monitoreo, de backup, de seguridad o de automatización. Muchas son **multicliente**: una sola app, con un solo secreto, tiene permisos en el tenant de cada cliente. Quien tiene ese secreto entra como la app, y la app no pasa por la verificación en dos pasos, que es para personas.

Todyl lo dice sin vueltas: *"One compromised MSP app exposes every client it manages."* En un caso, una sola app multicliente recorrió 15 tenants de clientes en ciclos de unos 10 segundos. Entre las credenciales usadas había integraciones de herramientas de seguridad, incluidas las del propio Todyl en dos MSP, aunque la empresa aclara que no hay señales de que se haya comprometido la plataforma de ningún fabricante.

Una vez adentro, el patrón se repite: el atacante pide un token de Azure Resource Manager, después uno de Key Vault, y en segundos los de Graph y Storage. Según Todyl, todo indica que lee los secretos guardados en Key Vault y entra con ellos a apps que nunca se habían usado. Microsoft dice que buscaba en especial credenciales de servicios de IA. Es la misma lógica que ya mostró [la falla de N-able N-central, la herramienta con la que tu proveedor administra tus equipos](/noticia/n-able-n-central-cve-2026-86218-proveedor-it): el acceso del proveedor vale por el de todos sus clientes.

## Entra ID: la campaña, del 28 de octubre al 6 de noviembre

```svg
<svg viewBox="0 0 680 322" role="img" aria-label="Cronología de la campaña con credenciales de aplicaciones de Entra ID, en 2026 y en hora UTC. Agosto: Microsoft ve búsquedas masivas de secretos en GitHub hechas con herramientas de IA. 28 de octubre: primeros inicios de sesión exitosos desde la dirección 199.19.77.84, según Microsoft. 1 de noviembre a las 08:45: Todyl ve el primer acceso con una credencial de aplicación robada. 2 de noviembre a las 10:39: primera reutilización de un token de sesión de Azure DevOps. 3 de noviembre a las 07:57: saltan las detecciones y el atacante cambia de herramientas. A las 18:12 reutiliza tokens de varios usuarios, y el acceso condicional los bloquea. A las 21:31, último intento que vio Todyl, bloqueado. 5 de noviembre: Microsoft publica su análisis. 6 de noviembre: Todyl pide extender la búsqueda en los registros hasta el 28 de octubre.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Entra ID: la campaña, del 28 de octubre al 6 de noviembre</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">2026, hora UTC. El rojo marca desde cuándo hay que buscar en los registros.</text>
  <path d="M118 58 V284" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">ago.</text>
  <circle cx="118" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Microsoft ve búsquedas masivas de secretos en GitHub con IA</text>
  <text x="20" y="96" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">28 oct.</text>
  <circle cx="118" cy="92" r="5" class="ch-dot-hi" fill="#e23a3a"/>
  <text x="134" y="96" class="ch-strong" font-size="10.5" fill="currentColor">Primeros inicios de sesión exitosos desde 199.19.77.84</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">1 nov. 08:45</text>
  <circle cx="118" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Todyl ve el primer acceso con una credencial de app robada</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">2 nov. 10:39</text>
  <circle cx="118" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Primera reutilización de un token de sesión de Azure DevOps</text>
  <text x="20" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">3 nov. 07:57</text>
  <circle cx="118" cy="170" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="174" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Saltan las detecciones y el atacante cambia de herramientas</text>
  <text x="20" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">3 nov. 18:12</text>
  <circle cx="118" cy="196" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Reutiliza tokens de usuarios; el acceso condicional los frena</text>
  <text x="20" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">3 nov. 21:31</text>
  <circle cx="118" cy="222" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="226" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Último intento que vio Todyl, bloqueado</text>
  <text x="20" y="252" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">5 nov.</text>
  <circle cx="118" cy="248" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="252" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Microsoft publica su análisis y las direcciones IP</text>
  <text x="20" y="278" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">6 nov.</text>
  <circle cx="118" cy="274" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"/>
  <text x="134" y="278" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Todyl pide buscar desde el 28 de octubre</text>
  <text x="20" y="312" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: Microsoft Threat Intelligence y Todyl.</text>
</svg>
```

La fecha importa para buscar en los registros. Todyl la vio por primera vez el 1 de noviembre, pero Microsoft registró inicios de sesión exitosos **desde el 28 de octubre**. El 6 de noviembre, Todyl actualizó su guía: *"If you scoped your hunt from November 1, extend it back to October 28."*

## ¿Cómo sé si mi tenant de Microsoft 365 fue alcanzado?

1. **Preguntale a tu proveedor de IT.** Qué apps suyas tienen permisos en tu tenant, si alguna fue afectada y si revisó sus accesos delegados. Microsoft le pide a cada MSP revisar el Partner Center, las relaciones de administración delegada (GDAP) y avisar a los clientes afectados.
2. **Mirá qué apps de terceros tienen permisos.** En el centro de administración de Microsoft Entra, la lista de aplicaciones empresariales muestra cada app con acceso a tu tenant. Una que no reconocés, o de un proveedor que ya no trabaja con vos, es para sacar.
3. **Buscá las direcciones en los registros de inicio de sesión.** Las de entidades de servicio y las no interactivas, desde el 28 de octubre, buscando `199.19.77.84` y `199.19.77.18`, incluidos los intentos fallidos. Microsoft señala dos errores: *"AADSTS7000215 (invalid secret) or AADSTS7000222 (expired secret) indicate someone holds a credential for your app"*. Un fallo seguido de un acceso exitoso es urgente.
4. **Si los registros no llegan tan atrás, usá el de auditoría de Microsoft 365.** Entra ID guarda los inicios de sesión **7 días** en la versión gratuita y **30 días** con P1 o P2, según [la documentación de Microsoft](https://learn.microsoft.com/en-us/entra/identity/monitoring-health/reference-reports-data-retention). Un tenant gratuito ya perdió lo del 28 de octubre al 1 de noviembre. El registro de auditoría de Microsoft 365, en Microsoft Purview, guarda **180 días** y registra, entre otras cosas, las descargas de SharePoint y OneDrive: Microsoft recomienda buscar ahí también la dirección.

## ¿Qué hago si una app de mi tenant aparece en la campaña de Entra ID?

1. **Limpiá la app por completo.** Microsoft pide quitarle todos los secretos, certificados y credenciales federadas, incluidos los que haya agregado el atacante, y deshabilitarla si hace falta. Ojo: los tokens ya emitidos siguen valiendo hasta que vencen.
2. **Rotá todo lo que esa app podía leer.** Cualquier secreto guardado en un Key Vault al que tenía acceso, y cualquier credencial que aparezca en los documentos descargados.
3. **Revocá las sesiones de los usuarios afectados.** Todyl vio al atacante pasar a reutilizar tokens de actualización de usuarios. Cambiar el secreto de la app no corta eso: hay que revocar las sesiones en Entra ID.
4. **Usá el acceso condicional donde tu licencia lo permita.** Todyl cuenta que el acceso condicional bloqueó todas las reutilizaciones de tokens que vio el 3 de noviembre, con políticas que exigen un dispositivo administrado o ubicaciones de confianza. Para bloquear a las apps, Microsoft aclara que las políticas para identidades de carga de trabajo *"require Workload ID Premium licensing and cover only single-tenant apps registered in your tenant"*. Cómo se roba y se reutiliza una sesión está en [la guía sobre el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).
5. **Si tenés desarrolladores, buscá secretos en el código.** Microsoft es tajante: un secreto que estuvo en un repositorio público se trata como comprometido aunque después se haya borrado, porque queda en el historial y en copias. GitHub escanea los repositorios públicos y avisa.

Las apps no pasan por la verificación en dos pasos, y por eso esta campaña la esquiva sin romper nada. Los otros huecos del MFA están en [la guía sobre por qué tener MFA no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), y el robo de tokens sin contraseña en [la nota sobre el phishing EvilTokens a Microsoft 365](/noticia/eviltokens-microsoft-365-phishing-codigo-de-dispositivo). Si tu proveedor te pide información para revisar el incidente, o si un cliente te la pide a vos, sirve [la guía para responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder). Para comparar herramientas de autenticación está [la categoría de MFA y autenticación](/productos/mfa-y-autenticacion).

## ¿Quién puede ignorar la campaña contra Entra ID?

Quien no use Microsoft 365 ni Azure. Y quien sí lo use, pero no tenga apps de terceros con permisos en su tenant ni registros de aplicaciones propios con secretos, tiene poco que revisar más allá de los registros. El resto, sobre todo si un proveedor administra el tenant, tiene que hacer al menos la pregunta del primer punto.

**Fuentes leídas:** el análisis de Todyl del 3 de noviembre con su actualización del 6, con la cronología, las cifras y los indicadores; el análisis de Microsoft Threat Intelligence del 5 de noviembre, con las mitigaciones y los criterios de búsqueda; y la documentación de Microsoft sobre la retención de los registros de Entra ID y del registro de auditoría de Microsoft 365. **Sin confirmar:** cómo obtuvo el atacante los primeros secretos, en lo que Microsoft y Todyl no coinciden; quién está detrás; si hay víctimas en América Latina, porque ninguna de las dos fuentes da países; y cuántas organizaciones fueron alcanzadas fuera de lo que ve Todyl.

## Preguntas frecuentes sobre la campaña con credenciales de apps de Entra ID

### ¿Es una vulnerabilidad de Microsoft Entra ID?

No. Todyl aclara que no hay ninguna falla de Microsoft, y Microsoft describe el abuso de credenciales válidas. El atacante usa secretos de aplicaciones válidos, robados o expuestos, para iniciar sesión como esas aplicaciones.

### ¿Por qué el MFA no frena este ataque?

Porque las aplicaciones no usan verificación en dos pasos: se autentican con un secreto o un certificado. Quien tiene ese secreto entra como la app, con los permisos que tenga en el tenant.

### ¿Cómo me afecta si mi Microsoft 365 lo administra un proveedor de IT?

Si el proveedor usa una app multicliente con permisos en tu tenant y su secreto quedó expuesto, el atacante puede entrar a tu tenant como esa app. Todyl vio una sola app recorrer 15 tenants de clientes. Preguntale a tu proveedor qué apps tiene en tu tenant y si fue afectado.

### ¿Desde qué fecha tengo que buscar en los registros?

Desde el 28 de octubre de 2026, cuando Microsoft vio los primeros inicios de sesión exitosos. Si tu tenant tiene la versión gratuita de Entra ID, los registros de inicio de sesión duran 7 días; para lo anterior queda el registro de auditoría de Microsoft 365, que guarda 180 días.

### ¿Qué direcciones IP hay que buscar?

Las que publicaron Microsoft y Todyl son 199.19.77.84, la del operador, y 199.19.77.18. Microsoft aclara que el atacante puede cambiar de dirección, así que también conviene buscar el patrón: una app que pide tokens de Azure, Key Vault y Graph en pocos segundos.
