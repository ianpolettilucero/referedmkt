---
title: "Ya tenés MFA y no alcanza: los huecos reales"
subtitle: Once puntos de acceso que casi ninguna PyME cubre con segundo factor, y el chequeo de dos minutos para confirmar cuáles tenés abiertos.
excerpt: El 97% de las organizaciones a las que les robaron credenciales ya tenía MFA activo (Sophos 2026). Acá están los once accesos que quedaron afuera.
type: guide
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-24
products: [microsoft-entra-id, cisco-duo, okta-workforce-identity]
meta_title: "Ya tenés MFA y no alcanza: los huecos reales"
meta_description: "El 97% de las empresas con credenciales robadas tenía MFA. El problema no es el MFA: son los once accesos que quedaron sin cubrir. Inventario y test."
---

Casi todas las empresas a las que les robaron credenciales el año pasado tenían el doble factor activado. El número es **97%** (Sophos, *State of Ransomware 2026*). El proyecto de MFA no sale mal: sale bien y se cierra. Se activa, sale el mail explicando cómo instalar el Authenticator, se aguantan dos semanas de quejas y el punto queda tildado. Y sigue tildado hasta que alguien entra por la consola del firewall, que nunca estuvo en el proyecto.

El problema no es el MFA. Es el inventario.

## Acto 1. Qué mide ese 97% y qué no

Ese número no dice que el segundo factor haya fallado. Dice algo más incómodo: entre las víctimas cuya causa raíz fueron credenciales comprometidas, el 97% tenía MFA activo **en alguna forma**. Presencia, no cobertura. Todo el resto de esta guía sale de esa diferencia.

Sophos atribuye la brecha a huecos de cobertura: MFA presente en las cuentas SaaS, ausente en VPNs, consolas de firewall y aplicaciones legacy. El dato viene de un fabricante que vende productos de seguridad, así que corresponde el descuento de rigor. Pero el *Data Breach Investigations Report 2026* de Verizon llega al mismo lugar por otro camino: en los incidentes cloud con terceros, la autenticación insegura —MFA ausente, rotación incorrecta de credenciales— y la falta de mínimo privilegio explican una porción sustancial de los casos. Dos metodologías, misma conclusión.

El segundo matiz importa si se lee desde una PyME. La muestra de Sophos 2026 son 2.158 responsables de IT y seguridad de 17 países, en organizaciones de **100 a 5.000 empleados**, encuestados entre enero y marzo de 2026. Ninguno de los grandes informes anuales muestrea empresas hispanohablantes de 1 a 50 personas. Para la región existe el *ESET Security Report 2026* (1.563 profesionales, 962 organizaciones, 10 países): la adopción de MFA en Latinoamérica está en **57%**, y entre el personal no técnico baja al **52,2%**. Acá el promedio no es "MFA con huecos" sino "MFA en la mitad de la gente, y encima con huecos".

Si el MFA no evitó nada, la pregunta razonable es para qué se puso. Lo evitó donde estaba puesto. El **79%** de los ataques de ransomware de 2025 empezó por vía de identidad (Sophos 2026), y ese 79 es la suma de cuatro causas raíz del informe: email malicioso 26%, phishing 24%, credenciales comprometidas 23% y fuerza bruta 6%. Sin MFA esos números empeoran. La conclusión operativa no es desconfiar del control: es terminar de instalarlo.

## Acto 2. El MFA se compra por aplicación, el atacante entra por lo que sobró

Nadie compra MFA. Se compran activaciones sueltas, empujadas por cosas distintas: Microsoft manda un aviso y se activa en Microsoft 365, el banco impone el token, el gestor de contraseñas lo trae de fábrica. Al final del año hay MFA en cuatro sistemas, todos elegidos por quién avisó primero, ninguno por dónde entra un atacante.

Mientras tanto queda afuera el firewall que configuró un proveedor externo hace años, con la consola escuchando en el puerto 443 de la IP pública y la contraseña de `admin` en una planilla compartida. Esa combinación es una hipótesis a verificar, no un diagnóstico: la fila 2 del inventario existe para confirmarla o descartarla en dos minutos. Nunca entra en un proyecto de MFA porque no es "una aplicación": es infraestructura, la mira otra persona, y en muchas PyMEs esa persona ya no trabaja ahí.

