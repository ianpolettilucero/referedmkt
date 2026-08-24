---
title: "Comparativa: seguridad de email para PyMEs"
subtitle: Precio por buzón con enlace a la lista pública, cómo se compra realmente cada uno, dónde se para en el flujo de correo y qué plazo de implementación documenta el fabricante.
excerpt: Precio por buzón de los cinco con link a cada lista pública. Dos publican tarifa, dos cotizan a pedido y uno cobra un fee fijo que rompe la cuenta.
type: comparison
status: published
category: email-y-antiphishing
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-24
products:
  - microsoft-defender-for-office-365
  - abnormal-security
  - check-point-harmony-email-collaboration
  - mimecast-email-security
  - proofpoint-email-protection
meta_title: "Seguridad de email PyMEs: precios publicados 2026"
meta_description: "Comparativa con precio por buzón verificable, forma de compra, posición MX o API y plazos de implementación de MDO, Abnormal, Harmony, Mimecast y Proofpoint."
verdict: |
  Para una PyME de 25 a 60 buzones que ya paga Microsoft 365 Business Premium, la
  compra correcta es casi siempre no comprar nada extra: Defender for Office 365
  Plan 1 ya está adentro y a medio encender. Si hace falta una segunda capa con
  precio verificable, Check Point Harmony es el único de los caros con tarifa
  impresa por usuario. Mimecast entra cuando hay obligación de archivado legal con
  retención larga y presupuesto para pagar una implementación aparte. Abnormal, con
  su cargo de plataforma fijo, no es un producto para PyME, aunque sea el que mejor
  apunta al fraude por correo sin adjunto ni enlace. Para todos los
  demás: gastá esa plata en backup y en llaves físicas.
pros:
  - Microsoft y los partners de Proofpoint Essentials publican tarifa por usuario, y Check Point publica la suya en AWS Marketplace
  - Los despliegues por API (Abnormal, Harmony) se autorizan por OAuth y no tocan el registro MX
  - Mimecast publica plazos objetivo de implementación, que es más de lo que hace el resto
  - Para 25 buzones existe una salida de USD 0 extra, y no es la que aparece primero en ninguna cotización
cons:
  - Mimecast y Proofpoint Enterprise no publican precio, así que toda cifra suya es una banda de agregador y no una tarifa
  - El cargo de plataforma de Abnormal que reportan los agregadores (USD 5.000 a 15.000 al año) se paga igual con 25 buzones que con 2.500
  - Los SEG con registro MX por delante convierten al proveedor en punto único de falla del correo entrante
  - Salvo Mimecast, ningún fabricante publica cuánto cuesta la implementación ni cuánto tarda
---

Proteger el correo de 25 personas cuesta entre USD 0 y USD 5.875 al año, y los dos extremos salen de precios verificables en una tarde.

Cero si ya se paga Microsoft 365 Business Premium y se termina de encender lo que viene adentro. USD 5.875 si se compra [Abnormal Security](/producto/abnormal-security): 25 buzones a USD 35 al año son USD 875, y los otros USD 5.000 son un cargo de plataforma fijo que se paga igual con 25 buzones que con 2.500. Esa cuenta resume toda la comparativa: por debajo de 100 buzones el precio por asiento casi no importa, importa el piso de entrada.

**Sesgo declarado, acá arriba y no en un pie de página**: Capa Cero se financia con enlaces de afiliado a algunos de los productos que cubre. Ninguno de estos cinco deja comisión hoy, y la recomendación principal —que buena parte de las PyMEs con Business Premium no compre nada— no la dejaría en ningún caso.

**De dónde salen los números**: cada precio tiene detrás una tarifa pública —de fabricante, de partner o de marketplace— o es una banda de un agregador de contratos reales, y en ese caso la celda lo aclara. Los cinco productos no fueron operados para esta nota: la comparación se apoya en documentación, listas públicas y agregadores, con enlace y fecha de consulta del **26 de julio de 2026**. Mezclar una lista con una mediana negociada sin avisar es lo que arruina las comparativas en español de este rubro.

