-- =============================================================================
-- 010_content_files.sql
-- Tracking de archivos markdown importados desde content/.
--
-- Motivacion:
--   El contenido vive en el repo como .md (versionado, diffeable, reviewable).
--   bin/import-content.php lo sincroniza a la tabla articles en cada deploy.
--   Esta tabla guarda el hash de cada archivo para que el import sea
--   idempotente: si el .md no cambio, no se toca la fila de articles
--   (evita bumpear updated_at y ensuciar el lastmod del sitemap).
-- =============================================================================

CREATE TABLE IF NOT EXISTS content_files (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id      INT UNSIGNED NOT NULL,
    path         VARCHAR(500) NOT NULL,   -- relativo a APP_ROOT, ej: content/capacero.online/articulos/x.md
    content_hash CHAR(64)     NOT NULL,   -- sha256 del archivo completo
    article_id   INT UNSIGNED NULL,       -- fila de articles que genero
    imported_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cf_site_path (site_id, path),
    KEY idx_cf_article (article_id),
    CONSTRAINT fk_cf_site    FOREIGN KEY (site_id)    REFERENCES sites(id)    ON DELETE CASCADE,
    CONSTRAINT fk_cf_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('010_content_files.sql');
