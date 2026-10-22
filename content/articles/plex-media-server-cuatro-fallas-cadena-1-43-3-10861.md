---
title: "Plex Media Server: cuatro fallas explotadas en cadena y por qué 1.43.3 no alcanza"
subtitle: Plex dice que si tu servidor es anterior a 1.43.3.10861 y estaba expuesto a internet, hay que asumir que está comprometido. El parche salió el 1 de septiembre, la prueba de concepto el 16 de octubre y CISA sumó cuatro CVE al catálogo el 20.
excerpt: Cuatro fallas de Plex Media Server están bajo explotación activa. El fabricante pide llegar a 1.43.3.10861: estar en "1.43.3" no alcanza. Cómo verificarlo y qué buscar.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-10-22
updated: 2026-10-22
products: []
meta_title: "Plex Media Server: por qué 1.43.3 no alcanza"
meta_description: "Cuatro fallas de Plex Media Server explotadas en cadena. El fabricante pide llegar a la build 1.43.3.10861 y asumir compromiso si estabas expuesto."
---

Si administrás un **Plex Media Server**, la verificación es de un minuto y conviene hacerla antes de seguir leyendo: abrí Plex Web, andá a **Configuración → General** y mirá la versión. Necesitás **1.43.3.10861 o posterior**.

Y acá está el detalle que hace que esta nota exista: **estar en "1.43.3" no alcanza**. El número que importa es el cuarto, el de la build. El propio hilo del fabricante se titula "para v1.43.2 y anteriores", pero el texto adentro dice que la versión no afectada es **1.43.3.10861**. Hay builds de 1.43.3 por debajo de esa, y quedan dentro del rango afectado.

**La respuesta práctica, antes de todo lo demás:** actualizá a 1.43.3.10861 o más nuevo. Y si tu servidor era anterior a esa build y estaba alcanzable desde internet, Plex no dice "revisá si te pasó algo": dice textualmente que **hay que asumir que puede estar comprometido**. Eso cambia el trabajo del lunes, porque actualizar ya no es el final.

## Qué pasó entre el 1 de septiembre y el 20 de octubre

