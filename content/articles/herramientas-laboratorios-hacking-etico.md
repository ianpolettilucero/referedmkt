---
title: "Herramientas de Pentesting: Las Principales y Qué Hace Cada Una"
subtitle: "Panorama de las herramientas más usadas en seguridad ofensiva profesional: Kali Linux, Burp Suite, Metasploit, nmap, Cobalt Strike y las demás. Qué hace cada una, por qué se consolidaron como estándar, y dónde encajan en el trabajo de un pentester."
excerpt: El trabajo de un pentester se apoya en un conjunto específico de herramientas que se convirtieron en estándar de la industria a lo largo de los últimos 20 años. Este artículo explica las principales, qué hace cada una, por qué se consolidaron como referencia, y cómo se combinan en las distintas fases de un pentest. Para empresas y profesionales que quieren entender qué herramientas aparecen cuando se habla de pentesting profesional.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-04-22 03:25:23
updated: 2026-07-26
meta_title: "Herramientas de Pentesting: Las Principales y Qué Hace Cada Una"
meta_description: "Panorama de las herramientas más usadas en pentesting profesional: Kali Linux, Burp Suite, Metasploit, nmap, BloodHound, Cobalt Strike y las demás. Qué hace cada una y cómo se combinan."
---

Si alguna vez viste a un pentester trabajar, probablemente notaste que rota entre las mismas cuatro o cinco herramientas durante todo el día. No es porque no existan alternativas — existen cientos. Es porque en cada categoría de trabajo ofensivo hay una o dos herramientas que se consolidaron como estándar de la industria durante los últimos 20 años, y el costo de cambiarlas por alternativas rara vez compensa.

Este artículo es un panorama de esas herramientas principales. Qué hace cada una, por qué se volvió estándar, y dónde encaja en el flujo de trabajo de un pentest. No es un manual de uso ni un listado exhaustivo — hay repositorios completos en GitHub con catálogos de 300 herramientas, y son útiles cuando necesitás algo específico. Este artículo cubre las que aparecen en prácticamente cualquier engagement profesional y cuya mención es parte del vocabulario básico del rubro.

Para empresas, entender este ecosistema ayuda a leer mejor las conversaciones técnicas con proveedores. Para cualquier lector curioso sobre cómo funciona el pentesting moderno, es el mapa inicial.

> **TL;DR** — Los pentesters trabajan sobre **Kali Linux** como sistema operativo. Usan **nmap** para reconocimiento de red, **Burp Suite** para aplicaciones web, **Metasploit** para explotación, **BloodHound** para Active Directory, y **Cobalt Strike** o alternativas open source para red teaming. Las herramientas específicas cambian según especialización, pero este núcleo aparece en casi todos los engagements. Lo importante no son las herramientas en sí sino cómo se combinan — una herramienta sin profesional capacitado es solo un programa; un profesional sin herramientas es solo teoría.

---

## El sistema operativo como punto de partida

Antes de las herramientas individuales, hay una decisión estructural: **desde qué sistema operativo trabaja el pentester**. La respuesta industry-standard es una distribución Linux especializada que viene con cientos de herramientas preinstaladas y configuradas para uso ofensivo.

### Kali Linux

