---
title: "Te llegó un cuestionario de seguridad de un cliente: cómo responderlo"
subtitle: Un cliente grande te manda 128 preguntas y diez días de plazo. Qué te llegó exactamente, las ocho preguntas que aparecen siempre, y qué contestar cuando la respuesta honesta es que no.
excerpt: Los clientes regulados trasladan sus obligaciones a sus proveedores vía cuestionario. Qué formato te llegó, qué preguntan siempre y cómo responder "todavía no" sin perder el contrato.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-07-27
updated: 2026-08-20
products:
  - microsoft-entra-id
  - 1password-business
  - veeam-data-platform
  - microsoft-defender-for-endpoint-p2
meta_title: "Cuestionario de seguridad de un cliente: cómo responderlo"
meta_description: "Qué formato te llegó (CAIQ, SIG o uno propio), las ocho preguntas que aparecen siempre, y cómo contestar cuando la respuesta honesta es que todavía no."
---

Llega un correo de compras de tu cliente más grande. Adjunta una planilla con entre cien y varios cientos de preguntas sobre tus controles de seguridad, y pide devolverla en diez días hábiles. Nadie en tu empresa vio nunca una planilla así.

La reacción habitual es una de dos, y las dos salen caras: contestar que sí a todo para no perder la cuenta, o pedir una prórroga que se convierte en tres meses de silencio incómodo. Hay un camino mejor, y empieza por entender que **ese cuestionario no es una auditoría: es una transferencia de responsabilidad**. Tu cliente tiene obligaciones y te está pidiendo evidencia de que trabajar con vos no se las rompe.

Esta guía es el mapa de esa planilla: qué te llegó exactamente, qué preguntan siempre, qué contestar cuando la respuesta honesta es que todavía no, y qué armar una vez para que la próxima te lleve dos horas en vez de tres semanas.

---

## Qué te llegó exactamente

El acrónimo del asunto te dice la forma de las preguntas, no cuán seguro tenés que ser. Hay tres familias.

