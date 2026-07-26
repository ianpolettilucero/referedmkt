---
title: Qué Es un EDR y En Qué Se Diferencia de un Antivirus
subtitle: Detección y respuesta en endpoints, explicada para quien tiene que firmar la compra
excerpt: Un EDR no es "un antivirus mejor". Es una categoría distinta que resuelve un problema distinto, y confundirlas es lo que hace que muchas PyMEs paguen de más por algo que no van a poder operar. Esta guía explica qué hace realmente un EDR, cuándo lo necesitás y cómo evaluarlo sin ser especialista.
category: Antivirus y EDR para empresas
author: Ian Poletti Lucero
type: guide
status: published
published_at: 2026-07-26
meta_title: "Qué Es un EDR: Diferencias con Antivirus y Cuándo lo Necesitás"
meta_description: Qué hace un EDR, en qué se diferencia de un antivirus tradicional, qué significan EPP, XDR y MDR, y cómo evaluarlo si sos PyME y no tenés equipo de seguridad.
---

La escena se repite y siempre duele igual: una empresa de sesenta empleados amanece con los servidores cifrados y una nota de rescate. Tenían antivirus. Estaba actualizado. Estaba corriendo en todas las máquinas. El proveedor lo confirma sin problema porque es cierto: el antivirus funcionó exactamente como fue diseñado.

El problema es que nadie le explicó al dueño para qué fue diseñado.

Esta guía es esa explicación. Qué hace un antivirus, qué hace un EDR, por qué la diferencia dejó de ser académica hace años, y cómo decidir cuál necesitás sin depender de la palabra del vendedor que te está cotizando.

> **TL;DR** — Un antivirus tradicional bloquea amenazas que ya conoce. Un EDR registra todo lo que pasa en cada equipo, detecta comportamientos sospechosos aunque el archivo sea nuevo, y te deja responder y reconstruir qué pasó. La contra: genera alertas que alguien tiene que mirar. Si no tenés a esa persona, un EDR solo te da la sensación de estar protegido. En ese caso el camino correcto es un EPP moderno con buena detección automática, o directamente un servicio gestionado (MDR) donde el análisis lo hace un tercero.

## Por qué el antivirus tradicional dejó de alcanzar

El antivirus clásico nació para resolver un problema concreto: identificar archivos maliciosos conocidos. Funcionaba comparando cada archivo contra una base de firmas —una huella digital de cada malware ya identificado y catalogado. Si coincidía, se bloqueaba.

Ese modelo fue muy efectivo durante mucho tiempo. Después el ataque cambió de forma.

### El modelo de firmas y su límite estructural

Una firma solo existe después de que alguien detectó, capturó y analizó una muestra del malware. Eso implica que siempre hay una ventana entre que una amenaza aparece y que tu antivirus la reconoce.

Los atacantes explotan esa ventana de forma industrial. Recompilar un binario con cambios mínimos, ofuscarlo o empaquetarlo distinto produce un archivo con firma diferente que hace exactamente lo mismo. No es una técnica sofisticada ni cara: está automatizada y es parte del flujo de trabajo estándar de cualquier operación de crimeware.

El resultado es que el volumen de muestras únicas creció a una escala donde el enfoque de "catalogar cada variante" ya no da abasto por sí solo.

### Malware sin archivo

Buena parte de los ataques actuales no dejan un ejecutable en disco para escanear. El código malicioso vive en memoria, se inyecta en un proceso legítimo que ya está corriendo, o se ejecuta directamente desde un script que se descarga y corre sin tocar el disco.

Un antivirus centrado en escanear archivos no tiene mucho que escanear en ese escenario. Y las técnicas para lograrlo dejaron de ser exclusivas de actores estatales: están en frameworks públicos que cualquiera con conocimiento intermedio sabe usar.

### Living off the land

La técnica que más complica al modelo de firmas es también la más simple conceptualmente: usar las herramientas que ya vienen con el sistema operativo.

PowerShell, WMI, PsExec, las utilidades administrativas de Windows, las herramientas de scripting de cualquier Linux. Todas son legítimas, todas están firmadas por el fabricante, todas tienen usos administrativos válidos y ninguna se puede bloquear sin romper la operación de la empresa.

Cuando el atacante se mueve por la red usando exclusivamente herramientas legítimas, no hay archivo malicioso que detectar. Hay comportamiento sospechoso. Y detectar comportamiento es un problema completamente distinto al de detectar archivos.

