<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AccessTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function can_issue_access_tokens()
    {
         $this->withoutJsonApiDocumentFormatting();
         $user = User::factory()->create();

         $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => $user->password,
             'device_name' => 'device_name'
         ]);

         $token = $response->json('token');

         $dbToken = PersonalAccessToken::findToken($token);

         $this->assertTrue($dbToken->tokenable->is($user));
    }
}
