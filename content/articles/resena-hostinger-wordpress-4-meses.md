---
title: "Reseña de Hostinger para WordPress: 4 meses con dos proyectos"
subtitle: Impresiones honestas después de migrar dos proyectos WordPress a Hostinger. Performance, hPanel, soporte, precio real y qué esperar más allá del marketing.
excerpt: Reseña de Hostinger WordPress después de 4 meses corriendo dos proyectos reales. No es una reseña de año completo — es el análisis honesto de las primeras impresiones profundas, con foco en qué esperar, qué sorprende para bien, qué fricciones aparecen y cuándo Hostinger tiene sentido. Incluye performance medida, experiencia con hPanel, comparación con alternativas locales y recomendación por perfil de proyecto.
type: review
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-04-22 00:25:00
updated: 2026-07-26
products:
  - hostinger-business
meta_title: "Reseña de Hostinger WordPress: 4 meses con dos proyectos reales"
meta_description: Reseña honesta de Hostinger para WordPress tras 4 meses usándolo con dos proyectos. Performance, hPanel, soporte, precio real y comparación con alternativas locales.
---

Esta reseña es **distinta** a las que solés encontrar sobre hosting. No es un año completo de uso, no es un testing sintético de una semana, y no es un análisis basado en información pública. Son **4 meses de uso real con dos proyectos WordPress distintos** en la misma cuenta de Hostinger.

Cuatro meses es un horizonte corto pero honesto: ya no son "primeras impresiones" superficiales, ya viví la curva de aprendizaje completa, ya tuve que resolver problemas reales, ya medí performance con seriedad. Lo que todavía **no** puedo opinar con certeza es sobre estabilidad a largo plazo, comportamiento del soporte en incidentes graves y el salto de precio al renovar — esos tres puntos los voy a aclarar cuando aparezcan.

> **TL;DR** — Hostinger me sorprendió por arriba en casi todas las dimensiones: hPanel es mejor de lo que esperaba, la performance con LiteSpeed y NVMe es real, los 4 meses fueron estables y el precio es difícil de igualar. Las fricciones están — upsells constantes, soporte que resuelve lo básico pero no lo complejo, y la famosa diferencia entre precio inicial y precio de renovación. Para la mayoría de los proyectos WordPress sin requisitos enterprise, es la elección correcta en 2026.

---

## Los dos proyectos

Para que quede claro el contexto, estos son los proyectos que tengo corriendo en Hostinger:

- **Proyecto 1** — sitio corporativo tipo *landing + blog*, con formularios de contacto, algunas páginas de producto y contenido publicado semanalmente. Tráfico mensual bajo-medio.
- **Proyecto 2** — sitio con más interactividad, incluyendo componentes custom, recursos descargables y páginas que se actualizan con más frecuencia.

Ambos corren en WordPress, con Elementor en uno y bloques nativos de Gutenberg en el otro. Los dos comparten la misma cuenta Hostinger (plan que permite múltiples sitios) y el mismo panel de administración.

No son sitios de enterprise. **Son los dos tipos de proyecto WordPress más comunes que cualquier profesional independiente o PyME termina gestionando** — por eso creo que la experiencia es generalizable.

---

## Por qué elegí Hostinger

Evalué cuatro opciones antes de contratar:

1. **Hostinger** — presencia fuerte en LATAM, LiteSpeed incluido, NVMe en Business, precios accesibles
2. **SiteGround** — históricamente muy buena reputación, pero los precios se dispararon desde 2023
3. **WordPress.com Business** — demasiado rígido para el control que quería tener
4. **DonWeb** — local, con la ventaja de factura argentina, pero performance en benchmarks públicos notablemente peor

Elegí Hostinger por tres razones concretas:

- **Stack técnico moderno** — LiteSpeed Web Server, almacenamiento NVMe en el plan Business, PHP 8.2+ por default
- **Presencia en la lista oficial de hosts recomendados por WordPress.org** — que es una señal real, no marketing (Hostinger reemplazó a SiteGround en esa lista en los últimos años)
- **Precio inicial bajo** con buen margen de prueba

