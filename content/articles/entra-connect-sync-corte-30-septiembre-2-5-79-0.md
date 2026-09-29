---
title: "Entra Connect Sync deja de sincronizar el 30 de septiembre"
subtitle: Si el servidor que sincroniza tu Active Directory con Microsoft 365 está por debajo de la versión 2.5.79.0, mañana deja de funcionar. Y las alertas que avisarían de la falla se rompen con el mismo corte.
excerpt: Microsoft corta la sincronización de las versiones viejas de Entra Connect el 30 de septiembre. Qué deja de propagarse a la nube y cómo saber en qué versión estás.
type: news
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-09-29
updated: 2026-09-29
products:
  - microsoft-entra-id
meta_title: "Entra Connect Sync: corte el 30 de septiembre"
meta_description: "Si tu Entra Connect Sync está por debajo de 2.5.79.0, deja de sincronizar el 30 de septiembre. Qué deja de llegar a la nube y por qué no vas a recibir aviso."
---

Esta no es una vulnerabilidad y no tiene CVE, pero tiene una fecha más precisa que la mayoría de los avisos de seguridad, y es **mañana**. La documentación de Microsoft lo dice en un recuadro marcado como actualización obligatoria: *"All synchronization services in Microsoft Entra Connect Sync will stop working on **September 30, 2026** if you're not on at least version 2.5.79.0"*.

Entra Connect Sync es el servicio que corre en un servidor propio y mantiene alineado el **Active Directory local** con **Microsoft 365**. Si se detiene, nada se rompe con estruendo: simplemente la nube se queda con la foto del día anterior, y a partir de ahí empieza a envejecer.

## Qué deja de llegar a Microsoft 365 cuando Connect Sync se detiene

```svg
<svg viewBox="0 0 680 235" role="img" aria-label="Qué ocurre cuando Entra Connect Sync se detiene. Un cambio hecho en el Active Directory local, como cambiar una contraseña, dar de baja a un empleado o crear una cuenta nueva, pasa normalmente por el servicio de sincronización y llega a Microsoft 365. Si el servicio está detenido, el cambio queda solamente en el servidor local y la nube conserva el dato anterior. Las tres consecuencias concretas son: la contraseña vieja sigue funcionando en Microsoft 365 porque la nube conserva el último hash sincronizado y el cambio nuevo no llegó; la cuenta del empleado dado de baja sigue activa en la nube, con su correo y sus archivos accesibles; y las cuentas nuevas no aparecen, así que la persona que entró a trabajar no puede iniciar sesión. Ninguna de las tres le muestra un error a nadie, y por eso el problema se descubre tarde">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">El cambio se hace igual, pero no sale del servidor</text>

  <rect x="20" y="42" width="180" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="42" width="180" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="110" y="66" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">En tu AD local</text>
  <text x="110" y="85" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">cambiás una clave,</text>
  <text x="110" y="99" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">das una baja, creás un alta</text>

  <path d="M206 73 L244 73" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="225" y="66" text-anchor="middle" font-size="16" font-weight="700" fill="#e23a3a">✕</text>

  <rect x="250" y="42" width="180" height="62" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="250" y="42" width="180" height="62" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="340" y="66" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">Connect Sync</text>
  <text x="340" y="85" text-anchor="middle" font-size="10" font-weight="700" fill="#e23a3a">detenido</text>
  <text x="340" y="99" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">desde el 30 de septiembre</text>

  <path d="M436 73 L474 73" stroke="currentColor" stroke-width="1.3" opacity="0.4" stroke-dasharray="4 3"/>

  <rect x="480" y="42" width="180" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="480" y="42" width="180" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="570" y="66" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">Microsoft 365</text>
  <text x="570" y="85" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">se queda con</text>
  <text x="570" y="99" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.85">el dato anterior</text>

  <text x="20" y="136" font-size="11" font-weight="700" fill="currentColor">Las tres consecuencias concretas</text>
  <text x="20" y="158" font-size="10.5" fill="currentColor" opacity="0.9">La clave vieja sigue sirviendo en Microsoft 365: la nube conserva el último hash sincronizado.</text>
  <text x="20" y="178" font-size="10.5" fill="currentColor" opacity="0.9">El empleado dado de baja sigue activo en la nube, con su correo y sus archivos.</text>
  <text x="20" y="198" font-size="10.5" fill="currentColor" opacity="0.9">Las cuentas nuevas no aparecen: quien entró a trabajar no puede iniciar sesión.</text>
  <text x="20" y="224" font-size="11" font-weight="600" fill="#e23a3a">Ninguna de las tres le muestra un error a nadie.</text>
</svg>
```

