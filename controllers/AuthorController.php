<?php
namespace Controllers;

use Models\Article;
use Models\Author;

final class AuthorController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $author = Author::findBySlug($this->site->id, $slug);
        if (!$author) {
            $this->notFound("Autor no encontrado");
            return;
        }

        $articles = Article::byAuthor($this->site->id, (int)$author['id'], 50);

        $this->seo
            ->title($author['name'])
            ->description($author['bio'])
            ->canonical('/autor/' . $author['slug'])
            ->ogImage($author['avatar_url'])
            ->ogType('profile')
            ->breadcrumb([['Inicio', '/'], ['Autores', '/autores'], [$author['name'], '/autor/' . $author['slug']]])
            ->schemaPerson($author);

        $this->render('author', ['author' => $author, 'articles' => $articles]);
    }
}
