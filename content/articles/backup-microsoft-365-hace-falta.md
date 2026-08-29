---
title: "Backup de Microsoft 365: por qué hace falta"
subtitle: El punto exacto del calendario donde la retención de Microsoft deja de alcanzar, modelado día por día sobre documentación pública, y los precios en dólares.
excerpt: Microsoft retiene tus mails borrados 14 días y tus archivos 93 —cuando la cuota lo permite—. El calendario de qué se pierde y cuánto cuesta evitarlo.
type: guide
status: published
category: backup-y-recuperacion
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-20
products: [veeam-data-platform, acronis-cyber-protect, rubrik-security-cloud]
---

Microsoft retiene los mails que un usuario borra definitivamente **14 días por defecto** —máximo 30, y solo si un admin lo sube a mano con `Set-Mailbox -RetainDeletedItemsFor`— y los archivos de SharePoint y OneDrive **93 días contados desde el borrado original**, no 93 por cada papelera. Ni siquiera esos 93 están garantizados: la papelera de segunda etapa tiene una cuota fija y, cuando se llena, purga sola lo más viejo. Fuente: documentación de Microsoft Learn y artículos de soporte de Microsoft 365, consultados en julio de 2026.

Puestos en un calendario, esos números cambian de significado. Lo que sigue es un **escenario modelo**: una PyME de 60 buzones en Microsoft 365 Business Premium, sin backup de terceros, atravesada por la secuencia de compromiso de identidad que describen los playbooks de Microsoft Defender y los informes de respuesta a incidentes del rubro. No es un caso atendido ni uno que nos hayan contado. Cada paso está anclado a documentación pública; los volúmenes, las horas y los montos son supuestos explícitos, para que puedas rehacer la cuenta con los tuyos. Es el patrón que explica por qué los 93 días se convierten en 33.

## La cronología del escenario, hora por hora

La empresa del modelo migró todo a SharePoint dieciocho meses antes, sin backup de terceros. La migración vino con la idea de que la nube ya venía respaldada. Es la suposición más cara del catálogo.

### Día 0, 09:14 — inicio de sesión válido

El registro de inicios de sesión de Entra ID muestra una autenticación exitosa de la cuenta del gerente comercial desde una IP de un proveedor de VPS en Países Bajos. El campo de resultado de MFA no dice que hubo un segundo factor: dice `previouslySatisfied`, que Microsoft documenta como *MFA requirement satisfied by claim in the token*. Traducido: nadie tocó el teléfono, porque el token de sesión ya venía firmado y el recurso lo aceptó sin volver a preguntar.

Nueve minutos antes, a las 09:05, la misma cuenta había iniciado sesión desde la IP de la oficina. En este patrón las dos sesiones conviven durante días: la del usuario y la del atacante, ambas legítimas para el servicio.

Esto es un ataque adversary-in-the-middle de manual: el usuario entra a una página que proxea el login real de Microsoft, completa su MFA de buena fe, y el atacante se queda con la cookie de sesión que Microsoft acaba de emitir. El MFA funcionó y se lo saltearon igual: funcionó, no sirvió. El desarrollo está en [por qué tener MFA activado no alcanza](/guia/ya-tenes-mfa-y-no-alcanza). El único factor que rompe este patrón de raíz es uno atado al dominio, que no se puede proxear: es el argumento a favor de [las llaves de seguridad FIDO2 de la línea YubiKey 5](/resena/analisis-yubikey-5-series-costo-total-argentina), y el motivo por el que un [despliegue de MFA de fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) tiene que elegir bien el método, no solo activar la casilla.

### Días 0 a 3 — reglas de bandeja y reenvío

Paso siguiente del patrón: una regla de bandeja de entrada con nombre irrelevante —un punto, tres puntos o directamente vacío— que mueve a una carpeta que nadie mira todo mensaje que contenga "factura", "transferencia", "CBU" o "pago", y lo marca como leído. El destino habitual es Suscripciones RSS o Elementos eliminados. Microsoft publica un playbook de clasificación de alertas dedicado a reglas de manipulación de bandeja, y la técnica está catalogada en MITRE ATT&CK como *Email Hiding Rules* (T1564.008). Las carpetas concretas son las que se repiten en los reportes públicos de respuesta a incidentes: son el caso frecuente, no una medición.