El plan contratado fue **[Hostinger Business](/producto/hostinger-business) con término largo**, que es el que tiene sentido cuando se cree que el hosting va a quedarse — el precio por mes baja fuerte comparado con el plan mensual o anual.

---

## Primeras semanas: migración y onboarding

### La migración funcionó

Hostinger ofrece **migración gratuita** para sitios WordPress vía ticket. Abrí el ticket, cargué las credenciales del hosting origen, y el equipo se encargó. Tiempo total hasta tener el primer sitio funcionando en Hostinger: **algunas horas**, no días.

Migraron correctamente:

- Archivos del sitio (tema, plugins, uploads)
- Base de datos completa
- Usuarios y configuración
- URLs reescritas automáticamente

Lo que **no migraron** automáticamente fue la configuración de email corporativo — eso lo reconfiguré yo, que ya estaba en un proveedor externo.

El cambio de DNS lo coordiné manualmente después de revisar la URL de preview. Downtime real: prácticamente cero.

### hPanel: la sorpresa positiva

Venía con prejuicio sobre hPanel. *"Panel propietario, seguro que es peor que cPanel"* — me equivoqué.

Lo que funciona muy bien en hPanel:

- **Dashboard específico por sitio WordPress**, no enterrado en opciones genéricas
- **Acceso a logs** de acceso y error en un click
- **File Manager** moderno y usable sin sentirse viejo
- **Gestor de bases de datos** con acceso directo a phpMyAdmin
- **Certificados SSL** gratuitos con Let's Encrypt instalados automáticamente
- **Búsqueda global** para encontrar cualquier configuración sin navegar menús

Lo que me costó al principio:

- **Navegación** distinta a cPanel — las cosas están en otro lado y con otros nombres
- Algunas **configuraciones avanzadas** están más escondidas o renombradas
- La **lógica de permisos** por sitio vs global tardé en entenderla

En dos semanas ya era productivo. En un mes reconocía que para **gestión específica de WordPress**, hPanel es más cómodo que cPanel para la mayoría de las tareas diarias.

---

## Performance real medida

### Los números

Durante los 4 meses medí regularmente con **GTmetrix** y **PageSpeed Insights**. Promedios consistentes en los dos proyectos:

- **TTFB (Time to First Byte)** — 200-300 ms desde Estados Unidos, 350-450 ms desde Sudamérica sin CDN
- **Largest Contentful Paint (LCP)** — 1.2-1.8 segundos
- **First Contentful Paint (FCP)** — 0.7-1.0 segundos
- **Cumulative Layout Shift (CLS)** — 0.01-0.05
- **PageSpeed score móvil** — entre 85 y 92
- **PageSpeed score desktop** — entre 95 y 98

Para proyectos WordPress **sin ser sitios estáticos optimizados hasta el extremo**, son muy buenos números. La combinación de LiteSpeed + NVMe + PHP moderno se siente — no es solo marketing.

### LiteSpeed Cache: la feature que más aporta

Hostinger incluye **LSCWP (LiteSpeed Cache para WordPress)** preinstalado y preconfigurado. Este plugin, combinado con el servidor LiteSpeed nativo, es probablemente la razón más concreta por la que la performance es tan buena a este precio.

Lo que hace:

- **Page cache** a nivel servidor, no PHP
- **Object cache** con memcached
- **Image optimization** con WebP automático
- **Critical CSS** generada automáticamente
- **Lazy loading** nativo y configurable
- **Integración con QUIC.cloud CDN** (gratis hasta cierto tráfico)

Es gratis. Si estuvieras pagando **WP Rocket** ($59/año) en otro hosting, LSCWP te da la mayor parte del valor sin costo adicional.

Configuración mínima que hice y funcionó bien:

1. Activé **Object Cache con memcached**
2. Habilité **WebP automático** para imágenes
3. Configuré **exclusiones de cache** en páginas con formularios
4. Conecté **QUIC.cloud CDN** para mejorar latencia en Sudamérica

Después de eso, no volví a tocar el plugin.

### Uptime en 4 meses