La segunda es la que importa en términos de seguridad, y conviene decirla sin eufemismos: **una persona que dejó la empresa mantiene el acceso a su correo y a sus archivos**, porque la baja se hizo en el directorio local y nunca cruzó a la nube. En el papel está dada de baja. En la práctica entra.

La primera tiene una vuelta contraintuitiva que vale explicar. Si la sincronización de hash de contraseñas se detiene, **la clave que sigue funcionando en Microsoft 365 es la vieja**, no la nueva: la nube conserva el último hash que recibió, y el cambio que hizo la persona en la red local no llegó. El síntoma que aparece en la mesa de ayuda es "cambié la contraseña y ahora Outlook no me deja", y la salida rápida y equivocada es decirle que use la anterior.

Es el mismo problema de fondo que el sitio discutió en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza): el control existe y está bien configurado, pero lo que protege no es lo que uno cree.

## Las 6 alertas de Connect Health que se rompen son las que avisarían del corte

Acá está el hallazgo que cambia cómo hay que tratar este corte, y sale de leer la tabla de impacto de Microsoft con atención. El corte no afecta solo a la sincronización: también afecta a **Entra Connect Health**, que es el componente que vigila y avisa. Y la lista de alertas que dejan de funcionar es específica.

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Las ocho alertas de Entra Connect Health para Connect Sync que dejan de funcionar con el corte del 30 de septiembre de 2026, según la tabla de impacto de Microsoft. Seis de las ocho son alertas de falla: conexión con Entra ID fallida por error de autenticación, la sincronización de hash de contraseñas dejó de funcionar, la exportación a Entra ID se detuvo al alcanzar el umbral de borrado accidental, el latido de la sincronización de hash de contraseñas se salteó en los últimos 120 minutos, el servicio de sincronización no puede arrancar por claves de cifrado inválidas, y el servicio de sincronización no está corriendo porque venció la credencial de la cuenta de servicio de Windows. Las otras dos son alertas de recursos: uso alto de CPU y consumo alto de memoria. Para los agentes de Active Directory Domain Services y de Active Directory Federation Services, Microsoft indica que se afectan todas las alertas. La conclusión es que la sincronización falla y al mismo tiempo se rompe el mecanismo que avisaría de esa falla">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Las 8 alertas de Connect Health que se caen con el corte</text>

  <rect x="20" y="38" width="620" height="122" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="38" width="620" height="122" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="58" font-size="11" font-weight="700" fill="#e23a3a">6 de 8 son alertas de falla, o sea las que avisarían del corte</text>
  <text x="38" y="78" font-size="10" fill="currentColor" opacity="0.9">Conexión con Entra ID fallida por error de autenticación</text>
  <text x="38" y="94" font-size="10" fill="currentColor" opacity="0.9">La sincronización de hash de contraseñas dejó de funcionar</text>
  <text x="38" y="110" font-size="10" fill="currentColor" opacity="0.9">La exportación a Entra ID se detuvo</text>
  <text x="38" y="126" font-size="10" fill="currentColor" opacity="0.9">El latido de la sincronización de hash se salteó en los últimos 120 minutos</text>
  <text x="38" y="142" font-size="10" fill="currentColor" opacity="0.9">El servicio no arranca por claves inválidas, o no corre por credencial vencida</text>

  <rect x="20" y="172" width="620" height="38" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="172" width="620" height="38" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="190" font-size="10.5" font-weight="700" fill="currentColor">Las otras 2 son de recursos</text>
  <text x="38" y="204" font-size="10" fill="currentColor" opacity="0.9">Uso alto de CPU y consumo alto de memoria</text>

  <text x="20" y="232" font-size="11" font-weight="600" fill="#e23a3a">Y en los agentes de AD DS y AD FS, Microsoft dice que se afectan todas.</text>
  <text x="20" y="246" font-size="9.5" fill="currentColor" opacity="0.7">Fuente: tabla de impacto de la documentación de Microsoft Entra, consultada el 29 de septiembre de 2026.</text>
