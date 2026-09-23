---
title: "Check Point Spark, F5 y Arista: cuatro fallas críticas en un solo día"
subtitle: CISA catalogó cuatro appliances de seguridad el 22 de septiembre, las cuatro explotables sin autenticarse y con plazo al 25. Check Point confirma ataques contra su línea para PyMEs desde el 12.
excerpt: Cuatro productos que existen para proteger la red entraron al catálogo de explotación activa el mismo día. Cuál te toca, qué versión corrige y qué buscar en los certificados VPN.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-09-23
updated: 2026-09-23
meta_title: "Check Point, F5 y Arista: cuatro críticas"
meta_description: "Cuatro appliances de seguridad entraron al catálogo de CISA el mismo día. Check Point confirma ataques contra su línea Spark. Qué versión corrige cada uno."
---

El 22 de septiembre CISA sumó al catálogo de explotación activa **cuatro vulnerabilidades de una sola vez**, todas con plazo al **25 de septiembre**. Las cuatro tienen algo en común que conviene decir antes que cualquier otra cosa: **los cuatro productos existen para proteger la red**.

| CVE | Producto | Qué permite | Puntaje |
|---|---|---|---|
| CVE-2026-85102 | Check Point Security Gateway y **Spark Firewall** | Ejecutar código en el gateway durante la negociación VPN | 9,8 |
| CVE-2026-93616 | Check Point Security Management | Subir y ejecutar scripts sin autenticarse | 9,8 |
| CVE-2026-94127 | F5 BIG-IP APM | Ejecución remota de código | 9,8 en CVSS 3.1 |
| CVE-2026-93952 | Arista VeloCloud Orchestrator, en sitio | Acceso a funciones internas privilegiadas del orquestador | 10,0 en CVSS 3.1 |

Las cuatro se disparan **sin autenticarse** y los cuatro puntajes los asignó cada fabricante. Ninguna de las cuatro tenía puntaje propio de NVD al momento de escribir esto: las cuatro figuran como *Awaiting Analysis*.

De las cuatro, la que más toca al lector de este sitio es la primera, y por un motivo concreto: **Check Point Spark es su línea de firewalls para empresas chicas**.

## Check Point confirma ataques contra la línea Spark desde el 12 de septiembre

El aviso de Check Point es específico y vale citarlo, porque nombra a quién le estaban pegando: *"we observed a wave of exploitation attempts against Spark customers"*, a partir del **12 de septiembre**.

**CVE-2026-85102** es una validación indebida de la confianza en el certificado **durante la negociación de la VPN**. Un atacante sin credenciales presenta un certificado preparado y termina ejecutando código en el gateway. Afecta, según el fabricante, a **Security Gateway, Spark Firewall (administrado centralmente) y Spark Firewall (administrado localmente)**, en versiones **R81 hasta R82.00.X**. La corrección está disponible **desde el 9 de septiembre**.

Esa distancia es el dato incómodo: **la corrección salió el 9 y los ataques empezaron el 12**. Tres días.

```svg
<svg viewBox="0 0 680 205" role="img" aria-label="Las dos ventanas opuestas de las vulnerabilidades de Check Point de septiembre de 2026. En CVE-2026-85102 la corrección estuvo disponible el 9 de septiembre y los ataques contra clientes de la línea Spark empezaron el 12: tres días después del parche. En CVE-2026-93616 ocurrió al revés: Check Point observó ataques dirigidos el 23 de julio de 2026 y la vulnerabilidad recién se publicó el 22 de septiembre, 61 días más tarde, de modo que fue explotada como día cero durante dos meses antes de que nadie pudiera parchearla">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Dos fallas del mismo fabricante, dos ventanas opuestas</text>

  <rect x="20" y="42" width="620" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="42" width="620" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="64" font-size="11" font-weight="700" fill="currentColor">CVE-2026-85102: el parche llegó primero, por poco</text>
  <text x="38" y="84" font-size="10.5" fill="currentColor" opacity="0.85">Corrección disponible el 9 de septiembre. Ataques contra clientes Spark desde el 12.</text>
  <text x="38" y="98" font-size="10.5" fill="currentColor" opacity="0.85">Tres días de margen para quien actualizó apenas salió; ninguno para el resto.</text>

  <rect x="20" y="116" width="620" height="62" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="116" width="620" height="62" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="138" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-93616: los ataques llegaron primero, por mucho</text>
  <text x="38" y="158" font-size="10.5" fill="currentColor" opacity="0.85">Ataques dirigidos observados el 23 de julio. La falla se publicó el 22 de septiembre.</text>
  <text x="38" y="172" font-size="10.5" fill="currentColor" opacity="0.85">61 días explotada como día cero, sin que nadie pudiera parchear lo que no sabía.</text>
</svg>
```

