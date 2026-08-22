---
title: "Zimbra y TrueConf: cuando el parche depende de vos"
subtitle: Las cuatro entradas del catálogo entre el 19 y el 21 de agosto son software autoalojado. Nadie las corrige del otro lado: si lo corrés, lo parcheás.
excerpt: Zimbra, TrueConf y MLflow entraron al catálogo de explotación activa. Las cuatro fallas están en servidores propios, y dos vencen en tres días.
type: news
status: published
category: email-y-antiphishing
author: ian-poletti-lucero
published: 2026-08-22
updated: 2026-08-22
products:
  - microsoft-defender-for-office-365
  - mimecast-email-security
  - proofpoint-email-protection
  - cloudflare-access
meta_title: "Zimbra y TrueConf: cuando el parche depende de vos"
meta_description: "Zimbra, TrueConf y MLflow entraron al catálogo de explotación activa de CISA. Las cuatro fallas están en software autoalojado y dos vencen en tres días."
---

Entre el 19 y el 21 de agosto el [catálogo de vulnerabilidades explotadas de CISA](https://www.cisa.gov/known-exploited-vulnerabilities-catalog) sumó cuatro entradas. Las cuatro comparten algo que la tanda anterior no tenía: **son software que corre en servidores propios**.

Hace tres días publicamos que [tres de cuatro fallas estaban en SharePoint, macOS y la VPN de Windows](/noticia/cuatro-fallas-criticas-sharepoint-macos-vpn-vcenter). En esos casos Microsoft y Apple empujan la corrección. Acá no hay nadie del otro lado: si lo corrés, lo parcheás.

| CVE | Producto | Qué es | Puntaje | Vence |
|---|---|---|---|---|
| [CVE-2026-73570](https://nvd.nist.gov/vuln/detail/CVE-2026-73570) | Zimbra Collaboration | Inyección de comandos del sistema operativo | 8,9 (MITRE) | 24 ago |
| [CVE-2026-72529](https://nvd.nist.gov/vuln/detail/CVE-2026-72529) | TrueConf Server | Función crítica sin autenticación | 9,3 (Kaspersky) | 23 ago |
| [CVE-2026-72530](https://nvd.nist.gov/vuln/detail/CVE-2026-72530) | TrueConf Server | Inyección de código | 9,5 (Kaspersky) | 3 sep |
| [CVE-2026-64849](https://nvd.nist.gov/vuln/detail/CVE-2026-64849) | MLflow | Falsificación de petición del lado del servidor | 9,3 (GitHub) | 2 sep |

Los puntajes salen de NVD, con quién los asignó anotado: ninguno fue evaluado por NVD.

```svg
<svg viewBox="0 0 660 210" role="img" aria-label="Comparación entre el lote del 18 de agosto, donde el fabricante aplica la corrección, y el del 19 al 21, donde el parche depende del que aloja el servidor">
  <rect x="20" y="26" width="600" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="26" width="600" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.5"/>
  <text x="36" y="50" font-size="13" font-weight="700" fill="currentColor">18 de agosto — servicio del fabricante</text>
  <text x="36" y="74" font-size="11.5" fill="currentColor" opacity="0.85">SharePoint · macOS · Windows — la corrección la empuja Microsoft o Apple</text>

  <rect x="20" y="112" width="600" height="66" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="112" width="600" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="136" font-size="13" font-weight="700" fill="#e23a3a">19 al 21 de agosto — servidor propio</text>
  <text x="36" y="160" font-size="11.5" fill="currentColor" opacity="0.85">Zimbra · TrueConf · MLflow — si lo corrés, lo parcheás vos</text>

  <text x="330" y="200" text-anchor="middle" font-size="12" fill="currentColor" opacity="0.75">La diferencia no es la gravedad: es quién tiene que actuar</text>
</svg>
```

---

## Zimbra es el que más pesa en la región

Zimbra Collaboration es correo y calendario autoalojado, y en LATAM lo corren PyMEs, universidades y organismos que buscaron una alternativa propia a Microsoft 365 o Google Workspace. Un servidor de correo comprometido no es una máquina más: es el archivo de todas las conversaciones de la empresa.

La falla afecta a **versiones anteriores a la 10.1.20** y permite ejecución remota de código, pero **tiene una condición que cambia a quién le toca**: según la descripción de NVD, solo aplica cuando está instalado el paquete opcional `zimbra-snmp` **y** las notificaciones SNMP están habilitadas. La entrada llegó al catálogo el 21 de agosto con vencimiento el 24.

Esa condición es la primera que hay que comprobar, porque decide si esto es urgente o no aplica.

---

## TrueConf: dos fallas, dos plazos distintos

TrueConf Server es videoconferencia autoalojada. Las dos entradas llegaron el mismo día, afectan a las **versiones 5.3.x hasta 5.3.9, 5.4.x hasta 5.4.9, 5.5.x hasta 5.5.5 y anteriores**, y las dos se explotan sin autenticarse por el **puerto 4307/TCP**.

CVE-2026-72529 permite ejecutar un script arbitrario invocando una función no documentada. CVE-2026-72530 permite salir del entorno aislado con un script preparado.

El detalle a mirar es el plazo: la primera vence el **23 de agosto** y la segunda el **3 de septiembre**. Dos fallas del mismo producto, agregadas el mismo día, con once días de diferencia en la urgencia asignada. Si administrás TrueConf, la primera es la que corre.

---

## MLflow: la superficie que casi nadie inventarió

MLflow es una plataforma de código abierto para modelos de aprendizaje automático. La falla afecta a **versiones anteriores a la 3.15.0** y está en un endpoint que acepta peticiones **sin autenticación**.

Para la mayoría de las PyMEs esto no aplica. Vale nombrarlo por otra razón: es la tercera vez en un mes que entra al catálogo una herramienta del ecosistema de aprendizaje automático, después de IBM Langflow y Ray. Si alguien en la empresa levantó un servidor de experimentación con IA, probablemente no esté en ningún inventario.

---

## A quién le toca

**Comprobalo hoy si:**

- Corrés **Zimbra** propio. Mirá la versión y, sobre todo, si tenés `zimbra-snmp` instalado con notificaciones SNMP activas. Sin esa condición, la falla no aplica.
- Tenés **TrueConf Server** en alguna de las versiones listadas. El acceso es por el puerto 4307/TCP: si ese puerto responde desde internet, la urgencia sube.
- Alguien levantó un **MLflow** para probar modelos.

**No te toca** si tu correo es Microsoft 365 o Google Workspace, no tenés videoconferencia autoalojada y nadie en la empresa corre plataformas de aprendizaje automático. Ese es el caso de la mayoría de las PyMEs, y perseguir cada CVE que sale en las noticias agota a la única persona que se ocupa de esto.

---

## Cómo comprobar exposición

Todo esto se verifica desde tu lado, con tus credenciales.

**Versión de Zimbra.** En el servidor, `zmcontrol -v`. Si es anterior a 10.1.20, comprobá el paquete: `rpm -qa | grep zimbra-snmp` en distribuciones basadas en RPM, o `dpkg -l | grep zimbra-snmp` en las basadas en Debian. Si no está instalado, esta falla no te alcanza.

**Puerto 4307.** Desde fuera de tu red, verificá si responde. Un servidor de videoconferencia con puertos de administración accesibles desde cualquier IP del mundo es una falla esperando a la próxima.

**Inventario de lo publicado.** Es el ejercicio que resuelve las tres a la vez: qué de lo tuyo responde desde internet. Los servidores propios se instalan una vez y se olvidan, que es lo que los convierte en el blanco preferido.

---

## Qué hacer, en orden

1. **Actualizá Zimbra a 10.1.20 o posterior.** Si no podés hoy y tenés `zimbra-snmp` instalado sin usarlo, desactivar las notificaciones SNMP corta la condición que habilita la falla.
2. **Actualizá TrueConf** por encima de las versiones afectadas, y cerrá el 4307 al exterior si no necesita estar publicado.
3. **Sacá de internet lo que no necesita estar.** Vale más que parchear: se hace una vez y baja el piso de riesgo de forma permanente. Si hace falta acceso remoto, un modelo por identidad como [Cloudflare Access](/producto/cloudflare-access) evita tener servicios publicados esperando el próximo CVE; el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).
4. **Revisá los registros del servidor de correo** por accesos anómalos. Cuando una falla de ejecución remota llega al catálogo, la pregunta no es solo si ya la cerraste sino si alguien entró antes. El tipo de señal a buscar está en [cómo funciona el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).
5. **Si administrás correo propio, evaluá el costo real de seguir haciéndolo.** Un servidor de correo autoalojado exige parchear en plazos de días, con una superficie que un proveedor gestionado absorbe por vos. La [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes) pone los números.

El punto 5 es el que más cambia la ecuación a mediano plazo. Autoalojar el correo es defendible por costo, soberanía de datos o requisitos regulatorios, y tiene una contrapartida que suele quedar fuera del cálculo: cada entrada al catálogo con vencimiento a tres días es tuya.

Los términos de esta nota están definidos en el [glosario](/guia/glosario-ciberseguridad-pymes), y el orden general de prioridades en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes

### Tengo Zimbra pero no uso SNMP. ¿Me afecta?

Según la descripción de NVD, la falla requiere que el paquete opcional `zimbra-snmp` esté instalado y que las notificaciones SNMP estén habilitadas. Si no lo instalaste, no aplica. Conviene verificarlo con el gestor de paquetes en vez de asumirlo: en instalaciones heredadas suele haber componentes que nadie recuerda haber activado.

### ¿Por qué dos fallas del mismo producto tienen plazos distintos?

CVE-2026-72529 vence el 23 de agosto y CVE-2026-72530 el 3 de septiembre, aunque las dos entraron al catálogo el 20. Los plazos que CISA fija a los organismos federales dependen de la evaluación de riesgo de cada caso, no solo del puntaje. Para una empresa privada el plazo no es obligatorio, pero sirve como orden de prioridad.

### Los puntajes los asignó Kaspersky y MITRE, no NVD. ¿Eso importa?

Importa para comparar. Cuando el puntaje lo asigna el fabricante o la organización que coordinó la divulgación, y no NVD, los números de distintas fallas no son estrictamente equivalentes porque parten de supuestos distintos. La decisión conviene tomarla por presencia en el catálogo de explotación activa y por si el producto está expuesto a internet.

### ¿Conviene dejar de autoalojar el correo?

Depende de por qué lo autoalojás. Si es por soberanía de datos o por un requisito regulatorio, la respuesta puede ser no, y entonces hay que asumir el compromiso de parchear en días. Si es solo por costo, conviene rehacer la cuenta incluyendo el tiempo de administración y el riesgo de quedar expuesto entre que sale un aviso y se aplica el parche.
