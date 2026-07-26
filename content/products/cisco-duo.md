---
name: Cisco Duo
brand: Cisco
category: mfa-y-autenticacion
description_short: Duo es la plataforma MFA empresarial más adoptada del mundo, con más de 100.000 organizaciones clientes. Adquirida por Cisco en 2018, combina autenticación multifactor, Single Sign-On, verificación de salud de dispositivos y acceso adaptativo. Famosa por su experiencia de usuario excepcional y despliegue rápido, es el estándar de facto en MFA para empresas medianas y grandes.
logo_url: /uploads/ciberseguridad/2026/04/cisco-logo-0-5952e373.svg
rating: 4.6
price_from: 3
price_currency: USD
pricing_model: monthly
featured: false
meta_title: "Cisco Duo: MFA Empresarial - Análisis Completo y Precios 2026"
meta_description: Cisco Duo es la plataforma MFA empresarial más adoptada del mundo. Análisis detallado de características, métodos de autenticación, SSO, Device Trust, precios por tier y posicionamiento frente a Okta y Microsoft Entra ID.
updated: 2026-04-23
features:
  - MFA con Duo Push, Verified Push, passcode, FIDO2, passkeys, biometría
  - Duo Mobile app nativa iOS/Android con push notifications rápidas
  - Single Sign-On con 1.000+ integraciones SaaS pre-construidas
  - Device Trust con verificación de salud completa del endpoint
  - Adaptive Access Policies contextuales (ubicación, red, dispositivo)
  - Risk-Based Authentication con ML (tier Premier)
  - Duo Central portal unificado de aplicaciones para usuarios
  - Passwordless authentication completo con passkeys
  - Trust Monitor para detección de anomalías y push bombing
  - Integraciones nativas con VPN, RDP, SSH, Windows Logon
  - Self-service device management para usuarios finales
  - Branding personalizable de pantallas y portal
  - Tier gratuito funcional hasta 10 usuarios
  - API REST completa y Web SDK para integraciones custom
  - Directory Sync con AD, Entra ID, Google Workspace, sistemas HR
  - Reportes de compliance y auditoría exportables
  - Soporte 24/7 en todos los planes pagos
  - Certificaciones SOC 2, ISO 27001, FedRAMP, HIPAA, PCI DSS
pros:
  - Experiencia de usuario final excepcional, consistentemente la mejor del rubro
  - Duo Push responde en 2-5 segundos con un solo tap
  - Despliegue rápido: horas a días en vez de semanas o meses
  - Más de 1.000 integraciones pre-construidas con SaaS y aplicaciones empresariales
  - Tier gratuito genuinamente funcional hasta 10 usuarios
  - Documentación extensa y comunidad madura
  - Integración natural con stack Cisco (firewalls, switches, routers, ISE)
  - Device Trust robusto para evaluar salud del endpoint antes de permitir acceso
  - Soporte completo para FIDO2, passkeys y passwordless
  - Compliance amplio (FedRAMP, HIPAA, SOC 2, ISO 27001, PCI DSS)
  - Self-service device management reduce carga del help desk
  - Pricing predecible y transparente
cons:
  - Pricing por usuario escala en empresas con mucho personal contratista o externo
  - Funciones ML avanzadas (Risk-Based Auth, Trust Monitor) solo en tier Premier
  - Configuración default deja SMS activado, requiere hardening deliberado
  - Admin Panel pierde simplicidad en deployments grandes con múltiples aplicaciones
  - Para empresas 100% Microsoft, Entra ID Premium P2 puede tener mejor ratio valor/precio
  - No incluye Identity Governance (IGA) formal, requiere solución complementaria
  - Dependencia de conectividad a Internet para autenticar (offline MFA limitado)
  - Integración con stack Microsoft funciona bien pero no es tan seamless como Entra nativo
  - Tier Essentials tiene SSO limitado, el valor real empieza en Advantage
  - Presencia limitada de partners locales en algunos países LATAM