El reenvío automático a dominios externos, en el modelo, está bloqueado a nivel de organización. Se saltea con una segunda regla que reenvía mensaje por mensaje a una casilla gratuita: el bloqueo de forwarding externo y el bloqueo de reglas de reenvío son dos controles distintos, y muchas organizaciones tienen uno solo activo. Andá a verificar el tuyo antes de seguir leyendo.

Supongamos 214 mensajes filtrados en tres días, entre ellos tres cadenas de negociación con un cliente grande. Ahí el fraude al CEO deja de ser anécdota y se vuelve negocio: el compromiso de correo corporativo fue en 2025 la **segunda categoría de pérdida más alta** de todo el informe anual del IC3 del FBI, con **USD 3.046 millones** reportados, detrás únicamente del fraude de inversión (USD 8.649 millones). Lo que se roba no es un archivo. Es una conversación en curso, con contexto suficiente para pedir un cambio de CBU sin que suene raro. Si tu dominio todavía no está firmado, [configurar SPF, DKIM y DMARC paso a paso](/guia/configurar-spf-dkim-dmarc-paso-a-paso) es la tarde mejor invertida del trimestre.

### Día 4, 02:40 — borrado

Ochenta y siete minutos de actividad. En orden:

- Se borran 38.412 archivos del OneDrive del gerente comercial (61 GB).
- Se borra la biblioteca de documentos del sitio de SharePoint del área Comercial: 240 GB, incluida la carpeta de licitaciones con nueve años de pliegos y pliegos respondidos.
- Se vacía la papelera de primera etapa desde la interfaz web, en dos tandas.
- Se purga la carpeta de Elementos eliminados del buzón y después la subcarpeta de recuperables, con la opción de purga que Outlook le ofrece al propio usuario.

No hace falta ransomware. Nada queda cifrado, y ahí está el problema: no hay nota de rescate, no hay proceso raro consumiendo CPU, no hay nada que un EDR pueda marcar. El atacante usa las funciones normales del producto con las credenciales de un empleado real. Es, literalmente, un usuario ordenando su OneDrive.

### Día 14 — alguien nota

Una administrativa abre el sitio para buscar un pliego de 2023 y la carpeta no está. Pregunta por Teams. Le dicen que pruebe al día siguiente. Ese día perdido es el motor de todo lo que viene después.

Ese mismo día 14 se cumple el plazo por defecto de retención de elementos recuperables del buzón. Los mails purgados el día 4 dejan de ser recuperables por cualquier vía —ni el usuario, ni el admin, ni eDiscovery— porque la cuenta no tenía retención ni litigation hold aplicado. Nadie toma nota de eso el día 14. La cuenta se paga el día 41.

### Día 33 — la papelera se vacía sola

Este es el detalle que convierte el caso en algo distinto de "se olvidaron de restaurar a tiempo".

Los 240 GB borrados del sitio Comercial están en la papelera de segunda etapa, con 93 días de reloj corriendo desde el día 4. Deberían estar disponibles hasta el día 97. Pero el día 33 el área de Marketing sube al mismo site collection un archivo histórico de campañas de 410 GB, y limpia otro tanto. La papelera de segunda etapa de SharePoint Online tiene una cuota fija del **200% de la cuota de almacenamiento del site collection**, no es configurable, y cuando se supera **el servicio empieza a borrar automáticamente los elementos más antiguos** hasta hacer lugar para los más nuevos. Está documentado por Microsoft y repetido en los artículos de soporte de Microsoft 365.

Los elementos más antiguos son las licitaciones.

Los 93 días no son una promesa: son un techo que solo se alcanza si nadie más en ese site collection borra nada grande mientras tanto. Ese comportamiento vive en un artículo de soporte y no en la página de producto, que es la clase de letra chica que conviene leer antes de firmar.

### Día 41 — el ticket

El día 41 es cuando, en este patrón, alguien finalmente abre el pedido de restauración con soporte de Microsoft. La respuesta no depende del ánimo del ingeniero que atienda: depende de lo que el producto documenta. El servicio conserva el contenido eliminado durante las ventanas de retención publicadas y el cliente puede restaurarlo por sí mismo dentro de esas ventanas. Fuera de ellas, la documentación de Microsoft no describe ningún mecanismo de restauración puntual de contenido de usuario. Si tu caso es de borde, la única respuesta que vale es la que te den por escrito en el ticket, no la que leas acá.

