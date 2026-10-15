---
title: "Ahsay AhsayCBS: dos fallas explotadas en el servidor de backup"
subtitle: Las dos abusan del mismo parámetro y juntas llegan a ejecutar comandos en el equipo que guarda las copias. El arreglo salió 70 días antes, y las notas de versión del fabricante no dicen que era de seguridad.
excerpt: CISA sumó cuatro vulnerabilidades explotadas el 14 de octubre y dos están en AhsayCBS, el software con el que muchos proveedores de IT hacen los backups de sus clientes. Qué versión mirar y qué preguntar.
type: news
status: published
category: backup-y-recuperacion
author: ian-poletti-lucero
published: 2026-10-15
updated: 2026-10-15
products: []
meta_title: "Ahsay AhsayCBS: dos explotadas en el backup"
meta_description: "Dos fallas explotadas en AhsayCBS, el servidor de backup: bypass de autenticación y ejecución de comandos. Qué versión mirar y qué preguntar."
---

El 14 de octubre el catálogo de vulnerabilidades explotadas de CISA sumó **cuatro entradas**, las cuatro con plazo al **17 de octubre**: **3 días**, y hoy quedan **2**. Dos de las cuatro están en el mismo producto, y es uno incómodo: **AhsayCBS**, el software de backup con el que muchos proveedores de IT de la región guardan las copias de sus clientes.

Las cuatro:

- **CVE-2026-105133** y **CVE-2026-105134**, en **Ahsay AhsayCBS**: un bypass de autenticación y una inyección de comandos del sistema operativo.
- **CVE-2026-76519**, en **Cisco Secure Email Gateway**: **9,8** según Cisco, escritura de archivos sin autenticación.
- **CVE-2026-0322**, en **Palo Alto Networks PAN-OS**: **8,6** según Palo Alto, código con privilegios de root en el firewall, pero con autenticación previa.

## Qué entró al catálogo el 14 de octubre y quién puso cada puntaje