specs:
  Tipo: MFA + SSO + Access Management
  Propietario: Cisco Systems (NASDAQ: CSCO)
  Fundada como Duo Security: 2009
  Adquirida por Cisco: 2018 (USD 2.350 millones)
  Sede: Ann Arbor, Michigan, EE.UU.
  Clientes activos: 100.000+ organizaciones
  Tier gratuito: Sí, hasta 10 usuarios
  Métodos MFA: Push, passcode, FIDO2/WebAuthn, biometría, SMS, llamada, hardware tokens
  Integraciones pre-construidas: 1.000+ aplicaciones SaaS
  Compatible con: Windows, macOS, Linux, iOS, Android, VPN, RDP, SSH
  Protocolos soportados: SAML 2.0, OIDC, OAuth 2.0, RADIUS, LDAP
  Incluye SSO: Sí, desde tier Essentials
  Soporte multitenancy: Sí
  Sincronización directorios: AD, Entra ID, Google Workspace, Workday
  Compliance: SOC 2 Type II, ISO 27001, FedRAMP Moderate, HIPAA, PCI DSS, GDPR, LGPD
  SLA planes Business: 99.9%
  Soporte técnico: Email 24/7 (todos los planes pagos), Priority Support (Premier)
  Idiomas de interfaz: 20+ idiomas incluyendo español, portugués, inglés
---

**Cisco Duo** es la plataforma de autenticación multifactor empresarial más adoptada del mundo, utilizada por más de 100.000 organizaciones en prácticamente todos los sectores. Originalmente fundada como Duo Security en 2009 por Dug Song y Jon Oberheide en Ann Arbor, Michigan, fue adquirida por Cisco Systems en 2018 por **USD 2.350 millones** — una de las adquisiciones más importantes del rubro cyber en la década. Desde entonces opera como unidad dentro del portfolio de seguridad de Cisco pero mantiene su identidad de marca y su enfoque en usabilidad.

Lo que hizo a Duo el estándar de facto no fue una innovación técnica disruptiva sino **la obsesión con hacer MFA empresarial que las personas efectivamente usen sin frustración**. En un mercado donde tradicionalmente los proyectos de MFA fracasaban por resistencia de usuarios finales, Duo se diferenció con una app móvil simple, una UX pulida, y un despliegue técnico que no requería meses de consultoría.

---

## Qué es y cómo funciona

### Arquitectura general

Duo es una **plataforma cloud-native** donde todos los servicios corren en infraestructura de Cisco distribuida globalmente. El cliente no aloja servidores Duo; consume la plataforma vía APIs, conectores SAML/OIDC, y aplicaciones integradas. Los componentes principales:

**Duo Authentication Service**: el motor central que procesa solicitudes de autenticación, evalúa políticas y decide si aprobar, rechazar o requerir verificación adicional.

**Duo Mobile**: la aplicación para iOS y Android que recibe push notifications, genera passcodes, y almacena credenciales FIDO2/WebAuthn. Es el punto de contacto principal con el usuario final.

**Duo Admin Panel**: consola web de administración donde los admins configuran políticas, integran aplicaciones, gestionan usuarios y ven reportes.

**Integraciones**: conectores pre-construidos para más de 1.000 aplicaciones SaaS más soporte estándar para SAML 2.0, OIDC, RADIUS, LDAP, WebSDK, y API REST para integraciones custom.

**Directory Sync**: sincronización con Active Directory, Azure AD/Entra ID, Google Workspace, y sistemas HR para poblar y mantener actualizados los usuarios.

### Cómo es un login típico con Duo

Un usuario intenta acceder a una aplicación protegida (por ejemplo, su cuenta corporativa de Microsoft 365):

1. Ingresa usuario y contraseña como siempre
2. Microsoft 365 redirige la solicitud a Duo para MFA
3. Duo evalúa el contexto: desde qué dispositivo viene, qué red, qué hora, riesgo histórico del usuario
4. Según las políticas configuradas, Duo decide el método: típicamente envía **push notification** al celular del usuario
5. El usuario recibe la notificación en Duo Mobile, ve *"Solicitud de acceso a Microsoft 365"* y aprueba con un tap
6. Duo confirma a Microsoft 365 que la autenticación fue exitosa
7. El usuario entra a su cuenta

Todo este flujo toma **entre 2 y 5 segundos** cuando funciona normalmente. Si el contexto indica riesgo elevado (ubicación inusual, dispositivo no reconocido), Duo puede exigir verificación adicional: passcode generado en la app, FIDO2 key física, o bloquear completamente el acceso según política.

