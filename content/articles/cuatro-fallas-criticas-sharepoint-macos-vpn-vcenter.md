---
title: "Cuatro fallas críticas nuevas: SharePoint, macOS, VPN y vCenter"
subtitle: El lote del 18 de agosto da vuelta el patrón de la semana pasada. Ahora lo explotado está en lo que tu gente usa todos los días, y el plazo que fija CISA es de tres días.
excerpt: CISA sumó cuatro vulnerabilidades críticas al catálogo de explotación activa. A diferencia del lote anterior, tres de cuatro están en el escritorio y el correo, no en la sala de servidores.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-19
updated: 2026-08-19
products:
  - microsoft-defender-for-office-365
  - microsoft-defender-for-endpoint-p2
  - cloudflare-access
  - veeam-data-platform
meta_title: "Cuatro fallas críticas: SharePoint, macOS, VPN y vCenter"
meta_description: "CISA sumó cuatro vulnerabilidades críticas explotadas activamente. Esta vez están en lo que tu gente usa todos los días. Qué son y qué hacer, con plazos."
---

El 18 de agosto el [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) sumó cuatro entradas nuevas. Las cuatro son **críticas**, con puntajes de 9,1 a 9,8. Y a diferencia del lote de la semana pasada, esta vez no están escondidas en la sala de servidores.

Hace dos días publicamos que, de las diez fallas explotadas en lo que iba de agosto, [nueve estaban en la capa de administración y solo una en el escritorio](/noticia/fallas-explotadas-agosto-2026-capa-administracion). Ese patrón se acaba de dar vuelta: **tres de estas cuatro están en cosas que tu gente abre todos los días.**

