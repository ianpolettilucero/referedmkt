---
title: "YubiKey 5 Series: el costo real en Argentina"
subtitle: Análisis de costo total y adecuación armado con precios de lista de Yubico, el régimen de importación vigente desde julio de 2026 y la documentación de producto de Microsoft y Yubico. No hay despliegue propio detrás: cada cifra sale de una fuente que podés abrir y rehacer.
excerpt: Una YubiKey 5C NFC son USD 58 de lista. Puesta en Argentina por courier, cerca de USD 81. Acá está el costo total a tres años, modelado con fuentes públicas.
type: review
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-08-20
products: [yubikey-5-series, microsoft-entra-id, cisco-duo]
rating: 4.2
verdict: |
  Evaluación editorial construida sobre precios de lista de Yubico, el régimen de importación argentino vigente (Decreto 604/2026) y la documentación de producto de Microsoft y Yubico. No hay un despliegue propio detrás de este número. La YubiKey 5 Series es, a mi juicio, la referencia de su categoría —no comparé contra otras marcas de llaves y no voy a fingir que sí—, y el número que importa no es el de la etiqueta: modelando 26 personas a tres años, el hardware desembarcado queda en torno al 45% del costo total y el resto es tiempo, logística y adaptadores. Compralas para las identidades con privilegio elevado y para la gente que se niega a instalar una app de la empresa en su teléfono personal. Para el resto del padrón, el propio Microsoft recomienda passkeys sincronizadas, que cuestan cero.
pros:
  - Precio de lista publicado y verificable, USD 29 la Security Key C NFC y USD 58 la YubiKey 5C NFC
  - Funciona sin batería, sin datos móviles y sin una app de la empresa en el teléfono personal del empleado
  - Las passkeys FIDO2 están disponibles en todas las ediciones de Entra ID, incluida la gratuita, así que la plataforma no agrega costo
  - Microsoft documenta 99% de registro exitoso y 95% de logins exitosos con passkeys contra 30% de los métodos heredados
  - Google no registra un solo phishing exitoso contra sus 85.000 empleados desde que impuso llaves físicas en 2017
  - Amortizada a cinco años, la llave desembarcada queda cerca de USD 1,30 por usuario por mes
cons:
  - Desembarcada en Argentina por courier cuesta alrededor de 1,4 veces la lista, y el IVA de ese régimen difícilmente se compute como crédito fiscal
  - Yubico clasifica a Argentina como destino de una sola llave por envío en YubiEnterprise Delivery
  - El registro de una llave nueva no funciona desde navegadores de iOS ni desde ChromeOS, según la matriz de compatibilidad de Microsoft
  - macOS no soporta NFC ni BLE para llaves de seguridad, así que ahí la llave entra por cable o no entra
  - El propio Microsoft advierte que las llaves suben el costo de equipamiento, capacitación y mesa de ayuda
  - Ocho PIN fallidos bloquean la aplicación FIDO2 y el único camino es un reset que borra todas las credenciales guardadas
meta_title: "YubiKey 5 Series: costo total en Argentina 2026"
meta_description: "Precios de lista de Yubico, cálculo de importación con el régimen vigente, costo total modelado a tres años y para quién sirve una llave física. Con fuentes."
---

> **Divulgación, arriba y no en un pie**: Capa Cero se financia con enlaces de afiliado a algunos de los productos que cubre. La [ficha de YubiKey 5 Series](/producto/yubikey-5-series) hoy no es uno de ellos, y la conclusión de esta nota —que para buena parte de las PyMEs la llave no se justifica— tampoco dejaría comisión si lo fuera.