### Ransomware operado por humanos

El ransomware masivo y automático —el que se propagaba solo y cifraba lo que encontraba— sigue existiendo, pero el que genera las pérdidas grandes es otro.

En una operación moderna hay personas del otro lado tomando decisiones. El acceso inicial se consigue por una credencial comprada, una VPN sin segundo factor o un servidor expuesto sin parchear. Después viene un período de reconocimiento que puede durar días o semanas: mapear la red, escalar privilegios, identificar dónde están los backups, entender el negocio para calibrar el rescate.

El cifrado es el último paso, y solo ocurre cuando el atacante ya destruyó o cifró los backups y está seguro de tener posición.

Ese período intermedio es la oportunidad real de detección. Un antivirus no lo ve porque no hay malware ejecutándose todavía: hay alguien usando credenciales válidas para hacer cosas administrativas. Ahí es exactamente donde entra un EDR.

## Qué hace un EDR exactamente

EDR es la sigla de *Endpoint Detection and Response*. Cada palabra importa.

### Telemetría continua del endpoint

Un EDR instala un agente en cada equipo que registra actividad de forma permanente: procesos que arrancan y con qué argumentos, relaciones padre-hijo entre procesos, conexiones de red, escrituras y lecturas en disco, cambios en el registro, uso de credenciales, carga de módulos en memoria.

Ese flujo se envía a una consola central donde queda almacenado. La diferencia conceptual con un antivirus es grande: el antivirus toma una decisión de bloquear o permitir y se olvida. El EDR guarda el registro de lo que pasó, haya bloqueado algo o no.

Esa memoria es lo que después te permite responder la pregunta que importa cuando hay un incidente: *¿por dónde entraron y qué tocaron?*

### Detección por comportamiento

Sobre esa telemetría corren reglas y modelos que no buscan archivos conocidos sino secuencias sospechosas.

Un documento de Office que lanza PowerShell, que descarga contenido de internet y lo ejecuta en memoria, es una cadena que casi nunca tiene una explicación legítima. Cada eslabón por separado es normal. La secuencia completa no lo es.

Otros ejemplos de lo que un EDR detecta y un antivirus estructuralmente no puede ver: un proceso accediendo a la memoria del subsistema de autenticación de Windows para extraer credenciales, borrado masivo de copias sombra del volumen —el paso previo clásico al cifrado—, una cuenta de servicio que de golpe empieza a conectarse por SMB a cincuenta equipos, o una herramienta administrativa legítima corriendo desde una carpeta temporal a las tres de la mañana.

### Respuesta y contención

La R de la sigla es la que suele pasarse por alto en la comparación, y es una de las diferencias más prácticas.

Un EDR permite aislar un equipo de la red desde la consola, manteniendo únicamente la conexión con la plataforma de seguridad para poder seguir investigando. En un escenario de ransomware activo, la diferencia entre aislar en cinco minutos y aislar en dos horas es la diferencia entre perder un equipo y perder la empresa.

También permite matar procesos remotamente, poner en cuarentena archivos en todos los equipos a la vez, revertir cambios en algunos casos, y ejecutar comandos de investigación sin ir físicamente a la máquina.

### Investigación forense retrospectiva

Cuando aparece información nueva sobre una amenaza —un indicador publicado, un hash, un dominio de comando y control— podés buscar hacia atrás en la telemetría almacenada si eso apareció alguna vez en tu red.

Es una capacidad que no existe sin registro histórico. Con un antivirus la pregunta "¿estuvimos comprometidos por esto el mes pasado?" simplemente no tiene forma de responderse.

## EDR, EPP, XDR y MDR: qué significa cada sigla

El mercado usa estas siglas de forma bastante laxa y eso genera confusión en las cotizaciones. La distinción práctica:

| Sigla | Qué es | Quién opera | Para quién tiene sentido |
|---|---|---|---|
| AV | Antivirus tradicional, detección por firmas | Nadie, es automático | Uso doméstico. Insuficiente como única capa en una empresa |
| EPP | Plataforma de protección de endpoint: antivirus moderno con machine learning, control de dispositivos, firewall, anti-exploit | Prácticamente nadie, prevención automática | PyMEs sin equipo de seguridad |
| EDR | Detección y respuesta: telemetría, detección por comportamiento, herramientas de investigación y contención | Alguien tiene que mirar y decidir | Empresas con al menos una persona dedicada a seguridad |
| XDR | EDR extendido a otras fuentes: identidad, correo, red, cloud | Equipo de seguridad | Organizaciones con varias fuentes de telemetría ya desplegadas |
| MDR | Servicio gestionado: la tecnología más un equipo externo que monitorea 24/7 | El proveedor | PyMEs y medianas que necesitan capacidad de detección pero no pueden sostener un equipo propio |