No es mala voluntad. Es el producto que compraron: el modelo de responsabilidad compartida que Microsoft publica pone la custodia de los datos del lado del cliente. Ese texto cambia con el tiempo y varía según el acuerdo que hayas firmado, así que si vas a citarlo en una reunión, citá la versión vigente de tu contrato y no la paráfrasis de un artículo.

### Día 52 — el balance del modelo

Qué se recupera, en este escenario, y de dónde: nada sale de Microsoft 365, todo sale de copias que existían por accidente fuera de él.

- **23.400 archivos de OneDrive** (61% del total), rescatados del caché de sincronización de un notebook que estaba con la sincronización pausada desde el día 2 y por eso nunca replicó los borrados. Pura suerte, y la suerte no es una estrategia de recuperación.
- **La carpeta de licitaciones de 2024 y 2025**, porque alguien tenía una copia local desactualizada.
- **Los mails salientes del gerente**, reconstruidos desde los buzones de los destinatarios internos.

Qué no vuelve nunca:

- **Nueve años de historial de versiones** del sitio Comercial. Lo que se recupera vuelve como versión única, sin el rastro de quién cambió qué.
- **1.140 mensajes recibidos** entre 2019 y el día 0, que solo existían en ese buzón.
- **Las tres cadenas** con el cliente grande, que además ya están en manos de un tercero.
- **Once días hábiles** de dos personas reconstruyendo carpetas, más el honorario de un forense externo. En el modelo se carga a USD 4.200; poné el número que te cotizarían a vos, la conclusión no cambia.

Un contrapunto honesto con la cifra grande del rubro: Sophos calculó en 2026 un costo medio de recuperación de **USD 1,7 millones** sin contar rescate, un 11% más que el año anterior. Ese número sale mayormente de organizaciones grandes y no es aplicable a una PyME de 60 personas: sirve por la escala, no como pronóstico. Lo que sí aplica del mismo informe es el otro dato: **el 66% de los casos con datos cifrados se recuperaron vía backups**, doce puntos más que en 2025.

## Se termina el escenario, empieza el manual

Lo que viene no son supuestos: son días, tablas y dólares verificables.

## Qué garantiza Microsoft, con los días al lado

El modelo se llama responsabilidad compartida y está escrito. Microsoft garantiza que el servicio esté disponible y que la infraestructura sea resiliente. Vos sos responsable de tus datos, de tus configuraciones y de tus identidades.

| Elemento | Qué conserva Microsoft por defecto | Desde cuándo corre el reloj |
|---|---|---|
| Elementos eliminados del buzón (recuperables) | 14 días, ampliable a 30 por admin | Desde el borrado por el usuario |
| Buzón de un usuario eliminado | 30 días | Desde el borrado de la cuenta |
| Archivos en papelera de OneDrive/SharePoint | 93 días **totales** entre 1.ª y 2.ª etapa | Desde el borrado original |
| Papelera de 2.ª etapa | Cuota del 200% del site collection; purga automática por antigüedad al excederse | — |
| OneDrive de un usuario eliminado | 30 días por defecto, configurable de 30 a 3.650 | Desde el borrado de la cuenta |
| Sitio de SharePoint eliminado | 93 días en sitios eliminados | Desde el borrado del sitio |

Tres precisiones que se confunden todo el tiempo:

**Retención no es backup.** Una política de retención de Purview evita que algo se borre. No te devuelve el estado del buzón al martes pasado a las 15:00. Es un candado, no una máquina del tiempo.

**Litigation hold tampoco es backup.** Preserva los elementos recuperables de forma indefinida mientras el hold esté activo, y sirve para lo que fue diseñado: litigios y auditorías. Para recuperar el contenido tenés que pasar por eDiscovery: buscar, exportar a PST y reimportar, buzón por buzón, con la licencia que corresponda. Es un flujo de exportación legal, no una restauración masiva, y usarlo como plan de recuperación para 60 buzones es inviable por diseño, no por lentitud.

**El versionado sí es útil y es gratis.** Si el ataque fuera ransomware que cifra archivos en OneDrive, el historial de versiones salvaría el día. Contra el borrado, no: cuando se borra el archivo se borran todas sus versiones.

