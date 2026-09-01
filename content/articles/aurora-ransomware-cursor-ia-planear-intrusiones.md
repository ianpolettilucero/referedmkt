---
title: "Ransomware Aurora: usó el asistente de IA Cursor para planear 20 intrusiones"
subtitle: Un directorio abierto sin contraseña dejó a la vista tres meses de trabajo del atacante. Las técnicas son todas viejas y conocidas, y lo que el operador se prohibió a sí mismo es la mejor lista de qué detectar.
excerpt: CloudSEK y Gambit Security encontraron el servidor de un afiliado de Aurora con las sesiones de Cursor a la vista. Planificó ataques contra más de 20 empresas en nueve países. Qué usó, qué le falló y qué mirar en tu red.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-09-01
updated: 2026-09-01
meta_title: "Aurora usó Cursor para planear 20 intrusiones"
meta_description: "Un afiliado del ransomware Aurora usó el asistente de IA Cursor para planear ataques contra más de 20 empresas. Qué técnicas usó, qué le falló y qué detectar."
---

Un afiliado del ransomware **Aurora** dejó un directorio abierto en el puerto 8888, sin autenticación, con su carpeta personal de Linux adentro. Entre lo que quedó a la vista estaban sus sesiones de **Cursor**, el asistente de programación con inteligencia artificial, usadas para planear intrusiones contra **más de 20 organizaciones en nueve países** entre abril y julio de 2026. Lo publicaron [CloudSEK](https://www.cloudsek.com/blog/aurora-ransomware-affiliate-ai-attack-planning-crypto-payments) y [Gambit Security](https://gambit.security/blog-posts/aurora-ransomware-targets-esxi-abuses-cursor-agent-for-exploitation) el 27 de agosto, cada uno por su lado.

Los sectores golpeados no son bancos: manufactura, alimentos y agro, servicios profesionales, transporte y logística, bienes de consumo, farmacéutica, gestión de residuos e infraestructura de backup. Es el mapa de una PyME industrial de LATAM.

## Qué hizo el agente de Cursor y qué no hizo

Conviene fijar el límite antes que el titular, porque acá es donde la cobertura se resbala: **Cursor no entró solo a ninguna red**. El operador lo usó para *planear*: redactar secuencias de ataque, ordenar pasos conocidos, depurar scripts que no andaban y documentar caminos dentro de un dominio Windows.

CloudSEK describe sesiones con "un ida y vuelta con la herramienta inusualmente sostenido" y encontró un plan completo de explotación de ADCS **escrito íntegramente en ruso**.

El dato que más ordena la discusión lo aporta Gambit, y va en contra del pánico: entre el 8 de abril y el 21 de mayo el operador usó el agente contra diez organizaciones, y

> la mayoría de los comandos no logró el objetivo declarado en el primer intento

Hizo falta refinar una y otra vez. La máquina no resolvió el ataque: le ahorró tiempo de escritura y de memoria a alguien que ya sabía lo que quería hacer.

```svg
<svg viewBox="0 0 660 210" role="img" aria-label="Qué aportó el asistente y qué no. Aportó: redacción de secuencias, orden de pasos conocidos, depuración de scripts y documentación del camino en el dominio. No aportó: ninguna técnica nueva, y la mayoría de los comandos falló en el primer intento según Gambit Security">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué cambió el asistente en la operación de Aurora</text>
  <rect x="20" y="38" width="620" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="38" width="620" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="34" y="58" font-size="10.5" font-weight="700" fill="currentColor">Lo que sí aportó</text>
  <text x="34" y="77" font-size="10.5" fill="currentColor" opacity="0.85">Redactar secuencias, ordenar pasos conocidos, depurar scripts</text>
  <text x="34" y="94" font-size="10.5" fill="currentColor" opacity="0.85">y documentar el camino dentro del dominio</text>
  <rect x="20" y="116" width="620" height="66" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="116" width="620" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="34" y="136" font-size="10.5" font-weight="700" fill="#e23a3a">Lo que no aportó</text>
  <text x="34" y="155" font-size="10.5" fill="currentColor" opacity="0.85">Ninguna técnica nueva: NTLM relay y ataques de certificados</text>
  <text x="34" y="172" font-size="10.5" fill="currentColor" opacity="0.85">son de manual, y la mayoría de los comandos falló al primer intento</text>
  <text x="20" y="202" font-size="11.5" font-weight="600" fill="currentColor">Ahorra tiempo a quien ya sabe. No reemplaza el saber.</text>
</svg>
```

Las herramientas que aparecen en las tareas son las de siempre, todas públicas y documentadas hace años: **PetitPotam** y **PrinterBug** con **Impacket** para retransmisión NTLM, **Certipy** para los ataques de certificados, **NetExec** con su recolector de BloodHound para enumerar el dominio, y **Nmap** para barrer la red. Son las mismas que aparecen en cualquier [laboratorio de hacking ético](/guia/herramientas-laboratorios-hacking-etico). La novedad no está en el arsenal.

## Por qué los titulares dan 7, 10 y 20 víctimas distintas

Las cifras que circularon esta semana no coinciden entre sí, y no es que alguna esté mal: cuentan cosas distintas.

| Fuente | Qué cuenta | Número |
|---|---|---|
| CloudSEK | Organizaciones comprometidas en la campaña | más de 20 |
| CloudSEK | Con acceso de dominio o interactivo | 17 |
| CloudSEK | Publicadas en el sitio de filtraciones de Aurora | 4 |
| Gambit Security | Organizaciones donde se usó el agente de Cursor | 10 |

La campaña es más grande que el uso del asistente. Si una nota dice "diez" y otra "más de veinte", las dos pueden estar bien según qué estén midiendo. Al citar el caso conviene decir cuál de los cuatro números se está usando.

Un detalle que ubica al grupo: en tres meses de listas de objetivos **no aparece ni un solo rango de IP ni un dominio de países de la CIS**. Es la marca de siempre de las bandas que operan desde esa región.

## Cómo ataca Aurora a los servidores ESXi

La parte que decide el daño real en una PyME no es la inteligencia artificial: es el hipervisor. Aurora despliega una variante Linux con un modo `-esxi` que hace algo simple y devastador.

Primero lista las máquinas virtuales en ejecución y las fuerza a apagarse, para soltar los bloqueos de archivo que impedirían cifrarlas. Después cifra los archivos de disco —`vmdk`, `vmx`, `vmsn`, `nvram` y compañía— con ChaCha20 y la clave envuelta en RSA-4096. Deja el hipervisor arrancable a propósito, según Gambit, "para que la víctima pueda leer el pedido de rescate".

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Secuencia del cifrado en ESXi: primero lista las máquinas virtuales en ejecución, después las fuerza a apagarse para liberar los bloqueos de archivo, luego cifra los discos vmdk y vmx con ChaCha20 y clave envuelta en RSA-4096, y deja el hipervisor arrancable para que la víctima lea el rescate">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Cómo se pierde un ESXi entero en minutos</text>
  <rect x="16" y="48" width="150" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="16" y="48" width="150" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="91" y="74" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">1. Lista</text>
  <text x="91" y="93" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">las máquinas</text>
  <text x="91" y="107" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">virtuales activas</text>
  <path d="M168 86 L178 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>
  <rect x="180" y="48" width="150" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="180" y="48" width="150" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="255" y="74" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">2. Las apaga</text>
  <text x="255" y="93" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">por la fuerza, para</text>
  <text x="255" y="107" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">soltar los bloqueos</text>
  <path d="M332 86 L342 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>
  <rect x="344" y="48" width="150" height="76" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="344" y="48" width="150" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="419" y="74" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">3. Cifra</text>
  <text x="419" y="93" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">vmdk y vmx con</text>
  <text x="419" y="107" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">ChaCha20 + RSA-4096</text>
  <path d="M496 86 L506 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>
  <rect x="508" y="48" width="156" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="508" y="48" width="156" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="586" y="74" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">4. Deja vivo</text>
  <text x="586" y="93" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">el hipervisor, para</text>
  <text x="586" y="107" text-anchor="middle" font-size="9.5" fill="currentColor" opacity="0.8">que leas el rescate</text>
  <text x="340" y="158" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Un solo ESXi comprometido son todos los servidores a la vez</text>
  <text x="340" y="180" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.75">Por eso el backup que importa es el que no se monta desde el dominio</text>
</svg>
```

Si tus servidores están virtualizados sobre un solo ESXi, comprometerlo equivale a perderlos todos en el mismo minuto. Y si el backup vive en un recurso montado desde ese mismo dominio, se va con ellos. Es exactamente el escenario que ordena el [backup con copia fuera de línea](/guia/backup-microsoft-365-hace-falta).

## Lo que el operador de Aurora se prohibió es tu lista de detección

Acá está el hallazgo más aprovechable de toda la investigación, y sale de leer las instrucciones que el operador le dio al agente. Tres prohibiciones explícitas, escritas en ruso:

- **`dcsync` categóricamente prohibido.**
- **Nada de bloquear cuentas** durante el rociado de credenciales.
- **Prohibido agregar objetos de computadora nuevos** al dominio.

Un atacante no se prohíbe una técnica porque no funcione. Se la prohíbe **porque hace ruido**. Cada una de esas tres cosas es una alerta clásica y barata de configurar:

| Lo que el operador evitó | Lo que deberías estar alertando |
|---|---|
| `dcsync` | Replicación de directorio desde equipos que no son controladores de dominio |
| Bloqueos de cuenta | Picos de fallos de autenticación sobre muchos usuarios |
| Objetos de computadora nuevos | Altas de cuentas de equipo fuera del alta normal |

Si esas tres alertas existen y alguien las mira, el atacante que las está esquivando tiene que trabajar mucho más despacio. Si no existen, se ahorró la precaución al pedo.

## ¿A quién le toca el aviso de Aurora?

- **Quien tiene Active Directory con servidor de certificados (ADCS).** Fue el camino de escalada preferido en esta campaña.
- **Quien virtualiza sobre VMware ESXi**, y más si es un solo servidor.
- **Quien expone servicios de acceso remoto** por los que se entra antes de escalar.
- **Quien está en manufactura, alimentos, logística o servicios profesionales**, que es donde pegó.

## ¿Quién puede ignorar el aviso de Aurora?

- **Quien no tiene Active Directory.** Sin dominio Windows, la mitad de la cadena —retransmisión NTLM, ADCS, `dcsync`— no aplica.
- **Quien no corre ESXi.** El modo de cifrado de hipervisor no tiene contra qué correr.
- **Quien trabaja íntegramente en la nube**, sin servidores propios. La superficie de esta campaña es de red interna.

Que no te toque esta campaña no significa que no te toque el ransomware. Significa que tu camino de entrada es otro.

## Qué hacer el lunes con el aviso de Aurora, en orden

1. **Comprobá si tenés ADCS y cómo está.** Es el eslabón que más se usó. Las plantillas de certificado mal configuradas son el problema, no el servicio en sí.
2. **Cerrá la retransmisión NTLM.** Firma SMB obligatoria y protección extendida para autenticación en los servicios que la acepten. Es la mitigación que neutraliza a PetitPotam y PrinterBug como vía de escalada.
3. **Prendé las tres alertas de la tabla de arriba.** Son gratis y son justo las que el atacante se esforzó por no disparar.
4. **Aislá el ESXi.** Su interfaz de administración no va en la misma red que las estaciones de trabajo, y no se administra con credenciales del dominio.
5. **Verificá que tenés una copia de backup que no se monta desde el dominio.** Si el ransomware llega al hipervisor, es lo único que queda.
6. **Revisá qué credenciales pueden alcanzar tus asistentes de programación.** No porque vayan a atacarte: porque un token de tu repositorio dentro de una herramienta que registra sesiones es una exposición nueva, y este caso muestra que esas sesiones a veces terminan a la intemperie.

Si nada de esto está montado, el orden general de prioridades está en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y conviene evaluar si el caso justifica [pasar de antivirus a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme), que es la capa que detecta el movimiento lateral de esta campaña.

## Cursor es de Anysphere, no de SpaceX

Varias coberturas de esta semana atribuyeron Cursor a SpaceX. Es falso. Los [términos de servicio de Cursor](https://cursor.com/terms-of-service), actualizados el 13 de agosto de 2026, empiezan identificando a **"Anysphere, Inc. […], makers of the Cursor software platform"**. Ni CloudSEK ni Gambit dicen nada sobre quién desarrolla la herramienta.

El error no cambia el riesgo, pero sí a quién se le reclama. Vale la pena corregirlo antes de que se cite tres veces más.

Un apunte técnico del informe de Gambit, por precisión y porque suele quedar afuera: el agente corría sobre el modelo `claude-4.5-sonnet-thinking`. Ningún proveedor de modelos autorizó ni participó de esto; es un uso indebido de una herramienta comercial, del mismo modo en que Impacket y Nmap se usan todos los días para trabajo legítimo. Es la diferencia entre [seguridad ofensiva con permiso](/guia/seguridad-ofensiva-ethical-hacking-pentesting-red-teaming) y un delito.

## Preguntas frecuentes sobre Aurora y el uso de Cursor

### ¿La inteligencia artificial hackeó las empresas por sí sola?
No. Cursor se usó para planear, redactar y depurar; la ejecución y las decisiones fueron del operador. Gambit registra además que la mayoría de los comandos falló en el primer intento y necesitó varias correcciones.

### ¿Tengo que dejar de usar Cursor u otro asistente de programación?
No es la conclusión del caso. Lo que muestra es que las sesiones de estas herramientas guardan un registro detallado del trabajo, y que ese registro es sensible: acá fue lo que permitió reconstruir la operación del atacante. Tratá esas sesiones como tratás tus credenciales.

### ¿Cómo sé si Aurora ya estuvo en mi red?
Los indicadores publicados por Gambit incluyen el hash del cifrador y dos direcciones IP de mando y control. Buscalos en tus registros de firewall y de EDR. La ausencia de coincidencias no prueba nada, pero una coincidencia sí.

### ¿Sirve el antivirus contra esto?
Contra el cifrador final, a veces. Contra la etapa que importa —retransmisión NTLM, abuso de certificados, movimiento lateral con credenciales válidas— hace falta detección de comportamiento en el dominio, no firmas de archivos.

### ¿Por qué el atacante evitó los países de la CIS?
Es una práctica habitual de las bandas que operan desde esa región, y funciona como indicio de origen. CloudSEK confirma que en tres meses de listas de objetivos no aparece ningún rango ni dominio de esos países.

---

**Fuentes primarias.** [CloudSEK, "Caught in 4K: The Aurora Files" (27 de agosto de 2026)](https://www.cloudsek.com/blog/aurora-ransomware-affiliate-ai-attack-planning-crypto-payments) · [Gambit Security sobre Aurora, ESXi y el agente de Cursor (27 de agosto de 2026)](https://gambit.security/blog-posts/aurora-ransomware-targets-esxi-abuses-cursor-agent-for-exploitation) · [Términos de servicio de Cursor, 13 de agosto de 2026](https://cursor.com/terms-of-service).

Consultadas el 1 de septiembre de 2026. Para el vocabulario, [el glosario](/guia/glosario-ciberseguridad-pymes); si querés ver la contracara autorizada de estas técnicas, [qué es el pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos). La semana pasada tocamos el otro extremo del mismo tema: [los agentes de OpenAI que explotaron el kernel de Linux](/noticia/kernel-linux-cve-2026-53362-agentes-openai).
