---
title: "Red Teaming: Qué Es, Cuándo Contratarlo y Cómo Evaluarlo"
subtitle: Red team no es "pentesting premium". Es otra cosa. Esta guía explica qué hace un red team real, en qué se diferencia de un pentest, cuándo tu empresa está lista para contratarlo y cuándo todavía no.
excerpt: Red teaming se vende mucho y se entiende poco. No es "pentesting más caro" ni "pentesting durante más tiempo" — es una disciplina distinta, con objetivos distintos, que solo tiene sentido cuando tu empresa ya pasó ciertas etapas de madurez. Esta guía explica qué hace un red team real, cuándo contratarlo, cuánto cuesta, qué es adversary emulation, cuándo conviene purple team en lugar de red team, y cómo saber si tu empresa está lista.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-04-22 02:45:00
updated: 2026-07-26
meta_title: "Red Teaming: Qué Es y Cuándo Contratarlo (Guía 2026)"
meta_description: Red team no es "pentesting premium". Es otra cosa. Qué es adversary emulation, cuándo tiene sentido, cuánto cuesta, qué esperar como entregable y cuándo conviene purple team en su lugar.
---

Red team suena bien en PowerPoint. *"Vamos a hacer red team este año"* genera asentimiento en las reuniones de directorio, presupuesto aprobado, pecho inflado del CISO. Y después cuando llega la propuesta — USD 120.000 por tres meses de trabajo — alguien pregunta la pregunta que debería haberse hecho al principio: *"¿pero qué es exactamente lo que vamos a recibir?"*.

Este artículo responde esa pregunta antes de que firmes nada. Qué es realmente un ejercicio de red team, en qué se diferencia de un pentest, qué tipo de empresa lo necesita, cuánto cuesta y qué obtenés por esa inversión. Y también qué no es: red team no es pentesting más caro, no es pentesting más largo, no es pentesting más profundo. Es otra cosa.

Si no leíste antes [**qué es pentesting**](/guia/que-es-pentesting-como-funciona-fases-tipos), te conviene empezar por ahí. El resto de esta guía asume que ya sabés de qué se trata.

> **TL;DR** — Pentest pregunta *"¿qué vulnerabilidades tiene mi sistema?"*. Red team pregunta *"¿podría un atacante real llegar a mi objetivo crítico sin que mi SOC lo detecte?"*. Son dos preguntas completamente distintas. Red team dura meses, cuesta entre USD 50.000 y USD 250.000+, y solo tiene sentido si ya tenés un SOC operativo que quieras entrenar. Si todavía no tenés MFA obligatorio, no contrates red team. Arreglá lo básico primero.

---

## Qué es red teaming realmente

**Red team es simular a un adversario sofisticado real intentando cumplir un objetivo específico.** No es encontrar todas las vulnerabilidades. Es demostrar si el objetivo es alcanzable y cómo, trabajando en condiciones similares a las de un atacante externo motivado.

La diferencia con pentesting es conceptual, no de grado.

Un pentester entra con la lista completa de IPs del alcance, una ventana de tiempo definida, permiso explícito de hacer ruido, y el objetivo de enumerar la mayor cantidad posible de vulnerabilidades en 2-4 semanas. Un red teamer entra con un objetivo (*"llegar a la base de datos de clientes"*, *"obtener control de la infraestructura de backups"*, *"enviar un correo desde la cuenta del CEO"*), tiempo largo (1-6 meses), **instrucción de no ser detectado**, y libertad de usar cualquier vector que no esté explícitamente prohibido.

Los dos servicios tienen valor. No compiten entre sí. Son complementarios y responden preguntas diferentes.

### La pregunta que responde red team

Un pentest te dice *"tenés estas 47 vulnerabilidades, estas 12 son críticas"*. Buena información, accionable, necesaria. Pero no te dice si tu equipo de seguridad va a detectar a un atacante real cuando llegue.

Red team responde específicamente: **¿mi organización detecta y responde adecuadamente a un adversario real?**

El output no es una lista de vulnerabilidades. Es una historia: *"entramos el día 12 con un phishing spear, escalamos privilegios el día 14, llegamos al dominio el día 19, exfiltramos los datos el día 23, el SOC detectó actividad sospechosa el día 31 y la atribuyó incorrectamente, nunca identificaron la intrusión completa, el objetivo se alcanzó sin detección real"*. Esa historia es lo que tu CISO lleva al directorio para justificar inversión en detección, no en más prevención.

### Qué hace distinto al adversario real

El red teamer trabaja emulando lo más posible la realidad de un atacante sofisticado. Eso significa varias cosas concretas.

