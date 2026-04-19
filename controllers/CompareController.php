<?php
namespace Controllers;

use Models\Product;

final class CompareController extends Controller
{
    public function index(): void
    {
        $ids = array_filter(array_map('intval', explode(',', (string)($_GET['ids'] ?? ''))));
        $ids = array_slice(array_values(array_unique($ids)), 0, 4); // hasta 4 productos

        $products = [];
        if ($ids) {
            $products = Product::byIds($this->site->id, $ids);
            // Reordenar segun el orden de los IDs en la query.
            $byId = [];
            foreach ($products as $p) { $byId[(int)$p['id']] = $p; }
            $products = [];
            foreach ($ids as $id) {
                if (isset($byId[$id])) { $products[] = $byId[$id]; }
            }
        }

        // Union de todas las keys de specs para las filas de la tabla.
        $specKeys = [];
        foreach ($products as $p) {
            if (!empty($p['specs']) && is_array($p['specs'])) {
                foreach (array_keys($p['specs']) as $k) {
                    if (!in_array($k, $specKeys, true)) { $specKeys[] = $k; }
                }
            }
        }

        $this->seo
            ->title('Comparador de productos')
            ->description('Comparativa lado a lado de productos del catalogo.')
            ->canonical('/comparar')
            ->noindex() // la URL con IDs es volatil, no tiene sentido indexarla
            ->breadcrumb([['Inicio', '/'], ['Productos', '/productos'], ['Comparador', '/comparar']]);

        $this->render('compare', [
            'products'  => $products,
            'spec_keys' => $specKeys,
        ]);
    }
}
