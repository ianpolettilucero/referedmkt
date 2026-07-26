---
title: Guía completa de ciberseguridad para PyMEs en LATAM (2026)
subtitle: "Del stack mínimo al presupuesto real: todo lo que tu empresa necesita para no ser la próxima víctima de ransomware"
excerpt: El 43% de los ciberataques apuntan a PyMEs porque los atacantes automatizaron el reconocimiento y cualquier empresa expuesta es un objetivo viable. Esta guía te da el stack mínimo de seguridad que necesitás por capas, el plan realista de implementación en 90 días, cuánto deberías gastar por empleado y qué compliance aplica en tu país. Incluye las 7 amenazas más comunes, benchmarks de presupuesto y los 5 errores que vemos una y otra vez.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-04-19 21:50:00
updated: 2026-07-26
meta_title: "Ciberseguridad para PyMEs en LATAM: Guía completa 2026"
meta_description: "Guía paso a paso para proteger tu PyME: stack mínimo, orden de implementación en 90 días, benchmarks de gasto y compliance en AR/MX/BR/CO/CL."
---

El **43% de los ciberataques apuntan a PyMEs**, no porque seas especial sino porque los atacantes automatizaron el reconocimiento y cualquier empresa con un servicio expuesto es un objetivo viable. En esta guía te doy el stack mínimo que necesitás, el orden de implementación realista en 90 días, cuánto te va a costar y qué compliance aplica en tu país.

---

## Por qué las PyMEs son el objetivo favorito en 2026

Durante muchos años la narrativa fue que los atacantes iban detrás de los *big fish*: bancos, gobiernos, multinacionales. Eso sigue siendo verdad para los grupos APT patrocinados por Estados, pero el **90% del ruido que hoy golpea a una PyME no viene de un humano apuntando a tu empresa**, viene de botnets que escanean internet de punta a punta cada pocas horas. Tu IP pública con RDP expuesto no es "descubierta" — es **inventariada automáticamente** y rematada en un foro de *initial access brokers* por entre 50 y 5.000 dólares, según el tamaño de tu empresa en LinkedIn.

La combinación perfecta para un atacante es una empresa con **facturación suficiente para pagar un rescate**, **compliance poco exigido**, **sin equipo de seguridad dedicado** y **budgets apretados en herramientas**. Esa descripción encaja con casi cualquier PyME de LATAM. No sos un objetivo porque importás — sos un objetivo porque **sos estadísticamente viable**.

- Los bots de reconocimiento escanean todo internet cada **6 horas**. Cualquier IP con RDP (puerto 3389) o SSH (22) expuesto se encuentra en menos de un día.
- Las PyMEs suelen tener **compliance menos exigido** que corporaciones, **security team inexistente** y **budgets apretados**.
- El costo promedio de un incidente de ransomware para una PyME en LATAM: **USD 50.000 a 250.000** entre rescate, downtime, recovery, consultoría forense y pérdida de clientes.
- El tiempo medio entre la intrusión inicial y el cifrado de datos (*dwell time*) bajó de semanas a **menos de 24 horas** en 2025.

> *"El atacante moderno no te elige — te encuentra."*

---

## Las 7 amenazas más comunes en PyMEs LATAM

### 1. Ransomware

Sigue siendo la amenaza #1 por impacto económico. Los vectores de entrada más frecuentes en PyMEs LATAM son:

1. **RDP expuesto a internet** sin MFA, con credenciales débiles o reutilizadas
2. **Phishing** con adjunto malicioso o link a una página de credenciales clonada
3. **VPN sin MFA** con credenciales filtradas en dumps públicos
4. **Vulnerabilidades sin parchar** en firewalls, Exchange o software expuesto

Los rescates típicos para PyMEs arrancan en **USD 25.000** y pueden llegar a **USD 500.000** en el rango medio. Pagar **no garantiza recuperar los datos**: entre el 30% y el 40% de las empresas que pagan no recuperan todo, y muchas son extorsionadas una segunda vez con la amenaza de publicar los datos robados (*double extortion*).