Usa **herramientas que los atacantes reales usan**, no simplifica con scripts sintéticos. [Cobalt Strike](https://www.cobaltstrike.com) es el estándar comercial del rubro (USD 7.000+ por año, razón por la que no cualquiera lo usa). Las alternativas open source como [Sliver](https://github.com/BishopFox/sliver) o [Mythic](https://github.com/its-a-feature/Mythic) están ganando terreno y también se usan profesionalmente.

**Establece infraestructura propia** para command and control (C2), típicamente dominios comprados para el engagement que imitan sitios legítimos, servidores en proveedores cloud no asociados a la firma de red team, redirectores para ocultar la infraestructura real. Nada de esto viene en una caja de Kali.

**Mantiene operational security (opsec)** durante todo el engagement. No usa sus herramientas favoritas si dejan rastros identificables. Rota dominios, rota IPs, usa técnicas de evasión que un atacante real usaría. Si el equipo defensivo lo detecta, el engagement perdió parte de su valor.

Y **persiste en el tiempo**. Un atacante estatal no se apura: puede estar meses adentro de una red antes de ejecutar su objetivo final. Un red team bien hecho replica eso — meses de reconocimiento pasivo, semanas de movimiento lateral cuidadoso, paciencia absoluta.

---

## Adversary emulation: el corazón del red teaming moderno

El concepto que separa al red team contemporáneo del pentest glorificado es **adversary emulation**: replicar las tácticas, técnicas y procedimientos específicos de un grupo de amenaza real.

En lugar de hacer *"red team genérico"*, el cliente elige qué adversario quiere simular. Un banco en México puede pedir emulación de [FIN7](https://attack.mitre.org/groups/G0046/) porque es el grupo financieramente motivado con TTPs documentadas contra el sector. Una empresa energética puede pedir emulación de [Sandworm](https://attack.mitre.org/groups/G0034/) por razones geopolíticas. Una empresa tech con exposición a China pide emulación de [APT41](https://attack.mitre.org/groups/G0096/).

La emulación usa el framework **MITRE ATT&CK** como lenguaje común. ATT&CK mapea todas las técnicas conocidas de atacantes reales en una matriz estructurada, y cada grupo de amenaza tiene un perfil documentado de qué técnicas usa, en qué orden, con qué herramientas. El red team sigue ese perfil con la mayor fidelidad posible.

Esto es relevante por una razón práctica: si tu SOC está configurado para detectar *"actividad sospechosa genérica"*, pero tu red team emula específicamente los TTPs de FIN7, vas a descubrir si tus detecciones están calibradas para adversarios reales o para libros de texto.

[MITRE Engenuity publica evaluaciones anuales](https://evals.mitre.org/results/enterprise?view=cohort&evaluation=er7&result_type=DETECTION&scenarios=1,2) donde empresas como CrowdStrike, SentinelOne, Microsoft Defender y otras se enfrentan a emulaciones de adversarios documentados. Esos resultados son lectura obligada para cualquier empresa que evalúe productos EDR — y la misma lógica aplica a cómo deberías pensar tu red team.

---

## Purple team: la alternativa más accesible

Acá está la recomendación que la mayoría de los vendedores de red team no te van a dar: **la mayoría de las empresas que piden red team en realidad se benefician más de un purple team**.

Purple team es un ejercicio donde el red team y el blue team (SOC defensivo) **trabajan juntos en tiempo real**. El red team ejecuta técnicas específicas, el blue team ve si las detecta, se comparan notas, se ajustan detecciones, se repite. No es un ejercicio donde gana el que encuentra al otro — es un ejercicio donde gana el aprendizaje mutuo.

Las ventajas prácticas son grandes. Cuesta menos (USD 30.000-80.000 típicamente vs USD 100.000+ para red team puro). Dura menos (2-4 semanas vs meses). Genera mejoras medibles y documentadas en las capacidades defensivas, porque cada técnica probada viene con feedback inmediato de si se detectó y cómo mejorarla si no. Y **entrena al SOC** en lugar de solo evaluarlo.

Red team puro tiene sentido cuando ya pasaste por varios purple teams y querés una evaluación *"como si fuera real"* sin alertar al equipo defensivo. Es el siguiente nivel, no el punto de entrada.

Si sos un CISO y te ofrecen red team sin haberte preguntado antes si hiciste purple team, el proveedor está priorizando su facturación por encima de tu madurez de seguridad.

---

## Cuándo tu empresa está lista para red team

Esta es la sección que te va a ahorrar dinero si la leés con honestidad.

Red team **no tiene sentido** si:

- **No tenés MFA obligatorio** en todas las cuentas corporativas. El red team va a entrar en el día uno con phishing exitoso y el engagement pierde todo valor educativo.
- **No tenés EDR desplegado** en la mayoría de endpoints. Sin detección en endpoint, no hay red team serio que medir.
- **Tu equipo de seguridad son menos de 3 personas** dedicadas full-time. Red team sin SOC que evaluar es teatro caro.
- **Nunca hiciste un pentest real.** Si hay vulnerabilidades básicas sin arreglar, el red team las va a explotar en 48 horas y el resto del engagement es irrelevante.
- **No tenés proceso documentado de respuesta a incidentes.** No podés evaluar la respuesta si no tenés proceso que evaluar.
- **Tu empresa tiene menos de 200 empleados** y el problema real es que no tenés los controles básicos todavía. Plata mejor gastada en implementar [MFA](https://capacero.online/productos/mfa-y-autenticacion), [EDR](https://capacero.online/productos/antivirus-y-edr) y [backup](https://capacero.online/productos/backup-y-recuperacion).

Red team **sí tiene sentido** si:

- Tenés SOC operativo, sea interno o MDR, con al menos 12 meses de operación.
- Hiciste pentests previos y los hallazgos están remediados.
- Tenés herramientas de detección modernas instaladas y configuradas.
- Ya hiciste al menos un purple team y querés subir de nivel.
- Hay una razón específica que justifica evaluar adversarios sofisticados — sector regulado, historial de ataques dirigidos, alto perfil geopolítico, adquisición reciente que integra infraestructuras heterogéneas.
- Tu empresa es suficientemente grande (500+ empleados típicamente) como para tener algo que merezca ataque sofisticado.

El test de realidad más simple: si tu CISO no puede responderte **en detalle** qué detecciones tiene activas hoy y qué tiempo de respuesta promedio tiene el SOC al detectar un incidente de severidad media, no estás listo para red team. Porque no vas a poder interpretar el resultado.

---

## Qué incluye un ejercicio de red team completo

Un engagement bien estructurado tiene varias fases que exceden lo que un pentest cubre.

**Scoping y reglas de engagement (1-2 semanas).** Definición del objetivo específico, adversario a emular, restricciones (qué sistemas no tocar, qué técnicas no usar, ventanas horarias), contactos de emergencia 24/7, criterios de éxito, mecanismo de abort si algo se rompe.

**Threat intelligence previa (1 semana).** El red team investiga al adversario elegido y al cliente. Reconocimiento OSINT de empleados, infraestructura, vendors, tecnologías. Preparación de infraestructura atacante (dominios, C2, certificados).

**Initial access (variable, 1-4 semanas típicamente).** Intentos de entrar. Phishing dirigido, explotación de vulnerabilidades expuestas, ingeniería social, en algunos casos ataques a la cadena de suministro. Esta es la fase más incierta — puede funcionar rápido o demorar semanas.

**Establecimiento de persistencia y C2 (1-2 semanas).** Una vez adentro, el red team establece canales de comunicación encubiertos, instala mecanismos de persistencia para sobrevivir reinicios, comienza reconocimiento interno.

**Lateral movement y escalación (2-6 semanas).** Movimiento dentro de la red hacia sistemas más valiosos. Escalación de privilegios. Búsqueda de credenciales, datos sensibles, llaves de acceso. Esta es la fase más larga típicamente y donde el SOC tiene la mayor oportunidad de detectar.

**Objective completion (1 semana).** Cumplir el objetivo específico acordado, con evidencia que demuestre el logro pero sin causar daño real. Exfiltración simulada con datos marcados, no reales. Demostración de acceso, no explotación.

**Reporting y debrief (1-2 semanas).** Reporte técnico detallado para el blue team, reporte ejecutivo para management, sesión de debrief donde red team y blue team comparan notas sobre qué se detectó, qué no, y por qué.

Un ejercicio completo serio rara vez baja de **3 meses calendario**, aunque las horas efectivas de trabajo del red team sean menos. El tiempo de espera y observación es parte del valor — un atacante real no se apura.

---

## Precios reales del mercado

Rangos en el mercado 2026 para red team:

**USD 40.000-70.000.** Purple team de 3-4 semanas con foco en técnicas específicas (típicamente MITRE ATT&CK sobre Active Directory, o un subset acotado). Buena entrada para empresas que nunca hicieron esto.

**USD 70.000-120.000.** Red team corto de 6-10 semanas con objetivo acotado. Típicamente "llegar a un sistema crítico específico" sin múltiples iteraciones ni profundidad de adversary emulation.

**USD 120.000-200.000.** Red team estándar de 3-4 meses con adversary emulation de un grupo específico, incluyendo ingeniería social y múltiples vectores. Lo que la mayoría de empresas serias contrata.

**USD 200.000-500.000+.** Red team extendido o continuo (6+ meses), múltiples adversarios, assumed breach scenarios, ejercicios físicos incluidos. Territorio de banca grande, energía crítica, defensa, algunas multinacionales.

**USD 500.000+.** Red team continuo anual, múltiples ejercicios paralelos, equipo dedicado al cliente. Fortune 500 típicamente.

Los precios en LATAM están en el **60-80% de esos rangos** para firmas locales, prácticamente al precio completo para firmas internacionales operando en la región.

Lo que influye en el precio: duración del engagement, cantidad de objetivos, complejidad del adversario a emular, si incluye ingeniería social (encarece porque requiere preparación específica), si incluye physical red team (encarece mucho), exclusividad del equipo (si tu engagement es full-time para ellos o paralelo a otros).

---

## Qué firmas hacen red team serio

Sin orden específico y sin afiliación, las firmas que tienen reputación consolidada en red teaming a nivel internacional: **Mandiant** (ahora parte de Google Cloud), **CrowdStrike Services**, **NCC Group**, **Bishop Fox**, **SpecterOps**, **TrustedSec**, **Coalfire**, **Synack** (modelo híbrido con bug bounty). En Europa: **F-Secure Consulting** (ahora WithSecure), **MWR**, **Context IS** (ahora Accenture).

En LATAM hay firmas locales serias que hacen red team a nivel regional pero el mercado es más chico y menos documentado públicamente. El criterio de evaluación es el mismo que para pentest serio pero con filtros adicionales: **experiencia verificable en adversary emulation**, equipo con certificaciones avanzadas (OSEP mínimo, idealmente CRTO o equivalente, algunos con GXPN), **uso de infraestructura C2 propia**, casos de estudio redacted disponibles bajo NDA.

Una consideración regional: muchas empresas LATAM contratan firmas internacionales para red team aunque sea más caro, porque la profundidad técnica del rubro se concentra en Estados Unidos y Europa. No es automático que la firma local de pentesting serio haga red team igual de bien — son disciplinas distintas.

---

## Entregables esperables

Un red team engagement serio termina con varios documentos y una presentación, no con un solo reporte técnico.

**Reporte ejecutivo.** 10-20 páginas para management y directorio. Resumen del ejercicio, logro o no del objetivo, impacto en términos de negocio, recomendaciones estratégicas. Lenguaje no técnico.

**Reporte técnico completo.** 80-200 páginas con detalle de cada técnica usada, mapeada a MITRE ATT&CK, con evidencia (capturas, logs, indicadores de compromiso), fechas y horas, qué se detectó y qué no.

**Indicators of Compromise (IOCs).** Lista completa de IPs, dominios, hashes, técnicas usadas durante el engagement. Útil para que el blue team pueda retrocederlos en logs y verificar detecciones.

**Recomendaciones de mejora para detección.** No es "arreglen estas vulnerabilidades" — es "estas técnicas no fueron detectadas, acá están las queries de Splunk/Sentinel/ELK para detectarlas a futuro".

**Presentación ejecutiva.** Sesión con management donde el lead del red team cuenta la historia del engagement y responde preguntas.

**Sesión de debrief técnico con blue team.** Donde los dos equipos repasan juntos qué pasó, qué se detectó, qué no y por qué. Esta sesión es donde más aprendizaje real ocurre.

Si la propuesta no menciona varios de estos entregables, el proveedor está vendiendo pentest caro con nombre de red team.

---

## Errores frecuentes al contratar red team

**Contratarlo antes de tiempo.** Si tu organización todavía tiene problemas de seguridad básicos, el red team los va a encontrar en días y el resto del engagement pierde valor. Arreglá lo básico primero.

**No involucrar al C-level.** Red team tiene que tener sponsor ejecutivo porque puede generar momentos incómodos — detección de credenciales débiles de gerentes, exposición de decisiones de arquitectura cuestionables, situaciones donde el red team "gana" de formas que generan fricción política. Sin sponsor C-level, las conclusiones se diluyen.

**Demasiadas restricciones.** Cada exclusión del alcance reduce el valor. *"No hagan phishing porque puede molestar a los empleados"* elimina el vector de ataque número uno. *"No toquen el ERP porque es crítico"* elimina el sistema más valioso. A más restricciones, más teatro y menos ejercicio real.

**Esperar sorpresas.** Si el CISO sabe exactamente qué va a encontrar el red team, el ejercicio no tiene sentido. Red team vale cuando hay incertidumbre sobre el resultado.

**Ocultar el ejercicio al blue team.** Hay debate sobre si avisar o no. Mi recomendación pragmática: en el primer red team de una organización, **avisar** al SOC que va a ocurrir en algún momento del trimestre sin dar detalles. Esto reduce el riesgo de un SOC respondiendo a "incidente real" cuando es ejercicio. En engagements subsiguientes, ya con madurez, se puede hacer sin avisar.

**Solo medir el resultado final.** El valor no está solo en *"¿se alcanzó el objetivo?"*. Está en la historia completa del engagement — en qué momentos el blue team casi detecta, qué técnicas fallaron, qué controles funcionaron aunque no se hayan enterado de la detección. El reporte es donde vive el aprendizaje.

---

## La diferencia entre red team y assumed breach

Una variante que aparece cada vez más: **assumed breach assessment**. En lugar de invertir semanas intentando entrar desde afuera, se asume que el atacante ya consiguió acceso inicial (por ejemplo, credenciales de un empleado por phishing) y el ejercicio empieza desde ahí.

Es una opción más eficiente cuando:

- El objetivo del ejercicio es evaluar detección de movimiento lateral, no de initial access.
- Ya sabés que tu initial access tiene fallas (phishing realmente funciona contra tu gente) y querés concentrar el presupuesto en lo que pasa después.
- Querés un engagement más corto por restricciones de tiempo o presupuesto.

El red team puro tiene su valor porque incluye el initial access real, pero assumed breach es un formato legítimo y más económico para empresas que ya saben por dónde entrarían los atacantes y quieren evaluar qué pasa después.

---

## El marco legal importa

Como en todo lo ofensivo, red team **solo tiene sentido con contrato formal y alcance explícito firmado antes de la primera acción**. Esto es obvio pero hay detalles específicos del red team que merecen mención.

**Autorización escrita** para usar ingeniería social contra empleados, si está en el alcance. Legalmente no es lo mismo que phishing en pentest — la escala y sofisticación son mayores.

**Carta "get out of jail"** física y digital. Si el red teamer intenta entrar a la oficina como parte del ejercicio y lo agarran, necesita una carta firmada por el CEO explicando que es ejercicio autorizado. Si el blue team lo detecta digitalmente y llama a la policía, necesita número de contacto 24/7 del sponsor.

**Cláusulas de confidencialidad bilaterales** con el proveedor. Durante el engagement tienen acceso a información crítica; esa confidencialidad tiene que estar contractualmente protegida.

**Reglas claras de manejo de datos reales.** Si el red team llega a exfiltrar datos de clientes, ¿qué hacen con ellos? El contrato tiene que especificar destrucción certificada, no almacenamiento, no uso.

**Coordinación con áreas legales y compliance** desde el diseño del engagement. En sectores regulados, hay obligaciones de reporte a reguladores si se detecta una brecha — y el red team puede parecer exactamente eso. Coordinar evita problemas innecesarios con reguladores.

---

## Próximos pasos

Si después de leer esto estás más cerca de contratar red team, el siguiente paso natural es [**Qué es pentesting y cómo funciona**](/guia/que-es-pentesting-como-funciona-fases-tipos) si no lo viste antes, porque el pentest es el prerequisito práctico antes del red team.

Si lo que te interesa es entender qué adversarios existen y cómo se mapean sus técnicas, el framework [MITRE ATT&CK](https://attack.mitre.org/) es lectura directa y gratuita. Las [evaluaciones de MITRE Engenuity](https://evals.mitre.org/results/enterprise?view=cohort&evaluation=er7&result_type=DETECTION&scenarios=1,2) son donde las empresas de detección se miden contra adversarios documentados, y son referencia obligada para evaluar productos EDR como los que cubrimos en [Antivirus y EDR](/productos/antivirus-y-edr).

Si te interesa el otro lado —ser red teamer, no contratar uno— empezá por [**Qué Es un Pentester: Rol, Tipos y Por Qué las Empresas los Contratan**](/guia/que-es-un-pentester-perfil-rol-como-evaluar) y después especializate con las certificaciones que cubrimos en [**Certificaciones ofensivas: OSCP, OSEP, CRTO y alternativas**](/guia/certificaciones-pentesting-que-exigir-proveedor).

El contexto completo de las tres disciplinas ofensivas está en el hub: [**Seguridad Ofensiva: Qué es Ethical Hacking, Pentesting y Red Teaming**](/guia/seguridad-ofensiva-ethical-hacking-pentesting-red-teaming).

---

*¿Estás evaluando una propuesta de red team y querés segunda opinión sobre si vale lo que cuesta? Escribinos a [contacto@capacero.online](mailto:contacto@capacero.online).*
