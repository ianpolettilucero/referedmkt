---
title: "Microsoft Entra ID retira el MFA por SMS"
subtitle: El 1 de septiembre las llaves de acceso pasan a ser el método por defecto, y el 1 de febrero de 2027 Microsoft deja de entregar SMS y llamadas. Sin exclusión posible.
excerpt: Microsoft retira el segundo factor por SMS y llamada de Entra ID. Desde el 1 de septiembre empuja a registrar llaves de acceso; en febrero bloquea.
type: news
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-08-30
updated: 2026-08-30
products:
  - microsoft-entra-id
  - yubikey-5-series
  - cisco-duo
  - okta-workforce-identity
meta_title: "Microsoft Entra ID retira el MFA por SMS"
meta_description: "Microsoft retira SMS y llamada como segundo factor en Entra ID. Qué pasa el 1 de septiembre, qué el 1 de febrero de 2027 y cómo saber si te toca."
---

Microsoft va a dejar de entregar SMS y llamadas telefónicas como segundo factor en Entra ID, el sistema de identidad detrás de Microsoft 365. Lo anuncia en [su documentación oficial](https://learn.microsoft.com/en-us/entra/identity/authentication/concept-sms-voice-retirement) con dos fechas y una frase que no deja lugar a interpretación: *"There is no opt out from this February 1 behavior. It will be enforced for all tenants."*

El **1 de septiembre**, o sea pasado mañana, las llaves de acceso pasan a ser la experiencia por defecto. El **1 de febrero de 2027** el SMS deja de existir como capacidad nativa.

Si tu empresa usa Microsoft 365 y el segundo factor de tu equipo es un código que llega por mensaje de texto, esto te toca. Es la configuración más común en una PyME, porque es la que no exige instalar nada.

| Dato | Valor |
|---|---|
| Qué se retira | Entrega de SMS y llamadas provista por Microsoft |
| Producto | Microsoft Entra ID, nube pública |
| Empieza | 1 de septiembre de 2026 |
| Retiro completo | 1 de febrero de 2027 |
| ¿Se puede evitar? | No después del 1 de febrero, para ningún inquilino |
| Alcanza también a | Restablecimiento de contraseña por autoservicio |
| Fuera de alcance | Azure AD B2C; Entra External ID va el año que viene |
| Costo de migrar a llaves de acceso | Ninguno |

---

## Qué pasa el 1 de septiembre en Microsoft Entra ID

No es el apagón: es el empujón. Según el aviso, los usuarios habilitados para SMS o voz en la política de métodos de autenticación —o en la configuración heredada de MFA— **quedan habilitados automáticamente para llaves de acceso**, y la campaña de registro pasa a estado *Microsoft Managed* apuntándoles.

La próxima vez que uno de esos usuarios inicie sesión y complete el segundo factor, le aparece un aviso para registrar una llave de acceso. El detalle que baja la urgencia del primer día: *"By default, users will have unlimited snoozes of the nudge prompt."* Se puede posponer sin límite.

Nada deja de funcionar el 1 de septiembre. El SMS sigue llegando.

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Cronología del retiro: el 1 de septiembre de 2026 las llaves de acceso pasan a ser el método por defecto y aparece un aviso que se puede posponer sin límite; el 18 de septiembre Microsoft publica las opciones de proveedores de telecomunicaciones; el 30 de octubre se pueden contratar; el 1 de febrero de 2027 el SMS provisto por Microsoft queda retirado y el aviso pasa a ser bloqueante">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Cinco meses entre el empujón y el bloqueo</text>

  <rect x="440" y="106" width="200" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M40 120 L650 120" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="80" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">1 sep 2026</text>
  <text x="80" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">aviso que se pospone</text>
  <path d="M80 84 L80 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="80" cy="120" r="5" fill="currentColor"/>

  <circle cx="240" cy="120" r="5" fill="currentColor"/>
  <path d="M240 128 L240 138" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="240" y="152" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">18 sep 2026</text>
  <text x="240" y="168" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">se publican los proveedores</text>

  <text x="410" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">30 oct 2026</text>
  <text x="410" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">ya se pueden contratar</text>
  <path d="M410 84 L410 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="410" cy="120" r="5" fill="currentColor"/>

  <circle cx="580" cy="120" r="5" fill="#e23a3a"/>
  <path d="M580 128 L580 138" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <text x="650" y="152" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">1 feb 2027</text>
  <text x="650" y="168" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">bloqueante, sin excepciones</text>

  <text x="20" y="204" font-size="11.5" font-weight="600" fill="currentColor">La franja marcada es la ventana en que el SMS ya no llega por Microsoft</text>
</svg>
```

---

## Por qué el 1 de febrero de 2027 es la fecha que importa

Ahí el aviso deja de ser un aviso. Microsoft lo describe así: los usuarios cuyo único método disponible sea SMS o voz **van a tener que registrar una llave de acceso durante el inicio de sesión para poder seguir entrando**, y ese pedido *"will be blocking"*.

La diferencia entre las dos fechas es toda la historia, y conviene tenerla clara antes de decidir cuánto correr.

```svg
<svg viewBox="0 0 660 210" role="img" aria-label="Comparación entre las dos fechas: el 1 de septiembre de 2026 el aviso para registrar una llave de acceso se puede posponer sin límite y el SMS sigue funcionando; el 1 de febrero de 2027 el pedido es bloqueante, no se puede posponer y no hay exclusión para ningún inquilino">
  <rect x="20" y="26" width="600" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="26" width="600" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.5"/>
  <text x="36" y="50" font-size="12.5" font-weight="700" fill="currentColor">1 de septiembre de 2026 · el empujón</text>
  <text x="36" y="74" font-size="11" fill="currentColor" opacity="0.85">Aviso posponible sin límite. El SMS sigue llegando. Nada se rompe.</text>

  <rect x="20" y="112" width="600" height="66" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="112" width="600" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="136" font-size="12.5" font-weight="700" fill="#e23a3a">1 de febrero de 2027 · el bloqueo</text>
  <text x="36" y="160" font-size="11" fill="currentColor" opacity="0.85">Registrar la llave o no entrás. Sin exclusión para ningún inquilino.</text>

  <text x="20" y="199" font-size="11.5" font-weight="600" fill="currentColor">Cinco meses para que nadie se entere un lunes a la mañana</text>
</svg>
```

Hay una salida intermedia para el tramo de septiembre a febrero: se puede posponer la habilitación automática con una llamada a Microsoft Graph, poniendo `passkeyDynamicMigration` en `true` sobre la política de métodos de autenticación. Requiere el permiso `Policy.ReadWrite.AuthenticationMethod`. Sirve para ordenar la migración con calma, y **no cambia nada del 1 de febrero**: el aviso lo dice dos veces.

---

## Qué opciones quedan si tu equipo depende del SMS

Son tres, y conviene ver el costo de cada una antes de elegir.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Las tres salidas posibles: migrar a llaves de acceso sin costo adicional, contratar un proveedor de telecomunicaciones propio desde el 30 de octubre con costo por mensaje que varía según proveedor y región, o no hacer nada y quedar con el bloqueo de inicio de sesión en febrero de 2027">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Tres salidas, y una de ellas no es una salida</text>

  <rect x="16" y="48" width="200" height="86" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="16" y="48" width="200" height="86" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="116" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Llaves de acceso</text>
  <text x="116" y="96" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">sin costo adicional</text>
  <text x="116" y="114" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">es lo que recomienda</text>
  <text x="116" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">el propio fabricante</text>

  <rect x="240" y="48" width="200" height="86" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="240" y="48" width="200" height="86" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="340" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Proveedor propio</text>
  <text x="340" y="96" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">desde el 30 de octubre</text>
  <text x="340" y="114" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">se paga por mensaje,</text>
  <text x="340" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">según región</text>

  <rect x="464" y="48" width="200" height="86" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="464" y="48" width="200" height="86" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="564" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="#e23a3a">No hacer nada</text>
  <text x="564" y="96" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">el 1 de febrero cada</text>
  <text x="564" y="114" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">usuario se topa con</text>
  <text x="564" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">el bloqueo</text>

  <text x="340" y="174" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Migrar no cuesta plata; seguir con SMS, sí</text>
</svg>
```

La opción del proveedor propio está pensada para quien tenga *"a genuine regulatory or operational need"*, y tiene precio: la propia documentación aclara que sí hay costo, que varía por proveedor y región y que suele cobrarse por mensaje. Migrar a llaves de acceso, en cambio, no agrega costo.

---

## ¿A quién afecta el retiro del SMS en Microsoft Entra ID?

A tu empresa si usa Microsoft 365 y alguien tiene el SMS o la llamada como método de segundo factor. En una PyME suele ser casi todo el mundo, porque es lo que se activa cuando nadie quiere pedirle a diez personas que instalen una aplicación.

Alcanza también al **restablecimiento de contraseña por autoservicio**: si tus usuarios recuperan la clave con un código por mensaje, eso entra en el mismo retiro.

Hay dos grupos que suelen quedar afuera del radar y sí están en alcance: los usuarios invitados de otra organización, y las cuentas de servicio o buzones compartidos que alguien configuró con un número de teléfono.

## ¿Quién puede ignorar el retiro del SMS en Entra ID?

Quien no use Microsoft 365 ni [Entra ID](/producto/microsoft-entra-id): si la identidad de tu empresa está en Google Workspace o en otro proveedor, esto no aplica.

Tampoco cambia nada para los usuarios que ya entran con llave de acceso, con Windows Hello para empresas o con otro método resistente a la suplantación. Y si tu segundo factor es una aplicación de autenticación —el código que rota cada 30 segundos, o la notificación que aprobás—, **no es lo que se retira**: lo que se va es el SMS y la llamada.

Fuera de alcance quedan también Azure AD B2C, y por ahora los inquilinos de Entra External ID, que según el aviso van el año que viene con anuncio propio. El calendario aplica a la nube pública.

## ¿Cómo sé cuántos de mis usuarios dependen del SMS?

Microsoft publicó un script de PowerShell propio para contarlos, en [entra-sms-voice-usage-analyzer](https://github.com/microsoft/entra-sms-voice-usage-analyzer). Necesitás uno de estos roles: lector global, administrador de directivas de autenticación o lector de seguridad.

El criterio para saber si te toca es directo: **cualquier resultado distinto de cero significa que estás en alcance.**

Sin correr nada, desde el centro de administración de Entra podés mirar *Métodos de autenticación* y ver qué usuarios tienen SMS o voz habilitados en la política. La configuración heredada de MFA cuenta igual, así que si tu inquilino es viejo y nunca migraste a la política nueva, mirá los dos lados.

## ¿Qué hago si mi empresa usa MFA por SMS?

1. **Contá a cuánta gente le toca**, con el script o desde el panel. Sin ese número no se puede planificar nada, y suele ser más alto de lo que uno cree.
2. **No apures el 1 de septiembre.** El aviso se pospone sin límite y el SMS sigue funcionando. Lo único que conviene hacer esta semana es avisarle al equipo que va a aparecer una pantalla nueva, para que nadie crea que es un intento de estafa. Una pantalla inesperada que pide registrar algo es exactamente lo que enseñamos a desconfiar.
3. **Elegí el método de reemplazo antes de octubre.** La llave de acceso en el teléfono cubre a la mayoría sin costo. Para las cuentas de administrador conviene ir a una llave física; los números reales de esa decisión están en el [análisis de las YubiKey 5](/resena/analisis-yubikey-5-series-costo-total-argentina), y el resto de las opciones, en [MFA y autenticación](/productos/mfa-y-autenticacion).
4. **Resolvé aparte los casos que no tienen teléfono propio.** El mostrador, el depósito, el turno que comparte una máquina. Son los que se descubren tarde y los que arman el problema el día del bloqueo.
5. **Poné el 1 de febrero de 2027 en el calendario, no en la memoria.** Con la migración hecha antes de fin de año, esa fecha pasa sin que nadie la note. El orden para encarar el despliegue está en [configurar MFA en una PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).

El motivo de fondo lo dice Microsoft sin vueltas: *"SMS and voice are among the most vulnerable authentication methods available today"*. Es lo mismo que explica [cómo funciona el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa) y lo que ya estaba en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza): el segundo factor por SMS frena el robo de contraseña, no al que te copia la sesión.

Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre el retiro del SMS en Entra ID

### ¿Se me van a bloquear las cuentas el 1 de febrero de 2027?

No, y el aviso lo aclara expresamente. Nadie queda afuera de su cuenta: lo que aparece es un pedido de registro de llave de acceso que no se puede saltear. El usuario registra la llave en ese momento y sigue entrando. La molestia es real, pero es una pantalla, no una puerta cerrada. Lo que sí conviene evitar es que le toque a veinte personas el mismo lunes.

### Uso la aplicación Microsoft Authenticator. ¿Me afecta?

No. Lo que se retira es el SMS y la llamada telefónica. Los códigos de la aplicación y las notificaciones de aprobación no entran en este anuncio. Tampoco entran los métodos de MFA externos, salvo que el mismo usuario tenga además SMS o voz habilitados.

### Tengo un requisito regulatorio que exige un canal telefónico. ¿Qué hago?

Existe la salida del proveedor propio: contratar la telefonía por tu cuenta a través de la tienda de Microsoft, en vez de que la entregue Microsoft. Las opciones se publican el 18 de septiembre y se pueden configurar desde el 30 de octubre. Tiene costo por mensaje según proveedor y región, así que conviene documentar cuál es el requisito concreto antes de tomar ese camino: la documentación lo enmarca en una necesidad regulatoria u operativa genuina, no en la comodidad.

### ¿Puedo frenar todo esto hasta que tenga tiempo?

Hasta el 1 de febrero de 2027, sí y de forma acotada. Se pospone la habilitación automática y la campaña de registro con una llamada a Microsoft Graph que pone `passkeyDynamicMigration` en `true`. Del 1 de febrero en adelante no hay ninguna forma de posponerlo: el aviso repite que la exigencia aplica a todos los inquilinos sin excepción.

### Somos cinco personas. ¿Vale la pena preocuparse ahora?

Con cinco personas la migración es cuestión de una tarde, y esa es la razón para hacerla ahora y no en enero. El problema de dejarlo para el final no es el tamaño del equipo sino la fecha: el 1 de febrero de 2027 cae lunes. El bloqueo empieza a la misma hora en que todos abren el correo, y es un escenario perfectamente evitable con cinco meses de aviso.

### Un cliente me pregunta si esto nos afecta. ¿Qué le respondo?

Con el número: cuántos usuarios tienen SMS o voz habilitados hoy, qué método de reemplazo elegiste y para cuándo está previsto terminar. Si ya migraron, la respuesta es que el cambio no los alcanza. El formato para ese tipo de respuestas está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
