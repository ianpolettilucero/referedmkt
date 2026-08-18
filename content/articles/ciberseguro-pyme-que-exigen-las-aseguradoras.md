---
title: "Ciberseguro para PyME: qué exigen las aseguradoras"
subtitle: Contratar la póliza dejó de ser llenar un formulario. Hoy la aseguradora te audita los controles antes de cubrirte, y si falta uno, sube la prima o te rechaza.
excerpt: El ciberseguro se volvió una auditoría técnica: MFA, EDR, backup inmutable y plan de incidentes son requisitos, no recomendaciones. Qué piden y cómo llegar preparado.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-18
updated: 2026-08-18
products:
  - microsoft-defender-for-endpoint-p2
  - veeam-data-platform
  - microsoft-entra-id
  - yubikey-5-series
meta_title: "Ciberseguro para PyME: qué exigen las aseguradoras"
meta_description: "El ciberseguro se volvió una auditoría: MFA, EDR, backup inmutable y plan de incidentes son requisitos, no recomendaciones. Qué piden y cómo llegar preparado."
---

Hasta hace unos años, contratar un seguro de ciberriesgo era llenar un formulario, pagar la prima y guardar la póliza en un cajón. Eso se terminó. Hoy, antes de cubrirte, la aseguradora te hace una auditoría técnica de tus controles de seguridad, y si te falta alguno, la respuesta no es "pagás un poco más": muchas veces es **te rechazo la póliza**.

Ese cambio tiene una consecuencia que conviene entender bien, porque le da vuelta la lógica de la compra: **la lista de requisitos de la aseguradora es, en la práctica, la mejor guía de qué implementar primero.** Alguien que analiza pérdidas reales todos los días ya hizo el trabajo de decidir qué controles evitan las facturas más caras. Esa lista es gratis y podés usarla tengas o no la intención de contratar el seguro.

Es el mismo mecanismo que con los [cuestionarios de seguridad de los clientes](/guia/cuestionario-seguridad-cliente-como-responder): un tercero con poder sobre tu negocio te obliga a demostrar controles. Solo que acá el tercero cobra por el riesgo, así que su lista es todavía más despiadadamente práctica.

---

## Por qué la región es un caso aparte

El contexto de LATAM explica por qué esto llegó y por qué importa. Según el [informe de ciberriesgo de Aon para América Latina](https://www.aon.com/cyber-risk-report/es-la/cyber-risk-is-a-corporate-risk-latin-america-responds), las PyMEs son el **99,5% del mercado** de la región, y esa combinación de baja inversión en seguridad y enorme cantidad de empresas chicas convierte a LATAM en un blanco ideal. El mismo informe cuenta 1.498 ataques de ransomware en la región en 2023, y ubica la preparación promedio de las empresas latinoamericanas en **2,59 sobre 4**, entre los niveles "básico" y "gestionado".

Traducido: la mayoría de las empresas de la región están por debajo del nivel que una aseguradora necesita para cubrirlas con comodidad. Por eso la póliza se volvió un examen, no un trámite. La buena noticia es que el examen tiene el temario publicado.

---

## Los controles que piden, y qué cubre cada uno en el catálogo

Los requisitos convergen entre aseguradoras porque todas miran las mismas estadísticas de pérdida. Estos son los que aparecen como mínimos, y —no por casualidad— son las mismas categorías que cubre este sitio. Si una aplicación no puede demostrarlos, se rechaza o enfrenta primas más altas y límites más bajos.

| Control que exigen | Qué quieren ver | Dónde está en el sitio |
|---|---|---|
| MFA, idealmente resistente a phishing | Segundo factor en accesos remotos y cuentas de privilegio | [Configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) |
| EDR en los endpoints | Cobertura amplia, no un antivirus por máquina | [Cuándo se justifica el salto a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme) |
| Backup inmutable y probado | Copias aisladas que un atacante no pueda borrar, con restauración testeada | [Categoría de backup y recuperación](/productos/backup-y-recuperacion) |
| Plan de respuesta a incidentes | Documento con responsable nombrado y ejercicios | La estructura está en la [guía de ciberseguridad para PyMEs](/guia/guia-ciberseguridad-pymes-latam-2026) |
| Seguridad de email | Filtrado anti-phishing y SPF, DKIM, DMARC | [Comparativa de seguridad de email](/comparativa/comparativa-seguridad-email-pymes) |
| Gestión de parches | Un plazo escrito para cerrar vulnerabilidades críticas | — |
| Capacitación del personal | Registro de quién la completó | — |

Miralo como lo que es: **el índice de un programa de seguridad completo, ordenado por lo que a una aseguradora le duele pagar.** No es coincidencia que sea casi idéntico a lo que pide un cliente grande en un cuestionario. Los dos están mirando el mismo riesgo.

---

## El detalle que hace fallar la mayoría de las solicitudes

Hay dos requisitos donde las PyMEs se caen más seguido, y vale detenerse porque son los que más plata cuestan si están mal.

**El MFA que exigen no es cualquier MFA.** Cada vez más aseguradoras piden MFA resistente a phishing para los accesos remotos y las cuentas de administrador, no un código por SMS. La razón es la misma por la que lo pedimos nosotros: el segundo factor común se puede robar junto con la sesión. Cómo funciona ese robo —y por qué solo las llaves físicas y las passkeys lo cortan— está en [cómo funciona el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa) y en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza). Si tu solicitud declara "MFA sí" pensando en el SMS, y la letra chica pedía resistente a phishing, tenés una exclusión esperándote justo el día del incidente. Para las cuentas de privilegio, [una llave física](/producto/yubikey-5-series) es lo que cierra ese requisito sin lugar a dudas.

