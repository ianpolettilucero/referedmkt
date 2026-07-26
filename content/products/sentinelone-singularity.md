---
name: SentinelOne Singularity
brand: SentinelOne
category: antivirus-y-edr
description_short: Plataforma XDR autónoma de SentinelOne que unifica antivirus de nueva generación, EDR, protección de identidades, cloud workloads y data lake en un único agente impulsado por IA. Destaca por su capacidad de respuesta automática y su función exclusiva de rollback ante ransomware, que revierte cambios maliciosos con un clic. Reconocida como Líder por Gartner en Endpoint Protection Platforms y una de las alternativas directas más sólidas frente a CrowdStrike.
logo_url: /uploads/ciberseguridad/2026/04/sentinelone-symbol-svg-e4d52e98.svg
rating: 4.7
price_from: 69.99
price_currency: USD
pricing_model: yearly
featured: false
meta_title: "SentinelOne Singularity: XDR Autónomo con IA y Rollback Anti-Ransomware"
meta_description: Plataforma XDR autónoma con IA en el endpoint, EDR con Storylines y rollback exclusivo ante ransomware. Alternativa líder a CrowdStrike para empresas medianas y grandes.
updated: 2026-04-20
features:
  - NGAV con motores de IA estáticos y de comportamiento ejecutándose localmente
  - Funcionamiento autónomo del agente incluso sin conexión a la nube
  - EDR con Storylines: reconstrucción automática de la cadena de ataque
  - Rollback automático ante ransomware en Windows mediante Volume Shadow Copies
  - Respuesta automática: bloqueo, aislamiento, cuarentena y terminación de procesos
  - Full Remote Shell para investigación y respuesta interactiva
  - Singularity Identity: protección de Active Directory y Azure AD con decepción
  - Singularity Cloud: CWPP y CNAPP para AWS, Azure, GCP, Kubernetes y contenedores
  - Singularity Data Lake: ingesta y análisis de logs de cualquier fuente
  - Purple AI: copiloto basado en LLM para investigación en lenguaje natural
  - Singularity Hyperautomation: SOAR nativo integrado
  - Vigilance MDR 24x7 operado por SentinelOne
  - Despliegue flexible: SaaS multi-tenant u on-premise
pros:
  - Líder en Gartner Magic Quadrant y excelentes resultados en MITRE ATT&CK
  - Agente autónomo: detecta y bloquea sin depender de la nube
  - Rollback automático ante ransomware, función casi exclusiva en el mercado
  - Storylines simplifica drásticamente la investigación forense
  - Gran experiencia de usuario en la consola
  - Opción de despliegue on-premise disponible, poco común en EDR modernos
  - Plataforma XDR completa: endpoint, identidad, cloud, datos, mobile
  - Soporte nativo para Linux y cargas cloud con bajo impacto
  - Purple AI acelera el trabajo de los analistas con lenguaje natural
cons:
  - Precio premium, en rango similar a CrowdStrike
  - Funciones avanzadas (Identity, Cloud, Data Lake, XDR) se licencian por separado
  - Rollback completo solo aplica con toda su potencia en Windows
  - Curva de aprendizaje significativa para aprovechar hunting y automatización
  - Ecosistema de partners en LATAM más pequeño que el de Microsoft o CrowdStrike
  - Soporte en español depende en gran medida del canal
  - La oferta XDR aún es percibida por algunos analistas como más joven que la de competidores
specs:
  Plataformas: Windows, macOS, Linux, Kubernetes, contenedores, iOS, Android, ChromeOS
  Arquitectura: Agente autónomo con IA local + plataforma cloud (SaaS u on-premise)
  Agente: Único, IA estática y de comportamiento integradas
  Modo de despliegue: MSI, PKG, RPM, DEB, SCCM, Intune, JAMF, Ansible, RMM
  Función exclusiva: Rollback automático ante ransomware en Windows
  Licenciamiento: Por endpoint / por año
  Bundles: Singularity Core, Control, Complete, Commercial, Enterprise
  Certificaciones: SOC 2 Type II, ISO 27001, FedRAMP Moderate, HIPAA, PCI-DSS
  MITRE ATT&CK: Resultados destacados en evaluaciones anuales consecutivas
  Soporte: 24/7 global, portal, teléfono, Vigilance MDR opcional
  Consola: Web, multi-tenant para MSSP, multi-site para grandes empresas
  API: REST completa, webhooks, marketplace de integraciones
  Integraciones: Splunk, Sentinel, QRadar, XSOAR, Okta, Azure AD, ServiceNow
  Sede: Mountain View, California, EE.UU. (fundada en 2013, NYSE: S)
