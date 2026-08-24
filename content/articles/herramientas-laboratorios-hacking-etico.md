---
title: "Herramientas de pentesting: cuáles son y qué hace cada una"
subtitle: Panorama de las herramientas que aparecen en casi cualquier trabajo de seguridad ofensiva profesional, qué resuelve cada una y cómo se encadenan.
excerpt: Kali, nmap, Burp Suite, Metasploit, BloodHound, Cobalt Strike. Qué hace cada herramienta, cuál es su licencia y en qué fase de un pentest entra.
type: guide
status: published
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-04-22 03:25:23
updated: 2026-08-24
meta_title: "Herramientas de pentesting: cuáles son y qué hace cada una"
meta_description: "Kali, nmap, Burp Suite, Metasploit, BloodHound y Cobalt Strike. Qué hace cada herramienta, qué licencia tiene y cómo se combinan en un pentest."
---

Un pentester rota entre las mismas cuatro o cinco herramientas durante todo el día. No es por falta de alternativas: hay cientos. Es que en cada categoría hay una o dos que se consolidaron como estándar durante los últimos veinte años, y cambiarlas cuesta fricción sin beneficio claro.

Este es el mapa de esas herramientas. No es un manual de uso ni un catálogo exhaustivo: son las que aparecen en prácticamente cualquier trabajo profesional y cuyo nombre forma parte del vocabulario básico del rubro. Para una empresa, conocerlas sirve para leer mejor una propuesta técnica.

---

## El sistema operativo

