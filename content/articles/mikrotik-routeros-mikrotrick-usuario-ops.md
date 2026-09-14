---
title: "MikroTik RouterOS explotado: MikroTrick y el usuario ops"
subtitle: CERT Polska confirma que dos fallas encadenadas toman el control total de routers con SSH publicado. Los ataques vienen de al menos el 2 de septiembre y dejan un usuario llamado "ops".
excerpt: Seis fallas en RouterOS, dos ya explotadas. Qué versión corrige, cómo saber si tu router fue tomado y por qué la falla que abre la puerta no está en el catálogo de CISA.
type: news
status: published
category: vpn-y-acceso-remoto
author: ian-poletti-lucero
published: 2026-09-14
updated: 2026-09-14
products:
  - tailscale-business
  - twingate
  - cloudflare-access
meta_title: "MikroTik RouterOS: MikroTrick ya se explota"
meta_description: "CERT Polska confirma ataques a RouterOS con SSH publicado. Qué versión corrige, cómo detectar el usuario ops y por qué CISA catalogó solo dos de seis fallas."
---

CERT Polska publicó el 5 de septiembre la coordinación de **seis vulnerabilidades en MikroTik RouterOS** y confirmó algo que el aviso del fabricante no dice: dos de ellas se están encadenando en ataques reales para **tomar el control completo del router sin autenticarse**, siempre que el servicio SSH esté accesible desde internet. Al encadenado le pusieron nombre —**MikroTrick**— justamente para que se pueda identificar.

Los ataques no empezaron cuando salió el aviso. Según el análisis de CERT Polska, vienen ocurriendo **desde al menos el 2 de septiembre**, un día antes de que MikroTik publicara nada.

Las versiones que corrigen son **6.49.21 (Long-term), 7.23.4 (Long-term) y 7.24.2 (Stable)**, más la 7.25beta3.

| Dato | CVE-2026-67276 | CVE-2026-86060 | CVE-2026-67277 |
|---|---|---|---|
| Qué hace | Entra por SSH sin la clave privada | Convierte esa sesión en administrador | Filtra memoria del kernel o reinicia el equipo |
| Puntaje de CERT Polska | 9.2, crítico, en CVSS 4.0 | 9.2, crítico, en CVSS 4.0 | 8.8, alto, en CVSS 4.0 |
| Puntaje de NVD | Sin analizar todavía | 9.8, crítico, en CVSS 3.1 | 8.2, alto, en CVSS 3.1 |
| Tipo | CWE-347, verificación de firma | CWE-88, inyección de argumentos | CWE-306, falta de autenticación |
| Ramas afectadas | Solo 7.x | 6.x y 7.x | 6.x y 7.x |
| En el catálogo de CISA | **No** | Sí, desde el 10 de septiembre | Sí, desde el 10 de septiembre |

Los puntajes de la columna de CERT Polska los asignó el equipo polaco, que actuó como autoridad de numeración de estas fallas: **el CNA acá no es MikroTik**. Los de NVD son posteriores y usan otra escala, por eso no coinciden.

## ¿Qué es MikroTrick y cómo toma el control de un RouterOS?

Son dos fallas que por separado no alcanzan y juntas entregan el equipo. La primera consigue una sesión SSH como un usuario existente. La segunda convierte esa sesión en administrador total.