Los puntos de entrada que releva Sophos 2026 para ataques que arrancan por vulnerabilidad, credenciales o fuerza bruta:

```svg
<svg viewBox="0 0 660 240" role="img" aria-label="Puntos de entrada según Sophos 2026: aplicaciones o sistemas expuestos 38 por ciento, dispositivos de usuario 30 por ciento, firewalls 21 por ciento, VPNs 8 por ciento y dispositivos IoT 3 por ciento. Firewalls y VPNs, marcados en rojo, suman 29 por ciento y son los que un proyecto de MFA casi nunca cubre">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Por dónde entran, según Sophos 2026</text>

  <text x="192" y="61" text-anchor="end" font-size="10.5" fill="currentColor">Aplicaciones o sistemas expuestos</text>
  <rect x="200" y="46" width="380" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="588" y="61" font-size="10.5" font-weight="700" fill="currentColor">38%</text>

  <text x="192" y="93" text-anchor="end" font-size="10.5" fill="currentColor">Dispositivos de usuario</text>
  <rect x="200" y="78" width="300" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="508" y="93" font-size="10.5" font-weight="700" fill="currentColor">30%</text>

  <text x="192" y="125" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">Firewalls</text>
  <rect x="200" y="110" width="210" height="20" rx="3" fill="#e23a3a" opacity="0.65"/>
  <text x="418" y="125" font-size="10.5" font-weight="700" fill="#e23a3a">21%</text>

  <text x="192" y="157" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">VPNs</text>
  <rect x="200" y="142" width="80" height="20" rx="3" fill="#e23a3a" opacity="0.65"/>
  <text x="288" y="157" font-size="10.5" font-weight="700" fill="#e23a3a">8%</text>

  <text x="192" y="189" text-anchor="end" font-size="10.5" fill="currentColor">Dispositivos IoT</text>
  <rect x="200" y="174" width="30" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="238" y="189" font-size="10.5" font-weight="700" fill="currentColor">3%</text>

  <text x="20" y="222" font-size="11.5" font-weight="600" fill="#e23a3a">Firewall y VPN suman 29%: los dos sistemas que el proyecto de MFA nunca toca</text>
</svg>
```

Y sumando "aplicaciones o sistemas expuestos" —en una PyME típica, el ERP publicado, el escritorio remoto y el panel del hosting— se llega arriba del 65% del problema concentrado donde el proyecto de MFA nunca llegó.

## Acto 3. Los once huecos

Con esta lista abierta, cada fila lleva dos minutos y ningún permiso especial.

| # | Punto de acceso | Chequeo de 2 minutos |
|---|---|---|
| 1 | **VPN corporativa** | Entrá desde fuera de la oficina con usuario y contraseña de un empleado común. Si entrás sin segundo paso, está abierta. |
| 2 | **Consola del firewall** | Abrí la IP pública del firewall en el navegador desde fuera de la oficina. Si aparece un login, ya perdiste la mitad: no debería ser alcanzable desde internet. |
| 3 | **RDP interno** | Revisá quién tiene el rol de Escritorio Remoto. Si entra con usuario de dominio y contraseña sola, es el camino más corto al cifrado total. |
| 4 | **IMAP/SMTP y autenticación básica** | En Entra, filtrá los registros de inicio de sesión por "Client app: otros clientes". Cada línea ahí es un login que esquivó tu MFA. |
| 5 | **La cuenta del contador externo** | ¿Tiene usuario en tu tenant o comparte la casilla de administración? Preguntale con qué autentica. Si entra con una clave que alguien le pasó, ahí está el hallazgo. |
| 6 | **Panel del hosting** | Buscá "seguridad" o "verificación en dos pasos" en el panel de tu proveedor. En la mayoría está disponible y desactivado por defecto. |
| 7 | **Registrador del dominio** | El hueco más caro: quien controla el DNS controla tu correo, tu SPF y tu certificado. Verificá el segundo factor y el bloqueo de transferencia. |
| 8 | **Facturación electrónica** | El portal de AFIP/ARCA, SAT, SII o DIAN, y el software intermediario. Chequeá si el certificado digital o el token está en una PC compartida sin PIN. |
| 9 | **Cuentas de servicio** | Listá los usuarios que corren integraciones, backups y scripts. Ninguno debería tener MFA humano; todos, credencial larga, rotada y restringida por IP. Si alguno usa la contraseña de 2021, ahí está el agujero. |
| 10 | **Admin del ERP** | Contá los perfiles de administrador y cruzá esa lista contra la nómina vigente. Si el número sorprende para arriba, ese es el hallazgo: los admin se acumulan porque dar permisos resuelve el problema del día y sacarlos no le resuelve nada a nadie. |
| 11 | **Cuenta break-glass** | Tiene que existir, estar excluida de las políticas y estar auditada. Si nadie sabe cuál es, o si es la cuenta personal del dueño, no es break-glass: es una excepción permanente. |