```svg
<svg viewBox="0 0 680 244" role="img" aria-label="Las cuatro vulnerabilidades que CISA agregó a su catálogo de explotadas el 14 de octubre de 2026, las cuatro con plazo al 17 de octubre, es decir tres días. Primera: CVE-2026-105134 en Ahsay AhsayCBS, inyección de comandos del sistema operativo, con puntaje 10,0 crítico en la escala CVSS 3.1 y 9,3 crítico en la escala 4.0, las dos asignadas por VulDB, no por Ahsay. Segunda: CVE-2026-105133 en Ahsay AhsayCBS, autenticación incorrecta, con puntaje 7,3 alto en la escala 3.1 y 5,5 medio en la escala 4.0, también de VulDB: la misma falla cambia de categoría según la escala que se mire. Tercera: CVE-2026-76519 en Cisco Secure Email Gateway, con puntaje 9,8 crítico asignado por el equipo de seguridad de Cisco, un recorrido de rutas que permite a un atacante remoto sin autenticar escribir archivos en el sistema operativo, y se explota enviando un correo con un adjunto cuando está activo el servicio de reputación de archivos. Cuarta: CVE-2026-0322 en Palo Alto Networks PAN-OS, con puntaje 8,6 alto asignado por el equipo de seguridad de Palo Alto, ejecución de código con privilegios de root en el firewall desde la interfaz de administración, pero requiere un atacante ya autenticado. Ninguna de las cuatro figura con uso conocido en campañas de ransomware">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Cuatro altas el 14 de octubre, las cuatro con 3 días</text>

  <text x="20" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">CVE</text>
  <text x="140" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">PRODUCTO</text>
  <text x="310" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">PUNTAJE (FUENTE)</text>
  <text x="470" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">QUÉ PERMITE</text>
  <line x1="20" y1="49" x2="640" y2="49" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="70" font-size="10" font-weight="700" fill="#e23a3a">CVE-2026-105134</text>
  <text x="140" y="70" font-size="10" fill="currentColor" opacity="0.9">Ahsay AhsayCBS</text>
  <text x="310" y="70" font-size="10" font-weight="700" fill="#e23a3a">10,0 y 9,3 (VulDB)</text>
  <text x="470" y="70" font-size="9.5" fill="currentColor" opacity="0.9">ejecuta comandos</text>

  <text x="20" y="96" font-size="10" font-weight="700" fill="#e23a3a">CVE-2026-105133</text>
  <text x="140" y="96" font-size="10" fill="currentColor" opacity="0.9">Ahsay AhsayCBS</text>
  <text x="310" y="96" font-size="10" fill="currentColor" opacity="0.9">7,3 y 5,5 (VulDB)</text>
  <text x="470" y="96" font-size="9.5" fill="currentColor" opacity="0.9">entra sin contraseña</text>

  <text x="20" y="122" font-size="10" font-weight="700" fill="currentColor">CVE-2026-76519</text>
  <text x="140" y="122" font-size="10" fill="currentColor" opacity="0.9">Cisco Secure Email GW</text>
  <text x="310" y="122" font-size="10" font-weight="700" fill="currentColor">9,8 (Cisco)</text>
  <text x="470" y="122" font-size="9.5" fill="currentColor" opacity="0.9">escribe archivos</text>

  <text x="20" y="148" font-size="10" font-weight="700" fill="currentColor">CVE-2026-0322</text>
  <text x="140" y="148" font-size="10" fill="currentColor" opacity="0.9">Palo Alto PAN-OS</text>
  <text x="310" y="148" font-size="10" fill="currentColor" opacity="0.9">8,6 (Palo Alto)</text>
  <text x="470" y="148" font-size="9.5" fill="currentColor" opacity="0.9">root, ya autenticado</text>

  <rect x="20" y="164" width="620" height="34" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="185" font-size="10" fill="currentColor" opacity="0.9">Las de Ahsay las puntuó VulDB, no el fabricante. Las otras dos, el equipo de seguridad de cada marca.</text>

  <text x="20" y="218" font-size="10.5" font-weight="700" fill="#e23a3a">La misma falla de Ahsay es "alta" en una escala y "media" en la otra.</text>
  <text x="20" y="238" font-size="9" fill="currentColor" opacity="0.65">Feed JSON del catálogo de CISA (versión 2026.10.14) y fichas de NVD, consultados el 15 de octubre de 2026.</text>
</svg>
```

Ese último renglón es una trampa concreta para quien prioriza por puntaje. **CVE-2026-105133 es 7,3 ("alto") en la escala CVSS 3.1 y 5,5 ("medio") en la 4.0.** Es la misma falla y las dos cifras las puso la misma fuente: cambia la escala, no el problema. Si tu proceso de parches tiene un umbral tipo "atiendo lo alto y crítico", esa falla entra o no entra según cuál de las dos columnas mire la herramienta. Y está en el catálogo de explotadas, que es la única señal que importa acá.

Conviene aclarar de dónde sale cada puntaje, porque no es parejo. **Las dos de Ahsay las registró y puntuó VulDB** como autoridad de numeración: **Ahsay no aparece como fuente de ningún puntaje**, y NVD todavía figura con análisis sin terminar en las dos. Las de Cisco y Palo Alto, en cambio, las puntuaron los equipos de seguridad de cada fabricante.

## Las dos fallas de Ahsay abusan del mismo parámetro

Acá está lo que las vuelve una cadena y no dos problemas sueltos.

