---
title: "ProFTPD, BIND y Struts: cinco fallas viejas al KEV"
subtitle: Tres de las cinco tienen diez años o más, y el plazo de CISA venció el 11 de octubre. Lo que las une es una etiqueta que el catálogo usó 20 veces en 1739 entradas: el componente está adentro de otro producto.
excerpt: CISA sumó cinco vulnerabilidades explotadas el 8 de octubre y ninguna es de 2026. Qué son, por qué una falla de 2015 vuelve a importar y cómo saber si está en tu red.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-10-12
updated: 2026-10-12
products: []
meta_title: "ProFTPD, BIND y Struts: cinco fallas viejas"
meta_description: "CISA sumó cinco fallas explotadas el 8 de octubre y ninguna es de 2026: tres tienen 10 años o más. Qué son y cómo saber si están en tu red."
---

El catálogo de vulnerabilidades explotadas de CISA pasó a la versión **2026.10.08** con **1739 entradas**, y las cinco que entraron ese día tienen algo en común que no se ve seguido: **ninguna es de 2026**. Dos son de 2015, una de 2016, una de 2021 y una de 2023.

El plazo que fijó el catálogo para las cinco fue el **11 de octubre** —tres días después del alta—, así que **ya venció**. Si el inventario no se revisó, el primer paso de hoy no es parchear: es averiguar si alguno de estos componentes está corriendo en algún lado.

## Qué entró al catálogo de CISA el 8 de octubre

```svg
<svg viewBox="0 0 680 252" role="img" aria-label="Las cinco vulnerabilidades que CISA agregó a su catálogo de explotadas el 8 de octubre de 2026, con el plazo del 11 de octubre para todas. Primera: CVE-2015-3306 en ProFTPD 1.3.5, de 11 años de antigüedad, puntaje 10,0 asignado por CISA-ADP, permite leer y escribir archivos sin credenciales a través del módulo mod_copy. Segunda: CVE-2015-5477 en ISC BIND anterior a 9.9.7-P2 y 9.10.2-P3, de 11 años, puntaje 7,5 asignado por CISA-ADP, provoca la caída del servicio de DNS mediante consultas TKEY. Tercera: CVE-2016-3081 en Apache Struts de la 2.3.20 a la 2.3.28, de 10 años, puntaje 8,1 que coinciden NVD y CISA-ADP, ejecuta código remoto pero solo si está activada la invocación dinámica de métodos. Cuarta: CVE-2021-3199 en ONLYOFFICE Document Server anterior a 5.6.3, de 5 años, puntaje 9,8 que coinciden NVD y CISA-ADP, ejecuta código mediante un recorrido de rutas en la subida de imágenes. Quinta: CVE-2023-22894 en Strapi, de 3 años, con puntajes que no coinciden: NVD le da 4,9 medio y CISA-ADP 7,2 alto, y expone hashes de contraseñas pero requiere acceso previo al panel de administración. Ninguna de las cinco figura con uso conocido en campañas de ransomware">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Cinco altas el 8 de octubre, plazo vencido el 11</text>

  <text x="20" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">CVE</text>
  <text x="130" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">PRODUCTO Y EDAD</text>
  <text x="330" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">PUNTAJE (FUENTE)</text>
  <text x="462" y="42" font-size="9" font-weight="700" fill="currentColor" opacity="0.6">QUÉ PERMITE</text>

  <line x1="20" y1="49" x2="640" y2="49" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="70" font-size="10" font-weight="700" fill="#e23a3a">CVE-2015-3306</text>
  <text x="130" y="70" font-size="10" fill="currentColor" opacity="0.9">ProFTPD 1.3.5 · 11 años</text>
  <text x="330" y="70" font-size="10" font-weight="700" fill="#e23a3a">10,0 (CISA-ADP)</text>
  <text x="462" y="70" font-size="9.5" fill="currentColor" opacity="0.9">escribe archivos sin clave</text>

  <text x="20" y="96" font-size="10" font-weight="700" fill="currentColor">CVE-2015-5477</text>
  <text x="130" y="96" font-size="10" fill="currentColor" opacity="0.9">ISC BIND · 11 años</text>
  <text x="330" y="96" font-size="10" fill="currentColor" opacity="0.9">7,5 (CISA-ADP)</text>
  <text x="462" y="96" font-size="9.5" fill="currentColor" opacity="0.9">tira abajo el DNS</text>

  <text x="20" y="122" font-size="10" font-weight="700" fill="currentColor">CVE-2016-3081</text>
  <text x="130" y="122" font-size="10" fill="currentColor" opacity="0.9">Apache Struts 2.3.x · 10 años</text>
  <text x="330" y="122" font-size="10" fill="currentColor" opacity="0.9">8,1 (NVD y CISA-ADP)</text>
  <text x="462" y="122" font-size="9.5" fill="currentColor" opacity="0.9">ejecuta código, con DMI</text>

  <text x="20" y="148" font-size="10" font-weight="700" fill="#e23a3a">CVE-2021-3199</text>
  <text x="130" y="148" font-size="10" fill="currentColor" opacity="0.9">ONLYOFFICE Docs · 5 años</text>
  <text x="330" y="148" font-size="10" font-weight="700" fill="#e23a3a">9,8 (NVD y CISA-ADP)</text>
  <text x="462" y="148" font-size="9.5" fill="currentColor" opacity="0.9">ejecuta código al subir</text>

  <text x="20" y="174" font-size="10" font-weight="700" fill="currentColor">CVE-2023-22894</text>
  <text x="130" y="174" font-size="10" fill="currentColor" opacity="0.9">Strapi · 3 años</text>
  <text x="330" y="174" font-size="10" fill="currentColor" opacity="0.9">4,9 NVD / 7,2 CISA-ADP</text>
  <text x="462" y="174" font-size="9.5" fill="currentColor" opacity="0.9">hashes, ya con admin</text>

  <rect x="20" y="190" width="620" height="30" rx="5" fill="currentColor" opacity="0.07"/>
  <text x="34" y="209" font-size="10" fill="currentColor" opacity="0.9">Las cinco con el mismo plazo: 11 de octubre. Ninguna figura con uso conocido en ransomware.</text>

  <text x="20" y="240" font-size="9" fill="currentColor" opacity="0.65">Feed JSON del catálogo de CISA (versión 2026.10.08) y fichas de NVD, consultados el 12 de octubre de 2026.</text>
</svg>
```

