---
title: "SPF, DKIM y DMARC: guía paso a paso para PyMEs"
subtitle: La checklist de cuatro comandos con veredicto verde, amarillo y rojo, más el calendario de 8 semanas para llegar a p=reject sin cortar la facturación electrónica.
excerpt: Cuatro comandos dig para correr contra tu dominio, cómo leer el veredicto de cada uno y el calendario de 8 semanas de p=none a p=reject sin romper el ERP.
type: guide
status: published
category: email-y-antiphishing
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-07-26
products:
  - microsoft-defender-for-office-365
  - proofpoint-email-protection
  - mimecast-email-security
meta_title: "SPF, DKIM y DMARC paso a paso: checklist para PyMEs"
meta_description: "Los cuatro comandos dig, cómo leer cada registro, y el calendario de 8 semanas para llegar a DMARC p=reject sin romper facturación, CRM ni impresoras."
---

Tenés SPF. Tenés DKIM. Te siguen suplantando el dominio. La razón es una: sin un registro DMARC en `p=reject`, ningún servidor del mundo está obligado a hacer nada con esos dos registros. SPF y DKIM son evidencia, DMARC es la sentencia. Si no publicaste la sentencia, el peritaje se archiva.

Esto no es un tutorial de sintaxis: es una **checklist de auditoría para que la corras vos, contra tu dominio, hoy**. Cada bloque de acá abajo es un comando, una salida anotada y un veredicto en tres estados: **verde** (dejalo), **amarillo** (funciona pero te rompe en tres meses) y **rojo** (arreglalo hoy).