Sobre la 9 y la 11: las cuentas de servicio y la de emergencia **no llevan MFA**, y eso es correcto. Ponerle segundo factor humano a un proceso automatizado rompe la integración el primer día. Llevan otra cosa: secreto largo, rotación con fecha, origen restringido y alerta cuando se usan. Si alguien creó una exclusión de política para que no falle el backup y sigue viva dos años después, hay una cuenta sin MFA con permisos de administrador que nadie mira.

La 4 merece un párrafo aparte. La autenticación básica —IMAP, POP3, SMTP AUTH, EWS, ActiveSync viejo— no es un hueco por descuido: lo es por diseño del protocolo. La credencial viaja sola, no hay dónde meter un segundo paso, y el atacante ni se entera de que había MFA. Microsoft apagó Basic auth en el resto de los protocolos de Exchange Online en octubre de 2022, pero dejó vivo el envío de clientes por SMTP AUTH —la multifunción que escanea a mail, el ERP que manda facturas— y la fecha de retiro se corrió varias veces. La única respuesta que vale es la del centro de mensajes del propio tenant: no conviene asumir que Microsoft ya lo apagó ni que la fecha del año pasado sigue en pie. Y si el dominio no tiene [SPF, DKIM y DMARC bien configurados](/guia/configurar-spf-dkim-dmarc-paso-a-paso), esa misma vía sirve para mandar correo en tu nombre.

## Acto 4. Los tres modos de saltar un MFA que sí está puesto

Hasta acá, ausencia. Ahora los casos en que el MFA está activo y el atacante entra igual. Son tres, y cada uno tiene contramedida concreta.

### Proxy AiTM (*adversary in the middle*)

El enlace del phishing no lleva a una copia del login de Microsoft: lleva a un proxy inverso que reenvía el tráfico al login real. Se ve la página verdadera, se escribe la contraseña verdadera, se aprueba el push verdadero, se entra. El proxy se queda con la cookie de sesión y la usa desde su máquina. El MFA funcionó perfecto y no sirvió de nada.

No es un caso de borde. Tycoon 2FA, el kit de *phishing como servicio* que industrializó la técnica, llegó a concentrar una porción mayoritaria de los intentos de phishing que reportan las empresas de inteligencia de amenazas, y cuando le cayó encima una acción de las fuerzas del orden los operadores se mudaron a kits equivalentes: Mamba 2FA, EvilProxy, Sneaky 2FA. Cuál está de moda este trimestre cambia rápido y no vale fijarlo en un número; lo que importa es que la técnica se alquila como servicio. Contra este ataque, TOTP y push son inútiles por diseño: el usuario puede entregar cualquier código a cualquier página.

**Contramedida exacta:** credenciales ligadas al dominio. FIDO2, passkeys sincronizadas o una llave física. El navegador firma contra el origen real; si el origen es `login.microsft-secure.com`, la llave no responde. No hay entrenamiento de usuario que iguale eso. El criterio de compra no es esperar a un incidente sino cubrir primero las cuentas que mueven plata; modelos, protocolos y precios están en el [análisis de las YubiKey 5 Series](/resena/analisis-yubikey-5-series-costo-total-argentina).

