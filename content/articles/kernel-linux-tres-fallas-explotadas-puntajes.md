---
title: "Kernel de Linux: tres fallas explotadas y tres puntajes que no coinciden"
subtitle: CISA sumó tres vulnerabilidades del kernel el 18 de septiembre con plazo al 21. En una de ellas el kernel dice 9,8 por red y NVD dice 7,1 en local: no es un error, es que suponen configuraciones distintas.
excerpt: Tres fallas del kernel de Linux entraron al catálogo de CISA el mismo día. Cuál te toca depende de si usás kTLS o reglas de ebtables, y de si reiniciaste.
type: news
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-09-19
updated: 2026-09-19
meta_title: "Kernel de Linux: tres fallas ya explotadas"
meta_description: "CISA sumó tres fallas del kernel de Linux al catálogo con plazo de tres días. Cuál te afecta depende de tu configuración. Cómo comprobarlo en tu servidor."
---

El 18 de septiembre CISA incorporó al catálogo de vulnerabilidades explotadas **tres fallas del kernel de Linux de una sola vez**, las tres con plazo al **21 de septiembre** y las tres marcadas con el requisito de triaje forense de la directiva BOD 26-04. Es apenas la segunda vez en toda la historia del catálogo que entran tres entradas del kernel el mismo día: la anterior fue el 15 de septiembre de 2022.

Las tres tienen algo incómodo en común, y es el motivo de esta nota: **ninguna de las tres tiene un puntaje del que todos estén de acuerdo**.

| CVE | Qué toca | Kernel (CNA) | NVD | Red Hat |
|---|---|---|---|---|
| CVE-2025-39682 | Recepción de kTLS | 9,8 crítico | 7,1 alto | 7,0 moderado |
| CVE-2026-53266 | Destino SNAT de ebtables | 8,8 alto | sin puntaje propio | 7,5 importante |
| CVE-2025-39964 | Sockets AF_ALG | 7,8 alto | 5,5 medio | 5,5 moderado |

Antes de mirar los números conviene saber que **los tres puntajes se calculan sobre supuestos distintos**, y que de esos supuestos sale la única pregunta que importa para tu servidor.

## Por qué el kernel dice "por red" y NVD dice "en local" sobre CVE-2025-39682

El desacuerdo más grande está en la falla de kTLS, y no es de una décima: es sobre **desde dónde se ataca**.

```svg
<svg viewBox="0 0 680 215" role="img" aria-label="Los tres puntajes de CVE-2025-39682 en el kernel de Linux y sus vectores: el equipo del kernel, que actúa como autoridad de numeración, le asigna 9.8 crítico con vector de red y sin credenciales; Red Hat le asigna 7.0 moderado, también por red y sin credenciales pero con complejidad alta y menor impacto; y NVD le asigna 7.1 alto pero con vector local y exigiendo privilegios previos. La diferencia no es de criterio sino de supuesto: Red Hat aclara que la falla solo se puede disparar de forma remota cuando el kernel TLS está realmente en uso">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">CVE-2025-39682: tres autoridades, dos vectores distintos</text>

  <text x="20" y="66" font-size="10.5" font-weight="700" fill="#e23a3a">Kernel (CNA)</text>
  <rect x="200" y="53" width="392" height="16" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="600" y="66" font-size="10.5" font-weight="700" fill="#e23a3a">9,8</text>
  <text x="200" y="84" font-size="10" fill="currentColor" opacity="0.8">AV:N / PR:N — por red, sin credenciales</text>

  <text x="20" y="116" font-size="10.5" font-weight="700" fill="currentColor">Red Hat</text>
  <rect x="200" y="103" width="280" height="16" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="488" y="116" font-size="10.5" font-weight="700" fill="currentColor">7,0</text>
  <text x="200" y="134" font-size="10" fill="currentColor" opacity="0.8">AV:N / AC:H / PR:N — por red, sin credenciales, difícil</text>

  <text x="20" y="166" font-size="10.5" font-weight="700" fill="currentColor">NVD</text>
  <rect x="200" y="153" width="284" height="16" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="492" y="166" font-size="10.5" font-weight="700" fill="currentColor">7,1</text>
  <text x="200" y="184" font-size="10" fill="currentColor" opacity="0.8">AV:L / PR:L — local, y con una cuenta previa en la máquina</text>

  <text x="20" y="206" font-size="11.5" font-weight="600" fill="currentColor">El que queda solo es NVD, no el fabricante: kernel y Red Hat coinciden en que es por red</text>
</svg>
```

