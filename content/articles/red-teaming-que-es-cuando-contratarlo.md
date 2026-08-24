---
title: "Red teaming: qué es, cuándo contratarlo y cómo evaluarlo"
subtitle: Red team no es pentesting caro ni pentesting largo. Responde otra pregunta, y solo sirve si la empresa ya tiene un equipo defensivo al que evaluar.
excerpt: Un pentest pregunta qué vulnerabilidades hay. Un red team pregunta si un atacante real llegaría al objetivo sin que nadie lo note. Cuándo tiene sentido pagarlo.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-04-22 02:45:00
updated: 2026-08-24
meta_title: "Red teaming: qué es y cuándo contratarlo"
meta_description: "Red team no es pentesting premium. Qué es la emulación de adversarios, cuándo conviene un purple team en su lugar y cuándo la empresa está lista."
---

Un red team cuesta entre USD 50.000 y 250.000 y dura meses. La pregunta que conviene hacerse antes de firmar es qué se recibe exactamente a cambio, porque no es pentesting más caro, ni más largo, ni más profundo. Es otra disciplina.

Si no leíste [qué es pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos), conviene empezar por ahí: el resto asume eso conocido.

---

## Dos preguntas distintas

| | Pentest | Red team |
|---|---|---|
| Pregunta que responde | ¿Qué vulnerabilidades tiene el sistema? | ¿Llegaría un atacante real al objetivo sin que lo detecten? |
| Alcance | Lista de IPs y aplicaciones definida | Un objetivo concreto, cualquier vector no prohibido |
| Duración | 2–4 semanas | 1–6 meses |
| Ruido | Permitido | Prohibido: no ser detectado es parte del ejercicio |
| Resultado | Lista priorizada de hallazgos | La historia de qué pasó y qué se detectó |
| Qué evalúa | La superficie técnica | La capacidad de detección y respuesta |

Los dos servicios tienen valor y no compiten. Un pentest informa que hay 47 vulnerabilidades y 12 críticas: información necesaria y accionable. Lo que no dice es si el equipo de seguridad va a notar a un atacante real cuando llegue.

El producto de un red team es esa segunda respuesta, y toma forma de línea de tiempo.

```svg
<svg viewBox="0 0 660 220" role="img" aria-label="Ejemplo de línea de tiempo de un ejercicio de red team: acceso inicial el día 12, escalada el 14, control del dominio el 19, exfiltración el 23, y detección del centro de operaciones recién el día 31 con atribución incorrecta, lo que deja 19 días de acceso sin detectar">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Ejemplo de línea de tiempo de un ejercicio</text>

  <rect x="239" y="106" width="315" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M30 120 L630 120" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="239" y="56" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">día 12</text>
  <text x="239" y="70" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">acceso inicial</text>
  <path d="M239 76 L239 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="239" cy="120" r="4.5" fill="currentColor"/>

  <text x="290" y="86" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">día 14</text>
  <text x="290" y="100" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">escalada</text>
  <path d="M283 106 L272 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="272" cy="120" r="4.5" fill="currentColor"/>

  <text x="355" y="56" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">día 19</text>
  <text x="355" y="70" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">control del dominio</text>
  <path d="M355 76 L355 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="355" cy="120" r="4.5" fill="currentColor"/>

  <text x="440" y="86" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">día 23</text>
  <text x="440" y="100" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">exfiltración</text>
  <path d="M432 106 L421 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="421" cy="120" r="4.5" fill="currentColor"/>

  <circle cx="554" cy="120" r="5.5" fill="#e23a3a"/>
  <path d="M554 128 L554 146" stroke="#e23a3a" stroke-width="1" opacity="0.6"/>
  <text x="554" y="160" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">día 31: lo detectan</text>
  <text x="554" y="174" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">y lo atribuyen mal</text>

  <text x="396" y="196" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">19 días de acceso sin detección</text>
  <text x="330" y="214" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Ese número es el producto del ejercicio, no la lista de fallas</text>
</svg>
```

Esa historia es lo que se lleva al directorio para justificar inversión en detección en lugar de más prevención.

---

## Qué hace distinto al adversario simulado

Un red team trabaja emulando las condiciones de un atacante real, y eso tiene consecuencias concretas.

