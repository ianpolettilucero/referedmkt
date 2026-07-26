---
name: CrowdStrike Falcon
brand: CrowdStrike
category: antivirus-y-edr
description_short: Plataforma cloud-native de protección de endpoints líder del mercado. Combina antivirus de nueva generación (NGAV), EDR, XDR, threat intelligence y threat hunting gestionado 24/7 en un único agente liviano. Reconocida como Líder en el Gartner Magic Quadrant para Endpoint Protection Platforms. Ideal para empresas medianas y grandes que buscan detección avanzada, respuesta automatizada y visibilidad total sobre su superficie de ataque sin degradar el rendimiento.
logo_url: /uploads/ciberseguridad/2026/04/red-feather-icon-c519401f.png
rating: 4.8
price_from: 59.99
price_currency: USD
pricing_model: custom
featured: true
meta_title: "CrowdStrike Falcon: EDR y Antivirus Empresarial Líder"
meta_description: Plataforma cloud-native de protección de endpoints líder del mercado. NGAV, EDR, threat hunting 24/7 y MDR en un único agente liviano.
updated: 2026-04-20
features:
  - NGAV con machine learning e indicadores de ataque (IoA)
  - EDR con telemetría continua y retención en la nube
  - Threat hunting gestionado 24/7 (Falcon OverWatch)
  - Threat intelligence con perfiles de más de 200 adversarios rastreados
  - Real Time Response: shell remota para investigación y contención
  - Aislamiento de red remoto con un clic
  - Protección de identidades y Active Directory (Falcon Identity Protection)
  - Gestión de vulnerabilidades sin escaneos adicionales (Spotlight)
  - Protección de cargas cloud, contenedores y Kubernetes
  - Agente único y liviano para Windows, macOS, Linux, iOS, Android
  - Integración nativa con SIEM, SOAR, IAM y herramientas de ticketing
  - API REST completa y CrowdStrike Store para extensibilidad
  - Opción MDR totalmente gestionada (Falcon Complete) con garantía económica
pros:
  - Líder indiscutido según Gartner, Forrester e IDC
  - Muy alta eficacia en detección y prevención validada por MITRE ATT&CK Evaluations
  - Agente extremadamente liviano, bajo impacto en el rendimiento
  - Cloud-native: despliegue rápido y sin infraestructura on-premise
  - Consola moderna, intuitiva y rápida
  - Threat intelligence de primer nivel incluida
  - OverWatch aporta cazadores humanos reales, no solo algoritmos
  - Excelente capacidad de respuesta y forense remoto
  - Ecosistema de integraciones muy amplio
  - Opción MDR de clase mundial con Falcon Complete
cons:
  - Precio premium, no siempre accesible para PyMEs
  - Requiere conectividad a internet para aprovechar toda la inteligencia
  - Curva de aprendizaje para explotar módulos avanzados de EDR y hunting
  - Modelo 100% cloud puede no encajar en entornos con restricciones regulatorias estrictas de soberanía de datos
  - Algunas funciones avanzadas (Identity, Cloud Security, Spotlight) se licencian por separado y suman al costo
  - Soporte en español limitado según región, a menudo se canaliza vía partners
specs:
  Plataformas: Windows, macOS, Linux, iOS, Android, ChromeOS
  Arquitectura: Cloud-native SaaS
  Agente: Único, <100 MB RAM, ~1% CPU
  Modo de despliegue: MSI, DMG, RPM, DEB, contenedor, GPO, SCCM, Intune, Jamf, RMM
  Retención de telemetría: 7 a 90 días según licencia
  Licenciamiento: Por endpoint / por año
  Bundles: Falcon Go, Pro, Enterprise, Elite, Complete
  Certificaciones: SOC 2 Type II, ISO 27001, FedRAMP High, PCI-DSS
  MITRE ATT&CK: Participación destacada en evaluaciones anuales
  Soporte: 24/7 global, portal, teléfono, partner
  Consola: Web, multi-tenant para MSSP
  API: REST completa, webhooks, SDK
  Integraciones: Splunk, Sentinel, QRadar, XSOAR, ServiceNow, Okta, Azure AD
  Sede: Austin, Texas, EE.UU. (fundada en 2011)
---

**CrowdStrike Falcon** es una plataforma unificada de ciberseguridad de endpoints entregada **100% desde la nube**, diseñada para proteger estaciones de trabajo, servidores, cargas cloud, contenedores y dispositivos móviles frente a amenazas modernas. Fundada en 2011 por George Kurtz y Dmitri Alperovitch, CrowdStrike redefinió el estándar de la industria al abandonar el modelo basado en firmas y construir una arquitectura que combina **inteligencia artificial, análisis de comportamiento, telemetría masiva y threat intelligence** de primer nivel.

Hoy es considerada por *Gartner*, *Forrester* e *IDC* como una de las soluciones líderes del mercado, con decenas de miles de clientes empresariales en todo el mundo, incluyendo gran parte del Fortune 500.

---

## Arquitectura y filosofía del producto

El corazón de Falcon es su **agente único**, llamado *Falcon sensor*. A diferencia de suites tradicionales que requieren instalar varios componentes pesados (antivirus, HIPS, DLP, EDR, firewall de host), CrowdStrike apuesta por un solo agente de muy bajo consumo que habilita todos los módulos mediante licenciamiento.

El agente se comunica de forma constante con el **Threat Graph**, una base de datos masiva en la nube que procesa **billones de eventos por semana** recolectados de toda la base instalada. Esa telemetría global permite detectar patrones de ataque emergentes en minutos y propagar la protección al resto de los clientes prácticamente en tiempo real.

