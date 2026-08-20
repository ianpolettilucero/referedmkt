---
title: "Acceso remoto seguro sin VPN: guía para PyMEs"
subtitle: Las nueve preguntas que te va a hacer el que firma el cheque, con el número que sostiene cada respuesta y la frase para decir en voz alta.
excerpt: Cloudflare Access es gratis hasta 50 usuarios. Ese número desarma la objeción de costo y te deja discutir lo que de verdad importa: el 3389 abierto.
type: guide
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-20
products: [cloudflare-access, tailscale-business, twingate]
meta_title: "Acceso remoto seguro sin VPN: guía para PyMEs"
meta_description: "Cómo dar acceso remoto a tus empleados sin VPN: precios de lista por persona, qué hacer con el RDP expuesto y cómo conseguir la aprobación en la reunión."
---

Sacar la VPN no se muere por un problema técnico. Se muere en la reunión, con tres preguntas: por qué cambiar algo que funciona, cuánto sale y quién lo mantiene.

La del medio hunde el proyecto antes de empezar, y tiene la respuesta más corta: hasta 50 personas, cero pesos. El plan gratuito de [Cloudflare Access](/producto/cloudflare-access) cubre 50 usuarios con acceso por identidad y túnel de salida; arriba de ese número el precio de lista es de USD 7 por usuario al mes ([planes de Cloudflare Zero Trust](https://www.cloudflare.com/plans/zero-trust-services/), consultados el 26 de julio de 2026).

Elegir producto es fácil: hay tres buenos y los tres andan. Lo difícil es la reunión. Cada título de acá abajo es una pregunta que te van a hacer, con el número que sostiene la respuesta y la frase para decir en voz alta. No hay despliegues propios ni mediciones de laboratorio: hay precios de lista, documentación de los fabricantes e informes anuales que podés descargar. Cada cifra dice de dónde sale.

## La versión de un minuto

1. La VPN te mete en la red entera; el reemplazo te da una aplicación y nada más.
2. Hoy se entra más por lo publicado que por engañar a alguien: la explotación de vulnerabilidades es el vector de acceso inicial número uno del DBIR 2026, con 31% de los casos contra 20% el año anterior.
3. Cloudflare Access sale USD 0 hasta 50 personas y USD 7 por usuario/mes arriba de eso.
4. El escritorio remoto en el puerto 3389 es la puerta a cerrar esta semana, con esto o sin esto.
5. La primera aplicación anda en una tarde; la VPN se apaga cuando quieras.
6. El ERP viejo también entra, sin tocarle una línea al servidor.

---

## "¿Qué tiene de malo la VPN que ya pagamos?"

Nada, mientras nadie robe una credencial. Cuando alguien la roba, la VPN hace lo que se le pidió: pone a esa persona adentro de la red con el mismo alcance que tendría sentada en su escritorio. Adentro están el controlador de dominio, la NAS, las impresoras y el servidor de facturación. La VPN no distingue entre "Marcela de compras necesita el ERP" y "alguien con la clave de Marcela quiere ver qué más hay".

Las credenciales se roban seguido: el 79% de los ataques de ransomware relevados arrancó por vía de identidad ([Sophos, *State of Ransomware 2026*](https://www.sophos.com/en-us/blog/sophos-state-of-ransomware-2026)). El informe lo publica una empresa que vende seguridad, pero el orden interno del dato aguanta la pinza: correo malicioso 26%, phishing 24%, credenciales comprometidas 23%, explotación de vulnerabilidades 18%. Los dos primeros se atacan por otro lado, con el [filtro de correo que te convenga](/comparativa/comparativa-seguridad-email-pymes).

El otro defecto es más chico. Toda VPN clásica manda el tráfico a un concentrador y lo devuelve: si el concentrador está en Villa Crespo y la persona está en Mendoza abriendo un archivo de Drive, ese archivo viaja a Buenos Aires y vuelve. Se llama *backhaul* y es la primera hipótesis a descartar cuando alguien dice que la VPN está lenta.

[Tailscale](/producto/tailscale-business) sostiene, en su propio material, que el overhead es de menos de 1 ms con conexión directa entre nodos. Son números del fabricante, sin verificación independiente. La letra chica también la publica la empresa: contra un NAT simétrico —habitual en redes móviles y en salidas de nubes públicas— la perforación falla y el tráfico cae a un relay DERP ([tipos de conexión de Tailscale](https://tailscale.com/docs/reference/connection-types)). Sigue cifrado y funcionando, pero ahí el milisegundo prometido no existe.

> **Para decir en la reunión:** "La VPN funciona. El problema es que le da la red entera a cualquiera que tenga una contraseña, y las contraseñas se roban todas las semanas."

---

## "¿Esto no es lo mismo que abrir el escritorio remoto?"

Es lo contrario, y es la confusión más cara de esta conversación.

Abrir el escritorio remoto significa publicar el puerto 3389 de un servidor Windows directo a internet. En la lista de puntos de entrada que releva Sophos, "aplicaciones o sistemas expuestos" encabeza con 38%, por encima de los dispositivos de usuario (30%), los firewalls (21%), las VPN (8%) y el IoT (3%). En una PyME típica ese 38% se llama RDP publicado, ERP publicado y panel del hosting.

No es que "algún día te encuentren": buscadores como Shodan y Censys barren el espacio IPv4 completo de forma continua y publican el resultado. No hace falta que un atacante te elija, alcanza con que consulte una lista. Después ese acceso se vende.

El 96% de las víctimas de ransomware con tamaño de organización conocido en el [DBIR 2026 de Verizon](https://www.verizon.com/business/resources/reports/dbir/) fueron PyMEs. Las organizaciones de 100 a 250 empleados detuvieron el ataque antes del cifrado en apenas el 34% de los casos, contra el 46% de las de 3.001 a 5.000 (Sophos 2026). No las atacan mejor: del otro lado no hay nadie mirando a las tres de la mañana.

Un reemplazo de VPN no abre nada. Se instala un agente adentro de tu red que sale hacia afuera —conexión saliente, la que tu firewall ya permite— y se encuentra con el usuario en el medio. El *connector* de [Twingate](/producto/twingate) corre en un contenedor con 1 vCPU y 512 MB de RAM como mínimo declarado, y una máquina virtual de 1 CPU y 2 GB alcanza para cientos de usuarios ([buenas prácticas de connectors](https://www.twingate.com/docs/connector-best-practices)). Cloudflare lo llama Tunnel y hace lo mismo. Desde internet, tu servidor deja de existir.

> **Para decir en la reunión:** "Hoy el servidor está publicado a internet y lo escanean todos los días. Con esto deja de estarlo. No abrimos un puerto: cerramos el que está abierto."

---

## "¿Cuánto sale por persona?"

Entre USD 0 y USD 18 por usuario al mes con precio de lista. Precios consultados el 26 de julio de 2026:

| Producto | Plan gratuito | Plan de entrada | Escalón siguiente |
|---|---|---|---|
| [Cloudflare Zero Trust](https://www.cloudflare.com/plans/zero-trust-services/) | 50 usuarios, ZTNA y gateway completos | USD 7 / usuario / mes, sin tope | Enterprise a cotizar |
| [Twingate](https://www.twingate.com/pricing) | 5 usuarios, 10 redes, 50 recursos | USD 5 / usuario / mes (Teams, hasta 100) | USD 10 (Business, hasta 500) |
| [Tailscale](https://tailscale.com/pricing) | 6 usuarios, dispositivos personales ilimitados | USD 8 / usuario / mes (Standard, con SCIM) | USD 18 (Premium) |

Dos aclaraciones que la tabla se come. Twingate baja a USD 4,25 y USD 8,50 con facturación anual. Y los USD 7 de Cloudflare son de pago por uso con facturación anual: si pasás de 50 usuarios no pagás solo por los que sobran, pasás a pagar por todos.

Los tres publican el precio de entrada en la web, sin "contactá a ventas". El escalón enterprise sí se cotiza.

La comparación que conviene llevar hecha no es contra el incidente, es contra la VPN que ya pagás: licencia más las horas del proveedor que la mantiene. Con números redondos para ver la forma: appliance amortizado más soporte anual, USD 1.200; dos horas mensuales del proveedor a USD 40, otros USD 960 al año; total USD 2.160, dividido entre 25 personas da USD 7,20 por persona por mes. Cambiá esos tres números por los tuyos —están en la factura y en los partes de horas—. El presupuesto completo de seguridad está en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

> **Para decir en la reunión:** "Hasta 50 personas, cero. Si pasamos de ahí, son siete dólares por persona por mes."

---

## "¿Y si se cae el proveedor?"

Es la pregunta que el mercado contesta con menos honestidad. La sigla del folleto es ZTNA, *Zero Trust Network Access*: el nombre técnico de dar acceso a una aplicación en vez de a una red.

Depende de qué se caiga. Los tres separan el plano de control —el servicio que decide quién entra— del plano de datos, por donde viaja tu tráfico. Si se cae el control, las sesiones establecidas siguen andando; lo que no podés es autenticar gente nueva. Mal rato, no parálisis. Cloudflare declara red propia en más de 330 ciudades.

Lo que nadie pone en el folleto: agregás una dependencia de un tercero, y el tercero es el problema que más rápido crece. El DBIR 2026 cuenta un tercero involucrado en el 48% de las brechas, un 60% más que el año anterior. Sacar la VPN no te saca de esa estadística: te cambia de proveedor adentro de ella. Decilo vos antes de que lo diga otro.

La mitigación es aburrida y funciona: una vía de emergencia documentada, acceso por consola física o IPMI con la credencial guardada fuera del sistema. Misma lógica que la cuenta *break-glass* de identidad, que está en [la guía de los huecos de MFA](/guia/ya-tenes-mfa-y-no-alcanza).

> **Para decir en la reunión:** "Si se cae el proveedor, el que está conectado sigue trabajando y no damos accesos nuevos por un rato. Y sí, dependemos de un tercero más: por eso dejamos una vía de emergencia aparte."

---

## "¿Los de sistemas van a tener que aprender otra cosa?"

Sí: menos de lo que temés y más de lo que dice el vendedor. Son dos conceptos, no veinte. Primero: en vez de rangos de IP definís recursos —esta aplicación, este servidor, este puerto— y decís qué grupo del directorio llega a cada uno. Segundo: la política se escribe en texto y se versiona. Las ACL de Tailscale viven en un archivo HuJSON que se guarda en Git; Twingate tiene proveedor oficial de Terraform.

Lo que se deja de hacer es más largo: no hay más certificados que vencen un domingo, ni listas de IP que actualizar, ni "andá al firewall y abrile el puerto a este". El alta y la baja pasan al directorio de identidad, y con SCIM el que se va queda sin acceso solo.

Una verificación que los comparativos suelen saltear: antes de firmar, mirá en qué idioma están la documentación y el soporte, y contá cuántos partners listan en tu país. Los tres publican buscador de partners y se responde en cinco minutos. Si tu esquema depende de que venga alguien a la oficina con una orden de trabajo, ese renglón pesa más que dos dólares de diferencia por usuario.

> **Para decir en la reunión:** "Dos conceptos nuevos y dos semanas de curva. A cambio dejamos de administrar certificados y listas de IP, y las bajas se aplican solas."

---

## "¿Qué pasa con el que entra desde su notebook personal?"

Con VPN, la notebook personal del contador externo entra igual que la de la empresa: si tiene la credencial, entra. Con acceso por identidad podés exigir condiciones antes de dejarlo pasar —disco cifrado, antivirus corriendo, sistema actualizado—. Se llama *device posture*. Cuál producto soporta cuál EDR, y en qué plan, cambia seguido: confirmalo en la documentación contra el que ya tenés puesto.

La política que conviene escribir primero, en dos líneas: equipo administrado por la empresa, acceso completo; equipo personal, solo la aplicación web por navegador y nada de red. Cloudflare permite publicar una aplicación interna sin instalar agente en la máquina del usuario, así que la segunda mitad no requiere pedirle nada a nadie. Es la respuesta correcta para el estudio contable y para el proveedor que necesita ver un sistema durante dos meses.

La trampa del modelo entero: todo se apoya en la identidad. Si tu segundo factor tiene agujeros, mudaste el problema a un lugar donde no lo estás mirando. Cerrá eso antes de migrar, con [cómo desplegar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana), y si el acceso incluye cuentas de administración, mirá el [análisis de costo total de las YubiKey 5 en Argentina](/resena/analisis-yubikey-5-series-costo-total-argentina).

> **Para decir en la reunión:** "Al equipo personal le damos la aplicación por navegador y nada más. Al de la empresa, con antivirus y disco cifrado, le damos el resto."

---

## "¿Cuánto tarda en estar andando?"

El primer recurso protegido, menos de una hora según los tres fabricantes. La migración completa no la publica ninguno: el rango de uno a dos meses es una estimación para una PyME de una o dos sedes, no un dato medido.

La promesa del primer día se audita gratis: levantá el plan gratuito, publicá un recurso de prueba y cronometrá. Llegá con una demostración, no con una diapositiva.

Lo que lleva semanas no es el producto: es el inventario. Lo caro no es escanear, es lo que aparece: una aplicación publicada hace años, sin documentar, con algo que factura del otro lado. Presupuestá ese descubrimiento en el plan.

Un calendario que se sostiene: semana 1, inventario y prueba con tres personas de sistemas. Semana 2, un área completa con la VPN todavía prendida. Semanas 3 y 4, el resto. Semana 5, apagar la VPN.

> **Para decir en la reunión:** "En una tarde tengo la primera aplicación andando para mostrarte. Un mes para pasar a todos, con la VPN prendida en paralelo."

---

## "¿Y el servidor viejo del ERP?"

Entra, y no hay que tocarlo.

Es la objeción que más fácil frena el proyecto y casi siempre está mal fundada. Se asume que un producto moderno solo sirve para aplicaciones web y que el ERP con cliente pesado sobre un Windows Server 2012 queda afuera. No: cualquiera de los tres publica un recurso por IP y puerto, incluido el 3389 del escritorio remoto y el 1433 de SQL Server. El cliente del ERP se conecta a la dirección de siempre; lo que cambia es que esa dirección solo resuelve para quien está autenticado. Tailscale además tiene *subnet routers*, que exponen un segmento entero a través de un nodo: la vía más corta para una red vieja llena de cosas que no aceptan agente.

Un servidor de 2012 al que llegás por acceso controlado sigue siendo un servidor de 2012: esto no lo parchea. El DBIR 2026 señala que la explotación de vulnerabilidades pasó a ser el vector de entrada número uno con 31% de los casos, y que el 80% de las vulnerabilidades persistentes del catálogo KEV se registraron antes de 2024.

> **Para decir en la reunión:** "El ERP entra sin tocarle nada. Se conecta igual que hoy, con la diferencia de que deja de estar visible desde internet."

---

## "¿Por qué ahora y no el año que viene?"

Porque el 3389 está abierto ahora. Es la única parte que no admite negociación de calendario.

Hay un dato regional que en una reunión argentina, chilena o mexicana pega distinto que un promedio global. En LATAM el 85% de las organizaciones tiene firewall, el 82% hace backup y el 73% tiene VPN. El MFA lo tiene apenas el 57%, y entre el personal no técnico la doble autenticación en cuentas laborales baja al 52,2% ([ESET Security Report 2026](https://www.welivesecurity.com/es/informes/eset-security-report-2026-ciberseguridad-empresas-latinoamerica/), sobre 962 organizaciones de 10 países). La región compró el portón y el túnel, y dejó la cerradura para el año que viene. El argumento de retorno para el presupuesto entero está en [la pieza sobre ROI, roles y certificaciones](/guia/ciberseguridad-importancia-roi-roles-certificaciones).

> **Para decir en la reunión:** "Lo del costo lo discutimos en diciembre. Lo del puerto 3389 abierto lo cierro esta semana."

---

## Qué elegir según tu escenario

**Una sola oficina, un servidor, menos de 50 personas.** Cloudflare Access, plan gratuito: **USD 0 al mes.** Publicás el ERP y el escritorio remoto con Cloudflare Tunnel, conectás Google Workspace o Entra ID como proveedor de identidad, y el servidor deja de tener puertos abiertos. Es el plan gratuito más generoso de los tres en cantidad de usuarios.

**Varias sedes que necesitan verse entre sí.** Tailscale Standard a USD 8 por usuario/mes: **25 personas son USD 200 al mes.** Cuando el problema no es "gente entrando a un servidor" sino "sucursales viéndose entre ellas" —replicación de base, backup cruzado—, la malla resuelve en una tarde lo que con túneles sitio a sitio son semanas. La contra: al precio de lista de hoy dejó de ser el barato de los tres. A cambio, el aprovisionamiento por SCIM entra en su plan de entrada. Si tu caso es acceso a un servidor y no malla entre sedes, Twingate hace lo mismo por menos.

**Todo en la nube, nada propio salvo un par de máquinas virtuales.** Twingate Teams a USD 5 por usuario/mes: **15 personas son USD 75 al mes**, y por debajo de 5 usuarios el plan gratuito alcanza. Advertencia contraintuitiva: si de verdad no tenés nada on-premise, probablemente no necesites ninguno de los tres. Gastá esa plata en acceso condicional y en que el correo tenga copia propia — el porqué está en [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

**Servidor legacy, ERP con cliente pesado, RDP publicado.** Twingate Teams a USD 5 por usuario/mes: **20 personas son USD 100 al mes.** El connector va en un contenedor al lado del servidor y no hay que tocar nada más. Es el escenario donde la promesa de "menos de una hora" tiene más chances de cumplirse. Las fichas de los tres están en el [directorio de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

---

## Checklist de apagado de la VPN vieja

En este orden. El paso 6 importa aunque no hagas ninguno de los otros.

1. **Inventariá lo publicado.** Escaneá tu propia IP pública desde afuera. Anotá cada puerto que responde y quién lo usa.
2. **Levantá el reemplazo en paralelo.** Un connector, un recurso, tres personas de sistemas usándolo una semana. La VPN sigue prendida.
3. **Migrá por área, no por persona.** Un área completa, con su responsable avisado y una vía de reclamo clara.
4. **Revisá los accesos de terceros.** Contador, proveedor del ERP, soporte externo: cada uno a su recurso, con fecha de vencimiento y sin acceso a la red.
5. **Confirmá que no queda nadie en la VPN.** Mirá los registros siete días seguidos: el que entra una vez por semana no aparece en una muestra corta.
6. **Cerrá el 3389.** Sacá la regla de NAT, verificá desde afuera que no responde, y revisá también el 22, el 1433, el 3306 y la consola del firewall.
7. **Apagá el concentrador dos semanas antes de desinstalar.** Si aparece un reclamo, lo prendés.
8. **Cancelá la licencia y revocá los certificados.** Con fecha en el calendario: la renovación automática es silenciosa.

---

## Preguntas frecuentes

### ¿Es seguro abrir el puerto RDP a internet?

No, y no hay matiz. Un escritorio remoto publicado en el 3389 queda listado en los buscadores de dispositivos expuestos, y el acceso se remata después en foros. Si por alguna razón operativa tenés que dejarlo publicado hoy, el mínimo es restringirlo por IP de origen, exigir segundo factor en el inicio de sesión de Windows y bloquear la cuenta a los cinco intentos fallidos. Es un parche para esta semana, no una arquitectura.

### ¿Cuánto cuesta una VPN empresarial por usuario al mes?

De USD 0 a USD 18 con precio de lista público, a julio de 2026: Cloudflare Access gratis hasta 50 usuarios y USD 7 arriba de eso, Twingate desde USD 5 en Teams y USD 10 en Business, Tailscale desde USD 8 en Standard y USD 18 en Premium. Una VPN de appliance no se cotiza por usuario sino por equipo más mantenimiento anual, y ahí se esconde el costo real.

### La VPN de la empresa está muy lenta, ¿cómo la mejoro?

Verificá primero si el problema es *backhaul*: si el tráfico de internet de los remotos pasa por la oficina antes de salir, cada video hace un viaje innecesario. Activar *split tunneling* suele resolver la mitad de los reclamos sin gastar un peso. Si sigue lenta, lo saturado es el enlace de subida de la oficina o el concentrador, y la conversación pasa a ser de arquitectura.