Una YubiKey 5C NFC cuesta **USD 58** en la [tienda de Yubico](https://www.yubico.com/store/), y una Security Key C NFC **USD 29**. Si viste números más bajos en un análisis de hace un año, no te confundiste ni te confundieron: la lista cambió. Los de arriba son los precios publicados hoy y son los únicos que sirven para presupuestar; cualquier cifra anterior hay que verificarla contra esa misma página antes de usarla.

Es el número con el que casi todos los análisis empiezan y terminan, y es el que menos se parece a lo que sale la llave puesta sobre un escritorio en Buenos Aires.

De FIDO2, PIV y OpenPGP ya habla la [ficha de producto de YubiKey 5 Series](/producto/yubikey-5-series). Acá va lo que rompe presupuestos: cuánto cuesta la llave desembarcada, cuánto cuesta el resto del proyecto que no es la llave, qué pasa cuando alguien la pierde, y para qué perfil de gente cierra la matemática. Todo sale de precios públicos, del régimen aduanero publicado y de la documentación de Microsoft y Yubico. Cuando algo es opinión mía, lo vas a ver escrito como opinión mía.

## Precios de lista, que es de donde arranca todo

| Modelo | Lista Yubico (julio 2026) | Qué hace |
|---|---|---|
| Security Key NFC / Security Key C NFC | **USD 29** | Solo FIDO2/U2F |
| YubiKey 5 NFC (USB-A) | **USD 58** | FIDO2, OTP, PIV, OpenPGP |
| YubiKey 5C NFC (USB-C) | **USD 58** | Idem |
| YubiKey 5C Nano | **USD 68** | Idem, formato que queda enchufado |
| YubiKey 5Ci (USB-C + Lightning) | **USD 85** | Idem, para iPhone con Lightning |
| YubiKey Bio / C Bio | **USD 98** | Solo FIDO2, con huella |
| YubiKey 5 FIPS Series | **USD 88 a 115** | Idem 5 Series, certificado FIPS |

La diferencia de USD 29 entre una Security Key y una YubiKey 5 no es marketing. La Security Key Series hace **solo** FIDO2 y U2F: no tiene OTP, ni PIV, ni OpenPGP. Para alguien que únicamente se loguea en Microsoft 365 y en el gestor de contraseñas, pagar USD 58 por capacidades que nunca va a usar es tirar USD 29 por cabeza; en un padrón de veinte personas, casi USD 600.

Mi opinión, asumida como tal: mezclar los dos modelos es la decisión correcta en casi cualquier PyME, y más abajo está el único motivo técnico por el que puede salirte mal.

## Qué cuesta puesta acá, con el régimen que rige hoy

Acá conviene ser exacto porque el terreno se movió hace pocos días. El **Decreto 604/2026**, vigente desde el 17 de julio de 2026, [reformó el régimen aduanero postal y courier](https://www.cronista.com/economia-politica/el-gobierno-modifico-las-compras-al-exterior-nuevo-limite-unificado-fin-de-la-alicuota-del-50-y-exportaciones-sin-tope/). Tres cambios importan para esta cuenta:

1. La franquicia quedó **unificada en USD 400 de valor FOB por envío**, para Correo Argentino y para courier privado por igual, con un cupo de **cinco envíos anuales**.
2. Dentro de la franquicia no se pagan derechos de importación ni tasa de estadística. Sí se paga **IVA del 21%**.
3. Se **derogó la alícuota plana del 50%** que castigaba al excedente. Lo que pasa los USD 400 ahora tributa según el arancel específico de la mercadería, bajo régimen general.

El tope por envío sigue en USD 3.000 FOB y 50 kg. Cualquier análisis de importación de llaves anterior a esa fecha está calculando con una regla que ya no existe, y eso incluye buena parte de lo que vas a encontrar googleando.

Con eso, el cálculo de desembarco por courier es una suma corta:

**Lista + flete internacional + IVA 21% sobre CIF** (y derechos solo si el FOB del envío supera los USD 400).

| Envío modelo | Contenido | FOB | Flete (supuesto) | IVA 21% s/CIF | Total | Por llave | Multiplicador |
|---|---|---|---|---|---|---|---|
| A | 13 × Security Key C NFC | USD 377 | USD 55 | USD 91 | **USD 523** | **USD 40** | 1,39x |
| B | 6 × YubiKey 5C NFC | USD 348 | USD 55 | USD 85 | **USD 488** | **USD 81** | 1,40x |

Los USD 55 de flete son un supuesto para un paquete express de menos de medio kilo, no un dato: cotizalo y reemplazá esa celda. Todo lo demás sale de la lista de Yubico y de la alícuota publicada. Lo robusto es el multiplicador: **alrededor de 1,4 veces la lista**, muy lejos del 2x o 2,5x que se sigue repitiendo de memoria.

Dos advertencias cambian la lectura de esa tabla.

**La primera es de caja fiscal.** El IVA de un envío courier simplificado va a nombre de una persona, no de tu CUIT como importador, y no hay despacho de importación que puedas computar. Mi lectura es que ese 21% en la práctica no vuelve como crédito fiscal, a diferencia de lo que pasa en una importación formal. Si tu contador te dice otra cosa para tu caso puntual, hacele caso a tu contador y no a un artículo.

**La segunda es el techo.** Cinco envíos anuales de hasta USD 400 FOB son unos **USD 2.000 de lista por año y por CUIT**: 68 Security Keys o 34 YubiKey 5C. Para veintipico de personas alcanza y sobra. Para cien personas con dos llaves cada una, no: ahí vas obligado a importación formal, con derechos según posición arancelaria, **tasa de estadística del 3%** —[prorrogada por Decreto 1140/2024 hasta el 31/12/2027](https://www.cda.org.ar/detalle_noticia.php?id=41773)—, IVA 21%, IVA adicional del 20% y percepción de Ganancias del 6%, los tres últimos recuperables como crédito fiscal. El costo económico final baja y el desembolso del primer trimestre se dispara. Ese es el problema de caja real, no el precio de la etiqueta.

Sobre la posición arancelaria: las llaves de Yubico se clasifican habitualmente en la partida **8471.80**, que es donde las ubicó [un dictamen aduanero de la India sobre la YubiKey de Yubico AB](https://taxguru.in/custom-duty/yubikey-manufactured-yubico-ab-classifiable-hs-code-84718000.html), y en Argentina esa posición figura en la Lista Nacional de Bienes de Informática y Telecomunicaciones con derecho extrazona del 0%. Pero la clasificación la define tu despachante, no un artículo, y una reclasificación cambia el número. Preguntalo antes de firmar la orden de compra.

## El techo logístico que casi nadie menciona

Este es el dato que más me sorprendió y que no suele aparecer en los análisis de producto: en las [políticas de entrega de YubiEnterprise Delivery](https://docs.yubico.com/cloud-services/yubienterprise/delivery/delivery-policies.html), **Argentina figura como destino de una sola llave por envío**. Yubico lo aplica a los países cuyas aduanas exigen trámite de importación: Brasil, Chile, Colombia, México, Perú, Uruguay y Paraguay también están en la lista.

Traducido: el servicio de entrega directa al empleado que Yubico vende como ventaja de su suscripción, acá te sirve para mandar una llave a una persona. Para mandar treinta y cuatro necesitás el servicio "White Glove" que ofrece aparte, o hacerlo vos. Y **la reposición en unidades sueltas no es barata**: el flete se reparte entre menos llaves. La respuesta no es logística, es inventario.

Yubico, en su [página de llaves de repuesto](https://www.yubico.com/products/spare/), lo dice sin vueltas: *"Starting off, you should be fine with 1-2 spare keys"*. Mi opinión: quien emite facturación o concilia bancos arranca con dos llaves registradas. La segunda cuesta USD 81 desembarcada; una mañana sin acceso de esa persona cuesta más, y no hace falta haberlo vivido para hacer la cuenta.

## Para quién sirve, según el fabricante de tu proveedor de identidad

Acá el análisis se pone incómodo para quien vende llaves, y por eso conviene citarlo textual. La [documentación de Microsoft sobre passkeys FIDO2 en Entra ID](https://learn.microsoft.com/en-us/entra/identity/authentication/concept-authentication-passkeys-fido2) dice esto sobre las llaves físicas:

> *"FIDO2 security keys are recommended for highly regulated industries or users with elevated privileges. They provide strong security, but can increase costs for equipment, training, and helpdesk support—especially when users lose their physical keys and need account recovery."*

Y sobre el resto del padrón: *"For most users—those outside highly regulated environments or without access to sensitive systems—synced passkeys offer a convenient, low-cost alternative to traditional MFA."*

Es el proveedor de la plataforma diciendo que las llaves no son para todo el mundo y que además **suben** el costo de mesa de ayuda. Quien te presupueste llaves para el padrón completo está discutiendo con la documentación del producto que va a administrar.

Los números que Microsoft publica en esa misma página, sobre cientos de millones de usuarios de cuentas de consumo, son igual de contundentes:

- **99% de los usuarios registra exitosamente** una passkey sincronizada.
- Son **14 veces más rápidas** que la combinación de contraseña y MFA tradicional: **3 segundos contra 69**.
- Los usuarios tienen **3 veces más éxito** iniciando sesión con passkey que con métodos heredados: **95% contra 30%**.

Ojo con la letra chica, porque acá es fácil hacer trampa: son cifras de passkeys **sincronizadas** en cuentas **de consumo**, no de llaves físicas en una PyME argentina. Sostienen la dirección y el orden de magnitud, no un número para tu oficina. Ese medilo vos: treinta logins por método alcanzan.

Del lado del hardware, el caso mejor documentado sigue siendo Google. [El estudio de caso de la FIDO Alliance](https://fidoalliance.org/google-case-study/) reporta que **no hubo un solo phishing exitoso contra sus más de 85.000 empleados** desde que impuso llaves físicas en 2017. Muestra enorme, resultado limpio. También es Google, con su logística y su mesa de ayuda, y eso no se traslada gratis a una empresa de veintiséis personas.

## El disparador que sí justifica el gasto

El disparador que justifica el gasto sin discusión no es un incidente de seguridad. Es la persona que se niega a instalar una app de la empresa en su celular personal. Es un argumento razonable, no hay mucho con qué rebatirlo, y la llave lo resuelve de raíz: no necesita batería, ni datos, ni una app en un dispositivo que no es de la empresa.

No conozco una encuesta que mida esa negativa en particular, así que lo que sigue es contexto y no prueba. El [ESET Security Report 2026](https://www.welivesecurity.com/es/informes/eset-security-report-2026-ciberseguridad-empresas-latinoamerica/), sobre 1.563 profesionales de 962 organizaciones en diez países de Latinoamérica, mide que solo el **57% de las empresas de la región implementa MFA** y que entre el personal no técnico la adopción baja al **52,2%**. Agrega un dato que explica bastante: el **26,9% del personal no técnico no aplica ninguna medida de seguridad sobre su teléfono corporativo**. La discusión sobre el teléfono personal cae dentro de ese hueco, y es justamente la parte que ninguna de esas encuestas pregunta de frente. Sobre por qué tener el segundo factor prendido no equivale a tenerlo puesto donde hace falta, está [los once accesos que casi ninguna PyME cubre](/guia/ya-tenes-mfa-y-no-alcanza).

## Costo total modelado a tres años

Es un **modelo**, no una factura: hardware e impuestos salen de precios y alícuotas publicados; horas y adaptadores son supuestos declarados que tenés que reemplazar por los tuyos. Uso USD 25 la hora de costo interno cargado; si el tuyo es otro, la última fila se mueve proporcionalmente. Dos escenarios, para 26 personas con [Microsoft Entra ID](/producto/microsoft-entra-id) como proveedor de identidad.

| Concepto | A: solo 8 identidades críticas | B: padrón completo de 26 |
|---|---|---|
| Composición | 16 × 5C NFC (2 por persona) | 18 × Security Key C + 16 × 5C NFC |
| Hardware a lista | USD 928 | USD 1.450 |
| Hardware desembarcado (1,40x) | **USD 1.299** | **USD 2.030** |
| Reposición 20% a 3 años | USD 244 | USD 400 |
| Hubs y adaptadores (supuesto) | USD 100 | USD 150 |
| Puesta en marcha (supuesto) | 20 h → USD 500 | 40 h → USD 1.000 |
| Soporte recurrente (supuesto) | 18 h → USD 450 | 36 h → USD 900 |
| Licencias de plataforma | USD 0 | USD 0 |
| **Total 3 años** | **USD 2.593** | **USD 4.480** |
| **Por usuario protegido / mes** | **USD 9,00** | **USD 4,79** |

Tres lecturas de esa tabla.

**El hardware es menos de la mitad.** En el escenario B, los USD 2.030 de llaves desembarcadas son el **45%** del total; el otro 55% es tiempo, logística y adaptadores. Quien presupueste esto multiplicando lista por cantidad de empleados está presupuestando un tercio de lo que va a costar.

**La plataforma sale cero, y esto corrige un error frecuente.** La documentación de Microsoft es explícita: *"Passkeys (FIDO2) are available in all Microsoft Entra ID editions, including Microsoft Entra ID Free. No extra licenses are required."* No necesitás P1 para usar llaves. Sí lo necesitás —viene en Business Premium, E3 y E5— para las políticas de fuerza de autenticación en Acceso Condicional, que son las que te dejan **exigir** la llave en vez de solo permitirla. Distinción cara de descubrir tarde.

**Contra las alternativas, el número aguanta pero no arrasa.** [Cisco Duo](/producto/cisco-duo) publica USD 6 por usuario por mes en Advantage ([página de precios de Duo](https://duo.com/pricing), consultada en julio de 2026): USD 5.616 a tres años para 26 personas, más caro que el escenario B. Pero Essentials figura en USD 3 y queda en USD 2.808, hay un plan gratuito para equipos chicos, y las passkeys sincronizadas de Entra, para quien ya paga Microsoft 365, cuestan **cero incremental**. El resto de la [categoría de MFA y autenticación](/productos/mfa-y-autenticacion) tiene aritmética parecida.

La otra cara también es real: amortizadas a cinco años —no tienen batería ni partes móviles—, esos USD 2.030 son unos **USD 1,30 por usuario por mes**. El problema nunca fue el costo total. Fue el pico de caja del primer trimestre.

## Las cinco cosas que rompen un despliegue de llaves

Ninguna es criptografía. Todas están documentadas y las cinco son fáciles de descubrir tarde.

**1. Los puertos USB.** Monitor, teclado, mouse, auriculares y un dock viejo ocupan cuatro o cinco puertos en un escritorio normal. Si la persona tiene que desenchufar el mouse para autenticarse, abandona el control en dos semanas. Contá los puertos libres del parque antes de comprar. Suena ridículo escribirlo y no lo es: un control que obliga a desenchufar algo se abandona solo.

**2. Las restricciones por AAGUID en Entra.** Cada modelo de llave tiene un identificador de 128 bits, el AAGUID, que declara marca y modelo; si activás restricciones de clave en un perfil de passkey, Entra solo acepta los de tu lista. El detalle importante es que el AAGUID **no depende del conector sino del chip y el firmware**: una Security Key C NFC y una YubiKey 5C NFC tienen AAGUID distintos aunque las dos sean de Yubico y las dos sean USB-C. Si mezclás modelos, cargalos todos antes de repartir. Y la advertencia textual de Microsoft, que es peor: *"If you change key restrictions and remove an AAGUID that you previously allowed, users who previously registered an allowed method can no longer use it for sign-in."* Sacar un AAGUID de la lista no bloquea registros nuevos: bloquea a gente que ya estaba adentro.

**3. iOS, macOS y ChromeOS.** Acá la [matriz de compatibilidad de Microsoft](https://learn.microsoft.com/en-us/entra/identity/authentication/concept-fido2-compatibility) contradice lo que casi todo el mundo asume. El registro de una llave nueva **no funciona desde navegadores de iOS**, porque no ofrecen configurar biometría ni PIN. Tampoco en ChromeOS ni en el navegador Chrome. En **macOS**, Apple no soporta NFC ni BLE para llaves de seguridad y el registro tampoco anda desde esos navegadores. Iniciar sesión sí anda en todos lados; lo que no anda es el alta. Planificá el registro desde Windows o Linux, o vas a tener media oficina esperando.

**4. Linux.** Hace falta una regla udev para que el navegador vea el dispositivo; sin ella, [solo root accede al `/dev/hidraw`](https://developers.yubico.com/libfido2/). El paquete `libfido2` instala las reglas en `/lib/udev/rules.d/` y crea el grupo `plugdev`. Un `apt install`, un `udevadm control --reload-rules` y volver a enchufar. Media hora si sabés lo que buscás, media tarde si no.

**5. El PIN.** [Yubico documenta](https://support.yubico.com/hc/en-us/articles/360016648899-Resetting-the-FIDO2-application-on-the-YubiKey) **ocho intentos** de PIN FIDO2. Al octavo fallido la aplicación se bloquea y el único camino es un reset de fábrica que **borra todas las passkeys de esa llave**; un PIN correcto reinicia el contador. Eso es lo que convierte una llave perdida en un problema de disponibilidad y no de seguridad, y es exactamente lo que hay que explicarle a la persona el día que se la das.

## El procedimiento de llave perdida, escrito antes de repartir

Este documento conviene tenerlo listo antes de la orden de compra, no después del reparto. Los pasos salen de la propia documentación de Entra:

1. **Confirmar que la llave tenía PIN.** Si lo tenía, es una emergencia de disponibilidad y no de seguridad, que es mucho más barata.
2. **Entrar con la cuenta break-glass**, la única exenta de las políticas de acceso condicional. Tenés que saber dónde están sus credenciales sin buscarlas.
3. **Emitir un [Temporary Access Pass](https://learn.microsoft.com/en-us/entra/identity/authentication/howto-authentication-temporary-access-pass)** de un solo uso y vida corta: Entra admite vida mínima desde 10 minutos.
4. **Que la persona registre una llave del pool** con ese TAP. Detalle no obvio: Entra exige MFA completado en los últimos cinco minutos para registrar una passkey, y el TAP cubre ese hueco cuando el único factor que tenía era la llave perdida.
5. **Revocar la credencial** de la llave perdida en Entra y en el gestor de contraseñas, y **las sesiones activas** de esa identidad.
6. **Anotar fecha y número de serie** de la llave dada de baja, y avisar por un canal fuera de banda.

El paso 6 parece burocracia y no lo es: a los tres meses nadie se acuerda de si la llave que sacás del pool es nueva o volvió de alguien que renunció. Y **la devolución va en el procedimiento de egreso**, en la misma fila que la notebook: es el paso que más fácil se escapa, porque no tiene dueño asignado, y no lo notás hasta que contás el pool.

## Qué no compra esto

Es la parte que se pierde en cualquier reunión donde alguien presenta llaves como si cerraran el tema. Una llave impide que usen la credencial de tu gente en una página que no es la tuya. **No impide** que a alguien le entre un infostealer y le roben la sesión ya autenticada. **No impide** que comprometan al proveedor que te liquida sueldos. **No impide** que borren un buzón, que es un problema de respaldo y no de identidad —sobre eso está [por qué Microsoft 365 necesita backup de terceros](/guia/backup-microsoft-365-hace-falta)—. Tres problemas distintos, tres presupuestos distintos, y la llave no toca ninguno.

Para ubicarla en el resto del tablero está la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), y si el proyecto de MFA todavía no arrancó, el orden de tareas está en [cómo configurar MFA en toda tu PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana).

## Dónde no llega este análisis

Este análisis se apoya en precios de lista, el régimen de importación publicado y documentación de producto. Hay dos zonas donde eso no alcanza.

**La suscripción YubiEnterprise.** Yubico la vende por tramos y describe el costo en su material comercial con una analogía —cuesta menos que un café por usuario por mes— en vez de con una cifra: **no publica el precio por usuario**, y el porcentaje de llaves de reemplazo incluido varía por tramo y tampoco está publicado como tarifa. Sin precio publicado no hay cuenta que hacer, y con Argentina marcada como destino de una llave por envío, mi opinión es que a esta escala no es el camino. En España o México, pedí la cotización y rehacé la tabla.

**Padrones de más de 400 identidades.** Ahí cambian la logística, el régimen de importación y el modelo de soporte, y no hay datos públicos confiables para modelarlo con honestidad. Así que no lo modelo.

## Preguntas frecuentes

### ¿Cuánto sale una YubiKey en Argentina con impuestos?

Con el régimen vigente desde el 17 de julio de 2026, un envío courier de hasta **USD 400 de valor FOB** no paga derechos de importación ni tasa de estadística, pero sí **IVA del 21%**. Sumando flete, el desembarco queda cerca de **1,4 veces la lista**: unos **USD 40** la Security Key C NFC de USD 29, unos **USD 81** la YubiKey 5C NFC de USD 58. Si el envío supera los USD 400, el excedente tributa según el arancel específico de la mercadería, porque el decreto derogó la vieja alícuota plana del 50%. Tenés cinco envíos por año.

### ¿Qué pasa si un empleado pierde la llave?

Si tenía PIN configurado, no es un problema de seguridad: quien la encuentre necesita el PIN, y a los ocho intentos fallidos la aplicación FIDO2 se bloquea sola. Es un problema de disponibilidad. La secuencia es cuenta break-glass, Temporary Access Pass de un solo uso, registro de una llave del pool, revocación de la credencial vieja y de las sesiones activas. Con una segunda llave ya registrada, el incidente dura lo que tarda la persona en abrir el cajón. Sin ella, dura lo que tardes vos en atender el teléfono.

### ¿Sirve YubiKey para empleados que no tienen celular de empresa?

Es el caso donde mejor funciona y, en mi opinión, el único que justifica el gasto sin discusión. No necesita batería, ni datos, ni una app instalada en un dispositivo que no es de la empresa. Si tu problema es que hay gente que se niega a poner la app corporativa en su teléfono personal, esto lo resuelve de raíz. Si tu problema es otro, revisá antes [las alternativas de la categoría de MFA y autenticación](/productos/mfa-y-autenticacion).

### ¿Qué son las passkeys y reemplazan a la YubiKey?

Una passkey es una credencial FIDO2 que reemplaza a la contraseña. La diferencia práctica está en dónde vive: las sincronizadas se guardan en el llavero de Apple o Google y se copian entre dispositivos; las de una YubiKey son *device-bound* y nunca salen del hardware. Microsoft es claro en que las sincronizadas alcanzan para la mayoría. La llave gana cuando querés que la credencial de trabajo no dependa de la cuenta personal de iCloud o Google del empleado, y cuando necesitás **attestation**: Entra solo verifica marca y modelo si la credencial es device-bound, porque las sincronizadas no la soportan. Sobre cómo conviven con un gestor, mirá los [gestores de contraseñas para empresas](/productos/gestion-contrasenas).

### ¿Vale la pena para una PyME de menos de 30 personas?

Depende de a quién se la des, y esa es la respuesta honesta. Modelado a tres años, cubrir 8 identidades críticas con dos llaves cada una da **USD 9 por usuario protegido por mes**; cubrir las 26 da **USD 4,79**. Para las cinco o diez identidades más críticas se justifica casi siempre. Para el padrón completo, si nadie objeta usar el celular, ese mismo dinero rinde más en backups probados. El propio Microsoft recomienda llaves físicas para industrias reguladas y privilegio elevado, y passkeys sincronizadas para el resto.

### ¿Funciona con acceso remoto y VPN?

Sí, por FIDO2 contra el proveedor de identidad o por certificado PIV en escenarios más viejos. Dos advertencias. La Security Key Series **solo hace FIDO2**: si necesitás PIV o un OTP tipeado —algunos concentradores VPN viejos no aceptan otra cosa— tenés que ir a una YubiKey 5, USD 29 más por unidad. Y los módulos de PowerShell que usan Internet Explorer en vez de Edge no piden FIDO2, así que ahí la llave no aparece; Microsoft sugiere autenticación por certificado para esas cuentas. El enfoque general está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).
