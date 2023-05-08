<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function can_fetch_a_single_article()
    {
         $this->withoutExceptionHandling();

         $article = Article::factory()->create();

         $response = $this->getJson(route('api.v1.articles.show', $article));

         $response->assertExactJson([
             'data' => [
                 'type' => 'articles',
                 'id'   => (string) $article->getRouteKey(),
                 'attributes' => [
                     'title' => $article->title,
                     'slug' => $article->slug,
                     'content' => $article->content
                 ],
                 'links' => [
                    'self' => url('/api/v1/articles/'.$article->getRouteKey())
                ]
             ]

         ]);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $this->withoutExceptionHandling();

        $article = Article::factory()->count(3)->create();
        $response = $this->getJson(route('api.v1.articles.index'));
        $response->assertJson([
            'data' => [
                [
                    'type' => 'articles',
                    'id'   => (string) $article[0]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[0]->title,
                        'slug' => $article[0]->slug,
                        'content' => $article[0]->content
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $article[0])
                    ]
                ],
                ['type' => 'articles',
                    'id'   => (string) $article[1]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[1]->title,
                        'slug' => $article[1]->slug,
                        'content' => $article[1]->content
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $article[1])
                    ]
                ],
                [
                    'type' => 'articles',
                    'id'   => (string) $article[2]->getRouteKey(),
                    'attributes' => [
                        'title' => $article[2]->title,
                        'slug' => $article[2]->slug,
                        'content' => $article[2]->content
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $article[2])
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.articles.index')
            ]
        ]);

    }
}