---

## Características y capacidades completas

### Métodos de autenticación multifactor

Duo soporta prácticamente todos los métodos MFA viables en 2026:

**Duo Push**: el método flagship, representa aproximadamente el 80% de los logins Duo globales. Push notification a Duo Mobile donde el usuario ve detalles de la solicitud y aprueba con un tap. Incluye información contextual (ubicación, aplicación, hora) para que el usuario detecte ataques de MFA fatigue.

**Verified Duo Push**: variante más segura donde el usuario debe ingresar un código numérico mostrado en la pantalla de login dentro de la app móvil, no solo tap. Mitiga ataques de push bombing que engañan al usuario a aprobar sin atención.

**Passcodes**: códigos temporales de 6 dígitos generados en Duo Mobile o hardware tokens. TOTP estándar (RFC 6238), compatible con cualquier autenticador.

**FIDO2 / WebAuthn**: soporte completo para security keys físicas como YubiKey, SoloKeys, Feitian, Google Titan, y similares. También funciona con passkeys plataforma (Windows Hello, Touch ID, Face ID, Android biometrics).

**Biometría en dispositivo**: Touch ID y Face ID en iOS, huella digital y reconocimiento facial en Android, Windows Hello en PC.

**SMS y llamada telefónica**: disponibles pero Duo los desaconseja activamente y varios clientes los desactivan completamente por política. SMS es vulnerable a SIM swapping y llamadas a voice phishing.

**Hardware tokens**: tokens HOTP tradicionales como YubiKey OTP, Duo D-100, o tokens compatibles con terceros. Útiles para ambientes sin celulares permitidos (salud, industria, gobierno).

**Bypass codes**: códigos de un solo uso generados por administradores para casos donde el usuario perdió su dispositivo principal. Configurables con expiración y cantidad limitada de usos.

### Single Sign-On

Duo ofrece SSO completo en los planes Essentials, Advantage y Premier. Las características:

**Duo Central**: portal unificado para que los usuarios accedan a todas sus aplicaciones empresariales desde un solo dashboard, similar a Okta Dashboard o Microsoft My Apps.

**Catálogo de aplicaciones**: más de **1.000 aplicaciones SaaS pre-integradas** con conectores específicos. Las principales plataformas (Microsoft 365, Google Workspace, Salesforce, AWS, Slack, Zoom, Atlassian, Dropbox, Workday, ServiceNow) tienen setup guiado en minutos.

**Protocolos soportados**: SAML 2.0, OIDC/OAuth 2.0, y Password-based SSO (vault de contraseñas para aplicaciones legacy sin soporte SSO).

**Custom applications**: cualquier aplicación custom con soporte SAML o OIDC puede integrarse.

### Device Trust y Device Health

Una diferenciación importante de Duo frente a competidores MFA puros: **verificación del estado de seguridad del dispositivo** antes de otorgar acceso.

Duo Device Trust verifica:

- **Sistema operativo actualizado** (Windows, macOS, Linux, iOS, Android)
- **Browser actualizado** (Chrome, Firefox, Edge, Safari)
- **Disco cifrado** (BitLocker, FileVault, LUKS)
- **Password lock habilitado** con política mínima
- **Firewall activo**
- **Antivirus/EDR instalado y actualizado**
- **Dispositivo gestionado** (MDM) vs personal
- **Dispositivo conocido** por la organización vs nuevo

Basado en estos checks, Duo puede decidir bloquear acceso, advertir al usuario, permitir con verificación adicional, o dar acceso normal. Este nivel de control es más sofisticado que lo que ofrecen MFA puros y es una de las razones por las que Duo se adoptó en sectores regulados.

### Adaptive Access Policies

El motor de políticas de Duo evalúa múltiples señales contextuales:

**Ubicación geográfica**: permitir desde países específicos, bloquear otros, requerir verificación adicional según región.

**Red de origen**: tratar diferente conexiones desde oficina vs redes externas vs VPN corporativa vs Tor/anonimizadores.

**Tipo de dispositivo**: diferenciar entre dispositivos gestionados (MDM), dispositivos personales conocidos, y dispositivos nuevos/desconocidos.

