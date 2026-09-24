---
title: "EvilTokens: el phishing a Microsoft 365 que nunca te pide la contraseña"
subtitle: Microsoft desarticuló el servicio que comprometió 12.000 casillas en más de 10.000 organizaciones. Usaba el flujo de código de dispositivo, así que la víctima escribe el código en la página real de Microsoft.
excerpt: Un phishing que no roba contraseñas, no usa páginas falsas y no lo frena el MFA. Qué es el flujo de código de dispositivo y cómo se bloquea.
type: news
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-09-24
updated: 2026-09-24
products:
  - microsoft-entra-id
  - microsoft-defender-for-office-365
meta_title: "Microsoft 365: phishing que no pide contraseña"
meta_description: "Microsoft desarticuló EvilTokens, el phishing que usa el código de dispositivo y no pide contraseña ni páginas falsas. Cómo bloquearlo en tu Microsoft 365."
---

Microsoft anunció el 22 de septiembre la desarticulación de **EvilTokens**, un servicio de phishing por suscripción que, según la propia empresa, comprometió *"more than 12,000 compromised email inboxes across over 10,000 organizations worldwide"* en los pocos meses que lleva funcionando: arrancó en **febrero de 2026**.

La Unidad de Crímenes Digitales de Microsoft **incautó 50 sitios web** y deshabilitó **más de 150 dominios** de la infraestructura asociada, junto con Cloudflare, Coinbase, OpenAI, Railway, SpyCloud, The Shadowserver Foundation, TRM Labs y Health-ISAC. Y el **11 de septiembre** el equipo de cibercrimen de la Policía Metropolitana del Reino Unido **detuvo a dos hombres**, de 32 y 38 años, por su presunta participación en la operación.

Pero lo que hace a esta nota no es la caída del servicio. Es **cómo entraba**, porque desmonta casi todo lo que se le enseña a un empleado sobre cómo reconocer un phishing.

## Qué es el flujo de código de dispositivo de Microsoft y por qué no pide contraseña

El flujo de código de dispositivo existe para un problema real: iniciar sesión en aparatos donde escribir es incómodo o imposible, como un televisor de sala de reuniones o un teléfono de escritorio. El aparato muestra un código corto, vos lo escribís en otro dispositivo donde ya estás con la sesión abierta, y listo.

La descripción de Microsoft del abuso es de una sola línea y lo dice todo: *"Instead of a legitimate device requesting access, the threat actor initiates the flow and provides the user with a code through a phishing lure."* El que pide el acceso no es tu televisor: es el atacante.

```svg
<svg viewBox="0 0 680 195" role="img" aria-label="Cómo funciona el phishing por código de dispositivo que usaba EvilTokens según Microsoft: el atacante inicia el flujo de autenticación contra el proveedor de identidad de Microsoft y obtiene un código vivo en tiempo real; te envía ese código con un pretexto mediante un correo con enlace o adjunto; vos escribís el código en microsoft punto com barra devicelogin, que es la página auténtica de Microsoft, y confirmás; el servicio del atacante está consultando en segundo plano y recibe los tokens de tu sesión, sin que vos hayas escrito nunca tu contraseña en ningún lado">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Quien pide el acceso no sos vos, pero quien lo aprueba sí</text>

  <rect x="20" y="46" width="150" height="94" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="46" width="150" height="94" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="95" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">El atacante</text>
  <text x="95" y="94" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">inicia el flujo y</text>
  <text x="95" y="112" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">obtiene un código</text>
  <text x="95" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">vivo, del momento</text>
  <path d="M175 93 L205 93" stroke="#e23a3a" stroke-width="1.5"/>

  <rect x="212" y="46" width="150" height="94" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="212" y="46" width="150" height="94" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="287" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">Te lo manda</text>
  <text x="287" y="94" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">con un pretexto,</text>
  <text x="287" y="112" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">por correo, con un</text>
  <text x="287" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">botón que parece bien</text>
  <path d="M367 93 L397 93" stroke="#e23a3a" stroke-width="1.5"/>

  <rect x="404" y="46" width="150" height="94" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="404" y="46" width="150" height="94" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="479" y="72" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Lo escribís vos</text>
  <text x="479" y="94" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">en la página real</text>
  <text x="479" y="112" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">de Microsoft, ya con</text>
  <text x="479" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">tu sesión abierta</text>
  <path d="M559 93 L589 93" stroke="#e23a3a" stroke-width="1.5"/>

  <rect x="596" y="46" width="64" height="94" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="596" y="46" width="64" height="94" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="628" y="82" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">Se lleva</text>
  <text x="628" y="98" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">los tokens</text>
  <text x="628" y="118" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">de tu sesión</text>

  <text x="20" y="180" font-size="11.5" font-weight="600" fill="currentColor">Nunca escribiste tu contraseña en ningún lado, y aun así el atacante entró</text>
</svg>
```

