-- =============================================================================
-- 005_security_tables.sql
-- Security hardening:
--   - banned_ips: IPs bloqueadas (automaticamente o manualmente por admin)
--   - ip_whitelist: IPs que NUNCA se banean (oficinas, admins, etc.)
--   - security_events: log unificado de eventos de seguridad para auditoria
-- =============================================================================

-- IPs baneadas (globales, no por site).
-- Guardamos la IP en plano porque es necesario mostrar al admin y comparar
-- en tiempo real. Los "affiliate_clicks" siguen usando hash para GDPR — aca
-- el interés legítimo de seguridad (art 6(1)(f) GDPR) justifica guardar IP.
CREATE TABLE IF NOT EXISTS banned_ips (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ip_address     VARCHAR(45)  NOT NULL,    -- IPv4 (15) o IPv6 (45)
    reason         VARCHAR(255) NULL,
    banned_by      INT UNSIGNED NULL,        -- NULL si fue automatico por RateLimiter
    auto_banned    TINYINT(1)   NOT NULL DEFAULT 0,
    banned_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at     TIMESTAMP    NULL,        -- NULL = permanente
    attempt_count  INT UNSIGNED NOT NULL DEFAULT 0, -- cuantos fails dispararon el ban
    PRIMARY KEY (id),
    UNIQUE KEY uq_banned_ip (ip_address),
    KEY idx_expires (expires_at),
    KEY idx_banned_at (banned_at),
    CONSTRAINT fk_banned_by FOREIGN KEY (banned_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Whitelist: IPs que el middleware NUNCA banea, aunque fallen 100 logins.
-- Util para tu IP fija de casa/oficina, o para tests externos (uptimerobot, etc).
CREATE TABLE IF NOT EXISTS ip_whitelist (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ip_address   VARCHAR(45)  NOT NULL,
    note         VARCHAR(255) NULL,          -- ej "mi casa", "uptime robot", "oficina mx"
    added_by     INT UNSIGNED NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_wl_ip (ip_address),
    CONSTRAINT fk_wl_added_by FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Log de eventos de seguridad para auditoria. Crece, se puede GC >90 dias.
CREATE TABLE IF NOT EXISTS security_events (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_type   ENUM(
        'login_fail',
        'login_success',
        'logout',
        'auto_ban',
        'manual_ban',
        'unban',
        'whitelist_add',
        'whitelist_remove',
        'blocked_request',
        'csrf_fail',
        'suspicious'
    ) NOT NULL,
    ip_address   VARCHAR(45)  NULL,
    user_id      INT UNSIGNED NULL,          -- user afectado si aplica
    email        VARCHAR(191) NULL,          -- email intentado en login_fail
    user_agent   VARCHAR(500) NULL,
    path         VARCHAR(500) NULL,          -- URL del request
    details      TEXT         NULL,          -- JSON con contexto extra (opcional)
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_type_time    (event_type, created_at),
    KEY idx_ip_time      (ip_address, created_at),
    KEY idx_user_time    (user_id, created_at),
    CONSTRAINT fk_se_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('005_security_tables.sql');