Dos precisiones sobre esa tabla, porque los puntajes no salen todos del mismo lado.

En las dos fallas de 2015, **el puntaje moderno lo puso CISA, no el fabricante ni NVD**. Lo verifiqué contra la API del programa CVE de MITRE: la fuente `134c704f-9b21-4f2e-91b3-4a467353bcc0` que aparece en las fichas de NVD es **CISA-ADP**. NVD, por su lado, tiene en esas dos entradas su puntaje propio en la escala vieja (CVSS 2): 10,0 para ProFTPD y 7,8 para BIND. Son métricas distintas, no una corrección de la otra.

Y en **Strapi los puntajes no coinciden**: NVD le da **4,9 (medio)** y CISA-ADP **7,2 (alto)**. La diferencia tiene sentido cuando se lee la descripción: la falla **requiere que el atacante ya tenga acceso al panel de administración**. Con ese acceso puede filtrar, según la ficha de NVD, hashes de contraseñas y tokens de recuperación de todos los usuarios. Es grave como segundo paso, no como puerta de entrada.

Hay además una contradicción **dentro de la propia ficha de Strapi** que conviene conocer antes de salir a actualizar: el texto dice que afecta "hasta la 4.5.5", mientras que el análisis de versiones de la misma ficha marca el rango **desde la 3.2.1 hasta antes de la 4.8.0**. No puedo resolver cuál manda. Lo prudente es tomar el rango más amplio y apuntar a **4.8.0 o posterior**.

## Por qué una falla de 2015 vuelve al catálogo en 2026

Acá está lo que explica la tanda, y no es que alguien haya descubierto algo nuevo en ProFTPD.