```svg
<svg viewBox="0 0 680 195" role="img" aria-label="Cadena MikroTrick en MikroTik RouterOS: CVE-2026-67276 permite abrir una sesión SSH como un usuario autorizado usando una clave pública con exponente uno, sin poseer la clave privada; sobre esa sesión, CVE-2026-86060 usa un nombre de usuario que empieza con guion para que el ayudante de login lo interprete como un argumento y cambie la máscara de permisos, obteniendo privilegios administrativos completos; el resultado observado en los ataques es la creación de un usuario privilegiado llamado ops">
  <text x="340" y="24" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Una entra, la otra manda</text>

  <rect x="25" y="46" width="170" height="94" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="25" y="46" width="170" height="94" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="110" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-67276</text>
  <text x="110" y="92" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">clave con exponente 1</text>
  <text x="110" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">entra sin la privada</text>
  <text x="110" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.6">solo rama 7.x</text>
  <path d="M200 93 L243 93" stroke="#e23a3a" stroke-width="1.5"/>

  <rect x="250" y="46" width="170" height="94" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="250" y="46" width="170" height="94" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="335" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">CVE-2026-86060</text>
  <text x="335" y="92" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">usuario que empieza</text>
  <text x="335" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">con guion: se lee flag</text>
  <text x="335" y="130" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.6">6.x y 7.x</text>
  <path d="M425 93 L468 93" stroke="#e23a3a" stroke-width="1.5"/>

  <rect x="475" y="46" width="170" height="94" rx="6" fill="currentColor" opacity="0.09"/>
  <rect x="475" y="46" width="170" height="94" rx="6" fill="none" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>
  <text x="560" y="78" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Administrador</text>
  <text x="560" y="100" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">del router, completo</text>
  <text x="560" y="122" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">aparece el usuario ops</text>

  <text x="20" y="176" font-size="11.5" font-weight="600" fill="currentColor">Ninguna de las dos sirve sola: una consigue la sesión, la otra le da los permisos</text>
</svg>
```

El segundo paso es más simple de lo que parece, y el registro del router lo delata. RouterOS pasa el nombre de usuario al programa que atiende el login **como un argumento más**, sin marcar dónde terminan las opciones. Un nombre que empieza con guion deja de leerse como nombre y pasa a leerse como una opción de línea de comandos, que termina cambiando la máscara de permisos de la sesión. Por eso la clasificación es CWE-88, inyección de argumentos, y por eso en los registros de los equipos atacados aparece literalmente un usuario llamado `-2`.

## Por qué una clave con exponente 1 alcanza para entrar sin la clave privada

La primera falla es la más elegante de las seis y merece entenderse, porque explica por qué tener claves SSH configuradas no salvó a nadie.

Una clave pública RSA son dos números: el **módulo** y el **exponente**. Cuando alguien intenta entrar con clave, el servidor tiene que hacer dos cosas distintas: comprobar que la clave presentada es una de las autorizadas, y después verificar la firma. RouterOS comparaba el tipo de clave y el módulo, **y no comparaba el exponente**.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Mecanismo de CVE-2026-67276 en MikroTik RouterOS: al comparar la clave pública SSH presentada contra la autorizada, RouterOS comprobaba el tipo de clave y el módulo pero omitía el exponente; como la verificación de la firma usa la clave que envía el cliente, un atacante que conoce el módulo de una clave autorizada puede presentar esa misma clave con exponente uno y falsificar una firma válida, porque elevar a la potencia uno devuelve el mismo número">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Qué comparaba RouterOS al validar una clave SSH</text>

  <rect x="25" y="44" width="190" height="66" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="25" y="44" width="190" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="120" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Tipo de clave</text>
  <text x="120" y="92" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">comparado</text>

  <rect x="240" y="44" width="190" height="66" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="240" y="44" width="190" height="66" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="335" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="currentColor">Módulo</text>
  <text x="335" y="92" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">comparado</text>

  <rect x="450" y="44" width="190" height="66" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="450" y="44" width="190" height="66" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="545" y="70" text-anchor="middle" font-size="11" font-weight="700" fill="#e23a3a">Exponente</text>
  <text x="545" y="92" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">no comparado</text>

  <path d="M545 116 L545 134" stroke="#e23a3a" stroke-width="1.4"/>
  <path d="M539 128 L545 136 L551 128" fill="none" stroke="#e23a3a" stroke-width="1.4"/>

  <rect x="25" y="140" width="615" height="42" rx="6" fill="#e23a3a" opacity="0.09"/>
  <rect x="25" y="140" width="615" height="42" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.3"/>
  <text x="332" y="166" text-anchor="middle" font-size="11.5" font-weight="600" fill="currentColor">La firma se verifica con la clave que manda el cliente: con exponente 1, se valida sola</text>