Porque la segunda falla de Check Point corre en la dirección contraria. **CVE-2026-93616** es un recorrido de rutas en el servicio web de administración que permite subir y ejecutar scripts sin autenticarse, y sobre ella el aviso dice: *"we observed a handful of pinpointed attacks on July 23, 2026"*. Ataques dirigidos el **23 de julio**; la vulnerabilidad se publicó el **22 de septiembre**. **Sesenta y un días** en los que se usó contra objetivos elegidos sin que existiera identificador, aviso ni parche.

Las dos juntas dibujan el rango completo: en una, quien actualizó el mismo día que salió el parche tuvo tres días de ventaja; en la otra, actualizar no era una opción porque nadie sabía.

## Qué buscar: el certificado VPN con el que atacaron a los Spark

Acá está lo más aprovechable del aviso, y es de los indicadores más simples que publicó un fabricante este mes.

Check Point describe los intentos contra clientes Spark como certificados VPN sospechosos **con sujetos del tipo `CN=vpn,OU=users,O=global`**.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Qué distingue al certificado usado en los ataques contra los firewalls Check Point Spark. Un certificado VPN legítimo de una empresa lleva en su sujeto el nombre real de esa organización y de su unidad, y lo emitió una autoridad que la empresa reconoce. El certificado que Check Point observó en los intentos de explotación lleva un sujeto genérico, del tipo CN igual a vpn, OU igual a users, O igual a global, sin ninguna relación con la organización atacada, y por eso es fácil de distinguir al revisar los registros de negociación de la VPN">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">El sujeto del certificado, en la negociación de la VPN</text>

  <rect x="20" y="42" width="620" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="42" width="620" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="64" font-size="11" font-weight="700" fill="currentColor">Un certificado legítimo de tu empresa</text>
  <text x="38" y="84" font-size="10.5" fill="currentColor" opacity="0.85">Lleva en el sujeto el nombre real de la organización y de su unidad.</text>
  <text x="38" y="98" font-size="10.5" fill="currentColor" opacity="0.85">Lo emitió una autoridad que vos reconocés y podés nombrar.</text>

  <rect x="20" y="116" width="620" height="62" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="116" width="620" height="62" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="138" font-size="11" font-weight="700" fill="#e23a3a">El que describe Check Point en los intentos</text>
  <text x="38" y="158" font-size="10.5" fill="currentColor" opacity="0.85">Sujeto genérico, del tipo CN=vpn, OU=users, O=global.</text>
  <text x="38" y="172" font-size="10.5" fill="currentColor" opacity="0.85">Sin relación con la organización atacada: nadie lo emitió para vos.</text>
