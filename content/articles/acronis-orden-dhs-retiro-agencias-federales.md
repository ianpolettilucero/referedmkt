---
title: "Acronis: el DHS ordenó sacar sus productos de las agencias civiles de Estados Unidos"
subtitle: La orden se firmó el 31 de julio y se hizo pública el 30 de octubre. No cita ningún CVE ni ningún incidente; el argumento es el acceso privilegiado de una herramienta de backup. Fuera de las agencias federales y de sus contratistas, no obliga a nadie.
excerpt: El DHS excluyó a Acronis de las compras federales y ordenó retirarlo de las agencias civiles en 180 días. Qué dice la orden, a quién obliga y qué hacer si lo usás.
type: news
section: mundo
status: published
category: backup-y-recuperacion
author: ian-poletti-lucero
published: 2026-11-02
updated: 2026-11-02
products:
  - acronis-cyber-protect
meta_title: "Orden del DHS contra Acronis: qué dice y a quién obliga"
meta_description: "El DHS excluyó a Acronis de las compras federales y ordenó retirarlo de las agencias civiles en 180 días. No cita CVE ni incidentes. Qué cambia si lo usás."
---

El **Departamento de Seguridad Nacional de Estados Unidos (DHS)** ordenó excluir a **Acronis** de las compras de las agencias civiles federales y **retirar sus productos de los sistemas de esas agencias en 180 días**. La orden se publicó en el [Federal Register del 30 de octubre](https://www.federalregister.gov/documents/2026/10/30/2026-22215/notice-of-order-regarding-exclusion-and-removal-of-acronis-products-from-federal-civilian-executive) (91 FR 68528, documento 2026-22215), pero su fecha de emisión es el **31 de julio de 2026**: se hizo pública tres meses después de firmada.

Es la **Orden de Exclusión y Retiro #2026-02**, firmada por el secretario de Seguridad Nacional, Markwayne Mullin, con copia al director de la Oficina de Gestión y Presupuesto, Russell Vought. Se dicta bajo la Ley de Seguridad de la Cadena de Suministro de Adquisiciones Federales de 2018 (FASCSA, por su sigla en inglés). Es la misma ley que el DHS usó para la [orden contra DeepSeek](https://www.federalregister.gov/documents/2026/10/20/2026-21447/notice-of-order-regarding-exclusion-and-removal-of-deepseek-products-from-federal-civilian-executive) publicada el 20 de octubre.

La orden **no menciona ninguna vulnerabilidad, ningún CVE ni ningún incidente**. Su argumento entra en una oración: las herramientas de Acronis tienen acceso privilegiado o de root a los sistemas de sus clientes. Eso no obliga a nadie fuera del gobierno federal de Estados Unidos y de sus contratistas. Pero el argumento vale para cualquier backup, y es lo que conviene llevarse de esta noticia.

## Qué dice la orden del DHS contra Acronis

La notificación del Federal Register ocupa una página; la orden firmada va como anexo escaneado, en tres. Tiene cinco partes que importan:

- **El riesgo.** Bajo el título *"Summary of Risks from Source"*, la orden describe a Acronis como una empresa extranjera, de capital privado, con subsidiarias en Estados Unidos, que ofrece protección de datos, backup, intercambio seguro de archivos y recuperación ante desastres, en local y en la nube. Y agrega: *"Acronis's cyber and backup tools have privileged or root access to customer security systems and present significant risk to the U.S. government information systems."*
- **La exclusión.** Las agencias deben sacar a Acronis de todas las compras en curso y futuras *"effective immediately after issuance"*. Eso incluye las listas de productos aprobados, las listas de equipos de proveedores de servicios y los mercados de comercio electrónico.
- **El retiro.** Deben quitar los productos de Acronis de todos sus sistemas de información *"within 180 calendar days of the issuance of the Order"*, o pedir una excepción.
- **Las excepciones.** Se piden por escrito dentro de los **60 días** de emitida la orden, con una justificación y las mitigaciones alternativas que la agencia propone. El DHS las aprueba o las rechaza por escrito.
- **Las medidas mientras tanto.** Hasta el retiro, el DHS alienta a las agencias a monitorear el tráfico que entra y sale, reforzar los registros y los controles contra la exfiltración de datos en los sistemas afectados, y detectar y marcar la actividad anómala.

Además, CISA puede dar asistencia técnica a las agencias que no puedan cumplir por su cuenta, y el secretario debe revisar la orden dentro de un año y después una vez por año.

La orden alcanza a toda la empresa, no a un producto. El resumen de la notificación nombra a *"Acronis AG and all affiliated entities"*; la información complementaria habla de productos de *"Acronis, Inc., Acronis SCS, and affiliated entities"*. Según la nota 2 de la orden, es afiliada cualquier persona o empresa que controle a Acronis AG o esté controlada por ella, directa o indirectamente, o que tenga con ella un controlante en común.

## Los plazos de la orden contra Acronis corren desde julio, no desde octubre

```svg
<svg viewBox="0 0 680 250" role="img" aria-label="Plazos de la orden 2026-02 del DHS contra Acronis, contados desde su emisión el 31 de julio de 2026. La exclusión de compras rige desde ese día, sin fecha de fin. El plazo para pedir una excepción, de 60 días, venció el 29 de septiembre. La orden se publicó en el Federal Register el 30 de octubre, 91 días después de emitida y 31 días después de cerrado el plazo de excepciones. El plazo para retirar los productos de Acronis, de 180 días, vence el 27 de enero de 2027, 86 días después del 2 de noviembre. Las dos fechas de vencimiento son cálculo propio a partir de la fecha de emisión que figura en la orden.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Orden del DHS contra Acronis: los plazos, desde el 31 de julio de 2026</text>
  <text x="20" y="43" class="ch-sub" font-size="11" fill="currentColor" opacity="0.8">Días desde la emisión de la orden. La línea vertical es la publicación en el Federal Register.</text>
  <path d="M230.0 60 V162" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="230" y="178" class="ch-tick" font-size="9.5" fill="currentColor" opacity="0.6">31 jul.</text>
  <path d="M347.0 60 V162" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="347" y="178" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">29 sep.</text>
  <path d="M407.4 60 V162" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="407.4" y="178" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">30 oct.</text>
  <path d="M581.0 60 V162" class="ch-grid" stroke="currentColor" stroke-opacity="0.15" fill="none"/>
  <text x="581" y="178" class="ch-tick" font-size="9.5" text-anchor="middle" fill="currentColor" opacity="0.6">27 ene.</text>
  <text x="220" y="79" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Exclusión de las compras</text>
  <path d="M230,66 H616.0 A4,4 0 0 1 620.0,70 V80 A4,4 0 0 1 616.0,84 H230 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Exclusión de las compras: rige desde el 31 de julio de 2026, sin fecha de fin</title></path>
  <text x="237" y="79" class="ch-inbar" font-size="9.5" font-weight="600" fill="currentColor">sin fecha de fin</text>
  <text x="220" y="113" class="ch-strong" font-size="11" text-anchor="end" font-weight="600" fill="currentColor">Pedido de excepciones</text>
  <path d="M230,100 H343.0 A4,4 0 0 1 347.0,104 V114 A4,4 0 0 1 343.0,118 H230 Z" class="ch-bar-hi ch-mark" fill="#e23a3a"><title>Pedido de excepciones: 60 días, hasta el 29 de septiembre de 2026</title></path>
  <text x="237" y="113" class="ch-inbar" font-size="9.5" font-weight="600" fill="currentColor">60 días</text>
  <text x="220" y="147" class="ch-label" font-size="11" text-anchor="end" fill="currentColor" opacity="0.8">Retiro de los productos</text>
  <path d="M230,134 H577.0 A4,4 0 0 1 581.0,138 V148 A4,4 0 0 1 577.0,152 H230 Z" class="ch-bar ch-mark" fill="currentColor" fill-opacity="0.45"><title>Retiro de los productos: 180 días, hasta el 27 de enero de 2027</title></path>
  <text x="237" y="147" class="ch-inbar" font-size="9.5" font-weight="600" fill="currentColor">180 días</text>
  <path d="M407.4 56 V166" class="ch-axis" stroke="currentColor" stroke-opacity="0.5" stroke-width="1.5" fill="none"/>
  <text x="412.4" y="54" class="ch-label" font-size="9.5" fill="currentColor" opacity="0.8">publicada</text>
  <text x="20" y="206" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Cuando la orden se hizo pública, el plazo para pedir excepciones llevaba 31 días cerrado.</text>
  <text x="20" y="226" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Vencimientos calculados desde la fecha de emisión de la orden (31 de julio de 2026).</text>
  <text x="20" y="242" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: Federal Register, 91 FR 68528, y orden 2026-02 del DHS.</text>
</svg>
```

Los plazos se cuentan desde la emisión. Contados desde el 31 de julio, los **60 días para pedir excepciones vencieron el 29 de septiembre**, un mes antes de que la orden apareciera en el Federal Register. Los **180 días para el retiro vencen el 27 de enero de 2027**: al 2 de noviembre quedan 86. Las dos fechas son cálculo propio; la orden da los plazos en días.

La orden está dirigida a las agencias, y es a ellas a quienes corre el reloj. Para todos los demás (contratistas, revendedores, clientes de Acronis), el registro público empieza el 30 de octubre.

## ¿Por qué el DHS sacó a Acronis si no hay un CVE?

Porque una orden FASCSA no necesita una falla. La ley de 2018 creó el **Consejo Federal de Seguridad de Adquisiciones** (FASC). Lo integran funcionarios de la Oficina de Gestión y Presupuesto, la Administración de Servicios Generales, el DHS, la Oficina del Director de Inteligencia Nacional y los departamentos de Justicia, Defensa y Comercio. Según la propia orden, el Consejo recomienda excluir productos que *"pose a significant risk to the federal enterprise"*.

La notificación dice que el secretario decidió *"following review of the Federal Acquisition Security Council Recommendation and affiliated evidence"*. **Ni la recomendación ni esa evidencia están publicadas.** Lo único que la orden pone por escrito sobre Acronis es lo citado arriba: es una empresa extranjera y sus herramientas tienen acceso privilegiado.

Es un argumento de arquitectura, no de incidente. Un agente de backup y protección corre con el máximo privilegio en cada equipo que protege, porque necesita leer todo y poder restaurar todo. Quien controle esa herramienta (el fabricante, alguien que entre a su nube o alguien que robe la consola de administración) puede hacer lo mismo que ella en todos esos equipos.

Quien lea "riesgo" como "Acronis fue hackeado" lee algo que el texto no dice. En septiembre, el [plugin de Acronis Backup para cPanel tuvo una falla explotada](/noticia/acronis-backup-cpanel-plesk-cve-2026-87886), pero la orden no la menciona y nada en el texto la vincula. Al 2 de noviembre, la sala de prensa de Acronis no tenía ningún comunicado sobre la orden.

## ¿A quién obliga la orden contra Acronis?

```svg
<svg viewBox="0 0 680 262" role="img" aria-label="A quién obliga la orden 2026-02 del DHS contra Acronis. Obligados: las agencias civiles del Poder Ejecutivo federal de Estados Unidos, salvo sus sistemas de seguridad nacional y de información compartimentada, y, según la nota 4 de la orden, los sistemas que un contratista opera por cuenta de una de esas agencias. No obligados: el resto de las organizaciones, en Estados Unidos o en América Latina, aunque usen Acronis directamente o a través de su hosting o de su proveedor de servicios gestionados. Lo que sí sirve para cualquiera es el argumento de la orden: una herramienta de backup con acceso privilegiado o de root concentra riesgo.">
  <text x="20" y="24" class="ch-title" font-size="13" font-weight="650" fill="currentColor">Orden del DHS contra Acronis: a quién obliga y a quién no</text>
  <rect x="20" y="40" width="640" height="78" rx="6" class="ch-box-hi" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="36" y="62" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">Obligados</text>
  <text x="36" y="82" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Agencias civiles del Poder Ejecutivo federal de Estados Unidos, salvo sus sistemas de seguridad nacional.</text>
  <text x="36" y="100" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">Sistemas que un contratista opera por cuenta de una de esas agencias (nota 4 de la orden).</text>
  <rect x="20" y="130" width="640" height="78" rx="6" class="ch-box" fill="none" stroke="currentColor" stroke-opacity="0.4"/>
  <text x="36" y="152" class="ch-strong" font-size="11.5" font-weight="650" fill="currentColor">No obligados</text>
  <text x="36" y="172" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">El resto de las organizaciones, en Estados Unidos o en la región, aunque usen Acronis</text>
  <text x="36" y="190" class="ch-label" font-size="10.5" fill="currentColor" opacity="0.8">directamente o a través de su hosting o de su proveedor de servicios gestionados.</text>
  <text x="20" y="234" class="ch-strong" font-size="11" font-weight="600" fill="currentColor">Para todos vale el argumento: cualquier backup con acceso root concentra riesgo, no solo Acronis.</text>
  <text x="20" y="252" class="ch-note" font-size="9.5" fill="currentColor" opacity="0.65">Fuente: orden 2026-02 del DHS, alcance y nota al pie 4.</text>
</svg>
```

A dos grupos. Primero, a las **agencias civiles del Poder Ejecutivo federal** de Estados Unidos, salvo sus sistemas de seguridad nacional y de información compartimentada, que dependen de Defensa y de Inteligencia. Segundo, por la nota 4 de la orden, a los sistemas operados *"by a contractor of an agency or other organization on behalf of an agency"*.

A nadie más. Una PyME de México, Colombia o Argentina que usa Acronis, directamente o a través de su hosting o de su proveedor de servicios gestionados, no tiene ninguna obligación nueva. La orden regula lo que compran y usan las agencias; no prohíbe nada a las empresas privadas.

## Si le vendés al gobierno de Estados Unidos: la cláusula FAR 52.204-30

El caso en el que sí pesa es el de una empresa de la región que trabaja para una agencia federal civil, de forma directa o como subcontratista. La nota 5 de la orden remite a la [cláusula FAR 52.204-30](https://www.acquisition.gov/far/52.204-30), que prohíbe al contratista *"provide or use as part of the performance of the contract"* productos de una fuente alcanzada por una orden FASCSA, salvo que tenga una excepción. La cláusula le pide:

- revisar **SAM.gov** al menos una vez cada tres meses, buscando la frase "FASCSA order";
- si aparece una orden nueva que pueda tocar su cadena de suministro, hacer una indagación razonable con la información que ya tiene, sin necesidad de auditoría;
- si encuentra el producto en el contrato, avisar al oficial de contrataciones dentro de **3 días hábiles**, y completar la información sobre mitigación dentro de los **10 días hábiles** siguientes.

La cláusula se traslada a todos los subcontratos, incluidos los de productos y servicios comerciales; lo único que no se traslada es la revisión trimestral de SAM.gov. Una orden emitida después de la convocatoria se aplica a un contrato en curso cuando se incorpora por enmienda o modificación, pero el deber de indagar y reportar corre igual.

## ¿Cómo sé si uso Acronis?

Hay tres lugares donde puede aparecer sin que nadie de la empresa lo haya elegido:

1. **El hosting.** Acronis tiene un plugin de backup para cPanel & WHM y una extensión para Plesk. Si el panel de tu hosting ofrece copias "Acronis", las estás usando.
2. **El proveedor de IT.** [Acronis Cyber Protect](/producto/acronis-cyber-protect) se vende con multi-tenencia para proveedores de servicios gestionados. Si tu proveedor administra el backup de tus equipos, preguntale con qué herramienta.
3. **Los equipos.** En Windows, en PowerShell, `Get-Service -DisplayName '*Acronis*'` lista los servicios de Acronis instalados. En Linux, `systemctl list-unit-files | grep -i acronis` hace lo mismo.

## Qué hacer si usás Acronis, o cualquier backup con acceso root

1. **No lo desinstales apurado.** Sacar el backup sin tener otro funcionando te deja sin copias. Si decidís migrar, que la herramienta nueva haga copias y pase una prueba de restauración antes de apagar la vieja. Conservá la forma de restaurar las copias viejas hasta que venza su retención.
2. **Hacé el inventario de agentes con privilegios.** Backup, administración remota, EDR, escritorio remoto: todo lo que corre como SYSTEM o root. En Windows, este comando lista los servicios de terceros que corren con la cuenta de máximo privilegio: `Get-CimInstance Win32_Service | Where-Object { $_.StartName -eq 'LocalSystem' -and $_.PathName -notmatch 'C:\\Windows' } | Select-Object DisplayName, PathName`. Cada línea es una herramienta con la misma llave que la orden describe. Que un atacante use esa llave ya pasó este año: CISA sumó a su catálogo de explotación activa fallas de [N-able N-central](/noticia/n-able-n-central-cve-2026-86218-proveedor-it), de [ScreenConnect](/noticia/screenconnect-cve-2026-84869-infecta-al-tecnico) y del servidor de backup [Ahsay](/noticia/ahsay-ahsaycbs-dos-explotadas-servidor-de-backup).
3. **Aplicá a la consola del backup las medidas que el DHS pide a las agencias.** Sirven para cualquier herramienta: saber a qué destinos hablan normalmente los agentes y la consola, y mirar lo que salga de ahí; guardar los registros de la consola fuera de la consola; y alertar sobre lo que haría alguien que la tomó: borrar copias, acortar la retención, crear un administrador, desinstalar agentes o ejecutar comandos en los equipos.
4. **Cerrá la consola.** Pocas cuentas de administración, separadas de las del día a día, y [autenticación en dos pasos que no alcance con robar una sesión](/guia/ya-tenes-mfa-y-no-alcanza).
5. **Tené una copia que esa consola no pueda borrar.** Una copia inmutable o desconectada, administrada con otra credencial. Si la consola cae en malas manos, o si un día tenés que dejar la herramienta, esa es la copia que queda. Las opciones están en [la comparativa de backup y recuperación](/productos/backup-y-recuperacion).
6. **Si trabajás para un contrato federal de Estados Unidos, hablá ya con el contratista principal.** La pregunta es concreta: si la orden contra Acronis ya está incorporada al contrato y qué plazo te dan.

Un EDR que corre en los mismos equipos suma su propio agente privilegiado. Cuándo se justifica tenerlo está en [la guía sobre EDR o antivirus para PyMEs](/guia/edr-o-antivirus-cuando-se-justifica-pyme).

**Fuentes leídas:** la notificación 2026-22215 del Federal Register, expediente DHS-2026-0694, y la orden 2026-02 escaneada en su anexo, también disponible en el [PDF de govinfo](https://www.govinfo.gov/content/pkg/FR-2026-10-30/pdf/2026-22215.pdf); la cláusula FAR 52.204-30 en acquisition.gov; la notificación de la orden contra DeepSeek, 91 FR 66346; y la sala de prensa de Acronis. **Sin confirmar:** el contenido de la recomendación del Consejo y de la evidencia que la acompaña, que no son públicos, y si la orden ya figura en SAM.gov.

## Preguntas frecuentes sobre la orden del DHS contra Acronis

### ¿Está prohibido usar Acronis?

No. La orden obliga a las agencias civiles federales de Estados Unidos y a los sistemas que contratistas operan para ellas. Para una empresa privada, en Estados Unidos o en América Latina, no cambia nada legalmente.

### ¿Acronis sufrió un hackeo o una vulnerabilidad que motivara la orden?

La orden no menciona ninguno. Cita como riesgo que sus herramientas tienen acceso privilegiado o de root a los sistemas de los clientes, y que es una empresa extranjera. La recomendación del Consejo Federal de Seguridad de Adquisiciones que la sustenta no es pública.

### ¿Cuándo tienen que sacar Acronis las agencias de Estados Unidos?

Dentro de los 180 días corridos desde la emisión, el 31 de julio de 2026; por cálculo propio, el 27 de enero de 2027. Las excepciones se pedían dentro de los 60 días, hasta el 29 de septiembre.

### ¿Tengo que cambiar de backup si uso Acronis?

No por la orden. Si decidís cambiar, hacelo con superposición: herramienta nueva funcionando y una restauración probada antes de apagar la vieja. Mientras tanto, protegé la consola y tené una copia que esa consola no pueda borrar.

### ¿Me afecta si trabajo para un contratista del gobierno federal?

Puede. La cláusula FAR 52.204-30 se traslada a los subcontratos y prohíbe usar productos alcanzados por una orden FASCSA en la ejecución del contrato. Si aparece uno, hay 3 días hábiles para reportarlo.
