---
title: "EDR o antivirus: cuándo se justifica el salto en una PyME"
subtitle: Tres preguntas que deciden si necesitás EDR, por qué el benchmark que todo el mundo cita se quedó sin la mitad de los fabricantes en 2025, y qué mirar en su lugar.
excerpt: El EDR cuesta entre tres y diez veces más que un antivirus y exige alguien que lo mire. Las tres preguntas que definen si te corresponde, y cómo evaluar cuando MITRE dejó de cubrir a la mitad del mercado.
type: guide
status: published
category: antivirus-y-edr
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-20
products:
  - microsoft-defender-for-endpoint-p2
  - eset-protect-enterprise
  - crowdstrike-falcon
  - bitdefender-gravityzone-business-security-premium
meta_title: "EDR o antivirus: cuándo se justifica el salto en una PyME"
meta_description: "Las tres preguntas que definen si necesitás EDR, por qué MITRE se quedó sin Microsoft, SentinelOne y Palo Alto en 2025, y qué mirar cuando el benchmark falla."
---

La diferencia entre un antivirus y un EDR no es cuánto detecta cada uno. Es **qué pasa a las tres de la mañana cuando detecta algo**.

Un antivirus bloquea y sigue. Un EDR bloquea, graba todo lo que pasó antes y después, y te deja una línea de tiempo para reconstruir por dónde entró. La primera herramienta es un portero. La segunda es un portero más las cámaras más alguien que las mire.

Ese "alguien que las mire" es el que decide si el salto tiene sentido, y es justo el que no aparece en ninguna presentación de venta. Un EDR sin nadie mirándolo es un antivirus caro con una consola llena de alertas que nadie cierra.

Esta guía es para decidir. No compara productos —eso está en la [comparativa de Bitdefender, Kaspersky y ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset)—: define si te corresponde dar el salto, y cómo evaluar en un año donde el termómetro de referencia del rubro se quedó a medias.

---

## La diferencia que importa no es la que te venden

La versión comercial dice que el antivirus detecta por firmas y el EDR por comportamiento. Es cierto e irrelevante: desde hace años cualquier producto de nivel empresarial, incluidos los que se venden como "antivirus", hace análisis de comportamiento. La distinción por firmas describe el mercado de 2015.

La diferencia real es de **capacidad operativa**, y son tres cosas concretas:

| | Antivirus de empresa | EDR |
|---|---|---|
| Cuando detecta algo | Bloquea y registra el evento | Bloquea, y conserva la cadena completa: qué proceso lo lanzó, qué escribió, con quién habló |
| Retención de telemetría | Días, y solo de eventos de seguridad | Semanas o meses, de toda la actividad de procesos |
| Búsqueda retroactiva | No existe | Podés preguntar "¿en qué equipos se ejecutó este archivo el mes pasado?" |
| Respuesta remota | Cuarentena del archivo | Aislar el equipo de la red, matar procesos, ejecutar comandos |
| Requiere | Que alguien atienda una alerta ocasional | Que alguien revise una cola de forma sostenida |

La última fila es la que rompe implementaciones. Las otras cuatro son funciones que se compran; esa es una función que se dota de personal.

Y hay un punto que la mayoría de las guías se saltea: **la búsqueda retroactiva es la razón principal para comprar EDR**. No es detectar mejor. Es que cuando te enterás de un problema tres semanas tarde —que es cómo te enterás casi siempre— tengas los datos para responder "¿a qué más llegó?". Sin esa respuesta, la única opción responsable es reinstalar todo.

---

## Las tres preguntas que deciden

Si respondés que sí a dos de las tres, el salto se justifica. Con una sola, casi nunca.

### 1. ¿Hay alguien que va a mirar la consola?

No hace falta un equipo de seguridad. Hace falta una persona con nombre y apellido, con tiempo asignado, que entre varias veces por semana y cierre lo que quedó abierto. Puede ser interna o puede ser el proveedor de sistemas, si está contratado explícitamente para eso y no "cuando pueda".

