---
title: "Defender dejó de escanear y no avisó"
subtitle: Entre el 18 y el 19 de agosto una actualización de firmas abortaba los escaneos de Microsoft Defender. Se corrigió sin boletín numerado, y el único lugar donde se ve es tu propia máquina.
excerpt: Los escaneos de Defender abortaban por una actualización de firmas. Ya está corregido, pero comprobarlo sigue dependiendo de mirar cada equipo.
type: news
status: published
category: antivirus-y-edr
author: ian-poletti-lucero
published: 2026-08-24
updated: 2026-08-24
products:
  - microsoft-defender-for-endpoint-p2
  - bitdefender-gravityzone-business-security-premium
  - eset-protect-enterprise
  - kaspersky-next-edr-optimum
meta_title: "Defender dejó de escanear y no avisó"
meta_description: "Una actualización de firmas del 18 de agosto abortaba los escaneos de Microsoft Defender. Cómo comprobar en cada equipo que volvió a escanear."
---

El 18 de agosto una tanda de actualizaciones de firmas de Microsoft Defender empezó a abortar los escaneos. Los rápidos y los completos arrancaban y se cortaban con el mensaje de que el servicio de amenazas se detuvo, y reiniciar el servicio no lo arreglaba. Microsoft corrigió el problema al día siguiente con un paquete de firmas nuevo.

Lo que hace que esto valga una nota cinco días después no es la falla, que ya está cerrada. Es cómo se enteró la gente: **no hubo boletín numerado**. No hay un CVE, ni una entrada en el catálogo de CISA, ni una página de aviso del fabricante a la que suscribirse. Un antivirus que deja de escanear no genera una alerta: se queda en silencio, con el escudo verde en pantalla.

| Dato | Valor | Fuente |
|---|---|---|
| Qué falló | Escaneos rápidos, completos y sin conexión que abortan | Reportes coincidentes del 18 y 19 de agosto |
| Origen | Actualizaciones de firmas del 18 de agosto | Ídem |
| Corregido en | Firmas 1.457.236.0 o posterior | Microsoft, declaración recogida por BleepingComputer |
| Versión de firmas hoy | 1.457.318.0 | Microsoft, página de actualizaciones, 24 de agosto 09:28 UTC |
| Motor hoy | 1.1.26070.7 | Ídem |
| Plataforma hoy | 4.18.26070.9 | Ídem |
| Aviso numerado del fabricante | No se encontró ninguno | Búsqueda en el soporte y la documentación de Microsoft |

Sobre el número exacto que corrige hay que ser preciso: la versión que Microsoft señaló es 1.457.236.0 o posterior, y esa declaración llegó a través de la prensa especializada, no de una página de aviso propia. Circularon además reportes de usuarios de que algunos equipos siguieron fallando hasta paquetes posteriores. Eso no se pudo confirmar contra fuente primaria y queda como lo que es: reportes sueltos.

Lo que sí está verificado hoy es el estado actual. La página de actualizaciones de Microsoft publica, con fecha del 24 de agosto a las 09:28 UTC, la versión de firmas 1.457.318.0. Cualquier equipo que se esté actualizando con normalidad está muy por encima del paquete corregido.

---

## Por qué importa que no haya habido aviso

Una vulnerabilidad tiene identificador, fecha y lugar donde mirar. Esto no. La cadena fue: se rompe el martes, se arregla el miércoles, se repone solo por Windows Update.

Para el que administra tres o quince equipos, la diferencia es concreta. Con un CVE, la pregunta es "¿parcheé?". Acá la pregunta es otra: **¿esta máquina completó un escaneo desde el martes pasado?** Y esa no la contesta ningún boletín, la contesta el equipo.

```svg
<svg viewBox="0 0 660 250" role="img" aria-label="Comparación entre una falla con aviso, donde el fabricante publica un identificador y el administrador comprueba si parcheó, y una falla sin aviso, donde no hay identificador y la única comprobación posible es mirar cada equipo">
  <rect x="20" y="30" width="600" height="80" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="30" width="600" height="80" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.5"/>
  <text x="38" y="54" font-size="13" font-weight="700" fill="currentColor">Falla con aviso</text>
  <text x="38" y="76" font-size="11.5" fill="currentColor" opacity="0.85">Identificador, fecha, versión corregida, catálogo donde figura</text>
  <text x="38" y="97" font-size="11.5" fill="currentColor" opacity="0.85">La pregunta es: ¿ya actualicé?</text>

  <rect x="20" y="130" width="600" height="80" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="130" width="600" height="80" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="154" font-size="13" font-weight="700" fill="#e23a3a">Falla sin aviso</text>
  <text x="38" y="176" font-size="11.5" fill="currentColor" opacity="0.85">Sin identificador ni página a la que suscribirse; se corrige sola</text>
  <text x="38" y="197" font-size="11.5" fill="currentColor" opacity="0.85">La pregunta es: ¿esta máquina completó un escaneo?</text>

  <text x="330" y="236" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.75">La segunda solo se responde equipo por equipo</text>
</svg>
```

---

## A quién le toca

A cualquier equipo con Windows que use Microsoft Defender como antivirus, que en una PyME suele ser el parque entero. Y le toca de verdad, aunque el arreglo ya esté publicado, a tres grupos:

