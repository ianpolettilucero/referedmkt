---
title: "Seguridad de WordPress en una PyME: qué cubre el hosting y qué no"
subtitle: Patchstack midió cuánto de lo que se explota en la práctica frenan las defensas del proveedor. El número es 12%. Esta guía es el mapa del 88% restante, ordenado por lo que rinde primero.
excerpt: Las defensas del hosting bloquearon el 12% de los ataques a vulnerabilidades específicas de WordPress. Qué cubre tu proveedor, qué no puede cubrir por diseño y en qué orden atacar lo que queda.
type: guide
status: published
category: hostings-y-cloud
author: ian-poletti-lucero
published: 2026-07-26
updated: 2026-07-26
products:
  - hostinger-business
  - acronis-cyber-protect
  - bitwarden-business
meta_title: "Seguridad WordPress PyME: qué cubre el hosting y qué no"
meta_description: "Patchstack midió que el hosting frena el 12% de los ataques a vulnerabilidades de WordPress. El mapa del 88% restante, ordenado por lo que rinde primero."
---

> **Divulgación, arriba y no en un pie**: Capa Cero se financia con enlaces de afiliado. Hostinger es hoy el único programa en el que el sitio está dado de alta, y aparece en esta guía. Lo que sigue argumenta que **ningún** hosting —tampoco ese— resuelve el grueso del problema, que es exactamente lo contrario de lo que conviene comercialmente.

Contratar "hosting seguro" y dar el tema por cerrado es la decisión más común y la peor documentada del mercado. Hay un número que la desarma.

