-- =============================================================================
-- 006_article_views_daily.sql
-- Aggregados diarios de pageviews por articulo.
--
-- Motivacion:
--   - articles.views_count ya trackea all-time, pero no sirve para "trending
--     de la semana" porque los articulos viejos siempre dominan.
--   - Esta tabla guarda solo (article_id, day, views) con UPSERT en cada view.
--
-- Retention:
--   - Mantener ~90 dias es suficiente (Security::gc() puede barrer tambien
--     esta tabla si crece demasiado — todavia no lo conectamos).
-- =============================================================================

CREATE TABLE IF NOT EXISTS article_views_daily (
    article_id  INT UNSIGNED NOT NULL,
    day         DATE         NOT NULL,
    views       INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (article_id, day),
    KEY idx_day (day),
    CONSTRAINT fk_avd_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('006_article_views_daily.sql');
