---
title: "Citrix NetScaler y SQL Server, ya explotados"
subtitle: CISA sumó seis fallas al catálogo de explotación activa el 26 de agosto. Cinco se publicaron entre 2015 y 2022, y la más vieja tiene 11 años.
excerpt: Seis fallas entraron al catálogo de explotación activa de CISA. Solo una es de este año: las otras cinco llevan entre 4 y 11 años publicadas.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-08-27
updated: 2026-08-27
products:
  - cloudflare-access
  - twingate
  - tailscale-business
meta_title: "Citrix NetScaler y SQL Server, ya explotados"
meta_description: "CISA sumó Citrix NetScaler, Microsoft SQL Server y cuatro fallas más al catálogo de explotación activa. Cinco tienen entre 4 y 11 años."
---

El 26 de agosto el catálogo de vulnerabilidades explotadas de CISA sumó seis entradas de una vez, en su versión 2026.08.26. Una es de este año. Las otras cinco se publicaron entre 2015 y 2022.

| CVE | Producto | Publicada | Puntaje | Vence |
|---|---|---|---|---|
| [CVE-2026-8452](https://nvd.nist.gov/vuln/detail/CVE-2026-8452) | Citrix NetScaler ADC y Gateway | 30 jun 2026 | 9.8 (NVD) · 8.8 (Citrix) | 29 ago |
| [CVE-2019-1068](https://nvd.nist.gov/vuln/detail/CVE-2019-1068) | Microsoft SQL Server | 15 jul 2019 | 8.8 (NVD) | 29 ago |
| [CVE-2021-23758](https://nvd.nist.gov/vuln/detail/CVE-2021-23758) | Ajax.NET Professional | 3 dic 2021 | 9.8 (NVD) · 8.1 (Snyk) | 9 sep |
| [CVE-2022-0995](https://nvd.nist.gov/vuln/detail/CVE-2022-0995) | Núcleo de Linux | 25 mar 2022 | 7.8 (NVD) | 9 sep |
| [CVE-2015-5287](https://nvd.nist.gov/vuln/detail/CVE-2015-5287) | Red Hat ABRT | 7 dic 2015 | 7.8 (NVD) | 9 sep |
| [CVE-2015-3246](https://nvd.nist.gov/vuln/detail/CVE-2015-3246) | Red Hat libuser | 11 ago 2015 | 5.1 (ADP) | 9 sep |

Los plazos son los que CISA fija a los organismos federales de Estados Unidos. Para una empresa privada no son obligatorios, pero funcionan como orden de prioridad: la agencia los calcula según lo que está viendo.

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Antigüedad de las seis fallas al entrar al catálogo: Red Hat libuser 11 años, Red Hat ABRT 10,7 años, Microsoft SQL Server 7,1 años, Ajax.NET Professional 4,7 años, núcleo de Linux 4,4 años y Citrix NetScaler apenas 0,2 años. Cinco de las seis llevaban más de cuatro años publicadas">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Años desde la publicación hasta entrar al catálogo</text>

  <text x="192" y="61" text-anchor="end" font-size="10.5" fill="currentColor">Red Hat libuser · 2015</text>
  <rect x="200" y="46" width="380" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="588" y="61" font-size="10.5" font-weight="700" fill="currentColor">11,0 años</text>

  <text x="192" y="89" text-anchor="end" font-size="10.5" fill="currentColor">Red Hat ABRT · 2015</text>
  <rect x="200" y="74" width="369" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="577" y="89" font-size="10.5" font-weight="700" fill="currentColor">10,7 años</text>

  <text x="192" y="117" text-anchor="end" font-size="10.5" fill="currentColor">Microsoft SQL Server · 2019</text>
  <rect x="200" y="102" width="245" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="453" y="117" font-size="10.5" font-weight="700" fill="currentColor">7,1 años</text>

  <text x="192" y="145" text-anchor="end" font-size="10.5" fill="currentColor">Ajax.NET Professional · 2021</text>
  <rect x="200" y="130" width="162" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="370" y="145" font-size="10.5" font-weight="700" fill="currentColor">4,7 años</text>

  <text x="192" y="173" text-anchor="end" font-size="10.5" fill="currentColor">Núcleo de Linux · 2022</text>
  <rect x="200" y="158" width="152" height="20" rx="3" fill="currentColor" opacity="0.3"/>
  <text x="360" y="173" font-size="10.5" font-weight="700" fill="currentColor">4,4 años</text>

  <text x="192" y="201" text-anchor="end" font-size="10.5" font-weight="700" fill="#e23a3a">Citrix NetScaler · 2026</text>
  <rect x="200" y="186" width="7" height="20" rx="2" fill="#e23a3a" opacity="0.75"/>
  <text x="215" y="201" font-size="10.5" font-weight="700" fill="#e23a3a">0,2 años</text>

  <text x="20" y="238" font-size="11.5" font-weight="600" fill="currentColor">La barra corta es la única falla de este año en toda la tanda</text>
</svg>
```

---

## Citrix NetScaler: la única de este año y la que vence antes

CVE-2026-8452 está en NetScaler ADC y NetScaler Gateway, el equipo que muchas empresas ponen en el borde para publicar acceso remoto. Citrix la corrigió en su [boletín CTX696604](https://support.citrix.com/external/article/CTX696604/netscaler-adc-and-netscaler-gateway-secu.html), publicado el 30 de junio y actualizado el 20 de julio.

**Solo aplica si el equipo está configurado** como Gateway —VPN SSL, ICA Proxy, CVPN o RDP Proxy— o como servidor virtual AAA. Un NetScaler que solo balancea carga no entra.

| Rama | Afectada | Corregida en |
|---|---|---|
| 14.1 | Anterior a 14.1-72.61 | 14.1-72.61 |
| 13.1 | Anterior a 13.1-63.18 | 13.1-63.18 |
| 14.1-FIPS | Anterior a 14.1-72.61 FIPS | 14.1-72.61 FIPS |
| 13.1-FIPS y 13.1-NDcPP | Anterior a 13.1-37.272 | 13.1-37.272 |

Hay un detalle que conviene mirar de frente. Tanto Citrix como NVD describen la falla como desbordamiento de memoria que lleva a *"comportamiento impredecible o erróneo y denegación de servicio"*. En agosto, investigadores publicaron un análisis técnico sosteniendo que la misma falla se puede llevar a ejecución de código sin autenticarse. Que CISA la haya puesto en el catálogo con dos días de plazo indica cuál de las dos lecturas pesa en la práctica. La descripción oficial se quedó corta.

Los dos puntajes tampoco se comparan directamente: el 8.8 de Citrix está en la escala CVSS 4.0 y el 9.8 de NVD, en la 3.1. Son sistemas de medición distintos, no dos opiniones sobre el mismo número.

---

## Por qué cinco de las seis tienen entre 4 y 11 años

El catálogo no registra cuándo se descubrió una falla, sino cuándo se confirmó que alguien la está usando contra organizaciones reales. Una falla de 2015 que aparece hoy significa que en 2026 todavía hay suficientes sistemas sin parchear como para que a un atacante le rinda apuntarles.

Las cuatro viejas comparten un perfil: **CVE-2015-3246** y **CVE-2015-5287** son de componentes de Red Hat Enterprise Linux, **CVE-2022-0995** está en el núcleo de Linux, y las tres permiten a un usuario local escalar privilegios. **CVE-2021-23758** es distinta: una biblioteca .NET vieja incrustada en aplicaciones internas, con deserialización que lleva a ejecución de código.

Ninguna se explota desde internet por sí sola. Son el segundo movimiento: sirven después de haber entrado por otro lado.

```svg
<svg viewBox="0 0 680 190" role="img" aria-label="Por qué una falla de 2015 sigue viva en 2026: el servidor se instala y funciona, deja de actualizarse porque nadie quiere tocar lo que anda, no figura en ningún inventario, y termina siendo el objetivo porque sigue expuesto años después">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Cómo una falla de 2015 llega viva a 2026</text>

  <rect x="16" y="48" width="130" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="16" y="48" width="130" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="81" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">1. Se instala</text>
  <text x="81" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">y funciona bien</text>
  <path d="M150 86 L176 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="182" y="48" width="130" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="182" y="48" width="130" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="247" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">2. Se congela</text>
  <text x="247" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">nadie toca lo que</text>
  <text x="247" y="111" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">está andando</text>
  <path d="M316 86 L342 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="348" y="48" width="130" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="348" y="48" width="130" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="413" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">3. Se olvida</text>
  <text x="413" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">no está en ningún</text>
  <text x="413" y="111" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">inventario</text>
  <path d="M482 86 L508 86" stroke="#e23a3a" stroke-width="1.4"/>

  <rect x="514" y="48" width="130" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="514" y="48" width="130" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="579" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="#e23a3a">4. Es el blanco</text>
  <text x="579" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">porque sigue ahí,</text>
  <text x="579" y="111" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">once años después</text>

  <text x="340" y="164" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Lo que te ataca hoy no suele ser lo último que salió, sino lo último que actualizaste</text>
</svg>
```

Los plazos que fijó CISA separan la tanda en dos grupos, y esa división sirve como orden de trabajo.

```svg
<svg viewBox="0 0 660 210" role="img" aria-label="Los plazos dividen la tanda en dos grupos: Citrix NetScaler y Microsoft SQL Server vencen el 29 de agosto, dentro de 2 días; el núcleo de Linux, Ajax.NET Professional, Red Hat ABRT y Red Hat libuser vencen el 9 de septiembre, dentro de 13 días">
  <rect x="20" y="26" width="600" height="66" rx="6" fill="#e23a3a" opacity="0.12"/>
  <rect x="20" y="26" width="600" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="50" font-size="12.5" font-weight="700" fill="#e23a3a">29 de agosto · dentro de 2 días</text>
  <text x="36" y="74" font-size="11" fill="currentColor" opacity="0.85">Citrix NetScaler ADC y Gateway · Microsoft SQL Server</text>

  <rect x="20" y="112" width="600" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="112" width="600" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.5"/>
  <text x="36" y="136" font-size="12.5" font-weight="700" fill="currentColor">9 de septiembre · dentro de 13 días</text>
  <text x="36" y="160" font-size="11" fill="currentColor" opacity="0.85">Núcleo de Linux · Ajax.NET Professional · Red Hat ABRT · Red Hat libuser</text>

  <text x="20" y="199" font-size="11.5" font-weight="600" fill="currentColor">Las dos que vencen antes son las únicas que se alcanzan desde la red</text>
</svg>
```

---

## ¿A quién afecta esta tanda del catálogo de CISA?

Comprobalo hoy si estás en alguno de estos casos:

- **Tenés un Citrix NetScaler publicando acceso remoto.** Es el caso más urgente de los seis y el único que se ataca desde internet sin credenciales.
- **Corrés Microsoft SQL Server sin actualizar desde hace años.** Es habitual en PyMEs donde el sistema de gestión o el ERP vino con su propia instancia y quedó ahí, con la actualización postergada por miedo a romper el sistema que factura.
- **Tenés servidores con Red Hat Enterprise Linux o derivados** de la época en que se instalaron y nunca migraron.
- **Alguien mantiene una aplicación .NET interna vieja.** Ajax.NET Professional es una biblioteca de las que quedan enterradas en un proyecto que compila y nadie revisa.

## ¿Quién puede ignorar este aviso?

Una PyME cuya infraestructura sea Microsoft 365 o Google Workspace, con las computadoras al día y sin servidores propios, no tiene ninguno de estos seis productos. Ese es el caso más común y el aviso no le aplica.

Tampoco aplica si tu NetScaler solo hace balanceo de carga sin Gateway ni servidor virtual AAA configurado: la condición está en el boletín de Citrix y decide si el equipo es alcanzable.

Y las cuatro fallas viejas no se disparan desde internet. Si un servidor Linux no tiene usuarios locales además de quien lo administra, el escenario de escalada de privilegios pierde casi todo su sentido.

## ¿Cómo sé si corro alguno de estos seis productos?

**Citrix NetScaler.** La versión está en la consola de administración, en *System* → *Licenses* o en el encabezado. Compará contra la tabla de arriba, y revisá en la configuración si hay un servidor virtual de tipo Gateway o AAA.

**Microsoft SQL Server.** Desde una consulta:

```sql
SELECT @@VERSION;
```

El número de compilación dice el nivel de actualización. Una instancia que nunca recibió actualizaciones acumulativas desde antes de julio de 2019 está en el rango.

**Red Hat y Linux.** En el servidor:

```bash
rpm -q libuser abrt 2>/dev/null; uname -r
```

**Ajax.NET Professional.** Es una biblioteca, así que se busca en los proyectos: un archivo `AjaxPro.2.dll` en el directorio `bin` de una aplicación web .NET.

El ejercicio que resuelve las seis a la vez es el inventario: qué corre en la empresa, en qué versión y desde cuándo. Es lo que también piden las aseguradoras cuando cotizan una póliza, según se detalla en [qué exigen las aseguradoras](/guia/ciberseguro-pyme-que-exigen-las-aseguradoras).

## ¿Qué hago si tengo Citrix NetScaler o Microsoft SQL Server?

1. **Actualizá el NetScaler primero.** Es lo único de la tanda expuesto a internet sin autenticación previa, y vence en dos días. Las compilaciones corregidas están en la tabla.
2. **Mientras tanto, mirá quién llega al equipo.** Un panel de administración accesible desde cualquier IP del mundo es una condición que se puede cerrar hoy, sin esperar la ventana de mantenimiento.
3. **Actualizá SQL Server con la acumulativa que corresponda a tu versión.** Si el proveedor del sistema de gestión dice que no se puede tocar, esa respuesta necesita fecha y plan, no un "no se puede" indefinido.
4. **Para las cuatro viejas, priorizá por exposición.** Un servidor Linux al que solo entra el administrador es distinto de uno con varias cuentas. Empezá por los compartidos.
5. **Aprovechá para reducir superficie, no solo para parchear.** Si el NetScaler está publicado para dar acceso remoto, un modelo por identidad como [Cloudflare Access](/producto/cloudflare-access) o [Twingate](/producto/twingate) evita tener un equipo de borde esperando el próximo aviso; el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn).

La tanda anterior del catálogo, del 18 al 21 de agosto, está en [las cuatro fallas de SharePoint, macOS y vCenter](/noticia/cuatro-fallas-criticas-sharepoint-macos-vpn-vcenter) y en [Zimbra, TrueConf y MLflow](/noticia/zimbra-trueconf-software-autoalojado-parche-propio). Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes

### Si la falla es de 2015, ¿no debería estar parcheada hace años?

Debería, y ese es el punto. Que entre al catálogo en 2026 significa que se confirmó explotación reciente, y para explotar algo hace falta encontrarlo sin parchear. El catálogo no es una lista de novedades: es una lista de lo que funciona contra el parque real, que incluye una cantidad de sistemas congelados mucho mayor de la que se supone.

### El puntaje de Citrix es 8.8 y el de NVD 9.8. ¿Cuál uso?

Ninguno de los dos por separado. Están en escalas distintas: el de Citrix es CVSS 4.0 y el de NVD, CVSS 3.1, así que comparar los números es comparar dos reglas con marcas diferentes. Para decidir, mirá los hechos: la falla se alcanza desde la red, sin credenciales, y CISA le dio dos días de plazo.

### La descripción dice denegación de servicio. ¿Por qué tanta urgencia?

Porque la descripción oficial describe el efecto comprobado por el fabricante, no el techo de lo que se puede hacer con la falla. En agosto se publicó un análisis técnico sosteniendo que el mismo desbordamiento lleva a ejecución de código sin autenticar. No se puede confirmar esa afirmación contra el aviso del fabricante, que sigue hablando de denegación de servicio; lo que sí es un hecho verificable es que CISA la agregó al catálogo de explotación activa con el plazo más corto de la tanda.

### Mi proveedor dice que no se puede actualizar el SQL Server. ¿Qué hago?

Pedile por escrito tres cosas: qué se rompe exactamente al aplicar la actualización acumulativa, qué fecha propone para resolverlo y qué medida compensatoria deja mientras tanto. Un servidor de base de datos con una falla de ejecución remota confirmada como explotada no es un riesgo teórico. Si además tenés que responderle a un cliente por esto, el formato está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

### Los plazos de CISA no me obligan. ¿Los uso igual?

Sí, como orden de prioridad. La agencia los fija según el riesgo que observa, no según el puntaje: en esta tanda hay una falla de 7.8 con 13 días y otra de 8.8 con 2. Esa diferencia dice más sobre qué atender primero que cualquiera de los dos números.
