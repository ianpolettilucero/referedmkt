-- =============================================================================
-- 008_index_status.sql
-- Tracking de estado de indexacion en Google Search Console por URL.
--
-- Se consulta la GSC URL Inspection API y se cachean los resultados
-- para poder renderizar un panel de "paginas no indexadas" sin hammer la API
-- (quota 2000 req/dia por propiedad).
--
-- verdict values (oficiales GSC):
--   PASS, PARTIAL, NEUTRAL, FAIL, VERDICT_UNSPECIFIED
-- coverageState ejemplos:
--   "Submitted and indexed", "Crawled - currently not indexed",
--   "Discovered - currently not indexed", "Page with redirect",
--   "Excluded by 'noindex' tag", "Blocked by robots.txt", etc.
-- =============================================================================

CREATE TABLE IF NOT EXISTS index_status (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id          INT UNSIGNED    NOT NULL,
    url              VARCHAR(2048)   NOT NULL,
    url_hash         CHAR(64)        NOT NULL,
    verdict          VARCHAR(32)     NULL,   -- PASS / PARTIAL / NEUTRAL / FAIL / VERDICT_UNSPECIFIED
    coverage_state   VARCHAR(200)    NULL,   -- mensaje humano del estado de indexacion
    indexing_state   VARCHAR(100)    NULL,   -- INDEXING_ALLOWED / BLOCKED_BY_META_TAG / etc.
    robots_txt_state VARCHAR(50)     NULL,   -- ALLOWED / DISALLOWED / ROBOTS_TXT_STATE_UNSPECIFIED
    page_fetch_state VARCHAR(50)     NULL,   -- SUCCESSFUL / SOFT_404 / BLOCKED_ROBOTS_TXT / NOT_FOUND / etc.
    google_canonical VARCHAR(2048)   NULL,
    user_canonical   VARCHAR(2048)   NULL,
    last_crawl_time  TIMESTAMP       NULL,
    error_message    VARCHAR(500)    NULL,   -- mensaje si fallo la consulta a la API
    last_checked_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_site_url (site_id, url_hash),
    KEY idx_verdict (site_id, verdict),
    KEY idx_checked (last_checked_at),
    CONSTRAINT fk_idxst_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('008_index_status.sql');
