---
title: "Cómo funciona el robo de sesión que saltea el MFA"
subtitle: No te roban la contraseña ni el código: te roban la sesión ya iniciada. Por dónde entra, cómo se detecta, y por qué solo hay una defensa que lo corta de raíz.
excerpt: El ataque que saltea el MFA no roba tu contraseña: roba el token de sesión después de que te autenticaste. Cómo funciona por dentro, cómo detectarlo y cómo frenarlo.
type: guide
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-08-18
updated: 2026-08-20
products:
  - yubikey-5-series
  - microsoft-entra-id
  - cisco-duo
  - microsoft-defender-for-office-365
meta_title: "Cómo funciona el robo de sesión que saltea el MFA"
meta_description: "El ataque que saltea el MFA no roba tu contraseña: roba la sesión ya iniciada. Cómo funciona, cómo detectarlo y la única defensa que lo corta de raíz."
---

Activaste el MFA en todo Microsoft 365. Hiciste bien. Y aun así, un atacante puede entrar a la cuenta de tu gente sin robar la contraseña y sin que nadie apruebe una notificación falsa en el teléfono.

No es un fallo de configuración: el ataque más común de 2026 no ataca la parte que protegiste. Es la continuación técnica de [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), que explica el qué; acá está el cómo.

No hay instrucciones para montar el ataque. Entender el mecanismo sirve para defenderse; los pasos para ejecutarlo, no.

---

## Lo que el atacante quiere no es tu contraseña

Cuando se habla de "robo de cuenta" se piensa en alguien adivinando o robando la contraseña. El MFA se inventó justamente para que eso no alcance: aunque tengan la clave, les falta el segundo factor.

El ataque moderno acepta esa premisa y la esquiva. No pelea contra el MFA: **espera a que vos lo completes y le roba el resultado.**

Ese resultado tiene nombre: el **token de sesión** (o cookie de sesión). Cuando iniciás sesión en Microsoft 365 y aprobás el segundo factor, el servidor te entrega una credencial temporal que dice "esta persona ya se autenticó, dejala trabajar sin volver a preguntarle". Tu navegador la guarda y la presenta en cada pedido siguiente. Es lo que hace que no tengas que poner la contraseña cada vez que abrís un correo.

Si un atacante consigue esa cookie, **hereda tu sesión ya autenticada**. No necesita tu contraseña, no necesita tu segundo factor, no necesita tu teléfono. Para el servidor, presenta la misma credencial que vos presentaste, así que es vos.

---

## El mecanismo, paso a paso

El ataque se llama **adversary-in-the-middle** (AiTM), adversario en el medio. La pieza central es un servidor intermedio que el atacante pone entre vos y Microsoft, haciendo de espejo. La técnica está documentada por [Microsoft en su guía sobre autenticación resistente a phishing](https://www.microsoft.com/en-us/security/business/security-101/what-is-fido2) y es la que explica por qué el segundo factor común no alcanza.

```svg
<svg viewBox="0 0 720 250" role="img" aria-label="Diagrama del ataque: la víctima se autentica contra Microsoft a través de un servidor intermedio que copia la cookie de sesión">
  <text x="60" y="26" text-anchor="middle" font-size="14" font-weight="600" fill="currentColor">Vos</text>
  <text x="360" y="26" text-anchor="middle" font-size="14" font-weight="600" fill="#e23a3a">Servidor del atacante</text>
  <text x="660" y="26" text-anchor="middle" font-size="14" font-weight="600" fill="currentColor">Microsoft</text>

  <rect x="15" y="40" width="90" height="170" rx="6" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.45"/>
  <rect x="290" y="40" width="140" height="170" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <rect x="615" y="40" width="90" height="170" rx="6" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.45"/>

  <line x1="105" y1="72" x2="288" y2="72" stroke="currentColor" stroke-width="1.5"/>
  <polygon points="288,72 279,68 279,76" fill="currentColor"/>
  <text x="196" y="66" text-anchor="middle" font-size="12" fill="currentColor">1. contraseña</text>

  <line x1="432" y1="72" x2="613" y2="72" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
  <polygon points="613,72 604,68 604,76" fill="currentColor" opacity="0.6"/>
  <text x="522" y="66" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.75">reenvía</text>

  <line x1="105" y1="118" x2="288" y2="118" stroke="currentColor" stroke-width="1.5"/>
  <polygon points="288,118 279,114 279,122" fill="currentColor"/>
  <text x="196" y="112" text-anchor="middle" font-size="12" fill="currentColor">2. segundo factor</text>

  <line x1="432" y1="118" x2="613" y2="118" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
  <polygon points="613,118 604,114 604,122" fill="currentColor" opacity="0.6"/>
  <text x="522" y="112" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.75">reenvía</text>

  <line x1="613" y1="166" x2="434" y2="166" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
  <polygon points="434,166 443,162 443,170" fill="currentColor" opacity="0.6"/>
  <text x="522" y="160" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.75">3. cookie de sesión</text>

  <line x1="288" y1="166" x2="107" y2="166" stroke="currentColor" stroke-width="1.5"/>
  <polygon points="107,166 116,162 116,170" fill="currentColor"/>
  <text x="196" y="160" text-anchor="middle" font-size="12" fill="currentColor">llega a vos</text>

  <text x="360" y="196" text-anchor="middle" font-size="13" font-weight="600" fill="#e23a3a">copia la cookie</text>
  <text x="360" y="232" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.8">todo funciona: te autenticaste contra Microsoft de verdad</text>
</svg>
```

Paso a paso, del lado de la víctima:

1. **Llega el correo.** Un mensaje convincente —una factura, un documento compartido, un aviso de que "tu contraseña vence"— con un enlace. La puerta de entrada casi siempre es el correo, y por eso reforzarlo es la primera línea; la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes) y la [guía de SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso) atacan ese frente.
2. **El enlace lleva al espejo, no a Microsoft.** La página se ve idéntica a la de Microsoft porque **es** la de Microsoft: el servidor intermedio le pide las páginas reales a Microsoft y te las reenvía. No es una copia mal hecha con un logo pixelado; es el sitio verdadero, servido a través del intermediario.
3. **Ponés tu contraseña. Funciona.** El intermediario se la pasa a Microsoft en tiempo real. Microsoft la valida.
4. **Te pide el segundo factor. Lo completás. Funciona.** El intermediario también reenvía eso. Desde tu lado, todo se comportó exactamente como siempre, porque efectivamente te estás autenticando contra Microsoft de verdad.
5. **Microsoft entrega la cookie de sesión.** Y como toda esa conversación pasó por el intermediario, la cookie pasa por sus manos antes de llegar a vos. **Ahí es donde la copia.**