```svg
<svg viewBox="0 0 680 252" role="img" aria-label="Las dos vulnerabilidades de Ahsay AhsayCBS en versiones hasta la 10.3.2 inclusive, según las fichas de NVD. La primera, CVE-2026-105133, está en la función checkSysPwd del archivo ApiStructsAction.java del componente API: manipulando el argumento llamado random se produce una autenticación incorrecta, de forma remota. La segunda, CVE-2026-105134, está en la dirección barra rps barra api barra json barra UpdateReceivers.do del componente Replication Receiver, el que recibe las réplicas: manipulando el mismo argumento random se llega a inyección de comandos del sistema operativo, también de forma remota. Lo que las une es que el parámetro manipulado se llama igual en los dos componentes, que son componentes distintos del mismo producto, lo que apunta a un patrón de validación compartido y roto en más de un lugar. Las dos fichas declaran además que el exploit ya es público. Las dos se corrigen en la versión 10.3.4. El resultado práctico de la combinación es que un atacante sin credenciales puede terminar ejecutando comandos del sistema operativo en el servidor que guarda las copias de seguridad">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Dos componentes distintos, el mismo parámetro roto</text>

  <rect x="20" y="36" width="620" height="76" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="36" width="620" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="55" font-size="10.5" font-weight="700" fill="currentColor">CVE-2026-105133 · componente API · función checkSysPwd</text>
  <text x="36" y="74" font-size="9.5" fill="currentColor" opacity="0.9">Manipulando el argumento "random" se produce autenticación incorrecta.</text>
  <text x="36" y="92" font-size="9.5" font-weight="700" fill="currentColor">Resultado: se entra sin credenciales válidas.</text>

  <text x="330" y="130" font-size="11" font-weight="700" fill="#e23a3a">y después</text>

  <rect x="20" y="144" width="620" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="144" width="620" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="163" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-105134 · componente Replication Receiver · UpdateReceivers.do</text>
  <text x="36" y="182" font-size="9.5" fill="currentColor" opacity="0.9">Manipulando el MISMO argumento "random" se llega a inyección de comandos.</text>
  <text x="36" y="200" font-size="9.5" font-weight="700" fill="#e23a3a">Resultado: comandos del sistema operativo en el servidor de backup.</text>

  <text x="20" y="240" font-size="9.5" fill="currentColor" opacity="0.85">Las dos fichas declaran que el exploit ya es público. Las dos se corrigen en la 10.3.4.</text>
  <text x="20" y="249" font-size="9" fill="currentColor" opacity="0.65">Fichas de NVD de los dos CVE, versiones afectadas hasta la 10.3.2 inclusive.</text>
</svg>
```

Las dos fichas de NVD son precisas sobre el mecanismo, y vale leerlas juntas. La primera está en la función `checkSysPwd` del componente API; la segunda, en `/rps/api/json/UpdateReceivers.do`, del componente **Replication Receiver**, el que recibe las réplicas. Y en las dos **el argumento manipulado se llama igual: `random`**.

Que el mismo nombre de parámetro aparezca como vía de entrada en dos componentes distintos del mismo producto apunta a un patrón de validación compartido y roto en más de un lugar, no a dos errores independientes. Es la misma forma de problema que vengo viendo en otras notas: un componente valida una cosa y otro confía en lo que recibe.

Las dos afectan **hasta la 10.3.2 inclusive** y las dos **se corrigen en la 10.3.4**. Y las dos fichas declaran que **el exploit ya es público**.

## El arreglo salió 70 días antes de que CISA la marcara como explotada