</svg>
```

Leído junto, el resultado es incómodo: **la sincronización falla y el mecanismo que avisaría de que falló se rompe con el mismo corte**. Entre las seis alertas que caen está, literalmente, *"Password Hash Synchronization has stopped working"*. La que sonaría.

Eso descarta la estrategia que uno tomaría por defecto con un cambio de este tipo, que es esperar a ver si algo se queja. Nada se va a quejar. Si el servidor está por debajo de la versión mínima, la única forma de enterarse es ir a mirar, y hay que ir a mirar hoy.

## No es una versión, son tres niveles: 2.5.79.0 alcanza por 23 días

La mayoría de los resúmenes de este cambio dicen "actualizá a 2.5.79.0". Es cierto y es insuficiente, y se ve cruzando la tabla de versiones del propio Microsoft con la fecha del corte.

```svg
<svg viewBox="0 0 680 225" role="img" aria-label="Los tres niveles de versión de Entra Connect frente al corte del 30 de septiembre de 2026, según la tabla de versiones de Microsoft. Nivel uno, por debajo de la versión 2.5.79.0: la sincronización deja de funcionar el 30 de septiembre. Nivel dos, exactamente la versión 2.5.79.0, publicada el 1 de septiembre de 2025: pasa el corte, pero su soporte termina el 23 de octubre de 2026, o sea 23 días después, y además esa versión tiene un problema documentado por Microsoft, que usar la interfaz del Synchronization Service Manager puede hacer fallar la renovación automática de certificados, corregido en la versión 2.6.1.0. Nivel tres, la versión actual 2.6.92.0, publicada el 23 de septiembre de 2026: es la que conviene instalar y todavía no tiene fecha de fin de soporte. La conclusión es que actualizar al mínimo resuelve el corte y deja otro vencimiento en tres semanas">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Dónde estás y hasta cuándo te sirve</text>

  <rect x="20" y="38" width="620" height="46" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="38" width="620" height="46" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="57" font-size="10.5" font-weight="700" fill="#e23a3a">Por debajo de 2.5.79.0</text>
  <text x="38" y="75" font-size="10.5" fill="currentColor" opacity="0.9">La sincronización se detiene el 30 de septiembre. Es el caso urgente.</text>

  <rect x="20" y="96" width="620" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="96" width="620" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="115" font-size="10.5" font-weight="700" fill="currentColor">Exactamente 2.5.79.0, del 1 de septiembre de 2025</text>
  <text x="38" y="132" font-size="10.5" fill="currentColor" opacity="0.9">Pasa el corte, pero su soporte termina el 23 de octubre: 23 días después.</text>
  <text x="38" y="149" font-size="10.5" fill="currentColor" opacity="0.9">Y tiene un problema documentado con la renovación automática de certificados.</text>

  <rect x="20" y="170" width="620" height="40" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="170" width="620" height="40" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="189" font-size="10.5" font-weight="700" fill="currentColor">2.6.92.0, del 23 de septiembre de 2026: la actual</text>
  <text x="38" y="204" font-size="10.5" fill="currentColor" opacity="0.9">Sin fecha de fin de soporte asignada. Es la que conviene instalar.</text>
