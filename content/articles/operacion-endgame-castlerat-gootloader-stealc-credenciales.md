---
title: "Operación Endgame: cómo saber si tu correo está en los datos de CastleRAT"
subtitle: Europol y nueve países desarmaron la infraestructura de Gootloader, CastleRAT y StealC, y encontraron más de 6 millones de credenciales robadas. Have I Been Pwned ya cargó 179.745 correos de víctimas. Si el tuyo está, el problema no es una contraseña: es una computadora infectada.
excerpt: La Operación Endgame encontró más de 6 millones de credenciales robadas por CastleRAT y StealC. Cómo buscar tu correo en Have I Been Pwned y qué hacer.
type: news
section: mundo
status: published
category: gestion-contrasenas
author: ian-poletti-lucero
published: 2026-11-14
updated: 2026-11-14
products: []
meta_title: "Operación Endgame y CastleRAT: revisá tu correo"
meta_description: "La Operación Endgame encontró más de 6 millones de credenciales robadas por CastleRAT y StealC. Cómo buscar tu correo en Have I Been Pwned y qué hacer."
---

Europol anunció el 12 de noviembre [una nueva fase de la Operación Endgame](https://www.europol.europa.eu/media-press/newsroom/news/64-million-victim-datasets-secured-in-international-operation-against-malware-networks), la ofensiva internacional contra las herramientas que preparan el terreno para el ransomware. Esta vez apuntó a tres: **Gootloader**, **CastleRAT** y **StealC**. Policías de nueve países, con empresas privadas, dieron de baja 235 servidores y 257 dominios, y en esos servidores encontraron, según Europol, *"over 6 million victim credentials"*: contraseñas robadas de equipos infectados, que los delincuentes vendían para otros ataques.

Una parte ya se puede consultar. El 13 de noviembre, [Have I Been Pwned](https://haveibeenpwned.com/) cargó los datos de CastleRAT que le entregó Europol: **179.745 direcciones de correo** asociadas a infecciones y 893.000 contraseñas distintas, que aparecieron más de 3 millones de veces en cuentas usadas desde equipos infectados.

| Herramienta | Qué hacía | Qué dio de baja la operación |
|---|---|---|
| **Gootloader** | Infectaba equipos con documentos legales falsos, muchas veces desde sitios de WordPress hackeados | 14 servidores y 218 dominios; 681 sitios infectados limpiados |
| **CastleRAT** | Daba control remoto del equipo infectado; según Europol, terminó en datos cifrados y rescates pagados | 120 servidores y 39 dominios; datos de víctimas compartidos para avisarles |
| **StealC** | Robaba contraseñas, accesos guardados e identidades digitales, y podía instalar otros programas | 102 servidores, en una acción que sigue a la de junio |

Las cifras son del comunicado de Europol. Las tres filas suman 236 servidores, uno más que el total que da el mismo comunicado.

```svg
<svg viewBox="0 0 680 292" role="img" aria-label="Cómo terminan en manos de terceros las contraseñas de una empresa, según el comunicado de Europol sobre la Operación Endgame del 12 de noviembre de 2026. Primero, un sitio de WordPress hackeado ofrece un documento legal falso; ese es el método de Gootloader. Segundo, alguien de la empresa lo descarga y su equipo queda infectado. Tercero, CastleRAT da control remoto del equipo y StealC roba lo que tiene guardado. Cuarto, se llevan contraseñas, datos de acceso e identidades digitales; esos son los datos que hoy se pueden buscar en Have I Been Pwned. Quinto, los delincuentes venden esas credenciales. Sexto, según Europol, se usan después en otros ataques, como el ransomware.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Operación Endgame: de un documento falso al ransomware</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según Europol. El rojo marca los datos que ya se pueden buscar en Have I Been Pwned.</text>
  <rect x="16" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>WordPress hackeado: ofrece un documento legal falso</title></rect>
  <text x="118" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">WordPress hackeado</text>
  <text x="118" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">ofrece un documento legal falso</text>
  <rect x="238" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Alguien lo descarga: y el equipo queda infectado</title></rect>
  <text x="340" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Alguien lo descarga</text>
  <text x="340" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y el equipo queda infectado</text>
  <rect x="460" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>CastleRAT y StealC: control remoto y robo de datos</title></rect>
  <text x="562" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">CastleRAT y StealC</text>
  <text x="562" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">control remoto y robo de datos</text>
  <path d="M222 91 H234 M229 86 L234 91 L229 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 91 H456 M451 86 L456 91 L451 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 120 V138 H118 V154 M113 149 L118 154 L123 149" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="16" y="156" width="204" height="58" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>Contraseñas y accesos: guardados en ese equipo</title></rect>
  <text x="118" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Contraseñas y accesos</text>
  <text x="118" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">guardados en ese equipo</text>
  <rect x="238" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Se venden: a otros delincuentes</title></rect>
  <text x="340" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Se venden</text>
  <text x="340" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">a otros delincuentes</text>
  <rect x="460" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Otros ataques: como el ransomware, dice Europol</title></rect>
  <text x="562" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Otros ataques</text>
  <text x="562" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">como el ransomware, dice Europol</text>
  <path d="M222 185 H234 M229 180 L234 185 L229 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 185 H456 M451 180 L456 185 L451 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="20" y="246" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Europol encontró más de 6 millones de credenciales en los servidores incautados.</text>
  <text x="20" y="276" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: comunicado de Europol sobre la Operación Endgame, 12 de noviembre de 2026.</text>
</svg>
```

## ¿Qué tienen que ver Gootloader, CastleRAT y StealC con una empresa chica?

Son las herramientas del primer paso. Europol describe a Gootloader como un programa que *"enabled unauthorised access to computer systems by distributing fake legal documents through compromised websites"*. Alguien busca un modelo de contrato o un acuerdo legal, llega a un sitio que parece confiable, descarga el documento y su computadora queda infectada. Europol agrega que eso pasaba *"often via hacked WordPress sites"*.

Una vez adentro, CastleRAT le da al atacante el control remoto del equipo, y StealC se lleva lo que el equipo tiene guardado: las contraseñas del navegador, los accesos y los datos de identidad. Según Europol, esas credenciales se vendían *"to be used for other cyber-attacks such as ransomware"*. El que compra no necesita forzar nada: entra con la contraseña de un empleado.

Ningún país de América Latina participó de esta fase. Los nueve fueron Alemania, Bulgaria, Canadá, Dinamarca, Estados Unidos, Moldavia, Países Bajos, Polonia y el Reino Unido. Pero estas herramientas no eligen país, y Europol no dio un desglose de las víctimas por región.

## ¿Cómo sé si mi correo está en los datos de la Operación Endgame?

1. **Buscá tu dirección en Have I Been Pwned.** Es gratis y alcanza con escribir el correo en la página principal. Si aparece en la filtración llamada **CastleRAT**, cargada el 13 de noviembre, tu dirección se usó en un equipo infectado. Las fases anteriores figuran como *Operation Endgame*, *Operation Endgame 2.0*, *3.0* y *4.0*.
2. **Revisá las contraseñas, no solo el correo.** El servicio [Pwned Passwords](https://haveibeenpwned.com/Passwords) dice si una contraseña aparece en filtraciones, sin que la contraseña salga de tu computadora: según el servicio, solo se mandan *"the first 5 characters of the SHA-1 hash"*. Algunos gestores de contraseñas hacen esa consulta solos.
3. **Si administrás el correo de la empresa, buscá el dominio entero.** Have I Been Pwned permite revisar todas las direcciones de un dominio después de verificar que es tuyo, desde la sección *Domain Search*.
4. **Suscribite a los avisos.** Have I Been Pwned avisa por correo si tu dirección aparece en una filtración nueva. Conviene, porque esta tanda puede no ser la última.

Un resultado negativo no es una garantía. Europol habla de más de 6 millones de credenciales, y la carga del 13 de noviembre trae 179.745 correos. En junio, con la fase anterior, Have I Been Pwned recibió una segunda tanda una semana después de la primera.

## ¿Qué hago si mi correo aparece en la filtración de CastleRAT?

Have I Been Pwned marca estos casos como filtración de *malware*, y su página de preguntas frecuentes explica por qué es distinto: el riesgo es que *"their personal device may be compromised"*. No te robaron la contraseña de un sitio: infectaron una computadora que usaste.

1. **Encontrá el equipo.** Pensá desde qué computadoras usaste ese correo en los últimos meses, incluidas las de tu casa y las compartidas.
2. **Limpialo antes de cambiar nada.** Pasale un antivirus completo o, mejor, reinstalalo. Si cambiás las contraseñas desde un equipo todavía infectado, el atacante se lleva también las nuevas.
3. **Cambiá las contraseñas desde otro equipo, empezando por el correo.** Después, las del banco, los sistemas de la empresa y todo lo que tenías guardado en el navegador. Para no repetir contraseñas, un gestor ayuda; las opciones están en [la comparativa de gestores de contraseñas](/comparativa/comparativa-1password-vs-bitwarden-vs-dashlane).
4. **Cerrá las sesiones abiertas.** Un programa que roba datos del navegador puede llevarse también las sesiones, y con eso se entra sin contraseña ni segundo factor, como explica [la guía sobre el robo de sesión](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa). En Microsoft 365 y Google Workspace, el administrador puede cerrar todas las sesiones de un usuario.
5. **Activá el segundo factor donde no lo tengas.** Europol lo pide para WordPress, y vale para cualquier cuenta. Si nunca lo configuraste para la empresa, [la guía para hacerlo en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana) va paso a paso.

## ¿Qué hago si mi sitio de WordPress pudo alojar Gootloader?

Gootloader necesita sitios hackeados para publicar sus documentos falsos, y Europol cuenta 681 sitios infectados que se limpiaron en esta fase. El comunicado no dice cómo se avisó a sus dueños. Su recomendación es breve: *"WordPress users are urged to update credentials, enable multi-factor authentication, and patch vulnerabilities."*

En la práctica: cambiá las contraseñas de todos los administradores del sitio, activá el segundo factor para entrar al panel y mantené al día WordPress, el tema y los plugins. Revisá también si hay páginas o entradas que no publicaste, sobre todo con modelos de contratos o documentos para descargar. Qué parte de eso cubre tu hosting y qué parte te toca a vos está en [la guía de seguridad de WordPress para PyMEs](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting). La última actualización urgente de WordPress fue la de [CVE-2026-119977](/noticia/wordpress-cve-2026-119977-ventana-de-actualizacion).

## ¿Por qué Have I Been Pwned tiene pocos correos de CastleRAT?

```svg
<svg viewBox="0 0 680 262" role="img" aria-label="Correos electrónicos de víctimas que cada fase de la Operación Endgame entregó a Have I Been Pwned, según los registros del servicio al 14 de noviembre de 2026, de mayor a menor. Mayo de 2024, primera fase: 16.466.858 correos. Mayo de 2025: 15.436.844. Junio de 2026, contra SocGholish y StealC: 4.348.526. Noviembre de 2025: 2.046.030. Noviembre de 2026, la fase de CastleRAT: 179.745 correos por ahora, cargados el 13 de noviembre. Europol habla de más de 6 millones de credenciales en esta fase, así que pueden llegar más datos, como pasó en junio, cuando una segunda tanda llegó una semana después.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Operación Endgame: correos de víctimas cargados en Have I Been Pwned</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Por fase, de mayor a menor. El rojo marca la de esta semana, que todavía puede crecer.</text>
  <path d="M160.0 58 V214" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="160" y="229" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">0</text>
  <path d="M271.1 58 V214" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="271.1" y="229" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">5 M</text>
  <path d="M382.2 58 V214" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="382.2" y="229" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">10 M</text>
  <path d="M493.3 58 V214" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="493.3" y="229" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">15 M</text>
  <text x="150" y="77" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Mayo de 2024</text>
  <path d="M160,64 H521.9 A4,4 0 0 1 525.9,68 V78 A4,4 0 0 1 521.9,82 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Mayo de 2024, primera fase: 16.466.858 correos</title></path>
  <text x="531.9" y="77" class="ch-value" font-size="11" font-weight="600" fill="currentColor">16.466.858</text>
  <text x="150" y="107" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Mayo de 2025</text>
  <path d="M160,94 H499.0 A4,4 0 0 1 503.0,98 V108 A4,4 0 0 1 499.0,112 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Mayo de 2025: 15.436.844 correos</title></path>
  <text x="509" y="107" class="ch-value" font-size="11" font-weight="600" fill="currentColor">15.436.844</text>
  <text x="150" y="137" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Junio de 2026</text>
  <path d="M160,124 H252.6 A4,4 0 0 1 256.6,128 V138 A4,4 0 0 1 252.6,142 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Junio de 2026, SocGholish y StealC: 4.348.526 correos</title></path>
  <text x="262.6" y="137" class="ch-value" font-size="11" font-weight="600" fill="currentColor">4.348.526</text>
  <text x="150" y="167" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Noviembre de 2025</text>
  <path d="M160,154 H201.5 A4,4 0 0 1 205.5,158 V168 A4,4 0 0 1 201.5,172 H160 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Noviembre de 2025: 2.046.030 correos</title></path>
  <text x="211.5" y="167" class="ch-value" font-size="11" font-weight="600" fill="currentColor">2.046.030</text>
  <text x="150" y="197" class="ch-strong" font-size="11" text-anchor="end" fill="currentColor">Noviembre de 2026</text>
  <path d="M160,184 H164.0 V202 H160 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>Noviembre de 2026, CastleRAT: 179.745 correos, por ahora</title></path>
  <text x="170" y="197" class="ch-value" font-size="11" font-weight="600" fill="currentColor">179.745</text>
  <text x="20" y="252" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: registros de Have I Been Pwned, consultados el 14 de noviembre de 2026.</text>
</svg>
```

Desde mayo de 2024, Have I Been Pwned registra cinco cargas de datos de víctimas de la Operación Endgame. Las dos primeras pasaron los 15 millones de correos cada una; la de esta semana, por ahora, no llega a 200.000. Esa diferencia dice más de cuánto se cargó hasta hoy que del tamaño del robo: Europol habla de más de 6 millones de credenciales y aclara que los datos de víctimas se van a compartir también con otras plataformas, como Shadowserver y el centro nacional de ciberseguridad de los Países Bajos.

## ¿Quién puede ignorar la Operación Endgame?

Nadie del todo, porque la consulta toma un minuto. Quien no aparece en Have I Been Pwned puede seguir con lo suyo, sabiendo que la carga puede crecer. Quien aparece tiene que tratarlo como una computadora infectada, no como una contraseña filtrada. El resto de las prioridades está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** el comunicado de Europol del 12 de noviembre; los registros de Have I Been Pwned de la filtración CastleRAT y de las fases anteriores de la Operación Endgame, consultados el 14 de noviembre a las 11 UTC; y las páginas de preguntas frecuentes y de Pwned Passwords del servicio. **Sin confirmar:** cuántas de las más de 6 millones de credenciales corresponden a víctimas de América Latina, porque Europol no da un desglose por país; si Have I Been Pwned va a recibir más datos de esta fase; y si hubo detenidos, porque el comunicado no menciona arrestos.

## Preguntas frecuentes sobre la Operación Endgame y CastleRAT

### ¿Qué es la Operación Endgame?

Es una ofensiva internacional coordinada por Europol contra las herramientas que preparan el terreno para el ransomware. Empezó en mayo de 2024 y la fase del 12 de noviembre de 2026 apuntó a Gootloader, CastleRAT y StealC.

### ¿Cómo sé si mi correo está en los datos de CastleRAT?

Buscalo en Have I Been Pwned. Si aparece en la filtración llamada CastleRAT, cargada el 13 de noviembre de 2026, tu dirección se usó en un equipo infectado. No aparecer no garantiza nada: puede haber más cargas.

### ¿Alcanza con cambiar la contraseña si mi correo aparece?

No. El problema es una computadora infectada. Primero hay que limpiar o reinstalar ese equipo; después, cambiar las contraseñas desde otro equipo y cerrar las sesiones abiertas.

### ¿Qué es Gootloader y por qué importa si tengo WordPress?

Es un programa que infecta equipos con documentos legales falsos publicados en sitios hackeados, muchas veces de WordPress. Europol pide a quienes usan WordPress cambiar las credenciales, activar el segundo factor y actualizar.

### ¿Participó algún país de América Latina en la Operación Endgame?

No en esta fase. Participaron Alemania, Bulgaria, Canadá, Dinamarca, Estados Unidos, Moldavia, Países Bajos, Polonia y el Reino Unido, con Europol y Eurojust.
