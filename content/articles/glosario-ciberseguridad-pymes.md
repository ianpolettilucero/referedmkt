---
title: "Glosario de ciberseguridad para PyMEs"
subtitle: Los términos que aparecen en un cuestionario de seguridad, una póliza de ciberseguro o una reunión con el proveedor de IT, explicados en dos frases y sin humo.
excerpt: MFA, EDR, ransomware, DMARC, passkey, CVE, Zero Trust y treinta términos más de ciberseguridad, explicados en dos frases y enlazados a la guía que profundiza cada uno.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-18
updated: 2026-08-18
products: []
meta_title: "Glosario de ciberseguridad para PyMEs"
meta_description: "MFA, EDR, ransomware, DMARC, passkey, CVE, Zero Trust y treinta términos más de ciberseguridad, explicados en dos frases y enlazados a la guía que profundiza."
---

Cada término de esta lista aparece, tarde o temprano, en un lugar donde tenés que tomar una decisión: un cuestionario de un cliente, la letra chica de un ciberseguro, una cotización de tu proveedor de IT. Este glosario es para que ninguno te agarre desprevenido.

No es un diccionario técnico. Cada entrada está explicada en dos o tres frases, desde el punto de vista de una PyME que tiene que decidir qué hacer, y enlaza a la guía que lo desarrolla cuando la hay. Usalo como índice: saltá al término que te trajo hasta acá.

---

## Identidad y acceso

### MFA (autenticación multifactor)

Pedir algo más que la contraseña para entrar: un código, una notificación en el teléfono, una llave física. Es el control que más ataques frena por menos plata, y el primero que pide cualquier auditoría. Cómo activarlo en toda la empresa está en [configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).

### Passkey

Una credencial que reemplaza a la contraseña, atada al dispositivo y al sitio real. No se puede robar por phishing porque nunca viaja un secreto por la red. Es la forma más accesible de autenticación resistente a phishing, y en muchas plataformas no cuesta nada. Por qué importa está en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza).

### FIDO2 / WebAuthn

El estándar técnico detrás de las passkeys y las llaves físicas. Su propiedad clave es el *origin binding*: la credencial solo funciona en el dominio real para el que se creó, así que una página falsa no puede usarla. Es lo que corta el [robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa) de raíz.

### SSO (inicio de sesión único)

Entrar a muchas aplicaciones con una sola identidad, en vez de una contraseña por servicio. Reduce la cantidad de credenciales que tu gente tiene que manejar y centraliza el control de accesos, pero concentra el riesgo: la cuenta de SSO tiene que estar especialmente protegida.

### SCIM (aprovisionamiento automático)

El mecanismo que da de alta y de baja usuarios automáticamente cuando entran o salen de la empresa. Importa porque el error más común de acceso es el exempleado que conserva la cuenta: el SCIM lo cierra solo. Es una de las diferencias que separan los planes de empresa en la [comparativa de gestores de contraseñas](/comparativa/comparativa-1password-vs-bitwarden-vs-dashlane).

### PAM (gestión de accesos privilegiados)

Controles extra para las cuentas de administrador, que son las que más daño hacen si caen: sesiones registradas, permisos mínimos, credenciales que rotan. Cada vez más aseguradoras lo piden como requisito.

### Gestor de contraseñas

Una bóveda cifrada donde el equipo guarda credenciales fuertes y distintas por servicio, en vez de reutilizar la misma en todos lados. Cuál conviene según tu caso está en la [comparativa de 1Password, Bitwarden y Dashlane](/comparativa/comparativa-1password-vs-bitwarden-vs-dashlane) y en la [categoría de gestión de contraseñas](/productos/gestion-contrasenas).

---

## Amenazas y ataques

### Phishing

El correo o mensaje que se hace pasar por alguien confiable para que hagas clic, entregues una credencial o pagues una factura falsa. Es la puerta de entrada de la mayoría de los incidentes en PyMEs, y por eso reforzar el correo rinde tanto.