</svg>
```

El detalle que cierra el razonamiento es que **la verificación de la firma usa la clave que envía el cliente**, no la que está guardada. Entonces alguien que conozca el módulo de una clave autorizada —que es información pública, para eso es la parte pública— puede presentar ese mismo módulo con **exponente 1**. Elevar un número a la potencia 1 lo devuelve intacto, así que la operación que debería demostrar la posesión de la clave privada se vuelve trivial y la firma se valida sin tener nada.

Es un recordatorio incómodo: la autenticación por clave es más fuerte que la contraseña **siempre que el servidor la valide bien**. Acá el problema no estuvo en la criptografía sino en la comparación.

Las otras tres fallas coordinadas no se están explotando, pero conviene saber qué son porque el mismo parche las cierra: una permite **falsificar certificados** para hacerse pasar por un servidor TLS cuando el router inicia conexiones salientes, otra permite **escribir archivos sin autenticarse** aprovechando una renegociación de SSH, y la tercera permite **leer archivos del sistema desde WebFig**, incluidos los que guardan credenciales.

## Por qué CVE-2026-67276, la falla que abre la puerta, no está en el catálogo de CISA

Acá hay algo que vale la pena mirar de cerca. CISA sumó al catálogo de vulnerabilidades explotadas **dos de las seis**: CVE-2026-86060, la escalada, y CVE-2026-67277, la del bandwidth-test. La que falta es precisamente **CVE-2026-67276**, el bypass de autenticación que es la primera mitad de MikroTrick y sin el cual la escalada no tiene sobre qué escalar.

Tampoco NVD la analizó todavía: al 14 de septiembre sigue en estado *Awaiting Analysis*, sin puntaje propio, mientras sus dos compañeras ya tienen el suyo.

La consecuencia práctica es cero, porque **el mismo upgrade corrige las seis**. La consecuencia para quien arma prioridades leyendo el catálogo no es cero: si el criterio es "parcheo lo que está en KEV", en este caso el catálogo describe la mitad de atrás del ataque y omite la de adelante. Es la misma lección que dejó [el caso de PaperCut](/noticia/papercut-ng-mf-explotado-parche-2), donde el aviso del fabricante llegó cuatro días antes que la entrada en el catálogo: **el catálogo confirma explotación, no describe cadenas**.

El plazo que fijó CISA para las dos que sí catalogó fue de **tres días** —del 10 al 13 de septiembre—, el escalón más corto que usa. Para dimensionarlo: sobre las 1709 entradas que tiene el catálogo en toda su historia, solo 110 llevan un plazo de tres días o menos, y 102 de esas 110 son de 2026.

## ¿A quién afecta MikroTrick y desde qué versión de RouterOS?

A cualquier equipo con RouterOS **anterior a 6.49.21, 7.23.4 o 7.24.2**, según la rama. Y ahí hay una distinción que cambia el diagnóstico:

- **Rama 7.x**: afectada por las seis, incluida la cadena completa MikroTrick.
- **Rama 6.x**: afectada por la escalada de privilegios y por la del bandwidth-test, **pero no por el bypass de SSH**, que según CERT Polska existe solo en 7.x.

Un equipo en 6.x no es tomable por la cadena completa, lo que no lo deja tranquilo: sigue teniendo una escalada de privilegios explotada y una falla que permite reiniciarlo desde afuera. Las dos están en el catálogo de CISA.

El riesgo real se concentra en una condición concreta: **el SSH del router alcanzable desde internet**. MikroTik aclara que su configuración de fábrica bloquea ese puerto desde afuera, así que los equipos expuestos lo están porque alguien abrió el puerto a mano —para administrar desde casa, para que lo atienda el proveedor, para no depender de una VPN—. Es exactamente el perfil de la PyME de la región: un router barato en el borde, configurado una vez por un tercero hace años, que funciona y al que nadie mira.

```svg
<svg viewBox="0 0 680 205" role="img" aria-label="Cronología de MikroTrick: los ataques a RouterOS ocurren desde al menos el 2 de septiembre de 2026; el 3 de septiembre MikroTik publica un aviso que dice explícitamente que no divulga detalles todavía para dar tiempo a actualizar; el 5 de septiembre CERT Polska publica la descripción completa de las seis fallas y confirma la explotación activa, y NVD publica los CVE; el 10 de septiembre CISA suma dos de las seis a su catálogo con un plazo de tres días; el 13 de septiembre vence ese plazo">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Los ataques empezaron antes que el aviso</text>

  <rect x="55" y="104" width="175" height="28" fill="#e23a3a" opacity="0.12"/>
  <path d="M40 118 L650 118" stroke="currentColor" stroke-width="1.4" opacity="0.45"/>

  <text x="70" y="58" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">2 de septiembre</text>
  <text x="70" y="74" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">ataques en curso</text>
  <path d="M70 82 L70 110" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <circle cx="70" cy="118" r="5" fill="#e23a3a"/>

  <circle cx="215" cy="118" r="5" fill="currentColor"/>
  <path d="M215 126 L215 136" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <text x="215" y="150" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">3 de septiembre</text>
  <text x="215" y="166" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">aviso sin detalles</text>

  <text x="360" y="58" text-anchor="middle" font-size="10.5" font-weight="700" fill="currentColor">5 de septiembre</text>
  <text x="360" y="74" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CERT Polska publica todo</text>
  <path d="M360 82 L360 110" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="360" cy="118" r="5" fill="currentColor"/>

  <circle cx="505" cy="118" r="5" fill="#e23a3a"/>
  <path d="M505 126 L505 136" stroke="#e23a3a" stroke-width="1" opacity="0.5"/>
  <text x="505" y="150" text-anchor="middle" font-size="10.5" font-weight="700" fill="#e23a3a">10 de septiembre</text>
  <text x="505" y="166" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.85">CISA: 2 de 6, plazo 3 días</text>

  <text x="650" y="58" text-anchor="end" font-size="10.5" font-weight="700" fill="currentColor">13 de septiembre</text>
  <text x="650" y="74" text-anchor="end" font-size="10.5" fill="currentColor" opacity="0.85">vence el plazo</text>
  <path d="M630 82 L630 110" stroke="currentColor" stroke-width="1" opacity="0.4"/>
  <circle cx="630" cy="118" r="5" fill="currentColor"/>

  <text x="20" y="196" font-size="11.5" font-weight="600" fill="currentColor">El día que MikroTik avisó, los equipos ya se estaban tomando hacía al menos 24 horas</text>