```svg
<svg viewBox="0 0 680 294" role="img" aria-label="Cronología de las cuatro fallas de Plex Media Server según el aviso del propio fabricante en su foro y el catálogo KEV de CISA. El 1 de septiembre de 2026 Plex publicó las versiones Plex Media Server 1.43.3 y Plex Desktop 1.115.0 para corregir varios problemas de seguridad, y avisó que los identificadores CVE todavía estaban pedidos y sin publicar. El 23 de septiembre de 2026 se publicaron los CVE y CISA emitió su aviso VA-26-266-01, que cubre cinco vulnerabilidades, todas corregidas en la build 1.43.3.10861. El 16 de octubre de 2026 se publicó una prueba de concepto pública, según el fabricante. El 19 de octubre de 2026 Plex actualizó el hilo para decir que las vulnerabilidades están bajo explotación activa y amplia, que vio ataques extendidos contra servidores expuestos a internet sin actualizar, que los servidores comprometidos se usaron para correr mineros de criptomonedas y software de proxy, y que si el servidor es anterior a 1.43.3.10861 y alcanzable desde internet hay que asumir que puede estar comprometido. El 20 de octubre de 2026 CISA sumó cuatro de los cinco CVE al catálogo KEV, con plazos del 23 de octubre y del 3 de noviembre. Al 22 de octubre de 2026 la versión correctora lleva 51 días disponible y el más corto de los dos plazos de CISA, el del 23 de octubre, vence al día siguiente">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">El parche salió 45 días antes de la prueba de concepto</text>

  <text x="38" y="54" font-size="10" font-weight="700" fill="currentColor">1 sep 2026</text>
  <text x="170" y="54" font-size="10.5" fill="currentColor" opacity="0.9">Plex publica 1.43.3 y Desktop 1.115.0. Sin CVE asignado</text>

  <text x="38" y="80" font-size="10" font-weight="700" fill="currentColor">23 sep 2026</text>
  <text x="170" y="80" font-size="10.5" fill="currentColor" opacity="0.9">Se publican los CVE y el aviso VA-26-266-01 de CISA</text>

  <text x="38" y="106" font-size="10" font-weight="700" fill="#e23a3a">16 oct 2026</text>
  <text x="170" y="106" font-size="10.5" fill="#e23a3a">Sale una prueba de concepto pública</text>

  <text x="38" y="132" font-size="10" font-weight="700" fill="#e23a3a">19 oct 2026</text>
  <text x="170" y="132" font-size="10.5" font-weight="700" fill="#e23a3a">Plex: explotación activa y amplia. Asumir compromiso</text>

  <text x="38" y="158" font-size="10" font-weight="700" fill="currentColor">20 oct 2026</text>
  <text x="170" y="158" font-size="10.5" fill="currentColor" opacity="0.9">CISA suma 4 de los 5 CVE al KEV</text>

  <text x="38" y="184" font-size="10" font-weight="700" fill="#e23a3a">22 oct 2026</text>
  <text x="170" y="184" font-size="10.5" font-weight="700" fill="#e23a3a">Hoy: el parche lleva 51 días. El plazo vence mañana</text>

  <rect x="20" y="202" width="620" height="46" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="222" font-size="10" fill="currentColor" opacity="0.9">Entre el parche y la explotación amplia pasaron 48 días. El que actualizó en septiembre</text>
  <text x="34" y="238" font-size="10" fill="currentColor" opacity="0.9">no tuvo que hacer nada más; el que no, hoy arranca por revisar si lo entraron.</text>

  <text x="20" y="282" font-size="9" fill="currentColor" opacity="0.65">Aviso de Plex en forums.plex.tv, aviso VA-26-266-01 de CISA y catálogo KEV, consultados el 22 de octubre de 2026.</text>
</svg>
```

La secuencia importa porque explica por qué esto aparece recién ahora. Plex publicó el aviso y las versiones corregidas el **1 de septiembre**, cuando todavía no había identificadores: el texto original dice que los CVE "fueron pedidos" y que iba a responder en el mismo hilo cuando se publicaran. Los CVE salieron el **23 de septiembre**, junto con el aviso **VA-26-266-01** de CISA.

Después, nada, durante 3 semanas. Hasta que el **16 de octubre** apareció una prueba de concepto pública. El **19 de octubre** Plex volvió al hilo con una actualización que cambia el tono por completo, y el **20** CISA metió cuatro de esas CVE en el catálogo de fallas explotadas.

## Las cuatro CVE del catálogo, y la quinta que no está