- **Los equipos que estuvieron apagados** entre el 18 y el 19, y los que estuvieron de vacaciones con su dueño. Al encender se ponen al día solos, pero conviene confirmarlo en vez de asumirlo.
- **Los que reciben actualizaciones con demora**, por WSUS, por una política de despliegue escalonado o por una conexión mala en una sucursal.
- **Las notebooks que nadie administra**, las del contador externo o el vendedor que se conecta de vez en cuando. Son las que más tardan en salir de una versión rota y las que menos figuran en un inventario.

## A quién NO le toca

Si tu antivirus es otro producto —[Bitdefender, Kaspersky, ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset) o cualquier otro de los [del catálogo](/productos/antivirus-y-edr)— este episodio no te alcanza, porque la falla estaba en las firmas de Defender. Tampoco alcanza a los equipos que se actualizan solos por Windows Update sin restricciones y estuvieron encendidos esta semana: esos ya pasaron por varios paquetes posteriores al corregido.

Y no cambió nada en la protección en tiempo real, que es la que ataja un archivo cuando se abre. Lo que falló fue el escaneo bajo demanda y el programado, que es la pasada de fondo.

---

## Cómo comprobar cada equipo

Todo esto se mira desde tu lado. En PowerShell como administrador:

```powershell
Get-MpComputerStatus | Select-Object AMEngineVersion, AntivirusSignatureVersion,
  AntivirusSignatureLastUpdated, QuickScanEndTime, FullScanEndTime, RealTimeProtectionEnabled
```

Tres cosas a leer:

1. **`AntivirusSignatureVersion`** tiene que ser 1.457.236.0 o posterior. Hoy lo normal es estar cerca de 1.457.318.0.
2. **`QuickScanEndTime`** es el dato que casi nadie mira. Si está vacío, o si la fecha es anterior al 18 de agosto, ese equipo no terminó un escaneo desde entonces.
3. **`RealTimeProtectionEnabled`** tiene que estar en `True`.

Si administrás varios equipos con Defender for Endpoint, la misma consulta sale del portal por dispositivo, sin recorrer máquina por máquina.

Para forzar la puesta al día en un equipo rezagado, desde una consola con permisos de administrador:

```powershell
Update-MpSignature
Start-MpScan -ScanType QuickScan
```

Si el escaneo vuelve a cortarse después de actualizar las firmas, ahí sí hay algo particular de ese equipo y toca revisarlo aparte.

---

## Qué hacer, en orden

1. **Pasá la consulta por el parque**, empezando por los equipos que estuvieron apagados la semana pasada.
2. **Donde el último escaneo sea anterior al 18 de agosto, actualizá firmas y corré uno completo.** No porque haya indicios de infección, sino porque hubo una ventana sin esa capa y conviene cerrarla con una pasada, no con una suposición.
3. **Anotá el "último escaneo exitoso" como algo que se mira todos los meses.** Es la métrica que delata a un antivirus que se quedó mudo, y sirve igual con cualquier producto.
4. **Revisá si tenés forma de enterarte de un problema así.** Un panel central de antivirus muestra los equipos que dejaron de reportar; sin panel, la única señal es que alguien lo note. Cuándo se justifica dar ese paso está en [EDR o antivirus: cuándo se justifica en una PyME](/guia/edr-o-antivirus-cuando-se-justifica-pyme), y la versión gestionada del producto de Microsoft es [Defender for Endpoint P2](/producto/microsoft-defender-for-endpoint-p2).
5. **Si un cliente te pregunta por esto**, la respuesta honesta es que hubo una ventana de aproximadamente un día, que ya está corregida y que verificaste el parque. El criterio para responder ese tipo de preguntas está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes

### ¿Estuve desprotegido esos días?

No del todo. La protección en tiempo real siguió funcionando, y es la que intercepta un archivo malicioso al abrirlo o descargarlo. Lo que no corrió fue el escaneo de fondo, que sirve para encontrar algo que ya está en disco y pasó desapercibido antes. La ventana existió y fue acotada; tratarla como catástrofe sería tan impreciso como ignorarla.

### ¿Por qué no hay un CVE para esto?

Porque un CVE describe una vulnerabilidad, no una actualización defectuosa. Son categorías distintas, y solo la primera tiene un sistema de identificadores, catálogos y avisos. Una actualización que rompe una función de seguridad cae en un hueco: afecta la postura de seguridad y no viaja por ninguno de los canales que uno sigue para enterarse. El caso opuesto también existe y se vio [hace pocos días con Entra ID](/noticia/entra-id-cvss-10-no-fue-explotado): un CVE con puntaje máximo, con aviso y todo, que no exigía ninguna acción del cliente.

### El escudo estaba en verde. ¿Cómo puede ser?

La interfaz reportaba el estado de la protección en tiempo real, que efectivamente estaba activa. El escaneo que abortaba es otra función, y su fracaso no cambiaba el indicador general. Un control de seguridad que falla sin encender una luz roja es peor que uno que falla ruidosamente, porque el que administra no tiene motivo para ir a mirar.

### ¿Conviene desconfiar de las actualizaciones automáticas de firmas después de esto?

No. Retrasar las firmas para evitar un paquete defectuoso cambia un riesgo raro por uno diario: quedarse sin detección de lo que apareció esta semana. El episodio duró alrededor de un día y se resolvió por la misma vía automática. Lo que sí conviene es tener manera de ver el estado real del parque, que es distinto de confiar en el ícono de cada equipo.
