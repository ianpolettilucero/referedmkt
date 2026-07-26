<?php
namespace Core;

/**
 * Generador de meta tags + JSON-LD schema.org.
 *
 * Uso en un controller:
 *   $seo = new SEO($site);
 *   $seo->title('Mejor antivirus para PyMEs')
 *       ->description('...')
 *       ->canonical('/producto/bitdefender')
 *       ->breadcrumb([['Inicio','/'], ['Productos','/productos']])
 *       ->schemaProduct($product, $review)
 *       ->schemaArticle($article);
 *
 * En el layout:
 *   <?= $seo->renderHead() ?>
 */
final class SEO
{
    private Site $site;

    private ?string $title = null;
    private ?string $description = null;
    private ?string $canonical = null;
    private ?string $ogImage = null;
    private string $ogType = 'website';
    private ?string $ogLocaleValue = null;
    private ?string $robots = null;

    /** @var array<int, array{0:string,1:string}> */
    private array $breadcrumb = [];

    /** @var array<int, array<string, mixed>> */
    private array $jsonLd = [];

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function title(string $title): self
    {
        $title = trim($title);
        $siteName = trim($this->site->name);

        // Si el title coincide con el nombre del sitio (caso tipico: home),
        // no aplicamos template para evitar "Ciberseguridad | Ciberseguridad".
        if ($title !== '' && strcasecmp($title, $siteName) === 0) {
            $this->title = $title;
            return $this;
        }

        $template = $this->site->metaTitleTemplate ?: '{title} | ' . $siteName;

        // Un template sin el placeholder {title} daria el MISMO <title> en todas
        // las paginas del sitio — de los peores problemas de SEO on-page que hay.
        // Si falta, lo tratamos como sufijo de marca para garantizar unicidad.
        if (strpos($template, '{title}') === false) {
            $template = '{title} | ' . $template;
        }

        $rendered = str_replace('{title}', $title, $template);

        // Defensa extra: si el render contiene el title duplicado (X | X | ...),
        // colapsar a una sola aparicion.
        $rendered = preg_replace(
            '/(?:^|\s\|\s)' . preg_quote($title, '/') . '(?=\s\|\s' . preg_quote($title, '/') . '(?:\s\||$))/i',
            '',
            $rendered
        );

        $this->title = trim($rendered, " |\t");
        return $this;
    }

