---
title: "PHP 8.2.36, 8.3.37, 8.4.28 y 8.5.13: 14 fallas corregidas, cinco críticas"
subtitle: Las versiones que corregían PHP2Shell en octubre ya no son las últimas. Las del 19 de noviembre cierran 14 fallas de seguridad, cinco con 9,2 según el proyecto PHP. Las críticas piden configuraciones poco comunes, pero dos vuelven sobre las sesiones, el mismo terreno de PHP2Shell.
excerpt: PHP 8.2.36, 8.3.37, 8.4.28 y 8.5.13 corrigen 14 fallas, cinco críticas. Qué configuración te expone, cómo saber qué versión tenés y qué pedirle al hosting.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-11-20
updated: 2026-11-20
products: []
meta_title: "PHP 8.4.28 y 8.3.37: cinco fallas críticas"
meta_description: "PHP 8.2.36, 8.3.37, 8.4.28 y 8.5.13 corrigen 14 fallas, cinco críticas. Qué configuración te expone, cómo saber qué versión tenés y qué pedir al hosting."
---

El proyecto PHP publicó el 19 de noviembre [versiones de seguridad para todas sus ramas con soporte](https://www.php.net/archive/2026.php#2026-11-19-3): **8.2.36**, **8.3.37**, **8.4.28** y **8.5.13**. Según el [ChangeLog de PHP 8](https://www.php.net/ChangeLog-8.php#8.4.28), corrigen **14 fallas de seguridad**. Cinco tienen 9,2 en CVSS 4.0, el puntaje que les asigna el propio proyecto como autoridad de numeración; NVD todavía no las analizó.

Si actualizaste en octubre por [PHP2Shell](/noticia/php2shell-php-cve-2026-109618-109619-versiones), las versiones de entonces, 8.2.35, 8.3.36, 8.4.27 y 8.5.12, ya no son las últimas: duraron 29 días. Hay que volver a actualizar. No hay reportes de explotación de estas 14 fallas al 20 de noviembre.

| Rama | Versión que corrige | Nota |
|---|---|---|
| PHP 8.5 | 8.5.13 | — |
| PHP 8.4 | 8.4.28 | — |
| PHP 8.3 | 8.3.37 | — |
| PHP 8.2 | 8.2.36 | Deja de recibir correcciones el 31 de diciembre de 2026 |
| PHP 8.1 o anterior | Sin corrección | Sin soporte; los registros incluyen versiones anteriores a 8.2 |

```svg
<svg viewBox="0 0 680 448" role="img" aria-label="Las 14 fallas de seguridad que corrigen PHP 8.2.36, 8.3.37, 8.4.28 y 8.5.13, publicadas el 19 de noviembre de 2026, con el puntaje CVSS 4.0 que les asigna el proyecto PHP, de mayor a menor: CVE-2026-126714, sesión escrita a medias, 9,2; CVE-2026-115560, serializador de sesión cruzado, 9,2; CVE-2026-129496, caché de zona horaria, 9,2; CVE-2026-129289, php-fpm: pedido contrabandeado, 9,2; CVE-2026-129481, sesión cortada al cerrar, 9,2; CVE-2026-129291, php-fpm: escalada local, 8,8; CVE-2026-127960, encabezados http plegados, 8,7; CVE-2026-128064, https en mayúsculas sin cifrar, 8,2; CVE-2026-128090, firma phar degradada, 7,7; CVE-2026-129538, php-cgi y path_info, 6,9; CVE-2026-129501, dom: doble liberación, 6,3; CVE-2026-15353, soap: fuga de datos, 6,3; CVE-2026-129506, soap: entidades dtd, 6,3; CVE-2026-129292, php-fpm: xss en estado, 5,3. Cinco tienen 9,2. La marcada es CVE-2026-126714, la que parte del manejador de sesiones por defecto.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">PHP: las 14 fallas de noviembre, por puntaje</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">CVSS 4.0, del proyecto PHP. El rojo marca la que parte de las sesiones por defecto.</text>
  <path d="M242.0 58 V400" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="242" y="415" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">0</text>
  <path d="M329.0 58 V400" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="329" y="415" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">2,5</text>
  <path d="M416.0 58 V400" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="416" y="415" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">5</text>
  <path d="M503.0 58 V400" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="503" y="415" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">7,5</text>
  <path d="M590.0 58 V400" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="590" y="415" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">10</text>
  <text x="232" y="77" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">Sesión escrita a medias</text>
  <path d="M242,64 H558.2 A4,4 0 0 1 562.2,68 V78 A4,4 0 0 1 558.2,82 H242 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>CVE-2026-126714, Sesión escrita a medias: 9,2 en CVSS 4.0</title></path>
  <text x="568.2" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">9,2</text>
  <text x="232" y="101" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Serializador de sesión cruzado</text>
  <path d="M242,88 H558.2 A4,4 0 0 1 562.2,92 V102 A4,4 0 0 1 558.2,106 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-115560, Serializador de sesión cruzado: 9,2 en CVSS 4.0</title></path>
  <text x="568.2" y="101" class="ch-value" font-size="11" font-weight="600" fill="currentColor">9,2</text>
  <text x="232" y="125" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Caché de zona horaria</text>
  <path d="M242,112 H558.2 A4,4 0 0 1 562.2,116 V126 A4,4 0 0 1 558.2,130 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129496, Caché de zona horaria: 9,2 en CVSS 4.0</title></path>
  <text x="568.2" y="125" class="ch-value" font-size="11" font-weight="600" fill="currentColor">9,2</text>
  <text x="232" y="149" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">PHP-FPM: pedido contrabandeado</text>
  <path d="M242,136 H558.2 A4,4 0 0 1 562.2,140 V150 A4,4 0 0 1 558.2,154 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129289, PHP-FPM: pedido contrabandeado: 9,2 en CVSS 4.0</title></path>
  <text x="568.2" y="149" class="ch-value" font-size="11" font-weight="600" fill="currentColor">9,2</text>
  <text x="232" y="173" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Sesión cortada al cerrar</text>
  <path d="M242,160 H558.2 A4,4 0 0 1 562.2,164 V174 A4,4 0 0 1 558.2,178 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129481, Sesión cortada al cerrar: 9,2 en CVSS 4.0</title></path>
  <text x="568.2" y="173" class="ch-value" font-size="11" font-weight="600" fill="currentColor">9,2</text>
  <text x="232" y="197" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">PHP-FPM: escalada local</text>
  <path d="M242,184 H544.2 A4,4 0 0 1 548.2,188 V198 A4,4 0 0 1 544.2,202 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129291, PHP-FPM: escalada local: 8,8 en CVSS 4.0</title></path>
  <text x="554.2" y="197" class="ch-value" font-size="11" font-weight="600" fill="currentColor">8,8</text>
  <text x="232" y="221" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Encabezados HTTP plegados</text>
  <path d="M242,208 H540.8 A4,4 0 0 1 544.8,212 V222 A4,4 0 0 1 540.8,226 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-127960, Encabezados HTTP plegados: 8,7 en CVSS 4.0</title></path>
  <text x="550.8" y="221" class="ch-value" font-size="11" font-weight="600" fill="currentColor">8,7</text>
  <text x="232" y="245" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">HTTPS en mayúsculas sin cifrar</text>
  <path d="M242,232 H523.4 A4,4 0 0 1 527.4,236 V246 A4,4 0 0 1 523.4,250 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-128064, HTTPS en mayúsculas sin cifrar: 8,2 en CVSS 4.0</title></path>
  <text x="533.4" y="245" class="ch-value" font-size="11" font-weight="600" fill="currentColor">8,2</text>
  <text x="232" y="269" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Firma PHAR degradada</text>
  <path d="M242,256 H506.0 A4,4 0 0 1 510.0,260 V270 A4,4 0 0 1 506.0,274 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-128090, Firma PHAR degradada: 7,7 en CVSS 4.0</title></path>
  <text x="516" y="269" class="ch-value" font-size="11" font-weight="600" fill="currentColor">7,7</text>
  <text x="232" y="293" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">PHP-CGI y PATH_INFO</text>
  <path d="M242,280 H478.1 A4,4 0 0 1 482.1,284 V294 A4,4 0 0 1 478.1,298 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129538, PHP-CGI y PATH_INFO: 6,9 en CVSS 4.0</title></path>
  <text x="488.1" y="293" class="ch-value" font-size="11" font-weight="600" fill="currentColor">6,9</text>
  <text x="232" y="317" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">DOM: doble liberación</text>
  <path d="M242,304 H457.2 A4,4 0 0 1 461.2,308 V318 A4,4 0 0 1 457.2,322 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129501, DOM: doble liberación: 6,3 en CVSS 4.0</title></path>
  <text x="467.2" y="317" class="ch-value" font-size="11" font-weight="600" fill="currentColor">6,3</text>
  <text x="232" y="341" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">SOAP: fuga de datos</text>
  <path d="M242,328 H457.2 A4,4 0 0 1 461.2,332 V342 A4,4 0 0 1 457.2,346 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-15353, SOAP: fuga de datos: 6,3 en CVSS 4.0</title></path>
  <text x="467.2" y="341" class="ch-value" font-size="11" font-weight="600" fill="currentColor">6,3</text>
  <text x="232" y="365" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">SOAP: entidades DTD</text>
  <path d="M242,352 H457.2 A4,4 0 0 1 461.2,356 V366 A4,4 0 0 1 457.2,370 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129506, SOAP: entidades DTD: 6,3 en CVSS 4.0</title></path>
  <text x="467.2" y="365" class="ch-value" font-size="11" font-weight="600" fill="currentColor">6,3</text>
  <text x="232" y="389" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">PHP-FPM: XSS en estado</text>
  <path d="M242,376 H422.4 A4,4 0 0 1 426.4,380 V390 A4,4 0 0 1 422.4,394 H242 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>CVE-2026-129292, PHP-FPM: XSS en estado: 5,3 en CVSS 4.0</title></path>
  <text x="432.4" y="389" class="ch-value" font-size="11" font-weight="600" fill="currentColor">5,3</text>
  <text x="20" y="438" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: registros de PHP en NVD y ChangeLog de PHP 8.4.28, 19 de noviembre de 2026.</text>
</svg>
```

## ¿Qué configuración de PHP expone a las cinco fallas críticas?

El anuncio previo del 18 de noviembre lo adelantó: *"The issues rated CRITICAL require either non-default configuration or uncommon setups."* No es lo mismo que PHP2Shell, que afectaba a casi cualquier instalación. Según las descripciones de los registros en NVD, esto es lo que hace falta para cada una:

1. **[CVE-2026-126714](https://nvd.nist.gov/vuln/detail/CVE-2026-126714), sesión escrita a medias.** El manejador de sesiones por defecto escribe los datos nuevos sobre el archivo viejo sin vaciarlo. Si la escritura se interrumpe, quedan restos que después se leen como objetos. El atacante necesita influir en los datos de la sesión, por ejemplo con *session upload progress*, y que la aplicación tenga una cadena de clases aprovechable. Es la que más se acerca a una instalación común.
2. **CVE-2026-115560, serializador de sesión cruzado.** Requiere *session upload progress* activado y que la misma sesión se lea con dos serializadores distintos. PHP ya había avisado de esta el 23 de octubre.
3. **CVE-2026-129496, caché de zona horaria.** Afecta a compilaciones sin soporte de hilos que escriben errores en un archivo durante el arranque, por ejemplo por un `date.timezone` inválido. Con eso, un pedido armado puede tirar los procesos de PHP-FPM, mod_php o CGI, y según el registro la ejecución de código *"has been demonstrated with known memory addresses"*.
4. **CVE-2026-129289, pedido contrabandeado en PHP-FPM.** Solo si el servidor web reutiliza las conexiones con PHP-FPM, algo que no viene por defecto: `fastcgi_keep_conn on` en nginx o `enablereuse=on` en Apache con mod_proxy_fcgi. Ahí, un formulario armado puede colar un pedido falso que termine ejecutando código.
5. **CVE-2026-129481, sesión cortada al cerrar.** Requiere un error fatal en un tipo particular de función de la aplicación mientras se cierra la sesión, y que el script no esté en la caché de OPcache. El registro dice que la ejecución de código *"has not been demonstrated"*. Afecta desde la rama 8.3.

Entre las demás hay una de 8,8 que permite a quien ya ejecuta código en PHP-FPM tomar el proceso principal, que suele correr como root, si está activado `catch_workers_output`. Ese ajuste viene apagado por defecto, pero según el registro está activado en las imágenes oficiales de Docker de PHP-FPM.

## ¿Por qué las sesiones de PHP vuelven a estar en el centro?

Porque *session upload progress*, la función que informa el avance de una subida de archivos, aparece otra vez. Apagarla ya era una de las mitigaciones de PHP2Shell y, según Check Point Research, los atacantes de [los firewalls de Check Point](/noticia/check-point-php2shell-cve-2026-123039-root-gateway), que llegaron 7 días después del parche, dejaban rastros con el texto `upload_progress_pwn` en los archivos de sesión. El 23 de octubre, 2 días después de corregir PHP2Shell, PHP publicó un aviso informativo que sugería apagarla hasta la versión siguiente para cerrar *"an unlikely RCE vector"*, que hoy es CVE-2026-115560. Y CVE-2026-126714 la menciona como forma de meter datos en la sesión. Quien usa PHP en Windows tuvo además una recompilación el 26 de octubre por [la falla de Brotli](/noticia/brotli-cve-2026-111497-node-dotnet-descomprimir).

```svg
<svg viewBox="0 0 680 244" role="img" aria-label="Cronología de las versiones de seguridad de PHP en 2026. 21 de octubre: PHP corrige PHP2Shell con las versiones 8.2.35, 8.3.36, 8.4.27 y 8.5.12. 23 de octubre: un aviso informativo recomienda apagar session.upload_progress.enabled hasta la próxima versión, por CVE-2026-115560. 30 de octubre: empiezan los ataques a firewalls de Check Point que usan PHP2Shell. 18 de noviembre: PHP anuncia una versión de seguridad con fallas críticas. 19 de noviembre: salen PHP 8.2.36, 8.3.37, 8.4.28 y 8.5.13, que corrigen 14 fallas; las versiones de octubre ya no son las últimas. 31 de diciembre: PHP 8.2 deja de recibir correcciones de seguridad.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">PHP: las versiones de octubre ya no son las últimas</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">2026. El rojo marca las versiones que hay que instalar ahora.</text>
  <path d="M84 58 V206" class="ch-axis" stroke="currentColor" stroke-opacity="0.3" fill="none"/>
  <text x="20" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">21 oct.</text>
  <circle cx="84" cy="66" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>21 oct.: PHP corrige PHP2Shell: 8.2.35, 8.3.36, 8.4.27 y 8.5.12</title></circle>
  <text x="100" y="70" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">PHP corrige PHP2Shell: 8.2.35, 8.3.36, 8.4.27 y 8.5.12</text>
  <text x="20" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">23 oct.</text>
  <circle cx="84" cy="92" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>23 oct.: Aviso: apagar session.upload_progress hasta la próxima versión</title></circle>
  <text x="100" y="96" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Aviso: apagar session.upload_progress hasta la próxima versión</text>
  <text x="20" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">30 oct.</text>
  <circle cx="84" cy="118" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>30 oct.: Atacan firewalls de Check Point con PHP2Shell</title></circle>
  <text x="100" y="122" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Atacan firewalls de Check Point con PHP2Shell</text>
  <text x="20" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">18 nov.</text>
  <circle cx="84" cy="144" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>18 nov.: PHP anuncia una versión de seguridad con fallas críticas</title></circle>
  <text x="100" y="148" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">PHP anuncia una versión de seguridad con fallas críticas</text>
  <text x="20" y="174" class="ch-strong" font-size="10.5" font-weight="600" fill="currentColor">19 nov.</text>
  <circle cx="84" cy="170" r="5" class="ch-dot-hi" fill="#e23a3a"><title>19 nov.: Salen 8.2.36, 8.3.37, 8.4.28 y 8.5.13, con 14 fallas</title></circle>
  <text x="100" y="174" class="ch-strong" font-size="10.5" fill="currentColor">Salen 8.2.36, 8.3.37, 8.4.28 y 8.5.13, con 14 fallas</text>
  <text x="20" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">31 dic.</text>
  <circle cx="84" cy="196" r="4.5" class="ch-dot" fill="currentColor" fill-opacity="0.6"><title>31 dic.: PHP 8.2 deja de recibir correcciones de seguridad</title></circle>
  <text x="100" y="200" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">PHP 8.2 deja de recibir correcciones de seguridad</text>
  <text x="20" y="234" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuentes: noticias de php.net y Check Point Research.</text>
</svg>
```

## ¿Cómo sé qué versión de PHP tiene mi sitio?

1. **Mirá el panel del hosting.** La mayoría de los paneles muestran la versión de PHP de cada sitio y permiten cambiarla. Fijate en el número completo, no solo en la rama: 8.3.36 está afectada y 8.3.37 no.
2. **Si tenés un servidor propio, preguntale al sistema.** `php -v` en la consola da la versión de la línea de comandos, que puede no ser la misma que usa el servidor web. La del sitio se ve con una página con `phpinfo()`, que hay que borrar apenas se consulta.
3. **Si te lo administra alguien, preguntá por escrito.** Igual que con PHP2Shell, la pregunta no es "¿están al día?", sino "¿en qué versión completa de PHP está mi sitio y cuándo pasa a 8.2.36, 8.3.37, 8.4.28 o 8.5.13?". Cómo pedir y contestar estas cosas por escrito está en [la guía para responder cuestionarios de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

## ¿Qué hago si tengo un sitio en PHP?

1. **Actualizá a la versión de tu rama.** Es lo único que cierra las 14 fallas juntas. Si estás en PHP 8.2, aprovechá para pasar a una rama más nueva: le quedan 6 semanas de soporte.
2. **Apagá `session.upload_progress.enabled` si no lo usás.** Ya lo recomendaba la nota de PHP2Shell aun después de parchear, y es la función que reaparece en dos de las críticas.
3. **Revisá cómo se conecta el servidor web con PHP-FPM.** Si usás `fastcgi_keep_conn on` en nginx o `enablereuse=on` en Apache, actualizar es urgente; si no lo necesitás, apagalo.
4. **Si usás las imágenes oficiales de Docker de PHP-FPM, actualizá la imagen.** Traen activado `catch_workers_output`, la condición de la falla de escalada local.
5. **Si seguís en PHP 8.1 o anterior, el problema es otro.** No hay corrección. La única salida es migrar, y si el sitio es un WordPress, que este mes ya tuvo [su propia actualización urgente](/noticia/wordpress-cve-2026-119977-ventana-de-actualizacion), los detalles de qué le toca al hosting y qué a vos están en [la guía de seguridad de WordPress para PyMEs](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting).

## ¿Quién puede ignorar las versiones de PHP de noviembre?

Quien no tenga sitios ni sistemas en PHP. Para el resto, no hay una configuración que quede afuera de las 14 fallas, aunque las críticas pidan condiciones poco comunes. Si tu sitio está en un hosting compartido, la actualización depende del proveedor, y conviene confirmar que ya la aplicó. El orden general de prioridades está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** las noticias de php.net del 23 de octubre y del 18 y 19 de noviembre; el ChangeLog de PHP 8.4.28; las fichas de NVD de los 14 CVE; y el informe de Check Point Research sobre PHP2Shell. **Sin confirmar:** si alguna de las 14 fallas se está explotando, porque no hay reportes al 20 de noviembre; y el texto completo de los avisos de seguridad del proyecto en GitHub, que no pudimos abrir desde este entorno, así que las condiciones salen de los registros en NVD.

## Preguntas frecuentes sobre las versiones de seguridad de PHP de noviembre

### ¿Qué versión de PHP tengo que instalar en noviembre de 2026?

La de tu rama: 8.2.36, 8.3.37, 8.4.28 o 8.5.13, publicadas el 19 de noviembre. Las versiones de octubre que corregían PHP2Shell, como 8.3.36 o 8.4.27, quedaron atrás.

### ¿Las fallas de PHP de noviembre ya se están explotando?

No hay reportes de explotación al 20 de noviembre de 2026. Las cinco críticas requieren configuraciones poco comunes, según el anuncio del propio proyecto PHP.

### ¿Me afecta si ya actualicé por PHP2Shell en octubre?

Sí. Las versiones de octubre corrigen PHP2Shell, pero no estas 14 fallas. Hay que pasar a 8.2.36, 8.3.37, 8.4.28 o 8.5.13.

### ¿Qué es session.upload_progress y conviene apagarlo?

Es una función de PHP que informa el avance de una subida de archivos y viene activada por defecto. Apagarla era una mitigación de PHP2Shell, y aparece en dos de las fallas críticas de noviembre. Si tu aplicación no la usa, conviene apagarla.

### ¿Hasta cuándo tiene soporte PHP 8.2?

Hasta el 31 de diciembre de 2026, según el proyecto PHP. Después no recibe más correcciones de seguridad, así que conviene pasar a 8.3, 8.4 o 8.5.
