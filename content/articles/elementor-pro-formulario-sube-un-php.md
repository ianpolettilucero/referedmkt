---
title: "Elementor Pro: el formulario que sube un PHP"
subtitle: Elementor corrigió CVE-2026-32475 en la versión 4.2.2 el 19 de agosto. Alcanza con tener publicada una página con un formulario que acepte archivos.
excerpt: Un visitante sin cuenta puede subir un archivo PHP por un formulario de contacto y ejecutarlo. Afecta a Elementor Pro 4.2.1 y anteriores.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-08-23
updated: 2026-08-23
products:
  - hostinger-business
  - acronis-cyber-protect
meta_title: "Elementor Pro: el formulario que sube un PHP"
meta_description: "CVE-2026-32475 permite subir y ejecutar un PHP sin autenticarse en sitios con Elementor Pro 4.2.1 o anterior. Cómo comprobarlo y qué hacer."
---

El 19 de agosto Elementor publicó la versión 4.2.2 de Elementor Pro y con ella el arreglo de [CVE-2026-32475](https://nvd.nist.gov/vuln/detail/CVE-2026-32475), una falla que permite a un visitante sin cuenta subir un archivo PHP a través de un formulario de contacto y ejecutarlo en el servidor. Afecta a la 4.2.1 y todas las anteriores.

El requisito para que un sitio sea alcanzable es más bajo que en la mayoría de las fallas de plugins. Según [el análisis de Patchstack](https://patchstack.com/articles/critical-unauthenticated-file-upload-to-rce-in-elementor-pro-plugin/), que coordinó la divulgación: *"The only prerequisite is that the target site has at least one published Elementor page containing a Form widget with a File Upload field."* Una página publicada, un formulario, un campo de carga de archivos. Nada más.

| Dato | Valor | Fuente |
|---|---|---|
| Identificador | CVE-2026-32475 | Patchstack, como CNA |
| Producto | Elementor Pro | NVD |
| Versiones afectadas | Hasta 4.2.1 inclusive | NVD |
| Versión corregida | 4.2.2, del 19 de agosto | Changelog de Elementor |
| Tipo | Subida de archivo peligroso sin restricción ([CWE-434](https://cwe.mitre.org/data/definitions/434.html)) | Patchstack |
| Puntaje | 9.0, crítico | Patchstack |
| Autenticación necesaria | Ninguna | NVD |
| Explotación activa | Sin confirmar | Catálogo KEV, versión 2026.08.21 |

El 9.0 lo asignó Patchstack, no NVD: la entrada en NVD figura como *Deferred*, o sea que NVD no va a publicar su propia evaluación. Es el mismo detalle que [en el caso de Entra ID](/noticia/entra-id-cvss-10-no-fue-explotado): el número tiene autor, y conviene saber cuál.

---

## Cómo funciona CVE-2026-32475: dos reglas para el mismo envío

La falla no está en el filtro de extensiones. Está en que el código que valida y el código que guarda recorren la misma lista de archivos con reglas distintas.

Cuando llega un envío con dos entradas para el mismo campo y la primera viene vacía, la rutina de validación se detiene ahí y da por terminada la revisión: nunca llega a mirar la segunda. La rutina que guarda, en cambio, saltea la vacía y sigue con la siguiente. El resultado es que la segunda entrada se escribe en disco sin haber pasado por la lista de extensiones prohibidas, dentro de `wp-content/uploads/elementor/forms/`, que es un directorio servido por el servidor web.

```svg
<svg viewBox="0 0 660 290" role="img" aria-label="Diagrama del desfase: la rutina de validación se detiene en la primera entrada vacía y nunca revisa la segunda, mientras que la rutina de guardado saltea la vacía y escribe la segunda en el directorio público">
  <text x="330" y="26" text-anchor="middle" font-size="13" font-weight="700" fill="currentColor">El mismo envío, recorrido por dos reglas distintas</text>

  <text x="24" y="76" font-size="12" font-weight="700" fill="currentColor">Validación</text>
  <rect x="150" y="56" width="150" height="40" rx="5" fill="currentColor" opacity="0.08"/>
  <rect x="150" y="56" width="150" height="40" rx="5" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="225" y="81" text-anchor="middle" font-size="11.5" fill="currentColor">entrada 1: vacía</text>
  <rect x="360" y="56" width="150" height="40" rx="5" fill="none" stroke="currentColor" stroke-width="1.3" stroke-dasharray="4 3" opacity="0.4"/>
  <text x="435" y="81" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.5">entrada 2: sin revisar</text>
  <text x="530" y="81" font-size="11.5" font-weight="700" fill="currentColor">corta acá</text>

  <text x="24" y="176" font-size="12" font-weight="700" fill="currentColor">Guardado</text>
  <rect x="150" y="156" width="150" height="40" rx="5" fill="none" stroke="currentColor" stroke-width="1.3" stroke-dasharray="4 3" opacity="0.4"/>
  <text x="225" y="181" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.5">entrada 1: se saltea</text>
  <rect x="360" y="156" width="150" height="40" rx="5" fill="#e23a3a" opacity="0.14"/>
  <rect x="360" y="156" width="150" height="40" rx="5" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="435" y="181" text-anchor="middle" font-size="11.5" font-weight="700" fill="#e23a3a">entrada 2: se escribe</text>
  <text x="530" y="181" font-size="11.5" font-weight="700" fill="currentColor">sigue</text>

  <line x1="435" y1="200" x2="435" y2="234" stroke="#e23a3a" stroke-width="1.8"/>
  <polygon points="435,244 430,232 440,232" fill="#e23a3a"/>
  <text x="330" y="272" text-anchor="middle" font-size="11.5" fill="currentColor" opacity="0.85">El archivo queda en un directorio que el servidor web publica</text>
</svg>
```

Patchstack lo describe como un desfase entre las dos rutinas. La corrección de la 4.2.2 alinea los dos recorridos y agrega una segunda comprobación de extensión en el momento de guardar, para que la lista de prohibidas cubra también ese paso.

---

## ¿A quién afecta la falla de Elementor Pro?

Comprobalo hoy si tenés un sitio en WordPress con **Elementor Pro en 4.2.1 o anterior** y al menos una página publicada con un formulario que acepte archivos adjuntos. El caso típico es el formulario de "trabajá con nosotros" que recibe currículums, o el de soporte que pide adjuntar una captura.

No hace falta ninguna configuración rara. El campo de carga con sus opciones por defecto ya alcanza: Patchstack anota que el interruptor de campo obligatorio viene desactivado de fábrica y que ese es justamente el estado que sirve para el ataque.

Las tres condiciones tienen que darse a la vez, y por eso vale mirarlas antes de dar por hecho que te toca:

```svg
<svg viewBox="0 0 660 205" role="img" aria-label="Las tres condiciones que tienen que darse juntas para que el sitio sea explotable: Elementor Pro en versión 4.2.1 o anterior, una página publicada con formulario, y que ese formulario acepte carga de archivos. Si falta una sola, la falla no aplica">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Las tres condiciones tienen que darse juntas</text>

  <rect x="20" y="44" width="180" height="62" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="44" width="180" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="110" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Elementor Pro</text>
  <text x="110" y="88" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">4.2.1 o anterior</text>

  <text x="220" y="80" text-anchor="middle" font-size="12" font-weight="700" fill="currentColor" opacity="0.6">y</text>

  <rect x="240" y="44" width="180" height="62" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="240" y="44" width="180" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="330" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Una página publicada</text>
  <text x="330" y="88" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">con formulario</text>

  <text x="440" y="80" text-anchor="middle" font-size="12" font-weight="700" fill="currentColor" opacity="0.6">y</text>

  <rect x="460" y="44" width="180" height="62" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="460" y="44" width="180" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="550" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Ese formulario acepta</text>
  <text x="550" y="88" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">carga de archivos</text>

  <path d="M330 108 L330 128" stroke="#e23a3a" stroke-width="1.4" opacity="0.7"/>
  <rect x="180" y="130" width="300" height="34" rx="6" fill="#e23a3a" opacity="0.16"/>
  <rect x="180" y="130" width="300" height="34" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="330" y="152" text-anchor="middle" font-size="11.5" font-weight="700" fill="#e23a3a">Explotable sin autenticarse</text>

  <text x="20" y="194" font-size="11.5" font-weight="600" fill="currentColor">Si falta una sola de las tres, la falla no aplica a tu sitio</text>
</svg>
```

## ¿Quién puede ignorar el aviso de Elementor Pro?

Si usás **Elementor gratuito** y no la versión Pro, esto no aplica: el módulo de formularios es una función de pago y la falla está ahí. Tampoco te toca si tenés Elementor Pro pero ningún formulario publicado con campo de carga de archivos, que es el caso de la mayoría de los sitios institucionales de una PyME, donde el formulario pide nombre, correo y mensaje.

Y no te toca si ya estás en 4.2.2 o posterior.

---

## ¿Cómo sé si mi sitio WordPress está afectado?

**La versión.** En el panel, *Plugins* muestra el número. Con WP-CLI, desde el servidor:

```bash
wp plugin list --name=elementor-pro --fields=name,status,version
```

**Los formularios publicados.** La pregunta concreta es si alguna página publicada tiene un widget de formulario con campo de carga. Si el sitio es chico, se revisa a mano; si son decenas de páginas, conviene mirar quién recibe adjuntos por correo desde el formulario.

**El directorio de subidas.** Patchstack indica revisar `wp-content/uploads/elementor/forms/` y buscar cualquier cosa que no sea uno de los tipos de documento o imagen que tus formularios aceptan, con atención a los `.php`:

```bash
find wp-content/uploads/elementor/forms/ -type f -name "*.php"
```

Los archivos subidos por esta vía reciben un nombre generado, así que no esperes un nombre reconocible. Y un resultado vacío no es certificado de nada: si alguien ejecutó código, pudo haber dejado el rastro en otro lado y borrado el archivo original. Es una señal, no una absolución.

---

## ¿Qué hago si tengo Elementor Pro 4.2.1 o anterior?

1. **Actualizá a 4.2.2 o posterior.** Es el paso que cierra la puerta y no admite postergación, porque el detalle técnico ya es público desde el 19 de agosto.
2. **Si no podés actualizar hoy, despublicá el formulario con carga de archivos.** Sin página publicada con ese campo, el requisito del ataque no se cumple. Es una medida temporal, no un reemplazo del parche.
3. **Revisá el directorio de subidas** con el comando de arriba, y los registros de acceso del servidor por peticiones POST al formulario seguidas de peticiones a archivos dentro de `uploads`.
4. **Si encontrás algo, tratalo como compromiso del sitio entero.** Ejecución de código en el servidor web significa que las credenciales de la base de datos estaban al alcance. Restaurar desde una copia anterior al incidente y rotar contraseñas es el camino; el orden para armar eso está en [copias de seguridad y recuperación](/productos/backup-y-recuperacion).
5. **Revisá qué otros plugins de pago tenés sin actualizar.** Los comerciales no se actualizan solos desde el repositorio oficial: dependen de que la licencia esté activa. Una licencia vencida es un sitio que dejó de recibir parches sin avisar.

Sobre lo que el proveedor de hosting cubre y lo que no en un caso así, la guía de [seguridad de WordPress en una PyME](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting) tiene el reparto de responsabilidades; si tu plan incluye actualizaciones gestionadas, como los de [Hostinger](/producto/hostinger-business), verificá que el plugin de pago esté dentro del alcance y no solo el núcleo de WordPress.

Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre CVE-2026-32475 en Elementor Pro

### Leí que solo aplica si tenés activada la carga de varios archivos. ¿Es así?

No. Esa condición circuló en parte de la cobertura y no coincide con la fuente. Patchstack, que coordinó la divulgación y asignó el CVE, pone un único requisito: una página publicada con un formulario que tenga campo de carga. El envío con dos entradas para el mismo campo se arma del lado de quien ataca, no depende de lo que permita el formulario en pantalla. Dar por buena la versión equivocada lleva a concluir que no te toca cuando sí.

### El changelog de Elementor solo dice "mejoras de seguridad en el widget de formulario". ¿Es esto?

Sí. La entrada de la 4.2.2 dice *"Fix: Improved code security enforcement in Form widget"*. Un fabricante rara vez describe la gravedad en su registro de cambios, y eso es habitual: se corrige primero y se detalla después, para no publicar el mapa antes de que la gente actualice. La consecuencia práctica es que la urgencia de una actualización no se puede deducir del changelog.

### ¿Está siendo explotada?

No hay confirmación. El análisis de Patchstack no menciona explotación en la vida real y la falla no figura en el catálogo de vulnerabilidades explotadas de CISA al 21 de agosto. Eso no es tranquilizador por sí solo: el análisis técnico es público desde el 19 de agosto, y las fallas de subida de archivos en plugins de WordPress con muchos sitios instalados suelen ser adoptadas rápido por escaneo automatizado.

### ¿Cuántos sitios están afectados?

No hay un número confiable. Elementor Pro es un producto comercial, así que no publica cantidad de instalaciones activas como sí lo hacen los plugins gratuitos del repositorio de WordPress. Las cifras que circularon en la cobertura salen de las instalaciones totales de Elementor, que incluyen la versión gratuita, donde el módulo de formularios no existe.

### Tengo el sitio hecho por un tercero. ¿Qué le pido?

Que confirme por escrito tres cosas: la versión de Elementor Pro instalada hoy, si hay formularios publicados con campo de carga y el resultado de revisar el directorio de subidas. Si el sitio lo mantiene un proveedor y alguien te pide a vos garantías de seguridad, el criterio para responder está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
