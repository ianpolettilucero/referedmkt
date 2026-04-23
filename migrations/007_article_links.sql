-- =============================================================================
-- 007_article_links.sql
-- Health check de links EXTERNOS dentro del contenido de articulos.
--
-- Motivacion:
--   - Los links afiliados /go/ ya se chequean en /admin/affiliate-links/health.
--   - Esta tabla cubre los OTROS links externos que el autor pone en el
--     contenido (fuentes, referencias, sitios oficiales, docs, etc.).
--   - Cache de resultados: evita re-chequear la misma URL todo el tiempo y
--     preserva historial ("roto desde hace 5 dias").
--
-- Status code interpretacion:
--   - 200-399: OK (clear broken state)
--   - 404/410 o 5xx: broken
--   - 403/429: sospechoso (algunos vendors bloquean bots; lo separamos visual)
--   - NULL: no se pudo conectar (DNS, SSL, timeout)
-- =============================================================================

CREATE TABLE IF NOT EXISTS article_links (
    id                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    article_id           INT UNSIGNED    NOT NULL,
    url                  VARCHAR(2048)   NOT NULL,
    url_hash             CHAR(64)        NOT NULL,       -- sha256(url) para unique index
    status_code          SMALLINT        NULL,           -- null = no se pudo conectar
    final_url            VARCHAR(2048)   NULL,           -- si hubo redirect, URL final
    error_message        VARCHAR(255)    NULL,
    last_checked_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    first_seen_broken_at TIMESTAMP       NULL,           -- se limpia cuando el link se recupera
    ignored_at           TIMESTAMP       NULL,           -- "marcar como OK" manual (falso positivo)
    created_at           TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_article_hash (article_id, url_hash),
    KEY idx_status_ignored (status_code, ignored_at),
    KEY idx_checked (last_checked_at),
    CONSTRAINT fk_al_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('007_article_links.sql');