No tuve monitoreo continuo con servicio externo (no es un proyecto crítico para justificarlo), pero los dos sitios estuvieron **arriba todo el tiempo** salvo un breve evento que noté por casualidad y duró poco. Hostinger publica *status page* pública que vale la pena revisar si algo parece raro.

Cuatro meses no es suficiente para certificar uptime con rigor — eso requiere un año completo de medición. Pero sí alcanza para decir que no hubo caídas significativas que afecten la experiencia.

---

## Lo que me sorprendió para bien

### Staging con un click

Poder clonar el sitio completo a un entorno de staging aislado, trabajar ahí sin romper producción, y hacer *push* cuando estoy conforme. Lo usé varias veces para probar cambios de plugins y actualizaciones antes de aplicarlos.

Es el tipo de feature que en otros hostings es parte del plan "avanzado" y acá viene incluido. Si venís de trabajar directamente en producción rezando que no se rompa nada, este cambio de workflow solo justifica bastante el costo.

### Backups diarios con restore granular

El plan Business incluye **backups diarios automáticos** con retención y posibilidad de descarga local. Podés restaurar todo el sitio o elementos específicos.

Todavía no tuve que usar backups para recuperarme de un desastre, pero probé el proceso de restore en staging para verificar que funciona. Funciona.

Un recordatorio que vale fuera del hosting: estos backups cubren el sitio web, no el correo ni los archivos que viven en la nube. Esa parte queda descubierta y necesita [backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta) aparte, porque la retención del proveedor se mide en días, no en años.

### SSL que simplemente funciona

No toqué nada relacionado con certificados en 4 meses. Let's Encrypt se renueva solo, el HTTPS está activo por default con redirección desde HTTP, el wildcard cubre subdominios. Suena básico pero en hostings peores perdía tiempo reconfigurando esto cada 90 días.

### PHP actualizado y cambiable

Cambiar versión de PHP es un selector en hPanel. Lo probé en staging primero, verifiqué compatibilidad de plugins, después subí en producción. Cero downtime.

En muchos hostings competidores todavía corren PHP 8.0 por default y hay que pedir ticket para subir. En Hostinger es autoservicio.

### Protección contra ataques básica incluida

Hostinger tiene por default:

- **WAF básico** a nivel servidor
- **Rate limiting** contra fuerza bruta en `/wp-login.php`
- **IP blocking** automático
- **Malware scan** periódico

No reemplaza a Wordfence o a una capa de seguridad seria, pero es más de lo que ofrecen muchos competidores al mismo precio. Yo sumé **Wordfence** como segunda capa en los dos sitios.

Aparte: hPanel soporta segundo factor y conviene activarlo el primer día. El panel del hosting es uno de [los accesos que quedan afuera cuando se despliega MFA](/guia/ya-tenes-mfa-y-no-alcanza) — casi nunca está en la lista, y controla el sitio entero.

---

## Lo que es apenas "aceptable"

### Soporte técnico

El soporte es por **chat en vivo 24/7** dentro del panel. La primera respuesta llega en minutos — rápido.

Lo mixto:

- Para consultas **estándar** (DNS, SSL, email, backups, facturación) resuelve bien
- Para problemas **técnicos más profundos** (tuning específico, debugging de plugins, configuraciones avanzadas) el agente de primer nivel no tiene las herramientas ni el conocimiento
- La **escalación** a nivel técnico avanzado sucede por email con tiempos más largos

En 4 meses contacté soporte un par de veces. La primera consulta (sobre configuración DNS) la resolvieron bien. La segunda (algo más específico sobre configuración avanzada) terminé resolviéndolo yo buscando en foros y documentación.

Si sos técnico y podés diagnosticar vos, no es crítico. Si dependés 100% del soporte para cualquier problema, tené presente esta limitación.

### Email incluido

El email incluido en el plan funciona pero es **básico**. Para un sitio personal o blog chico es suficiente. Para cualquier proyecto serio, tiene más sentido mantener email corporativo en **Google Workspace** o **Microsoft 365** y usar Hostinger solo para hosting web.