**Risk-Based Authentication** (tier Premier): ML de Cisco que analiza patrones históricos del usuario y detecta anomalías. Si alguien que siempre loguea desde Buenos Aires en horario laboral intenta loguear de madrugada desde otro país, el sistema eleva el nivel de verificación automáticamente.

**Trust Monitor**: detección de comportamientos sospechosos como push bombing, spray de credenciales, o uso anómalo.

### Passwordless Authentication

Cisco viene empujando fuerte la transición hacia autenticación sin contraseñas. Duo soporta:

**Passkeys completos**: los usuarios pueden autenticarse usando solo su dispositivo biométrico o security key, sin ingresar password alguna.

**Duo Passwordless**: flujo donde el usuario ingresa su email/username, recibe notificación en Duo Mobile, aprueba con biometría del celular, y queda autenticado sin haber escrito contraseña.

**Integración con Windows Hello y Touch ID**: passkeys respaldados en el hardware de plataforma del usuario.

---

## Integraciones destacadas

Duo se integra con prácticamente cualquier tecnología empresarial moderna. Las categorías principales:

### Stack Microsoft

- **Microsoft 365** (completo, incluyendo Exchange, SharePoint, Teams)
- **Azure Active Directory / Entra ID** (puede sumarse encima de Entra ID para capa MFA adicional)
- **Windows Logon** (MFA al iniciar sesión en PCs Windows)
- **Remote Desktop Gateway** y **RDP**
- **Microsoft Exchange on-premise**

### Stack Google

- **Google Workspace** completo
- **Google Cloud Platform** (GCP)

### Infraestructura y red

- **Cisco AnyConnect VPN** (integración nativa)
- **Palo Alto GlobalProtect**
- **Fortinet FortiGate SSL-VPN**
- **Check Point** VPN
- **OpenVPN**
- **Pulse Secure / Ivanti Connect Secure**
- **Cisco ASA**, **Cisco FTD**

### Acceso remoto y terminales

- **SSH** (Linux servers vía Duo Unix)
- **RDP** (Windows Remote Desktop)
- **Cisco ISE**
- **RADIUS** genérico para cualquier dispositivo compatible

### SaaS y aplicaciones

- **Salesforce** completo incluyendo Communities
- **AWS** con MFA en root account y IAM
- **Workday**
- **ServiceNow**
- **Atlassian** (Jira, Confluence, Bitbucket)
- **Slack** Enterprise
- **Zoom**
- **Box, Dropbox Business**
- **DocuSign**
- **GitHub Enterprise**
- Más de 1.000 aplicaciones SaaS adicionales

### Desarrollo custom

- **Web SDK** para integrar MFA en aplicaciones web propias
- **API REST completa** con documentación extensa
- **Librerías oficiales** en Python, Ruby, Java, PHP, Node.js, C#, Perl, ColdFusion

---

## Planes y licenciamiento detallado

Cisco Duo maneja cuatro tiers principales más opciones de volumen. Los precios *list* actualizados en 2026:

### Duo Free

**Precio**: USD 0
**Límite**: hasta 10 usuarios
**Incluye**:
- MFA con push, passcode, SMS, llamada
- Duo Mobile app completa
- Integración con aplicaciones ilimitadas
- Reportes básicos
- Soporte por email

**Target**: micro-empresas, equipos de desarrollo probando Duo, PoCs antes de contratar tier pago. Es una excelente forma de evaluar Duo sin compromiso.

### Duo Essentials

**Precio**: USD 3 por usuario por mes
**Incluye todo lo del Free más**:
- Sin límite de usuarios
- SSO básico
- Device Trust básico (detección de dispositivos)
- Políticas de MFA por grupo
- Branding custom del portal
- Reportes extendidos
- Soporte 24/7 por email

**Target**: PyMEs que necesitan MFA profesional sin complejidad. Cubre el 80% de los casos de uso empresariales básicos.

### Duo Advantage

**Precio**: USD 6 por usuario por mes
**Incluye todo lo del Essentials más**:
- SSO completo con todas las integraciones
- Device Health avanzado (chequeos detallados del endpoint)
- Adaptive Access Policies contextuales
- Duo Central (portal unificado de aplicaciones)
- Single Sign-On con protección avanzada
- Conditional Access policies
- Reportes avanzados y exportación