**CAIQ.** Lo publica la [Cloud Security Alliance](https://cloudsecurityalliance.org/artifacts/cloud-controls-matrix-v4-1) y va por la versión 4.1, ahora combinada con el Cloud Controls Matrix: 197 objetivos de control repartidos en 17 dominios. Es un set de preguntas de sí o no pensado para evaluar proveedores de servicios en la nube, y se descarga gratis para uso interno. Si vendés software como servicio, este es el que te va a llegar.

**SIG.** Lo publica Shared Assessments, una organización sin fines de lucro, y lo actualiza todos los años. Viene en dos tamaños: uno reducido para un tamizado inicial y otro extenso para proveedores que tocan datos sensibles o regulados. Es el formato típico cuando el cliente es un banco, una aseguradora o una empresa de salud.

**El propio del cliente.** Una planilla que escribió el equipo de seguridad de tu cliente, o su consultora. Varía muchísimo en calidad y suele mezclar preguntas técnicas serias con otras copiadas de una plantilla que no aplican a tu caso. Es el más común en LATAM y el más fácil de negociar.

La distinción importa menos de lo que parece. **Los tres preguntan lo mismo por abajo**, con distinto nivel de detalle. Si tenés resuelto el fondo, cambiar de formato es trabajo de transcripción.

---

## Por qué te llegó a vos

No es que tu cliente desconfíe de tu empresa en particular. Es que el marco regulatorio de los últimos años empujó las obligaciones de ciberseguridad hacia abajo en la cadena de proveedores: las empresas que caen bajo esas normas están obligadas a gestionar el riesgo de quienes les proveen, y la forma práctica de hacerlo es exigir evidencia por contrato.

El efecto secundario es el que te toca: **una PyME que ni de lejos alcanza el umbral de la norma queda alcanzada igual, por transitividad**, en cuanto le vende a alguien que sí lo alcanza. Si sos el que desarrolla el software de un hospital, el que provee un componente a una automotriz o el que administra los sistemas de una empresa que cotiza, ya estás dentro.

Vale entenderlo bien porque cambia la estrategia: esto no se va a ir. El cuestionario de este cliente lo va a repetir el próximo. Conviene resolverlo como capacidad permanente y no como incendio.

---

## La regla que ordena todo: no mientas, y no te disculpes

Dos errores simétricos, y el primero es mucho peor.

**Mentir es el peor negocio posible.** Un "sí" falso en una planilla firmada es una declaración contractual. Si después hay un incidente y sale que no tenías el control que declaraste, dejaste de tener un problema técnico y pasaste a tener uno legal, con la aseguradora de tu cliente buscando a quién imputarle la pérdida. Ninguna cuenta vale eso.

**Pero tampoco te disculpes por lo que falta.** Un "no" con plan y fecha se lee muchísimo mejor que un "no" a secas, y en muchos casos mejor que un "sí" dudoso. Los equipos de riesgo evalúan proveedores todo el día y reconocen a la legua cuál está diciendo la verdad. Un proveedor que sabe exactamente qué le falta y cuándo lo va a tener es, en la práctica, más confiable que uno que responde que sí a todo en catorce minutos.

Es la parte que más se subestima. La planilla no mide tu madurez tanto como mide **si sabés cuál es tu madurez**. Es un examen de autoconocimiento disfrazado de examen técnico.

---

## Las ocho preguntas que aparecen siempre

Cambia la redacción, no el fondo. Estas ocho están en todos los formatos y concentran la mayor parte del peso de la evaluación.

| Lo que preguntan | Lo que quieren saber | Qué tener |
|---|---|---|
| ¿Usan autenticación multifactor? | Si una contraseña robada alcanza para entrar | MFA en todas las cuentas, sin excepciones para directores |
| ¿Cómo gestionan las contraseñas? | Si hay credenciales compartidas por WhatsApp | Un gestor con bóvedas por equipo y altas y bajas nominales |
| ¿Tienen copias de seguridad y las prueban? | Si sobrevivirían a un ransomware | Copias fuera del alcance de las credenciales y una restauración probada con fecha |
| ¿Cómo protegen los equipos? | Si hay algo más que el antivirus de fábrica | Consola central, no un antivirus por máquina |
| ¿Cómo aplican parches? | En cuánto tiempo cierran una vulnerabilidad crítica | Un plazo escrito y evidencia de que se cumple |
| ¿Qué pasa cuando alguien se va? | Si los ex empleados conservan acceso | Baja de accesos como parte del proceso de salida |
| ¿Tienen plan de respuesta a incidentes? | A quién llaman a las 3 de la mañana | Un documento de dos páginas con nombres y teléfonos |
| ¿En cuánto notifican un incidente? | Si se enterarían a tiempo | Un compromiso de plazo que puedas sostener |

Mirá la columna del medio. **Ninguna pregunta es sobre tecnología sofisticada**: todas son sobre higiene básica y sobre si existe un proceso. Eso es una buena noticia, porque significa que el 80% de la planilla se responde con cosas que cuestan poco.

Los enlaces de abajo cubren cada bloque con el procedimiento concreto:

- **MFA y contraseñas.** La [guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) resuelve la primera pregunta, y conviene seguirla con [por qué tener MFA no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), porque un evaluador con oficio va a preguntar qué segundo factor usás y los SMS puntúan bajo. Para lo segundo están [1Password Business](/producto/1password-business), [Bitwarden Business](/producto/bitwarden-business) y el resto de la [categoría de gestión de contraseñas](/productos/gestion-contrasenas).
- **Copias.** Acá es donde más proveedores se caen, porque confunden tener copias con poder restaurar. La guía sobre [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta) desarma el supuesto más frecuente, y [Veeam Data Platform](/producto/veeam-data-platform) y [Acronis Cyber Protect](/producto/acronis-cyber-protect) son las herramientas del segmento.
- **Equipos.** Si todavía estás decidiendo qué comprar, la guía sobre [cuándo se justifica el salto de antivirus a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme) evita la compra apurada; si ya decidiste, la [comparativa de Bitdefender, Kaspersky y ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset) baja a producto.
- **Correo y dominio.** Varios cuestionarios preguntan explícitamente por SPF, DKIM y DMARC. Es de lo más barato de la lista: la [guía paso a paso de SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso) no cuesta licencias.
- **Sitio web.** Si tu producto es un sitio o una tienda, va a haber una sección sobre eso: [qué cubre tu hosting y qué te toca a vos](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting).

---

## Qué contestar cuando la respuesta es "no"

Esta es la parte que decide el resultado, y casi nadie la escribe bien.

Una respuesta negativa útil tiene tres partes, en este orden: **qué no tenés, qué hacés hoy en su lugar, y para cuándo lo vas a tener.**

> *"No tenemos EDR con retención de telemetría. Hoy usamos antivirus de empresa con consola central y revisión semanal de alertas. Tenemos presupuesto aprobado para EDR gestionado y fecha de implementación en el cuarto trimestre."*

Esa respuesta suma. Estas dos restan:

> *"No."*
>
> *"Sí, tenemos EDR."* (cuando lo que tenés es un antivirus)

El compensador —qué hacés hoy en su lugar— es lo que más pesa y lo que más se omite. Un evaluador no espera que una empresa de veinte personas tenga el mismo programa que un banco; espera que sepas dónde estás parado.

Dos advertencias sobre las fechas. La primera: **no prometas nada que no vayas a cumplir**, porque el cuestionario del año que viene va a preguntar por esa misma fecha y quedar en evidencia es peor que haber dicho que no. La segunda: si la respuesta a la mitad de las preguntas es "no, pero en el cuarto trimestre", el evaluador va a leer que no tenés programa. Prioriza: es mejor tener cuatro controles funcionando de verdad que doce a medio implementar.

---

## El paquete que respondés una vez

La mayor parte del costo de un cuestionario no es tenerlo resuelto: es escribirlo desde cero cada vez. Armá esto una sola vez y la siguiente planilla te lleva una tarde.

1. **Un inventario de tus controles**, en una hoja de cálculo. Una fila por control, con: qué es, qué herramienta lo implementa, quién es responsable, y desde cuándo funciona. Es la fuente de la que salen todas las respuestas.
2. **Un documento de dos páginas de respuesta a incidentes**: quién decide, a quién se llama, en qué orden, y qué se le comunica al cliente y en cuánto tiempo. Dos páginas reales valen más que veinte copiadas.
3. **Evidencia de una restauración probada**: fecha, qué se restauró, cuánto tardó, quién lo hizo. Una captura y tres líneas. Es lo que separa "tenemos backup" de "podemos recuperarnos".
4. **La lista de tus propios proveedores críticos** y qué datos toca cada uno. Cada vez más cuestionarios preguntan por tu cadena, no solo por vos.
5. **Un banco de respuestas**: las cuarenta o cincuenta preguntas que ya contestaste, con su redacción final. Es el que más tiempo ahorra a partir de la segunda vez.

Nada de esto requiere una consultora. Requiere una tarde y que alguien se haga cargo.

Si además te piden que un tercero valide algo, ahí sí entra presupuesto externo, y conviene saber qué estás comprando antes de pedir cotizaciones: [qué certificaciones exigirle a un proveedor de pentesting](/guia/certificaciones-pentesting-que-exigir-proveedor) y [cómo se estructuran esos costos](/guia/costos-pentesting-red-team-presupuesto-empresarial).

---

## Cuándo conviene no responder

Tres casos donde la respuesta correcta es negociar el alcance, y decirlo es parte de ser un proveedor serio.

- **El cuestionario no aplica a lo que vendés.** Si te mandan un CAIQ completo y vos entregás un servicio presencial sin tocar un solo dato de sus sistemas, respondé la sección que corresponde y marcá el resto como no aplicable, explicando por qué. Contestar preguntas sobre arquitectura de nube cuando no tenés nube te hace ver desprolijo, no colaborador.
- **El plazo es imposible y el contrato no lo justifica.** Cien preguntas bien contestadas son entre veinte y cuarenta horas la primera vez. Si el negocio no las paga, pedí el formato reducido o proponé una llamada de una hora con su equipo de seguridad. Sorprende cuántas veces aceptan.
- **Te piden evidencia que no podés dar sin romper otro compromiso.** Configuraciones detalladas de tu infraestructura o datos de otros clientes no se comparten. Se ofrece una demostración en vivo o un resumen firmado. Un evaluador razonable lo entiende; uno que insiste te está pidiendo que hagas con sus datos lo mismo que estás negándote a hacer con los de otro.

Y una que va contra el instinto comercial: **si el cliente exige controles que no tenés y no vas a poder financiar, decilo antes de firmar**. Un contrato con un anexo de seguridad incumplible es un pasivo, no una venta.

---

## Preguntas frecuentes

### ¿Puedo contestar que sí si lo estoy implementando?

No. "Sí" significa que funciona hoy. Lo correcto es "en implementación" con la fecha, y si la planilla solo admite sí o no, marcás no y lo aclarás en el campo de comentarios. Si no hay campo de comentarios, se aclara en el correo de respuesta: eso queda igual de registrado.

### ¿Necesito una certificación como ISO 27001 para responder?

En la mayoría de los casos no. La certificación acelera el proceso porque reemplaza muchas preguntas por un certificado, pero es cara y lleva meses. Para una PyME que recién recibe sus primeros cuestionarios, tener los controles funcionando y documentados resuelve la enorme mayoría de las evaluaciones sin certificarse.

### ¿Qué pasa si respondo mal y después hay un incidente?

Depende de si el error fue de buena fe o una declaración falsa. Un control que fallaba sin que lo supieras es un incidente; un "sí" que sabías que era mentira es un incumplimiento contractual, y suele activar cláusulas de indemnización. Es la razón por la que la regla número uno de esta guía es no mentir.

### ¿Cuánto tarda responder uno?

La primera vez, entre veinte y cuarenta horas para un cuestionario de cien preguntas, con la mayor parte del tiempo yendo a averiguar internamente qué controles existen realmente. Con el inventario y el banco de respuestas armados, el segundo baja a una fracción de eso.

### ¿Sirve pagar una herramienta que responda cuestionarios automáticamente?

Existen y funcionan, pero resuelven el problema equivocado si todavía no tenés el inventario de controles: automatizan la transcripción, no el conocimiento. Tiene sentido cuando recibís muchos cuestionarios por año. Con dos o tres, la hoja de cálculo alcanza.

### Soy proveedor de un proveedor. ¿Igual me alcanza?

Sí, y cada vez más. Los cuestionarios recientes incluyen una sección sobre la propia cadena de proveedores del evaluado, así que la exigencia baja un escalón más en cada ronda. Si le vendés a alguien que le vende a una empresa regulada, tarde o temprano llega.
