<?php
namespace Controllers;

use Core\Database;

final class AuthorController extends Controller
{
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $author = Database::instance()->fetch(
            'SELECT * FROM authors WHERE site_id = :s AND slug = :slug LIMIT 1',
            ['s' => $this->site->id, 'slug' => $slug]
        );
        if (!$author) {
            $this->notFound("Autor no encontrado");
            return;
        }
        $author['social_links'] = is_string($author['social_links'] ?? null)
            ? (json_decode($author['social_links'], true) ?: [])
            : ($author['social_links'] ?? []);

        $articles = Database::instance()->fetchAll(
            "SELECT * FROM articles
             WHERE site_id = :s AND author_id = :a AND status = 'published' AND published_at <= NOW()
             ORDER BY published_at DESC
             LIMIT 50",
            ['s' => $this->site->id, 'a' => $author['id']]
        );

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
