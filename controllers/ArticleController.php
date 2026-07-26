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
            [$a['title'], article_url($a)],
        ];

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

        // Filtros via query string
        $filters = [
            'type'        => $type,
            'category_id' => null,
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

        $label = $this->breadcrumbLabelForType($type);

        $this->seo
            ->title($label)
            ->description("Listado de {$label} publicadas en " . $this->site->name)
            ->canonical($this->sectionPathForType($type))
            ->breadcrumb([['Inicio', '/'], [$label, $this->sectionPathForType($type)]]);

        // Filtros activos -> noindex follow (canonical apunta a la base sin params).
        $hasFilters = !empty($filters['category_id'])
            || (($filters['sort'] ?? 'recent') !== 'recent')
            || $page > 1;
        if ($hasFilters) {
            $this->seo->noindex(true);
        }

        $this->render('article_list', [
            'articles'   => $data['items'],
            'total'      => $data['total'],
            'page'       => $data['page'],
            'per_page'   => $data['per_page'],
            'type'       => $type,
            'label'      => $label,
            'filters'    => $filters,
            'sorts'      => Article::SORTS,
            'categories' => $categories,
        ]);
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
