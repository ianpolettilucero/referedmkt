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
        $template = $this->site->metaTitleTemplate ?: '{title} | ' . $this->site->name;
        $this->title = str_replace('{title}', $title, $template);
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

    public function noindex(): self
    {
        $this->robots = 'noindex, nofollow';
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
     * Product + Review + AggregateRating.
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
            $data['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string)$p['rating'],
                'bestRating'  => '5',
                'worstRating' => '0',
                'ratingCount' => 1,
            ];
        }

        $this->jsonLd[] = array_filter($data, fn($v) => $v !== null);
        return $this;
    }

    /**
     * Article schema.
     * @param array<string, mixed> $a
     */
    public function schemaArticle(array $a): self
    {
        $data = [
            '@context'      => 'https://schema.org',
            '@type'         => ($a['article_type'] ?? '') === 'review' ? 'Review' : 'Article',
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
