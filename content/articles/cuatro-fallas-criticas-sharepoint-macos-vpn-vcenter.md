---
title: "Cuatro fallas críticas nuevas: SharePoint, macOS, VPN y vCenter"
subtitle: El lote del 18 de agosto invierte el patrón de la semana pasada: lo explotado está en lo que se usa todos los días. El plazo que fija CISA es de tres días.
excerpt: CISA sumó cuatro vulnerabilidades críticas al catálogo de explotación activa. A diferencia del lote anterior, tres de cuatro están en el escritorio y el correo.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-19
updated: 2026-08-20
products:
  - microsoft-defender-for-office-365
  - microsoft-defender-for-endpoint-p2
  - cloudflare-access
  - veeam-data-platform
meta_title: "Cuatro fallas críticas: SharePoint, macOS, VPN y vCenter"
meta_description: "CISA sumó cuatro vulnerabilidades críticas explotadas activamente. Esta vez están en lo que tu gente usa todos los días. Qué son y qué hacer, con plazos."
---

El 18 de agosto el [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) sumó cuatro entradas. Las cuatro son críticas, de 9,1 a 9,8. **Tres de las cuatro están en productos que se usan todos los días**, no en la sala de servidores.

Hace dos días publicamos que, de las diez fallas explotadas en lo que iba de agosto, [nueve estaban en la capa de administración](/noticia/fallas-explotadas-agosto-2026-capa-administracion). Ese patrón se invirtió.

