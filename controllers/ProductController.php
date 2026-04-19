<?php
namespace Controllers;

use Core\Database;
use Core\Markdown;
use Models\Product;

final class ProductController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $row = Database::instance()->fetch(
            "SELECT p.*, c.slug AS category_slug, c.name AS category_name,
                    al.tracking_slug AS affiliate_slug
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             LEFT JOIN affiliate_links al ON al.id = p.affiliate_link_id
             WHERE p.site_id = :site AND p.slug = :slug
             LIMIT 1",
            ['site' => $this->site->id, 'slug' => $slug]
        );

        if (!$row) {
            $this->notFound("Producto no encontrado");
            return;
        }

        // Hidratar JSON columns manualmente (bypass de Model).
        foreach (['features', 'pros', 'cons', 'specs'] as $col) {
            if (isset($row[$col]) && is_string($row[$col])) {
                $row[$col] = json_decode($row[$col], true) ?: null;
            }
        }

        $descriptionHtml = !empty($row['description_long'])
            ? Markdown::toHtml($row['description_long'])
            : '';

        $breadcrumb = [['Inicio', '/'], ['Productos', '/productos']];
        if (!empty($row['category_slug'])) {
            $breadcrumb[] = [$row['category_name'], '/productos/' . $row['category_slug']];
        }
        $breadcrumb[] = [$row['name'], product_url($row)];

        $this->seo
            ->title($row['meta_title'] ?: $row['name'])
            ->description($row['meta_description'] ?: $row['description_short'])
            ->canonical(product_url($row))
            ->ogImage($row['logo_url'])
            ->ogType('product')
            ->breadcrumb($breadcrumb)
            ->schemaProduct($row);

        $this->render('product', [
            'product'          => $row,
            'description_html' => $descriptionHtml,
        ]);
    }
}