Usa las herramientas que usan los atacantes: [Cobalt Strike](https://www.cobaltstrike.com) es el estándar comercial, con alternativas libres como [Sliver](https://github.com/BishopFox/sliver) y [Mythic](https://github.com/its-a-feature/Mythic) ganando terreno. Monta infraestructura propia de comando y control —dominios comprados para el ejercicio, servidores no asociados a la firma, redirectores— que no viene en ninguna distribución.

Mantiene seguridad operativa durante todo el ejercicio: rota dominios e IPs y evita cualquier herramienta que deje rastros identificables. Si el equipo defensivo lo detecta, el ejercicio pierde parte de su valor.

Y espera. Un atacante con recursos puede pasar meses dentro de una red antes de ejecutar su objetivo. El tiempo de observación es parte del método, no relleno de la factura.

### Emulación de adversarios

Lo que separa al red team contemporáneo del pentest glorificado es la **emulación de adversarios**: replicar las tácticas y técnicas de un grupo real y documentado en lugar de atacar de forma genérica.

El cliente elige a quién simular. Un banco mexicano puede pedir [FIN7](https://attack.mitre.org/groups/G0046/), por ser el grupo con motivación financiera y técnicas documentadas contra el sector. Una empresa energética, [Sandworm](https://attack.mitre.org/groups/G0034/). Una tecnológica con exposición a Asia, [APT41](https://attack.mitre.org/groups/G0096/).

El lenguaje común es [MITRE ATT&CK](https://attack.mitre.org/), que mapea las técnicas conocidas en una matriz y documenta qué usa cada grupo. La utilidad práctica es directa: si las detecciones están calibradas para actividad sospechosa genérica pero el ejercicio emula a FIN7, ahí se ve si sirven contra adversarios reales o solo contra el manual.

[MITRE Engenuity publica evaluaciones](https://evals.mitre.org/results/enterprise?view=cohort&evaluation=er7&result_type=DETECTION&scenarios=1,2) donde los productos de detección se enfrentan a esas emulaciones. Son lectura obligada para evaluar [productos de antivirus y EDR](/productos/antivirus-y-edr).

---

## Purple team: casi siempre la mejor primera compra

La mayoría de las empresas que piden red team se benefician más de un **purple team**, y es la recomendación que un vendedor de red team rara vez ofrece.

En un purple team el equipo ofensivo y el defensivo trabajan juntos y en tiempo real. El primero ejecuta una técnica, el segundo mira si la detecta, comparan notas, ajustan la detección y repiten. No gana quien encuentra al otro: el resultado es lo que aprenden los dos.

| | Purple team | Red team |
|---|---|---|
| Costo | USD 30.000 – 80.000 | USD 100.000+ |
| Duración | 2–4 semanas | 3+ meses |
| Efecto sobre el equipo defensivo | Lo entrena | Lo evalúa |
| Retroalimentación | Inmediata, técnica por técnica | Al final, en el informe |

El red team puro tiene sentido después de varios purple teams, cuando se busca una evaluación en condiciones reales sin avisar al equipo defensivo. Es el nivel siguiente, no la puerta de entrada. Si un proveedor ofrece red team sin preguntar antes si se hizo un purple team, está priorizando su facturación.

---

## Cuándo la empresa está lista

| No contratar red team si… | Contratarlo si… |
|---|---|
| No hay MFA obligatorio en todas las cuentas | Hay un centro de operaciones con 12 meses de funcionamiento, propio o gestionado |
| No hay EDR desplegado en la mayoría de los equipos | Hubo pentests previos y los hallazgos están remediados |
| El equipo de seguridad es de menos de tres personas dedicadas | Las herramientas de detección están instaladas y configuradas |
| Nunca se hizo un pentest real | Ya se hizo al menos un purple team |
| No hay proceso documentado de respuesta a incidentes | Hay una razón concreta: sector regulado, ataques dirigidos previos, perfil alto |
| La empresa tiene menos de 200 empleados y le falta lo básico | La empresa tiene tamaño suficiente para merecer un ataque sofisticado |

El primer punto es literal: [los accesos que quedan sin segundo factor](/guia/ya-tenes-mfa-y-no-alcanza) son lo primero que se prueba, y si el ejercicio entra el día uno por ahí, pierde todo valor formativo. Con presupuesto limitado, rinde más gastarlo en [MFA](/productos/mfa-y-autenticacion), EDR y [respaldos](/productos/backup-y-recuperacion).

Una prueba de realidad rápida: si el responsable de seguridad no puede detallar qué detecciones tiene activas hoy y cuánto tarda el equipo en responder a un incidente de severidad media, la empresa no está lista, porque no va a poder interpretar el resultado.

---

## Fases de un ejercicio completo

| Fase | Duración | Qué pasa |
|---|---|---|
| Alcance y reglas | 1–2 semanas | Objetivo, adversario a emular, restricciones, contactos de emergencia, criterio de corte |
| Inteligencia previa | 1 semana | Reconocimiento del cliente y del adversario; se monta la infraestructura de ataque |
| Acceso inicial | 1–4 semanas | Phishing dirigido, explotación de lo expuesto, ingeniería social. La fase más incierta |
| Persistencia y C2 | 1–2 semanas | Canales encubiertos, mecanismos que sobreviven reinicios, reconocimiento interno |
| Movimiento lateral | 2–6 semanas | Avance hacia sistemas valiosos. La fase más larga y la de mayor oportunidad de detección |
| Objetivo | 1 semana | Se cumple con evidencia y sin daño real: exfiltración simulada con datos marcados |
| Informe y repaso | 1–2 semanas | Informes técnico y ejecutivo, más la sesión conjunta de los dos equipos |

Un ejercicio serio rara vez baja de tres meses de calendario, aunque las horas efectivas sean menos.

Del lado defensivo, lo que más achica la superficie de acceso inicial es autenticar el propio dominio con [SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso), para que el phishing dirigido no llegue firmado como propio.

---

## Precios y proveedores

Un purple team de tres a cuatro semanas arranca en USD 30.000. Un red team acotado, en 50.000. Uno estándar con emulación de un grupo específico, entre 100.000 y 200.000. Los ejercicios extendidos de seis meses o más superan los 200.000. Las firmas de LATAM cotizan entre el 60% y el 80% de esos rangos; las internacionales operando en la región, casi el precio completo. El desglose completo, junto con el resto del presupuesto ofensivo, está en la [guía de costos de pentesting y red team](/guia/costos-pentesting-red-team-presupuesto-empresarial).

Encarecen el precio la ingeniería social, el componente físico y la exclusividad del equipo.

Con reputación consolidada a nivel internacional y sin afiliación de por medio: Mandiant, CrowdStrike Services, NCC Group, Bishop Fox, SpecterOps, TrustedSec, Coalfire y Synack. En Europa, WithSecure y Accenture tras absorber a Context IS.

En LATAM hay firmas locales serias, aunque el mercado es más chico y menos documentado. Los filtros adicionales frente a un pentest común son experiencia verificable en emulación de adversarios, [certificaciones avanzadas](/guia/certificaciones-pentesting-que-exigir-proveedor) del tipo OSEP o CRTO, infraestructura de comando y control propia, y casos de estudio disponibles bajo acuerdo de confidencialidad. Que una firma haga buen pentesting no implica que haga buen red team: son disciplinas distintas.

---

## Qué se recibe al final

- **Informe ejecutivo**, de 10 a 20 páginas, en lenguaje de negocio: si el objetivo se alcanzó, con qué impacto y qué se recomienda.
- **Informe técnico**, de 80 a 200 páginas, con cada técnica mapeada a MITRE ATT&CK, evidencia, fechas y qué se detectó.
- **Indicadores de compromiso**: IPs, dominios y hashes usados, para que el equipo defensivo los busque hacia atrás en sus registros.
- **Recomendaciones de detección**, que no son "arreglen esta falla" sino las consultas concretas para detectar a futuro lo que no se detectó.
- **Presentación ejecutiva** y **sesión de repaso conjunta** entre los dos equipos, que es donde ocurre la mayor parte del aprendizaje.

Una propuesta que no menciona varios de estos entregables está vendiendo un pentest caro con otro nombre.

---

## Errores frecuentes

- **Contratarlo antes de tiempo.** Si quedan problemas básicos, el ejercicio los encuentra en días y el resto pierde sentido.
- **No involucrar a la dirección.** El ejercicio genera momentos incómodos: contraseñas débiles de gerentes, decisiones de arquitectura expuestas. Sin respaldo ejecutivo, las conclusiones se diluyen.
- **Restringir de más.** Cada exclusión resta valor. Prohibir el phishing elimina el vector número uno; excluir el sistema más crítico elimina el objetivo que importaba.
- **Esperar que no haya sorpresas.** Si ya se sabe qué va a encontrar, el ejercicio no hace falta.
- **Medir solo el resultado final.** El valor está en la historia completa: dónde el equipo defensivo casi detecta, qué técnicas fallaron, qué controles funcionaron sin que nadie se enterara.

Sobre avisar o no al equipo defensivo hay debate. En el primer ejercicio de una organización conviene avisar que ocurrirá en algún momento del trimestre, sin dar detalles: evita que el equipo responda como si fuera un incidente real. En los siguientes, con más madurez, puede hacerse sin aviso.

---

## Acceso ya concedido, una variante más barata

En un ejercicio de **acceso ya concedido** se asume que el atacante consiguió la entrada inicial —por ejemplo, credenciales de un empleado— y el trabajo empieza desde ahí.

Conviene cuando lo que se quiere evaluar es la detección del movimiento lateral y no la del acceso inicial, cuando ya se sabe que el phishing funciona contra la propia gente, o cuando el presupuesto obliga a acortar. Es un formato legítimo y más económico para quien ya sabe por dónde entrarían.

---

## El marco legal

Un red team solo procede con contrato firmado y alcance explícito antes de la primera acción. Cinco puntos propios de esta disciplina:

1. **Autorización escrita** para ingeniería social contra empleados. La escala y la sofisticación no son las de un phishing de pentest.
2. **Carta de autorización**, física y digital. Si el operador entra a la oficina como parte del ejercicio y lo detienen, necesita una carta firmada por la dirección; si lo detectan por medios digitales y alguien llama a la policía, hace falta un contacto disponible las 24 horas.
3. **Confidencialidad bilateral.** Durante el ejercicio el proveedor accede a información crítica.
4. **Reglas de manejo de datos reales.** Si el equipo llega a exfiltrar datos de clientes, el contrato tiene que exigir destrucción certificada.
5. **Coordinación con las áreas legal y de cumplimiento** desde el diseño. En sectores regulados hay obligación de reportar brechas, y un ejercicio puede parecer exactamente eso.

El panorama de las tres disciplinas ofensivas está en [seguridad ofensiva](/guia/seguridad-ofensiva-ethical-hacking-pentesting-red-teaming), y el perfil profesional de quien las ejecuta, en [qué es un pentester](/guia/que-es-un-pentester-perfil-rol-como-evaluar).