### Robo de token de sesión

Variante sin phishing. Un infostealer en el equipo del usuario —RedLine, Lumma, Vidar— se lleva las cookies del navegador. El atacante las importa y ya está adentro, sin contraseña y sin MFA: la sesión ya estaba autenticada.

**Contramedida exacta:** vida corta de sesión en las aplicaciones administrativas, revocación de tokens ante señal de riesgo, y vinculación de la sesión al dispositivo. En Entra ID se llama *token protection* y *continuous access evaluation*; en Okta, *device binding*. Todo eso vive en la capa de acceso condicional, no en la de MFA.

### Fatiga de push (*MFA bombing*)

El atacante ya tiene la contraseña y dispara aprobaciones hasta que la persona toca "sí" para que el teléfono deje de vibrar, normalmente a las tres de la mañana. Funcionó contra Uber en 2022 y sigue funcionando donde el push pide una decisión binaria sin contexto.

**Contramedida exacta:** *number matching* obligatorio —el usuario tipea el número que ve en pantalla— más contexto de aplicación y ubicación en la notificación, más límite de intentos fallidos. Conviene verificar antes de gastar una tarde: Microsoft lo impuso para todo el push de Authenticator el 8 de mayo de 2023 y retiró los controles que permitían excluir usuarios, así que en Entra ID ya no se puede apagar; [Cisco Duo](/producto/cisco-duo) lo llama Verified Duo Push, y su documentación aclara que el comportamiento por defecto depende de cuándo se creó el tenant. El hueco sobrevive en lo que queda fuera de esos valores por defecto: tenants de Duo viejos, MFA de terceros, integraciones RADIUS y extensiones NPS on-premise. Ese es el inventario a revisar.

La objeción previsible es que los usuarios van a odiar tipear números. Van a odiar más el fin de semana de restore. Y el número aparece solo en logins nuevos, no cada vez que abren Outlook.

## Acto 5. Qué cierra cada hueco, ordenado por lo que cuesta

Las tres cosas se venden en el mismo paquete y no resuelven el mismo problema.

| Nivel | Qué es | Contra qué sirve | Costo |
|---|---|---|---|
| **0** | Lo que ya está pago: apagar autenticación básica, cerrar la consola del firewall a internet, segundo factor en el registrador y el hosting, revisar admins del ERP | Los huecos de cobertura, que son la mayoría | Cero y una tarde |
| **1** | MFA a secas donde falta, sistema por sistema | Credenciales filtradas y fuerza bruta. **No** contra AiTM | Duo Essentials desde USD 3 por usuario/mes; gratis hasta 10 usuarios |
| **2** | Acceso condicional: no solo quién sos, sino desde qué equipo, qué país y con qué sesión | Robo de token y accesos anómalos | Entra ID P1 USD 7, P2 USD 10; Duo Advantage USD 6, Premier USD 9; Okta USD 3 y USD 6 adaptativo |
| **3** | Credenciales resistentes a phishing en hardware | AiTM, que ninguno de los anteriores frena | Security Key NFC USD 29, YubiKey 5 NFC USD 58, dos por persona |

Precios de lista consultados en julio de 2026 y sujetos a cambio sin aviso: conviene abrir la página del fabricante antes de llevar un número a una reunión.

Tres notas de compra. Con Microsoft 365 Business Premium (USD 22 por usuario/mes con compromiso anual), [Entra ID](/producto/microsoft-entra-id) P1 ya viene incluido y probablemente no se esté usando entero; pagar [Okta](/producto/okta-workforce-identity) encima de eso en una organización 100% Microsoft es un error de compra aunque el producto sea bueno. El valor de [Duo](/producto/cisco-duo) en el nivel 1 es que se integra por RADIUS con la VPN y el firewall, justo donde el MFA nativo del proveedor de identidad no llega. Y en el nivel 2 conviene mirar en qué escalón entra la autenticación basada en riesgo, porque no siempre está donde uno la busca; en Okta, además, preguntar por el mínimo anual de contrato, que en una empresa de diez personas cambia todo el cálculo por usuario.

