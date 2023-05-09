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

         $article = Article::factory()->create();

         $response = $this->getJson(route('api.v1.articles.show', $article));

         $response->assertJsonApiResource($article, [
             'title' => $article->title,
             'slug' => $article->slug,
             'content' => $article->content
         ])->assertJsonApiRelationshipLinks($article, ['category']);
    }

    /** @test */
    public function can_fetch_all_articles()
    {

        $article = Article::factory()->count(3)->create();
        $response = $this->getJson(route('api.v1.articles.index'));

        $response->assertJsonApiCollection($article, [
            'title' , 'slug' , 'content'
        ]);

    }
}