</svg>
```

La ventaja de este indicador es que no hace falta entender la falla para usarlo. En los registros de negociación de la VPN del gateway, un certificado cuyo sujeto no tenga nada que ver con tu empresa ni con ninguna autoridad que vos reconozcas es, como mínimo, algo que hay que explicar.

## Las otras dos: F5 BIG-IP APM y Arista VeloCloud, con condiciones

Las dos que faltan tienen una característica en común que conviene aprovechar: **el fabricante dice con precisión en qué configuración aplican**, así que se puede descartar rápido.

**CVE-2026-94127**, en **F5 BIG-IP APM**, es un desbordamiento de montón que lleva a ejecución remota de código. F5 acota el alcance de forma inusualmente clara: la falla aparece cuando hay una política de acceso de APM **y un perfil de OAuth** configurados sobre un servidor virtual, y **sólo** cuando APM está configurado como **servidor de autorización OAuth**. El aviso lo dice sin ambigüedad: *"Deployments using APM strictly as an OAuth Client / Resource Server (without OAuth authorization server profiles configured) are not affected by this vulnerability."* Si tu APM sólo actúa como cliente o servidor de recursos, no te toca.

**CVE-2026-93952**, en **Arista VeloCloud Orchestrator**, es una validación indebida de entradas que permite a un atacante remoto alcanzar funciones internas privilegiadas y afectar al host del orquestador. Arista le asigna **10,0 en CVSS 3.1** —con alcance cambiado, `S:C`— y 9,5 en la escala 4.0. La condición acá es de despliegue: el aviso aclara que **las versiones alojadas, incluida la Dedicated, ya fueron parcheadas**. El que tiene que actuar es quien corre el orquestador **en sitio**.

Vale notar el detalle de escalas: tanto F5 como Arista publicaron el puntaje en **las dos versiones de CVSS**, la 3.1 y la 4.0, y en los dos casos el número de la 4.0 es más bajo —9,3 contra 9,8 en F5, 9,5 contra 10,0 en Arista—. No es que la falla sea menos grave en una escala: son dos maneras distintas de medir lo mismo, y conviene no comparar números de escalas diferentes como si fueran la misma medida.

## Los equipos de perímetro llevan 16 entradas al catálogo desde agosto

El día 22 fue el peor, pero no es un día suelto. Contando desde el 1 de agosto las entradas del catálogo que corresponden a firewalls, VPN, balanceadores, orquestadores de red y equipos de borde, son **16 en 52 días**: una cada **3,25 días**.

```svg
<svg viewBox="0 0 680 240" role="img" aria-label="Entradas del catálogo de vulnerabilidades explotadas de CISA correspondientes a equipos de perímetro y seguridad de red desde el 1 de agosto de 2026, agrupadas por fabricante: Cisco cuatro entradas, entre firewall ASA, Firewall Management Center, Secure Email Gateway e Identity Services Engine; Check Point dos; Citrix dos en NetScaler; SonicWall dos en los SMA1000; MikroTik dos en RouterOS; y una entrada cada uno para Fortinet, Zyxel, Arista y F5. En total dieciséis entradas en cincuenta y dos días, una cada 3,25 días">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Equipos de perímetro en el catálogo, desde el 1 de agosto</text>

  <text x="20" y="64" font-size="10.5" font-weight="700" fill="currentColor">Cisco</text>
  <rect x="200" y="52" width="320" height="15" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="528" y="64" font-size="10.5" font-weight="700" fill="currentColor">4</text>

  <text x="20" y="92" font-size="10.5" font-weight="700" fill="#e23a3a">Check Point</text>
  <rect x="200" y="80" width="160" height="15" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="368" y="92" font-size="10.5" font-weight="700" fill="#e23a3a">2</text>

  <text x="20" y="120" font-size="10.5" font-weight="700" fill="currentColor">Citrix</text>
  <rect x="200" y="108" width="160" height="15" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="368" y="120" font-size="10.5" font-weight="700" fill="currentColor">2</text>

  <text x="20" y="148" font-size="10.5" font-weight="700" fill="currentColor">SonicWall</text>
  <rect x="200" y="136" width="160" height="15" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="368" y="148" font-size="10.5" font-weight="700" fill="currentColor">2</text>

  <text x="20" y="176" font-size="10.5" font-weight="700" fill="currentColor">MikroTik</text>
  <rect x="200" y="164" width="160" height="15" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="368" y="176" font-size="10.5" font-weight="700" fill="currentColor">2</text>

  <text x="20" y="204" font-size="10.5" font-weight="700" fill="currentColor">Fortinet, Zyxel,</text>
  <text x="20" y="218" font-size="10.5" font-weight="700" fill="currentColor">Arista y F5</text>
  <rect x="200" y="192" width="320" height="15" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="528" y="204" font-size="10.5" font-weight="700" fill="currentColor">4</text>

  <text x="20" y="234" font-size="11.5" font-weight="600" fill="currentColor">16 entradas en 52 días: una cada 3,25 días, y cuatro cayeron el mismo 22</text>