Sobre el nivel 3: no hace falta comprarle a los 40 empleados. Ocho personas con dos llaves son 16 unidades, entre USD 464 y USD 928 por única vez, precios de lista en Estados Unidos sin impuestos ni envío —importadas a la región hay que sumar derechos, IVA y flete—. La comparación es contra los USD 3.046.598.558 que el FBI contabilizó en fraude BEC durante 2025 sobre 24.768 denuncias: USD 123.000 promedio por caso.

La comparación completa está en el [directorio de MFA y autenticación](/productos/mfa-y-autenticacion), y quien viene de cero arranca por el [plan de despliegue de MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).

### El caso Microsoft, leído bien

Microsoft exige MFA en el portal de Azure, el centro de administración de Entra y el de Intune desde el 15 de octubre de 2024; en febrero de 2025 sumó el centro de administración de Microsoft 365, y el 1 de octubre de 2025 arrancó la fase dos: Azure CLI, PowerShell, app móvil, infraestructura como código y APIs REST del plano de control, con las operaciones de solo lectura exceptuadas.

La parte que ningún titular explica: **eso cubre las herramientas de administración de Azure, no a tus usuarios**. No cubre Outlook Web, ni el buzón del contador, ni tu VPN, ni el ERP. La obligatoriedad de Microsoft protege la infraestructura de Microsoft. El resto sigue siendo tuyo.

## Acto 6. El test de 20 minutos

Siete preguntas, con lo que se pueda verificar hoy y no con lo que se cree recordar.

1. **¿Se entra a la VPN desde una red externa con usuario y contraseña solos?** No → 3 puntos. Sí, o hay RDP publicado → 0.
2. **¿La consola del firewall responde desde internet?** No responde → 3. Responde con segundo factor → 1. Responde con usuario y contraseña → 0.
3. **¿Está apagada la autenticación básica (IMAP/POP3/SMTP AUTH) en tu tenant?** Apagada y verificada en los registros → 3. Apagada con excepción documentada → 2. No sé → 0.
4. **¿Todos tus flujos de push exigen tipear un número, incluidos los que pasan por RADIUS, NPS o MFA de terceros?** Todos → 2. Solo la plataforma principal → 1. No sé → 0.
5. **¿Hay cuenta break-glass identificada, con contraseña fuera del sistema y alerta cuando se usa?** Las tres cosas → 3. Existe pero nadie la audita → 1. No existe → 0.
6. **¿Cuántas personas son admin del ERP y del panel del hosting, y las revisaste este trimestre?** Revisadas y son las que deben ser → 3. Sé el número pero no lo revisé → 1. No sé el número → 0.
7. **¿Las cuentas con acceso a pagos y al dominio usan credencial resistente a phishing (FIDO2 o passkey)?** Todas → 3. Algunas → 1. Ninguna → 0.

**18 a 20:** fuera del 97%. El paso siguiente es el acceso condicional y las sesiones.
**11 a 17:** el MFA cubre a las personas y no a la infraestructura. Es el escenario que este inventario está armado para detectar, y casi todo se arregla en el Nivel 0.
**0 a 10:** hay MFA en el correo y nada más. Empezar por las preguntas 1, 2 y 3: son las que un atacante prueba primero.

### Dos límites de esta guía

El primero es de las fuentes. Todo esto se apoya en documentación de producto, precios de lista públicos y tres informes anuales con metodología declarada: Sophos, el DBIR de Verizon y el ESET Security Report. Ninguno muestrea empresas de menos de 100 personas, que es el tamaño de buena parte de quienes leen esto, así que extrapolar hacia abajo es un criterio y no un dato. Hacia arriba pasa lo contrario: con varias unidades de negocio y tenants con confianzas cruzadas, un inventario de once filas se queda corto y no hay datos públicos confiables para extenderlo.