**El backup tiene que ser inmutable y probado, no solo existir.** El estándar que usan los suscriptores evolucionó: a la vieja regla de tres copias se le agregó "una inmutable y cero restauraciones sin verificar". Es decir, no alcanza con tener respaldos; hay que demostrar que **al menos una copia no se puede borrar ni siquiera con credenciales de administrador**, y que la restauración se probó, con fecha. Un backup diario que el ransomware cifra junto con todo lo demás no cuenta como control a los ojos de la aseguradora, con toda razón. [Veeam Data Platform](/producto/veeam-data-platform) y el resto de la [categoría de backup](/productos/backup-y-recuperacion) cubren el requisito de inmutabilidad; por qué las copias del proveedor no siempre alcanzan está en [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

---

## La trampa de la declaración falsa

Este es el punto donde una PyME puede convertir un seguro en un gasto inútil sin darse cuenta, y merece ser tajante.

Cuando llenás la solicitud, estás haciendo **declaraciones contractuales**. Si declarás que tenés EDR en todos los equipos y en realidad tenés un antivirus en la mitad, no compraste protección: compraste una excusa para que la aseguradora no pague. El día del incidente, lo primero que hace el ajustador es verificar si los controles que declaraste estaban efectivamente funcionando. Si no estaban, la póliza no responde, y pagaste la prima para nada.

Es exactamente la misma regla que aplica a los cuestionarios de clientes: **un "sí" falso es peor que un "no" honesto.** Un "no" te sube la prima; un "sí" falso te deja sin cobertura justo cuando la necesitás. Declarar la verdad —aunque sea incómoda— y mostrar un plan para lo que falta es siempre mejor negocio que fingir un control que no tenés.

Por eso conviene armar lo que los suscriptores llaman la carpeta de evidencia antes de solicitar: capturas del proveedor de identidad mostrando el MFA activo, la consola de EDR con la cobertura, los registros de pruebas de restauración con fecha, el plan de incidentes en PDF. No para engañar: para poder declarar la verdad y probarla.

---

## Qué hacer, en orden

1. **Pedí la solicitud de dos o tres aseguradoras antes de comprar nada.** El formulario es la lista de requisitos, y es gratis. Te dice exactamente qué mirar.
2. **Cerrá primero MFA y backup**, que son los dos que más rechazos causan y los que más bajan el riesgo real. MFA resistente a phishing en las cuentas de administrador, y una copia inmutable probada.
3. **Documentá mientras implementás**, no después. La carpeta de evidencia se arma sola si sacás la captura el día que activás cada control.
4. **Declará la verdad en la solicitud.** Lo que falte, decilo con su plan y su fecha. Una prima más alta es más barata que una póliza que no paga.
5. **Recién ahí, cotizá.** Con los controles puestos y documentados, la prima baja y las exclusiones se achican. Invertir en los controles reduce a la vez la probabilidad del ataque y el costo del seguro que lo cubre.

Mi lectura, marcada como opinión: para la mayoría de las PyMEs de la región, el mayor valor de este proceso no es la póliza, es la lista. Aunque termines decidiendo no contratar el seguro, salir a pedir tres formularios te deja el mejor plan de seguridad priorizado que vas a conseguir gratis. Y si lo contratás, llegás con los controles puestos en vez de comprar una cobertura llena de agujeros.

Para ubicar todo esto dentro del programa completo —qué va antes y qué después—, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) es el mapa general.

---

## Preguntas frecuentes

### ¿Me conviene contratar un ciberseguro o gastar esa plata en seguridad?

No es una disyuntiva: la aseguradora te va a exigir la seguridad como condición para venderte la póliza, así que el gasto en controles viene primero de todos modos. El seguro cubre el riesgo residual —lo que pasa a pesar de los controles—, no lo reemplaza. Y si tu presupuesto solo alcanza para una cosa, empezá por los controles: bajan la probabilidad del incidente, mientras que el seguro solo baja su costo.

### ¿Qué pasa si declaro un control que no tengo del todo?

Es el peor de los escenarios. Una declaración falsa en la solicitud suele activar exclusiones o directamente anular la cobertura cuando el ajustador verifica, después del incidente, que el control no estaba funcionando. Pagás la prima y no cobrás. Siempre es mejor declarar la verdad, aceptar una prima más alta o una exclusión explícita, y mostrar el plan para cerrar lo que falta.

### ¿Por qué me piden MFA resistente a phishing y no me alcanza con el SMS?

Porque el código por SMS o por app se puede interceptar y reenviar en un ataque de robo de sesión, que es hoy la forma dominante de saltear el MFA. Las aseguradoras lo saben y por eso, para accesos remotos y cuentas de privilegio, cada vez más piden métodos resistentes a phishing como las llaves físicas o las passkeys. El mecanismo está explicado en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).

### ¿Cuánto sale un ciberseguro para una PyME?

Varía mucho según el tamaño, el rubro, el límite de cobertura y —sobre todo— los controles que puedas demostrar: mejores controles, prima más baja. Los rangos publicados por corredores van desde unos cientos de dólares al año para una empresa chica hacia arriba, pero el número que importa es el que te coticen a vos con tu solicitud, no un promedio. Por eso el paso 1 es pedir las solicitudes.

### ¿El seguro cubre el pago del rescate en un ransomware?

Depende de la póliza y cada vez con más condiciones. Muchas coberturas incluyen la gestión del incidente —forense, negociación, notificación legal— y algunas el rescate, pero con exclusiones crecientes y requisitos previos. No lo asumas: es una de las cláusulas que hay que leer con lupa antes de firmar, no después del ataque.
