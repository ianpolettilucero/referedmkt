# Sección de noticias — proceso diario

Este documento es autónomo a propósito. La tarea programada que alimenta
`/noticias` arranca cada día en una sesión nueva, sin memoria de las
anteriores: todo lo que hay que saber está acá.

## Qué es esta sección

Un blog de nicho sobre ciberseguridad con eje en **hacking ético y seguridad
ofensiva**, escrito desde el punto de vista de Capa Cero: qué significa para
una PyME de LATAM que corre software común y no tiene equipo de seguridad.

No es un agregador. No republicamos titulares. Cada nota tiene que aportar la
traducción que falta: **si esto pasó, ¿me toca a mí, cómo lo compruebo y qué
hago el lunes a la mañana?**

La sección aparece sola. `active_sections()` la muestra en la navegación, el
sitemap y `llms.txt` en cuanto existe una nota con `status: published`, y la
esconde si no hay ninguna. No hay nada que activar.

## La regla que define la sección

**Si ningún hallazgo del día llega al estándar, no se publica nada.**

Este es el punto más importante del documento. Una nota floja no es neutral:
gasta presupuesto de rastreo, baja la calidad media del sitio y entrena a los
lectores a saltearse la sección. Un día sin publicar no cuesta nada.

Terminar el día con "revisé, no había nada que valiera una nota" es un
resultado correcto y esperado. Pasa seguido.

## El estándar

Una historia se publica solo si cumple **las cinco**:

1. **Verificable contra fuente primaria.** NVD, el aviso del fabricante, el
   catálogo KEV de CISA, el boletín oficial, el repositorio original, o el
   informe de quien investigó, con nombre y fecha. Un medio que cita a otro
   medio no es fuente.
2. **Le toca a una PyME de LATAM.** Software que esa empresa realmente corre:
   Microsoft 365, Windows, WordPress y sus plugins, firewalls de borde,
   herramientas de backup, navegadores. No un SOC de banco.
3. **Hay algo que hacer.** Si la respuesta es "esperá el parche", solo va si
   además hay mitigación o forma de comprobar exposición.
4. **Engancha con el catálogo.** Idealmente toca un producto o categoría que
   el sitio ya cubre, para que el enlazado interno salga del tema y no forzado.
5. **No está publicada ya.** Antes de escribir:
   `grep -ril "CVE-2026-XXXXX" content/articles/` y revisar los slugs
   recientes. Un CVE ya cubierto solo se retoma si hay novedad sustancial
   (explotación activa confirmada, parche que no arregla, alcance mayor).

## Reglas de honestidad — no negociables

Son las mismas del resto del sitio, con dos agregados propios de noticias.

1. **No existe experiencia propia.** Nadie probó, compró, instaló ni explotó
   nada. Prohibido "probé", "compré", "en mi laboratorio", "un cliente mío",
   "vimos en nuestros sistemas". Ni una vez, ni suavizado.
2. **Todo dato duro sale de fuente primaria enlazada.** CVE, CVSS, fecha,
   versión afectada, versión corregida, cantidad de sistemas expuestos.
3. **Lo que no se pudo confirmar se dice.** No se completa con lo que suena
   razonable. Si un dato cae, cae también la conclusión que dependía de él.
4. **Cero instrucciones de explotación.** El eje es ofensivo en el sentido de
   entender cómo se ataca para defenderse: comprobación de exposición sí,
   prueba de concepto armada no.
5. **Sin alarmismo.** "El peor ataque de la historia" y familia, fuera. La
   gravedad la establecen el CVSS y el alcance, con su fuente.
6. **Afiliados.** El único programa activo es Hostinger. No se afirma nada
   sobre la relación comercial con ningún otro fabricante, ni a favor ni en
   contra.

**El error más caro de esta sección es un CVE mal escrito.** Manda al lector a
otra vulnerabilidad, y la nota queda peor que si no existiera. Se verifica
carácter por carácter contra NVD o el aviso del fabricante, siempre.

## Dónde mirar

Cuatro frentes. Los cuatro se recorren aunque uno ya haya dado material.

| Frente | Qué se busca | Fuentes |
|---|---|---|
| Explotación activa | Vulnerabilidades que se están explotando ahora | Catálogo KEV de CISA, NVD, avisos de fabricantes |
| Ofensiva y hacking ético | Técnicas de conferencias, herramientas nuevas, cambios en certificaciones, bug bounty público | Sitios y repos oficiales, blogs de investigación, actas |
| Fabricantes del catálogo | Cambios de licencia y precio, fin de soporte, adquisiciones, brechas propias | Sala de prensa, páginas de precios y de ciclo de vida |
| LATAM | Incidentes regionales, campañas dirigidas, cambios regulatorios con fecha | CSIRT nacionales, boletines oficiales, informes de quien investigó |

## Cómo se escribe

Plantilla en `content/_plantillas/noticia.md`, con la estructura sugerida y el
checklist previo a publicar.

Lo que distingue una nota de Capa Cero de un titular reciclado:

- **Arranca con el hecho.** El lector quiere saber si le toca. Nada de
  introducción de contexto.
- **Un bloque de "a quién NO le toca".** Es el párrafo que casi nadie escribe
  y el que más confianza genera.
- **Cómo comprobar exposición**, con el comando o el lugar del panel.
- **Qué hacer, en orden**, primero lo que frena el sangrado.
- Segunda persona, frases de largo desparejo, opinión marcada como opinión.
- 1.200 a 1.900 palabras. Es una nota, no una guía.
- 6 a 12 enlaces internos con anchor descriptivo, todos verificados contra
  `php bin/list-urls.php`.

## Verificación — todas tienen que pasar

```bash
php bin/build-content.php          # compila y valida
php tests/run.php                  # suite completa
php bin/audit.php                  # 0 errores, las advertencias se leen
php bin/list-urls.php              # los destinos de los enlaces internos
```

Además, sobre el archivo nuevo:

- `grep -c '^# '` tiene que dar 0 (el H1 es el título del front-matter)
- `file` no puede reportar CRLF
- `meta_title` ≤ 60 y `meta_description` ≤ 158 caracteres
- `/noticias` y `/noticia/{slug}` responden 200
- La nota emite JSON-LD con `"@type": "NewsArticle"`

Si algo falla y no se puede arreglar, **no se publica**. Ver la regla que
define la sección.

## Publicar

```bash
git fetch origin main
git checkout -B claude/noticias-$(date +%Y-%m-%d) origin/main
# escribir, verificar
git add content/articles/{slug}.md
git commit -m "Noticia: ..."
git checkout main && git merge --ff-only claude/noticias-$(date +%Y-%m-%d)
git push -u origin main
```

El push a `main` dispara el CI y, si queda verde, el deploy automático a
Hostinger. Confirmar mirando que `https://capacero.online/healthz` reporte el
commit nuevo.

Si no hay nota que publicar, no se crea rama ni se commitea nada.

## Qué no hacer

- No tocar código, tema, tests ni otros artículos. Una nota es **un** archivo
  en `content/articles/`.
- No republicar un CVE ya cubierto sin novedad sustancial.
- No publicar para cumplir con la frecuencia diaria.
- No inventar una cifra para redondear un párrafo.