El segundo es del método. La lista de once cubre lo que se administra en casa. Hay un hueco número doce que no está porque no se puede cerrar desde acá: el MFA del proveedor de software contable, el del estudio que liquida sueldos, el del que hospeda el sitio. El DBIR 2026 cuenta un tercero involucrado en el **48%** de las brechas —venía del 30%— y encontró que apenas el **23%** remedió del todo el MFA faltante o mal configurado en sus cuentas cloud. Eso no se configura desde acá. Preguntarlo por escrito, con nombre y fecha, y guardar la respuesta, sí.

Si el test dejó pensando en el acceso remoto, la discusión sigue en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn). Si preocupa la higiene de credenciales antes que el segundo factor, el [análisis de 1Password Business para equipos chicos](/resena/resena-1password-business-12-meses) cubre esa capa. Y la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) tiene el orden de implementación completo, incluido el [backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

## Preguntas frecuentes

### ¿Qué pasa si no activo el MFA obligatorio de Microsoft 365?

Te quedás afuera de los portales y herramientas de administración: portal de Azure, Entra e Intune desde el 15 de octubre de 2024; centro de administración de Microsoft 365 desde febrero de 2025; Azure CLI, PowerShell e infraestructura como código desde el 1 de octubre de 2025. No hay multa ni suspensión del servicio: no vas a poder administrar, nada más. Y el límite importa más que el mandato: no obliga a nada en el inicio de sesión de tus usuarios finales, ni en tu VPN, ni en tus aplicaciones propias.

### Entraron a una cuenta aunque teníamos doble factor, ¿por dónde pudo ser?

Casi siempre por una de cuatro vías: un sistema que quedó sin MFA (VPN, firewall, RDP, IMAP), un proxy AiTM que capturó la cookie mientras el usuario aprobaba el push, un infostealer que robó cookies, o fatiga de push. Para saber cuál fue, buscá la sesión sospechosa en los registros: si hay un evento de MFA exitoso desde una IP rara, fue AiTM o token robado; si no hay ningún evento de MFA, ese sistema nunca lo tuvo.

### ¿El MFA por SMS sirve para algo o hay que sacarlo?

Sirve más que nada y menos de lo que parece. Detiene credenciales filtradas y fuerza bruta, que son el grueso del ruido automatizado. No detiene AiTM, ni *SIM swapping*, ni el robo de sesión. El criterio razonable: aceptable como respaldo para un usuario sin smartphone corporativo, inaceptable para cualquier cuenta que pueda mover dinero o cambiar accesos. Primero migrar a la app autenticadora y recién después apagarlo como método permitido.

### ¿Qué es el MFA fatigue y cómo lo corto hoy mismo?

Es el atacante disparando aprobaciones repetidas hasta que la persona acepta una para poder dormir. Se corta con *number matching*, contexto en la notificación y límite de intentos fallidos. En Microsoft Authenticator no hay nada que hacer: está impuesto desde mayo de 2023. En Duo el comportamiento por defecto depende de la antigüedad del tenant, así que conviene entrar a la consola y confirmar que Verified Push esté exigido. Lo que sí hay que revisar es lo que autentica por fuera: RADIUS, NPS y cualquier MFA de terceros.

### ¿Cómo configuro acceso condicional en Entra ID sin romper la operación?

En modo "solo informe" primero. Se crea la política, se deja en *report-only* entre siete y catorce días y se revisa quién habría sido bloqueado. Ahí aparecen las sorpresas: la multifunción que manda correo, el script de facturación, el gerente que viaja. Recién con el reporte limpio se pasa a activa. Antes de tocar nada, dejar creada y excluida la cuenta break-glass: una política mal armada bloquea fuera del tenant y esa recuperación con soporte lleva días.

### ¿Las cuentas de servicio también necesitan MFA?

No, y forzarlo rompe integraciones. Necesitan otra cosa: secreto largo al azar, rotación con fecha en el calendario, restricción por IP o identidad administrada cuando la plataforma la soporte, permisos mínimos, y alerta cuando la cuenta se usa fuera de su horario. El error frecuente no es dejarlas sin MFA: es dejarlas sin nada de eso y con permisos de administrador global porque así funcionó el día de la instalación.