Un aviso de entrada, porque cambia cómo se lee todo lo que sigue: **las salidas son ejemplos construidos**, armados a propósito para que cada caso de fallo aparezca una vez y se pueda comparar. El dominio `distribuidora-ejemplo.com.ar` no existe. Los comandos sí son reales y el criterio de lectura también. Corré los cuatro contra tu dominio y compará contra las tablas. Lo que sigue se apoya en el [RFC 7208](https://datatracker.ietf.org/doc/html/rfc7208) (SPF), el [RFC 7489](https://datatracker.ietf.org/doc/html/rfc7489) (DMARC), la documentación de Microsoft y los requisitos publicados por Google y Microsoft para remitentes de alto volumen. Cada número tiene su fuente.

---

## 0. Los cuatro comandos que corrés antes de leer el resto

Abrí una terminal. No hace falta acceso al panel DNS todavía, esto es todo público.

```
dig +short TXT tudominio.com.ar
dig +short TXT _dmarc.tudominio.com.ar
dig +short MX tudominio.com.ar
dig +short TXT selector1._domainkey.tudominio.com.ar
```

En Windows sin WSL, `nslookup -type=TXT _dmarc.tudominio.com 8.8.8.8` hace lo mismo con más ruido.

Así se ve un caso de manual, con los cuatro problemas más frecuentes puestos juntos en un mismo dominio de ejemplo:

```
$ dig +short TXT distribuidora-ejemplo.com.ar
"v=spf1 include:_spf.google.com include:spf.protection.outlook.com include:sendgrid.net include:_spf.mercadolibre.com include:servers.mcsv.net a mx ~all"
"google-site-verification=8kQ2..."

$ dig +short TXT _dmarc.distribuidora-ejemplo.com.ar
(vacío)

$ dig +short MX distribuidora-ejemplo.com.ar
0 distribuidoraejemplo-com-ar.mail.protection.outlook.com.

$ dig +short TXT selector1._domainkey.distribuidora-ejemplo.com.ar
"v=DKIM1; k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC..."
```

Veredicto, en orden de gravedad:

| Registro | Estado | Lectura |
|---|---|---|
| DMARC | Rojo | No existe. Cualquiera manda como `facturacion@` y llega |
| SPF | Rojo | 7 mecanismos con lookup + `~all`. Supera los 10 lookups |
| DKIM | Amarillo | Selector válido, pero la clave es de 1024 bits |
| MX | Verde | Un solo MX a Exchange Online, sin restos de servidores viejos |

Fijate en la asimetría: SPF de sobra —tanto que está roto por exceso—, DKIM presente, y `_dmarc` vacío. Esa asimetría tiene una explicación estructural, no un misterio: SPF y DKIM te los pide el proveedor cuando contratás la plataforma de mailing, porque sin eso no te entrega. DMARC no te lo pide nadie. Se hicieron los deberes que alguien reclamó y nadie cerró el círculo.

---

## 1. SPF leído carácter por carácter

SPF publica qué IPs pueden mandar con tu dominio en el sobre. Registro TXT en la raíz, empieza con `v=spf1` y termina con un mecanismo `all`.

### El límite de 10 lookups

El RFC 7208 fija un máximo de **10 consultas DNS** por evaluación. Cuentan `include`, `a`, `mx`, `ptr`, `exists` y el modificador `redirect`. `ip4` e `ip6` no cuentan: son literales, igual que `all`.

Lo que casi nadie tiene en cuenta es que **los includes son recursivos**: cada uno cuenta 1 y además arrastra los lookups del registro que trae adentro. Contá el del ejemplo:

- `_spf.google.com` → 1 propio + 3 heredados (`_netblocks`, `_netblocks2`, `_netblocks3`) = **4**
- `spf.protection.outlook.com` → **1**
- `sendgrid.net` → 1 + 2 anidados = **3**
- `_spf.mercadolibre.com` → **1**; `servers.mcsv.net` → **1**; `a` → **1**; `mx` → **1**

Total: **12**. Dos de más. Cuando un receptor pasa de 10 el resultado no es "fail", es `permerror`, y `permerror` se trata como no evaluable: equivale a no tener SPF. Tu registro larguísimo, armado por cinco proveedores en tres años, **no evalúa nada**.

Hay un agravante que hace de esto un bug intermitente y por eso más difícil de detectar: [la documentación de Microsoft aclara](https://learn.microsoft.com/en-us/defender-office-365/email-authentication-spf-configure) que los receptores evalúan los mecanismos **de izquierda a derecha y frenan cuando encuentran la coincidencia**. Un registro que en el papel pide 12 lookups puede validar sin problema para el correo que sale por Microsoft 365 —que está segundo— y fallar con `permerror` para el que sale por un proveedor que quedó al final. Mismo registro, dos resultados, según qué sistema mandó el mensaje.

No lo cuentes a ojo: usá el chequeo SPF de dmarcian o `spfquery`, porque los proveedores cambian sus registros sin avisar y hoy estás en 9 y el mes que viene en 11.

**Cómo lo arreglás**, en orden de preferencia:

1. Sacar includes de proveedores que ya no usás. En el ejemplo, `_spf.mercadolibre.com` y `servers.mcsv.net` (Mailchimp) son restos de integraciones viejas: sacarlos baja de 12 a 10. Estar justo en el límite no es lo mismo que estar cómodo.
2. Sacar `a` y `mx` si no mandás desde el web ni desde el MX. En un dominio 100% Exchange Online, `mx` es un lookup regalado.
3. Reemplazar un include por sus `ip4` literales si el proveedor publica rangos estables. Microsoft lo desaconseja explícitamente para su propia infraestructura, que rota IPs. Si el proveedor las rota, te quedaste sin SPF y no te enterás.
4. Aplanar con SPF flattening. Le entregás a un tercero la definición de quién puede mandar como vos. Último recurso.

### Por qué `~all` importa menos de lo que creés, pero no da lo mismo

`~all` es *softfail*: "esto probablemente no sea mío, pero entregalo igual". `-all` es *fail*. Microsoft recomienda `-all` para dominios de Microsoft 365, y el registro que documenta para el escenario simple termina así: `v=spf1 include:spf.protection.outlook.com -all`.

La parte contraintuitiva, y donde muchas guías se quedan a mitad de camino: **DMARC trata a `~all` y a `-all` igual**, porque no mira el matiz del fallo, mira si hubo `pass` alineado. Todo lo que no sea `pass` alineado cae en la política. Hasta ahí, da lo mismo.

Pero hay una excepción documentada por Microsoft en esa misma página, y es la que te muerde: la política DMARC **queda efectivamente ignorada para fallos de SPF `~all` cuando el mensaje tampoco trae firma DKIM**. O sea: en el peor caso —un mensaje suplantado, sin firma, con tu SPF en softfail— la sentencia no se ejecuta. Por eso `-all` es el estado final y `~all` es un lugar de paso.

| Terminador | Veredicto | Cuándo |
|---|---|---|
| `+all` | Rojo | Nunca. Autorizás a todo internet a mandar como vos |
| `?all` | Rojo | Neutral. Equivale a no tener SPF |
| `~all` | Amarillo | Aceptable durante el rollout, no como estado final |
| `-all` | Verde | Estado final, con los reportes DMARC ya validados |

Un detalle que rompe rollouts: **SPF se rompe con el reenvío**. Si un cliente reenvía tu mail, el reenviador manda desde su IP y SPF falla. Por eso DMARC acepta que alcance con SPF **o** DKIM: DKIM sobrevive al reenvío, SPF no. Esa es la razón real por la que necesitás los dos.

---

## 2. DKIM: encontrar el selector cuando nadie sabe cuál es

DKIM firma el mensaje. La clave pública vive en un TXT bajo `selector._domainkey.tudominio`. El problema operativo es que el selector no se puede listar —DNS no permite enumerar subdominios—, así que lo adivinás o lo sacás de un mail real.

### Método rápido: leelo de un mail

Abrí cualquier correo que te haya mandado alguien de la empresa. En Gmail, "Mostrar original". Buscá la cabecera `DKIM-Signature`:

```
DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed;
    d=distribuidora-ejemplo.com.ar; s=selector1;
    h=From:Date:Subject:Message-ID:Content-Type:MIME-Version;
    bh=Xk9v...
```

`s=selector1` es el selector. `d=` es el dominio firmante: si no coincide con el del `From:`, DKIM no está **alineado** y para DMARC no cuenta, aunque la firma verifique.

Sin un mail a mano, probá los estándar: Microsoft 365 usa `selector1` y `selector2`; Google Workspace, `google`; SendGrid, `s1` y `s2`; Mailchimp, `k1`; Zoho, `zoho`. Amazon SES genera tres selectores con hash — sacalos de la consola.

### Verificar la longitud de la clave

Contá los caracteres del valor `p=`. Una clave RSA de **1024 bits** produce una `p=` de unos 216 caracteres; una de **2048 bits**, de unos 392. Es aritmética de base64 sobre el DER de la clave pública, no una estimación: sirve como regla de bolsillo sin herramientas.

¿Es grave estar en 1024? Amarillo, no rojo: nadie va a factorizar tu clave para mandar facturas falsas cuando comprar una credencial robada cuesta dos órdenes de magnitud menos. Pero 2048 es el default desde hace años y Microsoft 365 lo rota desde el portal de Defender. Hacelo cuando toques DNS por otra cosa.

### El error de rotación silenciosa

Es un error fácil de pasar por alto y caro de diagnosticar, porque el día que aparece no hay ningún cambio reciente que culpar.

Microsoft 365 publica **dos** CNAME de DKIM, `selector1` y `selector2`, y rota entre ellos por su cuenta. En una migración de DNS donde los registros se copian a mano es fácil llevarse `selector1` y dejar `selector2` atrás. Todo funciona. Hasta el día que Microsoft rota, empieza a firmar con `selector2`, el receptor busca la clave, no la encuentra, y **todo el correo saliente falla DKIM de golpe**. Si ese día tenías `p=reject` y el SPF además fallaba por reenvío, el correo desaparece, muchas veces sin rebote visible.

El chequeo es trivial: que resuelvan **los dos**.

```
dig +short CNAME selector1._domainkey.tudominio.com
dig +short CNAME selector2._domainkey.tudominio.com
```

Las dos tienen que devolver algo terminado en `.onmicrosoft.com`. Si una devuelve vacío, ya tenés una bomba de tiempo puesta. Este comando es el que yo pondría primero en cualquier checklist post-migración de DNS.

---

## 3. DMARC campo por campo

El registro va en `_dmarc.tudominio.com`, tipo TXT. Este es el mínimo con el que arrancás:

```
v=DMARC1; p=none; rua=mailto:dmarc@tudominio.com; fo=1
```

Anatomía:

- **`v=DMARC1`** — obligatorio, primero, en mayúsculas. Sin esto el registro se ignora entero.
- **`p=`** — la política: `none`, `quarantine` o `reject`. El único campo que hace algo.
- **`rua=`** — dónde llegan los reportes agregados (XML comprimido, uno por día por receptor). El campo que hace posible el rollout.
- **`ruf=`** — reportes forenses, mensaje por mensaje. **Casi nadie los manda**: los receptores grandes en general no los envían, porque el reporte forense incluye datos del mensaje. Ponelo, pero no bases tu plan en recibirlos.
- **`pct=`** — porcentaje al que se aplica la política. `pct=25` con `p=quarantine`: cuarentena al 25%, el resto pasa. Tu perilla de riesgo.
- **`sp=`** — política para subdominios. Si no lo ponés, heredan `p=`. Quien manda desde `facturacion.tudominio.com` queda cubierto por `sp=`, no por `p=`.
- **`adkim=` / `aspf=`** — alineación, `r` (relaxed, default) o `s` (strict). Relaxed, salvo que sepas exactamente por qué no.
- **`fo=1`** — reporte si falla SPF **o** DKIM, no solo si fallan los dos. El default `fo=0` es demasiado silencioso.
- **`ri=`** — intervalo de reportes en segundos. Default 86400, no lo toques.

La casilla de `rua` **no puede ser una cuenta que leas a mano**: recibe un XML por día por cada receptor grande. Buzón dedicado. Y si apunta a otro dominio, ese dominio tiene que autorizarlo publicando `tudominio.com._report._dmarc.otrodominio.com` con valor `v=DMARC1`. Es un paso fácil de omitir al contratar una plataforma de reporting, y falla en silencio: los reportes simplemente no llegan nunca.

---

## 4. El calendario de 8 semanas, con el criterio numérico de cada salto

Acá es donde los blogs de hosting te abandonan: explican la sintaxis, dicen "después pasá a reject" y se van. El salto es exactamente lo que rompe el correo, y necesita un criterio numérico. Uno solo: **el porcentaje de volumen legítimo que pasa DMARC alineado, medido sobre los reportes agregados de las últimas dos semanas**. No "ya revisé y está todo bien". Un número.

| Semana | Registro | Umbral para avanzar | Qué mirás |
|---|---|---|---|
| 1–2 | `p=none; rua=...; fo=1` | — | Inventario. Aparecen remitentes que no sabías que existían |
| 3–4 | `p=none` (sin cambios) | ≥ 95% alineado | Corregiste los remitentes rotos y el número subió |
| 5 | `p=quarantine; pct=25` | ≥ 98% alineado | 2 semanas seguidas arriba de 98, no un día bueno |
| 6 | `p=quarantine; pct=100` | ≥ 99% + cero reclamos internos | Que nadie del equipo diga "no me llegó" |
| 7 | `p=quarantine; sp=quarantine` | ≥ 99,5% | Subdominios adentro |
| 8 | `p=reject; sp=reject; -all` | ≥ 99,5% sostenido | Ahora sí, terminador SPF en `-all` |

Los umbrales son míos y los defiendo como criterio, no como hallazgo: no salen de una norma, salen de que el salto tiene que apoyarse en un número medido y no en una sensación.

Tres reglas que no negocio:

**Bajá el TTL antes de cada cambio.** TTL de `_dmarc` en 300 segundos, 48 horas antes de tocar la política. Si algo explota revertís en 5 minutos, en vez de esperar a que expire un TTL de 14400. Ojo, esto vale para `_dmarc`: para el TXT de SPF, Microsoft recomienda un mínimo de 3600 para evitar timeouts de resolución.

**Nunca cambies política un viernes.** Tampoco un jueves a la tarde. Martes a la mañana, con toda la semana por delante.

**El 0,5% que falta no es ruido.** Ese medio punto suele ser reenvíos de listas y casillas personales de empleados que se autoreenvían el trabajo. No lo vas a arreglar. Lo que **no** puede quedar ahí adentro es un sistema tuyo: si ves al ERP, no saltes.

Ocho semanas es el mínimo razonable para una PyME de 20 a 80 personas con tres o cuatro sistemas que mandan mail. Con doce integraciones, doce semanas. El único plazo que apura de verdad es externo: desde febrero de 2024 Google exige SPF, DKIM y DMARC a todo remitente de **más de 5.000 mensajes por día** hacia Gmail, con tasa de spam reportado por debajo del **0,3%**. Microsoft aplicó un requisito equivalente para Outlook.com, Hotmail y Live desde el **5 de mayo de 2025**, también sobre 5.000 mensajes diarios, y el incumplimiento no es filtrado sino rechazo en el SMTP: `550 5.7.15 Access denied, sending domain does not meet the required authentication level`.

---

## 5. Leer los reportes agregados sin pagar una herramienta

Llegan como adjunto `.zip` o `.gz` con un XML adentro. La estructura relevante es una secuencia de bloques `record`. Un bloque de ejemplo, con el caso más típico que vas a encontrar en tu semana 1:

```xml
<record>
  <row>
    <source_ip>149.72.11.204</source_ip>
    <count>187</count>
    <policy_evaluated>
      <disposition>none</disposition>
      <dkim>fail</dkim>
      <spf>pass</spf>
    </policy_evaluated>
  </row>
  <identifiers>
    <header_from>distribuidora-ejemplo.com.ar</header_from>
  </identifiers>
</record>
```

Traducción: 187 mensajes desde una IP de SendGrid, SPF alineado, DKIM fallando. SendGrid manda por vos pero no firma con tu dominio. Se arregla activando "domain authentication" ahí, no tocando SPF. Con un puñado de reportes, esto alcanza:

```
for f in *.xml; do
  xmllint --xpath '//record/row/source_ip/text()' "$f" 2>/dev/null
  echo
done | sort | uniq -c | sort -rn
```

Te da el ranking de IPs que mandan como vos. Cruzá cada una contra tu inventario. Las que no reconocés son de dos tipos: un sistema tuyo que nadie documentó, o alguien suplantándote. Mi apuesta, y es una apuesta: casi siempre es lo primero, y el hallazgo interesante del rollout no es el atacante sino el inventario. Si preferís algo visual y gratis, subí los XML al analizador de dmarcian o MXToolbox — para un dominio de PyME con volumen bajo no veo motivo para pagar una plataforma en el primer rollout.

---

## 6. Los seis remitentes que suelen romper el rollout

Aparecen en la semana 3, cuando los reportes empiezan a mostrar remitentes que nadie había inventariado. Ninguno sale en la documentación de tu proveedor de DNS. La lista es mi criterio sobre dónde mirar primero, ordenada por daño esperado, no un ranking medido:

| Remitente | Qué hace mal | Corrección |
|---|---|---|
| **Facturación electrónica** | Manda comprobantes con tu dominio por su propio SMTP | Pedí el `include:` del proveedor. Si no lo tiene, subdominio dedicado tipo `cpe.tudominio.com` con SPF propio |
| **CRM** (HubSpot, Zoho, Pipedrive) | Manda "en nombre de" el vendedor sin firmar DKIM con tu dominio | Activá el envío autenticado con DKIM propio en la configuración de la plataforma. Suele existir y suele venir apagado: verificalo en la tuya |
| **Plataforma de mailing** | El `From:` es tuyo, el Return-Path es del proveedor | Verificá el dominio en la plataforma: genera un CNAME de DKIM propio |
| **Impresora multifunción** | Escanea a mail por SMTP directo, sin auth, con `From:` del dominio | Autenticá contra el relay corporativo. Si el firmware no soporta TLS, `From:` de un dominio interno |
| **ERP** | Manda remitos desde el servidor on-premise con la IP fija de la oficina | `ip4:` de esa IP en el SPF, y si podés, relay autenticado |
| **Proveedor de nómina** | Manda recibos de sueldo desde su plataforma | Subdominio delegado. Ojo con `sp=` |

El patrón: **la solución correcta casi nunca es agregar otro `include`**, porque cada uno te acerca al límite de 10 lookups. Lo escalable es mover ese remitente a un **subdominio dedicado** con su propio SPF y su propio DKIM, cubierto por `sp=`. No es opinión mía: Microsoft lo documenta como estrategia explícita para reducir lookups, y la razón técnica es que **cada subdominio tiene su propio presupuesto de 10**. La raíz se mantiene corta y auditable.

Si tuviera que apostar a cuál de los seis te rompe el rollout, apuesto a la impresora, y el motivo es de diseño más que de estadística: se configura una sola vez, con un remitente del dominio para que los PDFs caigan en el hilo correcto; manda SMTP plano desde la IP de la oficina; y no figura en ningún inventario porque no es un sistema, es un mueble. Cuando pasás a `p=reject` los escaneos dejan de llegar, y el que primero lo nota es el que estaba esperando el comprobante. Resolvelo en la semana 3, no en la 8.

---

## 7. Dónde termina DMARC y empieza el producto

DMARC resuelve un problema y solo uno: **que alguien mande correo poniendo tu dominio en el `From:` visible**. Es enorme y hay que hacerlo. Pero esto sigue pasando después de `p=reject`:

- **BEC desde una cuenta legítima comprometida.** El atacante entró al Microsoft 365 del gerente de compras y manda desde la cuenta real, por la infraestructura real. SPF pasa, DKIM pasa, DMARC pasa. El mail es criptográficamente perfecto y pide cambiar el CBU de un proveedor. Es auténtico: el problema es de identidad, no de dominio. Por eso hay que atacarlo del otro lado, con [MFA bien configurado en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) y entendiendo por qué [tener MFA activado no alcanza contra el robo de sesión](/guia/ya-tenes-mfa-y-no-alcanza).
- **Dominios lookalike.** `tuempresa-sa.com`, `rn` en lugar de `m`. El atacante registra un dominio propio, le pone SPF, DKIM y DMARC impecables, y pasa todo. Tu DMARC no aplica a un dominio que no es tuyo.
- **QR phishing.** El cuerpo es una imagen con un código QR y cero URLs analizables. El usuario lo escanea con el teléfono personal, fuera de tu control.
- **Proveedores comprometidos.** El mail viene de tu proveedor real, con su dominio real y su DMARC en reject, porque le entraron a él. El [DBIR 2026 de Verizon](https://www.verizon.com/business/resources/reports/dbir/) cuenta un tercero involucrado en el **48% de las brechas**, un salto del 60% contra el año anterior. Tu política no alcanza a la infraestructura de otro.

Contra eso necesitás análisis de contenido y comportamiento, no un registro DNS. Un trabajo de Barracuda con la Universidad de Columbia y la Universidad de Chicago, aceptado en ACM IMC 2025, midió sobre correo detectado entre febrero de 2022 y abril de 2025 que el **51% del spam** ya se genera con IA. La redacción impecable dejó de ser señal de legitimidad, que es más o menos el único criterio con el que entrenamos a los usuarios durante veinte años.

Las tres opciones de la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes) atacan capas distintas. Si ya estás en Business Premium o E5, [Microsoft Defender for Office 365](/producto/microsoft-defender-for-office-365) lo tenés adentro y el add-on Plan 1 suelto figura en **USD 2 por usuario al mes** de lista: es el punto de partida obvio. Cuál de estos motores detecta mejor el fraude dirigido no lo voy a rankear acá: no corrí una prueba comparativa y no conozco una pública que sea independiente. [Mimecast](/producto/mimecast-email-security) suma archivado con retención legal inmutable y continuidad durante caídas de Microsoft 365. No publica tarifa: las bandas que circulan salen de agregadores de contratos reales —Vendr ubica los bundles base entre USD 40 y 80 por usuario al año— y la implementación se compra aparte, con plazos objetivo publicados por el propio fabricante de 30 días en Guided y 45 en Managed. Eso hay que presupuestarlo como horas, no como licencia.

Y [Proofpoint](/producto/proofpoint-email-protection), que se posiciona en el segmento de ataques dirigidos y es el de gama más alta de los tres, con la trampa de que son dos productos: Essentials tiene lista publicada por partners —de USD 3,93 a USD 9,42 por usuario/mes— y Enterprise no publica precio ni mínimos, se cotiza. Para una PyME de 15 personas sin perfil de objetivo, Enterprise está sobredimensionado. Lo diría igual delante del vendedor. Las fuentes de cada cifra están en la [comparativa](/comparativa/comparativa-seguridad-email-pymes).

El directorio completo está en [productos de email y antiphishing](/productos/email-y-antiphishing), y el orden por capas de todo el stack, en la [guía de ciberseguridad para PyMEs en LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

Una última que se olvida siempre: DMARC no te devuelve un buzón borrado. La retención nativa de Microsoft 365 tiene límites que la mayoría descubre tarde — lo desarmé en [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta).

### Hasta dónde llega esta guía

Todo lo de arriba se apoya en RFCs, documentación de producto y requisitos publicados por los grandes receptores. No hay acá mediciones propias de entregabilidad, ni datos de tráfico de ningún dominio, ni pruebas de laboratorio: es una checklist de auditoría y un criterio de decisión, y funciona en la medida en que la corras vos sobre tus propios datos.

Los umbrales del calendario están pensados para un dominio corporativo de PyME con volumen bajo. Arriba de unos 100.000 mensajes diarios, o con varios dominios de marca y agencias mandando por vos, el problema deja de ser DNS y pasa a ser gobierno de remitentes: ahí hacen falta alertas automáticas ante fuentes nuevas y un proceso de alta de remitentes, y eso no lo cubre esta guía.

---

## Preguntas frecuentes

### Mis correos llegan a spam, ¿esto lo arregla?

En parte. La falta de autenticación es de las causas más comunes de que un dominio legítimo caiga en spam, y desde 2024 es directamente motivo de rechazo en Gmail y en Outlook.com para remitentes de más de 5.000 mensajes diarios. Pero si además tu IP está en listas negras o tu tasa de quejas supera el 0,3% que exige Gmail, la autenticación sola no alcanza. Corré primero los cuatro `dig`: si `_dmarc` está vacío, empezá por ahí, es la causa más barata de descartar.

### ¿Puedo pasar directo a p=reject si mi empresa es chica?

Podés, y aun así no lo haría a ciegas. El tamaño de la empresa no predice la cantidad de sistemas que mandan mail con tu dominio: entre facturación electrónica, CRM, plataforma de mailing, ERP e impresora, ocho personas pueden tener cinco remitentes distintos, y basta con que uno esté mal configurado para que el salto corte algo que factura. El mínimo son dos semanas en `p=none` leyendo reportes. Si a las dos semanas te dan 100% alineado y reconocés todas las IPs, saltá a `p=reject` y ahorrate el resto del calendario: el calendario existe para reducir riesgo, no por ritual.

### ¿Necesito una herramienta paga de DMARC?

Para un dominio con volumen de PyME, no. Un buzón dedicado para `rua`, `xmllint` y un analizador gratuito alcanzan. La herramienta paga se justifica con varios dominios, con alertas automáticas ante fuentes nuevas, o cuando alguien externo audita el proceso.

### ¿DMARC me protege de que me suplanten el dominio?

Con `p=reject`, sí, para el caso exacto de alguien poniendo tu dominio en el `From:` visible. No te protege de dominios parecidos (`tuempresa-sa.com`), ni de correos enviados desde una cuenta tuya comprometida, ni de códigos QR maliciosos. Esas tres necesitan un producto por encima de la autenticación, y están en la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes).

### ¿Qué hago si mi proveedor de facturación electrónica no soporta DKIM?

Movés ese flujo a un subdominio dedicado. Creás `cpe.tudominio.com`, publicás ahí un SPF con el `include:` del proveedor y ponés el `From:` en `comprobantes@cpe.tudominio.com`. La raíz queda limpia y en `p=reject`, el subdominio bajo su propia política, y no gastás ninguno de tus 10 lookups: cada subdominio tiene su propio presupuesto.
