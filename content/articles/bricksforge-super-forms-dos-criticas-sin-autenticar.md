---
title: "Bricksforge y Super Forms: dos críticas sin autenticar"
subtitle: Una sube y ejecuta un PHP, la otra borra carpetas enteras incluida la raíz de WordPress. Las dos son plugins de pago, y el changelog del fabricante no dice que el arreglo era de seguridad.
excerpt: Dos plugins comerciales de WordPress con fallas críticas sin autenticar. Qué versión mirar, y por qué en un plugin de pago el aviso no llega por el panel.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-10-08
updated: 2026-10-08
products: []
meta_title: "Bricksforge y Super Forms: dos críticas"
meta_description: "Dos plugins de pago de WordPress con críticas sin autenticar: una ejecuta PHP, la otra borra la raíz. Qué versión mirar y por qué no avisa el panel."
---

El catálogo de vulnerabilidades explotadas de CISA no suma entradas desde el 4 de octubre —un hueco de 4 días que, medido contra los últimos 12 meses, es rutinario—, así que la nota de hoy sale de las fichas publicadas en NVD. Y ahí aparecieron **dos fallas críticas sin autenticar en plugins de WordPress**, las dos registradas por Wordfence como autoridad de numeración.

- **CVE-2026-85097**, en **Bricksforge**, con **9,8**: subida y ejecución de un archivo PHP sin credenciales.
- **CVE-2026-17609**, en **Super Forms**, con **9,1**: borrado recursivo de carpetas arbitrarias, sin credenciales, incluida la raíz de WordPress.

## Qué hace cada una: Bricksforge ejecuta, Super Forms borra

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Las dos fallas críticas de plugins de WordPress publicadas el 8 de octubre de 2026, según las fichas de Wordfence. La primera es CVE-2026-85097 en Bricksforge, con puntaje 9,8, en versiones hasta la 3.1.8.9 inclusive: el atacante obtiene primero un token válido desde un punto de la interfaz que es público, sube un archivo que es imagen y PHP a la vez al directorio temporal donde la validación de tipo sí funciona, y después envía un formulario donde la ruta del archivo apunta a la imagen validada pero el campo de nombre que controla el atacante termina en punto php. El resultado es ejecución de código PHP sin credenciales. La segunda es CVE-2026-17609 en Super Forms, con puntaje 9,1, en versiones hasta la 6.3.316 inclusive: el atacante manda declaraciones de campos que no se validan contra el formulario real, y una protección de ruta que debía confinar el borrado se puede sortear porque la función que sube un nivel de directorio elimina la barra final. El resultado es borrado recursivo de carpetas, incluida la raíz del sitio, y requiere que el administrador tenga activada la opción de borrar archivos del servidor después de cada envío">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Dos mecanismos distintos, el mismo tipo de error</text>

  <rect x="20" y="38" width="620" height="88" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="38" width="620" height="88" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="57" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-85097 — Bricksforge, 9,8 — hasta la 3.1.8.9</text>
  <text x="38" y="76" font-size="10" fill="currentColor" opacity="0.9">La validación de tipo de archivo se hace sobre la imagen subida, y funciona bien.</text>
  <text x="38" y="92" font-size="10" fill="currentColor" opacity="0.9">Pero el nombre con el que queda guardado sale de otro campo, que el atacante</text>
  <text x="38" y="108" font-size="10" fill="currentColor" opacity="0.9">controla y hace terminar en .php. Resultado: código ejecutándose en tu servidor.</text>

  <rect x="20" y="140" width="620" height="88" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="140" width="620" height="88" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="159" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2026-17609 — Super Forms, 9,1 — hasta la 6.3.316</text>
  <text x="38" y="178" font-size="10" fill="currentColor" opacity="0.9">Los campos que llegan no se comparan contra el formulario real, y la protección</text>
  <text x="38" y="194" font-size="10" fill="currentColor" opacity="0.9">que debía confinar el borrado se sortea porque subir un nivel de directorio</text>
  <text x="38" y="210" font-size="10" fill="currentColor" opacity="0.9">borra la barra final. Resultado: borrado recursivo, incluida la raíz del sitio.</text>

  <text x="20" y="245" font-size="11" font-weight="600" fill="#e23a3a">En las dos, un componente valida una cosa y otro usa otra.</text>