```svg
<svg viewBox="0 0 680 230" role="img" aria-label="Cronología del arreglo y de la publicación de las dos fallas de Ahsay AhsayCBS. El 5 de agosto de 2026 Ahsay publicó la versión 10.3.4, que es la que corrige las dos fallas según las fichas de NVD, pero sus notas de versión no contienen ningún contenido de seguridad: no mencionan ningún CVE, ni autenticación, ni inyección de comandos, ni el componente Replication Receiver, ni la API. El 4 de octubre de 2026, sesenta días después, se publicaron las dos fichas de CVE en NVD, y ya declaraban que el exploit era público. El 14 de octubre de 2026, setenta días después del arreglo y diez días después de la publicación de las fichas, CISA agregó las dos al catálogo de vulnerabilidades explotadas con plazo al 17 de octubre. La conclusión práctica es que la corrección estuvo disponible durante setenta días, pero el lugar donde un administrador prudente mira para decidir si una actualización es urgente, que son las notas de versión del fabricante, no llevaba ninguna señal de seguridad">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">El parche existía 70 días antes del aviso de explotación</text>

  <text x="38" y="54" font-size="10" font-weight="700" fill="currentColor">5 ago</text>
  <text x="130" y="54" font-size="10.5" fill="currentColor" opacity="0.9">Ahsay publica la 10.3.4, que corrige las dos fallas</text>

  <text x="38" y="80" font-size="10" font-weight="700" fill="#e23a3a">5 ago</text>
  <text x="130" y="80" font-size="10.5" font-weight="700" fill="#e23a3a">Sus notas de versión no mencionan seguridad en ninguna parte</text>

  <text x="38" y="108" font-size="10" font-weight="700" fill="currentColor">4 oct</text>
  <text x="130" y="108" font-size="10.5" fill="currentColor" opacity="0.9">Se publican las fichas en NVD, con el exploit ya público (60 días)</text>

  <text x="38" y="136" font-size="10" font-weight="700" fill="#e23a3a">14 oct</text>
  <text x="130" y="136" font-size="10.5" font-weight="700" fill="#e23a3a">CISA las marca como explotadas, plazo al 17 (70 días)</text>

  <rect x="20" y="154" width="620" height="48" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="154" width="620" height="48" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="174" font-size="10.5" font-weight="700" fill="#e23a3a">El lugar donde se mira para decidir si actualizar no llevaba la señal</text>
  <text x="36" y="192" font-size="9.5" fill="currentColor" opacity="0.9">Quien leyó esas notas en agosto vio una actualización más, no un motivo para correr.</text>

  <text x="20" y="224" font-size="9" fill="currentColor" opacity="0.65">Notas de versión de AhsayCBS 10.3.4 del propio fabricante (fechadas 05-Aug-2026) y fichas de NVD, al 15 de octubre de 2026.</text>
</svg>
```

Esto lo verifiqué leyendo las notas de versión de la **10.3.4 en el sitio del propio Ahsay**, que es la página que las fichas de NVD enlazan como referencia. Están fechadas **05-Aug-2026** y **no contienen ningún contenido de seguridad**: no nombran ningún CVE, ni la autenticación, ni la inyección de comandos, ni el componente Replication Receiver. Los únicos términos parecidos aparecen en el menú de navegación del sitio, no en las notas de esa versión.

La consecuencia práctica es la misma que ya apareció con [dos plugins de pago de WordPress cuyo changelog tampoco nombraba el arreglo](/noticia/bricksforge-super-forms-dos-criticas-sin-autenticar), pero acá pega más fuerte: **el arreglo estuvo disponible 70 días** y quien leyó las notas en agosto no tenía forma de saber que había un motivo para apurarse. El primer aviso claro llegó cuando ya estaba en el catálogo de explotadas.

## El backup tiene 2,3 veces la tasa de ransomware del catálogo

Esto lo calculé sobre el feed completo, y es el dato que explica por qué una falla en un servidor de copias no es una falla más.

