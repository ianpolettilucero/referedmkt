<?php
namespace Controllers;

use Models\Article;
use Models\Category;
use Models\Product;

final class HomeController extends Controller
{
    public function index(): void
    {
        $featured = Product::featured($this->site->id, 6);
        $recent   = Article::recent($this->site->id, 6);
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