### Ingeniería social

Manipular a una persona para que haga algo inseguro, en lugar de atacar la tecnología. El phishing es su forma más común, pero también incluye la llamada telefónica del falso soporte técnico o el falso gerente que pide una transferencia urgente.

### BEC (fraude del correo corporativo)

Un ataque donde el delincuente se mete en —o imita— una cuenta de correo legítima para desviar un pago o robar datos, sin malware de por medio. Es de los que más plata mueven, y se combate con seguridad de correo y con procesos de verificación de pagos. La [comparativa de seguridad de email](/comparativa/comparativa-seguridad-email-pymes) baja a producto.

### Adversario en el medio (AiTM) / robo de sesión

El ataque que saltea el MFA sin robar la contraseña: un servidor intermedio roba la sesión ya iniciada después de que te autenticaste. Es el motivo por el que el MFA por SMS ya no alcanza. La anatomía completa está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).

### Ransomware

Software que cifra tus archivos y pide un rescate para devolvértelos, muchas veces con robo de datos de por medio. La defensa que de verdad te devuelve la operación no es pagar: es un backup inmutable y probado. El contexto para LATAM está en la [guía de ciberseguridad para PyMEs](/guia/guia-ciberseguridad-pymes-latam-2026).

### Zero-day

Una vulnerabilidad que se explota antes de que exista un parche, así que estar al día no te protege. Contra los zero-day, lo que vale es reducir la superficie expuesta y poder detectar el ataque, no solo parchear.

---

## Vulnerabilidades

### CVE

El identificador único de una vulnerabilidad conocida, con el formato CVE-año-número. Sirve para que todos hablen exactamente de la misma falla sin confusión. Aparecen todo el tiempo en la [sección de noticias](/noticias).

### CVSS

El puntaje de gravedad de una vulnerabilidad, de 0 a 10. Ojo: mide qué tan grave *sería* si alguien la explotara, no si te toca a vos. El fabricante y el organismo que lo asigna a veces difieren, así que conviene no decidir solo por ese número.

### KEV (catálogo de vulnerabilidades explotadas)

La lista que publica la agencia de ciberseguridad de Estados Unidos con las vulnerabilidades que hay evidencia de que se están explotando *ahora*. A diferencia del CVSS, no estima el riesgo: confirma que ya lo están usando. Si algo está en el KEV, se parchea sin discutir.

### Parche (patching)

La actualización que cierra una vulnerabilidad. La velocidad importa cada vez más: hay fallas que se explotan a las pocas horas de publicarse, así que un proceso que dependa de revisión manual llega tarde.

### Superficie de ataque

Todo lo tuyo que un atacante puede alcanzar desde afuera: servicios publicados en internet, cuentas, aplicaciones. Reducirla —apagar lo que no se usa, sacar de internet lo que no necesita estar— suele rendir más que perseguir cada vulnerabilidad.

---

## Defensa de los equipos

### Antivirus de nueva generación (NGAV)

El antivirus moderno, que detecta por comportamiento y no solo por firmas de malware conocido. Es el piso, no el techo: frena lo conocido, pero no te deja ver ni responder en el parque completo.

### EDR (detección y respuesta en el endpoint)

La evolución del antivirus: además de bloquear, graba lo que pasó y te deja aislar un equipo y reconstruir por dónde entró el ataque. Requiere que alguien mire la consola. Cuándo se justifica el salto está en [EDR o antivirus](/guia/edr-o-antivirus-cuando-se-justifica-pyme).

### XDR

Un EDR que además cruza señales de correo, identidad y nube, no solo del equipo. Útil cuando ya tenés varias piezas del mismo fabricante y querés que conversen entre sí.

---

## Red y acceso remoto

### VPN

Un túnel cifrado para conectarse a la red de la empresa desde afuera. Funciona, pero la VPN publicada a internet es un blanco frecuente, y una vez adentro suele dar acceso a demasiado. Las alternativas modernas están en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).