```svg
<svg viewBox="0 0 680 236" role="img" aria-label="Medición sobre el catálogo de vulnerabilidades explotadas de CISA, versión 2026.10.14, que tiene 1745 entradas. En todo el catálogo, 361 entradas figuran con uso conocido en campañas de ransomware, es decir el 20,7 por ciento. Entre los productos de backup y recuperación hay 15 entradas en total, y 7 de ellas figuran con uso conocido en ransomware, es decir el 46,7 por ciento: 2,3 veces la tasa del catálogo completo. El reparto de esas 15 entradas por fabricante es: Veeam 4, Veritas 3, Ahsay 2, Acronis 2, Commvault 2, NAKIVO 1 y Arcserve 1. Advertencia de método importante: 15 entradas es una muestra chica, así que la proporción de 2,3 veces marca una dirección y no una medida precisa. Las dos entradas de Ahsay del 14 de octubre de 2026 figuran todavía con uso en ransomware desconocido, que no es lo mismo que no">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué proporción de cada grupo se ató a ransomware</text>

  <text x="20" y="52" font-size="10" fill="currentColor" opacity="0.9">Todo el catálogo</text>
  <rect x="200" y="42" width="166" height="13" rx="2" fill="currentColor" opacity="0.45"/>
  <text x="374" y="52" font-size="10" font-weight="700" fill="currentColor">20,7% — 361 de 1745</text>

  <text x="20" y="80" font-size="10" fill="currentColor" opacity="0.9">Productos de backup</text>
  <rect x="200" y="70" width="374" height="13" rx="2" fill="#e23a3a"/>
  <text x="582" y="80" font-size="10" font-weight="700" fill="#e23a3a">46,7%</text>

  <rect x="20" y="96" width="620" height="30" rx="5" fill="#e23a3a" opacity="0.13"/>
  <text x="34" y="116" font-size="10.5" font-weight="700" fill="#e23a3a">7 de las 15 entradas de backup se ataron a ransomware: 2,3 veces la tasa general.</text>

  <text x="20" y="150" font-size="10" font-weight="700" fill="currentColor">Las 15 entradas de backup del catálogo, por fabricante:</text>
  <text x="38" y="170" font-size="10" fill="currentColor" opacity="0.9">Veeam 4 · Veritas 3 · Ahsay 2 · Acronis 2 · Commvault 2 · NAKIVO 1 · Arcserve 1</text>

  <text x="20" y="196" font-size="9.5" fill="currentColor" opacity="0.85">Método: 15 entradas es muestra chica. El 2,3x marca una dirección, no una medida precisa.</text>
  <text x="20" y="212" font-size="9.5" fill="currentColor" opacity="0.85">Las dos de Ahsay figuran con ransomware "desconocido", que no es lo mismo que "no".</text>

  <text x="20" y="230" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre el feed JSON del catálogo de CISA, versión 2026.10.14, 1745 entradas.</text>
</svg>
```

Los productos de backup son apenas **15 de las 1745 entradas** del catálogo, menos del 1%. Pero **7 de esas 15 figuran con uso confirmado en campañas de ransomware**, el **46,7%**, contra el **20,7%** del catálogo completo: **2,3 veces la tasa general**. Con la salvedad de método que corresponde: 15 entradas es una muestra chica, así que esa proporción marca una dirección, no una medida fina.

La dirección igual tiene sentido y es la que conviene entender: el servidor de copias es el objetivo que vuelve irreversible un ataque. Si el atacante llega ahí primero, se lleva los datos y además borra el camino de vuelta. Es el mismo razonamiento de [la falla de Acronis Backup de septiembre](/noticia/acronis-backup-cpanel-plesk-cve-2026-87886), que es justamente la entrada de backup anterior a estas dos en el catálogo.

## ¿A quién afectan las fallas de Ahsay AhsayCBS?

A quien tenga un **servidor AhsayCBS en la 10.3.2 o anterior**. Y acá está el problema de esta nota: **lo más probable es que no lo tengas vos, sino tu proveedor de IT**.

AhsayCBS es la parte servidor de una plataforma de backup que se vende sobre todo a proveedores que revenden el servicio: el proveedor levanta el servidor y los clientes corren el agente. Para una PyME eso significa que la copia de seguridad de sus datos vive en un equipo que no administra, que no puede parchear y sobre el que probablemente nunca preguntó qué software usa. Es el mismo encuadre que [la falla de N-able N-central que le pegaba al proveedor de IT](/noticia/n-able-n-central-cve-2026-86218-proveedor-it).

Si administrás el servidor vos, el perfil es directo: un AhsayCBS publicado para que los agentes se conecten, con el componente de réplica activo.

## ¿Quién puede ignorar el aviso de CVE-2026-105133 y CVE-2026-105134?

Quien no tenga AhsayCBS en ninguna parte de su cadena de backup, directa o a través de un proveedor.

Quien tenga el servidor en **10.3.4 o posterior**, que es la versión donde las dos fichas indican el arreglo.