</svg>
```

Vale subrayar ese cierre porque es la tercera vez en una semana que aparece la misma forma de error en este sitio. El 1 de octubre fue [la regla de autenticación de Cisco que leía la dirección distinto que la API](/noticia/cisco-sd-wan-manager-cve-2026-76504-admin-sin-clave); el 2, el byte nulo de FortiMail. Hoy, un plugin que valida el tipo de **un** archivo y guarda con el nombre que viene en **otro** campo.

En el caso de Super Forms hay una condición previa que conviene conocer porque puede sacarte de la lista: la explotación **requiere que el administrador haya activado la opción de borrar archivos del servidor después de cada envío**. Wordfence aclara igual que es una función documentada y de uso común, así que no es una salvedad que convenga asumir sin mirar.

Y una precisión sobre la clasificación: Wordfence asignó **CWE-434**, subida de archivos sin restricción, a las dos. Encaja perfecto en la de Bricksforge. En la de Super Forms, que es un borrado por recorrido de rutas y no una subida, la etiqueta queda forzada. No cambia el riesgo, pero sí cambia lo que encontrás si buscás por clase de falla.

## Ninguno de los dos está en el directorio de WordPress

Acá está lo que distingue a esta tanda de [la falla del núcleo de WordPress que el sitio cubrió hace 11 días](/noticia/wordpress-cve-2026-87902-explotada-el-mismo-dia), y es lo que más conviene entender si administrás un sitio.

```svg
<svg viewBox="0 0 680 245" role="img" aria-label="Diferencia entre cómo llega una actualización de seguridad en un plugin del directorio oficial de WordPress y en un plugin comercial. En el plugin del directorio oficial, el aviso aparece en el panel de administración del sitio, la actualización se puede aplicar con un clic o de forma automática, y el directorio publica la cantidad de instalaciones activas, así que la exposición se puede dimensionar. En el plugin comercial, en cambio, la actualización depende de que la licencia del fabricante esté activa y su propio mecanismo funcione, el directorio no publica instalaciones activas porque el plugin no está ahí, y si la licencia venció el sitio sigue funcionando pero deja de recibir actualizaciones de seguridad sin avisar. Se comprobó que tanto Bricksforge como Super Forms devuelven error de plugin no encontrado en la API del directorio de wordpress punto org, consultada el 8 de octubre de 2026">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Cómo llega el parche según de dónde venga el plugin</text>

  <rect x="20" y="38" width="620" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="38" width="620" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="57" font-size="10.5" font-weight="700" fill="currentColor">Plugin del directorio oficial</text>
  <text x="38" y="76" font-size="10" fill="currentColor" opacity="0.9">El aviso aparece en el panel del sitio. Se actualiza con un clic o solo.</text>
  <text x="38" y="94" font-size="10" fill="currentColor" opacity="0.9">Y el directorio publica las instalaciones activas: la exposición se puede medir.</text>

  <rect x="20" y="128" width="620" height="92" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="128" width="620" height="92" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="147" font-size="10.5" font-weight="700" fill="#e23a3a">Plugin comercial, como estos dos</text>
  <text x="38" y="166" font-size="10" fill="currentColor" opacity="0.9">La actualización depende de que la licencia esté activa y de que el</text>
  <text x="38" y="182" font-size="10" fill="currentColor" opacity="0.9">mecanismo del fabricante funcione. No hay cifra pública de instalaciones.</text>
  <text x="38" y="202" font-size="10" font-weight="700" fill="#e23a3a">Si la licencia venció, el sitio sigue andando y deja de recibir parches.</text>

  <text x="20" y="240" font-size="9.5" fill="currentColor" opacity="0.7">Comprobado: la API del directorio de wordpress.org devuelve "Plugin not found" para los dos, al 8 de octubre de 2026.</text>