La costumbre dice que NVD es la medida conservadora y que el fabricante exagera. Acá pasa lo contrario: **el equipo del kernel y Red Hat coinciden en que la falla se dispara por red y sin credenciales, y el que queda solo es NVD**, que la califica como local y con privilegios previos.

La explicación está en una línea del análisis de Red Hat, y resuelve el enigma: *"This can be remotely triggered only when kernel TLS (CONFIG_TLS with the TLS ULP) is in use."* Sólo se dispara de forma remota **cuando el TLS del kernel está realmente en uso**.

Ahí está todo. No hay un error de carga ni un fabricante inflando su aviso: cada quien puntuó un escenario distinto. El kernel y Red Hat puntuaron el caso en que kTLS está activo; NVD puntuó el caso general, donde no lo está. **Los dos números son correctos; lo que cambia es de qué servidor están hablando.**

Y eso convierte la pregunta útil en otra. No es "cuánto puntúa esta falla", sino **"¿mi servidor usa esa función?"**.

## Qué condición necesita cada falla: kTLS, ebtables y AF_ALG

Las tres son configuración-dependientes, y en los tres casos la condición es concreta y comprobable.

- **CVE-2025-39682**, en la recepción de kTLS: un registro de longitud cero tomado de la `rx_list` esquiva el control de tipo de registro por cada `recvmsg()`. Sólo aplica si el kernel está cifrando TLS por su cuenta, algo que pasa cuando se habilita explícitamente en el servidor web o el proxy, no por defecto. Red Hat marca **RHEL 7 y 8 como no afectados**, y corregido en RHEL 9 y 10.
- **CVE-2026-53266**, en el destino SNAT de ebtables: una reescritura de la dirección de hardware del emisor ARP escribe directamente sobre un fragmento no lineal del buffer de socket. Red Hat lo dice con todas las letras: *"This vulnerability requires specific bridge netfilter rules to be configured, limiting its impact to systems with such specialized network configurations."* Hace falta tener reglas de netfilter sobre el bridge, que es exactamente lo que hay en **hosts de contenedores y de máquinas virtuales**, y casi nunca en un VPS que sólo sirve un sitio.
- **CVE-2025-39964**, en los sockets AF_ALG: dos escrituras concurrentes sobre el mismo socket de la API de criptografía del kernel entrelazan los datos y dejan el contexto inconsistente. Es la más acotada de las tres en impacto —Red Hat y NVD coinciden en `A:H` solamente, o sea caída del servicio— y la más incómoda en otro sentido: **Red Hat todavía no publicó corrección**. Figura como *Affected* en RHEL 7, 8 y 10.

Dicho de otro modo: si tu servidor es un VPS común que sirve un sitio web con NGINX o Apache en configuración estándar, las dos primeras probablemente no te alcancen. Si administrás un host con contenedores, la de ebtables sí entra en tu lista.

## CVE-2025-39682 y CVE-2025-39964 se corrigieron hace casi un año

Este es el dato que ordena la urgencia, y se ve mejor con la distancia entre la fecha en que la falla se hizo pública y el día en que CISA confirmó que se estaba explotando.

