<?php
namespace Controllers;

use Core\Faq;
use Core\Markdown;
use Core\Toc;
use Models\Article;
use Models\Product;

final class ArticleController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $a = Article::findPublished($this->site->id, $slug);
        if (!$a) {
            $this->notFound("Articulo no encontrado");
            return;
        }

        // Si el tipo del articulo no coincide con el prefix de la URL, 301 al correcto.
        $expectedType = $params['__type'] ?? null;
        if ($expectedType !== null && $a['article_type'] !== $expectedType) {
            header('Location: ' . article_url($a), true, 301);
            return;
        }

        $relatedProducts = [];
        if (!empty($a['related_product_ids']) && is_array($a['related_product_ids'])) {
            $relatedProducts = Product::byIds($this->site->id, $a['related_product_ids']);
        }

        // El HTML ya viene renderizado desde el build: en runtime no se parsea
        // Markdown. Si el parser fallara, habria roto el build, no la pagina.
        $contentHtml = (string)($a['content_html'] ?? '');

        // TOC: agrega ids a h2/h3 y extrae la estructura para el nav lateral.
        $toc = Toc::process($contentHtml);
        $contentHtml = $toc['html'];
        $tocItems    = Toc::shouldShow($toc['items']) ? $toc['items'] : [];

        // Articulos relacionados (misma categoria > mismo tipo > recientes).
        $relatedArticles = Article::related(
            $this->site->id,
            (int)$a['id'],
            isset($a['category_id']) ? (int)$a['category_id'] : null,
            (string)($a['article_type'] ?? 'guide'),
            3
        );

        $breadcrumb = [
            ['Inicio', '/'],
            [$this->breadcrumbLabelForType($a['article_type']), $this->sectionPathForType($a['article_type'])],
        ];
        $newsSection = news_sections()[$a['news_section'] ?? ''] ?? null;
        if ($newsSection !== null) {
            $breadcrumb[] = [$newsSection['breadcrumb'], $newsSection['path']];
        }
        $breadcrumb[] = [$a['title'], article_url($a)];

        $this->seo
            ->title($a['meta_title'] ?: $a['title'])
            ->description($a['meta_description'] ?: $a['excerpt'])
            ->canonical(article_url($a))
            ->ogImage($a['featured_image'])
            ->ogType('article')
            ->breadcrumb($breadcrumb);

        // Las resenas emiten Review (itemReviewed + reviewRating). El producto
        // reseñado es el primero de los relacionados; sin producto o sin nota,
        // schemaReview degrada solo a Article.
        if (($a['article_type'] ?? '') === 'review') {
            $this->seo->schemaReview($a, $relatedProducts[0] ?? null);
        } else {
            $this->seo->schemaArticle($a);
        }

        // FAQPage a partir de la seccion "## Preguntas frecuentes" del markdown.
        $faqs = Faq::extract($a['content'] ?? '');
        if ($faqs) {
            $this->seo->schemaFaq($faqs);
        }

        $verdictHtml = !empty($a['verdict']) ? Markdown::toHtml((string)$a['verdict']) : '';

        $this->render('article', [
            'article'          => $a,
            'content_html'     => $contentHtml,
            'verdict_html'     => $verdictHtml,
            'related_products' => $relatedProducts,
            'related_articles' => $relatedArticles,
            'toc_items'        => $tocItems,
        ]);
    }

    public function indexByType(array $params): void
    {
        $type = $params['__type'] ?? 'guide';
        $page = max(1, (int)($_GET['page'] ?? 1));

        // Seccion dentro de Noticias (/noticias/mundo). Solo existe para news;
        // una clave desconocida es un 404, no un listado vacio.
        $sectionKey = $params['__section'] ?? null;
        $section = null;
        if ($sectionKey !== null) {
            $section = news_sections()[$sectionKey] ?? null;
            if ($type !== 'news' || $section === null) {
                $this->notFound('Seccion no encontrada');
                return;
            }
        }

        // Filtros via query string
        $filters = [
            'type'         => $type,
            'news_section' => $sectionKey,
            'category_id'  => null,
            'sort'        => array_key_exists($_GET['sort'] ?? '', Article::SORTS)
                             ? $_GET['sort'] : 'recent',
        ];

        // Filtro por categoria
        if (!empty($_GET['cat'])) {
            $catRow = \Models\Category::findBySlug($this->site->id, (string)$_GET['cat']);
            if ($catRow) {
                $filters['category_id'] = (int)$catRow['id'];
            }
        }

        $data = Article::paginate($this->site->id, $filters, $page, 20);

        $categories = \Models\Category::all($this->site->id);

        $label    = $this->breadcrumbLabelForType($type);
        $basePath = $this->sectionPathForType($type);
        $crumbs   = [['Inicio', '/'], [$label, $basePath]];
        if ($section !== null) {
            $crumbs[] = [$section['breadcrumb'], $section['path']];
            $basePath = $section['path'];
        }

        // Titulo y descripcion propios por seccion: "Guías | Capa Cero" no dice
        // nada y desaprovecha el espacio del SERP.
        $meta = $this->sectionMeta($sectionKey !== null ? "news:$sectionKey" : $type, $data['total']);
        $this->seo
            ->title($meta['title'])
            ->description($meta['description'])
            ->canonical($basePath)
            ->breadcrumb($crumbs);

        // Filtros activos -> noindex follow (canonical apunta a la base sin params).
        $hasFilters = !empty($filters['category_id'])
            || (($filters['sort'] ?? 'recent') !== 'recent')
            || $page > 1;
        if ($hasFilters) {
            $this->seo->noindex(true);
        }

        // Pestanas de Noticias: "Todas" y una por seccion con contenido. Solo
        // se muestran si existe al menos una seccion, para no poner una
        // pestana sola en el listado general.
        $subnav = [];
        if ($type === 'news') {
            $activas = active_sections();
            foreach (news_sections() as $key => $sec) {
                if (isset($activas['news:' . $key])) {
                    $subnav[] = ['label' => $sec['label'], 'path' => $sec['path'], 'active' => $sectionKey === $key];
                }
            }
            if ($subnav) {
                array_unshift($subnav, ['label' => 'Todas', 'path' => '/noticias', 'active' => $sectionKey === null]);
            }
        }

        $this->render('article_list', [
            'articles'   => $data['items'],
            'total'      => $data['total'],
            'page'       => $data['page'],
            'per_page'   => $data['per_page'],
            'type'       => $type,
            'label'      => $section !== null ? $meta['h1'] : $label,
            'intro'      => $section !== null ? $meta['description'] : null,
            'base_path'  => $basePath,
            'subnav'     => $subnav,
            'filters'    => $filters,
            'sorts'      => Article::SORTS,
            'categories' => $categories,
        ]);
    }

    /**
     * Metadatos del listado por tipo. El conteo real entra en la descripcion:
     * es una senal concreta y se actualiza sola al publicar.
     *
     * @return array{title:string, description:string, h1?:string}
     */
    private function sectionMeta(string $type, int $total): array
    {
        $n = $total > 0 ? "$total " : '';
        return match ($type) {
            'news:mundo' => [
                'title'       => 'Noticias de ciberseguridad del mundo',
                'h1'          => 'Noticias del mundo',
                'description' => "Lo que pasa en ciberseguridad más allá de los CVE: robos, filtraciones, operaciones policiales, leyes y movimientos de la industria, con la fuente primaria y qué cambia para una empresa de la región.",
            ],
            'review' => [
                'title'       => 'Reseñas de herramientas de ciberseguridad',
                'description' => "Análisis independientes de {$n}herramientas de seguridad, probadas en escenarios reales. Qué hace bien cada una, qué no, y para qué tamaño de empresa conviene.",
            ],
            'comparison' => [
                'title'       => 'Comparativas de software de seguridad',
                'description' => "Comparativas cara a cara de soluciones de ciberseguridad: detección, precio, consola y soporte, con la recomendación según el caso de uso.",
            ],
            'news' => [
                'title'       => 'Novedades de ciberseguridad',
                'description' => "Novedades del sector que afectan a PyMEs: vulnerabilidades, cambios de producto y movimientos de mercado, sin ruido.",
            ],
            default => [
                'title'       => 'Guías de ciberseguridad para PyMEs',
                'description' => "{$n}guías prácticas para proteger una empresa sin equipo de seguridad dedicado: qué implementar, en qué orden y cuánto cuesta.",
            ],
        };
    }

    private function breadcrumbLabelForType(string $type): string
    {
        return match ($type) {
            'review'     => 'Reseñas',
            'comparison' => 'Comparativas',
            'news'       => 'Noticias',
            default      => 'Guías',
        };
    }

    private function sectionPathForType(string $type): string
    {
        return match ($type) {
            'review'     => '/resenas',
            'comparison' => '/comparativas',
            'news'       => '/noticias',
            default      => '/guias',
        };
    }
}