| Herramienta | Qué aporta | Licencia |
|---|---|---|
| [Kali Linux](https://www.kali.org/) | Distribución Debian con más de 600 herramientas ofensivas preinstaladas | Libre |
| [Parrot Security OS](https://www.parrotsec.org/) | Alternativa más liviana, con énfasis en privacidad y forense | Libre |
| [BlackArch](https://blackarch.org/) | Basada en Arch, el catálogo más extenso: más de 2.800 herramientas | Libre |

Kali es la referencia por tres razones, ninguna estrictamente técnica: lleva más de quince años de mantenimiento activo —viene de *BackTrack*—, se enseña en casi todos los cursos y certificaciones del mercado incluido el de OSCP, y la documentación del rubro la asume por defecto. No es la mejor distribución para atacar: es la que todo cliente, colega y tutorial da por sentada.

Está disponible como máquina virtual, instalación directa, contenedor, WSL en Windows e imagen para Raspberry Pi. Esa flexibilidad es parte de su dominio.

Hay una excepción que conviene entender: muchos equipos de red team trabajan directamente desde Windows. Un atacante real no opera desde un Kali evidente, usa un entorno parecido al de sus víctimas. Cuando el objetivo del ejercicio es fidelidad al adversario, el sistema operativo también se elige por eso.

---

## Reconocimiento y enumeración

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [nmap](https://nmap.org/) | Descubre hosts, puertos abiertos, servicios y versiones; con [NSE](https://nmap.org/book/nse.html) detecta fallas concretas | Libre |
| [Masscan](https://github.com/robertdavidgraham/masscan) | Barrido de rangos enormes en minutos, sacrificando precisión por velocidad | Libre |
| [Amass](https://github.com/owasp-amass/amass) y [Subfinder](https://github.com/projectdiscovery/subfinder) | Mapean todos los subdominios que una organización expone | Libre |
| [theHarvester](https://github.com/laramies/theHarvester) | Recolecta correos, subdominios e IPs desde fuentes públicas | Libre |
| [Maltego](https://www.maltego.com/) | Análisis visual de relaciones entre personas, empresas y dominios | Comercial, con edición gratuita |
| [Shodan](https://www.shodan.io/) | Buscador de dispositivos conectados a internet | Freemium |

nmap se publicó en 1997 y sigue siendo lo primero que abre la mayoría de los pentesters. Su ventaja es la cobertura acumulada: años de trabajo sobre cómo responden los sistemas a paquetes construidos de maneras particulares, más un motor de scripts que permite buscar vulnerabilidades específicas durante el escaneo.

Masscan no reemplaza a nmap, lo precede: barrido amplio primero para saber qué está vivo, enumeración detallada después sobre lo interesante.

La enumeración de subdominios merece atención del lado defensivo. Es común que una empresa no sepa cuántos subdominios tiene registrados, y los olvidados suelen ser la puerta de entrada más débil.

---

## Aplicaciones web

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [Burp Suite](https://portswigger.net/burp) | Proxy que intercepta, modifica y repite cada petición entre navegador y aplicación | Community gratis; Professional ~USD 449 al año |
| [OWASP ZAP](https://www.zaproxy.org/) | Equivalente libre: proxy, escáner y fuzzer | Libre |
| [sqlmap](http://sqlmap.org/) | Detecta y explota inyección SQL de forma automatizada | Libre |
| [ffuf](https://github.com/ffuf/ffuf) y [wfuzz](https://github.com/xmendez/wfuzz) | Descubren directorios, parámetros y endpoints ocultos por diccionario | Libre |

Burp es la herramienta central de la categoría; hay pentesters que no abren otra cosa durante el 80% de un trabajo sobre una aplicación web. Interceptar y modificar peticiones parece poco y habilita todo lo demás: manipulación de parámetros, salteo de controles del lado del cliente, fuzzing, pruebas de autenticación y autorización, análisis de APIs.

La versión Professional agrega el escáner y elimina los límites de velocidad del *Intruder*. Es una de las pocas herramientas comerciales que los profesionales compran de su bolsillo, y en un equipo interno es una línea fija del [presupuesto de seguridad ofensiva](/guia/costos-pentesting-red-team-presupuesto-empresarial). PortSwigger además publica la [Web Security Academy](https://portswigger.net/web-security), que es el curso gratuito más completo de hacking web disponible.

ZAP aparece sobre todo en dos contextos: equipos sin presupuesto para licencias y tuberías de integración continua donde se automatizan escaneos.

---

## Explotación e infraestructura Windows

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [Metasploit](https://www.metasploit.com/) | Miles de exploits, payloads y módulos bajo una interfaz única | Framework libre; Pro comercial |
| [Impacket](https://github.com/fortra/impacket) | Scripts que implementan protocolos de red de Windows: `psexec.py`, `secretsdump.py` y decenas más | Libre |
| [NetExec](https://github.com/Pennyw0rth/NetExec) | Enumera, prueba credenciales y ejecuta en entornos de dominio; sucesor de CrackMapExec | Libre |
| [Responder](https://github.com/lgandx/Responder) | Responde consultas LLMNR, NBT-NS y mDNS para capturar hashes de la red | Libre |

Metasploit estandarizó en 2003 algo que antes era artesanal: encontrar el exploit, compilarlo, adaptar el payload y ejecutarlo. En 2026 los profesionales lo usan menos, porque los [productos de antivirus y EDR](/productos/antivirus-y-edr) detectan las firmas de sus payloads. Sigue siendo eficiente contra entornos sin defensa avanzada.

Responder ilustra por qué el pentest interno rinde: en muchas redes corporativas alcanza con dejarlo corriendo una hora para capturar credenciales, sin haber atacado nada. Los protocolos mismos filtran la información. Lo que corta esa cadena del lado defensivo no es otra herramienta, es tener segundo factor en los accesos internos, que es justo donde aparecen [los huecos que deja el MFA](/guia/ya-tenes-mfa-y-no-alcanza) en la mayoría de las PyMEs.

---

## Active Directory

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [BloodHound](https://bloodhound.readthedocs.io/) | Analiza Active Directory como un grafo y muestra el camino más corto a Domain Admin | Libre |
| [Mimikatz](https://github.com/gentilkiwi/mimikatz) | Extrae contraseñas, hashes y tickets Kerberos de la memoria de Windows | Libre |

BloodHound cambió la disciplina en 2016. Antes, encontrar esos caminos era análisis manual; ahora un pentester ve en segundos que desde una cuenta comprometida llega a Domain Admin en cuatro saltos. Su efecto llegó al lado defensivo: hoy un Active Directory se diseña pensando en qué mostraría BloodHound si alguien lo corriera, y los equipos internos lo usan para cerrar esos caminos antes que nadie los recorra.

Mimikatz es probablemente la herramienta de post-explotación más conocida. Microsoft respondió con Credential Guard y virtualización que reducen su efectividad, y en la mayoría de las redes reales sigue funcionando. Los EDR modernos la detectan por firma, así que en trabajos avanzados se usan variantes menos reconocibles.

### Cómo se encadenan en un pentest interno

```svg
<svg viewBox="0 0 680 200" role="img" aria-label="Cadena típica de un pentest interno de Active Directory: Responder captura hashes de la red, NetExec prueba dónde sirven, BloodHound mapea el camino a Domain Admin, Impacket lo ejecuta y Mimikatz extrae credenciales de la memoria">
  <text x="340" y="26" text-anchor="middle" font-size="12.5" font-weight="700" fill="currentColor">Cadena típica de un pentest interno</text>

  <rect x="16" y="48" width="118" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="16" y="48" width="118" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="75" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Responder</text>
  <text x="75" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">hashes de</text>
  <text x="75" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">la red</text>
  <path d="M136 86 L144 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="146" y="48" width="118" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="146" y="48" width="118" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="205" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">NetExec</text>
  <text x="205" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">dónde</text>
  <text x="205" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">sirven</text>
  <path d="M266 86 L274 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="276" y="48" width="118" height="76" rx="6" fill="#e23a3a" opacity="0.13"/>
  <rect x="276" y="48" width="118" height="76" rx="6" fill="none" stroke="#e23a3a" stroke-width="1.5"/>
  <text x="335" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="#e23a3a">BloodHound</text>
  <text x="335" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">camino a</text>
  <text x="335" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">Domain Admin</text>
  <path d="M396 86 L404 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="406" y="48" width="118" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="406" y="48" width="118" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="465" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Impacket</text>
  <text x="465" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">ejecución</text>
  <text x="465" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">remota</text>
  <path d="M526 86 L534 86" stroke="currentColor" stroke-width="1.4" opacity="0.6"/>

  <rect x="536" y="48" width="118" height="76" rx="6" fill="currentColor" opacity="0.08"/>
  <rect x="536" y="48" width="118" height="76" rx="6" fill="none" stroke="currentColor" stroke-width="1.3" opacity="0.55"/>
  <text x="595" y="74" text-anchor="middle" font-size="11.5" font-weight="700" fill="currentColor">Mimikatz</text>
  <text x="595" y="95" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">credenciales</text>
  <text x="595" y="110" text-anchor="middle" font-size="10.5" fill="currentColor" opacity="0.8">en memoria</text>

  <text x="340" y="156" text-anchor="middle" font-size="11" fill="currentColor" opacity="0.8">Ningún paso explota una vulnerabilidad: usan el funcionamiento normal del dominio</text>
  <text x="340" y="176" text-anchor="middle" font-size="11" font-weight="600" fill="currentColor">Por eso se corta con configuración, no con parches</text>
</svg>
```

Ese detalle es el que más importa del lado defensivo. La cadena no depende de ninguna falla sin parchear: se apoya en comportamientos previstos de Windows y del dominio. Se corta con configuración —apagar LLMNR y NBT-NS, restringir permisos, segundo factor en accesos internos—, no esperando un boletín de seguridad.

---

## Command and control

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [Cobalt Strike](https://www.cobaltstrike.com/) | Plataforma completa de simulación de adversarios: payloads, C2 y trabajo en equipo | Comercial, más de USD 7.000 al año |
| [Sliver](https://github.com/BishopFox/sliver) | La alternativa libre más madura, de Bishop Fox | Libre |
| [Mythic](https://github.com/its-a-feature/Mythic) | Framework modular para equipos que personalizan a fondo sus operaciones | Libre |
| [Havoc](https://github.com/HavocFramework/Havoc) | Más reciente, con foco en evasión moderna | Libre |
| [Empire](https://github.com/BC-SECURITY/Empire) | Post-explotación sobre PowerShell; menos central hoy | Libre |

Cobalt Strike tiene un costado incómodo: sus versiones pirateadas son de uso habitual entre grupos de ransomware. Los equipos de inteligencia de amenazas rastrean sus *beacons* como indicador de compromiso justamente porque los usan tanto los equipos legítimos como los atacantes reales. Fortra endureció los controles de distribución en los últimos años.

Empire perdió centralidad porque PowerShell es hoy uno de los vectores más vigilados por los EDR.

---

## Credenciales, tráfico e ingeniería inversa

| Herramienta | Qué hace | Licencia |
|---|---|---|
| [Hashcat](https://hashcat.net/) | Rompe hashes aprovechando GPU; más de 300 algoritmos | Libre |
| [John the Ripper](https://www.openwall.com/john/) | El clásico previo, más versátil en formatos históricos y archivos cifrados | Libre |
| [Hydra](https://github.com/vanhauser-thc/thc-hydra) | Prueba credenciales contra servicios vivos: SSH, RDP, FTP, formularios | Libre |
| [Wireshark](https://www.wireshark.org/) | Analizador de protocolos con captura en vivo | Libre |
| [tcpdump](https://www.tcpdump.org/) | Captura por línea de comandos, para servidores sin interfaz gráfica | Libre |
| [Ghidra](https://ghidra-sre.org/) | Ingeniería inversa de binarios, publicada por la NSA en 2019 | Libre |
| [IDA Pro](https://hex-rays.com/ida-pro/) | El estándar comercial histórico del rubro | Comercial |
| [Radare2](https://rada.re/) y [Cutter](https://cutter.re/) | Alternativas libres establecidas, populares en CTF e investigación | Libre |

Hashcat prueba miles de millones de contraseñas por segundo contra ciertos algoritmos con una GPU moderna. Eso explica por qué los sistemas actuales guardan contraseñas con funciones deliberadamente lentas como bcrypt o Argon2: la velocidad del atacante deja de alcanzar.

Hydra rinde cada vez menos, porque casi todos los servicios maduros aplican límites de intentos y bloqueo de cuenta.

La publicación de Ghidra cambió el ecosistema de ingeniería inversa: hasta 2019, una herramienta de ese nivel costaba miles de dólares.

---

## Cómo se combinan según el trabajo

**Pentest externo.** Reconocimiento abierto con theHarvester, Amass y Shodan; masscan para el barrido; nmap para enumerar en detalle. Las aplicaciones web que aparecen se prueban con Burp. Si hay RDP o SSH publicados, se intenta con hydra, aunque en 2026 rara vez funciona.

**Pentest interno de Active Directory.** Es la cadena del diagrama de arriba, y suele dar resultados en las primeras horas.

**Aplicación web.** Burp domina: mapeo de endpoints, pruebas con el *Repeater*, fuzzing con el *Intruder*, escaneo automatizado. sqlmap si hay indicios de inyección SQL, ffuf para recursos ocultos.

**Red teaming.** Infraestructura propia de C2 con Cobalt Strike, Sliver o Mythic, redirectores, dominios comprados para el ejercicio y certificados válidos. El acceso inicial suele venir por phishing —el lado defensivo de eso es lo que mide la [comparativa de seguridad de email para PyMEs](/comparativa/comparativa-seguridad-email-pymes)— o por un servicio expuesto. Adentro, el trabajo combina BloodHound e Impacket con técnicas de evasión que no son herramientas sino métodos con scripting propio.

---

## Las herramientas son la mitad del trabajo

Una empresa puede bajar Kali, correr nmap contra su propia red y abrir Burp contra su aplicación. Obtendrá resultados que parecen útiles. Interpretarlos es otra cosa: distinguir un falso positivo de un hallazgo real y traducir el conjunto en un plan de acción es exactamente lo que el entrenamiento profesional aporta y lo que las [certificaciones](/guia/certificaciones-pentesting-que-exigir-proveedor) buscan validar.

Esto pesa al evaluar propuestas de pentesting automatizado o servicios muy baratos. Si el servicio consiste en correr herramientas y entregar la salida cruda con formato, el valor es limitado.

La mayoría de las herramientas de la lista son libres. Las comerciales —Burp Professional, Cobalt Strike, IDA Pro— aportan interfaz pulida, soporte y funciones que exigen inversión de desarrollo. Un proveedor serio usa una mezcla de las dos, y eso no dice nada bueno ni malo sobre él.

---

## Hacia dónde va el rubro

- **Las clásicas no se mueven.** nmap, Burp y Metasploit llevan décadas como referencia y no hay señales de reemplazo.
- **El red teaming generó su propio ecosistema.** Hace diez años no había frameworks accesibles; hoy hay cuatro o cinco maduros.
- **La nube trajo herramientas propias**, como [Pacu](https://github.com/RhinoSecurityLabs/pacu) para AWS y [MicroBurst](https://github.com/NetSPI/MicroBurst) para Azure. Es el área con desarrollo más activo.
- **La IA está entrando** en análisis de código y automatización del reconocimiento. Es temprano para conclusiones.
- **Los EDR elevaron el piso.** Las herramientas con firma conocida se detectan solas, así que el trabajo serio exige variantes propias y técnicas de evasión.

El contexto del servicio completo está en [qué es pentesting](/guia/que-es-pentesting-como-funciona-fases-tipos), el perfil de quien lo ejecuta en [qué es un pentester](/guia/que-es-un-pentester-perfil-rol-como-evaluar), y el panorama general en [seguridad ofensiva](/guia/seguridad-ofensiva-ethical-hacking-pentesting-red-teaming). Para cuándo contratar un ejercicio de adversario completo, [red teaming](/guia/red-teaming-que-es-cuando-contratarlo).