</svg>
```

El sitio viene cubriendo esas entradas de a una: [Citrix NetScaler y Cisco FMC](/noticia/citrix-netscaler-cisco-fmc-misma-falla-camino-alternativo), [MikroTik RouterOS](/noticia/mikrotik-routeros-mikrotrick-usuario-ops), [el gateway de correo de Cisco](/noticia/cisco-secure-email-gateway-cve-2026-76461-root), [los switches Zyxel de ayer](/noticia/zyxel-gs1900-cve-2026-7273-switches-desde-la-lan). Vistas juntas dicen algo que ninguna dice sola.

No es que estos productos sean peores que otros. Es que **concentran acceso**: un gateway VPN ve a todos los que entran, un orquestador de red configura todas las sedes, un balanceador con política de acceso decide quién llega a las aplicaciones. Un atacante que elige dónde invertir su tiempo elige ahí, y por eso la caja que comprás para defenderte es también la que más vale atacar. Eso no es un argumento para no tenerla: es un argumento para tratarla como el activo crítico que es, y no como infraestructura que se instala y se olvida.

## ¿A quién afecta cada falla: Check Point, F5 o Arista?

Es el punto donde conviene ser preciso, porque tres de las cuatro tienen condiciones que descartan rápido.

- **CVE-2026-85102**: a quien tenga **Check Point Security Gateway o Spark Firewall**, en R81 hasta R82.00.X, con VPN de sitio a sitio o de acceso remoto configurada. Es la que más probablemente toque a una PyME de la región, porque Spark es la línea chica.
- **CVE-2026-93616**: a quien tenga **Check Point Security Management**. Según el fabricante, R82.20, R82.10 con Jumbo Hotfix Take 44 o anterior, R82 con Take 126 o anterior, y versiones previas hasta R80.
- **CVE-2026-94127**: a quien tenga **F5 BIG-IP APM configurado como servidor de autorización OAuth**. Si no usás APM en ese rol, no aplica.
- **CVE-2026-93952**: a quien corra **Arista VeloCloud Orchestrator en sitio**. Las versiones alojadas ya están parcheadas por el fabricante.

## ¿Quién puede ignorar los avisos de Check Point, F5 y Arista?

Quien no tenga ninguno de los cuatro productos, que es la mayoría de las empresas chicas: Check Point Spark es el que más aparece en ese segmento, y los otros tres son de organizaciones más grandes o de proveedores de servicios.

Ahora, si tu red la administra un tercero, ahí sí corresponde preguntar. Un proveedor de conectividad o de seguridad administrada es exactamente el tipo de organización que tiene un VeloCloud orquestando sedes o un BIG-IP adelante de las aplicaciones, y lo que le pase a ese equipo te alcanza aunque no sea tuyo.

## ¿Qué hago si tengo un Check Point Spark?

1. **Comprobá la versión y actualizá.** La corrección de CVE-2026-85102 existe desde el 9 de septiembre. Si estás en R81 a R82.00.X sin actualizar, estás en la ventana que se está atacando desde el 12.
2. **Revisá los registros de negociación de la VPN** buscando certificados con sujetos genéricos como el que describe Check Point. Es el indicador más barato de comprobar y el más concluyente si aparece.
3. **Si administrás también el Security Management**, tratalo aparte: es la otra falla, con sus propios niveles de Jumbo Hotfix, y con el agravante de que estuvo explotada dos meses antes de hacerse pública. Que no haya rastros hoy no dice nada sobre julio.
4. **Si aparece cualquier indicio, tratá el equipo como comprometido**, no como pendiente de parche. Un gateway VPN con ejecución de código ajena vio pasar todas las sesiones: corresponde rotar credenciales y claves que hayan atravesado ese equipo.
5. **Preguntá quién mira estos avisos.** Es la pregunta de fondo de esta nota: si el firewall lo instaló un proveedor hace tres años y nadie revisa sus boletines, el plazo de tres días del catálogo es irrelevante porque nadie lo va a leer. El formato para dejar eso acordado por escrito está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

Sobre reducir la superficie del acceso remoto en general, el razonamiento está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn) y el resto de la categoría en [VPN y acceso remoto](/productos/vpn-y-acceso-remoto). Los términos, en el [glosario](/guia/glosario-ciberseguridad-pymes), y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre las cuatro fallas del 22 de septiembre

### Tengo un Check Point Spark. ¿Cómo sé si ya me atacaron?

El camino que publicó el fabricante es revisar los registros de negociación de la VPN buscando certificados con sujetos genéricos, del tipo que Check Point describe. Con la advertencia de siempre cuando la falla termina en ejecución de código: si el ataque tuvo éxito, el atacante pudo tocar lo que el equipo registra. Si tu gateway estuvo en una versión vulnerable con la VPN publicada entre el 12 de septiembre y el día que actualices, lo prudente es cruzar con lo que vean otros equipos de la red.

### Si actualicé el 10 de septiembre, ¿estuve siempre cubierto?

Para CVE-2026-85102, sí: la corrección salió el 9 y los ataques empezaron el 12. Es uno de los pocos casos recientes donde actualizar rápido alcanzó de verdad. Para CVE-2026-93616 la respuesta es distinta y menos cómoda, porque esa falla se estuvo usando desde julio y recién se publicó el 22 de septiembre: ningún calendario de actualizaciones te habría protegido de algo que no existía como aviso.

### ¿Por qué F5 y Arista publican dos puntajes distintos para la misma falla?

Porque son dos versiones de la escala CVSS, la 3.1 y la 4.0, y miden con criterios distintos. F5 publica 9,8 en 3.1 y 9,3 en 4.0; Arista publica 10,0 en 3.1 y 9,5 en 4.0. Ninguno de los dos números está mal, y no hay que interpretarlos como que la falla "bajó" de gravedad: son dos reglas de cálculo diferentes aplicadas al mismo hecho. Lo comparable es un 3.1 contra otro 3.1.

### Mi BIG-IP APM no usa OAuth. ¿Igual tengo que actualizar?

Para esta falla puntual, no: F5 dice explícitamente que los despliegues que usan APM sólo como cliente o servidor de recursos de OAuth, sin perfiles de servidor de autorización configurados, no están afectados. Es una condición de configuración, no una versión, así que conviene confirmarla mirando la configuración real del servidor virtual y no de memoria. Actualizar sigue siendo buena práctica, pero la urgencia de tres días no aplica.

### ¿Tiene sentido cambiar de marca después de una tanda así?

No por esto. En los últimos 52 días entraron al catálogo equipos de perímetro de nueve fabricantes distintos: Cisco, Check Point, Citrix, SonicWall, MikroTik, Fortinet, Zyxel, Arista y F5. Cambiar de marca sólo mueve el problema, porque lo que atrae al atacante no es el fabricante sino la posición del equipo en la red. Lo que sí cambia el resultado es tener a alguien con nombre y apellido a cargo de leer los boletines de ese equipo y de aplicarlos, que es la diferencia entre enterarse el día 9 o el día 22.