Lo que **no** alcanza para ignorarlas: que el servidor "esté detrás del firewall". Las dos fallas son de vector de red y la de inyección de comandos no pide credenciales, así que lo que decide es si el servicio es alcanzable, no si hay un firewall en el camino. Y tampoco alcanza que tengas copias: el punto de estas dos fallas es precisamente el equipo donde están las copias.

## ¿Cómo sé qué versión de AhsayCBS usa mi proveedor de backup?

Si el servidor es tuyo, la versión figura en la consola de administración de AhsayCBS. Compará con 10.3.2 y apuntá a 10.3.4 o posterior.

Si el servidor es de tu proveedor, esto se resuelve preguntando. La pregunta concreta, por escrito, tiene tres partes:

1. **¿Qué software de backup usan para mis copias, y en qué versión está el servidor?** Si la respuesta es AhsayCBS, seguí con las otras dos.
2. **¿Está en 10.3.4 o posterior?** Es la versión con el arreglo. Si está en 10.3.2 o anterior, la siguiente pregunta es cuándo actualizan, no si van a hacerlo.
3. **¿El servidor estuvo expuesto en una versión afectada, y qué revisaron?** Con el exploit público desde antes de que las fichas se publicaran, "lo parcheamos" y "lo revisamos" son dos respuestas distintas.

Para saber si ya lo usaron, las señales que corresponde buscar en un servidor de backup son concretas:

- **Trabajos de copia que fallan o que terminan mucho más rápido de lo normal**, que es lo que pasa cuando el contenido ya no es el que era.
- **Cuentas de administración o receptores de réplica que nadie creó.** La segunda falla está justamente en el componente de réplica, así que ahí es donde conviene mirar primero.
- **Cambios en las políticas de retención**, que es la forma silenciosa de dejar a una empresa sin copias viejas sin borrar nada hoy.
- **Restauraciones de prueba.** La única manera de saber que una copia sirve es restaurarla, y es lo que casi nadie hace hasta que la necesita.

Ninguna de las cuatro altas figura con uso conocido en ransomware, así que **no hay indicadores publicados que buscar**: esto es revisión general. El encuadre más largo de qué tiene que cubrir una copia está en [la guía de backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta), y las opciones, en [la sección de backup y recuperación](/productos/backup-y-recuperacion).

## ¿Qué hago si mis copias pasan por un AhsayCBS?

En este orden, teniendo en cuenta que el plazo del catálogo vence el 17 de octubre:

1. **Mandá las tres preguntas de arriba por escrito, hoy.** Por escrito importa: fija la fecha en que preguntaste y la respuesta que te dieron.
2. **Si el servidor es tuyo, actualizá a 10.3.4 o posterior.** Las dos fichas coinciden en esa versión.
3. **Tratá el servidor como potencialmente comprometido si estuvo expuesto en 10.3.2 o anterior.** El exploit era público antes de que se publicaran las fichas, y la combinación de las dos fallas llega a ejecución de comandos sin credenciales. Parchear no responde la pregunta de qué pasó antes del parche.
4. **Pedí una restauración de prueba.** Es la única verificación que no depende de la palabra de nadie: si la copia restaura, existe.
5. **Revisá que las copias no dependan de un solo lugar.** Si el mismo servidor que recibe las copias es el que las guarda y el que define la retención, una falla ahí alcanza para dejarte sin nada.

Las dos de Cisco y Palo Alto tienen su propio camino. La de **Cisco Secure Email Gateway** se explota enviando un correo con un adjunto preparado cuando está activo el servicio de reputación de archivos, y es el **segundo CVE explotado en ese producto** que cubre el sitio: el anterior fue [la inyección SQL que llegaba a root](/noticia/cisco-secure-email-gateway-cve-2026-76461-root). La de **PAN-OS** necesita un atacante ya autenticado en la interfaz de administración, y Palo Alto señala que el riesgo baja si esa interfaz solo se alcanza desde direcciones internas de confianza, que es la recomendación que ya tenían publicada.

