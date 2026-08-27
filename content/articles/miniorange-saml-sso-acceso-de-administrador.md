---
title: "miniOrange SAML SSO: acceso de administrador"
subtitle: Dos fallas en el plugin de inicio de sesión único para WordPress permiten entrar como administrador sin credenciales. Hay escaneo activo contra los sitios que lo usan.
excerpt: Dos fallas en miniOrange SAML SSO dejan entrar como administrador de WordPress sin credenciales. El panel puede decir que estás al día y no estarlo.
type: news
status: published
category: mfa-y-autenticacion
author: ian-poletti-lucero
published: 2026-08-26
updated: 2026-08-26
products:
  - microsoft-entra-id
  - okta-workforce-identity
  - cisco-duo
  - hostinger-business
meta_title: "miniOrange SAML SSO: acceso de administrador"
meta_description: "Dos fallas en miniOrange SAML SSO permiten entrar como administrador de WordPress sin credenciales. Cómo saber qué edición tenés y qué hacer."
---

Dos fallas en el plugin miniOrange SAML SSO para WordPress permiten a quien no tiene cuenta iniciar sesión como administrador del sitio. Las dos están en la verificación de la firma de la respuesta SAML, que es la parte del inicio de sesión único encargada de comprobar que quien dice ser el proveedor de identidad realmente lo es.