---

**SentinelOne Singularity** es la plataforma de ciberseguridad empresarial desarrollada por SentinelOne, compañía fundada en 2013 y cotizada en la Bolsa de Nueva York desde 2021 (*NYSE: S*).

Su propuesta se centra en una idea clara: **automatizar al máximo la detección, la prevención y la respuesta** mediante inteligencia artificial ejecutándose directamente en el endpoint, de manera que el agente pueda tomar decisiones incluso **sin conectividad a la nube**.

---

## Filosofía del producto: autonomía e IA en el endpoint

La mayoría de las plataformas modernas dependen fuertemente de la nube para analizar telemetría. SentinelOne **invierte parcialmente ese modelo**: sus motores de IA corren **localmente en el agente**.

Esto es especialmente relevante para:

- Laptops de usuarios móviles
- Sucursales con conectividad intermitente
- Entornos industriales aislados (**OT/IoT**)
- Dispositivos air-gapped

> El otro pilar filosófico es la **autonomía operativa**: Singularity fue construido con la idea de que los equipos de seguridad siempre tendrán menos analistas que alertas. El producto apuesta fuerte por la respuesta automática con la mínima intervención humana que cada organización configure.

---

## Módulos principales de la plataforma Singularity

### Singularity Endpoint

El núcleo. Combina NGAV, EDR y respuesta automatizada en un único agente. Utiliza dos motores de IA:

1. **Motor estático** — analiza archivos antes de ejecución
2. **Motor de comportamiento** — supervisa procesos en tiempo real

Detecta malware conocido, desconocido, exploits, ataques sin archivo y técnicas de *living off the land*.

### Singularity XDR

Extiende las capacidades a otras fuentes: correo, identidad, red, cloud y SaaS, correlacionando señales para detectar ataques que cruzan dominios.

### Singularity Identity

Protege **Active Directory** y **Azure AD** detectando:

- *Kerberoasting*
- *DCSync*
- *Golden Ticket*
- Enumeración de credenciales

Incluye capacidades de **decepción** (honeytokens, cuentas señuelo) heredadas de la adquisición de *Attivo Networks* en 2022.

### Singularity Cloud

Protege cargas en AWS, Azure, GCP, Kubernetes y contenedores con **CWPP** y, tras la adquisición de *PingSafe* en 2024, **CNAPP/CSPM** unificados.

### Singularity Data Lake

Plataforma de análisis de logs basada en tecnología de *Scalyr*. Permite ingresar datos de cualquier fuente y consultarlos con un lenguaje tipo SQL sobre **retenciones prolongadas**. Se puede usar como alternativa a un SIEM tradicional en muchos casos.

### Servicios gestionados

- **Vigilance Respond** — MDR estándar
- **Vigilance Respond Pro** — MDR premium con respuesta a incidentes 24x7

### Otros módulos

- **Singularity RemoteOps** — ejecución de scripts y recolección forense
- **Singularity for Mobile** — MTD para iOS, Android y ChromeOS
- **Singularity Hyperautomation** — SOAR nativo integrado

---

## Capacidades técnicas destacadas

### Rollback automático ante ransomware

> **La función más distintiva del producto.**

En Windows, SentinelOne aprovecha los **Volume Shadow Copies** para revertir automáticamente los cambios realizados por procesos maliciosos. Si un ransomware comienza a cifrar archivos:

1. El agente lo detecta y detiene
2. Termina todos los procesos relacionados
3. **Restaura los archivos afectados** a su estado original con un clic

Es una capacidad que ningún otro competidor principal ofrece totalmente integrada y automatizada.

### Storylines

