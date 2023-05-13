<?php

namespace Tests\Unit;

use App\Models\Permission;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;
    /** @test  */
    public function can_assign_permissions_to_a_users()
    {
        $user = User::factory()->create();

        $permissions = Permission::factory()->create();

        $user->givePermissionTo($permissions);

        $this->assertCount(1, $user->fresh()->permissions);
    }
    /** @test  */
    public function cannot_assign_the_same_permissions_twice()
    {
        $user = User::factory()->create();

        $permissions = Permission::factory()->create();

        $user->givePermissionTo($permissions);
        $user->givePermissionTo($permissions);

        $this->assertCount(1, $user->fresh()->permissions);
    }
}
