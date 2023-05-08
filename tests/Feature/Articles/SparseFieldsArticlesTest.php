<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SparseFieldsArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function specific_fields_can_be_requested_in_the_article_index()
    {
        $articles = Article::factory()->create();
         // articles?fields[articles]=title,slug

        $url = route('api.v1.articles.index', [
            'fields' => [
                'articles' => 'title,slug',
            ]
        ]);

        $this->getJson($url)->assertJsonFragment([
            'title' => $articles->title,
            'slug' => $articles->slug,
        ])->assertJsonMissing([
            'content' => $articles->content,
        ])->assertJsonMissing([
            'content' => null
        ]);
    }

    /** @test */

    public function route_key_must_be_added_automatically_in_the_article_index()
    {
        $articles = Article::factory()->create();
         // articles?fields[articles]=title

        $url = route('api.v1.articles.index', [
            'fields' => [
                'articles' => 'title'
            ]
        ]);

        $this->getJson($url)->assertJsonFragment([
            'title' => $articles->title,
        ])->assertJsonMissing([
            'content' => $articles->content,
            'slug' => $articles->content,
        ]);
    }

    /** @test */

    public function specific_fields_can_be_requested_in_the_article_show()
    {
        $articles = Article::factory()->create();
        // articles/the-slug?fields[articles]=title,slug

        $url = route('api.v1.articles.show', [
            'article' => $articles,
            'fields' => [
                'articles' => 'title,slug',
            ]
        ]);


        $this->getJson($url)->assertJsonFragment([
            'title' => $articles->title,
            'slug' => $articles->slug,
        ])->assertJsonMissing([
            'content' => $articles->content,
        ])->assertJsonMissing([
            'content' => null
        ]);
    }


    /** @test */

    public function route_key_must_be_added_automatically_in_the_article_show()
    {
        $articles = Article::factory()->create();
        // articles/the-slug?fields[articles]=title

        $url = route('api.v1.articles.show', [
            'article' => $articles,
            'fields' => [
                'articles' => 'title'
            ]
        ]);

        $this->getJson($url)->assertJsonFragment([
            'title' => $articles->title,
        ])->assertJsonMissing([
            'content' => $articles->content,
            'slug' => $articles->content,
        ]);
    }
}
