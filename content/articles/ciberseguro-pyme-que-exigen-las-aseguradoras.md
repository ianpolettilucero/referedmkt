---
title: "Ciberseguro para PyME: qué exigen las aseguradoras"
subtitle: La póliza dejó de ser un trámite. La aseguradora audita tus controles antes de cubrirte, y si falta uno sube la prima o rechaza la solicitud.
excerpt: El ciberseguro se volvió una auditoría técnica: MFA, EDR, backup inmutable y plan de incidentes son requisitos, no recomendaciones. Qué piden y cómo llegar preparado.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-18
updated: 2026-08-20
products:
  - microsoft-defender-for-endpoint-p2
  - veeam-data-platform
  - microsoft-entra-id
  - yubikey-5-series
meta_title: "Ciberseguro para PyME: qué exigen las aseguradoras"
meta_description: "El ciberseguro se volvió una auditoría: MFA, EDR, backup inmutable y plan de incidentes son requisitos, no recomendaciones. Qué piden y cómo llegar preparado."
---

Contratar un seguro de ciberriesgo era llenar un formulario, pagar la prima y guardar la póliza en un cajón. Ya no. Hoy la aseguradora audita tus controles antes de cubrirte, y si falta alguno la respuesta no es "pagás un poco más": muchas veces es rechazo.

Eso da vuelta la lógica de la compra. **La lista de requisitos de la aseguradora es la mejor guía de qué implementar primero**, porque quien analiza pérdidas todos los días ya decidió qué controles evitan las facturas más caras. La lista es gratis y sirve tengas o no intención de contratar.

Es el mismo mecanismo que los [cuestionarios de seguridad de clientes](/guia/cuestionario-seguridad-cliente-como-responder): un tercero con poder sobre tu negocio te obliga a demostrar controles. Acá ese tercero cobra por el riesgo, así que su lista es más práctica todavía.

---

## Por qué la región es un caso aparte

Según el [informe de ciberriesgo de Aon para América Latina](https://www.aon.com/cyber-risk-report/es-la/cyber-risk-is-a-corporate-risk-latin-america-responds), las PyMEs son el **99,5% del mercado** regional. El mismo informe cuenta 1.498 ataques de ransomware en la región en 2023 y ubica la preparación promedio en **2,59 sobre 4**, entre los niveles "básico" y "gestionado".

La mayoría de las empresas de la región está por debajo del nivel que una aseguradora necesita para cubrirlas. Por eso la póliza se volvió un examen. El examen tiene el temario publicado.

---

## Los controles que piden

Los requisitos convergen entre aseguradoras porque todas miran las mismas estadísticas de pérdida. Una solicitud que no puede demostrarlos se rechaza o enfrenta primas más altas y límites más bajos.

| Control que exigen | Qué quieren ver | Dónde está en el sitio |
|---|---|---|
| MFA resistente a phishing | Segundo factor en accesos remotos y cuentas de privilegio | [Configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) |
| EDR en los endpoints | Cobertura amplia, no un antivirus por máquina | [Cuándo se justifica el salto a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme) |
| Backup inmutable y probado | Copias que un atacante no pueda borrar, con restauración testeada | [Backup y recuperación](/productos/backup-y-recuperacion) |
| Plan de respuesta a incidentes | Documento con responsable nombrado y ejercicios | [Guía de ciberseguridad para PyMEs](/guia/guia-ciberseguridad-pymes-latam-2026) |
| Seguridad de email | Filtrado anti-phishing y SPF, DKIM, DMARC | [Comparativa de seguridad de email](/comparativa/comparativa-seguridad-email-pymes) |
| Gestión de parches | Un plazo escrito para cerrar vulnerabilidades críticas | — |
| Capacitación del personal | Registro de quién la completó | — |

Es el índice de un programa de seguridad completo, ordenado por lo que a una aseguradora le duele pagar. Coincide casi punto por punto con lo que pide un cliente grande en un cuestionario, porque los dos miran el mismo riesgo.

---

## Los dos requisitos donde más solicitudes fallan

**El MFA que exigen no es cualquier MFA.** Cada vez más aseguradoras piden MFA resistente a phishing para accesos remotos y cuentas de administrador, no un código por SMS. El segundo factor común se roba junto con la sesión: el mecanismo está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa) y en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza). Si declarás "MFA sí" pensando en el SMS y la letra chica pedía resistente a phishing, tenés una exclusión esperándote el día del incidente. Para cuentas de privilegio, [una llave física](/producto/yubikey-5-series) cierra el requisito.