```svg
<svg viewBox="0 0 680 252" role="img" aria-label="Las cuatro CVE de Plex Media Server que CISA sumó al catálogo KEV el 20 de octubre de 2026, con la descripción del fabricante, el puntaje CVSS 3.1 y el plazo de CISA. CVE-2026-96652, falsificación de petición del lado del servidor sin autenticar en el endpoint del timeline del reproductor, CVSS 3.1 de 4,3, plazo 3 de noviembre. CVE-2026-96654, acceso a funciones internas de agente por la interfaz de loopback, CVSS 3.1 de 6,5, plazo 23 de octubre. CVE-2026-96655, divulgación del token de administrador del dueño del servidor, CVSS 3.1 de 4,3, plazo 3 de noviembre. CVE-2026-96656, escritura de archivos arbitrarios en el directorio Codecs a través de las preferencias del transcodificador, CVSS 3.1 de 7,2, plazo 23 de octubre. Los cuatro puntajes salen del CNA, no de un análisis propio de NVD: ninguna de las cuatro fichas tiene puntaje primario de NVD. El aviso VA-26-266-01 de CISA cubre una quinta falla, CVE-2026-96651, un path traversal con CVSS 3.1 de 6,5 que no está en el catálogo KEV, y que también se corrige con la misma build 1.43.3.10861">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Una sola actualización cierra las cinco</text>

  <text x="20" y="46" font-size="9" fill="currentColor" opacity="0.7">CVE</text>
  <text x="150" y="46" font-size="9" fill="currentColor" opacity="0.7">Qué permite, según Plex</text>
  <text x="488" y="46" font-size="9" fill="currentColor" opacity="0.7">CVSS 3.1</text>
  <text x="560" y="46" font-size="9" fill="currentColor" opacity="0.7">Plazo CISA</text>

  <line x1="20" y1="54" x2="640" y2="54" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="74" font-size="9.5" font-weight="700" fill="currentColor">CVE-2026-96652</text>
  <text x="150" y="74" font-size="9.5" fill="currentColor" opacity="0.9">SSRF sin autenticar en el timeline del reproductor</text>
  <text x="488" y="74" font-size="9.5" fill="currentColor" opacity="0.9">4,3</text>
  <text x="560" y="74" font-size="9.5" fill="currentColor" opacity="0.9">3 nov</text>

  <text x="20" y="96" font-size="9.5" font-weight="700" fill="currentColor">CVE-2026-96654</text>
  <text x="150" y="96" font-size="9.5" fill="currentColor" opacity="0.9">Funciones internas de agente por loopback</text>
  <text x="488" y="96" font-size="9.5" fill="currentColor" opacity="0.9">6,5</text>
  <text x="560" y="96" font-size="9.5" font-weight="700" fill="#e23a3a">23 oct</text>

  <text x="20" y="118" font-size="9.5" font-weight="700" fill="currentColor">CVE-2026-96655</text>
  <text x="150" y="118" font-size="9.5" fill="currentColor" opacity="0.9">Filtra el token de admin del dueño del servidor</text>
  <text x="488" y="118" font-size="9.5" fill="currentColor" opacity="0.9">4,3</text>
  <text x="560" y="118" font-size="9.5" fill="currentColor" opacity="0.9">3 nov</text>

  <text x="20" y="140" font-size="9.5" font-weight="700" fill="#e23a3a">CVE-2026-96656</text>
  <text x="150" y="140" font-size="9.5" font-weight="700" fill="#e23a3a">Escribe archivos en el directorio Codecs</text>
  <text x="488" y="140" font-size="9.5" font-weight="700" fill="#e23a3a">7,2</text>
  <text x="560" y="140" font-size="9.5" font-weight="700" fill="#e23a3a">23 oct</text>

  <line x1="20" y1="154" x2="640" y2="154" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="174" font-size="10" fill="currentColor" opacity="0.9">Los cuatro puntajes los puso el CNA. Ninguna de las cuatro fichas tiene puntaje propio de NVD.</text>

  <rect x="20" y="188" width="620" height="32" rx="5" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="188" width="620" height="32" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="34" y="208" font-size="10" font-weight="700" fill="#e23a3a">Hay una quinta: CVE-2026-96651, que no está en el KEV y se arregla con la misma build.</text>

  <text x="20" y="242" font-size="9" fill="currentColor" opacity="0.65">Catálogo KEV de CISA, aviso VA-26-266-01 de CISA y hilo de seguridad de Plex, consultados el 22 de octubre de 2026.</text>
</svg>
```

Las descripciones de la tabla salen del aviso de Plex. Y acá hay que decir algo que no cierra del todo entre las fuentes: **Plex y CISA no describen igual dos de las cuatro CVE**.

Para Plex, **CVE-2026-96655** es "divulgación del token de administrador del dueño del servidor". Para CISA y NVD, esa misma CVE es una SSRF que permite pedir direcciones internas o externas arbitrarias, y el robo del token aparece atribuido a **CVE-2026-96651**, el path traversal que no está en el KEV: el aviso de CISA dice que por ahí se puede leer cualquier archivo que el usuario pueda abrir, y que eso "incluye el PlexOnlineToken, que da control de la cuenta y del servidor". Lo mismo, en menor medida, con 96654: Plex habla de loopback y CISA de un parámetro `searchOne`.

