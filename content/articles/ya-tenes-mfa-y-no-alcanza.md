---
title: "Ya tenés MFA y no alcanza: los huecos reales"
subtitle: Once puntos de acceso que casi ninguna PyME cubre con segundo factor, y el chequeo de dos minutos para confirmar cuáles tenés abiertos.
excerpt: El 97% de las organizaciones a las que les robaron credenciales ya tenía MFA activo (Sophos 2026). Acá están los once accesos que quedaron afuera.
type: guide
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-07-26
products: [microsoft-entra-id, cisco-duo, okta-workforce-identity]
meta_title: "Ya tenés MFA y no alcanza: los huecos reales"
meta_description: "El 97% de las empresas con credenciales robadas tenía MFA. El problema no es el MFA: son los once accesos que quedaron sin cubrir. Inventario y test."
---

Casi todas las empresas a las que les robaron credenciales el año pasado tenían el doble factor activado. El número es **97%** (Sophos, *State of Ransomware 2026*). El problema no es que el proyecto de MFA salga mal: sale bien y se cierra. Se activa el MFA, sale el mail explicando cómo instalar el Authenticator, se aguantan dos semanas de quejas y el punto queda tildado. Y sigue tildado hasta que alguien entra por la consola del firewall, que nunca estuvo en el proyecto.

El problema no es el MFA. Es el inventario.

## Acto 1. Qué mide ese 97% y qué no

Ese número no dice que el segundo factor haya fallado. Dice algo más incómodo: entre las víctimas cuya causa raíz fueron credenciales comprometidas, el 97% tenía MFA activo **en alguna forma**. Presencia, no cobertura. Todo el resto de esta guía sale de esa diferencia.

Sophos atribuye la brecha a huecos de cobertura: MFA presente en las cuentas SaaS, ausente en VPNs, consolas de firewall y aplicaciones legacy. El dato viene de un vendor que vende productos de seguridad, así que corresponde el descuento de rigor. Pero el *Data Breach Investigations Report 2026* de Verizon llega al mismo lugar por otro camino: en los incidentes cloud con terceros, la autenticación insegura —MFA ausente, rotación incorrecta de credenciales— y la falta de mínimo privilegio explican una porción sustancial de los casos. Dos metodologías, misma conclusión.

Segundo matiz, y este importa si leés desde una PyME. La muestra de Sophos 2026 son 2.158 responsables de IT y seguridad de 17 países, en organizaciones de **100 a 5.000 empleados**, encuestados entre enero y marzo de 2026. Ninguno de los grandes informes anuales muestrea empresas hispanohablantes de 1 a 50 personas. Para la región existe el *ESET Security Report 2026* (1.563 profesionales, 962 organizaciones, 10 países): la adopción de MFA en Latinoamérica está en **57%**, y entre el personal no técnico baja al **52,2%**. Acá el promedio no es "MFA con huecos" sino "MFA en la mitad de la gente, y encima con huecos".

*Sí, pero si el MFA no evitó nada, ¿para qué lo puse?* Lo evitó donde estaba puesto. El **79%** de los ataques de ransomware de 2025 empezó por vía de identidad (Sophos 2026), y ese 79 es la suma de cuatro causas raíz del informe: email malicioso 26%, phishing 24%, credenciales comprometidas 23% y fuerza bruta 6%. Sin MFA esos números empeoran. La conclusión operativa no es desconfiar del control: es terminar de instalarlo.

## Acto 2. El MFA se compra por aplicación, el atacante entra por lo que sobró

Nadie compra MFA. Se compran activaciones sueltas, empujadas por cosas distintas: Microsoft manda un aviso y lo activás en Microsoft 365, el banco te impone el token, el password manager lo trae de fábrica. Al final del año tenés MFA en cuatro sistemas, todos elegidos por quién te avisó primero, ninguno por dónde entra un atacante.

