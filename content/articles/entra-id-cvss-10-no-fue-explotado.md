---
title: "CVE-2026-69836: el 10.0 de Entra ID no fue explotado"
subtitle: Microsoft publicó CVE-2026-69836 marcada como explotada el 20 de agosto y corrigió esa marca al día siguiente. La corrección viajó menos que el titular.
excerpt: Un CVSS 10.0 en el sistema de identidad de Microsoft 365, sin nada que parchear y sin explotación confirmada. Cómo verificarlo vos mismo.
type: news
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-08-22
updated: 2026-08-22
products:
  - microsoft-entra-id
  - okta-workforce-identity
  - cisco-duo
  - yubikey-5-series
meta_title: "CVE-2026-69836: el 10.0 de Entra ID no fue explotado"
meta_description: "CVE-2026-69836 salió con CVSS 10.0 y marca de explotación activa. Microsoft la corrigió a las 24 horas. Qué hace una PyME con Microsoft 365."
---

El 20 de agosto Microsoft publicó [CVE-2026-69836](https://msrc.microsoft.com/update-guide/vulnerability/CVE-2026-69836), una falla de ejecución remota de código en Entra ID con puntaje 10.0, el máximo de la escala. El aviso salió marcado como explotado en la vida real. El 21 de agosto Microsoft revisó el mismo aviso y anotó el cambio en el historial: *"Corrected **Exploited** to **No**. This vulnerability was not exploited in the wild. This is an informational change only."*

Entra ID es el sistema de identidad detrás de Microsoft 365 y Azure. Es la puerta por la que entra todo el mundo en la empresa, así que un 10.0 ahí es la clase de titular que hace que alguien un viernes a la tarde pregunte qué hay que parchear. La respuesta es nada, y el motivo es más útil que la noticia.

| Dato | Valor | Fuente |
|---|---|---|
| Identificador | [CVE-2026-69836](https://nvd.nist.gov/vuln/detail/CVE-2026-69836) | MSRC |
| Producto | Microsoft Entra ID | MSRC |
| Tipo | Deserialización de datos no confiables ([CWE-502](https://cwe.mitre.org/data/definitions/502.html)) | MSRC y NVD |
| Puntaje base | 10.0, crítico | Microsoft |
| Puntaje temporal | 8.7 | Microsoft |
| Explotado | No, corregido el 21 de agosto | MSRC, revisión 1.1 |
| Divulgado públicamente | No | MSRC |
| Acción del cliente | Ninguna | MSRC |
| En el catálogo de CISA | No | Catálogo KEV, versión 2026.08.21 |

El 10.0 lo asignó Microsoft, no NVD. Al 22 de agosto la entrada en NVD sigue en estado *Undergoing Analysis*, que significa que NVD todavía no publicó su propia evaluación. Cuando el número lo pone el fabricante y no NVD, los puntajes de fallas distintas no son estrictamente comparables.

Un 10.0 llama la atención porque es el techo de la escala. Pero el puntaje mide el peor caso teórico, y nada de lo que convierte ese número en urgencia está presente acá:

```svg
<svg viewBox="0 0 660 195" role="img" aria-label="CVE-2026-69836 tiene puntaje base 10.0, el techo de la escala, pero no fue explotado, quedó corregido el 21 de agosto y no requiere ninguna acción del cliente según Microsoft">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">El puntaje alto, y lo que no lo acompaña</text>

  <rect x="20" y="46" width="196" height="96" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="46" width="196" height="96" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="118" y="70" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">Puntaje base</text>
  <text x="118" y="103" text-anchor="middle" font-size="27" font-weight="700" fill="#e23a3a">10.0</text>
  <text x="118" y="125" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">el techo de la escala</text>

  <rect x="232" y="46" width="196" height="96" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="232" y="46" width="196" height="96" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.5"/>
  <text x="330" y="70" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">¿Fue explotado?</text>
  <text x="330" y="103" text-anchor="middle" font-size="27" font-weight="700" fill="currentColor">No</text>
  <text x="330" y="125" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">corregido el 21 de agosto</text>

  <rect x="444" y="46" width="196" height="96" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="444" y="46" width="196" height="96" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.5"/>
  <text x="542" y="70" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">¿Acción del cliente?</text>
  <text x="542" y="103" text-anchor="middle" font-size="21" font-weight="700" fill="currentColor">Ninguna</text>
  <text x="542" y="125" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">según MSRC</text>

  <text x="20" y="176" font-size="11.5" font-weight="600" fill="currentColor">El puntaje mide el peor caso posible, no lo que está pasando</text>
</svg>
```

La marca de explotación es el factor que más pesa de los tres, y estuvo mal puesta durante un día:

```svg
<svg viewBox="0 0 660 250" role="img" aria-label="Línea de tiempo de 48 horas: el 20 de agosto Microsoft publica el aviso marcado como explotado, ese día y el siguiente se replica en la prensa, y el 21 de agosto Microsoft corrige la marca a no explotado">
  <line x1="60" y1="150" x2="600" y2="150" stroke="currentColor" stroke-width="2" opacity="0.55"/>

  <circle cx="90" cy="150" r="7" fill="#e23a3a"/>
  <text x="90" y="126" text-anchor="middle" font-size="12" font-weight="700" fill="currentColor">20 ago</text>
  <text x="90" y="176" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">Aviso publicado</text>
  <text x="90" y="192" text-anchor="middle" font-size="11" fill="#e23a3a" font-weight="700">Exploited: Yes</text>

  <circle cx="330" cy="150" r="7" fill="currentColor" opacity="0.6"/>
  <text x="330" y="126" text-anchor="middle" font-size="12" font-weight="700" fill="currentColor">20 y 21 ago</text>
  <text x="330" y="176" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">Se replica el titular</text>
  <text x="330" y="192" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">"explotado en la vida real"</text>

  <circle cx="570" cy="150" r="7" fill="currentColor"/>
  <text x="570" y="126" text-anchor="middle" font-size="12" font-weight="700" fill="currentColor">21 ago</text>
  <text x="570" y="176" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.85">Revisión 1.1</text>
  <text x="570" y="192" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Exploited: No</text>

  <text x="330" y="42" text-anchor="middle" font-size="13" font-weight="700" fill="currentColor">El dato cambió a las 24 horas</text>
  <text x="330" y="66" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.8">El historial de revisiones del aviso es público y se consulta por API</text>
  <text x="330" y="230" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.75">La corrección no se replicó con la misma velocidad que el aviso original</text>
</svg>
```

---

## ¿Por qué Microsoft no pide ninguna acción por CVE-2026-69836?

Entra ID es un servicio que corre en la infraestructura de Microsoft. La falla se corrigió del lado de Microsoft antes de que se publicara el aviso. No hay paquete de actualización, no hay artículo de KB y no hay opción de configuración que cambiar.

El aviso lo dice en su sección de preguntas: *"This vulnerability has already been fully mitigated by Microsoft. There is no action for users of this service to take. The purpose of this CVE is to provide further transparency."*

Desde [junio de 2024](https://www.microsoft.com/en-us/msrc/blog/2024/06/toward-greater-transparency-unveiling-cloud-service-cves1) Microsoft publica CVE de sus servicios en la nube aunque el cliente no tenga que instalar nada. Antes, una falla así se corregía en silencio y nadie se enteraba. La política es mejor que la anterior y tiene un efecto secundario: la lista de CVE de Microsoft ahora mezcla dos cosas que se leen igual y se responden distinto.

| | CVE de software propio | CVE de servicio en la nube |
|---|---|---|
| Ejemplo | [Zimbra, TrueConf, MLflow](/noticia/zimbra-trueconf-software-autoalojado-parche-propio) | Entra ID, CVE-2026-69836 |
| Quién corrige | Vos | El proveedor, antes del aviso |
| Cuándo | Cuando actualizás | Ya está hecho |
| Qué mirar | Versión instalada y exposición | El campo "acción del cliente" |

Un CVE de servicio en la nube no es una alerta: es un informe de algo ya cerrado.

---

## ¿Quién puede ignorar el aviso de CVE-2026-69836?

A ninguna PyME que use Microsoft 365, que es lo mismo que decir a nadie que esté leyendo esto. No hay versión afectada que revisar, porque no corrés vos ninguna versión de Entra ID. No hay indicadores de compromiso que buscar, porque Microsoft no publicó ninguno. Y no hay nada que cerrar.

Hay un límite en lo que se puede verificar. Que la falla no fue explotada es la evaluación de Microsoft sobre su propio servicio, y un cliente no tiene forma independiente de confirmarla. No se publicó telemetría ni detalle técnico. Lo que sí se puede confirmar desde afuera es lo otro: que el aviso cambió, cuándo cambió y que la falla no está en el catálogo de explotación activa de CISA.

---

## ¿Cómo verifico el estado real de un CVE de Microsoft?

Son cuatro chequeos, sirven para cualquier CVE de Microsoft y no dependen de que nadie haya actualizado un titular.

**El aviso, en crudo.** La guía de actualizaciones de Microsoft tiene una API pública que devuelve el estado sin pasar por la página:

```bash
curl -s "https://api.msrc.microsoft.com/sug/v2.0/en-US/vulnerability/CVE-2026-69836"
```

Los campos que deciden son tres: `exploited`, `publiclyDisclosed` y `customerActionRequired`. En este caso devuelven `No`, `No` y `false`.

**El historial de revisiones.** La misma respuesta trae el arreglo `revisions`. Ahí figura la revisión 1.1 del 21 de agosto con el texto de la corrección. Un aviso que cambió de versión es un aviso que hay que releer.

**El catálogo de explotación activa.** Si una falla se está explotando contra organizaciones reales, termina en el catálogo de CISA con fecha de vencimiento. El archivo se consulta directo:

```bash
curl -s https://www.cisa.gov/sites/default/files/feeds/known_exploited_vulnerabilities.json | grep CVE-2026-69836
```

Sin resultado, al 22 de agosto.

**El puntaje, con su autor.** En [NVD](https://nvd.nist.gov/vuln/detail/CVE-2026-69836) figura quién asignó el número. Si dice `secure@microsoft.com`, lo puso Microsoft. El vector temporal que publicó Microsoft termina en `E:U/RL:O/RC:C`: código de explotación no probado, corrección oficial disponible, informe confirmado.

Los cuatro juntos llevan menos tiempo que leer la nota que los resume.

---

## ¿Qué hago si uso Microsoft 365 o Entra ID?

1. **Con esta falla, nada.** Cerrala como leída.
2. **Guardá el procedimiento de los cuatro chequeos.** Es lo que convierte el próximo titular con puntaje máximo en una decisión de dos minutos.
3. **Separá tus CVE en dos listas.** Los productos que corrés vos exigen ventana de parcheo; los servicios contratados exigen leer el aviso. Confundirlos gasta el poco tiempo que hay. La tanda de [Zimbra, TrueConf y MLflow](/noticia/zimbra-trueconf-software-autoalojado-parche-propio) del 19 al 21 de agosto es de la primera lista y sí tenía plazo.
4. **Revisá qué depende de tu proveedor de identidad.** Si [Entra ID](/producto/microsoft-entra-id) autentica el correo, los archivos y tres aplicaciones más, eso es concentración de riesgo aunque esta falla no haya sido explotada. La pregunta que importa es qué pasa el día que el servicio no responde, y ahí entra tener [copia propia de Microsoft 365](/guia/backup-microsoft-365-hace-falta).
5. **Reforzá la identidad por donde sí se ataca.** Contra el proveedor de identidad los incidentes reales de una PyME casi nunca son un 10.0 en la nube: son credenciales robadas y sesiones secuestradas. El mecanismo está en [cómo funciona el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa), y qué falta cuando ya tenés segundo factor, en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza).

Los términos están definidos en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre CVE-2026-69836 en Entra ID

### Entonces, ¿el 10.0 era falso?

No. El puntaje describe qué tan grave sería la falla si alguien la explotara: sin autenticarse, sin interacción del usuario y con impacto total. Eso no cambió. Lo que se corrigió es otra cosa: si alguien la explotó. Gravedad potencial y explotación real son dos campos distintos del mismo aviso, y el titular los fundió en uno.

### ¿Por qué Microsoft publica un CVE si no hay nada que hacer?

Por transparencia declarada. Desde junio de 2024 emite CVE de servicios en la nube aunque el cliente no tenga que instalar nada, con el argumento de que compartir las fallas encontradas y resueltas permite que la industria aprenda. La contrapartida es la que se vio acá: avisos sin acción posible que circulan con el mismo formato que los que sí la exigen.

### Si mi proveedor de seguridad me mandó una alerta por esto, ¿qué le respondo?

Que el aviso fue corregido el 21 de agosto y que el campo de acción del cliente está en falso, con el enlace al aviso. Si la alerta llegó después de esa fecha sin mencionar la corrección, es señal de que el proveedor replica titulares sin verificar la fuente. Para un cliente que te pregunta a vos, el criterio para responder está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

### ¿Conviene depender de un solo proveedor de identidad?

Depende de con qué se compara. Alternativas como [Okta](/producto/okta-workforce-identity) mueven la concentración de lugar, no la eliminan: seguís teniendo un proveedor que autentica todo. Lo que sí reduce el daño es que la identidad no sea el único control, que exista copia de los datos fuera del mismo proveedor y que el segundo factor resista el robo de sesión.