> La filosofía del producto se resume en tres principios: la prevención al 100% es imposible, la inteligencia humana experta sigue siendo imprescindible, y la nube es el único modelo viable para procesar el volumen de datos necesario para detectar ataques modernos.

---

## Módulos principales

La plataforma se organiza en módulos que pueden contratarse por separado o en bundles (*Falcon Go*, *Pro*, *Enterprise*, *Elite* y *Complete*).

### Falcon Prevent (NGAV)

Reemplaza al antivirus tradicional utilizando:

- Machine learning entrenado con miles de millones de muestras
- Indicadores de ataque (**IoA**) basados en comportamiento
- Bloqueo de exploits y ataques sin archivo
- Cumplimiento con **PCI-DSS**, **HIPAA** y otras regulaciones

### Falcon Insight (EDR)

Registra de forma continua la actividad del endpoint y la correlaciona con inteligencia global. Permite:

1. Investigar incidentes y reconstruir la cadena completa de un ataque
2. Aislar equipos remotamente de la red
3. Ejecutar scripts de remediación a distancia
4. Hacer consultas forenses en vivo con **Real Time Response**

### Falcon OverWatch

Servicio de **threat hunting gestionado 24x7x365**. Un equipo propio de cazadores humanos revisa manualmente la telemetría buscando actividad sospechosa que los algoritmos podrían haber pasado por alto.

### Falcon Intelligence

Informes de inteligencia de amenazas con perfiles de **más de 200 adversarios rastreados** (*Fancy Bear*, *Cozy Bear*, *Wicked Panda*, *Mustang Panda*, entre otros), análisis automatizado de malware en sandbox y atribución de ataques.

### Otros módulos

- **Falcon Discover** — visibilidad de activos, aplicaciones y cuentas
- **Falcon Spotlight** — gestión de vulnerabilidades sin escaneos adicionales
- **Falcon Identity Protection** — protección de Active Directory y Azure AD
- **Falcon Cloud Security** — CSPM y CWPP para AWS, Azure, GCP y Kubernetes
- **Falcon Complete** — servicio MDR con garantía económica de hasta **USD 1.000.000** ante brecha

---

## Capacidades técnicas destacadas

La prevención opera en **varias capas paralelas**:

- **ML local** — bloquea ejecutables maliciosos por modelo entrenado. *No requiere internet.*
- **IoA (Indicadores de Ataque)** — detecta técnicas de ataque en comportamiento. *No requiere internet.*
- **Cloud ML** — consulta modelos de machine learning en tiempo real. *Requiere internet.*
- **Threat Graph** — correlación global de telemetría a escala. *Requiere internet.*

El componente EDR retiene la telemetría en la nube entre **7 y 90 días** según la licencia, habilitando **investigaciones retrospectivas**: si mañana se descubre un nuevo IoC, un analista puede buscar si alguna vez apareció en la flota.

La consola web permite *hunting* con un lenguaje de consulta propio (**Falcon Query Language**), similar a SQL.

---

## Integración y ecosistema

CrowdStrike expone una **API REST** completa e integra nativamente con:

- **SIEMs** — Splunk, Microsoft Sentinel, IBM QRadar, Elastic
- **SOAR** — Palo Alto XSOAR, Splunk SOAR, Tines
- **Tickets** — ServiceNow, Jira
- **Identidad** — Okta, Azure AD, Ping
- **CrowdStrike Store** — apps de terceros que corren sobre la misma plataforma

---

## Rendimiento y despliegue

- **RAM** — menos de 100 MB
- **CPU** — aproximadamente 1% en condiciones normales
- **Sin actualizaciones de firmas** — la inteligencia vive en la nube

Soporta Windows (7 SP1 a 11, Server 2008 R2 a 2022), macOS (10.15 a Sequoia), Linux (*RHEL*, *CentOS*, *Rocky*, *Alma*, *Oracle*, *Ubuntu*, *Debian*, *SUSE*, *Amazon Linux*), iOS, Android y ChromeOS.

El despliegue se realiza mediante instaladores MSI, DMG, RPM, DEB o contenedores; también vía **GPO**, **SCCM**, **Intune**, **Jamf** y herramientas **RMM**. En un entorno típico de 1.000 endpoints, el despliegue completo toma pocos días incluyendo fase piloto.

---

## Casos de uso ideales

- Organizaciones que enfrentan **amenazas avanzadas** (APTs, ransomware dirigido)
- Sectores regulados: **financiero, salud, energía, gobierno, defensa**
- Empresas que sufrieron incidentes previos y necesitan capacidades maduras de respuesta
- Mid-market con equipos de seguridad reducidos que contratan **Falcon Complete**

---

## Consideraciones

- **Precio premium** — mayor al de alternativas tradicionales
- **Dependencia de conectividad** — para aprovechar toda la inteligencia cloud
- **Curva de aprendizaje** — para explotar EDR y threat hunting a fondo
- **Modelo 100% cloud** — puede no encajar con requisitos estrictos de soberanía de datos
- **Módulos separados** — *Identity*, *Cloud Security* y *Spotlight* se licencian aparte

---

## Conclusión

CrowdStrike Falcon representa el **estado del arte** en protección de endpoints empresariales. Su combinación de prevención basada en IA, EDR de clase mundial, threat intelligence accionable y servicios humanos gestionados lo convierte en la referencia obligatoria al evaluar soluciones serias.

Para organizaciones argentinas y latinoamericanas que están elevando su madurez de ciberseguridad, es una apuesta estratégica que rara vez se arrepiente a largo plazo.