Mientras tanto queda afuera el firewall que configuró un proveedor externo hace años, con la consola escuchando en el puerto 443 de la IP pública y la contraseña de `admin` en una planilla compartida. Escribo esa combinación como hipótesis a verificar, no como diagnóstico: la fila 2 del inventario de más abajo existe para que la confirmes o la descartes en dos minutos. Eso nunca entra en un proyecto de MFA porque no es "una aplicación": es infraestructura, la mira otra persona, y en muchas PyMEs esa persona ya no trabaja ahí.

Los puntos de entrada que releva Sophos 2026 para ataques que arrancan por vulnerabilidad, credenciales o fuerza bruta:

| Punto de entrada | % de los casos |
|---|---|
| Aplicaciones o sistemas expuestos | 38% |
| Dispositivos de usuario | 30% |
| Firewalls | 21% |
| VPNs | 8% |
| Dispositivos IoT | 3% |

Firewall más VPN suman **29%**: casi tres de cada diez entradas por los dos sistemas que menos aparecen en la conversación de MFA. Y si sumás "aplicaciones o sistemas expuestos" —en una PyME típica, el ERP publicado, el escritorio remoto y el panel del hosting— estás arriba del 65% del problema concentrado donde el proyecto de MFA nunca llegó.

## Acto 3. Los once huecos

Andá con esta lista abierta y hacé el chequeo de cada fila. Ninguno lleva más de dos minutos ni requiere permisos que no tengas.

| # | Punto de acceso | Chequeo de 2 minutos |
|---|---|---|
| 1 | **VPN corporativa** | Entrá desde fuera de la oficina con usuario y contraseña de un empleado común. Si entrás sin segundo paso, está abierta. |
| 2 | **Consola del firewall** | Abrí la IP pública del firewall en el navegador desde fuera de la oficina. Si aparece un login, ya perdiste la mitad: no debería ser alcanzable desde internet. |
| 3 | **RDP interno** | Revisá quién tiene el rol de Escritorio Remoto. Si entra con usuario de dominio y contraseña sola, es el camino más corto al cifrado total. |
| 4 | **IMAP/SMTP y autenticación básica** | En Entra, filtrá los registros de inicio de sesión por "Client app: otros clientes". Cada línea ahí es un login que esquivó tu MFA. |
| 5 | **La cuenta del contador externo** | ¿Tiene usuario en tu tenant o comparte la casilla de administración? Preguntale con qué autentica. Si la respuesta es que entra con una clave que alguien le pasó, ahí está el hallazgo. |
| 6 | **Panel del hosting** | Buscá "seguridad" o "verificación en dos pasos" en el panel de tu proveedor. En la mayoría está disponible y desactivado por defecto. |
| 7 | **Registrador del dominio** | El hueco más caro: quien controla el DNS controla tu correo, tu SPF y tu certificado. Verificá el segundo factor y el bloqueo de transferencia. |
| 8 | **Facturación electrónica** | El portal de AFIP/ARCA, SAT, SII o DIAN, y el software intermediario. Chequeá si el certificado digital o el token está en una PC compartida sin PIN. |
| 9 | **Cuentas de servicio** | Listá los usuarios que corren integraciones, backups y scripts. Ninguno debería tener MFA humano; todos, credencial larga, rotada y restringida por IP. Si alguno usa la contraseña de 2021, ahí está el agujero. |
| 10 | **Admin del ERP** | Contá los perfiles de administrador y cruzá esa lista contra la nómina vigente. Si el número te sorprende para arriba, ese es el hallazgo: los admin se acumulan porque dar permisos resuelve el problema del día y sacarlos no le resuelve nada a nadie. |
| 11 | **Cuenta break-glass** | Tiene que existir, estar excluida de las políticas y estar auditada. Si nadie sabe cuál es, o si es la cuenta personal del dueño, no es break-glass: es una excepción permanente. |

Sobre la 9 y la 11, una aclaración porque genera confusión: las cuentas de servicio y la de emergencia **no llevan MFA**, y eso es correcto. Ponerle segundo factor humano a un proceso automatizado rompe la integración el primer día. Llevan otra cosa —secreto largo, rotación con fecha, origen restringido, alerta cuando se usan—. Si alguien creó una exclusión de política para "que el backup no falle" y sigue viva dos años después, tenés una cuenta sin MFA con permisos de administrador y nadie la mira.