Si no podés nombrar a esa persona hoy, la respuesta es no. Comprar EDR sin ella produce el peor resultado posible: pagás el precio de una capacidad que no tenés, y encima generás la sensación de estar cubierto.

La alternativa honesta cuando no hay nadie es un **EDR gestionado** —el fabricante o un tercero opera la consola por vos, con un costo mensual adicional por equipo—. Es más caro por licencia y sale mucho más barato que el escenario de comprarlo y no mirarlo.

### 2. ¿Cuánto te cuesta un día sin operar?

Si la empresa factura igual con los sistemas caídos un día, casi nada de esto se justifica económicamente. Si un día caído significa producción parada, entregas incumplidas o una multa contractual, el cálculo cambia de signo.

El marco para armar ese número y defenderlo ante quien firma está en la [nota sobre ROI y roles en ciberseguridad](/guia/ciberseguridad-importancia-roi-roles-certificaciones). Lo importante es hacerlo antes de pedir presupuesto, no después.

### 3. ¿Tenés algo que a alguien le sirva más que a vos?

Datos de clientes, propiedad intelectual, acceso a la infraestructura de otras empresas. Si sos proveedor de compañías más grandes, ya estás en la cadena de suministro de alguien, y eso te convierte en objetivo por transitividad aunque tu facturación sea chica.

Este es el caso donde más seguido se subestima el riesgo. La empresa de veinte personas que administra los sistemas de tres clientes grandes tiene un perfil de amenaza que no se parece en nada al de una de veinte personas que vende al público.

---

## El problema de evaluar en 2026: el termómetro se vació

Acá es donde el consejo habitual —"mirá los resultados de MITRE"— dejó de funcionar bien, y conviene saberlo antes de apoyar una compra en eso.