Y hay una cuarta cosa que Microsoft directamente no cubre: los cambios de configuración. Reglas de bandeja, permisos de SharePoint, políticas de acceso condicional. Ninguna herramienta de retención te dice cómo estaba tu tenant el día 0.

## La contrafáctica: el mismo calendario, con copia de seguridad

Mismo escenario, mismas fechas, tres configuraciones distintas. La pregunta no es si el atacante entra —entra— sino qué día se detiene el daño.

| Hito del calendario | Sin backup de terceros | Con backup de terceros diario | Con backup inmutable + alertas |
|---|---|---|---|
| Día 0, 09:14 | Sesión abierta, sin señal | Igual | Igual |
| Días 0-3 | Reglas ocultas, 214 mails filtrados | Igual | Igual |
| Día 4, 02:40 | Borrado de 38.412 archivos + 240 GB | El borrado ocurre, pero el punto del día 3 ya está fuera del tenant | Igual, y el pico de borrado dispara alerta a las 03:10 |
| Día 5 | Nada | Nada | **Cuentas suspendidas, restauración iniciada** |
| Día 14 | Mails irrecuperables | Restaurables desde el punto del día 3 | Ya restaurado el día 5 |
| Día 33 | Papelera de 2.ª etapa purgada por cuota | Irrelevante: la copia no vive en SharePoint | Irrelevante |
| Día 41 | Microsoft responde que no puede | No se abre ticket | No se abre ticket |
| Día 52 | 61% recuperado, historial perdido | **Restauración completa con historial de versiones** | Cerrado en el día 6 |
| Daño residual | 11 días-persona + USD 4.200 de forense | ~1 día-persona | ~4 horas |

La columna del medio ya resuelve el grueso del problema y cuesta lo que vas a ver en un minuto. La tercera suma dos cosas: inmutabilidad y detección.

**Qué es un backup inmutable.** Es una copia escrita en un almacenamiento que no admite modificación ni borrado durante un período definido, ni siquiera por un administrador con credenciales completas. Se implementa con Object Lock en almacenamiento compatible con S3, con repositorios Linux endurecidos, o con sistemas de archivos append-only. La prueba de fuego: si un atacante que roba tu cuenta de administrador del backup puede borrar las copias, tu backup no es inmutable, es solo remoto. Que los grupos de ransomware buscan la consola de backup temprano es algo que reportan de forma consistente los informes de respuesta a incidentes del rubro, y tiene toda la lógica.

**La regla 3-2-1**, que el fotógrafo Peter Krogh formuló en *The DAM Book* en 2005 y sigue vigente veinte años después: 3 copias de cada dato, en 2 tipos de medio distintos, con 1 fuera del sitio. La actualización que conviene usar hoy es 3-2-1-1-0: se le suma 1 copia inmutable u offline y 0 errores en la verificación de restauración. Para Microsoft 365 eso significa, como mínimo: los datos en producción, una copia en el proveedor de backup, y esa copia con retención inmutable activa.

## Cuánto cuesta un respaldo en la nube para empresas

Precios de lista publicados y rangos de entrada de canal, en julio de 2026. Marco cuáles son públicos y cuáles no, porque eso te dice mucho del vendedor.

| Producto | Modelo | Precio | ¿Tarifa pública? |
|---|---|---|---|
| Microsoft 365 Backup (nativo) | Por GB de contenido protegido | **USD 0,15 /GB/mes** (Microsoft Learn, 2026) | Sí |
| Veeam Data Cloud for Microsoft 365 | Por usuario | **USD 2,63 a 3,50 /usuario/mes** según nivel | Sí, en la página de compra |
| [Veeam Data Platform](/producto/veeam-data-platform) | Licencia universal (VUL), por workload, en packs de 5 o 10 | **USD 250 a 450 por workload/año** según nivel, según revendedores | Parcial: el pack se lista en canal, el on-prem se cotiza |
| [Acronis Cyber Protect](/producto/acronis-cyber-protect) | Por workload o por GB consumido | **Desde USD 85/año** por workload, cifra de agregadores | No: Acronis cotiza por partner |
| [Rubrik Security Cloud](/producto/rubrik-security-cloud) | Por capacidad (BETB), suscripción con mínimo de 3 años | Sin precio de entrada publicado ni cifra de terceros que pueda citarte con fuente abierta | No, cotización cerrada |