La 4 merece un párrafo aparte, y tiene fecha. La autenticación básica —IMAP, POP3, SMTP AUTH, EWS, ActiveSync viejo— no es un hueco por descuido: lo es por diseño del protocolo. La credencial viaja sola, no hay dónde meter un segundo paso, y el atacante ni se entera de que tenías MFA. Microsoft apagó Basic auth en el resto de los protocolos de Exchange Online en octubre de 2022, pero dejó vivo el envío de clientes por SMTP AUTH —la multifunción que escanea a mail, el ERP que manda facturas— y la fecha de retiro se corrió varias veces, así que la única respuesta que vale para tu organización es la del centro de mensajes de tu propio tenant. Traducido: no asumas que Microsoft ya te lo apagó ni que la fecha que leíste el año pasado sigue en pie. Andá a mirar si SMTP AUTH sigue habilitado, en el tenant y por buzón. Y si tu dominio no tiene [SPF, DKIM y DMARC bien configurados](/guia/configurar-spf-dkim-dmarc-paso-a-paso), esa misma vía sirve para mandar correo en tu nombre.

## Acto 4. Los tres modos de saltar un MFA que sí está puesto

Hasta acá, ausencia. Ahora los casos en que el MFA está activo y el atacante entra igual. Son tres, no diez, y cada uno tiene contramedida concreta.

### Proxy AiTM (*adversary in the middle*)

El link del phishing no te lleva a una copia del login de Microsoft: te lleva a un proxy inverso que reenvía tu tráfico al login real. Ves la página verdadera, escribís tu contraseña verdadera, aprobás el push verdadero, entrás. El proxy se queda con la cookie de sesión y la usa desde su máquina. Tu MFA funcionó perfecto y no sirvió de nada.

No es un caso de borde. Tycoon 2FA, el kit de *phishing como servicio* que industrializó la técnica, llegó a concentrar una porción mayoritaria de los intentos de phishing que reportan las empresas de inteligencia de amenazas, y cuando le cayó encima una acción de las fuerzas del orden los operadores se mudaron a kits equivalentes: Mamba 2FA, EvilProxy, Sneaky 2FA. El dato importante no es cuál está de moda este trimestre —eso cambia y no lo voy a fijar en un número— sino que la técnica se alquila como servicio. Contra este ataque, TOTP y push son inútiles por diseño: el usuario puede entregar cualquier código a cualquier página.

**Contramedida exacta:** credenciales ligadas al dominio. FIDO2, passkeys sincronizadas o una llave física. El navegador firma contra el origen real; si el origen es `login.microsft-secure.com`, la llave no responde. No hay entrenamiento de usuario que iguale eso. Mi criterio para comprarlas no es esperar a un incidente sino cubrir primero las cuentas que mueven plata; modelos, protocolos y precios están en el [análisis de las YubiKey 5 Series](/resena/analisis-yubikey-5-series-costo-total-argentina).

### Robo de token de sesión

Variante sin phishing. Un infostealer en el equipo del usuario —RedLine, Lumma, Vidar— se lleva las cookies del navegador. El atacante las importa y ya está adentro, sin contraseña y sin MFA: la sesión ya estaba autenticada.

**Contramedida exacta:** vida corta de sesión en las aplicaciones administrativas, revocación de tokens ante señal de riesgo, y binding de la sesión al dispositivo. En Entra ID se llama *token protection* y *continuous access evaluation*; en Okta, *device binding*. Todo eso vive en la capa de acceso condicional, no en la de MFA.

### Fatiga de push (*MFA bombing*)

El atacante ya tiene la contraseña y dispara aprobaciones hasta que la persona toca "sí" para que el teléfono deje de vibrar, normalmente a las tres de la mañana. Funcionó contra Uber en 2022 y sigue funcionando donde el push pide una decisión binaria sin contexto.