**Target**: empresas medianas con requisitos de compliance (SOC 2, ISO 27001, PCI DSS) y necesidades de SSO real. Este es el tier más vendido en empresas latinoamericanas medianas.

### Duo Premier

**Precio**: USD 9 por usuario por mes
**Incluye todo lo del Advantage más**:
- Trust Monitor (ML de detección de anomalías)
- Risk-Based Authentication (autenticación adaptativa con risk scoring)
- Security Context (integración con Cisco Secure Access)
- Verified Duo Push con códigos de confirmación
- Session Trust (evaluación continua durante sesión)
- Priority support

**Target**: empresas enterprise con SOC maduro, requisitos regulatorios estrictos (bancos, salud, gobierno), o que quieren lo último en detección adaptativa.

### Descuentos y contratos

- **Volumen**: descuentos progresivos desde 100 usuarios, significativos a partir de 500 y muy importantes por encima de 5.000
- **Contratos anuales**: típicamente 10-15% menos que mensual
- **Contratos de 3 años**: 20-25% de descuento adicional
- **Educational discount**: 50% para universidades e instituciones educativas
- **Nonprofit discount**: disponible para ONGs calificadas
- **Cisco Enterprise Agreement**: integración con contratos corporativos Cisco, a veces con bundles ventajosos

---

## Compliance y certificaciones

Duo mantiene un stack amplio de certificaciones relevantes para empresas reguladas:

**SOC 2 Type II**: auditada anualmente, disponible bajo NDA para clientes.

**ISO 27001**: certificación vigente, renovada periódicamente.

**FedRAMP Moderate**: autorizada para uso en gobierno federal estadounidense. Duo FedRAMP corre en infraestructura separada con controles adicionales.

**HIPAA**: habilitada para clientes de salud en EE.UU. con BAA (Business Associate Agreement).

**PCI DSS**: compatible con requisitos PCI DSS para entornos que procesan tarjetas.

**GDPR**: compliance europeo con Data Processing Agreement disponible.

**LGPD**: compliance brasileño.

**DoD Impact Level 2**: autorización para cargas no sensibles del Departamento de Defensa estadounidense.

**NIST 800-63B AAL2**: cumple nivel de autenticación 2 del framework NIST, requisito típico para agencias federales.

---

## Casos de uso típicos

### Protección de acceso a aplicaciones SaaS

El caso más común: agregar MFA a Microsoft 365, Google Workspace, Salesforce, y el resto de las SaaS empresariales. Duo se integra vía SAML con cada una y agrega la capa de verificación sin modificar la aplicación.

### MFA en VPN corporativa

Protección de accesos remotos vía Cisco AnyConnect, Palo Alto GlobalProtect, Fortinet, o cualquier VPN compatible con RADIUS. Este fue el caso de uso original de Duo y sigue siendo central.

### MFA en Windows Logon y RDP

Requerir MFA para iniciar sesión en PCs Windows corporativas o para conexiones RDP a servidores. Crítico para entornos donde el endpoint es superficie de ataque principal.

### MFA en SSH

Agregar MFA a conexiones SSH a servidores Linux. Duo Unix es el módulo PAM que integra con OpenSSH.

### Protección de acceso a AWS y cloud

MFA en cuentas root de AWS, usuarios IAM, acceso a consola y APIs. Integración vía SAML con AWS IAM Identity Center.

### Cumplimiento PCI DSS Requirement 8

PCI DSS 4.0 requiere MFA para todos los accesos administrativos a entornos que procesan tarjetas. Duo cumple el requisito.

### Zero Trust foundation

Como capa de verificación de usuario y dispositivo en arquitecturas Zero Trust. Típicamente combinada con Cisco Secure Access o soluciones ZTNA de terceros.

---

## Experiencia de usuario final

Duo tiene reputación sólida en UX por razones concretas:

**Duo Mobile** es una app liviana, sin publicidad, sin features innecesarias. Una pantalla principal con las cuentas y botones grandes *Aprobar / Denegar* cuando llega una solicitud.

