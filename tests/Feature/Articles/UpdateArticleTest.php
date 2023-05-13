<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function can_update_articles()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $response = $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Updated Article',
                'slug'    => $article->slug,
                'content' => 'Updated content',
            ])->assertOk();


        $response->assertJsonApiResource($article, [
            'title'   => 'Updated Article',
            'slug'    => $article->slug,
            'content' => 'Updated content',
        ]);


    }
    /** @test */
    public function cannot_update_articles_owned_by_others_users()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Updated Article',
                'slug'    => $article->slug,
                'content' => 'Updated content',
            ])->assertForbidden();
    }


    /** @test */
    public function guest_cannot_update_articles()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article))
            ->assertJsonApiError(
                title: 'Unauthenticated',
                detail: 'This action requires authentication',
                status: '401'
            );
    }

    /** @test */
    public function slug_must_be_unique()
    {
        $article_edit = Article::factory()->create();
        $article_updated = Article::factory()->create();
        Sanctum::actingAs($article_edit->author);

        $this->patchJson(route('api.v1.articles.update', $article_edit),
            [
                'title'    => 'Nuevo Articulo',
                'slug' => $article_updated->slug,
                'content' => 'Contenido del articulo',
            ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function title_is_required()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'slug'    => 'update-article',
                'content' => 'Updated content',
            ])->assertJsonApiValidationErrors('title');

    }


    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Upd',
                'slug'    => 'update-article',
                'content' => 'Updated content',
            ])->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function content_is_required()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Updated Article',
                'slug'    => 'update-article',
            ])->assertJsonApiValidationErrors('content');

    }

    /** @test */
    public function slug_is_required()
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'   => 'Updated Article',
                'content' => 'Updated content',
            ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {

        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
            [
                'title'    => 'Nuevo Articulo',
                'slug' => '$%^&',
                'content' => 'Contenido del articulo',
            ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_contain_underscores()
    {

        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
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

        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
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

        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article),
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

}