**Contramedida exacta:** *number matching* obligatorio —el usuario tipea el número que ve en pantalla— más contexto de aplicación y ubicación en la notificación, más límite de intentos fallidos. Acá conviene verificar antes de gastar una tarde: Microsoft lo impuso para todo el push de Authenticator el 8 de mayo de 2023 y retiró los controles que permitían excluir usuarios, así que en Entra ID ya no se puede apagar; [Cisco Duo](/producto/cisco-duo) lo llama Verified Duo Push, y su documentación aclara que el comportamiento por defecto depende de cuándo se creó el tenant: entrá a la consola y confirmá el tuyo en vez de asumirlo. El hueco sobrevive en lo que queda fuera de esos defaults: tenants de Duo viejos, MFA de terceros, integraciones RADIUS y extensiones NPS on-premise. Ese es el inventario a revisar.

*Sí, pero mis usuarios van a odiar tipear números.* Van a odiar más el fin de semana de restore. Y el número aparece solo en logins nuevos, no cada vez que abren Outlook.

## Acto 5. Qué cierra cada hueco, ordenado por lo que cuesta

Acá está la línea divisoria que casi ningún material comercial dibuja. Por qué no la dibujan no lo sé y no voy a suponerlo; lo que sí sé es que las tres cosas se venden en el mismo paquete y no resuelven el mismo problema.

**Nivel 0 — lo que ya pagaste.** Apagar autenticación básica, cerrar la consola del firewall a internet, poner el segundo factor en el registrador de dominio y en el hosting, revisar quién es admin del ERP. Costo: cero pesos y una tarde. Si tenés Microsoft 365 Business Premium (USD 22 por usuario/mes con compromiso anual, tarifa publicada por Microsoft y consultada en julio de 2026), [Entra ID](/producto/microsoft-entra-id) P1 ya viene incluido y no lo estás usando entero.

**Nivel 1 — MFA a secas donde falta.** Un factor adicional sistema por sistema. Sirve contra credenciales filtradas y fuerza bruta; no contra AiTM. [Duo](/producto/cisco-duo) Essentials arranca en USD 3 por usuario/mes y su valor real acá es que se integra por RADIUS con la VPN y el firewall, justo donde el MFA nativo del IdP no llega. El plan gratuito cubre hasta 10 usuarios: para un taller de cinco personas resuelve casi todo.

**Nivel 2 — acceso condicional.** Es otra categoría de producto, aunque se venda en el mismo paquete. Acá dejás de preguntar "¿sos vos?" y empezás con "¿sos vos, desde un equipo administrado, desde un país donde operamos, con una sesión que no huele raro?". Entra ID P1 figura en USD 7 por usuario/mes standalone y P2 en USD 10 en la tarifa que publica Microsoft, consultada en julio de 2026; P2 suma Identity Protection y acceso privilegiado *just in time*. Duo publica USD 6 en Advantage y USD 9 en Premier, con un detalle que conviene mirar antes de pagar de más: fijate en qué escalón entra la autenticación basada en riesgo, porque no siempre está donde uno la busca. [Okta Workforce Identity](/producto/okta-workforce-identity) publica USD 3 por MFA y USD 6 por MFA adaptativo; preguntá además por el mínimo anual de contrato, que en una empresa de diez personas puede cambiar todo el cálculo por usuario. Las tres son tarifas de lista y cambian sin aviso: abrí la página del fabricante antes de llevar el número a una reunión. Si sos 100% Microsoft, pagar Okta arriba de una licencia que ya incluye P1 me parece un error de compra, aunque el producto sea bueno.