| CVE | Producto | Qué es | Puntaje |
|---|---|---|---|
| [CVE-2026-55040](https://nvd.nist.gov/vuln/detail/CVE-2026-55040) | Microsoft SharePoint | Autenticación débil: permite saltear un control de seguridad por red, sin autenticarse | 9,1 (Microsoft) |
| [CVE-2026-59310](https://nvd.nist.gov/vuln/detail/CVE-2026-59310) | VMware vCenter | Path traversal en el servidor de Syslog, con ejecución de código | 9,8 (VMware) |
| [CVE-2026-33824](https://nvd.nist.gov/vuln/detail/CVE-2026-33824) | Windows, extensión IKE | Doble liberación de memoria: ejecución de código por red sin autenticarse | 9,8 (Microsoft) |
| [CVE-2026-65400](https://nvd.nist.gov/vuln/detail/CVE-2026-65400) | Apple macOS | Falla de autenticación, corregida con mejor gestión de estado | 9,8 |

Los puntajes salen de NVD, y en cada caso está anotado quién los asignó: son evaluaciones del propio fabricante, no de NVD. Es la distinción que conviene mirar siempre, porque cuando ambos puntúan suelen diferir.

---

## Por qué este lote es distinto

SharePoint no es infraestructura: es donde tu equipo guarda los documentos. Si tenés Microsoft 365, tenés SharePoint, lo sepas o no — es lo que hay debajo de Teams y de los archivos compartidos. Una falla de autenticación débil ahí, explotable **por red y sin credenciales**, toca a cualquier empresa con un tenant.

macOS tampoco: es la notebook de tu diseñador, de tu contador o tuya.

Y la extensión IKE de Windows es la que negocia los túneles IPsec, o sea la VPN. Ejecución de código por red sin autenticarse contra el componente que atiende conexiones entrantes de VPN es exactamente la combinación que no querés.

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

La lección no es "ahora el riesgo está en el escritorio". Es que **el patrón de una semana no es una estrategia**. Si hace dos días hubieras concluido que solo importa blindar la capa de administración, hoy estarías mirando para el lado equivocado. Lo que sirve es cubrir las dos y decidir por exposición, no por la moda de la semana.

---

## El dato que más incomoda

Mirá la fecha de publicación de [CVE-2026-33824](https://nvd.nist.gov/vuln/detail/CVE-2026-33824), la de la extensión IKE: **14 de abril de 2026**. Entró al catálogo de explotación activa el **18 de agosto**.

Cuatro meses. Durante cuatro meses hubo un parche disponible y una falla de 9,8 que, para la mayoría de las organizaciones, no era urgente porque "no se estaba explotando". El 18 de agosto pasó a estarlo, y el plazo se volvió de tres días.

Esa es la trampa de priorizar solo por lo que está bajo ataque hoy: **la lista de lo que está bajo ataque cambia sin avisarte, y cuando cambia ya no tenés tiempo.** Una falla vieja no es una falla segura; es una que te dieron meses para cerrar.

Los plazos que fija CISA para los organismos federales en estas cuatro son del **21 de agosto**: tres días desde que entraron. La acción requerida que publica el propio catálogo cita como base la directiva **BOD 26-04, "Prioritizing Security Updates Based on Risk"**. No pudimos abrir la página de esa directiva desde acá para verificar su texto —el sitio devuelve un error de acceso—, así que no vamos a describir su contenido; lo que sí es verificable, porque está en el feed del catálogo, es que ese es el marco que invocan y que el plazo son tres días.

---

## A quién le toca, y a quién no

**Te toca casi seguro:**

- **Si tenés Microsoft 365**, tenés SharePoint. No hace falta que lo uses conscientemente: está debajo de Teams y de los archivos compartidos. Esta es la que más empresas alcanza de las cuatro.
- **Si tenés Windows con VPN IPsec** terminando en un servidor propio.

**Te toca si aplica:**

- **Macs en la empresa.** Apple corrigió la falla en **macOS Sequoia 15.7.9, Sonoma 14.8.9 y Tahoe 26.6.1**. Si tus equipos están en una versión anterior a esas, actualizá.
- **vCenter**, si administrás virtualización propia. Si toda tu infraestructura está en la nube de un tercero, no te aplica.

**No te toca:**

- Si no tenés Microsoft 365, ni Macs, ni VPN IPsec propia, ni VMware, estas cuatro no son tuyas. Vale decirlo: perseguir cada CVE que aparece en las noticias es la forma más rápida de agotar a la única persona que se ocupa de esto en una PyME.

---

## Qué hacer, en orden

1. **SharePoint primero**, porque es la de mayor alcance. En Microsoft 365 la corrección la aplica Microsoft del lado del servicio; lo que te toca a vos es revisar que no tengas una instalación propia de SharePoint Server sin parchear, que es donde el problema queda de tu lado.
2. **Actualizá los Macs** a las versiones de arriba. Es una tarde y cierra una falla de 9,8.
3. **Revisá la exposición de la VPN.** Si tenés IPsec terminando en Windows, aplicá el parche de abril —sí, el de abril— y, si nadie usa ese acceso desde que migraron a otra cosa, apagalo. Sacar de internet lo que no se usa vale más que parchearlo: el razonamiento completo está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn), y las alternativas por identidad como [Cloudflare Access](/producto/cloudflare-access) evitan tener un servicio publicado esperando el próximo CVE.
4. **Buscá señales de compromiso, no solo parchees.** Cuando una falla lleva meses con parche disponible y recién ahora se confirma explotación, la pregunta honesta no es solo "¿ya lo cerré?" sino "¿alguien entró antes?". Revisá inicios de sesión anómalos y accesos raros a SharePoint. El tipo de señal que hay que mirar está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).
5. **Verificá que tus copias sobrevivan a un administrador comprometido.** Es el control que convierte un incidente en un mal día en vez de en una quiebra; [Veeam Data Platform](/producto/veeam-data-platform) y la [categoría de backup](/productos/backup-y-recuperacion) cubren la inmutabilidad.

Mi lectura, marcada como opinión: los puntos 3 y 4 son los que más rinden. Parchear es una carrera que se corre todos los meses; reducir lo que está publicado y aprender a mirar los registros son capacidades que quedan.

Si estás armando el programa desde cero y no sabés en qué orden va cada cosa, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) lo ordena, y los términos que aparecen acá están definidos en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes

### Tengo Microsoft 365, ¿tengo que hacer algo con lo de SharePoint?

En el servicio en la nube, la corrección la aplica Microsoft del lado del servidor. Lo que te corresponde revisar es si además tenés una instalación propia de SharePoint Server —muchas PyMEs heredaron una y la olvidaron—, porque ahí el parche es tuyo. Y en cualquier caso vale mirar accesos anómalos recientes.

### ¿Por qué una falla publicada en abril entra al catálogo recién en agosto?

Porque el catálogo no lista fallas graves, lista fallas con evidencia de explotación activa. Entre abril y agosto existía el parche pero no había constancia de que alguien la estuviera usando; el 18 de agosto sí la hubo. Es el argumento más fuerte para no esperar a que algo esté bajo ataque para cerrarlo: cuando llega ese momento, el plazo pasa a ser de días.

### Los puntajes dicen "según Microsoft" o "según VMware". ¿Eso los hace menos confiables?

No menos confiables, sí menos comparables. En estas cuatro, quien puntuó fue el fabricante y no NVD. Cuando ambos evalúan la misma falla los números suelen diferir, a veces por casi un punto entero, porque parten de supuestos distintos sobre el contexto. Por eso conviene decidir por presencia en el catálogo de explotación activa y por si el producto está expuesto, no por decimales.

### Tengo Macs pero nadie los administra centralmente. ¿Cómo sé si están actualizados?

No lo sabés, y ese es el problema de fondo, más grande que esta falla puntual. Sin una consola que muestre el parque completo, cada equipo depende de que su usuario acepte la actualización. Es el mismo argumento que desarrolla la guía sobre [cuándo se justifica el salto de antivirus a EDR](/guia/edr-o-antivirus-cuando-se-justifica-pyme): lo que se compra no es solo detección, es visibilidad.