    public function rawTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function description(?string $description): self
    {
        if ($description !== null) {
            $this->description = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($description))), 0, 300);
        }
        return $this;
    }

    public function canonical(string $path): self
    {
        $this->canonical = site_url($path);
        return $this;
    }

    public function ogImage(?string $url): self
    {
        if ($url) {
            $this->ogImage = $url;
        }
        return $this;
    }

    public function ogType(string $type): self
    {
        $this->ogType = $type;
        return $this;
    }

    public function noindex(bool $follow = false): self
    {
        // follow=true → "noindex, follow" (no rankear esta URL pero sí seguir
        // los links, util para listados parametrizados con filtros).
        // Default false → "noindex, nofollow" (ej. admin, error pages).
        $this->robots = 'noindex, ' . ($follow ? 'follow' : 'nofollow');
        return $this;
    }

    /**
     * @param array<int, array{0:string,1:string}> $items lista de [nombre, path]
     */
    public function breadcrumb(array $items): self
    {
        $this->breadcrumb = $items;
        if ($items) {
            $this->jsonLd[] = [
                '@context' => 'https://schema.org',
                '@type'    => 'BreadcrumbList',
                'itemListElement' => array_map(
                    fn($i, $it) => [
                        '@type'    => 'ListItem',
                        'position' => $i + 1,
                        'name'     => $it[0],
                        'item'     => site_url($it[1]),
                    ],
                    array_keys($items),
                    $items
                ),
            ];
        }
        return $this;
    }

    public function schemaOrganization(): self
    {
        $this->jsonLd[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => $this->site->name,
            'url'      => site_url('/'),
            'logo'     => $this->site->logoUrl,
        ];
        return $this;
    }

    /**
     * WebSite + SearchAction → habilita el "sitelinks search box" de Google
     * cuando el sitio rankea con su nombre. Usar en la home.
     */
    public function schemaWebSite(): self
    {
        $this->jsonLd[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => $this->site->name,
            'url'             => site_url('/'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => site_url('/buscar?q={search_term_string}'),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        return $this;
    }

    /**
     * Person schema para paginas de autor. Mejora E-E-A-T.
     * @param array<string, mixed> $author row con name, slug, bio, avatar_url, social_links, expertise
     */
    public function schemaPerson(array $author): self
    {
        $sameAs = [];
        if (!empty($author['social_links']) && is_array($author['social_links'])) {
            foreach ($author['social_links'] as $url) {
                if (is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $sameAs[] = $url;
                }
            }
        }
        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Person',
            'name'        => $author['name'],
            'url'         => site_url('/autor/' . ($author['slug'] ?? '')),
            'description' => !empty($author['bio']) ? $this->oneLine($author['bio'], 300) : null,
            'image'       => $author['avatar_url'] ?? null,
            'jobTitle'    => $author['expertise'] ?? null,
            'sameAs'      => $sameAs ?: null,
            'worksFor'    => [
                '@type' => 'Organization',
                'name'  => $this->site->name,
                'url'   => site_url('/'),
            ],
        ];
        $this->jsonLd[] = $this->deepFilter($data);
        return $this;
    }

    /**
     * Setea og:locale (ej "es_AR", "es_MX"). Ayuda a la geo-relevancia
     * en mercados especificos. Default: derivado del site (lang_country).
     */
    public function ogLocale(?string $locale = null): self
    {
        if ($locale === null) {
            $lang = strtolower($this->site->defaultLanguage ?: 'es');
            $country = strtoupper($this->site->defaultCountry ?: 'AR');
            $locale = $lang . '_' . $country;
        }
        $this->ogLocaleValue = $locale;
        return $this;
    }

    private function oneLine(string $s, int $max = 200): string
    {
        $s = trim(preg_replace('/\s+/', ' ', strip_tags($s)));
        if (mb_strlen($s) > $max) { $s = rtrim(mb_substr($s, 0, $max - 1)) . '…'; }
        return $s;
    }

    /**
     * Product + Offer + Review editorial.
     *
     * Nota sobre el rating: nuestra nota es UNA opinion editorial, no el
     * promedio de reviews de usuarios. Por eso se emite como `review` (un
     * Review con author = la publicacion) y no como `aggregateRating` con
     * ratingCount 1, que le declara a Google un agregado que no existe.
     *
     * @param array<string, mixed> $p producto (con features/pros/cons ya decodificados)
     */
    public function schemaProduct(array $p): self
    {
        $offers = null;
        if (!empty($p['price_from'])) {
            $offers = [
                '@type'         => 'Offer',
                'price'         => (string)$p['price_from'],
                'priceCurrency' => $p['price_currency'] ?: 'USD',
                'availability'  => 'https://schema.org/InStock',
                'url'           => site_url(product_url($p)),
            ];
        }

        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $p['name'],
            'description' => $p['description_short'] ?? '',
            'brand'       => $p['brand'] ? ['@type' => 'Brand', 'name' => $p['brand']] : null,
            'image'       => $p['logo_url'] ?? null,
            'url'         => site_url(product_url($p)),
        ];
        if ($offers) {
            $data['offers'] = $offers;
        }
        if (!empty($p['rating'])) {
            $data['review'] = [
                '@type'        => 'Review',
                'reviewRating' => self::ratingNode($p['rating']),
                'author'       => [
                    '@type' => 'Organization',
                    'name'  => $this->site->name,
                    'url'   => site_url('/'),
                ],
            ];
        }

        $this->jsonLd[] = array_filter($data, fn($v) => $v !== null);
        return $this;
    }

    /**
     * Nodo Rating reutilizable. Escala 0-5, igual que products.rating y
     * articles.rating.
     *
     * @param mixed $value
     * @return array<string, string>
     */
    private static function ratingNode($value): array
    {
        return [
            '@type'       => 'Rating',
            'ratingValue' => number_format((float)$value, 1, '.', ''),
            'bestRating'  => '5',
            'worstRating' => '0',
        ];
    }

    /**
     * Review completo para articulos de tipo `review`.
     *
     * Google exige `itemReviewed` + `reviewRating` para tomar un Review como
     * valido. Si falta cualquiera de los dos (resena sin producto asociado, o
     * sin nota cargada) degradamos a Article, que siempre es correcto — es
     * preferible un Article valido a un Review que el validador descarta.
     *
     * @param array<string, mixed>      $a       articulo
     * @param array<string, mixed>|null $product producto reseñado
     */
    public function schemaReview(array $a, ?array $product = null): self
    {
        $rating = $a['rating'] ?? null;
        if (($rating === null || $rating === '') && $product !== null) {
            $rating = $product['rating'] ?? null;
        }

        if ($product === null || $rating === null || $rating === '') {
            return $this->schemaArticle($a);
        }

        $itemReviewed = [
            '@type' => 'Product',
            'name'  => $product['name'],
            'brand' => !empty($product['brand'])
                ? ['@type' => 'Brand', 'name' => $product['brand']]
                : null,
            'image' => $product['logo_url'] ?? null,
            'url'   => site_url(product_url($product)),
        ];

        $body = $a['verdict'] ?? null;
        if ($body === null || trim((string)$body) === '') {
            $body = $a['excerpt'] ?? null;
        }

        $data = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Review',
            'name'          => $a['title'],
            'headline'      => $a['title'],
            'reviewBody'    => $body ? $this->oneLine((string)$body, 500) : null,
            'itemReviewed'  => $itemReviewed,
            'reviewRating'  => self::ratingNode($rating),
            'datePublished' => !empty($a['published_at']) ? date('c', strtotime($a['published_at'])) : null,
            'dateModified'  => !empty($a['updated_at'])   ? date('c', strtotime($a['updated_at']))   : null,
            'author'        => !empty($a['author_name']) ? [
                '@type' => 'Person',
                'name'  => $a['author_name'],
                'url'   => !empty($a['author_slug']) ? site_url('/autor/' . $a['author_slug']) : null,
            ] : [
                '@type' => 'Organization',
                'name'  => $this->site->name,
                'url'   => site_url('/'),
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $this->site->name,
                'logo'  => $this->site->logoUrl ? [
                    '@type' => 'ImageObject',
                    'url'   => $this->site->logoUrl,
                ] : null,
            ],
            'url' => site_url(article_url($a)),
        ];

        $this->jsonLd[] = $this->deepFilter($data);
        return $this;
    }

    /**
     * Article schema. Para resenas usar schemaReview(), que emite el
     * `Review` con itemReviewed + reviewRating que Google requiere.
     *
     * @param array<string, mixed> $a
     */
    public function schemaArticle(array $a): self
    {
        $data = [
            '@context'      => 'https://schema.org',
            '@type'         => ($a['article_type'] ?? '') === 'news' ? 'NewsArticle' : 'Article',
            'headline'      => $a['title'],
            'description'   => $a['excerpt'] ?? null,
            'image'         => $a['featured_image'] ?? null,
            'datePublished' => $a['published_at'] ? date('c', strtotime($a['published_at'])) : null,
            'dateModified'  => $a['updated_at']   ? date('c', strtotime($a['updated_at']))   : null,
            'author'        => !empty($a['author_name']) ? [
                '@type' => 'Person',
                'name'  => $a['author_name'],
                'url'   => !empty($a['author_slug']) ? site_url('/autor/' . $a['author_slug']) : null,
            ] : null,
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => $this->site->name,
                'logo'  => $this->site->logoUrl ? [
                    '@type' => 'ImageObject',
                    'url'   => $this->site->logoUrl,
                ] : null,
            ],
            'mainEntityOfPage' => site_url(article_url($a)),
        ];
        $this->jsonLd[] = $this->deepFilter($data);
        return $this;
    }

    /**
     * FAQPage. Desde agosto 2023 Google restringio el rich result de FAQ a
     * sitios de gobierno y salud, asi que esto NO produce el acordeon
     * expandido en Google. Sigue siendo util para Bing, para los crawlers de
     * LLMs y como estructura semantica de la pagina.
     *
     * @param array<int, array{question:string, answer:string}> $faqs
     */
    public function schemaFaq(array $faqs): self
    {
        if (!$faqs) { return $this; }
        $this->jsonLd[] = [
            '@context' => 'https://schema.org',
            '@type'    => 'FAQPage',
            'mainEntity' => array_map(fn($f) => [
                '@type' => 'Question',
                'name'  => $f['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => $f['answer'],
                ],
            ], $faqs),
        ];
        return $this;
    }

    /**
     * @return array<int, array{0:string,1:string}>
     */
    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }

    public function renderHead(): string
    {
        $out = [];
        $title = $this->title ?? $this->site->name;
        $desc  = $this->description;
        $canonical = $this->canonical ?? site_url($_SERVER['REQUEST_URI'] ?? '/');

        $out[] = '<title>' . e($title) . '</title>';
        if ($desc) {
            $out[] = '<meta name="description" content="' . e($desc) . '">';
        }
        if ($this->robots) {
            $out[] = '<meta name="robots" content="' . e($this->robots) . '">';
        }
        $out[] = '<link rel="canonical" href="' . e($canonical) . '">';

        // Open Graph
        $out[] = '<meta property="og:site_name" content="' . e($this->site->name) . '">';
        $out[] = '<meta property="og:title" content="' . e($title) . '">';
        if ($desc) {
            $out[] = '<meta property="og:description" content="' . e($desc) . '">';
        }
        $out[] = '<meta property="og:url" content="' . e($canonical) . '">';
        $out[] = '<meta property="og:type" content="' . e($this->ogType) . '">';
        // og:locale: si no fue seteado explicitamente, derivar del site (lang_country).
        $locale = $this->ogLocaleValue
            ?? (strtolower($this->site->defaultLanguage ?: 'es') . '_' . strtoupper($this->site->defaultCountry ?: 'AR'));
        $out[] = '<meta property="og:locale" content="' . e($locale) . '">';
        if ($this->ogImage) {
            $out[] = '<meta property="og:image" content="' . e($this->ogImage) . '">';
        }

        // Twitter
        $out[] = '<meta name="twitter:card" content="summary_large_image">';
        $out[] = '<meta name="twitter:title" content="' . e($title) . '">';
        if ($desc) {
            $out[] = '<meta name="twitter:description" content="' . e($desc) . '">';
        }
        if ($this->ogImage) {
            $out[] = '<meta name="twitter:image" content="' . e($this->ogImage) . '">';
        }

        if ($this->site->googleSearchConsoleVerification) {
            $out[] = '<meta name="google-site-verification" content="'
                . e($this->site->googleSearchConsoleVerification) . '">';
        }

        foreach ($this->jsonLd as $block) {
            $json = json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            // Evitar </script> dentro del JSON (XSS defense).
            $json = str_replace('</', '<\\/', $json);
            $out[] = '<script type="application/ld+json">' . $json . '</script>';
        }

        return implode("\n    ", $out);
    }

    /**
     * Elimina recursivamente keys con valor null.
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function deepFilter(array $data): array
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = $this->deepFilter($v);
                if ($data[$k] === []) {
                    unset($data[$k]);
                }
            } elseif ($v === null) {
                unset($data[$k]);
            }
        }
        return $data;
    }
}
