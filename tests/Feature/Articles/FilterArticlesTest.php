<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function can_filter_articles_by_title()
    {
         Article::factory()->create([
             'title' => 'Aprende Laravel desde cero'
         ]);

         Article::factory()->create([
             'title' => 'Other articles'
         ]);

         // articles?filter[title]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'title' => 'Laravel'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel desde cero')
            ->assertDontSee('Other articles');
    }

    /** @test */

    public function can_filter_articles_by_content()
    {
         Article::factory()->create([
             'content' => 'Aprende Laravel desde cero'
         ]);

         Article::factory()->create([
             'content' => 'Other articles'
         ]);

         // articles?filter[content]=Laravel

        $url = route('api.v1.articles.index', [
            'filter' => [
                'content' => 'Laravel'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel desde cero')
            ->assertDontSee('Other articles');
    }

    /** @test */

    public function can_filter_articles_by_year()
    {
         Article::factory()->create([
             'content' => 'Aprende Laravel desde cero 2023',
             'created_at' => now()
         ]);

         Article::factory()->create([
             'content' => 'Aprende Laravel desde cero 2022',
             'created_at' => now()->year(2022)
         ]);

         // articles?filter[year]=2022

        $url = route('api.v1.articles.index', [
            'filter' => [
                'year' => '2022'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel desde cero 2022')
            ->assertDontSee('Aprende Laravel desde cero 2023');
    }

    /** @test */

    public function can_filter_articles_by_month()
    {
         Article::factory()->create([
             'content' => 'Aprende Laravel desde cero 2023 marzo',
             'created_at' => now()->month(3)
         ]);

         Article::factory()->create([
             'content' => 'Aprende PHP desde cero 2023 marzo',
             'created_at' => now()->month(3)
         ]);

         Article::factory()->create([
             'content' => 'Aprende Laravel desde cero 2022 enero',
             'created_at' => now()->month(1)
         ]);

         // articles?filter[month]=3

        $url = route('api.v1.articles.index', [
            'filter' => [
                'month' => '3'
            ]
        ]);

        $this->getJson($url)
            ->assertJsonCount(2, 'data')
            ->assertSee('Aprende Laravel desde cero 2023 marzo')
            ->assertSee('Aprende PHP desde cero 2023 marzo')
            ->assertDontSee('Aprende Laravel desde cero 2022 enero');
    }

    /** @test */

    public function can_filter_articles_by_unknown_filter()
    {
         Article::factory()->count(2)->create();

         // articles?filter[unknown]=filter

        $url = route('api.v1.articles.index', [
            'filter' => [
                'unknown' => 'filter'
            ]
        ]);

        $this->getJson($url)->assertStatus(400);
    }
}