Las [ATT&CK Evaluations de MITRE](https://evals.mitre.org/) son el ejercicio de referencia del sector: se ejecuta una cadena de ataque real contra los productos y se publica, paso por paso, qué vio cada uno. No hay puntaje ni ganador; MITRE es explícito en que **"las evaluaciones no rankean fabricantes, sino que proveen resultados objetivos y basados en evidencia"**.

En la ronda de 2025 participaron once fabricantes: Acronis, AhnLab, CrowdStrike, Cyberani, Cybereason, Cynet, ESET, Sophos, Trend Micro, WatchGuard y WithSecure. Los escenarios se modelaron sobre Scattered Spider —la primera vez que la evaluación incluyó ataques a infraestructura en la nube— y sobre Mustang Panda.

Lo que falta en esa lista es más informativo que lo que está. **Microsoft se bajó el 13 de junio, y SentinelOne y Palo Alto Networks el 12 de septiembre**, según [el relevamiento de Infosecurity Magazine](https://www.infosecurity-magazine.com/news/cyber-vendors-pull-out-mitre/). Las tres razones publicadas apuntan al mismo lugar —reasignar recursos de ingeniería—: Microsoft dijo que le permite "enfocar todos nuestros recursos en la Secure Future Initiative", SentinelOne que va a "priorizar recursos de producto e ingeniería en iniciativas centradas en el cliente", y Palo Alto que "acelera innovaciones críticas de plataforma".

Cruzado con los seis productos que cubrimos en [antivirus y EDR](/productos/antivirus-y-edr), el panorama queda así:

| Producto | ¿Datos de MITRE 2025? |
|---|---|
| [CrowdStrike Falcon](/producto/crowdstrike-falcon) | Sí, participó |
| [ESET PROTECT Enterprise](/producto/eset-protect-enterprise) | Sí, participó |
| [Microsoft Defender for Endpoint P2](/producto/microsoft-defender-for-endpoint-p2) | No, se retiró |
| [SentinelOne Singularity](/producto/sentinelone-singularity) | No, se retiró |
| [Bitdefender GravityZone](/producto/bitdefender-gravityzone-business-security-premium) | No figura en la ronda 2025 |
| [Kaspersky Next EDR Optimum](/producto/kaspersky-next-edr-optimum) | No figura en la ronda 2025 |

Cuatro de seis sin datos comparables del año. Si tu proceso de compra era "elijo el que mejor salió en MITRE", en 2026 ese proceso te deja eligiendo entre dos.

La ausencia de un fabricante en la evaluación **no es evidencia de que su producto sea peor**. Las razones publicadas son plausibles y el ejercicio es genuinamente caro de preparar. Lo que sí cambia es tu situación como comprador: perdiste una fuente independiente y te quedaste con material producido por quien te vende. Eso hay que compensarlo con otras cosas, no ignorarlo.

---

## Qué mirar cuando el benchmark no alcanza

Cinco criterios que podés verificar vos, ordenados por cuánto predicen el resultado real de la implementación.

**Prueba piloto en tus propios equipos, con tus propias aplicaciones.** Treinta días, diez a quince equipos representativos, incluyendo los que corren el software raro del rubro. Lo que estás midiendo no es si detecta el malware de prueba —lo va a detectar—: es cuántos falsos positivos genera contra tu ERP, tu sistema de facturación y esa aplicación de escritorio de 2011 que nadie puede reemplazar. Un producto que bloquea tu facturación es un producto que van a desactivar.

**Volumen de alertas por semana, medido en el piloto.** Extrapolalo a tu parque completo y preguntate si la persona de la pregunta 1 puede con eso. Este número solo aparece probando; ningún fabricante lo publica porque depende enteramente de tu entorno.

**Qué pasa cuando el equipo está sin conexión.** Los portátiles pasan la mitad del tiempo fuera de la red de la oficina. Preguntá específicamente qué capacidad de detección y respuesta queda cuando el agente no llega a la consola, y por cuánto tiempo retiene la telemetría local hasta poder sincronizarla.

**Retención de telemetría incluida en el precio de lista.** Es el truco de licenciamiento más común del rubro: el producto incluye siete o treinta días, y la retención que hace falta para la búsqueda retroactiva —que es la razón por la que estás comprando— se factura aparte. Pedí el precio con la retención que vas a usar, no el precio base.

**Soporte en tu zona horaria y en español.** Cuando aísles un equipo de producción por error un viernes a las siete de la tarde, la calidad del soporte deja de ser un renglón de la planilla. En LATAM esto varía muchísimo entre fabricantes y suele depender más del canal local que de la marca.

---

## El costo que no está en la etiqueta

La opacidad de precios en este mercado es notable y conviene nombrarla. Microsoft, por ejemplo, **no publica precio de lista de Defender for Endpoint Plan 1 ni Plan 2 como productos independientes** en su [página de precios de Defender](https://www.microsoft.com/en-us/security/microsoft-defender-pricing): ahí figura el Microsoft Defender Suite a USD 12 por usuario y por mes con pago anual, y Microsoft 365 E5 a USD 60. Los valores de P1 y P2 que circulan en comparativas salen de revendedores y analistas, no del fabricante.

Eso obliga a pedir cotización para comparar, que es exactamente lo que el modelo busca. Cuando la pidas, exigí que incluya:

- **Retención de telemetría** al plazo que necesitás, no al de base
- **Precio de renovación**, no solo el de contratación
- **Costo del servicio gestionado** si no tenés a la persona de la pregunta 1
- **Módulos que se facturan aparte**: control de dispositivos, cifrado de disco, gestión de vulnerabilidades

Y el costo que ninguna cotización trae: **entre dos y seis semanas de trabajo interno** para desplegar agentes, ajustar exclusiones y afinar las reglas hasta que el ruido baje a un nivel manejable. Presupuestá ese tiempo o el proyecto se estanca al mes.

Un atajo antes de comprar nada nuevo: si ya pagás Microsoft 365 en un plan que incluye capacidades de Defender, puede que tengas EDR sin usar. Auditá la licencia actual antes de sumar otra.

---

## Cuándo no dar el salto

Cuatro situaciones donde la respuesta correcta es quedarse con el antivirus y gastar la plata en otra cosa. Vale más decirlas que venderlas.

- **No hay nadie que mire la consola y no vas a contratar servicio gestionado.** Ya está dicho, y es la más importante.
- **Todavía no tenés MFA en todas las cuentas.** Un EDR no impide que alguien entre con credenciales válidas. El orden correcto es autenticación primero: la [guía para configurar MFA en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) cuesta un fin de semana y cubre un vector que el EDR no toca. Si ya lo tenés, el paso siguiente es entender [por qué el MFA solo no alcanza](/guia/ya-tenes-mfa-y-no-alcanza).
- **Tus copias no están probadas.** Si nunca restauraste una, no sabés si funcionan. Un EDR reduce la probabilidad de un incidente; una copia probada reduce su costo, y ante ransomware es lo único que efectivamente te devuelve la operación. Empezá por ahí —incluido el correo, que casi nadie respalda: la guía sobre [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta) explica por qué.
- **Tu problema real entra por correo.** El grueso de los incidentes en PyME empieza en un mensaje, no en un archivo ejecutándose en un equipo. Si la puerta de entrada está sin cubrir, reforzar el endpoint es empezar por el final. La [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes) y la [guía de SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso) atacan ese frente, y la segunda no cuesta licencias.

Si venís armando el programa completo y no sabés en qué orden va cada cosa, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) ubica el endpoint dentro del resto de las prioridades.

---

## Preguntas frecuentes

### ¿El antivirus quedó obsoleto?

No. Todos los EDR de nivel empresarial incluyen el motor antimalware adentro: no son productos alternativos, uno contiene al otro. Lo que quedó obsoleto es el antivirus doméstico sin consola central en un entorno de empresa, porque no te deja ver el parque completo ni actuar sobre él.

### ¿Cuánto más caro es un EDR que un antivirus de empresa?

En el segmento PyME, típicamente entre tres y diez veces por equipo y por año, con una dispersión enorme según la retención de telemetría contratada y si sumás servicio gestionado. Comparar precios de lista sirve poco: la mayoría de los fabricantes no los publica —Microsoft entre ellos— y las diferencias reales aparecen recién en la cotización con la retención que vas a usar.

### ¿Sirve mirar los resultados de MITRE para elegir?

Sirve, con dos advertencias. La primera es que MITRE explícitamente no rankea: publica evidencia paso a paso y la interpretación es tuya. La segunda es de cobertura: en la ronda de 2025 participaron once fabricantes y Microsoft, SentinelOne y Palo Alto Networks no estuvieron, así que hay productos grandes sin datos comparables de ese año.

### ¿Puedo usar EDR sin equipo de seguridad?

Sí, contratando el servicio gestionado que ofrecen los fabricantes y varios integradores. Es la opción correcta para la mayoría de las PyMEs: sale más caro por licencia y evita el escenario habitual de una consola llena de alertas sin cerrar, que es el peor de todos porque además genera falsa tranquilidad.

### ¿Qué hago primero, EDR o backup?

Backup, y probado. Un EDR baja la probabilidad de que ocurra un incidente; una copia restaurable baja su costo cuando ocurre. Ante ransomware, lo único que te devuelve la operación es la copia. Además el backup no requiere que nadie mire una consola todos los días, que es la restricción que hace fracasar la mayoría de los proyectos de EDR en empresas chicas.

### ¿Tener Microsoft 365 significa que ya tengo EDR?

Depende del plan, y conviene auditarlo antes de comprar nada. Las capacidades de Defender for Endpoint se distribuyen entre planes independientes y paquetes de Microsoft 365, y hay empresas pagando una licencia que incluye funciones de EDR sin haberlas activado nunca. Revisá qué incluye tu licencia actual antes de sumar un producto nuevo.
