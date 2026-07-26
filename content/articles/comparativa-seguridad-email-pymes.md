---
title: "Comparativa: seguridad de email para PyMEs"
subtitle: Precio por buzón con link a la lista pública, cómo se compra realmente cada uno, dónde se para en el flujo de correo y qué plazo de implementación documenta el fabricante.
excerpt: Precio por buzón de los cinco con link a cada lista pública. Dos publican tarifa, dos cotizan a pedido y uno cobra un fee fijo que rompe la cuenta.
type: comparison
status: published
category: email-y-antiphishing
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-07-26
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
  su cargo de plataforma fijo, no es un producto para PyME, por más que a mi juicio
  sea el que mejor apunta al fraude por correo sin adjunto ni enlace. Para todos los
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

Proteger el correo de 25 personas cuesta entre USD 0 y USD 5.875 al año, y los dos extremos salen de precios que podés verificar en una tarde.

Cero si ya pagás Microsoft 365 Business Premium y terminás de encender lo que viene adentro. USD 5.875 si comprás [Abnormal Security](/producto/abnormal-security): 25 buzones a USD 35 al año son USD 875, y los otros USD 5.000 son un cargo de plataforma fijo que pagás igual con 25 buzones que con 2.500. Esa cuenta es toda la comparativa en una línea: abajo de 100 buzones el precio por asiento casi no importa, importa el piso de entrada.

**Sesgo declarado, acá arriba y no en un pie de página**: Capa Cero se financia con enlaces de afiliado a algunos de los productos que cubre. Ninguno de estos cinco deja comisión hoy, y la recomendación principal —que buena parte de las PyMEs con Business Premium no compre nada— no la dejaría en ningún caso. Leé el resto sabiendo eso.

**Regla del artículo**: cada precio tiene detrás una tarifa pública —de fabricante, de partner o de marketplace— o es una banda de un agregador de contratos reales, y en ese caso la celda lo dice. Mezclar una lista con una mediana negociada sin avisar es lo que arruina las comparativas en español de este rubro. Y una aclaración sobre mí: no operé estos cinco productos. Esto se apoya en documentación, listas públicas y agregadores, con link y fecha de consulta (26 de julio de 2026). Una comparativa puede recomendar sin haber tenido el fierro en la mano, siempre que diga de dónde sale cada número y dónde se le termina la evidencia.

## La tabla

