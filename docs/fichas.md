# Fichas de producto — proceso semanal

Documento autónomo. La tarea programada arranca en una sesión nueva, sin
memoria de las anteriores: todo lo que hay que saber está acá.

Se lee junto con `docs/seo-geo.md` y `docs/estilo.md`.

## Qué es esta tarea

Las 25 fichas de `/producto/{slug}` publican **precio, planes, features y
límites**. Es el contenido del sitio que más rápido se vuelve falso: un
fabricante cambia el precio y la ficha sigue diciendo lo de antes, sin que
nada avise.

Esta tarea no escribe páginas nuevas. **Verifica las que hay contra la página
oficial del fabricante y corrige lo que cambió.** Una ficha con el precio
correcto vale más que una comparativa nueva, porque el precio es exactamente
el dato que el lector vino a buscar y el que un modelo va a citar.

## La regla que define la tarea

**Un dato que no se pudo verificar contra el fabricante no se toca.**

No se ajusta "para que quede razonable", no se estima, no se copia de un
comparador de terceros. Si la página de precios no carga o el fabricante dejó
de publicar el número, eso se refleja en la ficha como lo que es: un precio
que ya no es público.

Terminar la corrida con "verifiqué cuatro fichas, ninguna había cambiado" es
un resultado correcto y frecuente. No se commitea nada y no se mueve ninguna
fecha.

## Elegir sobre qué trabajar

```bash
php bin/staleness.php --seccion=fichas --top=6
```

Sale ordenado por urgencia. El puntaje pesa más el precio cuanto más viejo
—una ficha con precio de hace cuatro meses es un pasivo— y suma las fichas
huérfanas, las de descripción corta y las de meta fuera de rango.

**Se toman las 3 o 4 primeras.** Más que eso no se verifica bien en una
corrida.

## Qué se verifica, en orden

Para cada ficha, contra **la página oficial del fabricante**, nunca un
revendedor ni un comparador:

1. **Precio y moneda.** El plan que la ficha declara en `price_from`. Ojo con
   el tramo: muchos fabricantes muestran el precio anual dividido por doce y
   otro más alto si pagás mes a mes. La ficha dice `pricing_model: monthly`,
   así que va el precio mensual real de ese plan.
2. **Nombre del plan.** Los fabricantes renombran planes seguido. Un precio
   correcto de un plan que ya no se llama así confunde igual.
3. **Features que aparecen en `features`.** Si una pasó a un plan superior,
   deja de ser cierta para este. Es el cambio que más veces pasa sin que
   cambie el precio.
4. **Límites y mínimos.** Cantidad mínima de usuarios, tope de dispositivos,
   retención incluida.
5. **Que el producto siga existiendo.** Fin de venta, absorción por otro
   producto, cambio de nombre de la marca.

Lo que **no** se toca en esta tarea: `rating`, `pros` y `cons`. Son juicio
editorial del sitio, no dato del fabricante. Se corrigen solo si el cambio de
producto los volvió falsos —una contra que decía "sin SSO" y ahora tiene SSO.

## Precios: cómo se escriben

Todo precio lleva **el número, el plan, la unidad, de dónde salió y cuándo se
miró**. Es la forma en que un modelo lo puede citar sin mentir.

- ✗ *Cuesta USD 7,99.*
- ✓ *El plan Business cuesta **USD 7,99 por usuario/mes** con facturación
  anual, según la [página de precios de 1Password](https://1password.com/business-pricing)
  consultada el 30 de agosto de 2026.*

Cuando el fabricante no publica precio:

> El fabricante no publica precio de lista para este plan: la contratación pasa
> por su equipo comercial. Verificado el 30 de agosto de 2026.

Eso es información útil y honesta. Un número inventado para llenar el campo es
lo peor que puede tener la ficha.

Los precios en dólares se dejan en dólares. No se convierte a moneda local: la
cotización cambia todos los días y la conversión envejece peor que el precio.

## Qué se edita en el archivo

Las fichas viven en `content/products/{slug}.md`. Los campos que toca esta
tarea:

| Campo | Cuándo |
|---|---|
| `price_from`, `price_currency`, `pricing_model` | Cambió el precio |
| `features` | Una feature se movió de plan o apareció |
| `description_short` | Quedó desactualizada respecto de lo anterior |
| `description_long` (cuerpo) | Donde estén los datos que cambiaron |
| `meta_description` | Si pasa de 158 caracteres o quedó falsa |
| `updated` | **Solo si cambió algo real** |

`updated` es la parte donde se puede hacer daño. Ver `docs/seo-geo.md`,
sección Fechas: se movió el contenido, se mueve la fecha; no se movió, no se
mueve. Una ficha verificada y sin cambios se deja exactamente como estaba.

## Enlaces de afiliado

**El único programa activo es Hostinger.** Ninguna otra ficha lleva enlace de
afiliado ni afirma nada sobre la relación comercial con el fabricante, ni a
favor ni en contra. Si una verificación tienta a agregar un enlace de
afiliado nuevo, no se agrega: eso lo decide el dueño del sitio, no la tarea.

## Verificación

```bash
php bin/build-content.php          # compila y valida
php tests/run.php                  # suite completa
php bin/audit.php                  # 0 errores, las advertencias se leen
php bin/staleness.php --seccion=fichas --top=6   # confirmar que bajó el puntaje
```

Además, sobre cada ficha tocada:

- `/producto/{slug}` responde 200
- Emite JSON-LD con `"@type": "Product"`, y el `Offer` refleja el precio nuevo
- `meta_description` ≤ 158 caracteres
- `file` no reporta CRLF

Si algo falla y no se puede arreglar, no se publica esa ficha. Las otras de la
tanda sí, si están bien.

## Publicar

```bash
git fetch origin main
git checkout -B claude/fichas-$(date +%Y-%m-%d) origin/main
# verificar, corregir
git add content/products/
git commit -m "Fichas: verificación de precios y planes"
git checkout main && git merge --ff-only claude/fichas-$(date +%Y-%m-%d)
git push -u origin main
```

El push dispara CI y deploy. Confirmar que `https://capacero.online/healthz`
reporte el commit nuevo.

Si ninguna ficha cambió, no se crea rama ni se commitea nada.

## Qué no hacer

- No inventar ni estimar un precio.
- No tocar código, tema, tests ni artículos. Esta tarea escribe en
  `content/products/` y en ningún otro lado.
- No mover `updated` de una ficha que no cambió.
- No reescribir la descripción "para que quede mejor" sin un cambio de dato
  atrás.
- No agregar enlaces de afiliado.
- No verificar diez fichas por corrida a costa de mirarlas por arriba. Tres
  bien miradas valen más.
