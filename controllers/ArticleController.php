<?php
namespace Controllers;

use Core\Markdown;
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

        Article::incrementViews((int)$a['id']);

        // Defensivo: si el parser falla por algun edge case, no damos 500 -
        // mostramos el articulo con contenido escapado como texto plano y
        // loggeamos el error para debugging.
        try {
            $contentHtml = Markdown::toHtml($a['content'] ?? '');
        } catch (\Throwable $e) {
            error_log('[referedmkt] Markdown parse failed for article #'
                . $a['id'] . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
            $contentHtml = '<pre style="white-space:pre-wrap">'
                . htmlspecialchars((string)($a['content'] ?? ''), ENT_QUOTES, 'UTF-8')
                . '</pre>';
        }

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
            ->breadcrumb($breadcrumb)
            ->schemaArticle($a);

        $this->render('article', [
            'article'          => $a,
            'content_html'     => $contentHtml,
            'related_products' => $relatedProducts,
        ]);
    }

    public function indexByType(array $params): void
    {
        $type = $params['__type'] ?? 'guide';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Article::paginate($this->site->id, $type, $page, 20);

        $label = $this->breadcrumbLabelForType($type);

        $this->seo
            ->title($label)
            ->description("Listado de {$label} publicadas en " . $this->site->name)
            ->canonical($this->sectionPathForType($type))
            ->breadcrumb([['Inicio', '/'], [$label, $this->sectionPathForType($type)]]);

        $this->render('article_list', [
            'articles' => $data['items'],
            'total'    => $data['total'],
            'page'     => $data['page'],
            'per_page' => $data['per_page'],
            'type'     => $type,
            'label'    => $label,
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