Los términos están en [el glosario](/guia/glosario-ciberseguridad-pymes), y la nota de ayer sobre las dos altas anteriores, en [Ivanti Connect Secure y Windows Shell](/noticia/ivanti-connect-secure-windows-shell-dos-explotadas-plazo-16).

Una aclaración sobre el alcance de lo que verifiqué. **Las dos fallas de Ahsay las registró y puntuó VulDB**, no el fabricante: Ahsay no figura como fuente de ningún puntaje y NVD todavía tiene las dos fichas con el análisis sin terminar, así que no hay una segunda opinión sobre la severidad. Lo que sí leí directamente fue la página de notas de versión de la 10.3.4 en el sitio de Ahsay, y de ahí sale la afirmación de que no mencionan seguridad. No encontré ningún aviso de seguridad propio de Ahsay para estos dos CVE, y tampoco pude confirmar si el fabricante notificó a sus revendedores por otro canal: la ausencia de un aviso público no prueba que no haya avisado.

---

## Preguntas frecuentes sobre las fallas de Ahsay AhsayCBS

### ¿Por qué una falla en el backup es peor que en otro servidor?

Porque es la que vuelve irreversible todo lo demás. Un atacante que llega al servidor de copias se lleva los datos y además borra el camino de vuelta, así que el incidente pasa de "restauramos y seguimos" a "no tenemos de dónde restaurar". El dato que lo respalda: los productos de backup son 15 de las 1745 entradas del catálogo de CISA, pero 7 de esas 15 figuran con uso confirmado en campañas de ransomware, el 46,7%, contra el 20,7% del catálogo completo. Son 2,3 veces la tasa general, con la salvedad de que 15 entradas es una muestra chica.

### La misma falla aparece como 7,3 y como 5,5. ¿Cuál es la buena?

Las dos, porque son escalas distintas: 7,3 es CVSS 3.1 y 5,5 es CVSS 4.0, y las dos las puso la misma fuente sobre la misma falla. No hay una correcta. Lo que importa de ese detalle es práctico: si tu proceso de parches prioriza por umbral, esa falla queda arriba o abajo del corte según qué columna lea la herramienta. Para estas dos, el criterio que manda no es el puntaje sino que están en el catálogo de explotadas de CISA, que es una afirmación sobre lo que se está usando y no sobre la gravedad teórica.

### Mi proveedor dice que ya actualizó. ¿Alcanza con eso?

Actualizar cierra la puerta, pero no dice qué pasó mientras estuvo abierta. Las fichas declaran que el exploit ya era público cuando se publicaron, y el arreglo estuvo disponible desde el 5 de agosto sin que las notas de versión avisaran que era de seguridad, así que hubo una ventana larga. Las dos preguntas que corresponden son si el servidor estuvo expuesto en 10.3.2 o anterior y qué revisaron para descartar que lo hubieran usado. Y la verificación que no depende de la palabra de nadie es pedir una restauración de prueba.

### ¿Ahsay publicó un aviso de seguridad por estas fallas?

No encontré ninguno, y conviene decirlo con esas palabras en lugar de afirmar que no existe. Lo que sí verifiqué es que las notas de versión de la 10.3.4 —la versión que corrige las dos, según las fichas, y que el propio NVD enlaza como referencia— no mencionan seguridad en ninguna parte: ni CVE, ni autenticación, ni inyección de comandos, ni el componente de réplica. Tampoco pude confirmar si Ahsay avisó a sus revendedores por algún canal privado, y la ausencia de un aviso público no prueba que no lo haya hecho.

### ¿Esto me afecta si mi backup es a la nube de Microsoft o Google?

No, si tu copia va directo a un servicio de ese tipo sin pasar por un servidor AhsayCBS. Pero vale revisar el circuito completo antes de descartarlo, porque es común que un proveedor de IT ponga un servidor propio en el medio para centralizar las copias de todos sus clientes y después las mande a la nube. Lo que define si te toca es si hay un AhsayCBS en algún punto de esa cadena, no cuál es el destino final de los datos.