A partir de ese momento el atacante tiene tu sesión. Vos seguís trabajando sin notar nada —tu sesión también funciona—, mientras él usa la copia desde otra máquina, en otro país, sin que a nadie se le pida un nuevo segundo factor.

Esto dejó de ser artesanal. Hoy existen kits comerciales de "phishing como servicio" —Tycoon 2FA es uno de los nombres que circula en los informes de 2026— y herramientas de proxy inverso de código abierto como Evilginx que automatizan todo el montaje. Nombrarlos es contexto defensivo: son las herramientas cuyo tráfico tu equipo tiene que aprender a reconocer, no un instructivo.

---

## Por qué el MFA común no lo frena

Porque el MFA común valida **quién sos al entrar**, y este ataque no discute quién sos al entrar: te deja entrar de verdad y roba lo que pasa después. El código de seis dígitos, la notificación en la app, el SMS: todos confirman tu identidad en el momento del login, y todos son reenviables por el intermediario porque suceden **antes** de que se emita la cookie.

Es la distinción que casi nadie hace y que ordena toda la defensa: hay factores que se **transmiten** —un código que tu teléfono muestra y vos tecleás— y factores que **no se transmiten nunca**. Todo lo que se transmite, se puede interceptar y reenviar. El código más largo del mundo, si viaja por la red, viaja también por el intermediario.

---

## Cómo saber si te está pasando

El robo de sesión no se ve en el login, porque el login es legítimo. Se ve **después**, en el comportamiento de la sesión, y esas señales sí quedan registradas. Todo esto es del lado defensivo, con tus propios registros.

- **Cambio de IP dentro de la misma sesión.** Una sesión que empieza en una IP y sigue desde otra distinta, sin que haya habido una nueva autenticación en el medio, es la señal más clara de una cookie replicada.
- **Viaje imposible.** Un inicio de sesión en Buenos Aires y, veinte minutos después, actividad de esa misma sesión desde otro continente. [Microsoft Entra ID](/producto/microsoft-entra-id) tiene detección de viaje imposible entre sus señales de riesgo, y es de lo primero que conviene tener encendido.
- **Actividad desde rangos de proveedores de hosting.** Tu gente se conecta desde redes hogareñas y de oficina, no desde un centro de datos. Una sesión que aparece desde el rango de IP de un proveedor de nube que ese usuario nunca usa es sospechosa por definición.
- **Huella de dispositivo que no coincide.** La autenticación ocurrió desde Windows con Chrome; de golpe la misma sesión actúa desde Linux con Firefox. Misma cookie, dispositivo distinto.

Ninguna de estas señales aparece si solo mirás "¿el login tuvo MFA?". Todas requieren mirar la sesión después de iniciada, que es donde vive el ataque.

---

## La defensa que lo corta de raíz

Hay una sola, y es un cambio en el tipo de segundo factor, no en la contraseña ni en la vigilancia: **autenticación resistente a phishing**, que en la práctica hoy significa FIDO2/WebAuthn, ya sea con una llave física o con passkeys.