El detalle técnico que explica todo lo demás está en el análisis de Microsoft: el flujo de código de dispositivo *"decouples authentication from the originating session"*. Desacopla la autenticación de la sesión que la pidió. Por eso el atacante puede pedir el acceso desde su máquina y vos aprobarlo desde la tuya, sin que nada en el medio te avise de que son dos cosas distintas.

Y el remate, también de Microsoft: si ya estás con la sesión iniciada, *"simply pasting the code and confirming the request instantly authenticates the threat actor's session"*. Pegar el código y confirmar. Eso es todo lo que hace falta.

## Los cuatro consejos antiphishing de siempre fallan contra EvilTokens

Vale ponerlo en una lista, porque cada punto de la capacitación estándar se cae solo acá.

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Por qué los consejos habituales contra el phishing no funcionan frente al phishing por código de dispositivo. Mirar la dirección del sitio no sirve porque la página es microsoft punto com barra devicelogin, la auténtica. Mirar el candado del certificado no sirve porque el certificado es el real de Microsoft. La regla de no escribir nunca la contraseña en una página sospechosa no sirve porque en este ataque la contraseña no se escribe en ningún momento. Y tener activada la autenticación de múltiples factores tampoco sirve, porque el factor lo aprueba la propia víctima de forma legítima. Lo único que corta el ataque es no escribir códigos que otro te mandó, y bloquear el flujo de código de dispositivo por política">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Los cuatro consejos de siempre, y por qué fallan acá</text>

  <rect x="20" y="42" width="620" height="90" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="42" width="620" height="90" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="64" font-size="10.5" fill="currentColor" opacity="0.9">"Mirá la dirección del sitio" → la dirección es microsoft.com/devicelogin, la de verdad.</text>
  <text x="38" y="82" font-size="10.5" fill="currentColor" opacity="0.9">"Fijate en el candado" → el certificado es el auténtico de Microsoft.</text>
  <text x="38" y="100" font-size="10.5" fill="currentColor" opacity="0.9">"No escribas tu contraseña ahí" → no se escribe ninguna contraseña.</text>
  <text x="38" y="118" font-size="10.5" fill="currentColor" opacity="0.9">"Activá el MFA" → el factor lo aprobás vos, correctamente, y por eso funciona.</text>

  <rect x="20" y="144" width="620" height="52" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="144" width="620" height="52" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="166" font-size="11" font-weight="700" fill="#e23a3a">Lo que sí corta el ataque</text>
  <text x="38" y="186" font-size="10.5" fill="currentColor" opacity="0.85">Bloquear el flujo por política, y una regla para la gente: los códigos no se escriben, se leen.</text>