**Nivel 3 — resistente a phishing.** Hardware. En la [tienda de Yubico](https://www.yubico.com/store/), consultada en julio de 2026, una Security Key NFC cuesta USD 29 y una [YubiKey 5 NFC](/producto/yubikey-5-series) USD 58; siempre dos por persona, porque la primera se pierde. Son precios de lista en Estados Unidos, sin impuestos ni envío: importadas a la región hay que sumarles derechos, IVA y flete, así que hacé la cuenta con el régimen de tu país antes de presupuestar. No hace falta comprarle a los 40 empleados: empezá por quienes pueden mover plata o cambiar accesos. Ocho personas con dos llaves son 16 unidades, entre USD 464 y USD 928 por única vez antes de impuestos. Comparalo con los USD 3.046.598.558 que el FBI contabilizó en fraude BEC durante 2025 sobre 24.768 denuncias: USD 123.000 promedio por caso.

La comparación completa está en el [directorio de MFA y autenticación](/productos/mfa-y-autenticacion), y si venís de cero arrancá por el [plan de despliegue de MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).

### El caso Microsoft, leído bien

Microsoft exige MFA en el portal de Azure, el centro de administración de Entra y el de Intune desde el 15 de octubre de 2024; en febrero de 2025 sumó el centro de administración de Microsoft 365, y el 1 de octubre de 2025 arrancó la fase dos: Azure CLI, PowerShell, app móvil, infraestructura como código y APIs REST del plano de control, con las operaciones de solo lectura exceptuadas.

Ahora la lectura correcta, la parte que ningún titular explica: **eso cubre las herramientas de administración de Azure, no a tus usuarios**. No cubre Outlook Web, ni el buzón del contador, ni tu VPN, ni el ERP. La obligatoriedad de Microsoft protege la infraestructura de Microsoft. El resto sigue siendo tuyo.

## Acto 6. El test de 20 minutos

Siete preguntas. Sumá los puntos y leé el resultado al final. Contestá con lo que podés verificar hoy, no con lo que creés recordar.

1. **¿Se entra a la VPN desde una red externa con usuario y contraseña solos?** No → 3 puntos. Sí, o hay RDP publicado → 0.
2. **¿La consola del firewall responde desde internet?** No responde → 3. Responde con segundo factor → 1. Responde con usuario y contraseña → 0.
3. **¿Está apagada la autenticación básica (IMAP/POP3/SMTP AUTH) en tu tenant?** Apagada y verificada en los registros → 3. Apagada con excepción documentada → 2. No sé → 0.
4. **¿Todos tus flujos de push exigen tipear un número, incluidos los que pasan por RADIUS, NPS o MFA de terceros?** Todos → 2. Solo la plataforma principal → 1. No sé → 0.
5. **¿Hay cuenta break-glass identificada, con contraseña fuera del sistema y alerta cuando se usa?** Las tres cosas → 3. Existe pero nadie la audita → 1. No existe → 0.
6. **¿Cuántas personas son admin del ERP y del panel del hosting, y las revisaste este trimestre?** Revisadas y son las que deben ser → 3. Sé el número pero no lo revisé → 1. No sé el número → 0.
7. **¿Las cuentas con acceso a pagos y al dominio usan credencial resistente a phishing (FIDO2 o passkey)?** Todas → 3. Algunas → 1. Ninguna → 0.

**18 a 20:** estás fuera del 97%. Pasá al acceso condicional y a las sesiones.
**11 a 17:** el MFA cubre a las personas y no a la infraestructura. Es el escenario que este inventario está armado para detectar, y casi todo se arregla en el Nivel 0.
**0 a 10:** tenés MFA en el correo y nada más. Empezá por las preguntas 1, 2 y 3: son las que un atacante prueba primero.

Dos límites, antes de que uses esto para tomar decisiones.

El primero es de las fuentes. Todo esto se apoya en documentación de producto, precios de lista públicos y tres informes anuales con metodología declarada: Sophos, el DBIR de Verizon y el ESET Security Report. Ninguno muestrea empresas de menos de 100 personas, el tamaño de buena parte de quienes leen esto: la extrapolación hacia abajo es una opinión mía y la asumo como tal. Hacia arriba pasa lo contrario: con varias unidades de negocio y tenants con confianzas cruzadas, un inventario de once filas se queda corto y no hay datos públicos confiables, así que ahí no me meto.

El segundo es del método. La lista de once cubre lo que administrás vos. Hay un hueco número doce que no está porque no lo podés cerrar: el MFA de tu proveedor de software contable, el del estudio que te liquida sueldos, el del que te hospeda el sitio. El DBIR 2026 cuenta un tercero involucrado en el **48%** de las brechas —venía del 30%— y encontró que apenas el **23%** remedió del todo el MFA faltante o mal configurado en sus cuentas cloud. No lo configurás vos. Preguntarlo por escrito, con nombre y fecha, sí — y guardar la respuesta.

Si el test te dejó pensando en el acceso remoto, la discusión sigue en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn). Si te preocupa la higiene de credenciales antes que el segundo factor, el [análisis de 1Password Business para equipos chicos](/resena/resena-1password-business-12-meses) cubre esa capa. Y la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) tiene el orden de implementación completo, incluido el [backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

## Preguntas frecuentes

### ¿Qué pasa si no activo el MFA obligatorio de Microsoft 365?

Te quedás afuera de los portales y herramientas de administración: portal de Azure, Entra e Intune desde el 15 de octubre de 2024; centro de administración de Microsoft 365 desde febrero de 2025; Azure CLI, PowerShell e infraestructura como código desde el 1 de octubre de 2025. No hay multa ni suspensión del servicio: no vas a poder administrar, nada más. Y el límite importa más que el mandato: no obliga a nada en el inicio de sesión de tus usuarios finales, ni en tu VPN, ni en tus aplicaciones propias.

### Entraron a una cuenta aunque teníamos doble factor, ¿por dónde pudo ser?

Casi siempre por una de cuatro vías: un sistema que quedó sin MFA (VPN, firewall, RDP, IMAP), un proxy AiTM que capturó la cookie mientras el usuario aprobaba el push, un infostealer que robó cookies, o fatiga de push. Para saber cuál fue, buscá la sesión sospechosa en los registros: si hay un evento de MFA exitoso desde una IP rara, fue AiTM o token robado; si no hay ningún evento de MFA, ese sistema nunca lo tuvo.

### ¿El MFA por SMS sirve para algo o hay que sacarlo?

Sirve más que nada y menos de lo que creés. Detiene credenciales filtradas y fuerza bruta, el grueso del ruido automatizado. No detiene AiTM, ni *SIM swapping*, ni el robo de sesión. Mi criterio: aceptable como respaldo para un usuario sin smartphone corporativo, inaceptable para cualquier cuenta que pueda mover dinero o cambiar accesos. Migrá a la app autenticadora y recién después apagalo como método permitido.

### ¿Qué es el MFA fatigue y cómo lo corto hoy mismo?

Es el atacante disparando aprobaciones repetidas hasta que la persona acepta una para poder dormir. Se corta con *number matching*, contexto en la notificación y límite de intentos fallidos. En Microsoft Authenticator no tenés que hacer nada: está impuesto desde mayo de 2023. En Duo el comportamiento por defecto depende de la antigüedad del tenant: entrá a la consola y confirmá que Verified Push esté exigido, en vez de darlo por hecho. Lo que sí conviene revisar es lo que autentica por fuera: RADIUS, NPS y cualquier MFA de terceros.

### ¿Cómo configuro acceso condicional en Entra ID sin romper la operación?

En modo "solo informe" primero. Creás la política, la dejás en *report-only* entre siete y catorce días, y revisás quién habría sido bloqueado. Ahí aparecen las sorpresas: la multifunción que manda correo, el script de facturación, el gerente que viaja. Recién con el reporte limpio la pasás a activa. Antes de tocar nada, dejá creada y excluida la cuenta break-glass: una política mal armada te bloquea fuera del tenant y esa recuperación con soporte lleva días.

### ¿Las cuentas de servicio también necesitan MFA?

No, y forzarlo rompe integraciones. Necesitan otra cosa: secreto largo al azar, rotación con fecha en el calendario, restricción por IP o identidad administrada cuando la plataforma la soporte, permisos mínimos, y alerta cuando la cuenta se usa fuera de su horario. El error frecuente no es dejarlas sin MFA: es dejarlas sin nada de eso y con permisos de administrador global porque así funcionó el día de la instalación.