> Regla básica: si tu única defensa contra ransomware es "tengo backup", tu backup **tiene que ser *immutable*** y estar en un sistema que el atacante no pueda borrar con las credenciales que comprometió.

### 2. Phishing y Business Email Compromise (BEC)

El phishing sigue siendo el vector inicial en **más del 80%** de los incidentes corporativos. No porque los empleados sean torpes, sino porque los kits de phishing modernos son indistinguibles del sitio real y usan dominios que incluyen el nombre de tu empresa como subdominio.

Tres tipos principales a conocer:

- **Credential phishing** — te llevan a un login falso de Microsoft 365, Google, el banco o el ERP
- **Invoice fraud** — el atacante intercepta una cadena de correos y envía una factura con datos bancarios modificados
- **CEO fraud** — mail "del gerente" pidiendo urgente una transferencia o una compra de gift cards

El BEC es el ataque **más rentable por hora-atacante**: no requiere malware, no dispara alertas de EDR y el promedio de pérdida por incidente supera los **USD 100.000** según datos del FBI IC3.

### 3. Robo de credenciales

El **reuso de contraseñas** es el regalo que los atacantes nunca dejan de recibir. Cada vez que una web filtra su base de usuarios (y pasa todos los meses), esas credenciales terminan en *dumps* agregados que se cruzan contra logins corporativos con herramientas como *credential stuffing*.

Tres datos rápidos:

- [HaveIBeenPwned](https://haveibeenpwned.com) indexa más de **13.000 millones** de cuentas comprometidas
- El **60%** de los usuarios reutiliza la misma password entre cuentas personales y laborales
- Un dump combinado (*combolist*) con millones de pares usuario/password se consigue por unos pocos dólares en foros

La mitigación no es "pediles que cambien la password cada 90 días" — eso lo recomendaba el NIST hace 15 años y hoy está **desaconsejado**. La mitigación real es **password manager + MFA + monitoreo de leaks**.

### 4. Ataques a la cadena de suministro

Cuando tu proveedor se compromete, vos también. El atacante ya no tiene que romper tu perímetro: **rompe el de un proveedor de confianza y entra por la puerta grande**.

Casos de referencia:

- **SolarWinds (2020)** — el software de monitoreo comprometido llevó malware a 18.000 organizaciones
- **Kaseya (2021)** — un RMM comprometido cifró a cientos de MSPs y sus clientes
- **3CX (2023)** — el cliente de voz corporativo fue troyanizado vía su pipeline de build
- **MOVEit (2023)** — una vulnerabilidad en software de transferencia de archivos expuso datos de **miles de empresas**

Para una PyME, el riesgo concreto no es tanto ser SolarWinds, sino ser **cliente** de un proveedor que lo sea. Mitigación: inventario de proveedores críticos, revisión de su postura de seguridad y segmentación para que un compromiso en un proveedor no barra tu red completa.

### 5. Malware en endpoints

En LATAM los *trojans bancarios* locales son una industria propia. Nombres como *Grandoreiro*, *Mekotio*, *Casbaneiro* y *Amavaldo* están diseñados específicamente para **robar credenciales de home banking y empresas locales**, con ingeniería social adaptada al idioma y al diseño de los bancos argentinos, brasileños y mexicanos.

Otras amenazas comunes:

- **Cryptominers** que consumen CPU/GPU y aumentan la factura eléctrica
- **Infostealers** (*RedLine*, *Vidar*, *Lumma*) que extraen cookies de sesión, passwords guardadas en el navegador y tokens de MFA
- **Loaders** que después descargan el payload final (ransomware, stealer, trojan bancario)

El antivirus tradicional basado en firmas **no detecta** la mayoría de estas familias porque cambian su hash en cada campaña. Para esto se necesita **EDR con detección por comportamiento**.

### 6. Wi-Fi corporativo mal configurado

El Wi-Fi es una de las capas más descuidadas en PyMEs. Errores típicos:

- **WPA2 con password compartida** entre todos los empleados (y ex empleados)
- **Red de invitados NO aislada** de la red corporativa
- **Impresoras y cámaras IP** accesibles desde la red de empleados con credenciales por defecto
- **Rogue access points** personales que empleados conectan al switch corporativo

Solución mínima: **WPA2/WPA3 Enterprise con autenticación por usuario (802.1X)**, red de invitados con aislamiento L2, y segmentación por VLAN entre empleados, servidores, invitados e IoT.

### 7. Insiders no maliciosos

No todos los incidentes vienen de fuera. La mayoría de los "insiders" no son empleados resentidos, son **empleados normales que hicieron algo que no deberían haber hecho**:

- Clickearon un link sin pensar
- Conectaron un USB que encontraron "a ver qué tenía"
- Descargaron software crackeado para ahorrar una licencia
- Usaron un servicio cloud personal (*shadow IT*) para subir un archivo corporativo
- Le pasaron la password a un compañero "para ayudarlo"

La respuesta no es desconfiar de todos — es **asumir que esto va a pasar** y diseñar controles (MFA, EDR, least privilege, awareness training) que acoten el daño cuando pase.

---

## El stack mínimo que tu PyME necesita

Pensar la seguridad como **capas**. Cada capa independiente, ninguna depende de otra. Si una falla, las otras siguen protegiendo.

### Capa 1 — Identidad y acceso

Esta es la primera línea de defensa. Si un atacante no puede autenticarse como un empleado, el **80% de los ataques se cortan acá**.

- **MFA obligatorio** en email, admin panels, VPN, acceso al password manager. **Sin excepciones** — y revisá el inventario una vez activado, porque [los accesos que el MFA deja afuera](/guia/ya-tenes-mfa-y-no-alcanza) son los que terminan usando los atacantes. Si alguien dice "es incómodo", la respuesta es: un rescate de ransomware es más incómodo.
- **Password manager empresarial** con SSO, bóvedas compartidas y auditoría. Esto reemplaza al Excel con contraseñas y al "se las mando por WhatsApp".
- **SSO (Single Sign-On)** si ya tenés más de 20 empleados y usás Google Workspace, Microsoft 365 u Okta.

Nuestra recomendación por defecto para PyMEs es **1Password Business** o **Bitwarden Business** — el primero tiene mejor UX, el segundo es más económico y open source. Para MFA, priorizá **apps de autenticación (Authy, Microsoft Authenticator, 1Password)** o **llaves físicas FIDO2 (YubiKey)** por sobre SMS, que es vulnerable a *SIM swapping*. Si vas por las llaves, en el [análisis de costo total de las YubiKey 5 en Argentina](/resena/analisis-yubikey-5-series-costo-total-argentina) está el costo puesto acá modelado con precios de lista y el régimen de importación vigente, que no es el de la etiqueta.

### Capa 2 — Endpoints (los dispositivos)

Los endpoints son laptops, desktops y móviles con acceso corporativo.

- **EDR moderno**. *No es lo mismo que antivirus tradicional*. El EDR detecta **comportamiento anómalo** (un proceso cifrando archivos en masa = ransomware) en vez de solo firmas conocidas.
- **MDM (Mobile Device Management)** si hay BYOD. Si un empleado usa su teléfono personal para leer email corporativo, necesitás poder hacer *wipe* remoto si se pierde.

**Diferencia entre AV y EDR en una frase:** el antivirus te dice *"encontré un archivo malo, lo borré"* y cierra el tema; el EDR te dice *"un proceso de Word lanzó PowerShell que descargó un ejecutable desde un dominio raro y empezó a tocar archivos — ¿qué hacemos?"* y te deja investigar. En 2026, AV sin EDR es **insuficiente** para cualquier empresa con más de 10 empleados.

Opciones empresariales sólidas: [CrowdStrike Falcon](/producto/crowdstrike-falcon), [Microsoft Defender for Endpoint P2](/producto/microsoft-defender-for-endpoint-p2), [SentinelOne Singularity](/producto/sentinelone-singularity). Ver el directorio completo en [Antivirus y EDR](/productos/antivirus-y-edr).

### Capa 3 — Red

- **Firewall moderno (NGFW)** o **SASE cloud**. Ya no alcanza con un firewall que solo filtre puertos — se necesita inspección de aplicaciones, IDS/IPS y filtrado DNS.
- **VPN empresarial o ZTNA** para el acceso remoto. No uses VPN *consumer* (tipo NordVPN personal) en contexto corporativo — **son productos distintos**. Si estás por renovar la VPN vieja, mirá antes cómo dar [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn), que para menos de 50 usuarios suele salir cero.
- **Segmentación**: red de invitados ≠ red de empleados ≠ red de servidores.

### Capa 4 — Email

- **Filtro anti-phishing** más allá del built-in de Gmail/Microsoft 365. Productos dedicados como *Proofpoint*, *Mimecast* o *Abnormal* detectan *campaigns* coordinadas que el filtro base no ve; la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes) los pone lado a lado con precio por buzón y mínimo de licencias.
- **DMARC + SPF + DKIM** para que tu dominio no pueda ser *spoofeado*. Los 3 son records DNS. Toma **1 hora** configurarlos bien y previene el 80% de los ataques de suplantación: el paso a paso, con los comandos para verificar cada registro, está en [configurar SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso).