```svg
<svg viewBox="0 0 680 190" role="img" aria-label="Distancia entre la publicación de cada falla del kernel de Linux y su entrada al catálogo de vulnerabilidades explotadas de CISA el 18 de septiembre de 2026: CVE-2025-39682 se publicó el 5 de septiembre de 2025 y tardó 378 días, algo más de doce meses; CVE-2025-39964 se publicó el 13 de octubre de 2025 y tardó 340 días, algo más de once meses; y CVE-2026-53266 se publicó el 25 de junio de 2026 y tardó 85 días, menos de tres meses. Las correcciones llevaban meses disponibles antes de que se confirmara la explotación">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">De la publicación de la falla al catálogo de explotación activa</text>

  <text x="20" y="66" font-size="10.5" font-weight="700" fill="currentColor">CVE-2025-39682</text>
  <rect x="200" y="53" width="360" height="16" rx="3" fill="#e23a3a" opacity="0.85"/>
  <text x="568" y="66" font-size="10.5" font-weight="700" fill="#e23a3a">12,4 meses</text>

  <text x="20" y="100" font-size="10.5" font-weight="700" fill="currentColor">CVE-2025-39964</text>
  <rect x="200" y="87" width="324" height="16" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="532" y="100" font-size="10.5" font-weight="700" fill="currentColor">11,2 meses</text>

  <text x="20" y="134" font-size="10.5" font-weight="700" fill="currentColor">CVE-2026-53266</text>
  <rect x="200" y="121" width="81" height="16" rx="3" fill="currentColor" opacity="0.4"/>
  <text x="289" y="134" font-size="10.5" font-weight="700" fill="currentColor">2,8 meses</text>

  <text x="20" y="174" font-size="11.5" font-weight="600" fill="currentColor">El parche existía hace meses; lo que es nuevo es la confirmación de que se explota</text>
</svg>
```

Ninguna de las tres es un día cero. Dos se publicaron en septiembre y octubre de **2025** —hace 378 y 340 días respectivamente—, y la corrección está en el árbol del kernel desde entonces. Lo que cambió el 18 de septiembre no es que exista la falla: es que hay evidencia de que se está usando.

Eso desplaza el problema de lugar. **No es un problema de parches que no existen, es un problema de parches que existen y no están aplicados.** Es la misma forma que tenía [el historial de GitLab](/noticia/gitlab-cve-2026-85706-parchear-no-alcanza), donde tres de sus cinco entradas en el catálogo son fallas de 2021 que seguían funcionando en 2026.

## ¿Cómo sé con uname -r si mi servidor corre el kernel parcheado?

Acá está el paso que más se saltea, y no es instalar la actualización: es **reiniciar**.

Actualizar un kernel instala un paquete nuevo, pero el kernel que está corriendo sigue siendo el viejo hasta que la máquina se reinicia. En un servidor que nadie reinicia porque "anda", eso significa que el sistema puede llevar meses con el parche instalado y sin aplicar.

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Diferencia entre el kernel instalado y el kernel en ejecución en un servidor Linux. Después de actualizar, el paquete del kernel nuevo queda instalado en el disco, y el gestor de paquetes lo reporta como al día. Pero el kernel que está corriendo en memoria sigue siendo el anterior hasta que la máquina se reinicia, así que las correcciones no están aplicadas. Se comprueba comparando la salida del comando uname guion r con la versión del paquete instalado">
  <text x="20" y="24" font-size="12.5" font-weight="700" fill="currentColor">Instalado y en ejecución no son lo mismo</text>

  <rect x="20" y="42" width="620" height="62" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="20" y="42" width="620" height="62" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="38" y="64" font-size="11" font-weight="700" fill="currentColor">El kernel instalado</text>
  <text x="38" y="84" font-size="10.5" fill="currentColor" opacity="0.85">Está en el disco después de actualizar. El gestor de paquetes lo da por al día.</text>
  <text x="38" y="98" font-size="10.5" fill="currentColor" opacity="0.85">Un inventario que sólo mire paquetes va a decir que el servidor está corregido.</text>

  <rect x="20" y="116" width="620" height="62" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="20" y="116" width="620" height="62" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.6"/>
  <text x="38" y="138" font-size="11" font-weight="700" fill="#e23a3a">El kernel en ejecución</text>
  <text x="38" y="158" font-size="10.5" fill="currentColor" opacity="0.85">Sigue siendo el anterior hasta que la máquina se reinicia.</text>
  <text x="38" y="172" font-size="10.5" fill="currentColor" opacity="0.85">Mientras tanto las correcciones están instaladas, y no aplicadas.</text>
