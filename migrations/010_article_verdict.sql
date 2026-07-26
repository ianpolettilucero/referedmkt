-- =============================================================================
-- 010_article_verdict.sql
-- Veredicto editorial propio del articulo, independiente del producto.
--
-- Por que:
--   - Hasta ahora rating/pros/cons vivian solo en products. Una resena no
--     tenia score propio: heredaba el del producto o no mostraba nada.
--   - schema.org Review exige un reviewRating con valor concreto. Sin nota
--     propia del articulo no hay Review valido y Google descarta el markup.
--
-- Columnas:
--   rating  : nota editorial 0-5 (misma escala que products.rating).
--   verdict : parrafo de cierre — para quien si, para quien no.
--   pros    : JSON array de strings.
--   cons    : JSON array de strings.
--
-- Aplican a cualquier tipo de articulo, pero el uso natural es review y
-- comparison. Una guia normalmente los deja en NULL.
-- =============================================================================

ALTER TABLE articles
    ADD COLUMN rating  DECIMAL(3,2) NULL AFTER article_type,
    ADD COLUMN verdict TEXT         NULL AFTER rating,
    ADD COLUMN pros    JSON         NULL AFTER verdict,
    ADD COLUMN cons    JSON         NULL AFTER pros,
    ADD CONSTRAINT chk_articles_rating CHECK (rating IS NULL OR (rating >= 0 AND rating <= 5));

INSERT INTO migrations (filename) VALUES ('010_article_verdict.sql');