**No voy a resolver la contradicción**, porque no tengo con qué: las dos son fuentes de primera mano y dicen cosas distintas. Lo que sí se puede afirmar, y es lo único que cambia la acción, es que **las cinco se corrigen con la misma build**, así que el mapeo exacto no altera lo que hay que hacer.

## Por qué el puntaje más alto es 7,2 y la cadena es ejecución de código sin autenticar

Esta es la parte que vale la pena mirar si priorizás por puntaje. De las cuatro CVE del catálogo, **dos tienen CVSS 3.1 de 4,3**, una 6,5 y la más alta **7,2**. Ninguna llega a 9. Por puntaje individual, dos de estas cuatro entran cómodas en la categoría de "lo vemos el mes que viene".

Encadenadas, el fabricante dice otra cosa: "**permiten a un atacante remoto no autenticado ejecutar código con los privilegios del proceso de Plex Media Server**".

```svg
<svg viewBox="0 0 680 268" role="img" aria-label="Por qué la suma de las cuatro fallas de Plex Media Server vale más que cada puntaje por separado. El puntaje CVSS 3.1 más alto de las cuatro es 7,2 y dos de ellas son 4,3, de modo que priorizando por puntaje individual dos de las cuatro parecen postergables. Pero según el fabricante, encadenadas permiten a un atacante remoto no autenticado ejecutar código con los privilegios del proceso de Plex Media Server. El mecanismo que el fabricante y CISA describen tiene tres piezas: primero una entrada que no necesita credenciales válidas, después la filtración del token de administrador del dueño del servidor, y finalmente la escritura de archivos en el directorio Codecs, donde Plex carga archivos .so al iniciar sin verificar firma ni permisos de ejecución. El resultado observado por el fabricante en servidores comprometidos fue el uso para minería de criptomonedas y para software de proxy que enruta tráfico de terceros por la conexión de la víctima">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Cuatro puntajes medianos, un resultado alto</text>

  <rect x="20" y="38" width="196" height="54" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="58" font-size="10" font-weight="700" fill="currentColor">1. Entrada</text>
  <text x="34" y="74" font-size="9.5" fill="currentColor" opacity="0.9">Sin credenciales válidas</text>
  <text x="34" y="87" font-size="9.5" fill="currentColor" opacity="0.9">del lado del atacante</text>

  <text x="228" y="68" font-size="13" fill="currentColor" opacity="0.5">→</text>

  <rect x="250" y="38" width="196" height="54" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="264" y="58" font-size="10" font-weight="700" fill="currentColor">2. Token</text>
  <text x="264" y="74" font-size="9.5" fill="currentColor" opacity="0.9">Se filtra el token de</text>
  <text x="264" y="87" font-size="9.5" fill="currentColor" opacity="0.9">admin del dueño</text>

  <text x="458" y="68" font-size="13" fill="currentColor" opacity="0.5">→</text>

  <rect x="480" y="38" width="160" height="54" rx="5" fill="#e23a3a" opacity="0.13"/>
  <rect x="480" y="38" width="160" height="54" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.4"/>
  <text x="494" y="58" font-size="10" font-weight="700" fill="#e23a3a">3. Codecs</text>
  <text x="494" y="74" font-size="9.5" font-weight="700" fill="#e23a3a">Escribe un .so que</text>
  <text x="494" y="87" font-size="9.5" font-weight="700" fill="#e23a3a">Plex carga al iniciar</text>

  <text x="20" y="122" font-size="10" fill="currentColor" opacity="0.9">El paso 3 es el que explica la detección: Plex carga los .so del directorio Codecs al arrancar,</text>
  <text x="20" y="138" font-size="10" fill="currentColor" opacity="0.9">y según el aviso de CISA lo hace sin verificar firma, bit de ejecución ni símbolos.</text>

  <line x1="20" y1="152" x2="640" y2="152" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="174" font-size="10" font-weight="700" fill="currentColor">Para qué lo usaron, según Plex:</text>
  <text x="38" y="194" font-size="9.5" fill="currentColor" opacity="0.9">Minería de criptomonedas en el servidor de la víctima.</text>
  <text x="38" y="211" font-size="9.5" fill="currentColor" opacity="0.9">Software de proxy, que enruta tráfico de otra gente por tu conexión a internet.</text>

  <rect x="20" y="224" width="620" height="18" rx="4" fill="currentColor" opacity="0.07"/>
  <text x="34" y="237" font-size="9.5" fill="currentColor" opacity="0.9">Puntaje individual más alto: 7,2. Resultado de la cadena: código sin autenticar.</text>

  <text x="20" y="260" font-size="9" fill="currentColor" opacity="0.65">Hilo de seguridad de Plex y aviso VA-26-266-01 de CISA, consultados el 22 de octubre de 2026.</text>
</svg>
```

