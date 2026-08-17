---
# ---------------------------------------------------------------------------
# PLANTILLA DE NOTICIA. Copiar a content/articles/{slug}.md y completar.
# El nombre del archivo es la URL: articles/mi-nota.md -> /noticia/mi-nota
#
# El proceso completo, incluido el criterio para NO publicar, esta en
# docs/noticias.md. Leelo antes de escribir la primera.
# ---------------------------------------------------------------------------

# Maximo 60 caracteres CONTANDO " | Capa Cero" (12). O sea: 48 propios.
# En una noticia, el titulo dice QUE PASO, no de que trata la nota.
title: "Qué pasó, en 48 caracteres"

# La segunda linea del titular: agrega el dato duro que no entro arriba.
subtitle: El número, la versión afectada o el plazo. Concreto.

# Maximo 158 caracteres. Tiene que responder "¿me toca a mí?".
excerpt: A quién afecta, desde qué versión, y qué hay que hacer.

type: news
status: draft         # published cuando este verificada
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-01-01
updated: 2026-01-01

# Productos del catalogo que la nota toca. Sale la grilla al pie.
products: []

meta_title: "Máximo 60 caracteres"
meta_description: "Máximo 158. Qué pasó, a quién afecta y qué hacer."
---

<!--
ESTRUCTURA SUGERIDA. No es obligatoria, pero cubre lo que mide la auditoria.

## Qué pasó
El hecho, con la fuente primaria enlazada en la primera oración. Si hay CVE,
va completo (CVE-2026-12345) y enlazado a NVD o al aviso del fabricante la
primera vez que aparece.

## A quién le toca
Versiones afectadas y versiones corregidas, en tabla si son más de dos. Este
es el bloque que la gente vino a leer: que pueda responder "sí/no" en diez
segundos.

## A quién NO le toca
El párrafo que casi nadie escribe y el que más confianza genera. Decilo
explícitamente: si corrés X, esto no te aplica.

## Cómo saber si estás expuesto
El comando, la consulta o el lugar del panel donde mirarlo. Enfoque
defensivo: comprobación, nunca explotación.

## Qué hacer, en orden
Lista numerada. Primero lo que frena el sangrado, después lo estructural.
Acá es donde enlazan las guías del sitio.

## Preguntas frecuentes
Tres a cinco preguntas como ###. Emite FAQPage automático.

---------------------------------------------------------------------------
CHECKLIST ANTES DE PUBLICAR

[ ] Cada CVE verificado carácter por carácter contra NVD o el aviso oficial.
    Un CVE mal escrito manda al lector a otra vulnerabilidad.
[ ] Cada CVSS dice de dónde sale: NVD y el fabricante suelen diferir.
[ ] Cada fecha, versión afectada y versión corregida, contra fuente primaria.
[ ] Ninguna fuente citada sin haberla abierto.
[ ] Cero experiencia propia: nadie probó, compró ni explotó nada.
[ ] Cero instrucciones de explotación.
[ ] Lo que no se pudo confirmar se dice, no se completa con lo razonable.
[ ] 6 a 12 enlaces internos, todos verificados con php bin/list-urls.php.
[ ] Ningún "# " al principio de línea fuera de bloques de código.
[ ] 1.200 a 1.900 palabras. Es una nota, no una guía.
[ ] php bin/build-content.php pasa.
[ ] php bin/audit.php da 0 errores.
-->
