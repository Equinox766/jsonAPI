<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function can_create_articles()
    {
        $this->withoutExceptionHandling();
        $response = $this->postJson(route('api.v1.articles.create'),
         [
             'data' => [
                 'type' => 'articles',
                 'attributes' => [
                     'title'   => 'Nuevo Articulo',
                     'slug'    => 'nuevo-articulo',
                     'content' => 'Contenido del articulo',
                 ]
            ]
         ]);
         $response->assertCreated();

         $article = Article::first();


         $response->assertExactJson([
             'data' => [
                 'type' => 'articles',
                 'id' => (string) $article->getRouteKey(),
                 'attributes' => [
                     'title'   => 'Nuevo Articulo',
                     'slug'    => 'nuevo-articulo',
                     'content' => 'Contenido del articulo',
                 ],
                 'links' => [
                     'self' => route('api.v1.articles.show', $article)
                 ]

             ]
         ]);
    }

    /** @test */
    public function title_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.create'),
         [
             'data' => [
                 'type' => 'articles',
                 'attributes' => [
                     'slug'    => 'nuevo-articulo',
                     'content' => 'Contenido del articulo',
                 ]
            ]
         ]);



         $response->assertJsonApiValidationErrors('title');

    }
    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $response = $this->postJson(route('api.v1.articles.create'),
         [
             'data' => [
                 'type' => 'articles',
                 'attributes' => [
                     'title'    => 'Nue',
                     'slug'    => 'nuevo-articulo',
                     'content' => 'Contenido del articulo',
                 ]
            ]
         ]);

         $response->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function content_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.create'),
         [
             'data' => [
                 'type' => 'articles',
                 'attributes' => [
                     'title'    => 'Nuevo Articulo',
                     'slug'    => 'nuevo-articulo',
                 ]
            ]
         ]);

         $response->assertJsonApiValidationErrors('content');

    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.create'),
         [
             'data' => [
                 'type' => 'articles',
                 'attributes' => [
                     'title'    => 'Nuevo Articulo',
                     'content' => 'Contenido del articulo',
                 ]
            ]
         ]);

         $response->assertJsonApiValidationErrors('slug');

    }

    public function toResponse ($request)
    {
        return parent::toResponse($request)->withHeader([
            'Location' => route('api.v1.articles.show', $this->resource)
        ]);
    }

}