```svg
<svg viewBox="0 0 680 258" role="img" aria-label="Por qué una vulnerabilidad vieja de un componente de código abierto sigue siendo actual: el componente viene incrustado dentro de productos comerciales. Según el análisis de versiones afectadas de la ficha de NVD, la falla CVE-2015-5477 de ISC BIND no solo afecta a BIND instalado a mano, sino también a Juniper Junos con veintiún versiones listadas, a Oracle Solaris 10 y 11.2, a HP-UX en sus versiones 11.11, 11.23 y 11.31, a NetApp clustered Data ONTAP, a Apple OS X Server 4.1.5 y a HP OpenVMS. La falla CVE-2016-3081 de Apache Struts afecta a los sistemas de almacenamiento Huawei OceanStor en diez familias de modelos, a Huawei FireHunter6000, a Oracle FLEXCUBE Private Banking, a Oracle MICROS Retail XBRi y a Oracle Siebel E-Billing. La conclusión práctica es que nadie instala Struts ni BIND a propósito en estos casos: vienen adentro del firmware de un equipo o de una aplicación comprada, y por eso el inventario de software no los muestra">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Nadie "instaló" Struts: vino adentro de otra cosa</text>

  <rect x="20" y="36" width="620" height="92" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="36" width="620" height="92" rx="6" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
  <text x="36" y="55" font-size="10.5" font-weight="700" fill="currentColor">CVE-2015-5477 · el componente es ISC BIND (el servicio de DNS)</text>
  <text x="36" y="74" font-size="9.5" fill="currentColor" opacity="0.9">Además de BIND instalado a mano, la ficha de NVD lista como afectados:</text>
  <text x="36" y="92" font-size="9.5" fill="currentColor" opacity="0.9">Juniper Junos (21 versiones) · Oracle Solaris 10 y 11.2 · HP-UX 11.11, 11.23 y 11.31</text>
  <text x="36" y="110" font-size="9.5" fill="currentColor" opacity="0.9">NetApp clustered Data ONTAP · Apple OS X Server 4.1.5 · HP OpenVMS</text>

  <rect x="20" y="142" width="620" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="142" width="620" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="36" y="161" font-size="10.5" font-weight="700" fill="#e23a3a">CVE-2016-3081 · el componente es Apache Struts (una biblioteca Java)</text>
  <text x="36" y="180" font-size="9.5" fill="currentColor" opacity="0.9">Almacenamiento Huawei OceanStor (10 familias de modelos) · Huawei FireHunter6000</text>
  <text x="36" y="198" font-size="9.5" fill="currentColor" opacity="0.9">Oracle FLEXCUBE Private Banking · MICROS Retail XBRi · Siebel E-Billing</text>

  <text x="20" y="238" font-size="10.5" font-weight="700" fill="#e23a3a">Por eso el inventario de software no lo muestra: no es un programa que figure en la lista.</text>
  <text x="20" y="252" font-size="9" fill="currentColor" opacity="0.65">Rangos de versiones afectadas de las fichas de NVD, consultadas el 12 de octubre de 2026.</text>
</svg>
```

Ese es el mecanismo, y el catálogo lo dice con todas las letras. Las cinco entradas llevan la misma nota: la falla **"puede afectar a un componente de código abierto, una biblioteca de terceros, un protocolo o una implementación propietaria que podría ser usada por distintos productos"**.

No es una nota de rutina. La conté en el catálogo entero.

## Esa etiqueta aparece en 20 de 1739 entradas

