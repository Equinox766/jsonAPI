<?php

namespace Tests\Feature\Auth;

use App\Models\Permission;
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

        $data =  $this->validCredentials(['email' => $user->email]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $token = $response->json('plain-Text-Token');

        $dbToken = PersonalAccessToken::findToken($token);

        $this->assertTrue($dbToken->tokenable->is($user));
    }

    /** @test */

    public function users_permissions_are_assigned_as_abilities_to_the_token()
    {
        $this->withoutJsonApiDocumentFormatting();

        $user = User::factory()->create();

        $permissions1 = Permission::factory()->create();
        $permissions2 = Permission::factory()->create();
        $permissions3 = Permission::factory()->create();

        $user->givePermissionTo($permissions1);
        $user->givePermissionTo($permissions2);

        $data =  $this->validCredentials(['email' => $user->email]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $token = $response->json('plain-Text-Token');

        $dbToken = PersonalAccessToken::findToken($token);

        $this->assertTrue($dbToken->can($permissions1->name));
        $this->assertTrue($dbToken->can($permissions2->name));
        $this->assertFalse($dbToken->can($permissions3->name));
    }

    /** @test */

    public function password_must_be_valid()
    {
        $this->withoutJsonApiDocumentFormatting();
        $user = User::factory()->create();

        $data =  $this->validCredentials([
            'email' => $user->email,
            'password' => 'incorrect'
        ]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrorFor('email');

    }

    /** @test */

    public function user_must_be_registered()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data =  $this->validCredentials();

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrorFor('email');

    }

    /** @test */

    public function email_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data =  $this->validCredentials(['email' => null]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['email' => 'required']);

    }

    /** @test */

    public function password_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data =  $this->validCredentials(['password' => null]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['password' => 'required']);

    }

    /** @test */

    public function device_name_is_required()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data =  $this->validCredentials(['device_name' => null]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['device_name' => 'required']);

    }

    /** @test */

    public function email_must_be_valid()
    {
        $this->withoutJsonApiDocumentFormatting();

        $data =  $this->validCredentials(['email' => 'invalid-email']);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertJsonValidationErrors(['email' => 'email']);

    }

    protected function validCredentials(mixed $overrides = []): array
    {
        return array_merge([
            'email' => 'Equinox@equinox.dev',
            'password' => 'password',
            'device_name' => 'device_name'
        ], $overrides);
    }
}