| Producto | Precio por buzón/mes | Tipo de precio | Piso real de compra | Flujo y puesta en marcha |
|---|---|---|---|---|
| [Microsoft Defender for Office 365](/producto/microsoft-defender-for-office-365) | USD 2 (Plan 1) / USD 5 (Plan 2), anual. USD 0 marginal con Business Premium | **Tarifa del fabricante** ([fuente](https://www.microsoft.com/en-us/security/business/siem-and-xdr/microsoft-defender-office-365)) | 1 licencia, checkout web o partner CSP | Nativo, no toca el MX. Built-in protection ya viene aplicada; Standard y Strict hay que asignarlas |
| [Abnormal Security](/producto/abnormal-security) | USD 20-35 por buzón/**año** más cargo de plataforma de USD 5.000-15.000 anuales | Banda de agregador ([Vendr](https://www.vendr.com/marketplace/abnormal-security), [UnderDefense](https://underdefense.com/blog/abnormal-security-pricing-guide/)) | Quote-only. Mínimos de contrato de USD 25.000-50.000 | API post-entrega: el correo ya está en el inbox. Autorización OAuth, minutos según el fabricante |
| [Check Point Harmony Email & Collaboration](/producto/check-point-harmony-email-collaboration) | USD 6,96 (Advanced) / USD 9,10 (Complete), o USD 83,46 y USD 109,14 por usuario/12 meses | **Tarifa impresa** ([AWS Marketplace](https://aws.amazon.com/marketplace/pp/prodview-5v23pwcdewapi)) | Dimensiones por usuario, sin mínimo declarado | API post-entrega. Modo automático: autorizás la app y el resto se configura solo |
| [Mimecast Email Security](/producto/mimecast-email-security) | USD 3,33-6,67 base; USD 8,33-12,50+ completo (bandas anuales de USD 40-80 y USD 100-150+) | Banda de agregador ([Vendr, 110 compras](https://www.vendr.com/marketplace/mimecast)) | Partner o MSSP. Contrato mediano de USD 30.242/año | SEG clásico, con el MX por delante. Implementación aparte: [Guided 30 días, Managed 45](https://mimecastsupport.zendesk.com/hc/en-us/articles/34000453855763-Mimecast-Customer-Care-Managed-Guided-Onboarding-Steps) |
| [Proofpoint Email Protection](/producto/proofpoint-email-protection) | Essentials USD 3,93 a USD 9,42. Enterprise sin precio público | **Lista de Essentials publicada por un partner** ([Iron Cove](https://ironcovesolutions.com/en-US/pricing/proofpoint-essentials)) | Essentials desde 1 asiento y mes a mes. Enterprise quote-only | MX o API a elección. Por Graph API el fabricante habla de días; por SEG, cutover de MX |

Cada celda de precio tiene su fuente al lado: si alguna cifra te parece mal, discutila contra el link y no contra mí. Lo que sigue son notas al pie, una por producto.

## Microsoft: el USD 0 es real, el "ya estás protegido" no tanto

El costo marginal de [Defender for Office 365](/producto/microsoft-defender-for-office-365) Plan 1 es cero para quien ya paga Business Premium. El absoluto no: Business Premium sale USD 22 por usuario por mes con compromiso anual —USD 26,40 mes a mes—, o sea USD 6.600 al año para 25 buzones, con tope de 300 asientos.

Lo interesante de esa celda está en la [documentación de Microsoft](https://learn.microsoft.com/en-us/defender-office-365/preset-security-policies), no en una anécdota. Las preestablecidas son tres: **Built-in protection** viene aplicada a todos los destinatarios por defecto, mientras que **Standard** y **Strict** —dice el texto, textual— quedan asignadas a nadie hasta que un administrador las enciende. El escenario habitual no es "está apagado", es peor de detectar: está encendido en su versión mínima. Las diferencias están tabuladas ahí mismo:

- `AllowClickThrough` en `true`: el usuario puede saltearse la advertencia y entrar igual al enlace. En Standard y Strict, `false`.
- `DisableURLRewrite` en `true`: no se reescriben las URLs, solo se consultan por API.
- `EnableForInternalSenders` en `false`: Safe Links no se aplica al correo interno.
- Ante suplantación por mailbox intelligence, Standard mueve a Correo no deseado y Strict pone en cuarentena; el umbral de phishing pasa de 3 a 4.

Antes de cotizar nada, entonces, la pregunta honesta es si Standard tiene usuarios asignados en tu tenant. Se responde en dos minutos y es una tarde de trabajo que ya pagaste.

Los USD 2 y USD 5 standalone son la única tarifa de fabricante de la tabla, y por eso son el ancla contra la que negociar el resto. Si un partner te cotiza un producto de terceros a USD 8 por buzón, la pregunta no es si es caro: es qué te da por encima de los USD 5 del Plan 2.

## El mejor de la tabla es el que no te van a vender

Si tenés 25 personas, es probable que [Abnormal](/producto/abnormal-security) ni te cotice. Y ahora se puede explicar por qué con números en vez de con una sensación.

El precio por asiento no es el problema. UnderDefense ubica el módulo base de Inbound Email Security en USD 20 a 35 por buzón **al año** y Vendr da una banda parecida: entre USD 1,25 y USD 2,92 por buzón por mes, más barato que el Plan 2. El problema es el cargo de plataforma, plano, de USD 5.000 a 15.000 anuales, más los mínimos de contrato de USD 25.000 a 50.000 que Vendr reporta para despliegues chicos.

La cuenta a 25 buzones con el piso más benévolo: USD 875 de asientos más USD 5.000 de plataforma son USD 5.875 al año, o sea USD 19,58 por buzón por mes. Estás pagando 85% de estructura y 15% de producto, y es la versión optimista: asume que te dejan firmar por debajo del mínimo, cosa que la mediana de Vendr (USD 43.848 sobre 62 compras) sugiere que no pasa.

Hay además una hipótesis técnica, y la marco como mía porque no tengo medición que la respalde: el producto se apoya en un modelo de comportamiento construido sobre el historial de quién le escribe a quién adentro de la organización, y con 25 buzones ese historial es chico. Si eso diluye o no la ventaja frente a un filtro tradicional, no lo puedo afirmar. Es exactamente la pregunta que le haría al preventa antes de firmar.

Acá está la contradicción que este artículo no puede resolver y prefiero dejar escrita: a mi juicio es el que mejor apunta a lo que más importa —el fraude por correo sin adjunto ni enlace— y es el que menos PyMEs pueden comprar. Es una opinión formada sobre documentación, no sobre una prueba comparativa que yo haya corrido. Y antes de cualquier prueba, verificá dos cosas de las que no tengo dato: en qué idioma están la consola, la documentación y el soporte, y en qué huso horario te atienden. En una PyME de la región con un IT de dos personas eso pesa, y se responde en cinco minutos preguntando.

## Check Point Harmony: el único de los caros con la tarifa impresa

Esta es la celda que más cambió cuando fui a buscar fuentes en vez de repetir rangos de pasillo. [Harmony Email & Collaboration](/producto/check-point-harmony-email-collaboration) tiene precio público en su listado de AWS Marketplace, con dimensiones por usuario y por 12 meses.

- **Advanced**: USD 83,46 por usuario / 12 meses. Son USD 6,96 por buzón por mes.
- **Complete**: USD 109,14, o USD 9,10 al mes. Agrega sandbox extendido, DLP y cifrado.
- Add-ons con precio propio: archivado hasta 10 años, USD 51,36 por usuario/año; respuesta a incidentes gestionada, USD 15; gestión de DMARC, USD 10,70.

Como la dimensión es por usuario, la cuenta a 25 buzones es directa: 25 × 83,46 son USD 2.086,50 al año por Advanced. Si Check Point aplica algún mínimo comercial, no lo declara ahí, así que no lo voy a afirmar yo. Ninguna de esas cifras dice que sea barato: USD 6,96 está arriba del Plan 2, así que la comparación correcta es contra USD 5 y no contra cero. Lo que a mi juicio decide es el archivado a USD 51,36, único precio de archivado publicado en toda la comparativa.

### Dónde se para cada uno en el flujo del correo

Esta es la dimensión que ninguna comparativa en español mide y que define si el proyecto dura una tarde o un mes.

Harmony y Abnormal se conectan por API. Check Point documenta dos modos: en el automático autorizás la aplicación durante el asistente y el producto aplica solo el resto; en el manual, las configuraciones las hacés vos en el Centro de administración de Exchange antes de vincular la app.

Mimecast en modo clásico te pide apuntar el MX hacia ellos, y eso arrastra propagación de DNS, convivencia con el flujo viejo, reglas de conector, listas de IP permitidas y pruebas de entrega antes del cutover. Su documentación lo ordena en fases con plazos objetivo: 30 días en Guided, 45 en Managed. Proofpoint deja elegir: por Graph API habla de días, por SEG la mecánica es la misma.

La diferencia que a mí me importa no es el plazo sino la topología, y se ve el día malo. Si el producto está por API y se cae, el correo sigue llegando: perdés detección, no servicio. Si el MX apunta al proveedor y el proveedor se cae, el correo entrante se encola o rebota. Mimecast ofrece continuidad de servicio justamente para ese escenario, como parte de su catálogo: un agujero que el modelo por API nunca abrió. Si vas por SEG, preguntá por escrito si esa continuidad viene incluida en el SKU que te cotizan o se factura aparte.

Un asterisco honesto sobre los "minutos": ese número es del fabricante y describe el caso simple —un tenant, un dominio, modo automático—. El modo manual existe porque hay casos donde el automático no alcanza. Y no hay datos públicos confiables sobre estos despliegues arriba de unos cientos de buzones con coexistencia híbrida: ahí no me meto.

## Mimecast: qué te cuesta que un fabricante no publique lista

[Mimecast](/producto/mimecast-email-security) es el que mejor ilustra lo que cuesta la opacidad de precio. No hay tarifa pública: hay bandas construidas sobre contratos reales. Vendr, sobre 110 compras, ubica los bundles base entre USD 40 y USD 80 por usuario al año en organizaciones de 100 a 500 usuarios, y los paquetes completos en USD 100 a 150+. La mediana de contrato es de USD 30.242 anuales y el ahorro promedio negociado, 12%.

Leé esos números otra vez. La banda base sola es un factor de 2 entre extremos sobre el mismo producto, y de qué lado caés depende de cómo negocie el partner y no de qué necesites; contra el paquete completo el factor se va casi a 4. Eso es lo que te cuesta que no haya tarifa impresa: no tenés contra qué medir la cotización que llega.

Dos detalles que cambian la cuenta a 25 buzones. El primero es de lectura de la fuente: la banda de Vendr está construida sobre organizaciones de 100 a 500 usuarios, así que a 25 buzones ni siquiera estás dentro de la muestra que la generó, y esperar la parte baja del rango es voluntarismo. Y la implementación se compra aparte: Guided y Managed son la razón por la que Mimecast es el único que publica un plazo objetivo. Preferiría que publicara el precio.

## Hay dos Proofpoint y te van a cotizar el otro

[Proofpoint](/producto/proofpoint-email-protection) vende dos productos distintos bajo el mismo logo, y esa confusión es la trampa más fácil de comer del rubro. No hace falta haber leído cotizaciones ajenas para detectarla: alcanza con abrir las dos páginas de producto al lado.

Essentials sí tiene lista publicada, aunque no por el fabricante sino por partners que la imprimen. La de Iron Cove Solutions, vigente en 2026: Business USD 3,93, Business+ USD 4,86, Advanced USD 5,37, Advanced+ USD 5,87, Professional USD 8,14 y Professional+ USD 9,42 por usuario/mes. Advanced suma URL Defense y sandbox de adjuntos; Professional agrega cifrado, DLP y archivado.

Un dato que contradice lo que todo el mundo asume de este mercado: esa lista no exige contrato anual ni mínimo de asientos, y admite facturación mes a mes. Si necesitás una segunda capa por tres meses mientras migrás, es el único de la tabla que te lo permite.

Enterprise es otra cosa: no publica precio, pide proceso de venta con partner y los módulos se suman al bundle —la protección contra fraude de identidad es un SKU aparte, el entrenamiento simulado también—. El problema es de expectativas: el material de marketing que leíste, con sus referencias de clientes corporativos grandes, describe Enterprise. Lo que una empresa de 40 empleados puede comprar sin entrar en un proceso de venta con partner es Essentials, que trae bastante menos. Pedí el SKU exacto por escrito antes de comparar contra nada.

## Tres escenarios de compra para 25 buzones, cerrados por presupuesto

### Presupuesto: USD 0 extra al año

No compres nada. Ya pagaste USD 6.600 al año por Business Premium y el filtro viene adentro, a medio encender. Tu primera "compra" es una tarde: asignar Standard a todos los usuarios en el portal de Defender —un desplegable y un botón— y dejar [SPF, DKIM y DMARC bien configurados](/guia/configurar-spf-dkim-dmarc-paso-a-paso). Eso último no lo reemplaza ningún producto de la tabla: los cinco filtran el correo que entra, y DMARC gobierna el que sale con tu nombre hacia tus clientes. Problemas opuestos, y solo uno se arregla con plata.

### Presupuesto: hasta USD 1.500 al año

USD 1.500 dividido 25 buzones dividido 12 meses son USD 5 por buzón por mes exactos. Con precios publicados ahí entran dos cosas: el Plan 2 de Microsoft (25 × 5 × 12 = USD 1.500) y Proofpoint Essentials hasta Business+, a USD 4,86 (USD 1.458). Harmony Advanced queda afuera por poco: USD 2.086,50.

**Decisión: Defender for Office 365 Plan 2 standalone, USD 5 por usuario/mes.** No porque sea mejor motor que Essentials: es que si ya tenés Business Premium, comprar Essentials es poner un SEG adelante de un filtro que ya pagás, con dos consolas y dos lugares donde buscar un falso positivo. El Plan 2 agrega investigación —explorador de amenazas, respuesta automatizada, vistas de campaña— y el simulador de ataques, que a mi juicio es lo que justifica el salto: el elemento humano siguió presente en el 62% de las brechas del DBIR 2026. Si estás en Business Standard, ahí sí Essentials Business a USD 3,93 es una conversación legítima contra el Plan 1.

### Presupuesto: hasta USD 6.000 al año

USD 6.000 son USD 20 por buzón por mes: entra cualquier cosa que se deje comprar a 25 asientos. Hay dos caminos, y uno depende de un requisito legal.

**Decisión: si tenés obligación de archivado con retención larga, la única opción con precio verificable es Harmony Advanced más el add-on de archivado: 25 × (83,46 + 51,36) son USD 3.370,50 al año, y te sobran USD 2.629. Si no la tenés, no compres correo: reforzá identidad y backup.**

Un matiz. Mimecast sigue siendo la referencia cuando el requisito es e-discovery formal, cadena de custodia y búsquedas para un abogado, y su paquete completo (USD 2.500-3.750 a 25 buzones) también entra acá. La diferencia es que uno te dice el precio antes de que empieces a hablar y el otro no, y encima factura la implementación aparte. Si tu obligación es "guardar diez años y poder buscar", elegí el que publica; si viene con un requerimiento judicial adelante, pagá el proceso de venta.

Sobre el segundo camino. Sophos midió en 2026 que el 79% de los ataques de ransomware arranca por vía de identidad —primera vez en cuatro años por encima de la explotación de vulnerabilidades— y que el 97% de las víctimas por credenciales comprometidas ya tenía MFA puesto: faltaba justo donde importaba, en el 59% de los casos. Ese inventario está en [por qué tener MFA no alcanza](/guia/ya-tenes-mfa-y-no-alcanza), y el orden de tareas, en [cómo configurar MFA en una PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana). Con USD 2.600 comprás [llaves físicas resistentes a phishing](/resena/analisis-yubikey-5-series-costo-total-argentina) para todo el equipo y una licencia de [backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta): mueve más la aguja que subirte de tier en el filtro.

## Cuándo no comprar nada de esto

Hay cuatro situaciones donde comprar un producto de esta tabla es tirar plata, y las escribo sin matizar porque el matiz acá no ayuda.

- Tenés Business Premium y nunca asignaste Standard. Estás comprando una segunda capa arriba de una primera que corre en su configuración mínima documentada.
- Menos de 25 buzones. Dos de los cinco no publican precio ni te van a dar proceso de venta, y los que publican te cobran lista.
- No tenés DMARC en `reject`. El fraude que más caro sale es el que usa tu dominio contra tus clientes, y ninguno de estos productos lo frena desde afuera.
- No tenés backup de los buzones. Microsoft 365 tiene retención, no backup: si mañana borran una casilla y pasan 30 días, no la recuperás con un filtro anti-phishing.

Un dato para calibrar la urgencia. Las denuncias de phishing y suplantación ante el IC3 del FBI vienen bajando: 298.878 en 2023, 193.407 en 2024 y 191.561 en 2025. Pero las pérdidas por fraude al CEO subieron a USD 3.046.598.558 sobre 24.768 denuncias en 2025, contra USD 2.770 millones y 21.442 el año anterior. Menos ataques masivos, más dirigidos y rentables. Lo que rinde no es el filtro de volumen —eso ya lo hace Exchange Online Protection gratis— sino la detección de mensajes sin adjunto ni enlace: justo donde el que mejor puntúa es el que menos PyMEs pueden comprar.

Y una incomodidad para cerrar, porque va contra lo que acabo de venderte. En LATAM el phishing tocó al 73% de las organizaciones que relevó el ESET Security Report 2026 sobre 962 empresas de diez países. Pero el vector de entrada número uno del DBIR 2026 ya no es la gente: es la explotación de vulnerabilidades, que trepó al 31% de los accesos iniciales desde el 20% y por primera vez en diecinueve ediciones le pasó al robo de credenciales. Si tenés un solo proyecto este trimestre, parchear le gana a comprar filtro: lo desarrollo en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y el mismo criterio de precio verificable antes que marketing lo apliqué en la [comparativa de Bitdefender, Kaspersky y ESET](/comparativa/bitdefender-vs-kaspersky-vs-eset).

El resto de los productos del rubro están en la [categoría de seguridad de email y anti-phishing](/productos/email-y-antiphishing).

## Preguntas frecuentes

### ¿Hace falta comprar seguridad de email si ya tengo Microsoft 365?

Depende del plan. Con Business Basic o Standard tenés Exchange Online Protection: anti-spam y anti-malware por firmas, sin Safe Links ni Safe Attachments ni anti-phishing avanzado. Ahí sí falta algo, y lo más barato es el Plan 1 standalone a USD 2. Con Business Premium o E5 ya tenés Plan 1 o Plan 2 incluido, y lo primero es verificar si Standard tiene usuarios asignados. Si no, corrés con Built-in protection, que según Microsoft deja pasar el click-through y no aplica Safe Links al correo interno.

### ¿Cuál es la diferencia real entre Defender for Office 365 Plan 1 y Plan 2?

Plan 1 (USD 2/usuario/mes standalone) es prevención: Safe Links, Safe Attachments y anti-phishing con protección de suplantación. Plan 2 (USD 5) agrega investigación y respuesta: explorador de amenazas, respuesta automatizada, vistas de campaña y el simulador de ataques. Si no tenés a nadie que vaya a mirar el explorador, el salto de USD 3 se justifica casi solo por el simulador.

### ¿Cuánto cuesta Check Point Harmony Email & Collaboration?

Es el único de los tres productos caros de la tabla con tarifa impresa. En AWS Marketplace, Advanced figura a USD 83,46 por usuario por 12 meses y Complete a USD 109,14: USD 6,96 y USD 9,10 por buzón por mes. Los add-ons también tienen precio, incluido el archivado a 10 años a USD 51,36 por usuario/año. Un partner puede cotizarte distinto, pero ya tenés contra qué medirlo.

### ¿Cuánto cuesta Abnormal Security?

No publica precio. Las fuentes de terceros ubican el módulo de correo entrante en USD 20 a 35 por buzón al año, que es barato, más un cargo de plataforma plano de USD 5.000 a 15.000 anuales, que es lo que te deja afuera: a 25 buzones son USD 5.875 al año, USD 19,58 por buzón por mes, de los cuales USD 16,67 son estructura y no producto.

### ¿Un filtro anti-phishing reemplaza tener SPF, DKIM y DMARC?

No, resuelven problemas opuestos. Los cinco filtran el correo que entra a tu organización; SPF, DKIM y DMARC evitan que alguien mande correo haciéndose pasar por tu dominio hacia tus clientes y proveedores. Configurarlos no cuesta licencia, cuesta una tarde de DNS, y es lo primero que haría antes de mirar cualquier cotización.

### ¿Por qué ninguna comparativa en español publica estos precios?

Mi explicación, y la doy como opinión porque no tengo forma de probar la intención de nadie: buena parte de ese contenido lo escribe el mismo canal que después cotiza, y publicar una tabla de precios comparados no le conviene a quien negocia con ellos. Lo curioso, y esto sí es verificable, es que tres de los cinco precios de esta nota estaban publicados todo el tiempo —en la página de Microsoft, en un marketplace de AWS y en la tarifa de un partner— y nadie los había puesto en la misma tabla.