```svg
<svg viewBox="0 0 680 232" role="img" aria-label="Medición sobre el catálogo completo de CISA, que tiene 1739 entradas al 12 de octubre de 2026. Primero, la edad de las vulnerabilidades al momento de entrar al catálogo: el 44,2 por ciento entra el mismo año en que se publicó el CVE, y solo el 5 por ciento de las 303 entradas de los últimos 12 meses tenía 10 años o más al ingresar. La mediana de edad al ingresar, en los últimos 12 meses, es de cero años, es decir el mismo año. Segundo, la etiqueta que advierte que la falla está en un componente de código abierto o una biblioteca de terceros aparece en solo 20 de las 1739 entradas, es decir el 1,2 por ciento, y apareció por primera vez el 23 de enero de 2025. De esas 20 entradas, 5 son las de esta única tanda del 8 de octubre de 2026, o sea la cuarta parte de todas las que existen. Tercero, en 12 meses hubo 145 días con altas al catálogo y solo 3 de esos días tuvieron dos o más entradas de 10 años o más: el 8 de octubre de 2026 con 3 de 5, el 26 de agosto de 2026 con 2 de 6 y el 20 de mayo de 2026 con 5 de 7">
  <text x="20" y="22" font-size="12.5" font-weight="700" fill="currentColor">Qué tan raro es esto, contado sobre 1739 entradas</text>

  <text x="20" y="48" font-size="10" fill="currentColor" opacity="0.9">Entran el mismo año del CVE</text>
  <rect x="250" y="39" width="168" height="12" rx="2" fill="currentColor" opacity="0.5"/>
  <text x="426" y="49" font-size="10" font-weight="700" fill="currentColor">44,2%</text>

  <text x="20" y="72" font-size="10" fill="currentColor" opacity="0.9">Tienen 10 años o más al entrar</text>
  <rect x="250" y="63" width="19" height="12" rx="2" fill="#e23a3a"/>
  <text x="277" y="73" font-size="10" font-weight="700" fill="#e23a3a">5% (últimos 12 meses)</text>

  <text x="20" y="96" font-size="10" fill="currentColor" opacity="0.9">Llevan la etiqueta de componente</text>
  <rect x="250" y="87" width="5" height="12" rx="2" fill="#e23a3a"/>
  <text x="263" y="97" font-size="10" font-weight="700" fill="#e23a3a">1,2% — 20 de 1739</text>

  <line x1="20" y1="112" x2="640" y2="112" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>

  <text x="20" y="132" font-size="10.5" font-weight="700" fill="#e23a3a">5 de esas 20 entraron el 8 de octubre: la cuarta parte de todas, en un día.</text>

  <text x="20" y="156" font-size="10" fill="currentColor" opacity="0.9">En 12 meses hubo 145 días con altas. Solo 3 tuvieron 2 o más entradas de 10 años o más:</text>
  <text x="38" y="176" font-size="10" fill="currentColor" opacity="0.9">8 oct 2026 — 3 de 5 entradas</text>
  <text x="38" y="193" font-size="10" fill="currentColor" opacity="0.9">26 ago 2026 — 2 de 6 entradas</text>
  <text x="38" y="210" font-size="10" fill="currentColor" opacity="0.9">20 may 2026 — 5 de 7 entradas</text>

  <text x="20" y="228" font-size="9" fill="currentColor" opacity="0.65">Cálculo propio sobre el feed JSON del catálogo de CISA, versión 2026.10.08.</text>
</svg>
```

La mediana de edad de una entrada al ingresar al catálogo, en los últimos 12 meses, es **cero años**: lo normal es que se sume una falla del año en curso. Que entren tres de diez años o más en la misma tanda pasó **3 veces en 145 días con altas**.

Dicho de otro modo: esto no es el catálogo haciendo limpieza de archivo. Es una tanda deliberada sobre componentes viejos que siguen corriendo adentro de equipos y aplicaciones que nadie clasifica como "ProFTPD" ni como "Struts". Es el mismo problema que cuando [el catálogo sumó una falla de ownCloud de 2023 con tres días de plazo](/noticia/owncloud-cve-2023-49105-plazo-tres-dias): la fecha del CVE no dice nada sobre si te toca hoy.

## ¿A quién afectan las cinco fallas del 8 de octubre?

Por orden de probabilidad real en una PyME, no por puntaje:

**ONLYOFFICE Document Server anterior a 5.6.3** es la más probable de las cinco. Es la suite de oficina autoalojada que mucha gente instaló al lado de un Nextcloud para editar documentos en el navegador, y queda corriendo en un servidor propio que se actualiza cuando alguien se acuerda. La falla es un recorrido de rutas en la subida de imágenes que termina en ejecución de código. Es el caso de [software autoalojado donde el parche depende de vos](/noticia/zimbra-trueconf-software-autoalojado-parche-propio), sin panel que avise.

**ProFTPD 1.3.5** aparece en servidores Linux viejos que quedaron sirviendo archivos por FTP. La ficha de NVD lo marca además en Debian 7, 8 y 9, Fedora 20 a 22 y SUSE Linux Enterprise Server 15. Si hay un servidor de archivos al que nadie entra desde hace años, ahí hay que mirar.

**ISC BIND** y **Apache Struts** rara vez están instalados a mano en una PyME, pero sí pueden estar **adentro de un equipo**: un router Juniper, un almacenamiento NetApp o Huawei, un Solaris o HP-UX heredado. Eso no se busca en la lista de programas; se busca en el modelo y la versión de firmware del equipo, igual que en [las fallas de firmware de MikroTik RouterOS](/noticia/mikrotik-routeros-mikrotrick-usuario-ops).

**Strapi** afecta a quien tenga ese gestor de contenidos headless, y solo escala si el atacante ya entró al panel de administración.

## ¿Quién puede ignorar el aviso de ProFTPD y BIND?