Patchstack, junto a la firma de inteligencia de malware Monarx, midió qué proporción de los ataques reales contra vulnerabilidades de WordPress frenaban las defensas tradicionales del alojamiento. El resultado publicado en el [State of WordPress Security in 2026](https://patchstack.com/whitepaper/state-of-wordpress-security-in-2026/): **el 12%**. Ampliando la medición a un conjunto más grande de ataques, 26%.

Doce por ciento no es poco porque el proveedor haga mal su trabajo. Es poco porque **la capa donde ocurre el problema no es la capa que el proveedor administra**. Esta guía es el reparto de responsabilidades: qué cubre el hosting de verdad, qué no puede cubrir aunque quiera, y las seis cosas que quedan de tu lado, en orden de lo que rinde primero.

```svg
<svg viewBox="0 0 640 190" role="img" aria-label="Barra que muestra que las defensas del hosting frenaron el 12% de los ataques a vulnerabilidades de WordPress y el 88% restante depende del dueño del sitio">
  <text x="20" y="28" font-size="14" font-weight="600" fill="currentColor">Ataques a vulnerabilidades de WordPress</text>

  <rect x="20" y="48" width="72" height="46" rx="4" fill="#e23a3a"/>
  <rect x="92" y="48" width="528" height="46" rx="4" fill="currentColor" opacity="0.14"/>
  <rect x="20" y="48" width="600" height="46" rx="4" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.5"/>

  <text x="56" y="77" text-anchor="middle" font-size="15" font-weight="700" fill="#ffffff">12%</text>
  <text x="356" y="77" text-anchor="middle" font-size="15" font-weight="700" fill="currentColor">88%</text>

  <line x1="56" y1="100" x2="56" y2="118" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="20" y="136" font-size="12" font-weight="600" fill="#e23a3a">lo frena el hosting</text>
  <text x="20" y="152" font-size="11" fill="currentColor" opacity="0.7">WAF, DDoS y límite de tasa</text>

  <line x1="356" y1="100" x2="356" y2="118" stroke="currentColor" stroke-width="1.5" opacity="0.6"/>
  <text x="356" y="136" text-anchor="middle" font-size="12" font-weight="600" fill="currentColor">te toca a vos</text>
  <text x="356" y="152" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.7">qué plugins instalaste, quién tiene acceso,</text>
  <text x="356" y="166" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.7">y dónde están tus copias</text>
</svg>
```

---

## El dato que ordena todas las decisiones

El informe de Patchstack contabilizó **11.334 vulnerabilidades nuevas en el ecosistema WordPress durante 2025**, un 42% más que las cerca de 7.985 del año anterior. La distribución es lo interesante:

| Dónde apareció | Proporción | Lectura |
|---|---|---|
| Plugins | 91% | El problema real |
| Temas | 9% | Secundario, pero no cero |
| Núcleo de WordPress | 6 vulnerabilidades en todo el año, todas de prioridad baja | Estadísticamente irrelevante |

Seis. En un año. Sobre un núcleo que corre en decenas de millones de sitios.

Eso reordena la conversación entera: **WordPress no es inseguro, el mercado de plugins lo es**. Cada plugin que instalás es código de terceros ejecutándose con los permisos de tu sitio, mantenido por alguien que no conocés, con un ciclo de parches que no controlás.

Los otros tres números del informe que cambian cómo se opera un sitio:

- **1.966 vulnerabilidades (17%) fueron de severidad alta.** El informe señala que se descubrieron más vulnerabilidades de severidad alta en 2025 que en los dos años anteriores sumados.
- **El 46% no tenía parche disponible al momento de hacerse pública.** Casi la mitad se divulga mientras el plugin sigue roto. Actualizar rápido no alcanza si todavía no existe qué instalar.
- **La mediana ponderada hasta el primer exploit es de 5 horas** en los casos más atacados. Alrededor del 50% de las vulnerabilidades de alto impacto se explotan dentro de las primeras 24 horas.

Cinco horas es menos de lo que dura una jornada laboral. Cualquier proceso que dependa de que una persona lea un aviso, evalúe y programe una ventana de mantenimiento llega tarde por definición.

Un matiz que casi nadie menciona y que contradice la intuición: el informe encontró **1.983 vulnerabilidades en componentes premium o freemium**, de las cuales el 59% eran de alto riesgo, y asocia a los plugins y temas pagos con **el triple de vulnerabilidades explotadas conocidas (KEV) que las alternativas gratuitas**. Pagar por un plugin compra soporte y funciones. No compra seguridad.

---

## Qué cubre de verdad el hosting

Nada de lo anterior significa que el proveedor sea decorativo. Hay una franja que cubre bien y que además cubre mejor que vos, porque opera a una escala que ninguna PyME alcanza.

**Lo que un buen plan gestionado resuelve de verdad:**

- **La capa de infraestructura.** Kernel, versión de PHP, parches del servidor web, aislamiento entre cuentas. Si esto se rompe, se rompe para el proveedor, así que tiene el incentivo alineado.
- **Volumetría.** Mitigación de DDoS y límites de tasa contra fuerza bruta en `/wp-login.php`. Filtrar tráfico basura antes de que llegue a PHP es algo que solo se hace bien con visibilidad de red.
- **TLS operativo.** Certificado emitido, renovado y desplegado sin intervención. El certificado vencido sigue siendo una de las causas más frecuentes de caída evitable.
- **Copias periódicas de la cuenta.** Con la salvedad importante de la sección siguiente.

En el segmento de PyME esto viene incluido en los planes intermedios de los proveedores serios. [Hostinger Business](/producto/hostinger-business), por ejemplo, trae WAF a nivel servidor, escaneo de malware, límite de tasa contra fuerza bruta, copias diarias con restauración granular y entorno de staging. WordPress.org lo lista hoy entre sus [tres hosts recomendados](https://wordpress.org/hosting/), junto a Pressable y Bluehost. Si querés el detalle de cómo se comporta ese plan en uso sostenido, está en la [reseña de Hostinger para WordPress](/resena/resena-hostinger-wordpress-4-meses).

Elegir un proveedor con ese piso es la decisión correcta. Lo que no es correcto es creer que ahí termina.

---

## Qué no puede cubrir, aunque quiera

El 88% restante no se le escapa al proveedor por negligencia. Se le escapa por dónde está parado.

**Un ataque a un plugin vulnerable no parece un ataque.** Es una petición HTTP bien formada, a una ruta legítima de tu sitio, con parámetros que el propio plugin declara aceptar. Desde el borde de la red es indistinguible de un usuario usando la función. El WAF tendría que conocer la lógica interna de ese plugin específico, en esa versión específica, para saber que ese valor en ese campo termina en una escalada de privilegios.

Los tipos de vulnerabilidad más atacados del informe explican por qué:

| Tipo | Proporción de lo explotado | Por qué el borde no lo ve |
|---|---|---|
| Control de acceso roto | 57% | La petición es válida; lo que falla es la comprobación de permisos dentro del plugin |
| Escalada de privilegios | 20% | Igual: el problema es la lógica, no la forma |
| Inclusión de archivos locales | 10% | Detectable por patrón, pero con muchos falsos positivos |
| Inyección SQL | 5% | Es lo que un WAF sí filtra razonablemente bien |

**Más de tres cuartas partes de lo que se explota es lógica de autorización rota.** Ninguna regla genérica en el borde arregla eso. El único punto donde se arregla es el plugin —parcheándolo o sacándolo—, y esa decisión es tuya.

Hay tres cosas más que estructuralmente no son del proveedor:

- **Quién tiene cuenta de administrador y con qué contraseña.** El hosting no sabe si esa credencial se reutiliza en otros diez servicios.
- **Qué plugins instalaste y cuáles siguen mantenidos.** Un plugin abandonado no genera error; genera silencio.
- **Si tus copias sobreviven a un compromiso.** Una copia que se toma automáticamente de un sitio ya infectado guarda el sitio ya infectado, prolijamente, todos los días.

---

## Las seis cosas que te quedan, en orden

Ordenadas por lo que rinde primero por unidad de esfuerzo, no por lo que suena más serio.

### 1. Reducir la superficie: desinstalar, no desactivar

Es gratis, lleva veinte minutos y ataca directo al 91%.

Un plugin desactivado **sigue en el disco** y su código sigue siendo alcanzable por el servidor web. Varias vulnerabilidades explotadas en masa funcionan contra archivos de plugins desactivados, porque el archivo vulnerable se invoca directo por URL sin pasar por el arranque de WordPress.

Entrá al listado, y para cada plugin respondé dos preguntas: ¿lo usa alguien esta semana? ¿tuvo una actualización en los últimos doce meses? Si la primera es "no", desinstalalo. Si la segunda es "no", buscá reemplazo con urgencia: un plugin sin mantenimiento es una vulnerabilidad futura con fecha abierta.

Es el único paso de la lista sin contraargumento: todo lo demás implica una compensación, sacar código que no usás no.

### 2. Actualizaciones automáticas, con red de contención

Con una mediana de cinco horas hasta el primer exploit, la ventana de decisión humana no existe. Activá actualizaciones automáticas de núcleo, plugins y temas.

La objeción legítima es que una actualización automática puede romper el sitio. Se responde con dos cosas, no discutiendo el riesgo:

- **Entorno de staging.** Los planes intermedios de la mayoría de los proveedores lo incluyen con un clic. Para los plugins críticos del negocio —pasarela de pago, formularios de facturación— actualizá primero ahí.
- **Copia inmediatamente anterior recuperable.** Si el 46% de las vulnerabilidades se publica sin parche, vas a tener que convivir con ventanas sin arreglo disponible. Poder volver atrás en minutos vale más que poder actualizar rápido.

Aceptá el riesgo de que algo se rompa. Un sitio caído dos horas se arregla; un sitio comprometido tres semanas, no.

### 3. MFA en todas las cuentas de administrador

La contraseña sola dejó de ser un control hace años. Si todavía no tenés segundo factor en el panel de WordPress ni en el panel del hosting, ese es el agujero más grande que te queda después de los plugins.

El procedimiento completo, con el orden de despliegue para no dejar gente afuera, está en la [guía para configurar MFA en una PyME en un fin de semana](/guia/configurar-mfa-pyme-fin-de-semana). Y si ya tenés MFA activado, vale la pena leer [por qué tener MFA no alcanza](/guia/ya-tenes-mfa-y-no-alcanza): no todos los segundos factores resisten lo mismo, y los SMS resisten bastante poco.

Para las identidades con privilegio elevado —el administrador del hosting, el que puede tocar DNS— hay un escalón más, que son las llaves físicas. El cálculo de si se justifica está en el [análisis de costo total de las YubiKey 5 Series en Argentina](/resena/analisis-yubikey-5-series-costo-total-argentina), con la conclusión de que para buena parte de las PyMEs no cierra.

### 4. Credenciales distintas por servicio

El panel del hosting, el WordPress, la base de datos, el FTP y el correo del dominio son cinco credenciales. Si comparten contraseña, comprometer la más débil compromete las cinco.

Esto se resuelve con un gestor, no con disciplina. Las opciones que cubrimos son [1Password Business](/producto/1password-business), [Bitwarden Business](/producto/bitwarden-business) y [Dashlane Business](/producto/dashlane-business); la experiencia de uso sostenido de la primera está en la [reseña de 1Password Business](/resena/resena-1password-business-12-meses).

### 5. Copias fuera del proveedor

Acá está el error más caro y el más frecuente.

Las copias diarias que hace tu hosting viven **en la infraestructura del hosting** y se toman del estado actual del sitio. Eso cubre bien dos escenarios: te equivocaste vos, o se rompió el servidor. No cubre el escenario que importa: el sitio lleva tres semanas con una puerta trasera y las últimas veintiún copias la contienen.

La regla operativa es simple: **al menos una copia que no pueda ser borrada ni modificada desde las credenciales del sitio**, y retención suficiente para volver antes de la fecha de compromiso. Para sitios chicos, una descarga semanal a almacenamiento externo alcanza. Para operaciones más grandes, [Acronis Cyber Protect](/producto/acronis-cyber-protect) y [Veeam Data Platform](/producto/veeam-data-platform) son las herramientas del segmento, y en la [categoría de backup y recuperación](/productos/backup-y-recuperacion) está el resto del catálogo.

El mismo razonamiento aplica al correo, y ahí la confusión es todavía mayor: la guía sobre [si hace falta backup de Microsoft 365](/guia/backup-microsoft-365-hace-falta) desarma la idea de que el proveedor ya se encarga.

### 6. Un usuario, una cuenta, el permiso mínimo

La cuenta compartida de administrador que usan cuatro personas no es un atajo, es la eliminación deliberada de la trazabilidad. Cuando algo pase —y va a pasar— no vas a poder decir desde qué sesión entró.

Cuentas nominales, rol de editor para quien solo publica, y baja inmediata cuando alguien deja el equipo o termina un trabajo. Es aburrido y es lo que más se descuida.

---

## Qué hacer si sospechás que ya te comprometieron

Restaurar la última copia y seguir es la reacción natural y suele ser un error, porque si la puerta trasera entró antes de esa copia, la restauraste junto con todo lo demás.

El orden que tiene sentido:

1. **No apagues nada todavía.** Poné el sitio en mantenimiento o cortá el tráfico público, pero conservá los registros.
2. **Bajá los registros de acceso antes de tocar nada.** La mayoría de los proveedores los rota en pocos días. Lo que necesitás es la petición que entró primero, y ese dato tiene fecha de vencimiento.
3. **Fijá la fecha probable de compromiso** buscando en los registros la primera petición anómala a un archivo de plugin. Recién ahí sabés a qué copia volver.
4. **Rotá todas las credenciales**: WordPress, hosting, base de datos, FTP, y las claves de API que el sitio tuviera guardadas. Asumí que todo lo que estaba en el servidor se leyó.
5. **Restaurá a una copia anterior a esa fecha** y actualizá antes de volver a publicar.

Si el sitio guarda datos de clientes, además hay obligaciones de notificación que dependen del país donde operás y que conviene tener resueltas antes del incidente, no durante.

---

## Cuándo esta guía no aplica

Tres casos donde el consejo de arriba es el equivocado:

- **Si tu sitio es una landing estática sin base de datos ni formularios**, casi nada de esto aplica. No hay plugins, no hay sesiones y la superficie de ataque es el certificado y poco más. Meterle un plugin de seguridad a un sitio así agrega riesgo en vez de sacarlo.
- **Si tenés un e-commerce con volumen de transacciones serio**, esta guía es el piso, no el techo. Ahí entran requisitos de cumplimiento de medios de pago, segmentación y monitoreo continuo que no se resuelven con hosting compartido de ningún proveedor.
- **Si administrás sitios de terceros como agencia**, el modelo cambia: necesitás inventario centralizado, actualizaciones coordinadas y separación de credenciales por cliente. Las herramientas de gestión de flota resuelven eso; hacerlo a mano en veinte sitios no escala.

Y una recomendación que va contra la costumbre del rubro: **no instales un plugin de seguridad "todo en uno" como primer paso**. Son plugins, con la misma superficie de ataque que cualquier otro, y suelen duplicar controles que el proveedor ya aplica mejor en el borde. Primero desinstalá lo que no usás, activá actualizaciones y poné MFA. Si después de eso todavía hace falta algo, ahí se evalúa.

---

## Un orden de trabajo para el primer día

Si tenés una tarde y querés el mayor movimiento por hora invertida:

| Tiempo | Tarea | Qué cubre |
|---|---|---|
| 20 min | Desinstalar plugins sin uso o sin mantenimiento | El 91% de las vulnerabilidades |
| 15 min | Activar actualizaciones automáticas | La ventana de 5 horas |
| 30 min | MFA en WordPress y en el panel del hosting | Credenciales robadas o reutilizadas |
| 30 min | Contraseñas distintas por servicio en un gestor | Movimiento lateral entre servicios |
| 45 min | Primera copia descargada fuera del proveedor | El escenario que las copias del host no cubren |
| 20 min | Auditar usuarios y bajar los que sobran | Trazabilidad |

Menos de tres horas. Ninguna de esas seis tareas requiere comprar nada más allá del gestor de contraseñas, y ninguna depende de qué proveedor tengas contratado.

Si estás armando el programa de seguridad completo y no solo el sitio, la [guía de ciberseguridad para PyMEs de LATAM](/guia/guia-ciberseguridad-pymes-latam-2026) ubica esto dentro del resto, y el marco para justificar la inversión ante quien firma está en la [nota sobre ROI y roles en ciberseguridad](/guia/ciberseguridad-importancia-roi-roles-certificaciones).

---

## Preguntas frecuentes

### ¿Es cierto que WordPress es inseguro?

No en el sentido que suele decirse. El informe de Patchstack contabilizó **seis vulnerabilidades en el núcleo de WordPress en todo 2025**, todas de prioridad baja, contra 11.334 en el ecosistema completo. El 91% estuvo en plugins. Lo que es inseguro es el mercado de extensiones de terceros, no la plataforma.

### Si mi hosting incluye WAF y escaneo de malware, ¿no alcanza?

Es una base necesaria y no alcanza. Patchstack midió que las defensas tradicionales frenaron el 12% de los ataques a vulnerabilidades específicas de WordPress, y 26% en la medición ampliada. La razón es estructural: más del 75% de lo que se explota es lógica de autorización rota dentro de un plugin, y eso desde el borde de la red se ve como una petición legítima.

### ¿Los plugins pagos son más seguros que los gratuitos?

Los datos del informe apuntan en la dirección contraria. Contabilizó 1.983 vulnerabilidades en componentes premium o freemium, con un 59% de alto riesgo, y asocia a los componentes pagos con el triple de vulnerabilidades explotadas conocidas que las alternativas gratuitas. Pagar compra soporte y funciones; el mantenimiento activo hay que verificarlo aparte, mirando la fecha de la última actualización.

### ¿Las copias que hace mi hosting me sirven contra ransomware o un sitio comprometido?

Sirven contra el error propio y contra la falla de hardware. Contra un compromiso sirven solo si tenés retención suficiente para volver a antes de la fecha en que entraron, y si al menos una copia está fuera del alcance de las credenciales del sitio. Una copia diaria de un sitio infectado guarda el sitio infectado.

### ¿Conviene activar las actualizaciones automáticas aunque puedan romper algo?

Sí. La compensación se administra con staging y con una copia recuperable, no evitando actualizar. Con una mediana de cinco horas hasta el primer exploit, cualquier proceso que dependa de revisión humana llega tarde. Un sitio roto se restaura; uno comprometido durante semanas, no del todo.

### ¿Cambiar de hosting mejora mi seguridad?

Mejora el 12% —la franja que el proveedor efectivamente cubre— si venías de uno que no ofrecía WAF, límite de tasa ni copias. No mueve el 88% restante, que depende de qué plugins tenés instalados, quién tiene acceso y dónde están tus copias. Migrar es una buena decisión por rendimiento y por operación; como estrategia de seguridad, es la parte chica.