## La tabla

| Producto | Precio por buzón/mes | Tipo de precio | Piso real de compra | Flujo y puesta en marcha |
|---|---|---|---|---|
| [Microsoft Defender for Office 365](/producto/microsoft-defender-for-office-365) | USD 2 (Plan 1) / USD 5 (Plan 2), anual. USD 0 marginal con Business Premium | **Tarifa del fabricante** ([fuente](https://www.microsoft.com/en-us/security/business/siem-and-xdr/microsoft-defender-office-365)) | 1 licencia, checkout web o partner CSP | Nativo, no toca el MX. Built-in protection ya viene aplicada; Standard y Strict hay que asignarlas |
| [Abnormal Security](/producto/abnormal-security) | USD 20-35 por buzón/**año** más cargo de plataforma de USD 5.000-15.000 anuales | Banda de agregador ([Vendr](https://www.vendr.com/marketplace/abnormal-security), [UnderDefense](https://underdefense.com/blog/abnormal-security-pricing-guide/)) | Quote-only. Mínimos de contrato de USD 25.000-50.000 | API post-entrega: el correo ya está en el inbox. Autorización OAuth, minutos según el fabricante |
| [Check Point Harmony Email & Collaboration](/producto/check-point-harmony-email-collaboration) | USD 6,96 (Advanced) / USD 9,10 (Complete), o USD 83,46 y USD 109,14 por usuario/12 meses | **Tarifa impresa** ([AWS Marketplace](https://aws.amazon.com/marketplace/pp/prodview-5v23pwcdewapi)) | Dimensiones por usuario, sin mínimo declarado | API post-entrega. Modo automático: se autoriza la app y el resto se configura solo |
| [Mimecast Email Security](/producto/mimecast-email-security) | USD 3,33-6,67 base; USD 8,33-12,50+ completo (bandas anuales de USD 40-80 y USD 100-150+) | Banda de agregador ([Vendr, 110 compras](https://www.vendr.com/marketplace/mimecast)) | Partner o MSSP. Contrato mediano de USD 30.242/año | SEG clásico, con el MX por delante. Implementación aparte: [Guided 30 días, Managed 45](https://mimecastsupport.zendesk.com/hc/en-us/articles/34000453855763-Mimecast-Customer-Care-Managed-Guided-Onboarding-Steps) |
| [Proofpoint Email Protection](/producto/proofpoint-email-protection) | Essentials USD 3,93 a USD 9,42. Enterprise sin precio público | **Lista de Essentials publicada por un partner** ([Iron Cove](https://ironcovesolutions.com/en-US/pricing/proofpoint-essentials)) | Essentials desde 1 asiento y mes a mes. Enterprise quote-only | MX o API a elección. Por Graph API el fabricante habla de días; por SEG, cutover de MX |

## Microsoft: el USD 0 es real, el "ya estás protegido" no tanto

El costo marginal de [Defender for Office 365](/producto/microsoft-defender-for-office-365) Plan 1 es cero para quien ya paga Business Premium. El absoluto no: Business Premium sale USD 22 por usuario por mes con compromiso anual —USD 26,40 mes a mes—, o sea USD 6.600 al año para 25 buzones, con tope de 300 asientos.

Lo que importa está en la [documentación de Microsoft](https://learn.microsoft.com/en-us/defender-office-365/preset-security-policies). Las políticas preestablecidas son tres: **Built-in protection** viene aplicada a todos los destinatarios por defecto, mientras que **Standard** y **Strict** quedan asignadas a nadie hasta que un administrador las enciende. El escenario habitual no es que esté apagado, es peor de detectar: está encendido en su versión mínima. Las diferencias están tabuladas en esa misma página:

- `AllowClickThrough` en `true`: el usuario puede saltearse la advertencia y entrar igual al enlace. En Standard y Strict, `false`.
- `DisableURLRewrite` en `true`: no se reescriben las URLs, solo se consultan por API.
- `EnableForInternalSenders` en `false`: Safe Links no se aplica al correo interno.
- Ante suplantación por mailbox intelligence, Standard mueve a Correo no deseado y Strict pone en cuarentena; el umbral de phishing pasa de 3 a 4.

Antes de cotizar nada, la pregunta es si Standard tiene usuarios asignados en el tenant. Se responde en dos minutos y se corrige en una tarde ya pagada.

Los USD 2 y USD 5 standalone son la única tarifa de fabricante de la tabla, y por eso son el ancla contra la que negociar el resto. Si un partner cotiza un producto de terceros a USD 8 por buzón, la pregunta no es si es caro: es qué agrega por encima de los USD 5 del Plan 2.

## El mejor de la tabla es el que no te van a vender

Con 25 personas, es probable que [Abnormal](/producto/abnormal-security) ni cotice.

El precio por asiento no es el problema. UnderDefense ubica el módulo base de Inbound Email Security en USD 20 a 35 por buzón **al año** y Vendr da una banda parecida: entre USD 1,25 y USD 2,92 por buzón por mes, más barato que el Plan 2. El problema es el cargo de plataforma, plano, de USD 5.000 a 15.000 anuales, más los mínimos de contrato de USD 25.000 a 50.000 que Vendr reporta para despliegues chicos.

La cuenta a 25 buzones con el piso más benévolo da USD 19,58 por buzón por mes: 85% de estructura y 15% de producto. Y es la versión optimista, porque asume que dejan firmar por debajo del mínimo, cosa que la mediana de Vendr —USD 43.848 sobre 62 compras— sugiere que no pasa.

Queda una hipótesis técnica sin medición que la respalde: el producto se apoya en un modelo de comportamiento construido sobre el historial de quién le escribe a quién dentro de la organización, y con 25 buzones ese historial es chico. Si eso diluye la ventaja frente a un filtro tradicional no se puede afirmar acá, y es la pregunta que corresponde hacerle al preventa antes de firmar.

La contradicción que esta comparativa no resuelve: es el que mejor apunta a lo que más importa —el fraude por correo sin adjunto ni enlace— y es el que menos PyMEs pueden comprar. Antes de cualquier prueba conviene verificar dos cosas que no figuran en ninguna fuente pública: en qué idioma están la consola, la documentación y el soporte, y en qué huso horario atienden. En una PyME de la región con un IT de dos personas eso pesa, y se responde preguntando.

## Check Point Harmony: el único de los caros con la tarifa impresa

[Harmony Email & Collaboration](/producto/check-point-harmony-email-collaboration) tiene precio público en su listado de AWS Marketplace, con dimensiones por usuario y por 12 meses.

- **Advanced**: USD 83,46 por usuario / 12 meses. Son USD 6,96 por buzón por mes.
- **Complete**: USD 109,14, o USD 9,10 al mes. Agrega sandbox extendido, DLP y cifrado.
- Add-ons con precio propio: archivado hasta 10 años, USD 51,36 por usuario/año; respuesta a incidentes gestionada, USD 15; gestión de DMARC, USD 10,70.

Como la dimensión es por usuario, la cuenta a 25 buzones es directa: 25 × 83,46 son USD 2.086,50 al año por Advanced. Si Check Point aplica algún mínimo comercial, no lo declara en esa página.

Ninguna de esas cifras dice que sea barato: USD 6,96 está arriba del Plan 2, así que la comparación correcta es contra USD 5 y no contra cero. Lo que lo vuelve interesante es el archivado a USD 51,36, único precio de archivado publicado en toda la comparativa.

## Dónde se para cada uno en el flujo del correo

Esta es la dimensión que ninguna comparativa en español mide y que define si el proyecto dura una tarde o un mes.

Harmony y Abnormal se conectan por API. Check Point documenta dos modos: en el automático se autoriza la aplicación durante el asistente y el producto aplica solo el resto; en el manual, las configuraciones se hacen en el Centro de administración de Exchange antes de vincular la app.

Mimecast en modo clásico pide apuntar el MX hacia ellos, y eso arrastra propagación de DNS, convivencia con el flujo viejo, reglas de conector, listas de IP permitidas y pruebas de entrega antes del cutover. Su documentación lo ordena en fases con plazos objetivo: 30 días en Guided, 45 en Managed. Proofpoint deja elegir: por Graph API habla de días, por SEG la mecánica es la misma.

Lo que decide no es el plazo sino la topología, y se ve el día malo.

```svg
<svg viewBox="0 0 680 260" role="img" aria-label="Comparación de topologías: con el producto conectado por API el correo llega igual si el producto se cae, porque se pierde detección y no servicio; con el producto delante del registro MX, si se cae, el correo entrante se encola o rebota">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Dónde se para el producto, y qué pasa el día que se cae</text>

  <text x="20" y="50" font-size="11" font-weight="700" fill="currentColor" opacity="0.75">Por API — Abnormal, Harmony</text>
  <rect x="20" y="58" width="88" height="32" rx="5" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="58" width="88" height="32" rx="5" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="64" y="78" text-anchor="middle" font-size="10.5" fill="currentColor">Internet</text>
  <path d="M110 74 L130 74" stroke="currentColor" stroke-width="1.3" opacity="0.6"/>
  <rect x="132" y="58" width="110" height="32" rx="5" fill="currentColor" opacity="0.07"/>
  <rect x="132" y="58" width="110" height="32" rx="5" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="187" y="78" text-anchor="middle" font-size="10.5" fill="currentColor">Microsoft 365</text>
  <path d="M244 74 L264 74" stroke="currentColor" stroke-width="1.3" opacity="0.6"/>
  <rect x="266" y="58" width="88" height="32" rx="5" fill="currentColor" opacity="0.07"/>
  <rect x="266" y="58" width="88" height="32" rx="5" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="310" y="78" text-anchor="middle" font-size="10.5" fill="currentColor">Buzón</text>
  <path d="M187 92 L187 104" stroke="#e23a3a" stroke-width="1.2" stroke-dasharray="3 3" opacity="0.7"/>
  <rect x="132" y="104" width="110" height="26" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.3" stroke-dasharray="4 3"/>
  <text x="187" y="121" text-anchor="middle" font-size="10.5" fill="#e23a3a">producto caído</text>
  <text x="380" y="80" font-size="11" fill="currentColor" opacity="0.85">El correo sigue llegando:</text>
  <text x="380" y="98" font-size="11" font-weight="600" fill="currentColor">se pierde detección, no servicio</text>

  <text x="20" y="172" font-size="11" font-weight="700" fill="currentColor" opacity="0.75">Por MX — Mimecast, Proofpoint en modo SEG</text>
  <rect x="20" y="180" width="88" height="32" rx="5" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="180" width="88" height="32" rx="5" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="64" y="200" text-anchor="middle" font-size="10.5" fill="currentColor">Internet</text>
  <path d="M110 196 L130 196" stroke="currentColor" stroke-width="1.3" opacity="0.6"/>
  <rect x="132" y="180" width="110" height="32" rx="5" fill="#e23a3a" opacity="0.14"/>
  <rect x="132" y="180" width="110" height="32" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="187" y="200" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">producto caído</text>
  <path d="M248 190 L262 202 M262 190 L248 202" stroke="#e23a3a" stroke-width="1.8"/>
  <rect x="272" y="180" width="110" height="32" rx="5" fill="currentColor" opacity="0.07"/>
  <rect x="272" y="180" width="110" height="32" rx="5" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.4"/>
  <text x="327" y="200" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.55">Microsoft 365</text>
  <text x="404" y="194" font-size="11" fill="currentColor" opacity="0.85">El correo entrante</text>
  <text x="404" y="212" font-size="11" font-weight="600" fill="currentColor">se encola o rebota</text>

  <text x="20" y="248" font-size="11" fill="currentColor" opacity="0.8">El modelo por API nunca abre ese agujero. El SEG lo abre y lo tapa vendiendo continuidad aparte.</text>
</svg>
```

Mimecast ofrece continuidad de servicio justamente para ese escenario, como parte de su catálogo. Si la decisión es ir por SEG, conviene preguntar por escrito si esa continuidad viene incluida en el SKU cotizado o se factura aparte.

Un asterisco sobre los "minutos" de despliegue por API: ese número es del fabricante y describe el caso simple —un tenant, un dominio, modo automático—. El modo manual existe porque hay casos donde el automático no alcanza. Sobre despliegues de más de unos cientos de buzones con coexistencia híbrida no hay datos públicos confiables.

## Mimecast: qué cuesta que un fabricante no publique lista

[Mimecast](/producto/mimecast-email-security) ilustra lo que cuesta la opacidad de precio. No hay tarifa pública: hay bandas construidas sobre contratos reales. Vendr, sobre 110 compras, ubica los bundles base entre USD 40 y USD 80 por usuario al año en organizaciones de 100 a 500 usuarios, y los paquetes completos en USD 100 a 150+. La mediana de contrato es de USD 30.242 anuales y el ahorro promedio negociado, 12%.

La banda base sola es un factor de 2 entre extremos sobre el mismo producto, y de qué lado se cae depende de cómo negocie el partner y no de qué se necesite; contra el paquete completo el factor se va casi a 4. Eso es lo que cuesta que no haya tarifa impresa: no hay contra qué medir la cotización que llega.

Dos detalles cambian la cuenta a 25 buzones. El primero es de lectura de la fuente: la banda de Vendr está construida sobre organizaciones de 100 a 500 usuarios, así que con 25 buzones ni siquiera se está dentro de la muestra que la generó, y esperar la parte baja del rango es voluntarismo. El segundo es que la implementación se compra aparte: Guided y Managed son la razón por la que Mimecast es el único que publica un plazo objetivo, y no el precio.

## Hay dos Proofpoint y te van a cotizar el otro

[Proofpoint](/producto/proofpoint-email-protection) vende dos productos distintos bajo el mismo logo, y esa confusión es la trampa más fácil de comer del rubro.

Essentials tiene lista publicada, aunque no por el fabricante sino por partners. La de Iron Cove Solutions, vigente en 2026: Business USD 3,93, Business+ USD 4,86, Advanced USD 5,37, Advanced+ USD 5,87, Professional USD 8,14 y Professional+ USD 9,42 por usuario/mes. Advanced suma URL Defense y sandbox de adjuntos; Professional agrega cifrado, DLP y archivado.

Esa lista no exige contrato anual ni mínimo de asientos, y admite facturación mes a mes. Para quien necesita una segunda capa por tres meses mientras migra, es el único de la tabla que lo permite.

Enterprise es otra cosa: no publica precio, pide proceso de venta con partner y los módulos se suman al bundle —la protección contra fraude de identidad es un SKU aparte, el entrenamiento simulado también—. El problema es de expectativas: el material de marketing, con sus referencias de clientes corporativos grandes, describe Enterprise. Lo que una empresa de 40 empleados puede comprar sin entrar en un proceso de venta es Essentials, que trae bastante menos. Conviene pedir el SKU exacto por escrito antes de comparar contra nada.

## Tres escenarios de compra para 25 buzones

### Presupuesto: USD 0 extra al año

No comprar nada. Ya se pagaron USD 6.600 al año por Business Premium y el filtro viene adentro, a medio encender. La primera "compra" es una tarde: asignar Standard a todos los usuarios en el portal de Defender y dejar [SPF, DKIM y DMARC bien configurados](/guia/configurar-spf-dkim-dmarc-paso-a-paso). Eso último no lo reemplaza ningún producto de la tabla: los cinco filtran el correo que entra, y DMARC gobierna el que sale con tu nombre hacia tus clientes. Problemas opuestos, y solo uno se arregla con plata.

### Presupuesto: hasta USD 1.500 al año

USD 1.500 dividido 25 buzones dividido 12 meses son USD 5 por buzón por mes exactos. Con precios publicados entran dos cosas: el Plan 2 de Microsoft (25 × 5 × 12 = USD 1.500) y Proofpoint Essentials hasta Business+, a USD 4,86 (USD 1.458). Harmony Advanced queda afuera por poco: USD 2.086,50.

**Decisión: Defender for Office 365 Plan 2 standalone, USD 5 por usuario/mes.** No porque sea mejor motor que Essentials: es que con Business Premium ya pago, comprar Essentials es poner un SEG adelante de un filtro que ya se paga, con dos consolas y dos lugares donde buscar un falso positivo. El Plan 2 agrega investigación —explorador de amenazas, respuesta automatizada, vistas de campaña— y el simulador de ataques, que es lo que justifica el salto: el elemento humano siguió presente en el 62% de las brechas del DBIR 2026. Desde Business Standard, en cambio, Essentials Business a USD 3,93 es una conversación legítima contra el Plan 1.

### Presupuesto: hasta USD 6.000 al año

USD 6.000 son USD 20 por buzón por mes: entra cualquier cosa que se deje comprar a 25 asientos. Hay dos caminos y uno depende de un requisito legal.

**Decisión: con obligación de archivado y retención larga, la única opción con precio verificable es Harmony Advanced más el add-on de archivado: 25 × (83,46 + 51,36) son USD 3.370,50 al año, y sobran USD 2.629. Sin esa obligación, no comprar correo: reforzar identidad y backup.**

Mimecast sigue siendo la referencia cuando el requisito es e-discovery formal, cadena de custodia y búsquedas para un abogado, y su paquete completo —USD 2.500 a 3.750 a 25 buzones— también entra en este presupuesto. La diferencia es que uno publica el precio antes de que empiece la conversación y el otro no, y además factura la implementación aparte. Si la obligación es guardar diez años y poder buscar, conviene el que publica; si viene con un requerimiento judicial adelante, se paga el proceso de venta.

Sobre el segundo camino: Sophos midió en 2026 que el 79% de los ataques de ransomware arranca por vía de identidad —primera vez en cuatro años por encima de la explotación de vulnerabilidades— y que el 97% de las víctimas por credenciales comprometidas ya tenía MFA puesto, y faltaba justo donde importaba en el 59% de los casos. Ese inventario está en [por qué tener MFA no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), y el orden de tareas en [cómo configurar MFA en una PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana). Con USD 2.600 se compran [llaves físicas resistentes a phishing](/resena/analisis-yubikey-5-series-costo-total-argentina) para todo el equipo y una licencia de [backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta): mueve más la aguja que subir de tier en el filtro.

## Cuándo no comprar nada de esto

- **Hay Business Premium y nunca se asignó Standard.** Sería comprar una segunda capa arriba de una primera que corre en su configuración mínima documentada.
- **Menos de 25 buzones.** Dos de los cinco no publican precio ni van a abrir un proceso de venta, y los que publican cobran lista.
- **No hay DMARC en `reject`.** El fraude que más caro sale es el que usa tu dominio contra tus clientes, y ninguno de estos productos lo frena desde afuera.
- **No hay backup de los buzones.** Microsoft 365 tiene retención, no backup: si mañana borran una casilla y pasan 30 días, no se recupera con un filtro anti-phishing.

Para calibrar la urgencia: las denuncias de phishing y suplantación ante el IC3 del FBI vienen bajando —298.878 en 2023, 193.407 en 2024 y 191.561 en 2025— mientras las pérdidas por fraude al CEO subieron a USD 3.046.598.558 sobre 24.768 denuncias en 2025, contra USD 2.770 millones y 21.442 denuncias el año anterior. Menos ataques masivos, más dirigidos y rentables. Lo que rinde no es el filtro de volumen —eso ya lo hace Exchange Online Protection gratis— sino la detección de mensajes sin adjunto ni enlace: justo donde el que mejor puntúa es el que menos PyMEs pueden comprar.

Un dato final que va contra lo anterior. En LATAM el phishing tocó al 73% de las organizaciones que relevó el ESET Security Report 2026 sobre 962 empresas de diez países. Pero el vector de entrada número uno del DBIR 2026 ya no es la gente: es la explotación de vulnerabilidades, que trepó al 31% de los accesos iniciales desde el 20% y por primera vez en diecinueve ediciones le pasó al robo de credenciales. Con un solo proyecto por trimestre, parchear le gana a comprar filtro. El desarrollo está en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y el mismo criterio de precio verificable antes que marketing, en la [comparativa de Bitdefender, Kaspersky y ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset).

El resto de los productos del rubro están en la [categoría de seguridad de email y anti-phishing](/productos/email-y-antiphishing).

## Preguntas frecuentes

### ¿Hace falta comprar seguridad de email si ya tengo Microsoft 365?

Depende del plan. Con Business Basic o Standard hay Exchange Online Protection: anti-spam y anti-malware por firmas, sin Safe Links ni Safe Attachments ni anti-phishing avanzado. Ahí sí falta algo, y lo más barato es el Plan 1 standalone a USD 2. Con Business Premium o E5 ya viene incluido el Plan 1 o el Plan 2, y lo primero es verificar si Standard tiene usuarios asignados. Si no, corre Built-in protection, que según Microsoft deja pasar el click-through y no aplica Safe Links al correo interno.

### ¿Cuál es la diferencia real entre Defender for Office 365 Plan 1 y Plan 2?

Plan 1 (USD 2 por usuario/mes standalone) es prevención: Safe Links, Safe Attachments y anti-phishing con protección de suplantación. Plan 2 (USD 5) agrega investigación y respuesta: explorador de amenazas, respuesta automatizada, vistas de campaña y el simulador de ataques. Sin nadie que vaya a mirar el explorador, el salto de USD 3 se justifica casi solo por el simulador.

### ¿Cuánto cuesta Check Point Harmony Email & Collaboration?

Es el único de los tres productos caros de la tabla con tarifa impresa. En AWS Marketplace, Advanced figura a USD 83,46 por usuario por 12 meses y Complete a USD 109,14: USD 6,96 y USD 9,10 por buzón por mes. Los add-ons también tienen precio, incluido el archivado a 10 años a USD 51,36 por usuario/año. Un partner puede cotizar distinto, pero ya hay contra qué medirlo.

### ¿Cuánto cuesta Abnormal Security?

No publica precio. Las fuentes de terceros ubican el módulo de correo entrante en USD 20 a 35 por buzón al año, que es barato, más un cargo de plataforma plano de USD 5.000 a 15.000 anuales, que es lo que deja afuera a una PyME: a 25 buzones son USD 5.875 al año, USD 19,58 por buzón por mes, de los cuales USD 16,67 son estructura y no producto.

### ¿Un filtro anti-phishing reemplaza tener SPF, DKIM y DMARC?

No, resuelven problemas opuestos. Los cinco filtran el correo que entra a la organización; SPF, DKIM y DMARC evitan que alguien mande correo haciéndose pasar por tu dominio hacia clientes y proveedores. Configurarlos no cuesta licencia, cuesta una tarde de DNS, y es lo primero que conviene hacer antes de mirar cualquier cotización.

### ¿Por qué ninguna comparativa en español publica estos precios?

Una explicación posible, sin forma de probar la intención de nadie: buena parte de ese contenido lo escribe el mismo canal que después cotiza, y publicar una tabla de precios comparados no le conviene a quien negocia con ellos. Lo verificable es que tres de los cinco precios de esta nota estaban publicados todo el tiempo —en la página de Microsoft, en un marketplace de AWS y en la tarifa de un partner— y nadie los había puesto en la misma tabla.