### Zero Trust / ZTNA

El modelo de "no confiar en nadie por estar dentro de la red": cada acceso se verifica por identidad y contexto, y se da el permiso mínimo. El ZTNA es su forma aplicada al acceso remoto, y reemplaza a la VPN dando acceso a una aplicación por vez, no a toda la red.

---

## Correo

### SPF, DKIM y DMARC

Los tres registros que evitan que suplanten tu dominio para mandar correo en tu nombre. SPF y DKIM son la evidencia; DMARC es la sentencia que le dice al mundo qué hacer con un correo que falla. Configurarlos es de lo más barato que hay: la [guía paso a paso de SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso) no cuesta licencias.

---

## Backup y resiliencia

### Backup inmutable

Una copia que no se puede borrar ni modificar, ni siquiera con credenciales de administrador. Es lo que hace que el ransomware no cifre también tus respaldos. Sin inmutabilidad, un backup diario de un sistema comprometido guarda el problema, no la solución. La [categoría de backup y recuperación](/productos/backup-y-recuperacion) cubre las herramientas.

### Regla 3-2-1

La receta clásica de respaldo: tres copias, en dos tipos de medio, una fuera del sitio. Los suscriptores de seguros le agregaron "una inmutable y cero restauraciones sin verificar". La prueba de restauración es la parte que casi nadie hace y la que más importa.

### Plan de respuesta a incidentes

El documento —de dos páginas alcanza— que dice quién decide, a quién se llama y en qué orden cuando algo pasa. Lo pide cualquier cuestionario y cualquier aseguradora, y sirve sobre todo a las 3 de la mañana del día del incidente.

---

## Seguridad ofensiva

### Pentesting

Una prueba autorizada donde alguien ataca tus sistemas para encontrar los agujeros antes que un delincuente. Qué es y cómo funciona está en [qué es el pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos), y qué exigirle a un proveedor en [qué certificaciones pedir](/guia/certificaciones-pentesting-que-exigir-proveedor).

### Red team

Un ejercicio ofensivo más amplio y sigiloso que un pentest: simula un adversario real con un objetivo concreto, para probar no solo la tecnología sino también la detección y la respuesta. Cuándo conviene está en [red teaming](/guia/red-teaming-que-es-cuando-contratarlo).

### Bug bounty

Un programa donde investigadores externos reportan vulnerabilidades a cambio de una recompensa. Es más para empresas con producto propio que para la PyME promedio, pero explica de dónde salen muchas de las fallas que después aparecen en las noticias.

---

## Gestión y cumplimiento

### Ciberseguro

Una póliza que cubre las consecuencias económicas de un incidente. Hoy la aseguradora te audita los controles antes de venderla: qué exigen y cómo llegar preparado está en [ciberseguro para PyME](/guia/ciberseguro-pyme-que-exigen-las-aseguradoras).

### Cuestionario de seguridad

La planilla de decenas o cientos de preguntas que un cliente grande te manda para evaluar si trabajar con vos es seguro. Cómo responderlo sin mentir ni perder el contrato está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

---

## Preguntas frecuentes

### ¿Por dónde empiezo si todo esto es nuevo?

Por la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), que ordena las prioridades. Después, los dos controles que más rinden y que casi todo el mundo pide primero son [MFA](/guia/configurar-mfa-pyme-fin-de-semana) y un backup inmutable probado.

### ¿Este glosario reemplaza a las guías?

No: es el índice. Cada término está resumido en dos frases para que sepas de qué se habla, y enlaza a la guía que lo desarrolla cuando querés implementarlo de verdad. Sirve para orientarte, no para hacer.

### ¿Por qué algunos términos no tienen enlace?

Porque todavía no escribimos la guía profunda de ese tema. La definición alcanza para entender de qué se trata; cuando publiquemos la guía correspondiente, el término va a enlazar a ella. La [sección de noticias](/noticias) también suma términos nuevos a medida que aparecen.