</svg>
```

Ese último punto es el que conviene convertir en frase para el equipo, porque es corto y no requiere entender nada de lo anterior: **un código que alguien te mandó nunca se escribe en una pantalla de inicio de sesión**. Los códigos van en la dirección contraria: los genera tu aplicación o te los manda el servicio, y los leés vos. Si un código te llegó por correo, por chat o por teléfono y alguien te pide que lo pongas en algún lado, eso es el ataque.

Es la misma familia de problemas que el sitio explicó en [cómo funciona el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa): lo que se lleva el atacante no es la credencial sino la sesión ya autenticada. Acá el método es más limpio todavía, porque ni siquiera hace falta un sitio intermediario.

## El chatbot de EvilTokens leía tu casilla para decidir a quién estafar

La otra mitad de EvilTokens es la que explica por qué Microsoft lo llamó, en el título de su anuncio, un chatbot construido para el cibercrimen.

Según Microsoft, la función central del servicio podía *"analyze a victim's inbox and help criminals identify trusted relationships, payment authorizations, and sensitive responsibilities"*, y además *"recommend fraud strategies, including drafting messages that impersonated trusted contacts"*.

Leído despacio: el trabajo que antes hacía un humano revisando a mano una casilla robada —buscar quién autoriza pagos, con qué proveedores hay confianza, quién firma qué— lo hacía el servicio, automáticamente, y después **redactaba el mensaje de suplantación**. Eso es fraude por correo corporativo industrializado, que es exactamente el tipo de pérdida que más golpea a una empresa chica: no le cifran nada, le hacen transferir.

El servicio se vendía por Telegram con **1.500 dólares de entrada y 500 dólares por mes**. Vale detenerse en ese número: por el costo de un plan de software cualquiera, alguien alquilaba una operación completa de acceso y análisis.

Es la continuación del hilo que este sitio viene siguiendo desde que [Aurora usó un asistente de código para planear intrusiones](/noticia/aurora-ransomware-cursor-ia-planear-intrusiones): la parte que la IA automatiza no es la intrusión, que sigue siendo la de siempre, sino **el trabajo de entender qué encontraste** una vez adentro.

## 12.000 casillas en 10.000 organizaciones: 1,2 por empresa

Hay un dato en las cifras de Microsoft que no aparece en las coberturas y que dice bastante sobre para qué se usaba esto.

```svg
<svg viewBox="0 0 680 185" role="img" aria-label="Relación entre casillas comprometidas y organizaciones afectadas por EvilTokens según Microsoft: más de 12.000 casillas de correo comprometidas repartidas en más de 10.000 organizaciones de todo el mundo, lo que da un promedio de 1,2 casillas por organización. Las dos barras son casi iguales, y esa igualdad es el dato: no se trata de una cosecha masiva de cuentas dentro de pocas empresas, sino de aproximadamente una casilla por empresa, que es lo que hace falta para montar un fraude por correo corporativo">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Casi tantas organizaciones como casillas</text>

  <text x="20" y="74" font-size="10.5" font-weight="700" fill="#e23a3a">Casillas comprometidas</text>
  <rect x="230" y="61" width="400" height="16" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="638" y="74" font-size="10" font-weight="700" fill="#e23a3a" text-anchor="end">12.000</text>

  <text x="20" y="128" font-size="10.5" font-weight="700" fill="currentColor">Organizaciones afectadas</text>
  <rect x="230" y="115" width="333" height="16" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="638" y="128" font-size="10" font-weight="700" fill="currentColor" text-anchor="end">10.000</text>

  <text x="20" y="172" font-size="11.5" font-weight="600" fill="currentColor">1,2 casillas por organización: no es cosecha masiva, es una puerta en cada empresa</text>