### Capa 5 — Backup y recuperación

Y no vale asumir que lo que está en la nube ya viene respaldado: si tu empresa trabaja sobre Microsoft 365, mirá primero por qué [el backup de Microsoft 365 hace falta](/guia/backup-microsoft-365-hace-falta) y cuántos días de retención real tenés antes de dar esta capa por cubierta.

La regla **3-2-1**:

- **3** copias de los datos críticos
- en **2** medios distintos (local + cloud, por ejemplo)
- con **1** copia *off-site* e ***immutable*** (no modificable ni siquiera con credenciales admin)

Un caso hipotético pero representativo: un estudio contable de 30 personas sufre ransomware un viernes a la noche. El atacante llevaba **3 semanas** adentro, inventarió la red, deshabilitó el antivirus y **eliminó los backups locales y el backup en la NAS** antes de disparar el cifrado el viernes porque sabe que nadie va a revisar hasta el lunes. El único backup que sobrevive es la copia *immutable* en la nube de la semana anterior. Pierden 7 días de trabajo — molesto, pero recuperable. Sin esa copia *immutable*, la historia termina en pago de rescate o cierre de la empresa.

> **Un backup que nunca probaste no es backup.** Prueba de *restore* mínimo **1 vez por trimestre**.

---

## Cuánto deberías gastar en ciberseguridad

Benchmarks que vemos en PyMEs LATAM 2026:

| Tamaño | Empleados | USD/empleado/mes | Total mensual | Stack típico |
|---|---|---|---|---|
| Micro | 1-5 | 30-50 | 150-250 | Password manager + MFA + EDR |
| Pequeña | 6-25 | 20-40 | 120-1.000 | + VPN + email security + backup |
| Mediana | 26-100 | 15-30 | 400-3.000 | + SIEM básico + awareness training |
| Mediana+ | 100+ | 15-25 | 1.500+ | + SOC outsourced o SecOps propio |

Regla general: **1-2% del *revenue*** debería ir a ciberseguridad. Si gastás menos, estás subsidiando a los atacantes.

---

## Plan de implementación en 90 días

Si tu PyME no tiene nada o casi nada, este es el orden ideal.

### Días 1-30 — Identidad

1. Activar MFA en email admin (**día 1**)
2. MFA obligatorio para todos los empleados (**primeros 7 días**)
3. Elegir y desplegar password manager con *onboarding* del equipo (**3 semanas**)

### Días 31-60 — Endpoints

1. EDR en todos los endpoints con *deploy* automatizado (**1 semana**)
2. MDM si aplica (**2 semanas**)
3. *Audit* de equipos sin *updates* de OS (**1 semana**)

### Días 61-90 — Red, backup y awareness

1. Firewall / VPN empresarial / ZTNA según tamaño
2. Backup con **prueba de *restore* real**
3. Primera campaña de *phishing simulation* con el equipo

---

## Compliance mínimo aplicable en LATAM