Quien no tenga ninguno de los cinco componentes, que es la mayoría de las PyMEs: ni servidor FTP propio, ni DNS propio, ni aplicaciones Java viejas, ni ONLYOFFICE autoalojado, ni Strapi.

Quien tenga **ONLYOFFICE Document Server en 5.6.3 o posterior**, que es casi cualquier instalación hecha en los últimos años: la versión con el arreglo salió hace tiempo.

Quien tenga **Apache Struts con la invocación dinámica de métodos desactivada**. La condición está en el aviso del propio Apache: la falla aplica "cuando Dynamic Method Invocation está activada", y el fabricante recomienda desactivarla como alternativa a actualizar.

Quien tenga **Strapi 4.8.0 o posterior**, tomando el rango más amplio de los dos que da la ficha.

Lo que **no** alcanza: que el equipo "no esté expuesto a internet" en el caso de BIND. Un DNS interno que se puede tirar abajo desde la red local deja sin resolución a toda la oficina, y es el tipo de corte que paraliza sin que haya robo de nada.

## ¿Cómo sé si tengo ProFTPD, BIND o Struts en mi red?

El problema de esta tanda es justamente que no se resuelve mirando la lista de programas instalados. Tres caminos, del más rápido al más incómodo:

1. **Para ONLYOFFICE y Strapi, que son aplicaciones que alguien instaló a propósito:** entrá a la interfaz y mirá la versión. Compará con 5.6.3 y con 4.8.0. Es una pantalla y cierra el tema.
2. **Para ProFTPD:** preguntá si queda algún servidor sirviendo archivos por FTP. En un Linux, `proftpd -v` responde la versión. Si nadie sabe contestar si hay un FTP, la respuesta más probable es que sí lo hay y que no lo mira nadie.
3. **Para BIND y Struts, que vienen adentro de otra cosa:** armá la lista de equipos de red y almacenamiento con marca, modelo y versión de firmware, y consultá los avisos de cada fabricante por su cuenta. Es la parte tediosa, y es la única que sirve: el dato que necesitás no está en el equipo, está en el aviso del fabricante que lo armó.

Para saber si ya lo usaron contra tu sitio, lo que se busca es distinto en cada caso:

- **En ProFTPD**, archivos nuevos o modificados con fecha reciente en los directorios que sirve el FTP, sobre todo archivos ejecutables donde solo debería haber documentos. La falla permite escribir, y escribir es lo que deja rastro.
- **En ONLYOFFICE**, archivos inesperados en los directorios de subida del servidor de documentos.
- **En BIND**, caídas o reinicios del servicio sin causa clara en los registros: el síntoma de esa falla es que el proceso se muere.
- **En Strapi**, accesos al panel de administración que no reconozcas, porque la falla empieza después de ese acceso.

Ninguna de las cinco figura en el catálogo con uso conocido en campañas de ransomware, así que **no hay indicadores publicados que buscar**: esto es revisión general. Y si aparece un archivo que nadie puso, el problema deja de ser actualizar y pasa a ser restaurar desde [una copia con historia](/productos/backup-y-recuperacion).

## ¿Qué hago si encuentro ProFTPD 1.3.5 o ONLYOFFICE viejo?

En este orden, teniendo en cuenta que el plazo ya venció:

1. **Resolvé primero ONLYOFFICE**, si lo tenés: pasá a **5.6.3 o posterior**. Es la más probable de las cinco y termina en ejecución de código.
2. **Decidí qué hacer con el FTP.** Si ese servidor todavía hace falta, actualizá ProFTPD más allá de la 1.3.5 con el repositorio de tu distribución. Si no hace falta —y muchas veces no hace falta—, apagarlo es mejor que actualizarlo. Un servicio que no existe no se explota.
3. **Para Struts, desactivá la invocación dinámica de métodos** si no la usás, que es lo que recomienda el propio aviso de Apache, o pasá a **2.3.20.3, 2.3.24.3 o 2.3.28.1** según la rama. Si Struts vino adentro de una aplicación comprada, esto lo tiene que hacer el fabricante: lo tuyo es abrir el ticket y pedir la versión del componente por escrito.
4. **Para BIND**, actualizá a **9.9.7-P2 o 9.10.2-P3 o posteriores** según la rama. Si el DNS vive adentro de un router o un almacenamiento, de nuevo: ticket al fabricante.
5. **Anotá el resultado del inventario**, aunque sea en una planilla. El trabajo caro de esta tanda no es parchear: es averiguar qué tenés. Esa lista te sirve la próxima vez, y la próxima vez va a llegar con tres días de plazo otra vez, como ya pasó con [las altas de septiembre](/noticia/kev-2-septiembre-sonicwall-switchvox-plazo-tres-dias).

