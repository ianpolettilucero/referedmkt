-- =============================================================================
-- 004_sites_gtm.sql
-- Agrega Google Tag Manager por sitio. GA4 (google_analytics_id) y GSC
-- (google_search_console_verification) ya existian desde la 001.
-- =============================================================================

ALTER TABLE sites
    ADD COLUMN google_tag_manager_id VARCHAR(50) NULL AFTER google_search_console_verification;

INSERT INTO migrations (filename) VALUES ('004_sites_gtm.sql');
