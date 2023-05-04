<?php


namespace Tests;

use Illuminate\Testing\TestResponse;

trait MakesJsonApiRequests
{
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        TestResponse::macro(
            'assertJsonApiValidationErrors',
            function($attributes) {
                /** @var TestResponse  $this*/
                $this->assertJsonStructure([
                    'errors' => [
                        ['title', 'detail', 'source' => ['pointer']]
                    ]
                ])->assertJsonFragment([
                    'source' => ['pointer' => "/data/attributes/{$attributes}"],
                ])->assertHeader(
                    'Content-Type', 'application/vnd.api+json'
                )->assertStatus(422);
            });

    }
    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';
        return parent::json(
            $method,
            $uri,
            $data,
            $headers
        );
    }

    public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers);
    }

    public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers);
    }
}