La confusión más costosa es entre EDR y MDR. Un EDR es una herramienta. Sin alguien que analice sus alertas, es una herramienta que genera trabajo sin producir seguridad.

## Qué necesita una PyME realmente

Acá es donde la mayoría de las guías comerciales te empujan hacia arriba en la escala. Vale la pena ser más honesto que eso.

### El problema del equipo que no existe

Un EDR bien configurado en una empresa de cien empleados genera alertas todos los días. La mayoría son ruido: comportamiento inusual pero legítimo, software interno que hace cosas raras, un administrador haciendo su trabajo de una forma que parece sospechosa.

Alguien tiene que separar el ruido de la señal. Esa persona necesita entender qué es normal en tu red, saber leer una cadena de procesos y tener criterio para decidir cuándo escalar. No es una tarea que se le pueda sumar al responsable de sistemas que ya está saturado con soporte, backups y el ERP.

Cuando ese rol no existe, lo que pasa en la práctica es predecible: las primeras semanas alguien mira la consola, después se empiezan a acumular alertas sin revisar, y a los tres meses nadie la abre. La empresa está pagando una licencia cara por una capacidad que no está usando, y lo peor es que cree estar cubierta.

### Cuándo alcanza un EPP moderno

Si tenés menos de cincuenta empleados, no manejás datos regulados y no tenés a nadie que pueda dedicarle horas semanales a revisar alertas, un EPP con buena detección automática es una decisión defendible.

Los EPP actuales están bastante lejos del antivirus de hace diez años: incorporan detección por comportamiento, protección anti-exploit, control de aplicaciones y capacidad de bloqueo automático de ransomware. No te dan visibilidad forense ni capacidad de investigación, pero sí previenen una porción grande de los ataques oportunistas, que es de lo que más probablemente seas víctima.

La condición para que esta decisión sea razonable: que el resto de tus controles básicos estén en orden. Segundo factor en todos los accesos remotos, backups con copia offline probada, parcheo al día, y mínimo privilegio en cuentas administrativas. Un EPP sin esos fundamentos no compensa nada.

### Cuándo sí necesitás EDR

El umbral no es de tamaño sino de exposición y capacidad. Necesitás EDR cuando se cumple alguna de estas:

Tenés al menos una persona con tiempo asignado y formación para analizar alertas. Manejás datos regulados o información de terceros que te obliga contractualmente a demostrar capacidad de detección y respuesta. Ya tuviste un incidente y necesitás visibilidad para asegurarte de que el atacante no siga adentro. Tu operación depende de infraestructura que si se detiene te para el negocio en horas, no en días. O sos proveedor de empresas más grandes que te auditan la postura de seguridad.

### Cuándo conviene MDR

Para la mayoría de las PyMEs latinoamericanas que necesitan capacidad real de detección, MDR es la respuesta más honesta.

Pagás la tecnología más el equipo que la opera. Tenés cobertura fuera de horario, que es exactamente cuando ocurren los ataques de ransomware —fines de semana largos y feriados son la ventana preferida por una razón obvia. Y no dependés de retener a un perfil escaso y caro en un mercado donde ese perfil se va cada dieciocho meses.

El costo por endpoint es mayor que el de una licencia sola. El costo total, comparado contra sostener un analista propio, casi siempre es menor.

## Cómo evaluar un EDR sin ser especialista

Si ya decidiste que necesitás EDR o MDR, estos son los criterios que separan una decisión informada de una compra por marca.

### Cobertura real de tu parque

Pedí la lista específica de sistemas operativos y versiones soportadas, y contrastala contra lo que realmente tenés. Es habitual que la cobertura de Windows sea excelente, la de macOS aceptable y la de Linux notablemente más limitada en capacidades de respuesta.

Si tenés servidores Linux críticos, preguntá explícitamente qué funcionalidades están disponibles ahí y cuáles no. Y si te queda algún equipo con un sistema fuera de soporte, confirmá si el agente corre —muchas veces no.