</svg>
```

Las comprobaciones, en orden:

```
uname -r                                  # kernel en ejecución
dpkg -l 'linux-image-*' | grep ^ii        # instalados, en Debian y Ubuntu
rpm -q kernel                             # instalados, en RHEL y derivados
cat /var/run/reboot-required              # Debian y Ubuntu: existe si falta reiniciar
needs-restarting -r                       # RHEL y derivados
```

Si el número de `uname -r` es menor que el del paquete más nuevo instalado, el reinicio está pendiente y las correcciones no están puestas.

Y para las dos fallas condicionadas, las comprobaciones son igual de cortas:

```
lsmod | grep -w tls                       # ¿está cargado el módulo de kTLS?
lsmod | grep -E 'ebtable|br_netfilter'    # ¿hay netfilter sobre el bridge?
ebtables -t nat -L                        # ¿hay reglas SNAT de ebtables?
```

## ¿A quién afectan las tres fallas del kernel de Linux?

A servidores Linux en general, con el matiz de las condiciones de arriba, y el perfil que más expuesto queda en la región es previsible: **el VPS**. La máquina que se contrató una vez, que sirve el sitio o la aplicación de la empresa, que no tiene a nadie asignado y que no se reinicia desde hace un año y medio.

Red Hat, que es la referencia más clara porque publica estado por versión:

- **CVE-2025-39682**: RHEL 7 y 8 **no afectados**; corregido en RHEL 9 y 10.
- **CVE-2026-53266**: corregido en RHEL 8 y 9; RHEL 10 **afectado**; RHEL 7 no afectado.
- **CVE-2025-39964**: RHEL 7, 8 y 10 **afectados y sin corrección publicada** al momento de escribir esto.

Para Debian, Ubuntu y derivados hay que mirar el aviso de cada distribución: los números de versión del kernel no se comparan entre distribuciones, porque cada una retropropaga las correcciones a su propia rama.

Dos de las tres entradas traen además la advertencia estándar de CISA de que el producto afectado **puede estar fuera de soporte**, en cuyo caso la recomendación deja de ser parchear y pasa a ser migrar.

## ¿Quién puede ignorar el aviso del kernel de Linux?

Nadie que administre un servidor Linux propio puede ignorar la parte del reinicio, porque esa aplica siempre. Lo que sí varía es la urgencia de cada falla concreta.

Si tu empresa **no administra servidores** —el sitio está en un hosting compartido, las aplicaciones son servicios en la nube, los equipos son Windows o macOS— esto no es tuyo. Es de tu proveedor, y es una pregunta legítima para hacerle.

Si el servidor **no tiene kTLS activo ni reglas de ebtables sobre un bridge**, dos de las tres bajan mucho de prioridad. Queda la tercera, que es una caída de servicio y no un robo de datos.

## ¿Qué hago si tengo un servidor Linux propio?

1. **Comprobá primero si el reinicio está pendiente**, con los comandos de arriba. Es probable que el trabajo grande ya esté hecho y falte sólo eso.
2. **Actualizá el kernel y programá el reinicio.** Si la ventana es difícil, sabé que existe la alternativa de parcheo en caliente —Livepatch en Ubuntu, kpatch en RHEL— que aplica correcciones sin reiniciar, con la salvedad de que no cubre todos los casos.
3. **Antes de reiniciar, tomá una instantánea o verificá que tenés copia.** Un servidor que hace un año y medio que no reinicia es también un servidor que nunca probó que arranca; el orden para eso está en [copias de seguridad y recuperación](/productos/backup-y-recuperacion).
4. **Revisá si te tocan las condiciones**: `lsmod | grep -w tls` para kTLS y las reglas de ebtables para la del bridge. Si ninguna de las dos aparece, tu urgencia real es menor que la que sugiere un 9,8 leído sin contexto.
5. **Si el servidor lo administra un tercero**, preguntá tres cosas: qué versión de kernel está corriendo hoy —no cuál está instalada—, cuándo fue el último reinicio, y si usa kTLS o bridges con netfilter. El formato para dejar eso por escrito está en [cómo responder un cuestionario de seguridad](/guia/cuestionario-seguridad-cliente-como-responder).

Es la tercera vez en un mes que el kernel aparece en el catálogo: la anterior fue [la falla que usaron los agentes de OpenAI](/noticia/kernel-linux-cve-2026-53362-agentes-openai) a fines de agosto. Con estas tres, el kernel acumula **31 entradas** en toda la historia del catálogo y **8 en 2026**, que iguala a 2022 como el año con más, y todavía falta el último trimestre.

El orden general de prioridades está en la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026), los términos en el [glosario](/guia/glosario-ciberseguridad-pymes), y si el servidor es parte de tu hosting, el resto de la categoría en [hostings y cloud](/productos/hostings-y-cloud).

---

## Preguntas frecuentes sobre las tres fallas del kernel de Linux

### ¿Cuál de los tres puntajes de CVE-2025-39682 tengo que creer?

Los tres, porque describen situaciones distintas. El 9,8 del equipo del kernel y el 7,0 de Red Hat valen para un servidor donde kTLS está en uso, y ahí la falla se dispara por red y sin credenciales. El 7,1 de NVD vale para el caso general, sin kTLS, donde hace falta ya tener acceso a la máquina. La forma de saber cuál te aplica no es comparar números sino correr `lsmod | grep -w tls` en tu servidor.

### Actualicé el kernel la semana pasada. ¿Ya está?

Sólo si reiniciaste después. El paquete nuevo queda en el disco, pero el kernel que atiende las llamadas sigue siendo el que se cargó en el último arranque. Lo confirmás comparando `uname -r` con la versión del paquete instalado: si no coinciden, el parche está instalado y sin aplicar. Es la diferencia que hace que un inventario de parches diga que estás al día cuando no lo estás.

### ¿Qué es kTLS y cómo sé si lo uso?

Es la función que permite que el cifrado TLS lo haga el kernel en lugar de la aplicación, para ahorrar copias de memoria en servidores con mucho tráfico. No viene activo por defecto: hay que habilitarlo explícitamente en el servidor web o el proxy, y suele hacerse en instalaciones que sirven mucho volumen. La comprobación rápida es `lsmod | grep -w tls`, que muestra si el módulo está cargado.

### Mi servidor es un VPS con un sitio en WordPress. ¿Me toca?

Las dos fallas condicionadas casi seguro que no: un VPS estándar no usa kTLS ni tiene reglas de ebtables sobre un bridge. Igual corresponde actualizar el kernel y reiniciar, tanto por la tercera falla como porque el kernel acumuló ocho entradas en el catálogo este año y la próxima puede no ser condicionada. Y aprovechá para comprobar hace cuánto que esa máquina no reinicia.

### Red Hat dice que para CVE-2025-39964 todavía no hay corrección. ¿Qué hago mientras tanto?

Esperar el paquete y no forzar nada. Es la menos grave de las tres: tanto Red Hat como NVD la puntúan con impacto sólo en disponibilidad, o sea que el peor caso documentado es que el servicio se caiga, no que alguien se lleve datos. Afecta a la API de criptografía del kernel en el espacio de usuario, que es algo que pocas aplicaciones usan directamente. Conviene seguir el aviso de tu distribución y aplicar el paquete cuando salga.
