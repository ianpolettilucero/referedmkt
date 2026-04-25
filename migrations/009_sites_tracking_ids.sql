-- =============================================================================
-- 009_sites_tracking_ids.sql
-- Agrega columnas para nuevos tracking IDs:
--   - google_ads_id: tag AW-XXXXXXXXX (Google Ads conversiones / remarketing)
--   - microsoft_clarity_id: ID corto (10 chars) de Microsoft Clarity
--   - meta_pixel_id: ID numerico del pixel de Facebook/Instagram (opcional)
-- =============================================================================

ALTER TABLE sites
    ADD COLUMN google_ads_id        VARCHAR(50) NULL AFTER google_tag_manager_id,
    ADD COLUMN microsoft_clarity_id VARCHAR(50) NULL AFTER google_ads_id,
    ADD COLUMN meta_pixel_id        VARCHAR(50) NULL AFTER microsoft_clarity_id;

INSERT INTO migrations (filename) VALUES ('009_sites_tracking_ids.sql');
