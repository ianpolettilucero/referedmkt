---
title: "Xen Orchestra VSA-2026-062: entran como admin sin contraseña y toman XCP-ng"
subtitle: Vates confirma que la falla ya se explota. Un token vacío alcanza para entrar como administrador a Xen Orchestra, y desde ahí los atacantes llegaron a root en los hosts XCP-ng. La corrección está desde el 10 de noviembre, pero una segunda falla del mismo ataque sigue abierta.
excerpt: Xen Orchestra VSA-2026-062 da acceso de administrador sin credenciales y ya se explota. Qué versión corrige, la falla que sigue abierta y cómo buscar rastros.
type: news
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-11-16
updated: 2026-11-26
products: []
meta_title: "Xen Orchestra VSA-2026-062 ya se explota"
meta_description: "Xen Orchestra VSA-2026-062 da acceso de administrador sin credenciales y ya se explota. Qué versión corrige, qué sigue abierto y cómo buscar rastros."
---

Vates, la empresa detrás de **XCP-ng** y **Xen Orchestra**, publicó el 10 de noviembre [el aviso VSA-2026-062](https://docs.vates.tech/security-advisories/advisories/2026/vates-sa-2026-062) con una advertencia en la primera línea: *"This vulnerability is actively exploited by a threat actor"*. La falla deja entrar a Xen Orchestra, el panel web con el que se administran los servidores de virtualización XCP-ng, sin ninguna credencial. Según el aviso, el atacante puede *"perform actions with 'Admin' privilege"*, y eso *"can lead to arbitrary code execution on managed pools and a full XCP-ng node takeover as root"*.

| Aviso | Qué permite | Severidad según Vates | Estado al 16 de noviembre |
|---|---|---|---|
| VSA-2026-062 | Entrar como administrador a Xen Orchestra sin credenciales | Crítica | Corregida en 6.9.2 y 6.10.1 |
| [VSA-2026-063](https://docs.vates.tech/security-advisories/advisories/2026/vates-sa-2026-063) | Que un usuario con permiso de importar VM tome el host XCP-ng | Importante | Sin corrección |

**Actualización del 26 de noviembre:** VSA-2026-062 ya tiene número, [CVE-2026-133469](https://nvd.nist.gov/vuln/detail/CVE-2026-133469), con un puntaje de 9,8 asignado por GitHub como autoridad de numeración; NVD no cargó uno propio. CISA la sumó el 25 de noviembre a su [catálogo de vulnerabilidades explotadas](https://www.cisa.gov/known-exploited-vulnerabilities-catalog), con plazo al 28. Las versiones que la corrigen son las mismas, 6.9.2 y 6.10.1. Cuando se publicó esta nota, ninguno de los dos avisos tenía CVE ni puntaje CVSS, porque Vates los publica con su propia numeración.

XCP-ng y Xen Orchestra son de código abierto y se usan como alternativa a VMware: Xen Orchestra incluso trae una función para importar máquinas desde VMware ESXi, que aparece en la lista de avisos de Vates. El panel concentra el control de todas las máquinas virtuales, los backups y las credenciales de los servidores: es la llave de todo lo demás.

```svg
<svg viewBox="0 0 680 292" role="img" aria-label="Cómo fue el ataque a Xen Orchestra que describen los avisos de Vates del 10 de noviembre de 2026. Primero, el atacante llega a un Xen Orchestra alcanzable por la red. Segundo, explota VSA-2026-062: un token de sesión vacío hace que Xen Orchestra lo autentique como el dueño de un token válido, sin credenciales. Tercero, con permisos de administrador, saca tokens de inicio de sesión reutilizables. Cuarto, importa una máquina virtual armada con parámetros especiales, el camino de VSA-2026-063, que todavía no tiene corrección. Quinto, obtiene root en el host XCP-ng. Sexto, deja persistencia, con tareas programadas y archivos ocultos que lista Vates.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Xen Orchestra: de un token vacío a root en el host XCP-ng</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según los avisos de Vates. El rojo marca el paso que todavía no tiene corrección.</text>
  <rect x="16" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Xen Orchestra alcanzable: por internet o desde la red</title></rect>
  <text x="118" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Xen Orchestra alcanzable</text>
  <text x="118" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">por internet o desde la red</text>
  <rect x="238" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>VSA-2026-062: un token vacío pasa como válido</title></rect>
  <text x="340" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">VSA-2026-062</text>
  <text x="340" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">un token vacío pasa como válido</text>
  <rect x="460" y="62" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Admin de Xen Orchestra: y tokens que puede reusar</title></rect>
  <text x="562" y="86" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Admin de Xen Orchestra</text>
  <text x="562" y="104" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">y tokens que puede reusar</text>
  <path d="M222 91 H234 M229 86 L234 91 L229 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 91 H456 M451 86 L456 91 L451 96" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M562 120 V138 H118 V154 M113 149 L118 154 L123 149" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <rect x="16" y="156" width="204" height="58" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>VM armada importada: VSA-2026-063, todavía abierta</title></rect>
  <text x="118" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">VM armada importada</text>
  <text x="118" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">VSA-2026-063, todavía abierta</text>
  <rect x="238" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Root en el host XCP-ng: control del hipervisor</title></rect>
  <text x="340" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Root en el host XCP-ng</text>
  <text x="340" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">control del hipervisor</text>
  <rect x="460" y="156" width="204" height="58" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>Persistencia: tareas de cron y archivos ocultos</title></rect>
  <text x="562" y="180" class="ch-strong" font-size="11" text-anchor="middle" font-weight="600" fill="currentColor">Persistencia</text>
  <text x="562" y="198" class="ch-label" font-size="10.5" text-anchor="middle" fill="currentColor" opacity="0.8">tareas de cron y archivos ocultos</text>
  <path d="M222 185 H234 M229 180 L234 185 L229 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <path d="M444 185 H456 M451 180 L456 185 L451 190" class="ch-arrow" stroke="currentColor" stroke-opacity="0.5" fill="none"/>
  <text x="20" y="246" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Vates: la explotación no deja ningún registro en Xen Orchestra.</text>
  <text x="20" y="276" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: avisos VSA-2026-062 y VSA-2026-063 de Vates, 10 de noviembre de 2026.</text>
</svg>
```

## ¿Cómo funciona VSA-2026-062 en Xen Orchestra?

El aviso lo explica en cuatro pasos. La API REST de Xen Orchestra lee la cookie de sesión, y un valor armado de cierta forma se convierte en un objeto vacío en lugar de un texto. Ese objeto llega al buscador de tokens, que lo interpreta como *"an unfiltered lookup"*: una búsqueda sin filtro que devuelve el primer token guardado. Si ese token no venció, Xen Orchestra autentica la conexión como su dueño, que puede ser un administrador. Con eso, el atacante puede pedir tokens de inicio de sesión reutilizables y volver cuando quiera.

El segundo aviso, VSA-2026-063, explica cómo siguió el ataque. Xen Orchestra le habla al servidor XCP-ng con permisos totales, y al importar una máquina virtual reenvía su configuración sin revisarla. Una VM importada con ciertos parámetros armados puede comprometer el host. Vates dice que *"This path has been abused by a threat actor"*, el mismo de VSA-2026-062, con permisos de administrador. Y advierte algo más: un usuario sin privilegios de administrador, pero con permiso para importar VM, también podría hacerlo.

La falla la encontraron Filip Magic y Christoffer Olsen, de TDC NET Security, según los créditos del aviso.

## ¿Mi Xen Orchestra está afectado por VSA-2026-062?

Sí, si corre una versión anterior a la **6.9.2**, en el canal *stable*, o a la **6.10.1**, en el canal *latest*. La Xen Orchestra Appliance, la versión empaquetada que distribuye Vates, también está afectada. Quien instaló Xen Orchestra desde el código fuente tiene que actualizar a la última versión de la rama *master*, según el aviso.

Para VSA-2026-063 no alcanza con actualizar: afecta a la 6.10.1 y anteriores, y al 16 de noviembre, 6 días después del aviso, Vates sigue trabajando en la corrección.

```svg
<svg viewBox="0 0 680 280" role="img" aria-label="Qué corrige la actualización de Xen Orchestra y qué sigue abierto, según Vates al 16 de noviembre de 2026. VSA-2026-062, severidad crítica, explotada: afecta a Xen Orchestra antes de la 6.9.2 en el canal stable y de la 6.10.1 en el canal latest, y a la Xen Orchestra Appliance. La corrigen la 6.9.2 y la 6.10.1, con xo-server 5.211.2 y 5.213.1; quien instaló desde el código fuente tiene que actualizar a la última versión de master. VSA-2026-063, severidad importante: afecta a la 6.10.1 y anteriores y no tiene corrección. La única mitigación es deshabilitar a los usuarios con los permisos vm.import, sr/import:vm, network/action:create o network/action:update, para que solo un administrador pueda importar máquinas virtuales.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Xen Orchestra: qué corrige la actualización y qué sigue abierto</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Según Vates, al 16 de noviembre. El rojo marca la falla que todavía no tiene corrección.</text>
  <rect x="16" y="58" width="648" height="94" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"><title>VSA-2026-062: corregida en 6.9.2 y 6.10.1</title></rect>
  <text x="32" y="80" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">VSA-2026-062, crítica y explotada: corregida</text>
  <text x="32" y="100" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Canal stable: Xen Orchestra 6.9.2, con xo-server 5.211.2.</text>
  <text x="32" y="118" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Canal latest: Xen Orchestra 6.10.1, con xo-server 5.213.1.</text>
  <text x="32" y="136" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Instalado desde el código fuente: actualizar a la última versión de master.</text>
  <rect x="16" y="162" width="648" height="94" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"><title>VSA-2026-063: sin corrección al 16 de noviembre</title></rect>
  <text x="32" y="184" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">VSA-2026-063, importante: sin corrección</text>
  <text x="32" y="204" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Afecta a la 6.10.1 y anteriores: la actualización no la cierra.</text>
  <text x="32" y="222" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Mitigación: deshabilitar usuarios con vm.import, sr/import:vm,</text>
  <text x="32" y="240" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">network/action:create o network/action:update.</text>
  <text x="20" y="272" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: avisos VSA-2026-062 y VSA-2026-063 de Vates.</text>
</svg>
```

## ¿Qué hago si tengo Xen Orchestra?

1. **Actualizá hoy.** La corrección está disponible desde hace 6 días. En la Xen Orchestra Appliance, la [documentación del actualizador](https://docs.xen-orchestra.com/updater) indica la vista *XOA > Updates*, o el comando `xoa-updater --upgrade` en la consola. El canal *stable* recibe versiones que llevan un mes en el canal *latest*, y las dos ramas ya tienen la corrección.
2. **Sacá Xen Orchestra de internet.** Es la primera mitigación del aviso: *"Do not expose Xen Orchestra to the Internet"*, y ponerlo en una red de administración aparte. Para administrar desde afuera sin publicar el panel, las opciones están en [la guía de acceso remoto seguro](/guia/acceso-remoto-seguro-sin-vpn).
3. **Restringí la importación de máquinas virtuales.** Mientras VSA-2026-063 siga abierta, Vates pide deshabilitar a los usuarios que tengan los permisos `vm.import`, `sr/import:vm`, `network/action:create` o `network/action:update`, para que solo un administrador pueda importar.
4. **Bloqueá la infraestructura del atacante.** El aviso lista ocho direcciones IP y una dirección de Tor, y recomienda bloquear el tráfico de entrada y de salida. Revisá también los registros del firewall de por lo menos los últimos 30 días, buscando conexiones con esas direcciones. Si tenés un proxy inverso o un firewall de aplicaciones delante de Xen Orchestra, Vates sugiere bloquear también los pedidos con el token vacío, aunque advierte que una regla así se puede esquivar con otros formatos.
5. **Buscá rastros, aunque ya hayas actualizado.** Vates es claro: *"there is no log to detect unauthenticated access"*. Las señales que lista el aviso para esta campaña son:
   - una máquina virtual llamada `pivot2` en ejecución;
   - en los hosts XCP-ng, VM con el parámetro `hvm_serial: file`, que se ven con `xe vm-list params=name-label,platform`;
   - archivos `/etc/ld.so.preload`, `/.ustar5`, `/.ustar6` o tareas programadas `/etc/cron.d/zz-ustar` y `zz-ustar2`.
   El aviso aclara que no encontrar estas señales no prueba que no hubo intrusión.
6. **Si hubo compromiso, cambiá todos los secretos y reinstalá.** Vates pide rotar las contraseñas del proxy, de los destinos de backup (NFS, S3, Azure, SMB), la de root, el secreto del pool, las cuentas de usuario, los códigos de segundo factor y la del servidor de correo. Y recomienda *"a full reinstallation"*, porque la limpieza sola *"cannot guarantee the removal of all backdoors"*. Es la misma lógica que se explicó con [Citrix NetScaler](/noticia/citrix-netscaler-cve-2026-88771-reconstruir-no-parchear).

Los backups merecen un párrafo aparte: si el atacante tuvo las credenciales de los destinos de backup, también pudo llegar a las copias. Una copia que el panel de virtualización no puede tocar es la que queda en pie; las opciones están en [la comparativa de backup y recuperación](/productos/backup-y-recuperacion).

## ¿Por qué el panel de Xen Orchestra es un blanco tan valioso?

Porque el que controla el panel controla todos los servidores a la vez. No es la primera vez este año: la falla de [VMware ESX con puntaje 2,7](/noticia/vmware-esx-cve-2026-41709-puntaje-2-7-explotado) también se explotó, y la semana pasada los atacantes de [los gateways de Check Point](/noticia/check-point-php2shell-cve-2026-123039-root-gateway), que llegaron 7 días después del parche, usaron el firewall para saltar a un servidor VMware ESXi y borrar archivos. Cambiar de VMware a XCP-ng cambia de proveedor, pero no el riesgo: un panel de administración expuesto sigue siendo la puerta más corta a toda la infraestructura.

## ¿Quién puede ignorar el aviso de Xen Orchestra?

Quien no use Xen Orchestra. XCP-ng administrado solo con la consola del host o con otra herramienta queda fuera de VSA-2026-062, que es una falla del panel. Pero el mismo 10 de noviembre Vates publicó otros avisos para XCP-ng 8.3, así que conviene revisar la [lista completa de avisos de Vates](https://docs.vates.tech/security-advisories/advisories/). El orden general de prioridades está en [la guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026).

**Fuentes leídas:** los avisos VSA-2026-062 y VSA-2026-063 de Vates, del 10 de noviembre de 2026, y el índice de avisos, consultado el 16 de noviembre; la documentación del actualizador de Xen Orchestra; y, el 26 de noviembre, la ficha de NVD de CVE-2026-133469 y el catálogo de CISA. **Sin confirmar:** quién está detrás de los ataques y a cuántas instalaciones llegaron, porque Vates no da cifras; y desde cuándo se explota la falla.

## Preguntas frecuentes sobre Xen Orchestra VSA-2026-062

### ¿Qué versión de Xen Orchestra corrige VSA-2026-062?

La 6.9.2 en el canal stable y la 6.10.1 en el canal latest, con xo-server 5.211.2 y 5.213.1. Si instalaste desde el código fuente, actualizá a la última versión de master.

### ¿VSA-2026-062 tiene número de CVE?

Sí, desde el 23 de noviembre: CVE-2026-133469, con 9,8 según GitHub, que emitió el número. Vates la había publicado con su propia numeración y sin puntaje CVSS. CISA la sumó a su catálogo de explotadas el 25 de noviembre.

### ¿Alcanza con actualizar Xen Orchestra?

No del todo. La actualización cierra VSA-2026-062, pero VSA-2026-063 sigue abierta 6 días después del aviso. Mientras tanto, deshabilitá a los usuarios con permiso para importar máquinas virtuales y revisá las señales de intrusión que lista Vates.

### ¿Cómo sé si atacaron mi Xen Orchestra?

La explotación no deja registro en Xen Orchestra. Vates sugiere buscar una VM llamada pivot2, máquinas virtuales con el parámetro hvm_serial: file en los hosts XCP-ng y archivos como /etc/ld.so.preload o tareas en /etc/cron.d/zz-ustar.

### ¿Qué hago si mi Xen Orchestra fue comprometido?

Vates recomienda cambiar todas las contraseñas y secretos que usa la plataforma, incluidos los de los destinos de backup y el secreto del pool, y reinstalar desde cero, porque la limpieza no garantiza sacar todas las puertas traseras.
