---
title: "Acceso remoto seguro sin VPN: guía para PyMEs"
subtitle: Las nueve preguntas que te va a hacer el que firma el cheque, con el número que sostiene cada respuesta y la frase para decir en voz alta.
excerpt: Cloudflare Access es gratis hasta 50 usuarios. Ese número desarma la objeción de costo y te deja discutir lo que de verdad importa: el 3389 abierto.
type: guide
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-07-26
products: [cloudflare-access, tailscale-business, twingate]
meta_title: "Acceso remoto seguro sin VPN: guía para PyMEs"
meta_description: "Cómo dar acceso remoto a tus empleados sin VPN: precios de lista por persona, qué hacer con el RDP expuesto y cómo conseguir la aprobación en la reunión."
---

Sacar la VPN no se muere por un problema técnico. Se muere en la reunión, en menos de un minuto, con tres preguntas: por qué cambiar algo que funciona, cuánto sale y quién lo va a mantener.

La del medio es la que hunde el proyecto antes de que empiece, y es la que tiene la respuesta más corta: hasta 50 personas, cero pesos. El plan gratuito de [Cloudflare Access](/producto/cloudflare-access) cubre 50 usuarios con acceso por identidad y túnel de salida, y recién arriba de ese número el precio de lista es de USD 7 por usuario al mes ([plan y precios de Cloudflare Zero Trust](https://www.cloudflare.com/plans/zero-trust-services/), consultados el 26 de julio de 2026).

Pero el precio nunca fue el problema de fondo, y el que firma el cheque también lo sabe. Elegir producto es fácil: hay tres buenos y los tres andan. Lo difícil es la reunión.

Así que esta guía no está escrita para vos. Está escrita para tu jefe. Cada título de acá abajo es una pregunta que te va a hacer, en el orden en que se suelen hacer, con el número que sostiene la respuesta y la frase exacta para decir en voz alta.

Un aviso de método antes de empezar, porque cambia cómo hay que leer lo que sigue: acá no hay despliegues propios ni mediciones de laboratorio. Hay precios de lista públicos, documentación de los fabricantes y tres informes anuales que podés descargar y revisar vos. Cada cifra dice de dónde sale. Si alguna no la podés verificar en la fuente, no debería estar.

## La versión de un minuto

Seis líneas. Copiálas, imprimílas, llevalas.

1. La VPN te mete en la red entera; el reemplazo te da una aplicación y nada más.
2. Hoy se entra más por lo que dejamos publicado que por engañar a alguien: la explotación de vulnerabilidades es el vector de acceso inicial número uno del DBIR 2026, con 31% de los casos contra 20% el año anterior.
3. Cloudflare Access sale USD 0 hasta 50 personas y USD 7 por usuario/mes arriba de eso.
4. El escritorio remoto en el puerto 3389 es la puerta a cerrar esta semana, con esto o sin esto.
5. La primera aplicación anda en una tarde; la VPN se apaga cuando querramos, no el mismo día.
6. El ERP viejo también entra, sin tocarle una línea al servidor.

---

## "¿Qué tiene de malo la VPN que ya pagamos?"

Nada, mientras nadie robe una credencial. Cuando alguien la roba, la VPN hace exactamente lo que se le pidió: pone a esa persona adentro de la red con el mismo alcance que tendría sentada en su escritorio. Y adentro están el controlador de dominio, la NAS, las impresoras, la cámara del depósito y el servidor de facturación. La VPN no distingue entre "Marcela de compras necesita el ERP" y "alguien con la clave de Marcela quiere ver qué más hay".

Y las credenciales se roban seguido: el 79% de los ataques de ransomware relevados arrancó por vía de identidad ([Sophos, *State of Ransomware 2026*](https://www.sophos.com/en-us/blog/sophos-state-of-ransomware-2026)). El informe lo publica una empresa que vende productos de seguridad, así que leelo con la pinza puesta — pero el orden interno del dato aguanta la pinza: correo malicioso 26%, phishing 24%, credenciales comprometidas 23%, explotación de vulnerabilidades 18%. Los dos primeros son la misma conversación de siempre y se atacan por otro lado, con el [filtro de correo que te convenga](/comparativa/comparativa-seguridad-email-pymes).

El otro defecto de la VPN es más chico y más molesto. Toda VPN clásica manda el tráfico a un concentrador y lo devuelve: si el concentrador está en Villa Crespo y la persona está en Mendoza abriendo un archivo de Drive, ese archivo viaja a Buenos Aires y vuelve. Se llama *backhaul* y es la primera hipótesis que conviene descartar cuando alguien dice que la VPN está lenta.

[Tailscale](/producto/tailscale-business) sostiene, en su propio material, que el overhead es de menos de 1 ms cuando la conexión sale directa entre los dos nodos y que la conexión directa se consigue en la amplia mayoría de los casos. Son números del fabricante, sin verificación independiente, y hay que tratarlos como tal. La letra chica también la publica la empresa, en su documentación: contra un NAT simétrico —el que reasigna el puerto de origen en cada conexión saliente, habitual en redes móviles y en las salidas NAT de las nubes públicas— la perforación falla y el tráfico cae a un relay DERP ([documentación de tipos de conexión de Tailscale](https://tailscale.com/docs/reference/connection-types)). Sigue cifrado punta a punta y sigue funcionando, pero ahí el milisegundo prometido no existe más. Es información que conviene tener antes de prometer velocidad en una reunión.

> **Para decir en la reunión:** "La VPN funciona. El problema es que le da la red entera a cualquiera que tenga una contraseña, y las contraseñas se roban todas las semanas."

---

## "¿Esto no es lo mismo que abrir el escritorio remoto?"

Es exactamente lo contrario, y conviene aclararlo despacio porque es la confusión más cara de esta conversación.

Abrir el escritorio remoto significa publicar el puerto 3389 de un servidor Windows directo a internet para que la gente entre con usuario y contraseña. En la lista de puntos de entrada que releva Sophos, "aplicaciones o sistemas expuestos" encabeza con 38%, muy por encima de los dispositivos de usuario (30%), los firewalls (21%), las VPN (8%) y el IoT (3%). En una PyME típica, ese 38% se llama RDP publicado, ERP publicado y panel del hosting.

Y no es que "algún día te encuentren". Buscadores como Shodan y Censys barren el espacio IPv4 completo de forma continua y publican el resultado en una interfaz que cualquiera consulta sin permiso de nadie: no hace falta que un atacante te elija, alcanza con que consulte una lista. Después ese acceso se vende, y el que lo compra no es el que lo escaneó.

El 96% de las víctimas de ransomware con tamaño de organización conocido en el [DBIR 2026 de Verizon](https://www.verizon.com/business/resources/reports/dbir/) fueron PyMEs. Y las organizaciones de 100 a 250 empleados detuvieron el ataque antes del cifrado en apenas el 34% de los casos, contra el 46% de las de 3.001 a 5.000 (Sophos 2026). No las atacan mejor. Es que del otro lado no hay nadie mirando a las tres de la mañana.

Un reemplazo de VPN no abre nada. Se instala un agente adentro de tu red que sale hacia afuera —conexión saliente, la que tu firewall ya permite— y se encuentra con el usuario en el medio. El *connector* de [Twingate](/producto/twingate) corre en un contenedor y el fabricante declara como mínimo 1 vCPU y 512 MB de RAM, con una máquina virtual de 1 CPU y 2 GB alcanzando para cientos de usuarios ([buenas prácticas de connectors de Twingate](https://www.twingate.com/docs/connector-best-practices)); Cloudflare lo llama Tunnel y hace lo mismo. Desde internet, tu servidor deja de existir.

> **Para decir en la reunión:** "Hoy el servidor está publicado a internet y lo escanean todos los días. Con esto deja de estarlo. No abrimos un puerto: cerramos el que está abierto."

---

## "¿Cuánto sale por persona?"

Entre USD 0 y USD 18 por usuario al mes con precio de lista, y la mayoría de las PyMEs que hacen la cuenta terminan en la mitad baja de ese rango. La tabla, con precios consultados el 26 de julio de 2026 en las páginas de los tres fabricantes:

| Producto | Plan gratuito | Plan de entrada | Escalón siguiente |
|---|---|---|---|
| [Cloudflare Zero Trust](https://www.cloudflare.com/plans/zero-trust-services/) | 50 usuarios, ZTNA y gateway completos | USD 7 / usuario / mes, sin tope de usuarios | Enterprise a cotizar; el aislamiento de navegador se vende aparte |
| [Twingate](https://www.twingate.com/pricing) | 5 usuarios, 10 redes remotas, 50 recursos | USD 5 / usuario / mes (Teams, hasta 100 usuarios) | USD 10 (Business, hasta 500, con EDR y cuentas de servicio) |
| [Tailscale](https://tailscale.com/pricing) | 6 usuarios, dispositivos personales ilimitados | USD 8 / usuario / mes (Standard, con aprovisionamiento SCIM) | USD 18 (Premium, logs de flujo y acceso just-in-time) |

Dos aclaraciones que la tabla se come. Twingate baja a USD 4,25 y USD 8,50 con facturación anual. Y el precio de Cloudflare de USD 7 es el de pago por uso con facturación anual: si pasás de 50 usuarios no pagás solo por los que sobran, pasás a pagar por todos.

Los tres publican el precio de entrada en la web, sin "contactá a ventas", cosa que en este mercado no es obvia. El escalón enterprise sí se cotiza, como en todos lados.

El reflejo del que escucha esto es comparar contra el incidente: recuperarse de un ransomware costó 1,7 millones de dólares en promedio en 2026 sin contar el rescate (Sophos 2026), y el rescate mediano pagado fue de USD 139.875 (Verizon DBIR 2026). Esas cifras salen de muestras que arrancan en 100 empleados, así que una empresa de 18 personas no pierde 1,7 millones: pierde el equivalente proporcional, que suele ser el año.

Hay una comparación mejor, y es la que conviene llevar hecha. Compará contra la licencia de la VPN que ya pagás más las horas del proveedor que la mantiene. La cuenta, con números declarados y redondos para que veas la forma: appliance amortizado más soporte anual del fabricante, USD 1.200; dos horas mensuales del proveedor a USD 40, otros USD 960 al año; total USD 2.160, dividido entre 25 personas da USD 7,20 por persona por mes. Cambiá esos tres números por los tuyos —los tenés en la factura y en los partes de horas— y la cuenta te va a dar sola. El presupuesto completo de seguridad, para ubicar este renglón entre los demás, está en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

> **Para decir en la reunión:** "Hasta 50 personas, cero. Si algún día pasamos de ahí, son siete dólares por persona por mes. Es menos de lo que gastamos en café."

---

## "¿Y si se cae el proveedor?"

Buena pregunta, y la que el mercado contesta con menos honestidad.

Acá aparece la sigla que vas a leer en todos los folletos: ZTNA, *Zero Trust Network Access*, que es el nombre técnico de dar acceso a una aplicación en vez de a una red. Ya está, sigamos.

Depende de qué se caiga. Los tres separan el plano de control —el servicio en la nube que decide quién entra— del plano de datos, por donde viaja tu tráfico. Si se cae el control, las sesiones ya establecidas siguen andando; lo que no podés es autenticar gente nueva. Mal rato, no parálisis. Cloudflare declara red propia en más de 330 ciudades: bastante más infraestructura, y bastante más gente mirándola de noche, de la que vas a tener vos.

Lo que sí es cierto y nadie pone en el folleto: agregás una dependencia de un tercero, y el tercero es hoy el problema que más rápido crece. El DBIR 2026 cuenta un tercero involucrado en el 48% de las brechas, un 60% más que el año anterior. Sacar la VPN no te saca de esa estadística: te cambia de proveedor adentro de ella. Decilo vos en la reunión antes de que lo diga otro.

La mitigación es aburrida y funciona: dejá una vía de emergencia documentada, un acceso por consola física o IPMI con la credencial guardada fuera del sistema. Misma lógica que la cuenta *break-glass* de identidad, y cómo se arma está en [la guía de los huecos de MFA](/guia/ya-tenes-mfa-y-no-alcanza).

> **Para decir en la reunión:** "Si se cae el proveedor, el que está conectado sigue trabajando y no damos accesos nuevos por un rato. Y sí, dependemos de un tercero más: por eso dejamos una vía de emergencia aparte."

---

## "¿Los de sistemas van a tener que aprender otra cosa?"

Sí. Menos de lo que temés y más de lo que dice el vendedor.

Son dos conceptos nuevos, no veinte. Primero: en vez de rangos de IP definís recursos —esta aplicación, este servidor, este puerto— y decís qué grupo del directorio llega a cada uno. Segundo: la política se escribe en texto y se versiona. Las ACL de Tailscale viven en un archivo HuJSON que se guarda en Git; Twingate tiene proveedor oficial de Terraform. Para un equipo que ya trabaja así es una mejora; para uno que administra todo por interfaz gráfica, es un cambio de hábito de un par de semanas.

Lo que se deja de hacer es una lista más larga: no hay más certificados que vencen un domingo, ni listas de IP que actualizar, ni "andá al firewall y abrile el puerto a este". El alta y la baja pasan al directorio de identidad, y con SCIM el que se va queda sin acceso solo.

Una advertencia que los comparativos suelen saltear, y que te dejo como verificación y no como dato: antes de firmar, mirá en qué idioma están la documentación y el soporte de cada uno, y contá cuántos partners listan en tu país contra los que lista un fabricante de appliances tradicional. Los tres publican buscador de partners y eso se responde en cinco minutos. Yo no tengo esa cuenta hecha y no te la voy a inventar, pero si tu esquema depende de que venga alguien a la oficina con una orden de trabajo, ese renglón pesa más que dos dólares de diferencia por usuario.

> **Para decir en la reunión:** "Dos conceptos nuevos y dos semanas de curva. A cambio dejamos de administrar certificados y listas de IP, y las bajas de empleados se aplican solas."

---

## "¿Qué pasa con el que entra desde su notebook personal?"

Ahí es donde este cambio te da algo que la VPN no podía darte.

Con VPN, la notebook personal del contador externo entra igual que la de la empresa: si tiene la credencial, entra. Con acceso por identidad podés exigir condiciones antes de dejarlo pasar —disco cifrado, antivirus corriendo, sistema actualizado—. Se llama *device posture*, y cada fabricante publica su propia lista de integraciones —CrowdStrike, SentinelOne, Intune y Jamf están entre las que aparecen habitualmente—. Cuál soporta cuál, y en qué plan, cambia seguido: confirmalo en la documentación del producto que estés por comprar, contra el EDR que ya tenés puesto.

La política que conviene escribir primero, en dos líneas: equipo administrado por la empresa, acceso completo; equipo personal, solo la aplicación web por navegador y nada de red. Cloudflare permite publicar una aplicación interna sin instalar ningún agente en la máquina del usuario, así que la segunda mitad de esa regla no requiere pedirle nada a nadie. Es la respuesta correcta para el estudio contable y para el proveedor que necesita ver un solo sistema durante dos meses.

Ojo con esto, que es la trampa del modelo entero: todo se apoya en la identidad. Si tu segundo factor tiene agujeros, no resolviste nada — mudaste el problema a un lugar donde no lo estás mirando. Cerrá eso antes de migrar, con el plan corto de [cómo desplegar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana), y si el acceso incluye cuentas de administración, mirá cuánto cuesta subir a llave física en el [análisis de costo total de las YubiKey 5 en Argentina](/resena/analisis-yubikey-5-series-costo-total-argentina).

> **Para decir en la reunión:** "Al equipo personal le damos la aplicación por navegador y nada más. Al de la empresa, con antivirus y disco cifrado, le damos el resto."

---

## "¿Cuánto tarda en estar andando?"

El primer recurso protegido, menos de una hora según los tres fabricantes. La migración completa no la publica ninguno: el rango de uno a dos meses que uso acá es criterio mío para una PyME de una o dos sedes, y sale de dejar la VPN prendida en paralelo mientras se migra por áreas, no de un dato medido.

La promesa del primer día es la única que podés auditar gratis y antes de la reunión, así que auditala: levantá el plan gratuito, publicá un recurso de prueba y cronometrá. Llegá con una demostración, no con una diapositiva. Si el fabricante exagera, te vas a enterar vos primero y no delante de tu jefe.

Lo que lleva semanas no es el producto: es el inventario. Nadie sabe del todo qué tiene publicado. Lo caro de ese ejercicio no es escanear, es lo que puede salir: una aplicación publicada hace años, que no figura en ninguna documentación y que tiene algo que factura del otro lado. Presupuestá ese descubrimiento en el plan en vez de esconderlo: el inventario existe justamente para que aparezca ahí y no en la semana 4.

Un calendario que se sostiene: semana 1, inventario y prueba con tres personas de sistemas. Semana 2, un área completa con la VPN todavía prendida. Semanas 3 y 4, el resto. Semana 5, apagar la VPN.

> **Para decir en la reunión:** "En una tarde tengo la primera aplicación andando para mostrarte. Un mes para pasar a todos, con la VPN prendida en paralelo todo ese tiempo."

---

## "¿Y el servidor viejo del ERP?"

Entra. Y no hay que tocarlo.

Es la objeción que más fácil frena el proyecto y casi siempre está mal fundada. Se asume que un producto moderno solo sirve para aplicaciones web modernas y que el ERP con cliente pesado sobre un Windows Server 2012 queda afuera. No: cualquiera de los tres publica un recurso por IP y puerto, incluido el 3389 del escritorio remoto y el 1433 de SQL Server. El cliente del ERP se conecta a la misma dirección de siempre; lo que cambia es que esa dirección solo resuelve para quien está autenticado. Tailscale además tiene *subnet routers*, que exponen un segmento entero a través de un nodo: es la vía más corta para una red vieja llena de cosas que no aceptan agente.

Ahora, un servidor de 2012 al que llegás por acceso controlado sigue siendo un servidor de 2012. Esto no lo parchea y no hay que dejar que parezca que sí. El DBIR 2026 señala que la explotación de vulnerabilidades pasó a ser el vector de entrada número uno con 31% de los casos, y que el 80% de las vulnerabilidades persistentes del catálogo KEV se registraron antes de 2024 — hubo veinticuatro meses de aviso.

> **Para decir en la reunión:** "El ERP entra sin tocarle nada. Se conecta igual que hoy, con la diferencia de que deja de estar visible desde internet."

---

## "¿Por qué ahora y no el año que viene?"

Porque el 3389 está abierto ahora.

Es la única parte que no admite negociación de calendario. Todo lo demás —la comodidad, el ahorro de horas, el *split tunneling*— puede esperar al presupuesto del año que viene. El escritorio remoto publicado, no.

Y hay un dato regional que en una reunión argentina, chilena o mexicana pega distinto que cualquier promedio global. En LATAM el 85% de las organizaciones tiene firewall, el 82% hace backup y el 73% tiene VPN. El MFA lo tiene apenas el 57%, y entre el personal no técnico la doble autenticación en cuentas laborales baja al 52,2% ([ESET Security Report 2026](https://www.welivesecurity.com/es/informes/eset-security-report-2026-ciberseguridad-empresas-latinoamerica/), sobre 962 organizaciones de 10 países). La región compró el portón y el túnel, y dejó la cerradura para el año que viene. Si te toca defender el presupuesto entero y no solo este renglón, el argumento de retorno está en [la pieza sobre ROI, roles y certificaciones](/guia/ciberseguridad-importancia-roi-roles-certificaciones).

> **Para decir en la reunión:** "Lo del costo lo discutimos en diciembre. Lo del puerto 3389 abierto lo cierro esta semana."

---

## Qué elegir según tu escenario

Cuatro ramas. Buscá la tuya, tomá el precio de lista, andá a la reunión.

**Una sola oficina, un servidor, menos de 50 personas.** Cloudflare Access, plan gratuito: **USD 0 al mes.** Publicás el ERP y el escritorio remoto con Cloudflare Tunnel, conectás Google Workspace o Entra ID como proveedor de identidad, y el servidor deja de tener puertos abiertos. De los tres planes gratuitos de la tabla es el más generoso en cantidad de usuarios, y esa —más una opinión mía sobre lo que cubre— es la razón por la que aparece primero acá.

**Varias sedes que necesitan verse entre sí.** Tailscale Standard a USD 8 por usuario/mes: **25 personas son USD 200 al mes.** Cuando el problema no es "gente entrando a un servidor" sino "sucursales viéndose entre ellas" —replicación de base de datos, backup cruzado—, la malla resuelve en una tarde lo que con túneles sitio a sitio son semanas. Acá va la parte incómoda para el producto que más cariño despierta entre técnicos: al precio de lista de hoy dejó de ser el barato de los tres, y su plan de entrada se cobra por asiento. Lo que gana a cambio, según la tabla de planes que Tailscale publica hoy, es que el aprovisionamiento de usuarios y grupos por SCIM entra en ese plan de entrada. Si tu caso es acceso a un servidor y no malla entre sedes, hoy Twingate hace lo mismo por menos.

**Todo en la nube, nada propio salvo un par de máquinas virtuales.** Twingate Teams a USD 5 por usuario/mes: **15 personas son USD 75 al mes**, y por debajo de 5 usuarios el plan gratuito alcanza. Advertencia contraintuitiva: si de verdad no tenés nada on-premise, probablemente no necesites ninguno de los tres. Gastá esa plata en acceso condicional y en que el correo tenga copia propia — el porqué está en [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

**Servidor legacy on-premise, ERP con cliente pesado, RDP publicado.** Twingate Teams a USD 5 por usuario/mes: **20 personas son USD 100 al mes.** El connector va en un contenedor al lado del servidor y no hay que tocar nada más de la red. Es el escenario donde la promesa de "menos de una hora" tiene más chances de cumplirse, porque no hay que integrar nada raro. Las fichas completas de los tres están en el [directorio de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

---

## Checklist de apagado de la VPN vieja

En este orden. El paso 6 importa aunque no hagas ninguno de los otros.

1. **Inventariá lo publicado.** Escaneá tu propia IP pública desde afuera. Anotá cada puerto que responde y quién lo usa. Vas a encontrar cosas que no sabías.
2. **Levantá el reemplazo en paralelo.** Un connector, un recurso, tres personas de sistemas usándolo una semana entera. La VPN sigue prendida.
3. **Migrá por área, no por persona.** Un área completa, con su responsable avisado y una vía de reclamo clara. Después la siguiente.
4. **Revisá los accesos de terceros.** Contador, proveedor del ERP, soporte externo: cada uno a su recurso, con fecha de vencimiento y sin acceso a la red.
5. **Confirmá que no queda nadie en la VPN.** Mirá los registros siete días seguidos, y no tres: el que entra a la VPN una vez por semana es exactamente el que no aparece en una muestra corta.
6. **Cerrá el 3389.** Sacá la regla de NAT del escritorio remoto, verificá desde afuera que no responde, y revisá también el 22, el 1433, el 3306 y la consola del propio firewall.
7. **Apagá el concentrador dos semanas antes de desinstalar.** Si aparece un reclamo, lo prendés. Si no aparece ninguno, ya está.
8. **Cancelá la licencia y revocá los certificados.** Con fecha en el calendario: la renovación automática es silenciosa y te enterás en la factura de marzo.

---

## Preguntas frecuentes

### ¿Es seguro abrir el puerto RDP a internet?

No, y no hay matiz. Un escritorio remoto publicado en el 3389 queda listado en los buscadores de dispositivos expuestos que barren el espacio IPv4 de forma continua, y el acceso se remata después en foros. Si por alguna razón operativa tenés que dejarlo publicado hoy, el mínimo es restringirlo por IP de origen, exigir segundo factor en el inicio de sesión de Windows y bloquear la cuenta a los cinco intentos fallidos. Es un parche para esta semana, no una arquitectura.

### ¿Cuánto cuesta una VPN empresarial por usuario al mes?

De USD 0 a USD 18 con precio de lista público, a julio de 2026: Cloudflare Access gratis hasta 50 usuarios y USD 7 arriba de eso, Twingate desde USD 5 en Teams y USD 10 en Business, Tailscale desde USD 8 en Standard y USD 18 en Premium. Una VPN de appliance no se cotiza por usuario sino por equipo más mantenimiento anual, y ahí se esconde el costo real: sumá licencia, soporte y horas de administración, y dividí por la cantidad de gente que la usa.

### La VPN de la empresa está muy lenta, ¿cómo la mejoro?

Verificá primero si el problema es *backhaul*: si el tráfico de internet de los remotos pasa por la oficina antes de salir, cada video hace un viaje de ida y vuelta innecesario. Activar *split tunneling* en la VPN actual suele resolver la mitad de los reclamos sin gastar un peso. Si sigue lenta, lo saturado es el enlace de subida de la oficina o el concentrador, y ahí la conversación pasa a ser de arquitectura y no de configuración.

### Tailscale u OpenVPN, ¿cuál conviene para una empresa?

Depende de quién lo va a mantener. OpenVPN es gratis en licencia y caro en horas: alguien administra la autoridad de certificados, los renueva y actualiza el servidor. Tailscale cuesta USD 8 por usuario/mes en Standard y elimina ese trabajo. Si tenés una persona de sistemas con tiempo y ganas, OpenVPN funciona perfecto. Si esa persona ya está desbordada, la licencia gratuita te sale carísima.

### ¿Qué hago con los proveedores externos que necesitan entrar?

Recurso propio, política propia y fecha de vencimiento. Es donde estos productos ganan por goleada: al proveedor del ERP le das un solo servidor y un solo puerto, sin agente en su máquina. Cloudflare permite incluso autenticar contratistas con un código de un solo uso por email, sin crearles usuario en tu directorio. Vale la pena hacerlo aunque el proveedor se ofenda un poco: en el DBIR 2026 casi la mitad de las brechas —48%— involucró a un tercero, un 60% más que el año anterior.

### ¿Los precios de esta guía siguen vigentes?

Los tres fabricantes publican precio de lista y lo cambian sin aviso, y en este rubro las reestructuraciones de planes son frecuentes. Las cifras de acá se consultaron el 26 de julio de 2026 en las páginas de precios enlazadas en la tabla. Antes de llevar un número a una reunión, abrilas y confirmalo — es un minuto y te ahorra quedar mal por una diferencia de dos dólares.