Configurar MX records para desacoplar email del hosting web es simple desde hPanel — solo hay que saber que lo querés hacer. Y si estás tocando el DNS, aprovechá el mismo viaje para dejar bien [SPF, DKIM y DMARC](/guia/configurar-spf-dkim-dmarc-paso-a-paso): son los tres registros que evitan que te suplanten el dominio, y se configuran en el mismo panel.

### Latencia desde Sudamérica

Los datacenters de Hostinger están en **Estados Unidos, Europa, India, Singapur y Brasil (São Paulo)**. Para público LATAM, elegir São Paulo ayuda y es lo que uso.

La latencia desde Argentina hacia São Paulo es aceptable pero no óptima. Sumar **QUIC.cloud CDN** (gratis) o **Cloudflare** mejora notablemente la experiencia de visitantes locales. Es un paso que recomiendo hacer desde el día uno si tu público principal está en Sudamérica.

### hPanel en móvil

La versión móvil de hPanel funciona pero es menos cómoda que en escritorio. Para tareas rápidas va bien, para operaciones complejas conviene trabajar sentado.

---

## Lo que me frustró

### Upsells constantes

hPanel muestra **permanentemente** promociones y sugerencias de upgrade:

- Dominios adicionales
- Planes superiores
- Website builder propio
- Servicios de marketing
- Email premium
- Seguridad premium

No son intrusivos al punto de romper la usabilidad, pero son **frecuentes y visibles**. Si entrás a hPanel todos los días, vas a ver promos todos los días. Para algunos usuarios esto es indiferente; para otros se vuelve molesto. A mí me molesta un poco, no al punto de cambiar de hosting.

### La renovación al precio real (que todavía no viví)

Este es el punto **más importante** y el que hay que tener claro antes de contratar.

Hostinger se contrata con descuentos agresivos que aplican al **primer término contratado**. Al renovar, el precio vuelve al valor "normal" que es significativamente más alto — típicamente **2x a 3x** el precio inicial.

Esto **no es engaño** — está documentado en el checkout. Pero muchos usuarios no leen con atención y se sorprenden al momento de renovar.

Yo lo manejé contratando desde el inicio con el **término más largo posible** para aprovechar el precio bajo por más tiempo. Cuando se acerque la renovación voy a re-evaluar — probablemente renovar si la performance sigue siendo competitiva, o migrar si conviene.

**Importante**: estoy a 4 meses de la contratación, así que **no viví la renovación todavía**. Esta reseña se va a actualizar cuando ese momento llegue.

### Facturación fuera de Argentina

Hostinger factura desde el exterior, no emite factura argentina con CUIT. Para **profesionales independientes o empresas** que necesitan descargar el gasto como insumo con factura A o B, esto puede ser un *dealbreaker* operativo.

En mi caso no es bloqueante porque el rubro de uso lo permite, pero si tu contador es estricto con facturación local, tenelo presente antes de contratar. Si necesitás factura argentina, la alternativa es **DonWeb** u otros proveedores locales, sacrificando parte de la performance.

---

## Incidentes en 4 meses

Un incidente vale la pena mencionar:

### Plugin mal actualizado que generó carga anormal

En el **proyecto 2**, actualicé un plugin y empezó a hacer queries pesadas a la base de datos. El sitio quedó lento durante unas horas hasta que lo detecté.

Lo diagnostiqué revisando error logs desde hPanel (acceso directo, rápido), identifiqué el plugin problemático, lo volví a la versión anterior y todo se resolvió.

Hostinger no tenía que intervenir — es un problema de aplicación — pero ayudó que los logs fueran **accesibles en un click** sin tener que abrir tickets ni conectarse por SSH.

---

## Costo real hasta ahora

Sin dar números exactos porque dependen del término contratado y las promos del momento, puedo decir que:

- **Pagado mensual amortizado** — muy por debajo de lo que cuesta competencia como SiteGround, Kinsta o WP Engine
- **Lo que obtenés por ese precio** — LiteSpeed, NVMe, staging, backups diarios, SSL wildcard, WordPress auto-update, múltiples sitios
- **Otros costos adicionales en 4 meses** — cero (CDN gratis con QUIC.cloud, SSL gratis, email incluido aunque no lo uso)