Y conviene hacer el inventario esta semana por un motivo de calendario: **mañana, 13 de octubre, vencen el soporte de Office 2021 y de Windows Server 2012**, que es [la otra fecha que deja software viejo sin parches](/noticia/office-2021-windows-server-2012-fin-soporte-13-octubre). Las dos cosas se responden con la misma lista.

Los términos que aparecen acá están explicados en [el glosario](/guia/glosario-ciberseguridad-pymes), y el encuadre de qué dejar expuesto y qué no, en [la guía de acceso remoto](/guia/acceso-remoto-seguro-sin-vpn).

Una aclaración sobre lo que no pude confirmar. **El sitio de ProFTPD devolvió error 503** desde este entorno, así que no pude leer sus notas de versión; los rangos de ProFTPD salen de la ficha de NVD. **Tampoco pude leer el registro de cambios de ONLYOFFICE**: la versión 5.6.3 sale del análisis de versiones de NVD y de la referencia que la propia ficha enlaza. El aviso de Apache sobre Struts sí lo leí, y confirma las tres versiones corregidas y la condición de la invocación dinámica.

---

## Preguntas frecuentes sobre las cinco fallas del 8 de octubre

### ¿Por qué CISA agrega una falla de 2015 en 2026?

Porque el catálogo no ordena por fecha del CVE sino por explotación observada. Las cinco entradas de esta tanda llevan además una nota del propio catálogo que avisa que la falla puede estar en un componente de código abierto o una biblioteca de terceros usada por distintos productos. Eso es lo que mantiene viva una falla de 2015: el componente sigue corriendo adentro de equipos y aplicaciones que se siguen usando. Esa nota aparece en 20 de las 1739 entradas del catálogo, y 5 de esas 20 entraron el 8 de octubre.

### ¿El plazo del 11 de octubre me obliga a algo?

A una PyME de la región, no: el plazo del catálogo obliga formalmente a organismos federales de Estados Unidos. Lo que sirve es tomarlo como medida de urgencia, y tres días es el plazo más corto que usa el catálogo. Que ya haya vencido no cambia la acción, solo saca la excusa de esperar: el plazo se pasó el 11 de octubre, hace un día, y las entradas llevan cuatro días en el catálogo.

### ¿Cuál de las cinco debería mirar primero una PyME?

ONLYOFFICE Document Server, por probabilidad y no por puntaje. Es la única de las cinco que una PyME instala a propósito y deja corriendo en un servidor propio, y la falla termina en ejecución de código a través de la subida de una imagen. ProFTPD va segundo, porque suele estar en un servidor viejo que nadie mira. BIND y Struts importan si vienen adentro de un equipo de red o de una aplicación comprada.

### ¿Por qué Strapi tiene dos puntajes distintos?

Porque los asignaron dos organizaciones diferentes y pesaron distinto la condición previa. NVD le pone 4,9 (medio) y CISA-ADP 7,2 (alto), sobre la misma falla, que requiere que el atacante ya tenga acceso al panel de administración. Con ese acceso puede filtrar hashes de contraseñas y tokens de recuperación. La ficha además se contradice sobre las versiones afectadas: el texto dice hasta la 4.5.5 y el análisis de versiones marca hasta antes de la 4.8.0. No pude resolver cuál manda, así que lo prudente es apuntar a 4.8.0 o posterior.

### Tengo un router o un almacenamiento viejo. ¿Cómo sé si trae BIND o Struts adentro?

No lo vas a ver desde el equipo, y ese es el punto de esta tanda. Lo que sirve es anotar marca, modelo y versión de firmware, y buscar los avisos de seguridad de ese fabricante para esos CVE, o abrir un ticket preguntando explícitamente si el producto incluye el componente y en qué versión. La ficha de NVD de la falla de BIND lista entre los afectados a Juniper Junos, Oracle Solaris, HP-UX, NetApp Data ONTAP y Apple OS X Server, y la de Struts a los almacenamientos Huawei OceanStor y a varias aplicaciones de Oracle. Si tenés alguno de esos, la pregunta al fabricante es concreta.