Lo de los mineros y el proxy no es una hipótesis mía: es lo que Plex dice haber visto en servidores comprometidos. Y el software de proxy merece un párrafo propio, porque es el que más cara sale y menos se nota: enruta **tráfico de terceros** por tu conexión. Si alguien usa esa salida para algo ilegal, el que figura es el titular de la línea. Es el mismo problema que ya aparecía en [las fallas de routers Linksys, Edimax y Wavlink](/noticia/linksys-edimax-wavlink-17-criticas-routers-sin-parche): el equipo comprometido no se rompe, se alquila.

## ¿A quién afecta la cadena de Plex Media Server?

A quien corra **Plex Media Server en una build anterior a 1.43.3.10861**. Eso incluye a bastante más gente de la que se da por aludida:

**Si tenés Plex en un NAS**, es el caso más expuesto de todos, y por dos razones. El aviso original de Plex avisa que en dispositivos NAS la versión actualizada **puede no estar todavía en el gestor de paquetes del fabricante**, y que hay que instalar el paquete a mano. O sea que tener las actualizaciones automáticas al día del NAS no garantiza nada acá. Y además el NAS suele ser justo la máquina donde están los archivos que no querés perder.

**Si lo tenés en una máquina que comparte credenciales o unidades de red**, el alcance no termina en Plex. El propio aviso pide tratar como expuesto todo lo que la cuenta de servicio de Plex pudiera alcanzar: recursos montados y credenciales guardadas en la misma máquina o máquina virtual.

**Si abriste el puerto 32400 en el router** para ver tus cosas desde afuera, estás en el grupo que Plex describe como blanco de los ataques: servidores expuestos a internet sin actualizar. El acceso remoto es, justamente, para lo que la mayoría instala Plex.

**Si lo tenés en una PyME** —y pasa más de lo que parece, como servidor de contenido para una sala de espera, un local o un hotel—, es un equipo que probablemente nadie inventarió como "servidor" y que está en la misma red que lo demás. El encuadre general de ese problema está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

## ¿Quién puede ignorar el plazo del 23 de octubre?

Conviene separar dos cosas que se confunden seguido. El **plazo del 23 de octubre** —3 días desde que CISA dio de alta las fichas— es una obligación para las agencias federales civiles de Estados Unidos, por la directiva BOD 26-04 que el propio catálogo cita. Si tenés una PyME en Latinoamérica, ese plazo **no te obliga**: es una señal de urgencia, no una fecha que te aplique.

Lo que sí te aplica es la otra parte, que no tiene plazo porque ya pasó: **la explotación es activa y amplia desde el 16 de octubre**, según el fabricante.

Quien **nunca expuso el servidor a internet** —sin redirección de puertos, sin acceso remoto habilitado— está en una posición bastante mejor, y el propio Plex lo trata distinto: la indicación de asumir compromiso es para servidores "alcanzables desde internet". Igual conviene actualizar, porque la cadena arranca sin credenciales y una red local no es un perímetro.