| CVE | Producto | Qué es | Puntaje |
|---|---|---|---|
| [CVE-2026-55040](https://nvd.nist.gov/vuln/detail/CVE-2026-55040) | Microsoft SharePoint | Autenticación débil: saltea un control de seguridad por red, sin autenticarse | 9,1 (Microsoft) |
| [CVE-2026-59310](https://nvd.nist.gov/vuln/detail/CVE-2026-59310) | VMware vCenter | Path traversal en el servidor de Syslog, con ejecución de código | 9,8 (VMware) |
| [CVE-2026-33824](https://nvd.nist.gov/vuln/detail/CVE-2026-33824) | Windows, extensión IKE | Doble liberación de memoria: ejecución de código por red sin autenticarse | 9,8 (Microsoft) |
| [CVE-2026-65400](https://nvd.nist.gov/vuln/detail/CVE-2026-65400) | Apple macOS | Falla de autenticación, corregida con mejor gestión de estado | 9,8 |

Los puntajes salen de NVD, con quién los asignó anotado en cada caso: son evaluaciones del fabricante, no de NVD. Cuando ambos puntúan, suelen diferir.

---

## Por qué estas fallas están en el escritorio y no en el servidor

SharePoint no es infraestructura: es donde el equipo guarda los documentos. Si tenés Microsoft 365 tenés SharePoint, lo uses conscientemente o no, porque es lo que hay debajo de Teams y de los archivos compartidos. Una falla de autenticación débil ahí, explotable por red y sin credenciales, alcanza a cualquier empresa con un tenant.

macOS es la notebook del diseñador o del contador. La extensión IKE de Windows negocia los túneles IPsec, o sea la VPN: ejecución de código por red sin autenticarse contra el componente que atiende conexiones entrantes es la peor combinación posible.

Solo vCenter, la consola que administra máquinas virtuales, pertenece a la categoría de la semana pasada.

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Comparación entre el lote del 17 de agosto, con nueve de diez fallas en la capa de administración, y el del 18, con tres de cuatro en el escritorio y el correo">
  <text x="20" y="24" font-size="13" font-weight="700" fill="currentColor" opacity="0.75">Lote del 17 de agosto</text>
  <rect x="20" y="36" width="558" height="34" rx="4" fill="currentColor" opacity="0.16"/>
  <rect x="20" y="36" width="62" height="34" rx="4" fill="currentColor" opacity="0.45"/>
  <text x="299" y="59" text-anchor="middle" font-size="13" font-weight="700" fill="currentColor">9 de 10 en administración</text>
  <text x="592" y="59" font-size="12" fill="currentColor" opacity="0.7">1 escritorio</text>

  <text x="20" y="118" font-size="13" font-weight="700" fill="#e23a3a">Lote del 18 de agosto</text>
  <rect x="20" y="130" width="558" height="34" rx="4" fill="#e23a3a" opacity="0.22"/>
  <rect x="20" y="130" width="418" height="34" rx="4" fill="#e23a3a"/>
  <text x="229" y="153" text-anchor="middle" font-size="13" font-weight="700" fill="#ffffff">3 de 4 en el escritorio y el correo</text>
  <text x="508" y="153" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.8">1 admin</text>

  <text x="20" y="196" font-size="12" fill="currentColor" opacity="0.85">SharePoint · macOS · extensión IKE de Windows</text>
  <text x="20" y="216" font-size="12" fill="currentColor" opacity="0.55">vCenter queda del lado de la administración</text>
  <text x="20" y="240" font-size="12" font-weight="600" fill="currentColor">Conclusión: no hay una sola capa que alcance con cuidar</text>
</svg>
```

La lección no es que ahora el riesgo esté en el escritorio, sino que **el patrón de una semana no es una estrategia**. Se cubren las dos capas y se decide por exposición.

---

## CVE-2026-33824: una falla de abril que recién ahora es urgente

[CVE-2026-33824](https://nvd.nist.gov/vuln/detail/CVE-2026-33824), la de la extensión IKE, se publicó el **14 de abril de 2026** y entró al catálogo de explotación activa el **18 de agosto**.

Cuatro meses con parche disponible y una falla de 9,8 que no era urgente porque nadie la estaba explotando. El 18 de agosto pasó a estarlo y el plazo se volvió de tres días.

```svg
<svg viewBox="0 0 660 195" role="img" aria-label="CVE-2026-33824 se publicó el 14 de abril de 2026 con parche disponible y sin urgencia, y estuvo así cuatro meses hasta que el 18 de agosto entró al catálogo de explotación activa y el plazo pasó a ser de tres días">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">CVE-2026-33824: cuatro meses de calma, tres días de plazo</text>

  <text x="325" y="60" text-anchor="middle" font-size="11" font-weight="600" fill="currentColor" opacity="0.85">Cuatro meses con parche disponible y sin urgencia</text>
  <path d="M90 72 L90 80 M90 76 L560 76 M560 72 L560 80" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>

  <rect x="90" y="96" width="470" height="28" fill="currentColor" opacity="0.1"/>
  <rect x="560" y="96" width="60" height="28" fill="#e23a3a" opacity="0.2"/>
  <path d="M40 110 L630 110" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <circle cx="90" cy="110" r="5.5" fill="currentColor"/>
  <path d="M90 118 L90 134" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="90" y="148" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">14 de abril</text>
  <text x="90" y="164" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">se publica, con parche</text>

  <circle cx="560" cy="110" r="5.5" fill="#e23a3a"/>
  <path d="M560 118 L560 134" stroke="#e23a3a" stroke-width="1" opacity="0.6"/>
  <text x="560" y="148" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">18 de agosto</text>
  <text x="560" y="164" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">al catálogo: 3 días</text>

  <text x="20" y="188" font-size="11.5" font-weight="600" fill="currentColor">El parche existía desde abril. Lo que cambió fue la urgencia, no la falla.</text>
</svg>
```

Esa es la trampa de priorizar solo por lo que está bajo ataque hoy: **esa lista cambia sin aviso, y cuando cambia ya no hay tiempo.** Una falla vieja no es una falla segura.

Los plazos que CISA fija a los organismos federales para estas cuatro vencen el **21 de agosto**. La acción requerida del catálogo cita como base la directiva **BOD 26-04, "Prioritizing Security Updates Based on Risk"**. No pudimos abrir esa página desde acá —el sitio devuelve un error de acceso—, así que no describimos su contenido. Lo verificable, porque está en el feed del catálogo, es que ese es el marco que invocan y que el plazo son tres días.

---

## ¿A quién afectan SharePoint, macOS, la VPN de Windows y vCenter?

**Casi seguro:**

- **Microsoft 365**, porque incluye SharePoint. Es la que más empresas alcanza de las cuatro.
- **Windows con VPN IPsec** terminando en un servidor propio.

**Si aplica:**

- **Macs.** Apple corrigió la falla en **macOS Sequoia 15.7.9, Sonoma 14.8.9 y Tahoe 26.6.1**. Versiones anteriores, actualizar.
- **vCenter**, si administrás virtualización propia. Si todo está en la nube de un tercero, no aplica.

**No te toca** si no tenés Microsoft 365, ni Macs, ni VPN IPsec propia, ni VMware. Perseguir cada CVE que aparece en las noticias es la forma más rápida de agotar a la única persona que se ocupa de esto en una PyME.

---

## ¿Qué hago si uso Microsoft 365, Macs o VMware?

1. **SharePoint primero**, por alcance. En Microsoft 365 la corrección la aplica Microsoft del lado del servicio; lo que te toca es revisar que no tengas una instalación propia de SharePoint Server sin parchear.
2. **Actualizá los Macs** a las versiones de arriba. Es una tarde y cierra una falla de 9,8.
3. **Revisá la exposición de la VPN.** Si tenés IPsec terminando en Windows, aplicá el parche de abril y, si nadie usa ese acceso, apagalo. Sacar de internet lo que no se usa rinde más que parchearlo: el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn), y alternativas por identidad como [Cloudflare Access](/producto/cloudflare-access) evitan tener un servicio publicado esperando el próximo CVE.
4. **Buscá señales de compromiso, no solo parchees.** Cuando una falla lleva meses con parche y recién ahora se confirma explotación, la pregunta no es solo "¿ya lo cerré?" sino "¿alguien entró antes?". Revisá inicios de sesión anómalos y accesos raros a SharePoint; el tipo de señal está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).
5. **Verificá que tus copias sobrevivan a un administrador comprometido.** [Veeam Data Platform](/producto/veeam-data-platform) y la [categoría de backup](/productos/backup-y-recuperacion) cubren la inmutabilidad.

Los puntos 3 y 4 son los que más rinden. Parchear es una carrera mensual; reducir lo publicado y saber leer los registros son capacidades que quedan.

La [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) ordena las prioridades, y los términos de esta nota están en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes

### Tengo Microsoft 365, ¿tengo que hacer algo con lo de SharePoint?

En el servicio en la nube la corrección la aplica Microsoft. Lo que te corresponde revisar es si además tenés una instalación propia de SharePoint Server —muchas PyMEs heredaron una y la olvidaron—, porque ahí el parche es tuyo. En cualquier caso, mirá los accesos anómalos recientes.

### ¿Por qué una falla publicada en abril entra al catálogo recién en agosto?

Porque el catálogo no lista fallas graves sino fallas con evidencia de explotación activa. Entre abril y agosto existía el parche pero no había constancia de uso; el 18 de agosto sí. Es el argumento más fuerte para no esperar a que algo esté bajo ataque: cuando ese momento llega, el plazo pasa a ser de días.

### Los puntajes dicen "según Microsoft" o "según VMware". ¿Son menos confiables?

Menos comparables, no menos confiables. En estas cuatro puntuó el fabricante y no NVD. Cuando ambos evalúan la misma falla los números difieren, a veces por casi un punto, porque parten de supuestos distintos. Conviene decidir por presencia en el catálogo y por exposición del producto, no por decimales.

### Tengo Macs pero nadie los administra centralmente. ¿Cómo sé si están actualizados?

No lo sabés, y ese es el problema de fondo. Sin una consola que muestre el parque completo, cada equipo depende de que su usuario acepte la actualización. Es el argumento de la guía sobre [cuándo se justifica el salto de antivirus a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme): lo que se compra no es solo detección, es visibilidad.
