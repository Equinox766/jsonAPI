<?php

namespace Tests\Unit\JsonApi;

use App\JsonApi\Document;
use Mockery;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{

    /** @test  */
    public function can_create_json_api_documents()
    {

        $category = Mockery::Mock('Category', function($mock)
        {
            $mock->shouldReceive('getResourceType')->andReturn('categories');
            $mock->shouldReceive('getRouteKey')->andReturn('categories-id');
        });

        $document = Document::type('articles')
            ->id('articles-id')
            ->attributes([
                'title' => 'Article title',
            ])->relationshipData([
                'category' => $category,
            ])
            ->toArray();

        $expected = [
            'data' => [
                'type' => 'articles',
                'id' => 'articles-id',
                'attributes' => [
                    'title' => 'Article title',
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => 'categories-id',
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $document);
    }
}