Para el perfil de proyecto que tengo (dos sitios WordPress sin requisitos enterprise), **la relación costo-beneficio es difícil de igualar**.

La pregunta honesta es qué va a pasar en la renovación. Cuando llegue, voy a comparar precio post-renovación con SiteGround, Kinsta y un VPS administrado, y decidir si conviene quedarme o mover. Esa comparación todavía no la hice — cuando pase, esta reseña se actualiza.

---

## Comparación honesta con alternativas

### Dónde Hostinger gana claramente

- **Precio inicial** — sin competencia seria en shared/managed WordPress
- **Stack técnico** — LiteSpeed + NVMe + PHP moderno es mejor que Apache/SSD
- **hPanel** — sorprendentemente bueno
- **Inclusión en la lista oficial de hosts recomendados por WordPress.org**

### Dónde SiteGround sería mejor

- **Soporte técnico** — históricamente más profundo
- **Tuning avanzado** — más flexibilidad para casos complejos
- **Reputación consolidada** con equipos profesionales

### Dónde Kinsta o WP Engine serían mejores

- **Enterprise WordPress** con SLA estricto
- **Proyectos con dev team** que necesita Git deploy, SSH avanzado, staging múltiple
- **E-commerce de alto volumen** con latencia crítica

### Dónde DonWeb o hosting argentino sería mejor

- **Factura A o B** local con CUIT argentino
- **Soporte en español** con horario local
- **Integración con medios de pago locales**

---

## Qué haría distinto si empezara de nuevo

1. **Contratar directamente el plan Business** (saltando el Premium) — el salto a NVMe y backups diarios vale la diferencia de precio
2. **Usar el servicio de migración gratuita desde el día 1** — no intentar migrar solo primero para ahorrar tiempo
3. **Configurar QUIC.cloud CDN desde el principio** — no esperar a darse cuenta después que mejora notablemente la latencia en LATAM
4. **Mantener email en Google Workspace desde el inicio** — no probar siquiera el email incluido
5. **Instalar Wordfence desde el día 1** como redundancia de seguridad
6. **Contratar el término más largo posible** — el ahorro es sustancial y el riesgo bajo si el producto cumple

---

## Recomendación final por perfil de proyecto

**Blog personal / portfolio / sitio pequeño** — plan **Premium** de Hostinger. Es suficiente, y si crece se upgradea sin mover el sitio.

**Proyecto WordPress con múltiples sitios o componentes custom** (como los míos) — plan **Business**. Es el sweet spot claro del portfolio.

**WooCommerce con tráfico moderado** — plan **Cloud Startup** por recursos dedicados y más PHP workers.

**Sitio de alto tráfico o SaaS** — no es terreno de Hostinger shared. Evaluar Kinsta, WP Engine o un VPS administrado.

**E-commerce de alto volumen con SLAs estrictos** — Kinsta o WP Engine enterprise.

**Profesional argentino que necesita factura A por hosting** — DonWeb u otros proveedores locales.

---

## Conclusión provisoria (4 meses)

Después de 4 meses con dos proyectos en Hostinger, la experiencia es **mejor de lo que esperaba**. El stack técnico cumple lo prometido, hPanel es cómodo, los backups y el staging justifican Business sobre Premium, y el precio actual es difícil de batir.

Las dos objeciones legítimas que veo son el **salto de precio al renovar** (que mitigable contratando término largo) y el **soporte que se queda corto en problemas técnicos complejos** (mitigable si tenés capacidad propia de resolver).

Para proyectos WordPress que no sean enterprise ni de volúmenes enormes, **Hostinger es una recomendación sensata** en 2026. Es la elección que volvería a hacer mañana para los mismos dos proyectos.

Esta reseña es de 4 meses, no de un año. Cuando complete 12 meses, con renovación vivida y más incidentes resueltos, voy a actualizarla con lecciones adicionales. Hasta ahora, el producto cumple lo que ofrece.

---

*¿Estás evaluando Hostinger y tenés dudas puntuales sobre performance, migración desde otro hosting o limitaciones específicas? Escribinos a [contacto@capacero.online](mailto:contacto@capacero.online) y actualizamos la reseña con casos reales que vayamos viendo.*