</svg>
```

Los dos detalles del nivel del medio salen de la documentación y merecen quedar claros. El primero: en la tabla de versiones, la **2.5.79.0 termina su soporte el 23 de octubre de 2026**, o sea **23 días** después del corte que estamos hablando. Quien actualice justo al mínimo resuelve mañana y se queda con otro vencimiento en tres semanas.

El segundo es más concreto. La ficha de esa versión trae una advertencia de Microsoft: no usar la interfaz del *Synchronization Service Manager* en esa versión, porque hacerlo *"may cause the Microsoft Entra Connect wizard and automatic certificate renewal to fail"*, y el problema está corregido a partir de la 2.6.1.0. O sea que la versión mínima tiene una falla documentada que afecta la renovación automática de certificados.

Conclusión práctica: **el objetivo no es 2.5.79.0, es la versión actual**, que al 29 de septiembre es la 2.6.92.0, publicada el 23 de septiembre.

## Dos páginas de Microsoft dan fechas distintas para la misma versión

Una discrepancia que conviene anotar, de las que este sitio viene marcando. La página de endurecimiento dice que la 2.5.79.0 salió en **mayo de 2025**: *"In May 2025, we released this version with a back-end service change that hardens our services"*. La tabla de versiones, en cambio, le asigna como fecha de publicación el **1 de septiembre de 2025**, y agrega una línea de estado más específica todavía: publicada para descarga el 1 de septiembre de 2025, con actualización automática de las instalaciones existentes a partir del 4 de septiembre.

**No puedo resolver con estos dos documentos cuál de las dos fechas es la correcta**, así que no elijo una. Sí anoto lo que se ve en la misma tabla, como lectura mía y no como afirmación de Microsoft: la versión **2.5.3.0 sí se publicó el 27 de mayo de 2025**, lo que ofrece una explicación posible para la confusión, si el cambio de servicio de fondo salió con esa y el número de versión quedó mal atribuido.

Lo que cambia según cuál sea la fecha no es menor. Si la versión mínima está disponible desde septiembre de 2025, el plazo real para acomodarse fue de unos **13 meses**, no de 16. Y hay un dato que enmarca a quién le queda pendiente esto: Microsoft viene actualizando automáticamente estos servidores **desde septiembre de 2023**, o sea hace 3 años, y aclara que quienes recibieron esa actualización automática no están afectados. Los que sí lo están son dos grupos: los que la desactivaron a propósito, y aquellos a quienes —en palabras de la propia documentación— *"autoupgrade failed"*. Ese segundo grupo no eligió nada, y es exactamente el que no sabe que tiene el problema.

## ¿A quién afecta el corte de Entra Connect Sync?

A toda organización que tenga un **servidor propio corriendo Entra Connect Sync** para sincronizar su Active Directory local con Microsoft 365, y que esté por debajo de la 2.5.79.0.

Ese escenario es muy común en la empresa mediana de la región: el Active Directory de siempre en un servidor de la oficina, Microsoft 365 para correo, y una identidad única que se mantiene con este servicio en el medio. Es la infraestructura que se instaló una vez, funcionó, y nadie volvió a mirar. Que sea aburrida es justamente el problema: **nadie la vigila porque nunca falla**.

Alcanza también a **Entra Connect Health**, con sus propios mínimos: los agentes de Connect Sync, de AD DS y de AD FS necesitan la versión 4.5.2466.0 o superior.

Un caso que vale nombrar aparte: si tu infraestructura la administra un proveedor de sistemas, esto es suyo, no tuyo, y conviene preguntarlo hoy por escrito. Es el argumento de [la nota sobre N-able y los proveedores de IT](/noticia/n-able-n-central-cve-2026-86218-proveedor-it): tercerizar la administración no terceriza la consecuencia.

## ¿Quién puede ignorar el corte de Entra Connect Sync?

Quien no tenga servidor de sincronización. Si tus identidades viven solamente en la nube, sin Active Directory local, no hay Connect Sync que actualizar y esto no te toca.

Quien use **Entra Cloud Sync**, que es el cliente que corre desde la nube y que Microsoft recomienda para quien sea elegible, tampoco tiene este corte.

Y quien ya esté en 2.5.79.0 o superior pasa el 30 de septiembre sin novedades. Con la salvedad del nivel del medio: si estás exactamente en el mínimo, no terminaste, tenés tres semanas.

## ¿Cómo sé en qué versión está mi Entra Connect Sync?

Tres formas, de la más directa a la más indirecta:

1. **En el servidor**, abrí *Microsoft Entra Connect* y la versión aparece en la pantalla inicial del asistente. También está en Panel de control, en la lista de programas instalados.
2. **Con PowerShell** en el servidor, consultando la versión del producto instalado. Es lo más rápido si administrás varios servidores.
3. **En el portal de Entra**, mirando la **hora de la última sincronización** de tu inquilino. Esto no te dice la versión, pero responde la pregunta que importa: si esa marca quedó vieja, ya se cortó.

Y una comprobación que no depende de ninguna versión, útil desde el 1 de octubre en adelante: **tomá una cuenta de prueba, cambiale la contraseña en el directorio local, esperá unos minutos e intentá entrar a Microsoft 365 con la nueva.** Si entra con la vieja y no con la nueva, la sincronización está detenida. Es la prueba más corta que existe para esto, y no requiere herramientas.

Acordate además de revisar el **servidor de escenario** si tenés uno en espera para promover: el corte aplica a cada servidor activo, no solo al que está en producción.

## ¿Qué hago si tengo un servidor de Entra Connect Sync?

Hoy, en este orden:

1. **Averiguá la versión.** Es una pantalla. Si es 2.5.79.0 o superior, respirá y pasá al punto 4.
2. **Si está por debajo, actualizá a la versión actual**, la 2.6.92.0, y no al mínimo. Resolvés el corte de mañana y el vencimiento del 23 de octubre en un solo movimiento.
3. **Verificá los requisitos antes de actualizar:** la documentación pide **.NET Framework 4.7.2** y **TLS 1.2**. Si el servidor es viejo, esto es lo que puede trabarte, así que miralo antes y no durante.
4. **Actualizá también los agentes de Connect Health** a 4.5.2466.0 o superior, para recuperar las alertas. Sin eso, la próxima falla vuelve a ser silenciosa.
5. **Si la sincronización ya se cortó**, la documentación es clara en que no hay daño permanente: *"all synchronization services will fail until you upgrade to the latest version"*. Se reanuda al actualizar. Lo que sí hay que revisar después es qué quedó desalineado en el medio, y el primer lugar donde mirar son las bajas de personal que se hicieron durante el corte.
6. **Evaluá pasar a Cloud Sync.** Es la recomendación explícita de Microsoft para quien sea elegible, y elimina el servidor del medio, que es el que genera este tipo de fecha.

El resto del encuadre sobre identidad en Microsoft 365 está en [Entra ID](/producto/microsoft-entra-id), y el sitio ya cubrió otros dos cambios de este mismo producto: [el retiro del MFA por SMS](/noticia/microsoft-entra-id-retira-mfa-por-sms) y [la falla con puntaje 10 que no fue explotada](/noticia/entra-id-cvss-10-no-fue-explotado). Para el lado de la autenticación, [configurar MFA en una PyME](/guia/configurar-mfa-pyme-fin-de-semana) y el catálogo de [MFA y autenticación](/productos/mfa-y-autenticacion). Los términos, en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes sobre el corte de Entra Connect Sync

### Si no actualizo a tiempo, ¿pierdo datos o se borran cuentas?

No. Según la documentación, lo que ocurre es que los servicios de sincronización fallan hasta que actualices, y se reanudan cuando lo hagas. No hay borrado ni pérdida: hay desactualización. El riesgo no está en los datos sino en el tiempo que la nube pase con información vieja, sobre todo si en ese período hubo bajas de personal o cambios de contraseña que nunca cruzaron.

### ¿Cómo hago para que mis usuarios no noten nada?

Si actualizás antes de mañana, no van a notar nada, porque la actualización de Connect Sync no interrumpe el acceso a Microsoft 365: lo que se detiene durante la actualización es la propagación de cambios, no el inicio de sesión. Conviene igual hacerlo fuera del horario de mayor movimiento y verificar una sincronización completa después.

### Estoy en 2.5.79.0 exactamente. ¿Tengo que hacer algo o no?

Pasás el corte del 30 de septiembre, así que no es urgente hoy. Pero esa versión termina su soporte el 23 de octubre de 2026, o sea tres semanas después, y tiene una advertencia documentada: usar la interfaz del Synchronization Service Manager en esa versión puede hacer fallar la renovación automática de certificados, algo corregido a partir de la 2.6.1.0. Si ya vas a tocar el servidor, andá a la versión actual y no quedás con una tarea abierta.

### Tengo la actualización automática activada. ¿Estoy cubierto?

Probablemente, y Microsoft dice que quienes fueron actualizados automáticamente no están afectados. Pero la propia documentación nombra dos situaciones que dejan a un servidor atrás: haber desactivado la actualización automática, y que la actualización automática haya fallado. El segundo caso no deja aviso, así que tener la función activada no reemplaza mirar el número de versión una vez.

### ¿Esto significa que Microsoft está dejando de ofrecer la sincronización con Active Directory?

No. Este corte es un requisito mínimo de versión por un cambio de seguridad del lado del servicio, no el retiro de la sincronización como función. Lo que sí hay es una dirección marcada: Microsoft recomienda a quien sea elegible pasar a Entra Cloud Sync, que funciona desde la nube, y dice que las funciones nuevas van hacia ese lado. Conviene leerlo como lo que es, una señal sobre dónde conviene estar en un par de años, no como un apagón anunciado.