</svg>
```

Doce mil casillas repartidas en diez mil organizaciones dan **1,2 casillas por organización**. No es el patrón de alguien que entra a una empresa y se lleva todo el directorio: es el de alguien que **consigue una casilla en cada empresa y pasa a la siguiente**. Y una casilla bien elegida es todo lo que hace falta para el fraude que describe Microsoft, porque el valor no está en el volumen de correos sino en la confianza que esa persona tiene con quien paga.

Para una PyME eso tiene una consecuencia incómoda: **no hace falta ser un objetivo para estar en la lista**. Con 10.000 organizaciones en 7 meses, la selección no fue una por una.

## ¿A quién afecta el phishing por código de dispositivo en Microsoft 365?

A cualquier organización que use **Microsoft 365**, que en la región es casi cualquiera con correo corporativo. No depende de la versión, del plan ni de tener algo desactualizado: el flujo de código de dispositivo es una función estándar del sistema de identidad, y el ataque abusa de ella tal como funciona.

Que hayan desarticulado EvilTokens no cierra el tema. Lo que cayó es **un proveedor** del servicio, no la técnica: el flujo sigue existiendo y cualquier otro puede montar lo mismo. De hecho, según Microsoft, EvilTokens fue el primero en ofrecer esto a escala, lo que sugiere que el camino recién empieza a transitarse.

## ¿Quién puede ignorar EvilTokens sin correr riesgo?

Quien no use Microsoft 365 ni Entra ID. Si tu correo es Google Workspace o un servidor propio, esta implementación puntual no te toca —aunque la idea general de "un código que te mandan y te piden que escribas" no es exclusiva de ningún fabricante, y conviene que la regla para el equipo sea igual de amplia.

Tampoco es urgente del mismo modo si tu organización **ya bloquea el flujo de código de dispositivo** por política de acceso condicional. Si no sabés si lo bloquea, la respuesta es que probablemente no: viene habilitado.

## ¿Cómo bloqueo el flujo de código de dispositivo en Microsoft 365?

La recomendación es de Microsoft y es explícita: **bloquear el flujo de código de dispositivo donde sea posible**, mediante una política de acceso condicional en [Entra ID](/producto/microsoft-entra-id). Donde haga falta dejarlo, el fabricante indica acotar la excepción *"to specific Teams device resource accounts"*, o sea a las cuentas de los equipos de sala que realmente lo necesitan.

Las demás medidas que publica Microsoft, en orden de esfuerzo:

1. **Configurar políticas antiphishing y Safe Links** en [Defender para Office 365](/producto/microsoft-defender-for-office-365), y activar la purga automática de hora cero (ZAP), que retira mensajes maliciosos de las casillas después de entregados.
2. **Alertar sobre la creación de reglas de bandeja sospechosas.** Es el movimiento clásico posterior al compromiso: el atacante crea una regla que oculta o reenvía correos para que la víctima no vea las respuestas de la estafa en curso.
3. **Vigilar el registro de dispositivos después de una autenticación anómala**, y las peticiones inusuales a la API de Microsoft Graph. Microsoft lista las tres como indicadores.
4. **Usar las consultas de búsqueda avanzada** que el fabricante publicó junto al análisis, para revisar hacia atrás clics sospechosos y entregas de phishing que hayan llegado.

Y la medida que no es técnica y probablemente sea la más efectiva: **decirle al equipo la regla del código**. Una frase, en la próxima reunión. Si alguien te manda un código y te pide que lo escribas, no lo escribas, sea quien sea el que lo manda.

El resto del encuadre sobre por qué tener MFA no alcanza está en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), la puesta en marcha ordenada en [configurar MFA en una PyME](/guia/configurar-mfa-pyme-fin-de-semana), las opciones del catálogo en [MFA y autenticación](/productos/mfa-y-autenticacion) y en [email y antiphishing](/productos/email-y-antiphishing), y los términos en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes sobre EvilTokens y el código de dispositivo

### Si nunca escribí mi contraseña, ¿cómo entraron a mi cuenta?

Porque no necesitaban tu contraseña. El flujo de código de dispositivo permite que un aparato pida acceso y que vos lo apruebes desde otro lado donde ya estás autenticado. El atacante ocupa el lugar del aparato: pide el acceso, te manda el código con una excusa, y cuando vos lo escribís y confirmás en la página real de Microsoft, quien queda con la sesión iniciada es él. Tu contraseña nunca pasó por ningún lado, y eso es lo que hace que el ataque no deje las señales que uno busca.

### ¿El MFA no tendría que haberlo frenado?

No, y no porque el MFA esté mal configurado. El segundo factor cumplió exactamente su función: verificar que sos vos. El problema es que lo que estabas autorizando no era lo que creías. El MFA protege contra alguien que intenta entrar en tu lugar; no protege contra vos mismo aprobando, de buena fe, un pedido ajeno. Por eso la defensa acá es de política —bloquear el flujo— y no de factor adicional.

### Desarticularon EvilTokens. ¿Ya pasó el peligro?

No. Lo que cayeron son los sitios y la infraestructura de un servicio concreto, y hay dos detenidos en el Reino Unido, pero la técnica que usaban no era propia: es el abuso de una función estándar de Microsoft 365. Cualquier otro grupo puede montar lo mismo mañana, y Microsoft señala que EvilTokens fue el primero en ofrecerlo a escala, no el único posible. La política de bloqueo sigue siendo igual de necesaria que la semana pasada.

### ¿Cómo sé si alguien de mi empresa cayó?

Los indicadores que publicó Microsoft son tres: registro de un dispositivo nuevo después de una autenticación que se vea rara, peticiones anómalas a la API de Microsoft Graph, y reglas de bandeja de entrada creadas después del compromiso. La tercera es la más fácil de revisar sin herramientas: entrá a las reglas de correo de las cuentas y buscá alguna que mueva, marque como leídos o reenvíe mensajes y que el dueño de la cuenta no reconozca.

### Somos diez personas, ¿de verdad nos van a apuntar?

Las cifras de Microsoft sugieren que la pregunta está mal planteada. Fueron más de 12.000 casillas repartidas en más de 10.000 organizaciones en unos siete meses: alrededor de una casilla por empresa. Con ese reparto, no hubo una selección cuidadosa de objetivos grandes, hubo alcance. Y para el fraude que describe Microsoft —identificar quién autoriza pagos y redactar el mensaje suplantando a alguien de confianza— una empresa de diez personas no es un objetivo peor, porque suele tener menos controles entre el pedido y la transferencia.