[Kali Linux](https://www.kali.org/) es la distribución de referencia absoluta en pentesting. Mantenida por [Offensive Security](https://www.offsec.com/), es una distribución basada en Debian con más de 600 herramientas de seguridad preinstaladas, configuraciones específicas para uso ofensivo, y un ecosistema maduro de documentación, comunidad y soporte.

Lo que la hizo estándar no es técnico — son tres factores combinados: ha sido mantenida activamente durante más de 15 años (evolucionó desde *BackTrack* hasta la versión actual), se enseña en prácticamente todos los cursos y certificaciones de pentesting del mercado (incluido el curso OSCP), y el ecosistema de tutoriales, foros y recursos asume Kali por default.

Para un pentester profesional, Kali no es "la mejor distribución Linux para hackear" — es *la* distribución que todo cliente, colega y documentación espera. Cambiarla tiene costo de fricción sin beneficio claro.

Kali está disponible como máquina virtual, instalación bare metal, contenedor Docker, WSL en Windows, e incluso imagen para Raspberry Pi y dispositivos móviles Android. La flexibilidad es parte de su dominancia.

### Alternativas relevantes

**[Parrot Security OS](https://www.parrotsec.org/)** es la alternativa más seria. Basada en Debian como Kali pero con enfoque algo distinto — énfasis en privacidad, herramientas forenses integradas, y rendimiento más liviano en hardware modesto. Tiene comunidad sólida y algunos pentesters la prefieren por cuestiones de usabilidad, aunque sigue siendo minoritaria frente a Kali.

**[BlackArch Linux](https://blackarch.org/)** está basada en Arch Linux y ofrece el catálogo más extenso de herramientas (más de 2.800). Es preferida por usuarios avanzados que valoran control granular del sistema, a costa de mayor complejidad de instalación y mantenimiento.

**Windows con herramientas de red team.** Muchos red teamers trabajan directamente desde Windows para simular adversarios reales con mayor fidelidad. Los atacantes reales no suelen operar desde Kali obvio — usan el entorno que más se parece al de sus víctimas. Para red teaming sofisticado, el equipo puede trabajar en Windows con herramientas como [Covenant](https://github.com/cobbr/Covenant) o desde máquinas dedicadas con setups específicos.

---

## Reconocimiento y enumeración

La fase inicial de cualquier pentest es reconocimiento: mapear qué existe en el objetivo antes de intentar atacar nada. Las herramientas dominantes en esta fase son maduras y estables — llevan décadas siendo referencia.

### nmap

[Nmap](https://nmap.org/) es la herramienta más icónica de la ciberseguridad ofensiva. Desarrollada originalmente por Gordon Lyon (conocido en la comunidad como *Fyodor*) en 1997, sigue siendo la referencia absoluta en escaneo de redes y descubrimiento de servicios.

Lo que hace nmap: envía paquetes de red cuidadosamente construidos para descubrir qué hosts existen en un rango de IPs, qué puertos tienen abiertos, qué servicios corren detrás de cada puerto, qué versiones específicas de software están instaladas, y a veces incluso qué sistema operativo usa cada host. Toda esa información sale de patrones sutiles en cómo responden los sistemas a los paquetes enviados.

Nmap se convirtió en tan emblemático que aparece en películas y series (es la herramienta que usan Trinity en *Matrix Reloaded* y los personajes técnicos en *Mr. Robot*). Más allá del guiño cultural, es literalmente la primera herramienta que abre la mayoría de los pentesters cuando empieza un engagement.

Su dominancia viene de tres factores: cobertura técnica exhaustiva (años de desarrollo acumulado), flexibilidad para casos de uso muy distintos, y el [Nmap Scripting Engine (NSE)](https://nmap.org/book/nse.html) que permite ejecutar scripts personalizados durante el escaneo para detectar vulnerabilidades específicas.

### Masscan

[Masscan](https://github.com/robertdavidgraham/masscan) es el complemento natural de nmap cuando la escala es grande. Diseñado para escanear internet entero en minutos (literalmente), sacrifica precisión por velocidad.

Un pentester no usa masscan en lugar de nmap — los usa en secuencia. Masscan primero para un barrido rápido de grandes rangos, identificando qué está vivo, y después nmap para enumeración detallada de los hosts interesantes.

### Enumeración de subdominios

Varias herramientas especializadas cubren el reconocimiento de superficie web. [Amass](https://github.com/owasp-amass/amass) y [Subfinder](https://github.com/projectdiscovery/subfinder) son dos de las más usadas. Combinan múltiples técnicas — consultas a bases de datos públicas de certificados SSL, APIs de servicios como Shodan o Censys, análisis de DNS — para mapear todos los subdominios que una organización expone.

Es sorprendentemente común que una empresa ignore cuántos subdominios tiene registrados, y algunos de esos subdominios olvidados resultan ser los puntos de entrada más vulnerables.

### OSINT y reconocimiento pasivo

[theHarvester](https://github.com/laramies/theHarvester) recolecta información pública sobre una organización — correos electrónicos de empleados, subdominios, IPs — desde fuentes abiertas. [Maltego](https://www.maltego.com/) permite análisis visual de relaciones en OSINT, mapeando conexiones entre personas, empresas, dominios y otros elementos. **Shodan** ([shodan.io](https://www.shodan.io/)) es un motor de búsqueda de dispositivos conectados a internet — tipo *Google para servidores expuestos* — muy usado en reconocimiento inicial.

---

## Pentesting de aplicaciones web

Las aplicaciones web son uno de los objetivos más comunes, y tienen un ecosistema de herramientas bien desarrollado. Dos dominan la categoría.

### Burp Suite

[Burp Suite](https://portswigger.net/burp) de **PortSwigger** es la herramienta más importante del web pentesting. Hay pentesters que literalmente no abren otra cosa durante el 80% de un engagement de aplicación web.

Burp funciona como proxy entre el navegador y la aplicación — intercepta cada request y response, permitiendo ver, modificar, repetir y automatizar interacciones. Eso parece simple pero habilita todo el trabajo ofensivo serio en web: manipulación de parámetros, bypass de controles del lado del cliente, fuzzing automatizado, testing de vulnerabilidades de autenticación y autorización, explotación de inyecciones, y análisis de APIs.

La versión **Community** es gratuita con limitaciones. La versión **Professional** (USD 449/año aproximadamente) incluye el escáner automatizado, el *Intruder* sin rate limits, y extensiones avanzadas. Es una de las pocas herramientas comerciales que los pentesters profesionales compran individualmente — el retorno justifica el costo. En un equipo interno, estas licencias son una línea fija del [presupuesto de seguridad ofensiva](/guia/costos-pentesting-red-team-presupuesto-empresarial).

El ecosistema alrededor de Burp incluye la [PortSwigger Web Security Academy](https://portswigger.net/web-security), plataforma gratuita con el curso más completo de web hacking disponible, y una comunidad activa de extensiones (*BApp Store*) que añaden funcionalidades específicas.

### OWASP ZAP

[OWASP ZAP](https://www.zaproxy.org/) (Zed Attack Proxy) es la alternativa gratuita y open source a Burp. Mantenida por la Fundación OWASP, cubre aproximadamente el mismo territorio conceptual — proxy, scanner, fuzzer, manipulación de requests — aunque con experiencia de usuario menos pulida que Burp Professional.

En pentesting profesional, ZAP aparece principalmente en dos contextos: equipos con restricciones de presupuesto que no pueden adquirir licencias de Burp, y pipelines automatizados de DevSecOps donde se integra en CI/CD para escaneos continuos de aplicaciones.

### Herramientas especializadas en web

Alrededor de Burp y ZAP orbitan varias herramientas más específicas. [sqlmap](http://sqlmap.org/) es el estándar absoluto para explotación automatizada de SQL injection — detecta vulnerabilidades SQL y las explota profundamente, extrayendo bases de datos enteras si las defensas lo permiten. [ffuf](https://github.com/ffuf/ffuf) y [wfuzz](https://github.com/xmendez/wfuzz) son herramientas de *fuzzing* para descubrir recursos ocultos (directorios, parámetros, endpoints) mediante fuerza bruta inteligente con diccionarios.

---

## Explotación y frameworks

Una vez identificadas vulnerabilidades, viene la fase de explotación. El rubro está dominado por un framework masivo y varias herramientas especializadas.

### Metasploit Framework

[Metasploit](https://www.metasploit.com/) es el framework de explotación más conocido de la industria. Mantenido por **Rapid7** como producto comercial (*Metasploit Pro*) pero con versión open source completamente funcional (*Metasploit Framework*), contiene miles de exploits para vulnerabilidades conocidas, payloads para distintos sistemas, y módulos auxiliares para reconocimiento, post-explotación y evasión.

La importancia histórica de Metasploit es profunda: cuando se lanzó en 2003, democratizó la explotación de vulnerabilidades. Antes, explotar una vulnerabilidad conocida requería encontrar o escribir el exploit correcto, compilarlo, adaptar el payload, ejecutarlo cuidadosamente. Metasploit estandarizó todo eso en una interfaz unificada: seleccionás el exploit, configurás el objetivo, elegís el payload, ejecutás.

En 2026, los pentesters profesionales usan Metasploit menos que antes por una razón específica: los productos de [antivirus y EDR](/productos/antivirus-y-edr) modernos detectan las firmas típicas de sus payloads. Para entornos sin defensas avanzadas sigue siendo enormemente útil y eficiente. Para entornos con EDR serio, los pentesters recurren a técnicas más custom y menos detectables.

### Impacket

[Impacket](https://github.com/fortra/impacket) es una colección de scripts Python que implementan protocolos de red Windows. Se volvió absolutamente indispensable para pentesting de infraestructura corporativa Windows. Incluye scripts icónicos como `psexec.py` para ejecución remota, `secretsdump.py` para extracción de credenciales, `smbclient.py` para interactuar con servicios SMB, y docenas más.

Originalmente desarrollado por **Core Security**, ahora mantenido por **Fortra**, es software libre y se usa en prácticamente cualquier pentest interno de red corporativa Windows.

### CrackMapExec y NetExec

[CrackMapExec](https://github.com/byt3bl33d3r/CrackMapExec) fue una de las herramientas más usadas en pentesting de Active Directory durante años — un "swiss army knife" que combina funciones de enumeración, testing de credenciales, ejecución remota y extracción de información en entornos Windows domain.

Su fork más reciente [NetExec](https://github.com/Pennyw0rth/NetExec) está manteniendo activo el proyecto y es donde el desarrollo se mueve hacia adelante en 2026. Para pentesters que trabajan con Active Directory, estas herramientas están entre las primeras que abren al encontrar acceso a una red corporativa.

### Responder

[Responder](https://github.com/lgandx/Responder) es una herramienta icónica de ataques a redes Windows internas. Captura y responde a consultas de protocolos como LLMNR, NBT-NS, y mDNS que Windows usa por default, frecuentemente logrando capturar hashes de credenciales de la red sin intervención del usuario.

Es una de esas herramientas que ilustran por qué el pentesting interno es valioso: en muchas redes corporativas, ejecutar Responder durante una hora resulta en captura de credenciales aprovechables sin haber "atacado" nada explícitamente — los protocolos mismos filtran información.

Lo que corta esa cadena del lado defensivo no es otra herramienta: es tener segundo factor en los accesos internos, que es justo donde aparecen [los huecos que deja el MFA](/guia/ya-tenes-mfa-y-no-alcanza) en la mayoría de las PyMEs.

---

## Active Directory y movimiento lateral

El pentesting de Active Directory es su propia especialidad dentro del pentesting de infraestructura. Dos herramientas se destacan como estándares modernos.

### BloodHound

[BloodHound](https://bloodhound.readthedocs.io/) cambió el pentesting de Active Directory cuando se lanzó en 2016. Toma datos de Active Directory (extraídos con su compañero SharpHound) y los analiza como un grafo de relaciones, identificando visualmente los caminos de ataque más cortos hacia objetivos de alto valor como *Domain Admin*.

Antes de BloodHound, encontrar esos caminos requería análisis manual tedioso. BloodHound lo automatiza con algoritmos de grafos — un pentester puede ver en segundos que *"desde esta cuenta comprometida, con 4 saltos específicos, llego a Domain Admin"*.

Su impacto fue tan grande que Active Directory en empresas modernas se diseña explícitamente pensando en qué mostraría BloodHound si alguien lo ejecutara. Los equipos defensivos también lo usan para identificar sus propios caminos de ataque y bloquearlos antes que un atacante los encuentre.

### Mimikatz

[Mimikatz](https://github.com/gentilkiwi/mimikatz) es posiblemente la herramienta más famosa de post-explotación Windows. Desarrollada por el investigador francés Benjamin Delpy, extrae credenciales directamente de la memoria de Windows, permitiendo recuperar contraseñas, hashes, tickets Kerberos y más.

Tuvo impacto histórico: muchos de los ataques masivos de ransomware de los últimos años usaron técnicas que Mimikatz hizo accesibles. Microsoft respondió con mitigaciones crecientes (Credential Guard, VBS) que reducen su efectividad en entornos modernos bien configurados, pero en la mayoría de las redes corporativas reales Mimikatz sigue siendo relevante.

Los EDR modernos detectan Mimikatz por firma — los pentesters avanzados usan variantes o técnicas similares implementadas de formas menos detectables.

---

## Red teaming y command and control

Para red teaming, las herramientas son más especializadas y frecuentemente comerciales.

### Cobalt Strike

[Cobalt Strike](https://www.cobaltstrike.com/) es el estándar comercial del red teaming profesional. Desarrollado por Raphael Mudge y ahora parte de **Fortra**, es una plataforma completa de simulación de adversarios que incluye generación de payloads ("Beacons"), command and control robusto, módulos de post-explotación, y capacidades de colaboración en equipo.

Su licencia cuesta más de USD 7.000 por año, razón por la que no es accesible para aficionados. Esa barrera económica es parte de su valor: usarlo implica operación corporativa seria.

Cobalt Strike también tiene un lado incómodo: versiones crackeadas son ampliamente usadas por grupos de ransomware reales. Los equipos de threat intelligence mapean los "beacons" de Cobalt Strike como indicador de compromiso porque tanto red teams legítimos como actores maliciosos lo usan. Esta dualidad genera debates éticos sobre si la herramienta debería existir — Fortra endureció los controles de distribución en los últimos años.

### Alternativas open source

**[Sliver](https://github.com/BishopFox/sliver)** de Bishop Fox es la alternativa open source más madura a Cobalt Strike. Gratuita, activamente desarrollada, con features comparables para operaciones estándar. Ganó mucha adopción profesional entre 2023 y 2026.

**[Mythic](https://github.com/its-a-feature/Mythic)** es un framework de C2 modular y extensible, muy usado por red teams que quieren personalizar profundamente sus operaciones.

**[Havoc](https://github.com/HavocFramework/Havoc)** es más reciente pero ganó tracción rápidamente por su enfoque en evasión moderna.

### Empire y PowerShell

[Empire](https://github.com/BC-SECURITY/Empire) y su variante [Starkiller](https://github.com/BC-SECURITY/Starkiller) fueron referentes de post-explotación basada en PowerShell durante años. Son menos centrales en 2026 porque PowerShell es uno de los vectores más monitoreados por los EDR modernos, pero siguen siendo relevantes en ejercicios controlados y entornos donde la detección PowerShell es limitada.

---

## Cracking de credenciales

Cuando se obtienen hashes de contraseñas, hay que intentar romperlos. Tres herramientas dominan la categoría.

### Hashcat

[Hashcat](https://hashcat.net/) es el cracker de contraseñas más rápido disponible, aprovechando GPU para paralelizar ataques masivamente. Soporta más de 300 algoritmos de hash distintos y ataca con diccionarios, fuerza bruta, reglas de transformación, y ataques híbridos.

Para una empresa con GPU moderna, hashcat puede probar miles de millones de contraseñas por segundo contra ciertos algoritmos. Esto ilustra por qué los hashes modernos usan funciones deliberadamente lentas como Argon2 o bcrypt — para que esa velocidad no alcance para romper contraseñas razonablemente seguras.

### John the Ripper

[John the Ripper](https://www.openwall.com/john/) es el clásico anterior a hashcat. Más versátil en algunos formatos específicos, especialmente para archivos cifrados y formatos históricos. En pentesting moderno, los profesionales suelen usar ambos según el caso.

### Hydra

[Hydra](https://github.com/vanhauser-thc/thc-hydra) no crackea hashes sino que prueba credenciales contra servicios de red en vivo — SSH, RDP, FTP, HTTP forms, y muchos otros. Útil cuando hay servicios expuestos que aceptan intentos de login, aunque en 2026 la mayoría de los servicios maduros implementan rate limiting y lockout que limitan su efectividad.

---

## Análisis de tráfico y captura

Varias herramientas clásicas cubren el análisis de tráfico de red, útil tanto en pentesting activo como en análisis de protocolos.

### Wireshark

[Wireshark](https://www.wireshark.org/) es el analizador de protocolos de red más conocido. Captura paquetes en vivo desde interfaces de red y permite análisis profundo de cada protocolo. En pentesting se usa para inspeccionar tráfico durante pruebas, entender cómo se comunican aplicaciones custom, y ocasionalmente capturar información sensible transmitida sin cifrar.

### tcpdump

[tcpdump](https://www.tcpdump.org/) es el equivalente de línea de comandos, útil para captura rápida en servidores o entornos sin interfaz gráfica. Los pentesters lo usan frecuentemente durante engagements para capturar tráfico en máquinas comprometidas antes de llevarlo a Wireshark para análisis detallado.

---

## Ingeniería inversa

Para pentesting avanzado que requiere análisis de binarios — exploit development, análisis de firmware, mobile pentesting profundo — la ingeniería inversa es una disciplina aparte.

### Ghidra

[Ghidra](https://ghidra-sre.org/) es la herramienta de ingeniería inversa publicada por la NSA en 2019. Análisis de binarios, decompilación, debugging, scripting. Es gratuita, open source, y extremadamente potente. Su publicación cambió el ecosistema porque hasta entonces las alternativas comerciales de su nivel costaban miles de dólares.

### IDA Pro

[IDA Pro](https://hex-rays.com/ida-pro/) es el estándar comercial histórico de ingeniería inversa. Extremadamente potente, con ecosistema maduro de plugins. Sigue siendo referencia en research profesional de vulnerabilidades.

### Radare2 y Cutter

[Radare2](https://rada.re/) y su interfaz gráfica [Cutter](https://cutter.re/) son alternativas open source establecidas, particularmente populares en la comunidad de CTF y research independiente.

---

## Cómo se combinan las herramientas en un pentest real

Las herramientas listadas no se usan aisladamente — se combinan en flujos de trabajo que varían según el tipo de engagement. Algunos patrones típicos ilustran cómo se ensamblan.

En un **pentest externo estándar**, el flujo típico arranca con reconocimiento OSINT (theHarvester, Amass, Shodan) seguido de masscan para barrido rápido de IPs y nmap para enumeración detallada de puertos y servicios. Los hallazgos interesantes se investigan manualmente o con herramientas específicas (Nikto para web básico, Metasploit para vulnerabilidades conocidas). Las aplicaciones web encontradas se testean con Burp Suite. Si hay RDP o SSH expuestos, hydra para ataques de credenciales básicos si tiene sentido (raro que funcione en 2026 pero se prueba).

En un **pentest interno de Active Directory**, el arranque es distinto. Con acceso a la red interna, Responder captura credenciales mediante spoofing de protocolos Windows durante las primeras horas. Las credenciales obtenidas se prueban con NetExec contra dominio para mapear qué acceso otorgan. SharpHound recolecta datos para BloodHound, que revela caminos de ataque hacia Domain Admin. Impacket se usa para explotar esos caminos — `secretsdump.py` para extracción de credenciales, `psexec.py` para ejecución remota, y múltiples otros según la técnica específica. Mimikatz entra cuando se tiene acceso local a máquinas donde hay credenciales en memoria.

En un **pentest de aplicación web**, Burp Suite domina el trabajo. Reconocimiento manual de la aplicación, mapeo de endpoints con el *Site Map*, testeo de funcionalidades con el *Repeater*, fuzzing con *Intruder*, escaneo automatizado con el *Scanner*. sqlmap se activa si se detectan indicios de SQL injection. ffuf para descubrimiento de recursos ocultos. Wireshark ocasional si hay que analizar tráfico API específico.

En **red teaming**, el setup es más sofisticado. Infraestructura C2 propia con Cobalt Strike (o Sliver/Mythic), redirectores, dominios comprados para el engagement, certificados SSL válidos. El acceso inicial frecuentemente vía phishing —el lado defensivo de eso es lo que mide la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes)— o explotación de algún servicio expuesto. Una vez adentro, el trabajo combina BloodHound, Impacket y técnicas de evasión avanzadas que no son herramientas *per se* sino métodos que se ejecutan con scripting custom.

---

## Las herramientas son la mitad del trabajo

Un punto que merece énfasis específico: **las herramientas solas no hacen pentesting**. Una empresa puede descargar Kali Linux, ejecutar nmap contra su propia red, abrir Burp Suite contra su aplicación, y obtener resultados que parecen útiles. Pero interpretar esos resultados, entender qué significan, distinguir falsos positivos de hallazgos reales, y traducir todo en un plan de acción requiere el entrenamiento profesional que las certificaciones buscan validar.

Las mismas herramientas en manos de un pentester OSCP con 5 años de experiencia producen resultados radicalmente distintos que en manos de alguien que nunca usó esos términos antes. Es similar a la diferencia entre un médico con un estetoscopio y cualquier otra persona con el mismo estetoscopio — el instrumento es idéntico, la capacidad de interpretar la información que genera es completamente distinta.

Esto pesa cuando se evalúan propuestas de "pentesting automatizado" o servicios muy económicos que prometen resultados rápidos. Si el servicio es literalmente correr herramientas automáticas y entregar el output crudo con maquillaje, el valor es limitado — los resultados crudos de nmap o Burp requieren análisis profesional para ser útiles.

---

## Herramientas comerciales vs open source

Una distinción que aparece frecuentemente: la mayoría de las herramientas principales de pentesting son open source, pero varias herramientas comerciales importantes también existen. Cada categoría tiene su rol.

Las **herramientas open source** dominan en capas amplias: reconocimiento, enumeración, explotación básica, análisis. Son gratuitas, activamente mantenidas por comunidades, y sus capacidades compiten con alternativas comerciales en muchos casos.

Las **herramientas comerciales** (Burp Suite Professional, Cobalt Strike, IDA Pro) ofrecen ventajas específicas: experiencia de usuario pulida, soporte técnico profesional, features avanzadas que requieren inversión de desarrollo, y a veces aspectos legales (como operar herramientas de red team bajo licencia formal). Los pentesters profesionales típicamente usan una mezcla — open source para la mayoría del trabajo y comercial para casos donde aportan valor diferencial.

Las empresas que evalúan propuestas pueden encontrar que los proveedores usan mezcla de ambas categorías. Eso es normal y esperable — no es señal de nada en particular ni positivo ni negativo.

---

## La evolución del rubro

El ecosistema de herramientas de pentesting cambia lento pero cambia. Algunos patrones observables en los últimos años:

**Las herramientas clásicas mantienen relevancia**. nmap, Burp, Metasploit llevan décadas siendo referencia y no hay señales de desplazamiento. La estabilidad es característica del rubro — nuevas herramientas se suman pero las clásicas no desaparecen.

**Red teaming generó ecosistema propio**. Hace 10 años no existían frameworks específicos de red team accesibles. Hoy hay múltiples opciones (Cobalt Strike, Sliver, Mythic, Havoc) y el rubro maduró mucho.

**Las herramientas cloud se multiplicaron**. A medida que la infraestructura corporativa migró a AWS, Azure y GCP, aparecieron herramientas específicas para pentesting cloud: [Pacu](https://github.com/RhinoSecurityLabs/pacu) para AWS, [MicroBurst](https://github.com/NetSPI/MicroBurst) para Azure, entre otras. Es un área con desarrollo activo.

**La IA empieza a aparecer**. Herramientas que usan LLMs para acelerar análisis de código, generación de exploits, y automatización de reconocimiento están emergiendo. Todavía es área temprana pero cambios importantes están previstos en los próximos años.

**Los EDR modernos fuerzan sofisticación**. Las herramientas clásicas con firmas conocidas son detectadas fácilmente por EDR de buena calidad. Los pentesters profesionales cada vez más usan técnicas custom y variantes que evaden detección, lo que eleva el piso técnico requerido para pentesting serio.

---

## Próximos pasos

Si llegaste hasta acá, ya tenés el mapa general del ecosistema de herramientas. Los artículos complementarios que profundizan otros aspectos del rubro:

- [**Qué es pentesting: fases, tipos y cómo evaluar proveedores**](/guia/que-es-pentesting-como-funciona-fases-tipos) — el contexto del servicio completo
- [**Qué es un pentester: rol, tipos y por qué las empresas los contratan**](/guia/que-es-un-pentester-perfil-rol-como-evaluar) — la figura profesional que usa estas herramientas
- [**Red teaming: qué es y cuándo contratarlo**](/guia/red-teaming-que-es-cuando-contratarlo) — donde Cobalt Strike y las alternativas de C2 son centrales
- [**Certificaciones de pentesting: qué son y qué significan**](/guia/certificaciones-pentesting-que-exigir-proveedor) — contexto de las certificaciones donde muchas de estas herramientas se enseñan oficialmente

Para el panorama general, el hub está en [**Seguridad Ofensiva: qué es Ethical Hacking, Pentesting y Red Teaming**](/guia/seguridad-ofensiva-ethical-hacking-pentesting-red-teaming).

---

*¿Querés profundizar en alguna categoría específica de herramientas o entender mejor cómo se usan en un caso concreto? Escribinos a [contacto@capacero.online](mailto:contacto@capacero.online).*