</svg>
```

Lo comprobé contra la API del directorio: **los dos devuelven "Plugin not found"**. Bricksforge se vende en el sitio del fabricante como complemento del constructor Bricks, y Super Forms se distribuye a clientes. Eso tiene tres consecuencias prácticas.

La primera es que **nadie puede dimensionar la exposición**, ni yo ni nadie. Para la falla del núcleo de WordPress pude calcular que los temas nombrados sumaban 450.000 instalaciones activas, porque el directorio publica ese dato. Acá no existe. Cuando leas una cifra de sitios afectados por estas dos fallas, preguntate de dónde salió.

La segunda es que **la actualización no llega por el camino habitual**. Un plugin de pago se actualiza si su licencia está activa y su propio mecanismo funciona. Si la licencia venció —porque cambió quien administraba, porque se renovó el hosting y no la licencia, porque el sitio lo armó una agencia que ya no trabaja con vos— el sitio sigue funcionando igual y **deja de recibir actualizaciones de seguridad sin que nadie avise**.

La tercera es que la responsabilidad se difumina. Es la continuación de lo que planteó [la guía de seguridad de WordPress del sitio](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting): el hosting cubre una parte, el núcleo se actualiza solo, y los plugins de pago quedan en una zona donde hay que acordar explícitamente quién los mira.

## Los dos arreglos salieron 21 días antes, sin decir que eran de seguridad

Esta es la parte que más me llamó la atención al verificar, y la que cambia el consejo práctico.

```svg
<svg viewBox="0 0 680 225" role="img" aria-label="Cronología de los arreglos y la publicación de las dos fallas de plugins de WordPress. El 28 de agosto de 2026 se publicó la versión 3.1.8.9 de Bricksforge, que es la última versión vulnerable según la ficha del CVE. El 17 de septiembre de 2026 ocurrieron dos cosas: Bricksforge publicó la versión 4.0.0, y el autor de Super Forms fusionó un cambio titulado endurecimiento de seguridad en la rama de soporte prolongado 6.3.x. El 8 de octubre de 2026, veintiún días después, se publicaron las fichas de los dos CVE. La conclusión es que el arreglo existía tres semanas antes del aviso público, pero en ninguno de los dos casos el registro de cambios del fabricante nombra el problema: el changelog de Bricksforge no menciona este CVE en ninguna parte, y el cambio de Super Forms se fusionó sin descripción y sin nombrar el borrado de carpetas">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">El arreglo llegó 21 días antes del aviso</text>

  <text x="38" y="54" font-size="10" font-weight="700" fill="currentColor">28 ago</text>
  <text x="130" y="54" font-size="10.5" fill="currentColor" opacity="0.9">Bricksforge 3.1.8.9 — la última versión vulnerable</text>

  <text x="38" y="80" font-size="10" font-weight="700" fill="currentColor">17 sep</text>
  <text x="130" y="80" font-size="10.5" fill="currentColor" opacity="0.9">Bricksforge publica 4.0.0</text>

  <text x="38" y="104" font-size="10" font-weight="700" fill="currentColor">17 sep</text>
  <text x="130" y="104" font-size="10.5" fill="currentColor" opacity="0.9">Super Forms fusiona "endurecimiento de seguridad" en la rama 6.3.x</text>

  <text x="38" y="130" font-size="10" font-weight="700" fill="#e23a3a">8 oct</text>
  <text x="130" y="130" font-size="10.5" font-weight="700" fill="#e23a3a">Se publican las fichas de los dos CVE</text>

  <rect x="20" y="146" width="620" height="54" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="146" width="620" height="54" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="166" font-size="10.5" font-weight="700" fill="#e23a3a">Y en ninguno de los dos el registro de cambios nombra el problema</text>
  <text x="38" y="184" font-size="10" fill="currentColor" opacity="0.9">El changelog de Bricksforge no menciona este CVE. El cambio de Super Forms se fusionó sin descripción.</text>

  <text x="20" y="218" font-size="9.5" fill="currentColor" opacity="0.7">Changelog público de Bricksforge y el cambio público del repositorio de Super Forms, consultados el 8 de octubre de 2026.</text>
