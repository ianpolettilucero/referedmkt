-- =============================================================================
-- 003_uploads_search_rate_limit.sql
--   - tabla uploads (imagenes subidas desde el admin)
--   - tabla login_attempts (rate limit del login)
--   - FULLTEXT en products (busqueda interna)
-- =============================================================================

-- uploads ----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS uploads (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id       INT UNSIGNED NOT NULL,
    uploaded_by   INT UNSIGNED NULL,
    original_name VARCHAR(255) NOT NULL,
    filename      VARCHAR(255) NOT NULL,
    path          VARCHAR(500) NOT NULL,
    mime_type     VARCHAR(100) NOT NULL,
    size_bytes    INT UNSIGNED NOT NULL,
    width         SMALLINT UNSIGNED NULL,
    height        SMALLINT UNSIGNED NULL,
    alt_text      VARCHAR(255) NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_uploads_site_path (site_id, path),
    KEY idx_uploads_site_created (site_id, created_at),
    CONSTRAINT fk_uploads_site FOREIGN KEY (site_id)     REFERENCES sites(id) ON DELETE CASCADE,
    CONSTRAINT fk_uploads_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- login_attempts ---------------------------------------------------------------
-- Guarda intentos fallidos por (ip_hash, email) para rate-limiting.
CREATE TABLE IF NOT EXISTS login_attempts (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    ip_hash      CHAR(64)     NOT NULL,
    email        VARCHAR(191) NULL,
    successful   TINYINT(1)   NOT NULL DEFAULT 0,
    attempted_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_la_ip_time    (ip_hash, attempted_at),
    KEY idx_la_email_time (email, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- products fulltext ------------------------------------------------------------
ALTER TABLE products
    ADD FULLTEXT KEY ft_products_search (name, brand, description_short);

INSERT INTO migrations (filename) VALUES ('003_uploads_search_rate_limit.sql');