La razón por la que funciona es elegante y vale entenderla, porque es lo que separa esta defensa de todas las demás. Según [la documentación de Microsoft](https://www.microsoft.com/en-us/security/business/security-101/what-is-fido2), FIDO2 usa criptografía de clave pública: al registrarte, tu dispositivo genera un par de claves único para **ese dispositivo, esa cuenta y ese servicio**. La clave privada nunca sale del dispositivo; solo se comparte la pública.

La credencial queda **atada al dominio exacto** del servicio. Cuando te autenticás, el dispositivo firma un desafío criptográfico, pero **solo firma si el dominio que lo pide es el dominio real para el que se registró la clave**. El servidor intermedio del atacante vive en otro dominio. Cuando le pide a tu llave que firme, la llave mira el dominio, ve que no es el que corresponde, y **se niega a firmar**. No hay nada que reenviar, porque nunca se generó una firma válida. No viaja ningún secreto reutilizable por la red.

En criptografía esto se llama **origin binding** —atadura al origen— y es la propiedad que ningún código de seis dígitos puede tener, porque el código no sabe a qué sitio lo estás tecleando. La llave sí lo sabe, y por eso no se deja engañar.

Las opciones concretas:

- **Passkeys**, disponibles en todas las ediciones de Entra ID incluida la gratuita, así que la plataforma no agrega costo. Para buena parte del equipo, es la respuesta.
- **Llaves físicas** como las [YubiKey 5 Series](/producto/yubikey-5-series) para las identidades de más privilegio: administradores, finanzas, quien pueda mover dinero o accesos. Si conviene la inversión y para quién, está en el [análisis de costo total de las YubiKey en Argentina](/resena/analisis-yubikey-5-series-costo-total-argentina), cuya conclusión es que no para todos, pero sí para ese núcleo.
- **[Cisco Duo](/producto/cisco-duo)** y las demás plataformas de la categoría también ofrecen métodos resistentes a phishing; el catálogo está en [MFA y autenticación](/productos/mfa-y-autenticacion).

---

## Qué hacer, en orden

1. **Encendé las señales de riesgo de sesión** en Microsoft 365: viaje imposible, actividad desde rangos de hosting, anomalías de sesión. Es gratis en las ediciones que ya lo incluyen y es lo que te avisa si ya está pasando.
2. **Pasá a passkeys** al grueso del equipo. Cuesta cero en licencia y convierte el ataque de "posible" a "no funciona" para esas cuentas.
3. **Llaves físicas para las cuentas de privilegio elevado.** Administradores primero. Es el núcleo donde un robo de sesión hace más daño y donde la inversión cierra.
4. **Reforzá el correo, que es la puerta.** Sin el mensaje inicial, el ataque no arranca; la [comparativa de seguridad de email](/comparativa/comparativa-seguridad-email-pymes) baja a producto.
5. **Acortá la vida de las sesiones** de las cuentas sensibles, para que una cookie robada caduque antes. No frena el ataque, pero le reduce la ventana.

Los pasos 2 y 3 son los que importan; el resto reduce daño pero no cierra el agujero. Si tuvieras que hacer una sola cosa, es mover a las cuentas de administrador a autenticación resistente a phishing, hoy.

Y si esto te llega porque un cliente te está por auditar, el robo de sesión y su defensa son exactamente lo que hay detrás de la pregunta de MFA en cualquier [cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder): un evaluador con oficio no pregunta "¿tenés MFA?", pregunta "¿de qué tipo?".

---

## Preguntas frecuentes

### Si ya tengo MFA, ¿estoy en riesgo igual?

Si tu segundo factor es un código, una notificación en una app o un SMS, sí: todos son reenviables por el intermediario porque se transmiten por la red. El MFA sube muchísimo la barrera contra el robo simple de contraseña, pero no frena el robo de sesión. La defensa específica es la autenticación resistente a phishing.

### ¿Las passkeys y las llaves físicas son de verdad inmunes a esto?

Contra el robo de sesión por adversario en el medio, sí, y por una razón estructural: la credencial está atada al dominio real y el dispositivo se niega a firmar para el dominio falso del atacante. No se genera nada reutilizable. Ninguna defensa es absoluta contra todo, pero contra este ataque específico, el origin binding lo corta de raíz.

### ¿Me sirve un antivirus o un EDR contra esto?

Poco, porque el ataque no instala nada en la máquina de la víctima: pasa en el navegador y en la red. Lo que sirve es el tipo de segundo factor y la detección de anomalías de sesión del lado de la nube, no el endpoint. Es un buen recordatorio de que no todo se resuelve en la computadora.

### ¿Cómo entra el ataque en primer lugar?

Casi siempre por un correo con un enlace a la página espejo. Por eso reforzar el correo con buen filtrado anti-phishing y con SPF, DKIM y DMARC bien configurados es la primera línea: si el mensaje no llega, el ataque no empieza.

### ¿Cada cuánto tendría que revisar los inicios de sesión sospechosos?

Las señales de viaje imposible y anomalía de sesión conviene tenerlas como alerta automática, no como revisión manual periódica: el valor está en enterarte en minutos, no en descubrirlo la semana siguiente. Configuralas para que te avisen, y revisá manualmente solo cuando salte una.
