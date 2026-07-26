<?php
namespace Controllers;

use Models\Product;

final class ProductController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $row = Product::findBySlug($this->site->id, $slug);

        if (!$row) {
            $this->notFound("Producto no encontrado");
            return;
        }

        $descriptionHtml = (string)($row['description_html'] ?? '');

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