Qué implica que no publiquen tarifa: que no tenés contra qué medir la cotización que llega, y que dos empresas parecidas pueden terminar con números distintos por el mismo producto sin que ninguna de las dos sepa por qué. En este rubro el precio de lista existe para la nube y desaparece en cuanto hay capacidad o hardware de por medio. Pedí dos cotizaciones y compará el costo total a 36 meses, no el primer año: el descuento de arranque y el precio de renovación son dos números distintos y conviene pedir los dos por escrito.

**Dónde está el punto de corte**, con números concretos:

- **Hasta 25 buzones y menos de 500 GB.** El backup nativo de Microsoft a USD 0,15/GB/mes son unos USD 75/mes por 500 GB, sin sumar proveedor externo. La contra: los datos siguen dentro del mismo tenant. Contra un borrado accidental sirve; contra un administrador global comprometido, que es el escenario de arriba, no.
- **De 25 a 200 buzones.** Acá [Veeam Data Platform](/producto/veeam-data-platform) es la opción por defecto. A USD 3/usuario/mes, 60 buzones son USD 180/mes o USD 2.160/año, con retención propia fuera del tenant. Va por defecto por la tarifa pública por usuario, que deja presupuestar sin llamar a nadie. Es un criterio de compra, no una medición de adopción.
- **Más de 200 buzones o mucho volumen de archivos.** El licenciamiento por usuario deja de ser eficiente cuando el problema es capacidad. [Rubrik Security Cloud](/producto/rubrik-security-cloud) licencia por capacidad y su documentación describe la inmutabilidad como parte del diseño del producto y no como una casilla que alguien se olvida de prender. Como no publica precio de entrada, no puedo ponerle número; lo que sí digo, y es una opinión, es que un licenciamiento por capacidad con mínimo de tres años difícilmente cierre para 60 buzones.
- **Si querés consolidar antimalware y backup en un agente.** [Acronis Cyber Protect](/producto/acronis-cyber-protect), que los agregadores ubican desde USD 85/año por workload, es la opción de MSP y de empresas sin equipo de IT, y su argumento de venta es la consolidación: un solo agente para backup y antimalware. La objeción: si ya tenés un EDR que te gusta —hay tres comparados en [Bitdefender vs Kaspersky vs ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset)— la consolidación deja de ser un argumento.

El resto del catálogo, con fichas y precios, está en el [directorio de backup y recuperación](/productos/backup-y-recuperacion).

Un dato regional que va contra el reflejo de esta guía: según el ESET Security Report 2026, el **82%** de las organizaciones latinoamericanas declara tener backup. Es el segundo control más extendido de la región, apenas debajo del firewall (85%) y muy por encima del MFA (57%). O sea que el control no falta. Lo que falta es que alguien lo haya restaurado alguna vez, y eso no se compra.

## El ensayo de restauración trimestral, en seis pasos

Un backup que nunca restauraste es una hipótesis. Este es el ejercicio que corresponde correr cada tres meses. Bloqueá media mañana, hacelo con cronómetro y anotá los tiempos reales al lado de los objetivos.

1. **Elegí el objetivo sin avisar.** Un buzón, un sitio de SharePoint y un OneDrive, tomados al azar de la lista, no los de siempre. *Objetivo: 5 minutos.*
2. **Restaurá un ítem único a su ubicación original.** Un mail de hace más de 60 días, con adjunto. Si tardás más de esto, el problema es la interfaz, no el backup. *Objetivo: 10 minutos.*
3. **Restaurá una carpeta completa a una ubicación alternativa.** Nunca sobre la original en un ensayo. Verificá que el historial de versiones haya vuelto, no solo la última versión. *Objetivo: 30 minutos.*
4. **Restaurá un buzón entero a un buzón nuevo** y contá los ítems contra el original. Un delta del 2% ya amerita un ticket con el proveedor. *Objetivo: 90 minutos para un buzón de 40 GB.*
5. **Probá el borrado desde una cuenta de administrador comprometida simulada.** Intentá borrar un punto de restauración con la cuenta de admin del backup. Si podés, la inmutabilidad no está activa. Es el paso que más se saltea. *Objetivo: 15 minutos.*
6. **Escribí el número.** Tiempo de restauración por buzón y por 100 GB. Ese es tu RTO real, y el único dato que vas a poder darle a la dirección durante un incidente. *Objetivo: 10 minutos.*