Lo que **no** sirve como razón para ignorarlo: que Plex sea "una cosa de películas". El proceso corre en una máquina real, con acceso a archivos reales, y lo que los atacantes instalaron según el fabricante no tiene nada que ver con multimedia.

## ¿Cómo sé si mi Plex Media Server está comprometido?

Esta lista es la del fabricante, no mía, y es lo más útil del aviso. Plex pide buscar cuatro cosas:

1. **Procesos lanzados por Plex Media Server que no reconozcas.** El aviso nombra tres ejemplos concretos que vio: `myawesomeproxy`, `mylovelyminer` y `x12345`.
2. **Archivos `.so` o `.dll` desconocidos en la carpeta Codecs** de Plex. Es el directorio del paso 3 de la cadena, y el que Plex carga al iniciar.
3. **Conexiones salientes a `proxystream.st`.**
4. **Uso sostenido de CPU alto mientras no se está reproduciendo nada.** Es el síntoma clásico de un minero, y el más fácil de ver sin herramientas.

Las dos primeras se revisan a mano. Para la tercera y la cuarta, y para tener registro de lo que pasó en lugar de una foto de ahora, hace falta algo que mire el equipo de forma continua: es exactamente la diferencia que separa un antivirus de un EDR, y está desarrollada en [cuándo se justifica un EDR en una PyME](/guia/edr-o-antivirus-cuando-se-justifica-pyme) y en [la categoría de antivirus y EDR](/productos/antivirus-y-edr).

Y una advertencia sobre el token: si el token de administrador se filtró, la sesión del atacante sigue siendo válida aunque cambies la contraseña, hasta que la cierres. Es el mismo mecanismo que explica [cómo el robo de sesión saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa). Por eso el aviso pide cerrar sesión en **todos** los dispositivos, no solo cambiar la clave.

## ¿Qué hago si tengo Plex expuesto a internet?

En este orden, que es el del fabricante con una aclaración mía donde hace falta:

1. **Verificá la versión y actualizá a 1.43.3.10861 o posterior.** Plex Web → Configuración → General. Si estás en un NAS y no aparece, bajá el paquete del sitio de Plex e instalalo a mano.
2. **Revisá los cuatro indicadores de la sección anterior.** Antes de dar por cerrado el tema.
3. **Si encontrás algo, o si no podés descartar el compromiso: reinstalá desde cero.** El aviso es explícito en que conviene reconstruir el servidor desde una instalación limpia en lugar de reusar el directorio de datos viejo. Como mínimo, borrá todo el contenido de la carpeta Codecs y revisá `Preferences.xml` por opciones de transcodificación que no pusiste vos. Es la misma lógica de [reconstruir en lugar de parchear](/noticia/citrix-netscaler-cve-2026-88771-reconstruir-no-parchear) que ya apareció con NetScaler: cuando lo que entró pudo dejar algo escrito, el parche no lo saca.
4. **Cerrá sesión en todos los dispositivos y cambiá la contraseña de tu cuenta Plex.** En ese orden importa poco; que estén las dos, mucho.
5. **Tratá como expuesto todo lo que la cuenta de Plex podía alcanzar.** Unidades montadas y credenciales guardadas en la misma máquina o VM.
6. **Si no necesitás el acceso remoto, apagalo** en Configuración → Acceso remoto, y evitá redirigir el puerto 32400 salvo que te haga falta. Si lo necesitás de verdad, la forma de darlo sin publicar el puerto está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn) y en [la categoría de VPN y acceso remoto](/productos/vpn-y-acceso-remoto).
7. **Si tenías archivos en esa máquina, revisá que tu copia de seguridad siga siendo recuperable**, y que no sea solo una carpeta sincronizada con el equipo comprometido. Qué pedirle a una copia está en [la categoría de backup y recuperación](/productos/backup-y-recuperacion).

