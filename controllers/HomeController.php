<?php
namespace Controllers;

use Models\Article;
use Models\Category;
use Models\Product;

final class HomeController extends Controller
{
    public function index(): void
    {
        // 4 en vez de 6: 6 cards genera un "4 + 2 huerfanos" feo en pantallas
        // que muestran 4 columnas. 4 llena una fila completa en desktop (4 col)
        // o dos filas parejas en laptop (2 col).
        $featured = Product::featured($this->site->id, 4);
        $recent   = Article::recent($this->site->id, 4);
        $cats     = Category::topLevel($this->site->id);
        $trending = Article::trendingWeek($this->site->id, 4);

        $this->seo
            ->title($this->site->name)
            ->description($this->site->metaDescriptionTemplate)
            ->canonical('/')
            ->schemaOrganization()
            ->schemaWebSite();

        $this->render('home', [
            'featured_products' => $featured,
            'recent_articles'   => $recent,
            'top_categories'    => $cats,
            'trending_articles' => $trending,
        ]);
    }
}
