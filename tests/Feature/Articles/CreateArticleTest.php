<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function can_create_articles()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson(route('api.v1.articles.store'),
         [
             'title'   => 'Nuevo Articulo',
             'slug'    => 'nuevo-articulo',
             'content' => 'Contenido del articulo',
             '_relationships' => [
                 'category' => $category,
                 'author'  => $user,
             ]
         ])->assertCreated();

         $article = Article::first();

         $response->assertJsonApiResource($article, [
             'title'   => 'Nuevo Articulo',
             'slug'    => 'nuevo-articulo',
             'content' => 'Contenido del articulo',
         ]);

         $this->assertDatabaseHas('articles', [
             'title'   => 'Nuevo Articulo',
             'user_id' => $user->id,
             'category_id' => $category->id,
         ]);
    }

    /** @test */
    public function title_is_required()
    {
        $this->postJson(route('api.v1.articles.store'),
         [
             'slug'    => 'nuevo-articulo',
             'content' => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('title');

    }


    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nue',
             'slug'    => 'nuevo-articulo',
             'content' => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function content_is_required()
    {
       $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug'    => 'nuevo-articulo',
         ])->assertJsonApiValidationErrors('content');

    }
    /** @test */
    public function category_relationship_is_required()
    {
       $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug'    => 'nuevo-articulo',
             'content'    => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('relationships.category');

    }
    /** @test */
    public function category_must_exist_in_database()
    {
       $this->postJson(route('api.v1.articles.store'),
         [
             'title'          => 'Nuevo Articulo',
             'slug'           => 'nuevo-articulo',
             'content'        => 'Contenido del articulo',
             '_relationships' => [
                 'category' =>Category::factory()->make()
             ]
         ])->assertJsonApiValidationErrors('relationships.category');

    }

    /** @test */
    public function slug_must_be_unique()
    {
        $article = Article::factory()->create();

        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug' => $article->slug,
             'content' => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {

        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug' => '$%^&',
             'content' => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {

        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug' => 'with_underscore',
             'content' => 'Contenido del articulo',
         ])->assertSee(
             trans(
                 'validation.no_underscores',
                 ['attribute' => 'data.attributes.slug']
             ))->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_start_with_dashes()
    {

        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug' => '-start-with-dashes',
             'content' => 'Contenido del articulo',
         ])->assertSee(
            trans(
                'validation.no_starting_dashes',
                ['attribute' => 'data.attributes.slug']
            ))->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_end_with_dashes()
    {

        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'slug' => 'end-with-dashes-',
             'content' => 'Contenido del articulo',
         ])->assertSee(
            trans(
                'validation.no_ending_dashes',
                ['attribute' => 'data.attributes.slug']
            ))->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_is_required()
    {
        $this->postJson(route('api.v1.articles.store'),
         [
             'title'    => 'Nuevo Articulo',
             'content' => 'Contenido del articulo',
         ])->assertJsonApiValidationErrors('slug');

    }
}