### Impacto en performance

Un agente que consume recursos de forma visible termina desinstalado por el usuario o excluido por el área de sistemas. Es el modo de falla más común y nadie lo pone en la propuesta.

Pedí una prueba piloto sobre los equipos más débiles de tu parque, no sobre los nuevos. Y sobre las estaciones que corren tu software de línea de negocio, que suele ser el que peor convive con agentes de seguridad.

### Calidad de las alertas

Esta es la métrica que más determina si el producto se va a usar en seis meses, y la más difícil de evaluar en una demo.

En el piloto, contá cuántas alertas se generan por semana y qué proporción resulta ser ruido. Un producto que genera trescientas alertas semanales en cincuenta equipos es inoperable para un equipo chico, por más buena que sea su tasa de detección.

Preguntá también qué tan trabajoso es crear excepciones para tu software propio, porque vas a necesitar hacerlo.

### Capacidad de respuesta automática

Preguntá qué puede hacer el producto sin intervención humana. Aislar automáticamente un equipo ante indicadores de ransomware de alta confianza es una capacidad que salva empresas fuera del horario laboral.

Confirmá también qué tan rápido es el aislamiento manual desde la consola y si funciona cuando el equipo está fuera de la red corporativa.

### Retención de telemetría

Cuánto tiempo se guardan los datos y qué costo tiene extender ese plazo. Es un detalle que aparece en la letra chica y define tu capacidad de investigar hacia atrás.

Los ataques dirigidos suelen tener períodos de permanencia largos: si tu retención es de siete días, la investigación de un incidente descubierto tarde te va a dejar sin la parte más importante de la historia.

### Resultados en evaluaciones independientes

Las evaluaciones de MITRE Engenuity ATT&CK son la referencia más útil del sector porque no publican un ranking sino el detalle de qué detectó cada producto en cada paso de un ataque simulado.

Leelas con criterio. Un producto puede tener excelente cobertura de detección pero requerir configuración avanzada para lograrla, o generar muchas detecciones de baja calidad que en la práctica son ruido. Los resultados hay que interpretarlos junto con tu capacidad real de operación.

### Soporte en tu zona horaria y en tu idioma

Durante un incidente activo, la diferencia entre escalar a un equipo que responde en español en el momento y abrir un ticket que se atiende en doce horas es enorme.

Preguntá específicamente por el soporte para incidentes críticos: en qué horario, en qué idioma, con qué tiempo de respuesta comprometido, y si ese compromiso está en el contrato o es una expectativa verbal.

## Errores frecuentes al implementar

**Desplegar en modo detección y quedarse ahí.** Es correcto empezar sin bloqueo para calibrar, pero muchas implementaciones nunca pasan a modo prevención y quedan indefinidamente generando alertas que nadie acciona.

**Excluir carpetas enteras para evitar falsos positivos.** Es la solución rápida cuando el software de línea de negocio genera ruido, y es exactamente donde el atacante va a alojar sus herramientas si llega a enterarse. Las exclusiones tienen que ser lo más específicas posible: un binario concreto, no un directorio completo.

**Dejar equipos afuera del despliegue.** El servidor viejo que nadie quiere tocar por miedo a que se rompa suele ser el punto de entrada. Una cobertura del ochenta por ciento deja el veinte por ciento donde el atacante va a operar cómodo.

**No probar el procedimiento de respuesta.** Tener capacidad de aislar un equipo no sirve si nadie practicó hacerlo. Vale la pena un simulacro: quién detecta, quién decide, quién ejecuta, a quién se avisa.

**Comprar EDR y no comprar tiempo para operarlo.** Repito esto porque es el error más caro y el más común. La licencia es la parte barata de la ecuación.

## Qué sigue

Si estás evaluando reemplazar tu antivirus actual, el orden que tiene más sentido es: primero confirmar que los fundamentos estén cubiertos —segundo factor, backups probados, parcheo—, después definir con honestidad si vas a tener quién opere la herramienta, y recién ahí salir a cotizar la categoría que corresponda.

Cotizar EDR cuando lo que tu organización puede sostener es un EPP administrado no es prudencia: es gastar de más en una capacidad que va a quedar apagada.

En el [catálogo de productos](/productos) están las plataformas que analizamos, y en la sección de [guías](/guias) el resto del material sobre cómo estructurar la seguridad de una PyME sin sobredimensionar la inversión.