</svg>
```

## ¿Quién puede ignorar el aviso de MikroTik?

Nadie que tenga un equipo con RouterOS, y conviene ser literal con eso: hay que actualizar igual. Pero la urgencia no es la misma para todos.

Si el router **nunca tuvo puertos de administración abiertos a internet** —ni SSH, ni WebFig, ni el bandwidth-test— y se administra desde la red interna o por VPN, la ventana es distinta: se puede programar como una actualización normal en lugar de resolverlo un domingo. MikroTik dice lo mismo para los usuarios hogareños, que con la configuración de fábrica no corren riesgo inmediato.

Quien no tenga MikroTik, directamente no aplica. Es hardware de red, no software que se instale sobre otra cosa.

## ¿Cómo sé si mi MikroTik fue comprometido?

Hay tres comprobaciones concretas, y la primera es la más rápida de todas.

**1. Buscá un usuario llamado `ops`.** CERT Polska señala la presencia de un usuario muy privilegiado con ese nombre como indicador de compromiso. Es el rastro que dejaron los ataques observados.

**2. Revisá el registro del router.** Estas son las dos entradas que dejaron los ataques, tal como las publicó CERT Polska:

```
login failure for user -2 from <ip> via ssh
user <name> added by ssh:-2@<ip>
```

Ese `-2` es el nombre de usuario falsificado del que hablábamos: no es un error de tipeo del registro, es la huella de la inyección de argumentos.

**3. Mirá el marcador "Flagged".** Las versiones corregidas analizan la configuración al arrancar, desactivan lo que reconocen como sospechoso, escriben un mensaje crítico en el log y marcan el equipo. Se consulta así:

```
/system/device-mode/print
```

Si `flagged` dice `yes`, tratá el equipo como comprometido.

Las direcciones desde las que CERT Polska observó los ataques exitosos, incluida la creación del usuario `ops`, son `82.192.72.4`, y `103.102.31.18` para intentos de explotación. Sirven para buscar hacia atrás en los registros del firewall.

Ahora la advertencia que acompaña a todo esto, y que está tanto en el texto de CERT Polska como en el de MikroTik: **la ausencia de estos rastros no prueba que el equipo esté limpio**. El mecanismo de marcado detecta solo algunas huellas conocidas, y CERT Polska agrega que no puede descartar la existencia de fallas que el fabricante no describió en el changelog.

## ¿Qué hago si tengo un MikroTik en el borde de mi red?

1. **Actualizá a 6.49.21, 7.23.4 o 7.24.2**, según tu rama. Es lo único que cierra las seis. El equipo ofrece la actualización en el menú "Check for updates".
2. **Cerrá SSH y WebFig hacia internet.** Si por algún motivo no podés actualizar hoy, esto es lo que reduce la exposición mientras tanto: restringí SSH, WWW/WWW-SSL y el servidor de bandwidth-test a las redes de administración de confianza. CERT Polska lo plantea como medida temporal, no como reemplazo del parche.
3. **Después de actualizar, revisá la configuración**: usuarios que no reconozcas, scripts, tareas del scheduler, servidores proxy y túneles. Es donde queda la persistencia.
4. **Si el equipo quedó marcado, no lo limpies a mano.** CERT Polska recomienda aislarlo, asegurar primero los registros y la configuración, recién después resetear a fábrica, reconfigurarlo desde una configuración verificada y cambiar contraseñas, claves y secretos. Y algo que es fácil equivocar: **no restaures un backup completo del equipo comprometido**, porque restaurarías también lo que te dejaron adentro. Tampoco borres el marcador antes de terminar el análisis.
5. **Sacá la administración de internet, de forma permanente.** Si el puerto estaba abierto para poder administrar desde afuera, el reemplazo razonable es un acceso por identidad —[Tailscale](/producto/tailscale-business), [Twingate](/producto/twingate) o [Cloudflare Access](/producto/cloudflare-access)— en lugar de un puerto publicado. El razonamiento completo está en [acceso remoto seguro sin VPN](/guia/acceso-remoto-seguro-sin-vpn), y el resto de la categoría en [VPN y acceso remoto](/productos/vpn-y-acceso-remoto).

El patrón no es nuevo: [Citrix NetScaler y Cisco FMC](/noticia/citrix-netscaler-cisco-fmc-misma-falla-camino-alternativo) fueron exactamente lo mismo hace una semana, equipos de borde con un camino alternativo de autenticación. Los términos están en el [glosario](/guia/glosario-ciberseguridad-pymes) y el orden general de prioridades, en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

## Las seis fallas de RouterOS las encontró un agente con modelos de lenguaje

Hay una parte del texto de CERT Polska que no es habitual y que conviene leer entera, porque es de las pocas veces que un equipo nacional explica el método.

Las seis vulnerabilidades las descubrió **Sławomir Rozbicki, del equipo de CERT Polska**, usando los modelos GPT-5.5-cyber y GPT-5.6-sol dentro del programa GTAC de OpenAI. El agente trabajó en un laboratorio aislado con máquinas MikroTik reales: automatizó crearlas y restaurarlas, descargar y comparar versiones, analizar RFC y código binario, y construir scripts que confirmaran cada hallazgo. La técnica que según ellos rindió más fue **modelar los protocolos como máquinas de estados y probar qué pasa cuando una etapa se saltea, se repite o se ejecuta fuera de orden**. Mirando las seis fallas, se nota: una sesión de bandwidth-test que arranca antes de autenticar, un SSH que entra en fase de comandos tras una renegociación sin haber autenticado nunca.

Y la parte que más vale, que es la que suele faltar en este tipo de anuncios: CERT Polska aclara que **esto no salió de un prompt**. Cada hipótesis requirió confirmación en un RouterOS real, pruebas de control negativas, repetición sobre una máquina limpia y una evaluación de impacto hecha por los investigadores. Lo más costoso del proyecto, dicen, fue preparar el contexto útil sobre RouterOS, diseñar el laboratorio, elegir las direcciones de investigación y después verificar todo y descartar conclusiones falsas.

Es el otro lado de una historia que el sitio ya cubrió del lado del ataque, cuando [Aurora usó un asistente de código para planear intrusiones](/noticia/aurora-ransomware-cursor-ia-planear-intrusiones) y cuando [un agente de OpenAI encontró una falla en el kernel de Linux](/noticia/kernel-linux-cve-2026-53362-agentes-openai). La herramienta es la misma; acá la usó el que defiende, y el resultado fueron seis fallas encontradas y corregidas antes de que se publicaran los detalles.

También explica por qué CERT Polska publicó tan rápido: como los paquetes corregidos ya eran públicos, comparar versiones permitía reconstruir parte de los arreglos. Lo dicen sin vueltas —se limitan a lo que el administrador necesita y no publican código de explotación—, que es la otra mitad de la decisión que MikroTik tomó al revés: su aviso del 3 de septiembre dice textualmente *"To give time to update your systems, we are not currently publishing detailed information."* Para cuando eso se publicó, los ataques llevaban al menos un día.

---

## Preguntas frecuentes sobre MikroTrick y RouterOS

### ¿Alcanza con desactivar SSH en mi MikroTik en lugar de actualizar?

Reduce mucho la exposición, pero no cierra el tema. Cerrar SSH hacia internet elimina el camino de MikroTrick, que necesita ese servicio. Quedan afuera de esa protección la falla del bandwidth-test, que usa su propio servicio, y las de WebFig y certificados. CERT Polska lo plantea explícitamente como medida temporal hasta poder actualizar, no como alternativa.

### Tengo RouterOS 6 y no puedo pasar a la rama 7. ¿Estoy cubierto?

Actualizando a **6.49.21** quedás cubierto de las fallas que afectan a tu rama. La rama 6 no tiene el bypass de autenticación SSH, que según CERT Polska existe solo en 7.x, pero sí tiene la escalada de privilegios CVE-2026-86060 y la del bandwidth-test CVE-2026-67277, que son las dos que CISA catalogó como explotadas. O sea: no hace falta migrar de rama, pero sí hace falta actualizar.

### Mi router quedó en estado "Flagged". ¿Lo limpio y sigo?

No. El marcador significa que RouterOS reconoció rastros de una modificación no autorizada y desactivó lo que encontró, no que el problema esté resuelto. CERT Polska recomienda tratar el equipo como comprometido: aislarlo, guardar registros y configuración antes de tocar nada, resetear a fábrica y reconfigurar desde una base confiable, y cambiar todas las credenciales que pasaron por ahí. Borrar el marcador antes de terminar el análisis destruye la evidencia.

### ¿Por qué CISA catalogó solo dos de las seis fallas de RouterOS?

El catálogo registra explotación confirmada caso por caso, no cadenas completas ni lotes de un fabricante. CISA sumó CVE-2026-86060 y CVE-2026-67277 el 10 de septiembre y dejó afuera CVE-2026-67276, que es la que abre la sesión. En la práctica no cambia nada, porque las seis se corrigen con la misma actualización; sí cambia si armás prioridades leyendo únicamente el catálogo.

### Un cliente me pregunta si su router está afectado. ¿Qué le respondo?

Con cuatro datos: qué versión de RouterOS corre y de qué rama, si el SSH o el WebFig estuvieron accesibles desde internet, si existe un usuario `ops` o entradas con `-2` en el registro, y qué dice `flagged` en `/system/device-mode/print`. Con eso alcanza para decir si hay que investigar o solo actualizar. El formato para ese tipo de respuestas está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).