| País | Ley principal | Multas |
|---|---|---|
| 🇦🇷 Argentina | Ley 25.326 + AAIP | Hasta ARS 100M |
| 🇲🇽 México | LFPDPPP | Hasta 320k UMA |
| 🇧🇷 Brasil | LGPD | Hasta 2% *revenue* o R$50M |
| 🇨🇴 Colombia | Ley 1581 + Decreto 1377 | Hasta 2.000 SMLMV |
| 🇨🇱 Chile | Ley 19.628 (reforma 2024) | Hasta 20k UTM |
| 🇵🇪 Perú | Ley 29733 | Hasta 100 UIT |

Si procesás pagos con tarjeta: **PCI DSS** es transversal a todos los países.

**Por qué importa aunque seas chico**: las multas escalan con el *revenue*, no con el *headcount*. Una PyME exitosa puede pagar una multa proporcionalmente **más grande** que una corporación.

---

## Los 5 errores que vemos una y otra vez

1. **Confiar solo en Windows Defender** para equipos de más de 10 empleados. Es aceptable para una PC de uso hogareño; no alcanza para proteger infraestructura corporativa.
2. **MFA opcional**. Si no es *mandatory*, no existe. Los empleados van a saltar el paso extra siempre que puedan.
3. **Backup sin pruebas de *restore***. Descubrís que tu backup estaba corrupto el día que lo necesitás. Tarde.
4. **"Somos chicos, no nos atacan"**. Los atacantes son *bots*. No te están mirando a vos — te están buscando junto con otros 10 millones de *targets*.
5. **Comprar la herramienta y no *onboardear* al equipo**. Una licencia de *password manager* sin capacitación acumula polvo en un Excel compartido.

---

## Preguntas frecuentes

### ¿Es suficiente Windows Defender para mi empresa?

Para **1-5 empleados** con cuidado básico, es aceptable como *baseline*. Para más de 10 empleados o cualquier equipo que maneje datos sensibles (facturación, clientes, propiedad intelectual), **no**. Necesitás EDR moderno con visibilidad centralizada.

### ¿Cuánto cuesta la ciberseguridad para una PyME chica?

Entre **USD 30-50 por empleado por mes** para un *stack* mínimo decente. Una empresa de 10 personas gasta aprox **USD 300-500/mes**. Menos que un almuerzo semanal con clientes.

### ¿Necesito contratar un analista de seguridad full-time?

Hasta **100 empleados**, generalmente no. Alcanza con buenas herramientas + el admin de IT existente + un servicio de respuesta a incidentes contratado (**MSSP**) para cuando algo salga mal. A partir de 100-200 empleados sí empieza a tener sentido un rol dedicado.

### ¿Qué hago si ya sufrí un ataque?

1. **No pagues el rescate** (al menos no sin asesoría legal)
2. **Desconectá** los equipos afectados de la red
3. **Contactá** a un *incident response firm*
4. **Comunicá** el incidente a tu proveedor de *ciber-seguro* si tenés
5. **Reportá** a la autoridad de protección de datos correspondiente (es obligatorio en muchos países si hay datos personales afectados)

### ¿Los antivirus gratis sirven para empresas?

**No.** Los *vendors* de AV/EDR gratis **prohíben explícitamente el uso comercial en el TOS**. Avast Free, AVG Free, Bitdefender Free Edition son solo para uso personal. Usarlos en empresa es **violación de licencia** y típicamente no tienen consola centralizada ni *reporting*.

---

## Próximos pasos

Si te sirvió esta guía, profundizá en cada capa con artículos específicos:

- [Reseña de 1Password Business: 12 meses usándolo](/resena/resena-1password-business-12-meses)
- [Comparativa: Bitdefender vs Kaspersky vs ESET para empresas](/comparativa/bitdefender-vs-kaspersky-vs-eset)
- [Cómo configurar MFA en toda tu PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana)

O explorá los [productos del directorio](/productos) por categoría.

---

*¿Encontraste algo desactualizado? ¿Tenés un caso para sumar? Escribinos a [contacto@capacero.online](mailto:contacto@capacero.online).*