**El backup tiene que ser inmutable y probado, no solo existir.** El estándar de los suscriptores agregó a la vieja regla de tres copias dos condiciones: una inmutable y cero restauraciones sin verificar. Hay que demostrar que **al menos una copia no se puede borrar ni con credenciales de administrador** y que la restauración se probó, con fecha. Un backup diario que el ransomware cifra junto con todo lo demás no cuenta como control. [Veeam Data Platform](/producto/veeam-data-platform) cubre la inmutabilidad, y por qué las copias del proveedor no siempre alcanzan está en [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

---

## La declaración falsa

Llenar la solicitud es hacer **declaraciones contractuales**. Si declarás EDR en todos los equipos y tenés antivirus en la mitad, no compraste protección: compraste una excusa para que la aseguradora no pague. El día del incidente el ajustador verifica si los controles declarados estaban funcionando. Si no estaban, la póliza no responde.

Vale la misma regla que en los cuestionarios de clientes: **un "sí" falso es peor que un "no" honesto.** El "no" sube la prima; el "sí" falso deja sin cobertura justo cuando se la necesita.

De ahí la carpeta de evidencia, que conviene armar antes de solicitar: capturas del proveedor de identidad con el MFA activo, la consola de EDR con la cobertura, los registros de pruebas de restauración con fecha, el plan de incidentes en PDF. Sirve para declarar la verdad y poder probarla.

---

## Qué hacer, en orden

1. **Pedí la solicitud de dos o tres aseguradoras antes de comprar nada.** El formulario es la lista de requisitos y es gratis.
2. **Cerrá primero MFA y backup.** Son los que más rechazos causan y los que más bajan el riesgo real: MFA resistente a phishing en cuentas de administrador y una copia inmutable probada.
3. **Documentá mientras implementás.** La carpeta de evidencia se arma sola si sacás la captura el día que activás cada control.
4. **Declará la verdad.** Lo que falte, con su plan y su fecha. Una prima más alta es más barata que una póliza que no paga.
5. **Recién ahí, cotizá.** Con los controles puestos y documentados baja la prima y se achican las exclusiones.

El mayor valor del proceso no es la póliza: es la lista. Aunque termines sin contratar, pedir tres formularios deja el mejor plan de seguridad priorizado que se consigue gratis.

Para ubicar esto dentro del programa completo, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) es el mapa general.

---

## Preguntas frecuentes

### ¿Conviene contratar un ciberseguro o gastar esa plata en seguridad?

No es una disyuntiva: la aseguradora exige los controles como condición para venderte la póliza, así que el gasto viene primero de todos modos. El seguro cubre el riesgo residual, no lo reemplaza. Si el presupuesto alcanza para una sola cosa, empezá por los controles: bajan la probabilidad del incidente, mientras que el seguro solo baja su costo.

### ¿Qué pasa si declaro un control que no tengo del todo?

Una declaración falsa suele activar exclusiones o anular la cobertura cuando el ajustador verifica, después del incidente, que el control no estaba funcionando. Se paga la prima y no se cobra. Conviene declarar la verdad, aceptar una prima más alta o una exclusión explícita, y mostrar el plan para cerrar lo que falta.

### ¿Por qué piden MFA resistente a phishing y no alcanza el SMS?

Porque el código por SMS o por app se intercepta y se reenvía en un ataque de robo de sesión, la forma dominante de saltear el MFA. Para accesos remotos y cuentas de privilegio, las aseguradoras piden métodos resistentes a phishing: llaves físicas o passkeys. El mecanismo está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).

### ¿Cuánto sale un ciberseguro para una PyME?

Varía según tamaño, rubro, límite de cobertura y los controles que se puedan demostrar: mejores controles, prima más baja. Los rangos publicados por corredores arrancan en unos cientos de dólares al año para una empresa chica, pero el número que importa es el de tu cotización, no un promedio.

### ¿El seguro cubre el pago del rescate en un ransomware?

Depende de la póliza y con condiciones crecientes. Muchas coberturas incluyen la gestión del incidente —forense, negociación, notificación legal— y algunas el rescate, con exclusiones y requisitos previos. Es una de las cláusulas que hay que leer antes de firmar.