Si querés meterlo en un plan más amplio de controles, el orden de implementación por capas está en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y el control que habría cortado la parte del fraude está desarrollado en la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes).

Un límite explícito: todo lo de arriba se apoya en documentación de producto, precios de lista y estadística publicada. Por encima de los 200 buzones, o con decenas de TB, el cálculo por usuario deja de describir la realidad y no hay tarifas públicas confiables para reemplazarlo. Pedí cotización antes de creerle a cualquier regla general, incluidas las de esta guía.

## Preguntas frecuentes

### ¿Hace falta hacer copia de seguridad de Microsoft 365?

Sí, y el motivo es de calendario, no de opinión. Microsoft conserva los elementos eliminados de un buzón 14 días por defecto y los archivos borrados de SharePoint u OneDrive 93 días desde el borrado original, con la papelera de segunda etapa sujeta a una cuota que puede purgar antes por antigüedad. Fuera de esas ventanas, el soporte de Microsoft no restaura contenido de usuario, y el propio acuerdo de servicios recomienda que el cliente mantenga su copia. Si tu tiempo de detección promedio supera esos plazos —y en borrados sigilosos suele superarlos—, no tenés a dónde ir.

### ¿Cuánto cuesta un backup en la nube para empresas?

Entre USD 2,63 y 3,50 por usuario y por mes en el modelo SaaS de Veeam Data Cloud, y USD 0,15 por GB y por mes en el servicio nativo de Microsoft 365 Backup (precios de lista, julio de 2026). Para una empresa de 60 buzones eso son entre USD 158 y 210 mensuales por el modelo por usuario. Acronis se ubica desde USD 85 por workload al año según agregadores de precios, y Rubrik directamente no publica precio de entrada: se cotiza por capacidad, con mínimo de suscripción de tres años. En los dos casos hay que pedir cotización.

### ¿Cada cuánto hay que hacer copias de seguridad en una empresa?

Para Microsoft 365, mínimo diario; cuatro veces por día es lo habitual en los planes actuales y no cuesta más, porque el precio va por usuario o por capacidad, no por frecuencia. La pregunta correcta no es la frecuencia sino el RPO: cuánto trabajo estás dispuesto a rehacer. Con backup diario, la respuesta es "hasta 24 horas". Para un área de facturación eso puede ser demasiado. Sumale una verificación de restauración por trimestre, sin excepción.

### ¿Qué es un backup inmutable y en qué se diferencia de uno cifrado?

Son cosas distintas y se confunden seguido. Un backup encriptado protege la confidencialidad: si alguien se lleva la copia, no puede leerla. Un backup inmutable protege la integridad y la disponibilidad: nadie puede borrarla ni alterarla durante el período de retención, ni siquiera con credenciales de administrador. Contra ransomware, el que importa es el inmutable, porque el atacante no quiere leer tu backup: quiere borrarlo. Lo ideal es tener los dos, cifrado en tránsito y en reposo más inmutabilidad con Object Lock o filesystem append-only.

### ¿La papelera de reciclaje de SharePoint no alcanza como copia de seguridad?

No, por tres razones medibles. Los 93 días se cuentan desde el borrado original y no se reinician al pasar a la segunda etapa. La papelera de segunda etapa tiene una cuota del 200% del site collection, no configurable, y purga automáticamente los elementos más antiguos cuando la supera. Y cualquiera con permisos suficientes puede vaciarla, que es exactamente lo que hace un atacante con la cuenta de un empleado.

### ¿Veeam o Acronis para una PyME?

Si tu problema es Microsoft 365 y ya tenés antivirus resuelto, Veeam: publica tarifa por usuario, que te deja presupuestar sin llamar a nadie, y documenta inmutabilidad para el repositorio. Si no tenés equipo de IT y querés un solo agente para backup y antimalware, o trabajás con un MSP que ya lo usa, Acronis. Rubrik no es la respuesta acá: está pensado para capacidad en entornos grandes y por debajo de 200 buzones no cierra por precio. Esta recomendación se apoya en modelos de licenciamiento y precios publicados, no en una prueba comparativa de los tres productos.
