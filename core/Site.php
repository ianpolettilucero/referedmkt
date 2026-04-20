<?php
namespace Core;

/**
 * Tenant resolver.
 *
 * Resuelve el sitio activo a partir del host HTTP. Todas las consultas
 * tenant-scoped deben usar Site::current()->id como filtro obligatorio.
 *
 * Flujo:
 *   1. Normaliza el host (lowercase, sin puerto, sin "www.").
 *   2. Busca en tabla `sites` por `domain`.
 *   3. Fallback: override por env (DEV_SITE_DOMAIN) util para dev local.
 *   4. Si no hay match: 404 tenant (no revelar sitios existentes).
 */
final class Site
{
    private static ?self $current = null;

    public int    $id;
    public string $domain;
    public string $name;
    public string $slug;
    public string $themeName;
    public ?string $primaryColor;
    public ?string $logoUrl;
    public ?string $faviconUrl;
    public ?string $affiliateDisclosureText;
    public ?string $googleAnalyticsId;
    public ?string $googleSearchConsoleVerification;
    public ?string $googleTagManagerId;
    public string $defaultLanguage;
    public string $defaultCountry;
    public ?string $metaTitleTemplate;
    public ?string $metaDescriptionTemplate;
    public bool   $active;

    private function __construct(array $row)
    {
        $this->id                              = (int)$row['id'];
        $this->domain                          = $row['domain'];
        $this->name                            = $row['name'];
        $this->slug                            = $row['slug'];
        $this->themeName                       = $row['theme_name'] ?: 'default';
        $this->primaryColor                    = $row['primary_color'];
        $this->logoUrl                         = $row['logo_url'];
        $this->faviconUrl                      = $row['favicon_url'];
        $this->affiliateDisclosureText         = $row['affiliate_disclosure_text'];
        $this->googleAnalyticsId               = $row['google_analytics_id'];
        $this->googleSearchConsoleVerification = $row['google_search_console_verification'];
        $this->googleTagManagerId              = $row['google_tag_manager_id'] ?? null;
        $this->defaultLanguage                 = $row['default_language'] ?: 'es';
        $this->defaultCountry                  = $row['default_country'] ?: 'AR';
        $this->metaTitleTemplate               = $row['meta_title_template'];
        $this->metaDescriptionTemplate         = $row['meta_description_template'];
        $this->active                          = (bool)$row['active'];
    }

    /**
     * Normaliza un host HTTP: lowercase, strip de puerto y de "www.".
     */
    public static function normalizeHost(string $host): string
    {
        $host = strtolower(trim($host));
        // strip port
        if (($pos = strpos($host, ':')) !== false) {
            $host = substr($host, 0, $pos);
        }
        // strip leading www.
        if (strncmp($host, 'www.', 4) === 0) {
            $host = substr($host, 4);
        }
        return $host;
    }

    /**
     * Resuelve el sitio activo y lo cachea. Si no hay match, devuelve null
     * para que el caller decida como responder (usualmente 404 a nivel de router).
     */
    public static function resolve(?string $hostOverride = null): ?self
    {
        if (self::$current !== null) {
            return self::$current;
        }

        $host = $hostOverride
            ?? getenv('DEV_SITE_DOMAIN')
            ?: ($_SERVER['HTTP_HOST'] ?? '');

        $host = self::normalizeHost($host);
        if ($host === '') {
            return null;
        }

        $row = Database::instance()->fetch(
            'SELECT * FROM sites WHERE domain = :domain AND active = 1 LIMIT 1',
            ['domain' => $host]
        );

        if (!$row) {
            return null;
        }

        self::$current = new self($row);
        return self::$current;
    }

    public static function current(): self
    {
        if (self::$current === null) {
            throw new \RuntimeException('No active site. Call Site::resolve() first.');
        }
        return self::$current;
    }

    public static function hasCurrent(): bool
    {
        return self::$current !== null;
    }

    /**
     * Reset util para tests. No usar en request real.
     */
    public static function reset(): void
    {
        self::$current = null;
    }

    public function themePath(): string
    {
        return APP_ROOT . '/themes/' . $this->themeName;
    }
}
