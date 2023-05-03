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

         $response = $this->getJson('/api/v1/articles/'.$article->getRouteKey());

         $response->assertExactJson([
             'data' => [
                 'type' => 'articles',
                 'id'   => $article->getRouteKey(),
                 'atributes' => [
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
}