**Tiempo de respuesta** al push notification es consistentemente menor a 3 segundos en condiciones normales. La app mantiene conexión persistente con servidores Duo.

**Sin telefonía obligatoria**: el flujo principal no requiere recibir SMS ni llamadas. Los usuarios instalan la app una vez y reciben notificaciones.

**Multi-device**: un usuario puede tener Duo Mobile en múltiples dispositivos (celular + tablet por ejemplo) y recibir notificaciones en cualquiera.

**Self-service device management**: los usuarios pueden registrar y desregistrar dispositivos propios sin requerir admin, dentro de políticas configuradas.

**Branding personalizable**: empresas pueden mostrar su logo y colores en pantallas de Duo, aumentando confianza del usuario final.

---

## Limitaciones reales y consideraciones

Ser honesto sobre debilidades:

**Pricing puede escalar**: empresas con mucho personal contratista, seasonal workers, o usuarios externos (partners, clientes B2B) pagan caro si cada uno cuenta como usuario. Algunos clientes resuelven con tiers separados o consumo concurrente, pero requiere negociación específica.

**Funciones ML solo en Premier**: el Risk-Based Authentication y Trust Monitor están solo en el tier más caro (USD 9/usuario/mes). Clientes Advantage se pierden las features de detección más modernas.

**Configuración default no es la óptima**: Duo out-of-the-box deja SMS activado, no exige FIDO2, y no activa Device Trust. Requiere hardening deliberado para llegar a postura segura.

**Admin Panel puede volverse complejo**: con múltiples aplicaciones integradas, múltiples políticas, y grupos anidados, el admin panel pierde simplicidad. Empresas grandes terminan documentando internamente sus configuraciones.

**Integración con stack Microsoft**: aunque funciona bien, no es tan seamless como Entra ID nativo. Empresas 100% Microsoft a veces descubren que su stack se duplica con Duo encima de Entra ID.

**Dependencia de red**: Duo es cloud-native, requiere conectividad a Internet para autenticar. En escenarios de conectividad intermitente (filiales remotas con enlaces precarios) puede generar fricción. Duo ofrece *Offline MFA* para algunos casos pero no cubre todos.

**No incluye Identity Governance**: Duo es MFA + SSO excelente pero no reemplaza plataformas IGA como SailPoint o Okta Identity Governance. Para access reviews formales y workflows de approval complejos, necesitás otra solución además.

---

## Posicionamiento competitivo en 2026

**Contra Microsoft Entra ID**: Duo gana en empresas heterogéneas (no todo Microsoft), empresas que priorizan simplicidad operativa, y casos donde la integración Cisco-wide aporta valor. Entra ID gana en empresas 100% Microsoft por licenciamiento unificado y cero costo incremental con M365 Business Premium / E3 / E5.

**Contra Okta Workforce Identity**: Duo es más simple de implementar y mantener. Okta cubre más terreno (IGA serio, lifecycle management profundo, workflows enterprise). Empresas medianas que no necesitan IGA completa suelen elegir Duo; empresas grandes con requisitos de governance serio suelen elegir Okta.

**Contra alternativas open source (privacyIDEA, Keycloak, Authelia)**: Duo gana por usabilidad, soporte, y completitud. Las alternativas open source son viables para equipos técnicos con capacidad de self-hosting y tolerancia a menor UX final.

**Contra tokens hardware puros (YubiKey solo)**: son capas complementarias, no competidoras. Muchas empresas combinan Duo + YubiKeys para los usuarios críticos.

---

## Conclusión

Cisco Duo sigue siendo en 2026 la elección por defecto razonable para empresas medianas que necesitan MFA empresarial serio sin complejidad excesiva. Su combinación de **UX excepcional, despliegue rápido, integraciones amplias y pricing predecible** lo mantiene como referencia del rubro pese a la competencia intensa de Microsoft Entra ID (que gana en ecosystem Microsoft puro) y Okta (que gana en enterprise grande multi-cloud).

Para una PyME argentina, mexicana o brasileña con 50-500 empleados, stack típicamente heterogéneo (M365 + Google + Salesforce + AWS + VPN), el tier Advantage a USD 6 por usuario mes ofrece uno de los mejores balances valor/precio del mercado MFA actual.
