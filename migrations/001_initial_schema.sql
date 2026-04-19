-- =============================================================================
-- Multi-tenant affiliate platform - Initial schema
-- Target: MySQL 8.0+  /  Engine: InnoDB  /  Charset: utf8mb4 (utf8mb4_0900_ai_ci)
-- =============================================================================
-- Convenciones:
--   - Toda tabla tenant-scoped lleva site_id y FK ON DELETE CASCADE a sites.
--   - Slugs siempre unicos por (site_id, slug).
--   - Timestamps en UTC (TIMESTAMP) con defaults.
--   - JSON nativo de MySQL 8 para arrays/objects estructurados.
--   - IP del visitante se almacena como CHAR(64) (hash SHA-256 + salt) para GDPR.
-- =============================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------------------------------
-- sites : tenant principal. El resto del sistema pivota sobre site_id.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sites (
    id                                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    domain                            VARCHAR(191) NOT NULL,
    name                              VARCHAR(150) NOT NULL,
    slug                              VARCHAR(80)  NOT NULL,
    theme_name                        VARCHAR(80)  NOT NULL DEFAULT 'default',
    primary_color                     CHAR(7)      NULL,
    logo_url                          VARCHAR(500) NULL,
    favicon_url                       VARCHAR(500) NULL,
    affiliate_disclosure_text         TEXT         NULL,
    google_analytics_id               VARCHAR(50)  NULL,
    google_search_console_verification VARCHAR(191) NULL,
    default_language                  CHAR(5)      NOT NULL DEFAULT 'es',
    default_country                   CHAR(2)      NOT NULL DEFAULT 'AR',
    meta_title_template               VARCHAR(255) NULL,
    meta_description_template         VARCHAR(500) NULL,
    active                            TINYINT(1)   NOT NULL DEFAULT 1,
    created_at                        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_sites_domain (domain),
    UNIQUE KEY uq_sites_slug   (slug),
    KEY idx_sites_active       (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- users : admins del panel (un panel unificado, usuarios globales).
-- Se mantiene aparte de sites para permitir un admin gestionando varios tenants.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email           VARCHAR(191) NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    name            VARCHAR(150) NOT NULL,
    role            ENUM('superadmin','admin','editor') NOT NULL DEFAULT 'admin',
    active          TINYINT(1)   NOT NULL DEFAULT 1,
    last_login_at   TIMESTAMP    NULL,
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- user_site_access : pivot opcional para acotar editores a sitios especificos.
-- superadmin ignora esta tabla (acceso total).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_site_access (
    user_id    INT UNSIGNED NOT NULL,
    site_id    INT UNSIGNED NOT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, site_id),
    CONSTRAINT fk_usa_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_usa_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- authors : firmas editoriales publicas por sitio (EEAT / trust signals).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS authors (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id       INT UNSIGNED NOT NULL,
    name          VARCHAR(150) NOT NULL,
    slug          VARCHAR(150) NOT NULL,
    bio           TEXT         NULL,
    avatar_url    VARCHAR(500) NULL,
    social_links  JSON         NULL,
    expertise     VARCHAR(255) NULL,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_authors_site_slug (site_id, slug),
    CONSTRAINT fk_authors_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- categories : jerarquia opcional (parent_id). Una categoria por sitio.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id          INT UNSIGNED NOT NULL,
    parent_id        INT UNSIGNED NULL,
    slug             VARCHAR(150) NOT NULL,
    name             VARCHAR(150) NOT NULL,
    description      TEXT         NULL,
    sort_order       INT          NOT NULL DEFAULT 0,
    meta_title       VARCHAR(255) NULL,
    meta_description VARCHAR(500) NULL,
    featured_image   VARCHAR(500) NULL,
    created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_site_slug (site_id, slug),
    KEY idx_categories_parent (parent_id),
    KEY idx_categories_site_sort (site_id, sort_order),
    CONSTRAINT fk_categories_site   FOREIGN KEY (site_id)   REFERENCES sites(id)      ON DELETE CASCADE,
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- affiliate_links : destinos trackeados via /go/{tracking_slug}.
-- Se define ANTES de products/articles porque ambos pueden referenciar FK.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS affiliate_links (
    id                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id              INT UNSIGNED NOT NULL,
    name                 VARCHAR(200) NOT NULL,
    destination_url      VARCHAR(2000) NOT NULL,
    tracking_slug        VARCHAR(120) NOT NULL,
    network_name         VARCHAR(100) NULL,
    commission_structure TEXT         NULL,
    notes                TEXT         NULL,
    active               TINYINT(1)   NOT NULL DEFAULT 1,
    created_at           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_aff_site_slug (site_id, tracking_slug),
    KEY idx_aff_active (site_id, active),
    CONSTRAINT fk_aff_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- products : catalogo filtrable.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id            INT UNSIGNED NOT NULL,
    category_id        INT UNSIGNED NULL,
    affiliate_link_id  INT UNSIGNED NULL,
    slug               VARCHAR(180) NOT NULL,
    name               VARCHAR(200) NOT NULL,
    brand              VARCHAR(150) NULL,
    description_short  VARCHAR(500) NULL,
    description_long   MEDIUMTEXT   NULL,
    logo_url           VARCHAR(500) NULL,
    rating             DECIMAL(3,2) NULL,
    price_from         DECIMAL(12,2) NULL,
    price_currency     CHAR(3)      NULL,
    pricing_model      ENUM('one_time','monthly','yearly','free','custom') NOT NULL DEFAULT 'custom',
    features           JSON         NULL,
    pros               JSON         NULL,
    cons               JSON         NULL,
    specs              JSON         NULL,
    meta_title         VARCHAR(255) NULL,
    meta_description   VARCHAR(500) NULL,
    featured           TINYINT(1)   NOT NULL DEFAULT 0,
    created_at         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_products_site_slug (site_id, slug),
    KEY idx_products_site_category (site_id, category_id),
    KEY idx_products_featured (site_id, featured),
    KEY idx_products_rating (site_id, rating),
    KEY idx_products_aff_link (affiliate_link_id),
    CONSTRAINT fk_products_site     FOREIGN KEY (site_id)           REFERENCES sites(id)           ON DELETE CASCADE,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)       REFERENCES categories(id)      ON DELETE SET NULL,
    CONSTRAINT fk_products_aff      FOREIGN KEY (affiliate_link_id) REFERENCES affiliate_links(id) ON DELETE SET NULL,
    CONSTRAINT chk_products_rating  CHECK (rating IS NULL OR (rating >= 0 AND rating <= 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- articles : reviews, comparativas, guias, noticias. Markdown en content.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS articles (
    id                  INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id             INT UNSIGNED NOT NULL,
    category_id         INT UNSIGNED NULL,
    author_id           INT UNSIGNED NULL,
    slug                VARCHAR(200) NOT NULL,
    title               VARCHAR(255) NOT NULL,
    subtitle            VARCHAR(300) NULL,
    excerpt             VARCHAR(500) NULL,
    content             MEDIUMTEXT   NOT NULL,
    featured_image      VARCHAR(500) NULL,
    article_type        ENUM('review','comparison','guide','news') NOT NULL DEFAULT 'guide',
    related_product_ids JSON         NULL,
    meta_title          VARCHAR(255) NULL,
    meta_description    VARCHAR(500) NULL,
    status              ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
    published_at        TIMESTAMP    NULL,
    views_count         INT UNSIGNED NOT NULL DEFAULT 0,
    created_at          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_articles_site_slug (site_id, slug),
    KEY idx_articles_status_pub    (site_id, status, published_at),
    KEY idx_articles_type          (site_id, article_type, status),
    KEY idx_articles_category      (site_id, category_id, status),
    KEY idx_articles_author        (author_id),
    FULLTEXT KEY ft_articles_title_excerpt (title, excerpt),
    CONSTRAINT fk_articles_site     FOREIGN KEY (site_id)     REFERENCES sites(id)      ON DELETE CASCADE,
    CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_articles_author   FOREIGN KEY (author_id)   REFERENCES authors(id)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- affiliate_clicks : log por click. Particionable por fecha si crece mucho.
-- user_ip_hash = sha256(ip + APP_SALT) -> sin IP plana (GDPR-friendly).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS affiliate_clicks (
    id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    affiliate_link_id  INT UNSIGNED    NOT NULL,
    article_id         INT UNSIGNED    NULL,
    product_id         INT UNSIGNED    NULL,
    user_ip_hash       CHAR(64)        NULL,
    user_agent         VARCHAR(500)    NULL,
    referer            VARCHAR(1000)   NULL,
    country            CHAR(2)         NULL,
    clicked_at         TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_clicks_link_time (affiliate_link_id, clicked_at),
    KEY idx_clicks_article   (article_id),
    KEY idx_clicks_product   (product_id),
    KEY idx_clicks_time      (clicked_at),
    CONSTRAINT fk_clicks_link    FOREIGN KEY (affiliate_link_id) REFERENCES affiliate_links(id) ON DELETE CASCADE,
    CONSTRAINT fk_clicks_article FOREIGN KEY (article_id)        REFERENCES articles(id)        ON DELETE SET NULL,
    CONSTRAINT fk_clicks_product FOREIGN KEY (product_id)        REFERENCES products(id)        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- redirects : gestion de cambios de URL sin perder SEO.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS redirects (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id     INT UNSIGNED NOT NULL,
    from_path   VARCHAR(500) NOT NULL,
    to_path     VARCHAR(500) NOT NULL,
    status_code SMALLINT     NOT NULL DEFAULT 301,
    active      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_redirects_site_from (site_id, from_path),
    KEY idx_redirects_active (site_id, active),
    CONSTRAINT fk_redirects_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE,
    CONSTRAINT chk_redirects_status CHECK (status_code IN (301, 302, 307, 308))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- settings : KV flexible por sitio (feature flags, ids externos, textos sueltos).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    site_id    INT UNSIGNED NOT NULL,
    `key`      VARCHAR(100) NOT NULL,
    `value`    TEXT         NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_settings_site_key (site_id, `key`),
    CONSTRAINT fk_settings_site FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- -----------------------------------------------------------------------------
-- migrations : registro de scripts aplicados (control de version del schema).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS migrations (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    filename    VARCHAR(191) NOT NULL,
    applied_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_migrations_filename (filename)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO migrations (filename) VALUES ('001_initial_schema.sql');