Una aclaración sobre las fuentes, porque esta nota se apoya en tres documentos y no en coberturas. **Lo que sí pude leer:** el hilo de seguridad de Plex en `forums.plex.tv`, con el aviso original del 1 de septiembre y la actualización del 19 de octubre, de donde salen las descripciones de las CVE, la build corregida, la frase sobre explotación activa y amplia, los indicadores de compromiso y los pasos; el aviso **VA-26-266-01** de CISA en formato CSAF, de donde salen los cinco CVE, los puntajes CVSS 3.1 y la build corregida; y el feed JSON del catálogo KEV, de donde salen las altas del 20 de octubre y los plazos. Los puntajes los verifiqué también contra la API de NVD, ficha por ficha. **Lo que no pude confirmar:** en qué fecha salió cada build intermedia de 1.43.3, así que no digo cuáles builds de esa serie quedaron afectadas más allá de lo que dicen el fabricante y CISA, que es "anteriores a 1.43.3.10861". Tampoco describo la prueba de concepto ni enlazo el análisis técnico público que referencia NVD: el mecanismo y la detección están arriba, el paso a paso no.

---

## Preguntas frecuentes sobre las fallas de Plex Media Server

### Estoy en la versión 1.43.3, ¿ya estoy a salvo?

No necesariamente, y es el error más fácil de cometer acá. La versión no afectada, según el propio fabricante, es **1.43.3.10861**, con el cuarto número incluido. Existen builds de la serie 1.43.3 anteriores a esa, y el aviso de CISA define el rango afectado como "anterior a 1.43.3.10861". Mirá la versión completa en Plex Web → Configuración → General y comparala con los cuatro números, no con los tres primeros.

### Actualicé recién. ¿Alcanza con eso?

Depende de si tu servidor estaba alcanzable desde internet antes de actualizar. Si no lo estaba, actualizar es lo principal. Si lo estaba y corría una build anterior a 1.43.3.10861, Plex dice que hay que **asumir que puede estar comprometido**, y actualizar no saca lo que ya quedó escrito: el paso final de la cadena escribe archivos en la carpeta Codecs que Plex carga al iniciar. Por eso el aviso pide revisar esa carpeta, revisar `Preferences.xml` y, si no podés descartarlo, reinstalar desde cero.

### Tengo Plex en un NAS Synology o QNAP y dice que está actualizado, ¿me cubre?

Conviene verificarlo contra el número de build y no contra lo que diga el gestor de paquetes. El aviso original de Plex dice que en dispositivos NAS la versión actualizada **puede no estar todavía disponible en el gestor de paquetes del fabricante**, y que en ese caso se puede instalar el paquete a mano. Es el escenario donde más fácil se queda alguien atrás creyendo que está al día.

### ¿Por qué CISA le puso dos plazos distintos a fallas de la misma cadena?

El catálogo les dio plazo **23 de octubre** a CVE-2026-96654 y CVE-2026-96656 —3 días desde el alta— y **3 de noviembre** a CVE-2026-96652 y CVE-2026-96655, que son las dos de puntaje más bajo: 14 días. En la práctica la distinción no cambia nada, porque **las cinco fallas se corrigen con la misma build**: una sola actualización cierra todo, así que la fecha que manda es la más corta. Y si no sos una agencia federal de Estados Unidos, ninguno de los dos plazos te obliga; lo que te apura es la explotación activa.

### Nunca abrí el puerto 32400, ¿me tengo que preocupar?

Estás bastante mejor, y el fabricante lo trata distinto: la indicación de asumir compromiso es para servidores alcanzables desde internet. Pero conviene actualizar igual, por dos motivos. La cadena arranca sin necesidad de credenciales válidas, así que cualquiera que llegue a la red alcanza el servicio. Y una red local con equipos de visitas, de familia o de empleados no es un perímetro confiable. Actualizar cuesta minutos; el resto de la lista, en tu caso, probablemente no haga falta.
