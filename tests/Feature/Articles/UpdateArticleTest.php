<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function can_update_articles()
    {
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Updated Article',
                'slug'    => 'update-article',
                'content' => 'Updated content',
            ])->assertOk();

//        $response->assertHeader(
//            'Location',
//            route('api.v1.articles.show', $article)
//        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title'   => 'Updated Article',
                    'slug'    => 'update-article',
                    'content' => 'Updated content',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]

            ]
        ]);
    }
}