Mientras otros EDR muestran listas de eventos crudos, Singularity agrupa automáticamente todos los procesos, conexiones, archivos y eventos relacionados en un **grafo único**. Cada Storyline tiene un identificador propio (*StorylineID*), lo que simplifica enormemente la investigación forense y el hunting.

### Respuesta

- Aislamiento de red remoto con un clic
- Cuarentena de archivos
- Terminación de procesos y procesos hijos
- **Full Remote Shell** — consola remota interactiva en bundles superiores

### Purple AI

Copiloto basado en LLMs que permite a los analistas:

- Hacer preguntas en **lenguaje natural** sobre la telemetría
- Obtener investigaciones asistidas
- Traducir consultas automáticamente
- Recibir recomendaciones de hunting

---

## Rendimiento, despliegue y flexibilidad

| Característica | Detalle |
|---|---|
| Impacto en CPU | Bajo, incluso bajo carga intensiva |
| Actualizaciones | Sin firmas masivas |
| Despliegue | SaaS multi-tenant **u on-premise completo** |
| Multi-site | Sí, para grandes empresas |
| MSSP | Multi-tenant nativo |

> Un diferencial clave: SentinelOne admite **despliegues on-premise completos** para organizaciones con requisitos estrictos de soberanía de datos, algo **poco común** entre los grandes jugadores modernos.

### Plataformas soportadas

- **Windows** — 7 SP1 a 11, Server 2008 R2 a 2025
- **macOS** — versiones actualmente soportadas
- **Linux** — RHEL, CentOS, Rocky, Ubuntu, Debian, SUSE, Amazon Linux, Oracle
- **Contenedores** — Kubernetes, Docker
- **Móviles** — iOS, Android, ChromeOS

El despliegue se realiza mediante instaladores nativos o herramientas estándar como **SCCM**, **Intune**, **JAMF**, **Ansible** o **RMM**.

---

## Integración y ecosistema

El **Singularity Marketplace** ofrece integraciones nativas con:

- **SIEM** — Splunk, Sentinel, QRadar
- **SOAR** — XSOAR, Splunk SOAR, Torq, Tines
- **Ticketing** — ServiceNow, Jira
- **Identidad** — Okta, Azure AD
- **Vulnerabilidades, NDR**, y más

La API REST es completa y bien documentada.

---

## Casos de uso ideales

- Empresas medianas y grandes que valoran la **autonomía del agente**
- Organizaciones con **equipos de seguridad reducidos** donde la remediación automática marca la diferencia
- Sectores con alta exposición a ransomware: **salud, manufactura, financiero, educación**
- Entornos con requisitos estrictos de **soberanía de datos** (on-premise)
- Empresas sin SOC propio que contratan **Vigilance MDR** 24x7

---

## Consideraciones

- **Precio premium** — en rango similar a CrowdStrike
- **Módulos separados** — *Identity*, *Cloud*, *Data Lake* y *XDR* se licencian aparte
- **Rollback** — aplica con toda su potencia **solo en Windows** (depende de Volume Shadow Copies)
- **Curva de aprendizaje** — para aprovechar hunting y automatización
- **Ecosistema de partners en LATAM** — más pequeño que Microsoft o CrowdStrike
- **Soporte en español** — depende del canal regional
- **Percepción XDR** — algunos analistas la ven más joven que la de competidores

---

## Conclusión

SentinelOne Singularity es una plataforma XDR de **primer nivel** con una combinación poco común de:

- IA autónoma en el endpoint
- Visualización clara de ataques vía *Storylines*
- **Rollback exclusivo** ante ransomware
- Ecosistema XDR completo (endpoint, identidad, cloud, datos, mobile)
- Flexibilidad de despliegue (SaaS u on-premise)

Para organizaciones que buscan una alternativa seria a CrowdStrike o que necesitan flexibilidad de despliegue **sin sacrificar capacidades modernas**, Singularity es probablemente la elección más equilibrada del mercado.

Su enfoque en la automatización de la respuesta lo convierte en un aliado estratégico para **equipos de seguridad pequeños** que necesitan proteger grandes flotas sin escalar proporcionalmente el headcount.
