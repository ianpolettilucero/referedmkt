---
# ---------------------------------------------------------------------------
# PLANTILLA DE GUIA. Copiar a content/articles/{slug}.md y completar.
# El nombre del archivo es la URL: articles/mi-guia.md -> /guia/mi-guia
# ---------------------------------------------------------------------------

# Maximo 60 caracteres CONTANDO " | Capa Cero" (12). O sea: 48 propios.
# Si el titulo propio pasa de 60, el sitio suelta la marca automaticamente.
title: "Título de hasta 48 caracteres"

# Aparece bajo el H1. Opcional, pero ayuda a enganchar.
subtitle: Una línea que amplíe el título sin repetirlo.

# Meta description. Maximo 158 caracteres: arriba de eso Google lo corta.
# Debe prometer algo concreto, no describir el articulo.
excerpt: Qué se lleva el lector, en concreto y con un dato si se puede.

type: guide           # guide | review | comparison | news
status: draft         # draft mientras se escribe; published para publicar
category: fundamentos-y-educacion
author: ian-poletti-lucero
published: 2026-01-01
# updated: 2026-06-01   # descomentar al revisar: muestra "Actualizado el X"

# Productos que se mencionan. Habilita la grilla al pie y los links de afiliado.
products: []

# Solo para type: review o comparison.
# rating: 4.5
# verdict: |
#   Para quién sí y para quién no, en dos o tres frases.
# pros:
#   - Ventaja concreta y verificable
# cons:
#   - Limitación real, no un elogio disfrazado
---

<!--
ESTRUCTURA. Cada punto sale de un hallazgo medido por bin/audit.php.

1. RESPUESTA PRIMERO. Las primeras 60 palabras responden la pregunta del
   titulo, con al menos un dato numerico. Los asistentes de IA citan el pasaje
   que se sostiene solo; si arranca con "En el mundo actual de la
   ciberseguridad...", no hay nada citable.

2. UN SOLO H1: lo pone la plantilla desde `title`. Acá se arranca en `##`.
   El build falla si aparece un `#` en el cuerpo.

3. AL MENOS 5 DATOS CUANTIFICADOS: precios, porcentajes, plazos, tamaños.
   Con fuente y año cuando sea un dato de terceros.

4. LISTAS Y TABLAS. Una comparativa en tabla se extrae limpia; la misma
   informacion en prosa corrida, no.

5. MINIMO 900 PALABRAS para competir en una query informacional. Las guias
   pilar del sitio andan en 2500-4000.

6. ENLAZAR A 2+ ARTICULOS del sitio dentro del texto. Sin eso el articulo no
   forma cluster y queda aislado.

7. CERRAR CON "## Preguntas frecuentes" y cada pregunta en `###`. Se convierte
   solo en schema FAQPage.

Antes de publicar: php bin/audit.php
-->

El dato que responde la pregunta del título va acá, en la primera frase.

## Primera sección

Texto.

## Preguntas frecuentes

### ¿Pregunta que la gente realmente busca?

Respuesta directa en la primera oración. Después el detalle.

### ¿Segunda pregunta?

Ídem.