</svg>
```

Vale ser preciso sobre lo que verifiqué y lo que no, porque acá es fácil afirmar de más.

Del lado de **Bricksforge**: la ficha del CVE dice que son vulnerables las versiones **hasta la 3.1.8.9 inclusive**, y esa versión es del 28 de agosto. La siguiente publicada es la **4.0.0, del 17 de septiembre**, cuyo registro de cambios menciona que *"bundles the latest security fixes"* y describe un arreglo de escalada de privilegios en otro componente. **Lo que no hace, en ninguna parte de la página, es nombrar este CVE.** Así que no puedo decir "el fabricante confirma que la 4.0.0 corrige CVE-2026-85097": lo que puedo decir es que la versión vulnerable es la 3.1.8.9 y que la siguiente es la 4.0.0.

Del lado de **Super Forms**: la ficha dice hasta la **6.3.316 inclusive**. El autor fusionó el 17 de septiembre un cambio titulado *"fix(security): hardening update"* en la rama de soporte prolongado 6.3.x. Ese cambio **se fusionó sin descripción**, no nombra el borrado de carpetas ni la función señalada en el CVE, y la página no indica en qué número de versión salió. Así que tampoco puedo mapear el arreglo a una versión concreta: lo que corresponde es pedirle al fabricante la última de la rama 6.3.x y confirmar que el número sea mayor que 6.3.316.

La consecuencia práctica no es menor. **El lugar donde un administrador prudente mira para decidir si una actualización es urgente —el registro de cambios— no llevaba la señal.** Quien leyó el changelog de Bricksforge en septiembre vio un cambio de versión mayor con mejoras y arreglos, no un motivo para actualizar el mismo día. El primer aviso claro es la ficha publicada hoy, 21 días después.

## ¿A quién afectan las fallas de Bricksforge y Super Forms?

A quien tenga un sitio en WordPress con **Bricksforge en la 3.1.8.9 o anterior**, o con **Super Forms en la 6.3.316 o anterior**.

El perfil es bastante concreto. **Bricksforge** es un complemento del constructor Bricks, así que aparece en sitios armados por agencias o profesionales que eligieron ese constructor, no en instalaciones hechas con el editor por omisión. **Super Forms** es un constructor de formularios de pago, y los formularios son lo que recibe archivos de afuera, que es justamente el camino de estas fallas.

Dicho de otro modo: esto suele estar en **el sitio que te armó alguien más**. Y si ese alguien ya no te mantiene el sitio, nadie está mirando las licencias ni las versiones de estos plugins.

Es la misma lógica que en [el formulario de Elementor Pro que subía un PHP](/noticia/elementor-pro-formulario-sube-un-php): el componente que más expuesto está es el que recibe archivos, y en un sitio institucional casi siempre hay uno. Y como en [el plugin de inicio de sesión único que daba acceso de administrador](/noticia/miniorange-saml-sso-acceso-de-administrador), lo que define el riesgo no es cuánta gente usa el plugin sino qué puede hacer el plugin cuando falla.

## ¿Quién puede ignorar CVE-2026-85097 y CVE-2026-17609?

Quien no tenga ninguno de los dos plugins, que es la mayoría de los sitios en WordPress.

Quien tenga Bricksforge en la **4.0.0** o posterior.

Quien tenga Super Forms en una versión de la rama 6.3.x **posterior a la 6.3.316**, confirmada con el fabricante.

Y, solo para la de Super Forms, quien tenga **desactivada** la opción de borrar archivos del servidor después de cada envío. Eso saca a tu sitio de esa falla puntual, aunque conviene actualizar igual y no quedarse con una configuración como única defensa.

Lo que **no** alcanza para ignorarlas: que el sitio sea "solo institucional" y no tenga datos de clientes. La de Bricksforge termina en ejecución de código en tu servidor, y la de Super Forms puede borrarte el sitio entero. Ninguna de las dos necesita que tengas información valiosa.

## ¿Cómo sé qué versión de Bricksforge o Super Forms tengo?

1. **Entrá al panel de WordPress, a Plugins.** La versión instalada figura al lado de cada nombre. Compará con 3.1.8.9 y 6.3.316.
2. **Mirá si el plugin dice que la licencia está activa.** Los plugins de pago suelen tener su propia pantalla de licencia, y si está vencida ahí es donde lo dice. Esa pantalla es la que explica por qué el panel nunca te ofreció la actualización.
3. **Si no administrás el sitio vos**, esta es la pregunta concreta para quien lo haga, por escrito: en qué versión están esos plugins y si las licencias están vigentes. Las dos cosas, porque la segunda explica la primera.

Para saber si ya lo usaron contra tu sitio, el síntoma de cada falla es distinto y conviene saber qué buscar:

- **Para la de Bricksforge**, archivos `.php` nuevos o modificados en los directorios de subidas y en los temporales del plugin, con fecha reciente. Un archivo PHP en una carpeta de subidas no tiene ninguna razón legítima de estar ahí.
- **Para la de Super Forms**, lo notarías: el síntoma es que falten carpetas o que el sitio deje de cargar. No es una falla sigilosa.

Como ninguna de las dos figura como explotada, **no hay indicadores publicados del fabricante que buscar**, así que esto es revisión general. Y si encontrás un PHP que no pusiste, el problema ya no es actualizar: ahí hace falta restaurar desde una copia anterior, que es el argumento de siempre a favor de tener [copias con historia](/productos/backup-y-recuperacion).

## ¿Qué hago si tengo Bricksforge o Super Forms instalado?

En este orden:

1. **Mirá las dos versiones hoy.** Es una pantalla y resuelve la pregunta.
2. **Si Bricksforge está en 3.1.8.9 o anterior, pasá a la 4.0.0 o posterior.** Tené en cuenta que es un salto de versión mayor, así que corresponde copia de seguridad previa y revisar que el sitio renderice bien después, no solo que cargue. Si tu hosting tiene entorno de pruebas, el salto se prueba ahí antes: [la reseña de Hostinger del sitio](/resena/resena-hostinger-wordpress-4-meses) cuenta un caso donde una actualización de plugin aplicada directo en producción dejó el sitio lento durante horas.
3. **Si Super Forms está en 6.3.316 o anterior, conseguí la última de la rama 6.3.x con el fabricante** y confirmá el número. Si no podés, desactivá mientras tanto la opción de borrar archivos después de los envíos, que es la condición que la falla necesita.
4. **Revisá el estado de las licencias de todos los plugins de pago del sitio**, no solo de estos dos. Una licencia vencida es un plugin que dejó de recibir parches, y es el hallazgo más probable de esta revisión.
5. **Anotá quién mira esto.** Si el sitio lo armó un tercero, el acuerdo sobre quién renueva licencias y aplica actualizaciones conviene que esté escrito, porque es exactamente el hueco donde viven estas dos fallas.

El encuadre más largo de qué cubre el hosting y qué queda de tu lado está en [la guía de seguridad de WordPress para PyMEs](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting), y los términos, en el [glosario](/guia/glosario-ciberseguridad-pymes).

---

## Preguntas frecuentes sobre CVE-2026-85097 y CVE-2026-17609

### ¿Por qué mi panel de WordPress no me avisó de estas actualizaciones?

Porque ninguno de los dos plugins está en el directorio oficial de WordPress, y lo comprobé: la API del directorio devuelve "plugin no encontrado" para los dos. Los plugins de pago se actualizan por un mecanismo propio del fabricante que depende de que la licencia esté activa. Si venció, el plugin sigue funcionando y el panel no muestra nada. Es el modo de falla más silencioso que existe en un sitio en WordPress.

### ¿Cuántos sitios usan Bricksforge y Super Forms?

No se sabe, y conviene desconfiar de cualquier cifra que circule. El directorio oficial de WordPress publica las instalaciones activas de los plugins que aloja, y ese dato es el que permite dimensionar una falla. Estos dos no están ahí, así que no hay número público. Para la falla del núcleo de WordPress de hace 11 días sí pude calcular las instalaciones de los temas nombrados, justamente porque estaban en el directorio.

### ¿La versión 4.0.0 de Bricksforge corrige la falla?

Es lo que indica la ficha del CVE por implicación, porque limita el problema a las versiones hasta la 3.1.8.9 inclusive y la siguiente publicada es la 4.0.0. Pero vale la precisión: **el registro de cambios del fabricante no nombra este CVE en ninguna parte**, así que no hay una confirmación explícita de su parte que yo pueda citar. La acción prudente es estar en 4.0.0 o posterior y, si necesitás certeza, preguntárselo al fabricante.

### Tengo Super Forms pero no uso la opción de borrar archivos. ¿Estoy a salvo?

De esa falla puntual, sí, porque la explotación requiere que esa opción esté activada. Dos salvedades. Una: confirmá que de verdad está desactivada en vez de suponerlo, porque Wordfence aclara que es una función de uso común. Dos: una configuración no es una corrección, y cualquiera con acceso al panel puede activarla sin saber lo que implica. Conviene actualizar igual.

### ¿Conviene evitar los plugins de pago entonces?

No es la conclusión, y sería injusta: los plugins de pago suelen tener más mantenimiento que los gratuitos abandonados, y acá los dos fabricantes ya tenían el arreglo hecho 3 semanas antes del aviso público —publicado en una versión, en el caso de Bricksforge; fusionado en la rama de soporte, en el de Super Forms. Lo que muestra el caso es otra cosa: con un plugin de pago, el camino del parche pasa por una licencia y por el mecanismo del fabricante, no por el panel. Eso hay que administrarlo a propósito —saber qué licencias tenés, cuándo vencen y quién las renueva— en lugar de esperar que el sitio te avise.
