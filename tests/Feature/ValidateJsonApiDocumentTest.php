<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateJsonApiDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutJsonApiDocumentFormatting();
        Route::any('test_route', fn() => 'Ok')
            ->middleware(ValidateJsonApiDocument::class);
    }

    /** @test */
    public function data_is_required()
    {
         $this->postJson('test_route', [])
             ->assertJsonApiValidationErrors('data');
         $this->patchJson('test_route', [])
             ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function only_accepts_valid_json_api_document()
    {
         $this->postJson('test_route', [
             'data' => [
                 'type' => 'string',
                 'attributes' => ['name' =>'string']
             ]
         ])
             ->assertSuccessful();

         $this->patchJson('test_route', [
             'data' => [
                 'id' => '1',
                 'type' => 'string',
                 'attributes' => ['name' =>'string']
             ]
         ])
             ->assertSuccessful();

    }
    /** @test */
    public function data_must_be_an_array()
    {
         $this->postJson('test_route', [
             'data' => 'string'
         ])
             ->assertJsonApiValidationErrors('data');

         $this->patchJson('test_route', [
             'data' => 'string'
         ])
             ->assertJsonApiValidationErrors('data');
    }
    /** @test */
    public function data_types_is_required()
    {
         $this->postJson('test_route', [
             'data' => [
                 'attributes' => [
                     'name' => 'string'
                 ]
             ]
         ])
             ->assertJsonApiValidationErrors('data.type');

         $this->patchJson('test_route', [
             'data' => [
                 'attributes' => [
                     'name' => 'string']
             ]
         ])
             ->assertJsonApiValidationErrors('data.type');
    }


    /** @test */
    public function data_types_must_be_a_string()
    {
         $this->postJson('test_route', [
             'data' => [
                 'type' => 1,
                 'attributes' => ['name' =>'string']
             ]
         ])
             ->assertJsonApiValidationErrors('data.type');

         $this->patchJson('test_route', [
             'data' => [
                 'type' => 1,
                 'attributes' => ['name' =>'string']
             ]
         ])
             ->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_attribute_is_required()
    {
         $this->postJson('test_route', [
             'data' => [
                 'type' => 1
             ]
         ])
             ->assertJsonApiValidationErrors('data.attributes');

         $this->patchJson('test_route', [
             'data' => [
                 'type' => 1
             ]
         ])
             ->assertJsonApiValidationErrors('data.attributes');
    }
    /** @test */
    public function data_attribute_must_be_an_array()
    {
         $this->postJson('test_route', [
             'data' => [
                 'type' => 'string',
                 'attributes' => 'string'
             ]
         ])
             ->assertJsonApiValidationErrors('data.attributes');

         $this->patchJson('test_route', [
             'data' => [
                 'type' => 'string',
                 'attributes' => 'string'
             ]
         ])
             ->assertJsonApiValidationErrors('data.attributes');
    }
    /** @test */
    public function data_id_is_required()
    {

         $this->patchJson('test_route', [
             'data' => [
                 'type' => 'string',
                 'attributes' => [
                     'name' => 'test'
                 ]
             ]
         ])
             ->assertJsonApiValidationErrors('data.id');
    }
    /** @test */
    public function data_id_must_be_a_string()
    {

         $this->patchJson('test_route', [
             'data' => [
                 'id' => 1,
                 'type' => 'string',
                 'attributes' => [
                     'name' => 'test'
                 ]
             ]
         ])
             ->assertJsonApiValidationErrors('data.id');
    }
}