El plugin, publicado en el repositorio de WordPress como [SAML Single Sign On – SSO Login](https://wordpress.org/plugins/miniorange-saml-20-single-sign-on/), declara más de 10.000 instalaciones activas en su edición gratuita. Las de pago no publican cifras.

| Dato | CVE-2026-15981 | CVE-2026-61979 |
|---|---|---|
| Puntaje | 9.8, crítico | 8.1, alto |
| Quién lo asignó | Wordfence | Patchstack |
| Tipo | Elusión de autenticación (CWE-287) | Escalada de privilegios sin autenticar (CWE-266) |
| Publicada | 23 de julio | 13 de agosto |
| Estado en NVD | *Awaiting Analysis* | *Deferred* |
| En el catálogo de CISA | No | No |

Ninguno de los dos puntajes lo puso NVD: uno es de Wordfence y el otro de Patchstack, cada uno como autoridad que asignó su CVE. NVD no publicó evaluación propia de ninguno.

---

## Cómo funcionan las dos fallas de miniOrange SAML SSO

**CVE-2026-15981** es la más grave y la más simple de entender. La función que valida la firma llama a `openssl_verify()`, que en PHP devuelve tres valores posibles: 1 si la firma es válida, 0 si no lo es, y -1 si hubo un error al verificarla. El plugin hacía una comprobación booleana laxa sobre ese resultado, y en PHP el -1 es verdadero. Un error de verificación se leía como firma correcta.

```svg
<svg viewBox="0 0 680 190" role="img" aria-label="La función openssl_verify de PHP devuelve tres valores: 1 firma válida, 0 firma inválida y -1 error de verificación. Con una comprobación booleana laxa el 1 se acepta correctamente, el 0 se rechaza correctamente, pero el -1 también se acepta porque en PHP menos uno es verdadero">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Tres respuestas posibles, dos tratadas igual</text>

  <rect x="40" y="46" width="170" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="40" y="46" width="170" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="125" y="76" text-anchor="middle" font-size="20" font-weight="700" fill="currentColor">1</text>
  <text x="125" y="99" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">firma válida</text>
  <text x="125" y="136" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">se acepta</text>

  <rect x="250" y="46" width="170" height="66" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="250" y="46" width="170" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="335" y="76" text-anchor="middle" font-size="20" font-weight="700" fill="currentColor">0</text>
  <text x="335" y="99" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">firma inválida</text>
  <text x="335" y="136" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">se rechaza</text>

  <rect x="460" y="46" width="170" height="66" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="460" y="46" width="170" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="545" y="76" text-anchor="middle" font-size="20" font-weight="700" fill="#e23a3a">-1</text>
  <text x="545" y="99" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">error al verificar</text>
  <text x="545" y="136" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">también se acepta</text>

  <text x="340" y="172" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">En PHP el -1 es verdadero, así que el error entraba por la puerta del acierto</text>
</svg>
```

**CVE-2026-61979** ataca el otro extremo. El plugin tomaba el algoritmo de firma de la propia respuesta que estaba verificando, en vez de exigir el que tiene configurado. Eligiendo HMAC-SHA1, el plugin usa la clave pública RSA del proveedor de identidad como si fuera un secreto compartido. Una clave pública es, por definición, conocida: con ella se puede fabricar una firma que el plugin da por buena.

Las dos terminan en el mismo lugar. Y las dos esquivan el segundo factor, porque el plugin acepta una sesión ya autenticada: el segundo factor se validó, en teoría, del lado del proveedor de identidad. Es la misma familia de problema que se explica en [ya tenés MFA y no alcanza](/guia/ya-tenes-mfa-y-no-alcanza).

---

## Por qué el panel de WordPress dice que miniOrange está al día

Acá está lo que convierte esto en algo más que dos CVE más. El plugin se distribuye bajo un solo identificador en el repositorio de WordPress, pero existe en siete ediciones con numeraciones independientes que no se solapan.

| Edición | Vulnerable hasta | Corregida en |
|---|---|---|
| Gratuita, un sitio | 5.4.4 | 5.4.5 |
| Premium, un sitio | 13.0.3 | 13.0.4 |
| Standard, un sitio | 17.0.5 | 17.0.6 |
| Premium, Enterprise y All-Inclusive, multisitio | 20.2.7 | 20.2.8 |
| Enterprise y All-Inclusive, un sitio | 26.0.2 | 26.0.3 |
| VIP, un sitio | 32.0.7 | 32.0.8 |
| VIP, multisitio | 35.0.6 | 35.0.7 |

WordPress compara la versión instalada contra la que publica el repositorio, que hoy es la 5.4.7 de la edición gratuita. Cualquier edición de pago tiene un número mayor. El panel concluye que no hay nada que actualizar.

```svg
<svg viewBox="0 0 680 210" role="img" aria-label="El panel de WordPress compara la versión instalada contra la 5.4.7 que publica el repositorio para la edición gratuita. Una instalación de la edición Standard en la 17.0.5 tiene un número mayor, así que el panel informa que no hay actualizaciones disponibles aunque el sitio siga siendo vulnerable">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Una comparación de números que miente</text>

  <rect x="20" y="48" width="180" height="80" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="20" y="48" width="180" height="80" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="110" y="72" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">El repositorio publica</text>
  <text x="110" y="98" text-anchor="middle" font-size="17" font-weight="700" fill="currentColor">5.4.7</text>
  <text x="110" y="117" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.8">edición gratuita</text>

  <text x="220" y="93" text-anchor="middle" font-size="13" font-weight="700" fill="currentColor" opacity="0.6">vs</text>

  <rect x="240" y="48" width="180" height="80" rx="6" fill="currentColor" opacity="0.07"/>
  <rect x="240" y="48" width="180" height="80" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="330" y="72" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">Tu sitio tiene</text>
  <text x="330" y="98" text-anchor="middle" font-size="17" font-weight="700" fill="currentColor">17.0.5</text>
  <text x="330" y="117" text-anchor="middle" font-size="10" fill="currentColor" opacity="0.8">edición Standard</text>

  <path d="M424 88 L454 88" stroke="#e23a3a" stroke-width="1.6"/>
  <polygon points="460,88 450,83 450,93" fill="#e23a3a"/>

  <rect x="460" y="48" width="180" height="80" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="460" y="48" width="180" height="80" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="550" y="74" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">Sin actualizaciones</text>
  <text x="550" y="94" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">17 es mayor que 5</text>
  <text x="550" y="114" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">y seguís vulnerable</text>

  <text x="340" y="176" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">Siete ediciones comparten identificador y numeran distinto</text>
  <text x="340" y="196" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.8">De una 16.x vulnerable a una 17.x corregida se pasa subiendo el archivo a mano</text>
</svg>
```

Hay un agravante de transparencia: según el análisis, solo la edición gratuita recibió avisos públicos. Las seis de pago se corrigieron en silencio, sin registro de cambios público ni documentación del CVE.

---

## ¿Están explotando las fallas de miniOrange SAML SSO?

Hay actividad, y conviene decir con precisión de qué tipo. El análisis de Patchstack reporta escaneo contra los puntos de acceso del plugin desde seis direcciones IP repartidas entre Bélgica, Nigeria, Alemania y Estados Unidos, y lo describe como *"opportunistic scanning rather than a targeted campaign"*: barrido oportunista, no campaña dirigida.

El hallazgo salió de un caso real. DigitalOcean detectó intentos de sesión de administrador desde redes no confiables, que sus controles en capas bloquearon antes de que el ataque prosperara.

Ninguna de las dos fallas figura en el catálogo de vulnerabilidades explotadas de CISA, en su versión del 25 de agosto. Que haya escaneo no es lo mismo que explotación confirmada, y tampoco es motivo para esperar: el detalle técnico es público desde julio.

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Cronología: CVE-2026-15981 se publica el 23 de julio, CVE-2026-61979 el 13 de agosto, durante agosto se observa escaneo desde seis direcciones IP en cuatro países, y al 26 de agosto ninguna de las dos figura en el catálogo de vulnerabilidades explotadas de CISA">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Un mes entre la publicación y el barrido</text>

  <rect x="300" y="106" width="310" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M40 120 L650 120" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="90" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">23 de julio</text>
  <text x="90" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CVE-2026-15981</text>
  <path d="M90 84 L90 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="90" cy="120" r="5" fill="currentColor"/>

  <circle cx="300" cy="120" r="5" fill="currentColor"/>
  <path d="M300 128 L300 138" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="300" y="152" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">13 de agosto</text>
  <text x="300" y="168" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CVE-2026-61979</text>

  <text x="450" y="60" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">agosto</text>
  <text x="450" y="76" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">escaneo desde 6 IP</text>
  <path d="M450 84 L450 112" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="450" cy="120" r="5" fill="#e23a3a"/>

  <text x="650" y="60" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">26 de agosto</text>
  <text x="650" y="76" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">no está en el catálogo</text>
  <path d="M610 84 L610 112" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="610" cy="120" r="5" fill="currentColor"/>

  <text x="20" y="204" font-size="11.5" font-weight="600" fill="currentColor">La franja marcada es el período con detalle técnico público y sitios sin actualizar</text>
</svg>
```

---

## ¿A quién afecta la falla de miniOrange SAML SSO?

A cualquier sitio en WordPress que tenga el plugin instalado y activo, en cualquiera de sus siete ediciones, por debajo de la versión corregida de su línea. Es el caso de una PyME que conectó el ingreso a su sitio con Microsoft 365, Google Workspace u otro proveedor de identidad para que el equipo entre con la cuenta de la empresa.

El impacto es total: una sesión de administrador de WordPress permite instalar plugins, y un plugin permite ejecutar código en el servidor. Es el mismo destino al que llegaba, por otro camino, [la falla de Elementor Pro de la semana pasada](/noticia/elementor-pro-formulario-sube-un-php): en WordPress, la ruta al servidor casi siempre pasa por un plugin.

## ¿Quién puede ignorar el aviso de miniOrange SAML SSO?

Quien no use inicio de sesión único en su WordPress, que es la enorme mayoría de los sitios de una PyME: si entrás con usuario y contraseña del propio WordPress, este plugin no está instalado y nada de esto aplica.

Tampoco aplica si usás otro plugin de SSO. La falla es del código de miniOrange, no del protocolo SAML.

## ¿Cómo sé qué edición y versión de miniOrange tengo?

La edición no siempre está a la vista, así que conviene mirar el número de versión, que es el que la identifica. En el panel, *Plugins* lo muestra. Desde el servidor con WP-CLI:

```bash
wp plugin list --name=miniorange-saml-20-single-sign-on --fields=name,status,version
```

Con ese número, buscá tu línea en la tabla de arriba. Si estás por debajo de la versión corregida de tu edición, estás expuesto aunque el panel no ofrezca actualizar.

Para revisar si alguien ya entró, el análisis apunta a lo mismo que destapó el caso: buscar en los registros **sesiones de administrador autenticadas desde direcciones IP fuera del rango esperado**. Un inicio de sesión exitoso de administrador desde un país donde no trabaja nadie es la señal.

## ¿Qué hago si mi WordPress tiene miniOrange SAML SSO?

1. **Identificá tu edición por el número de versión** y comparalo con la tabla. Es el paso que no se puede saltear, porque el panel no lo resuelve.
2. **Si estás en la edición gratuita, actualizá desde el panel.** La versión publicada hoy en el repositorio es la 5.4.7, por encima de la 5.4.5 que corrige.
3. **Si estás en una edición de pago, bajá el paquete desde tu cuenta y subilo a mano.** El salto de una 16.x vulnerable a una 17.x corregida no se ofrece como actualización automática.
4. **Revisá los registros de acceso** por sesiones de administrador desde IP inesperadas, y la lista de usuarios administradores del sitio por cuentas que no reconozcas.
5. **Si encontrás algo, tratalo como compromiso del sitio completo.** Un administrador de WordPress llega al servidor por la vía de instalar un plugin. Restaurar desde una copia anterior y rotar credenciales es el camino; el orden está en [copias de seguridad y recuperación](/productos/backup-y-recuperacion).

El reparto de responsabilidades con el proveedor de hosting en un caso así está en [seguridad de WordPress en una PyME](/guia/seguridad-wordpress-pyme-que-cubre-el-hosting), y si tu plan incluye actualizaciones gestionadas, como los de [Hostinger](/producto/hostinger-business), vale confirmar que los plugins de pago entren en el alcance: son justamente los que no se actualizan solos.

Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

---

## Preguntas frecuentes sobre miniOrange SAML SSO

### Tengo el segundo factor activado en mi proveedor de identidad. ¿Me protege?

No contra esto. Las dos fallas fabrican una respuesta SAML que el plugin acepta como si el proveedor de identidad ya hubiera hecho su trabajo, segundo factor incluido. El atacante no pasa por la pantalla de inicio de sesión: entrega directamente el comprobante de que ya inició sesión. El mecanismo general de por qué una sesión válida vale más que una contraseña está en [cómo funciona el robo de sesión que saltea el MFA](/guia/como-funciona-el-robo-de-sesion-que-saltea-el-mfa).

### El repositorio dice 5.4.7 y yo tengo 26.0.2. ¿Estoy más actualizado?

No. Los números de las siete ediciones no son comparables entre sí: cada línea numera por su cuenta. Una 26.0.2 es la edición Enterprise para un sitio y está por debajo de su versión corregida, la 26.0.3. Que el número sea más alto que 5.4.7 no significa nada más que pertenecer a otra línea.

### ¿Por qué los dos CVE tienen puntajes tan distintos si terminan en lo mismo?

Porque los asignaron organizaciones distintas con criterios distintos: el 9.8 es de Wordfence y el 8.1, de Patchstack. NVD no publicó evaluación propia de ninguno de los dos, así que no hay un tercero que los ponga en la misma escala. Para decidir qué hacer, el dato que manda no es el puntaje sino que las dos permiten entrar sin credenciales y que las dos se corrigen con la misma actualización.

### Si hay escaneo pero no está en el catálogo de CISA, ¿es urgente?

Sí, y no por el catálogo. El catálogo registra explotación confirmada, y llegar ahí lleva tiempo. Acá el detalle técnico es público desde julio, hay barrido automatizado en curso y la actualización ya existe para las siete ediciones. Las condiciones para que un sitio sin actualizar caiga están puestas.

### El sitio lo mantiene una agencia. ¿Qué le pido?

Tres datos por escrito: la edición y versión exacta del plugin instalado hoy, si esa versión está por encima de la corregida de su línea, y el resultado de revisar los registros por sesiones de administrador desde IP no esperadas. Si además sos vos quien tiene que responderle a un cliente, el formato está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
