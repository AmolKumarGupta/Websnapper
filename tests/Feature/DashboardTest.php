<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;

#[Group('page'), Group('user')]
class DashboardTest extends TestCase 
{
    use RefreshDatabase;

    public function test_login_user_can_see_dashboard() 
    {
        $this->seed([
            RoleSeeder::class,
        ]);

        $user = User::factory()->create();
        $user->syncRoles(['client']);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_cant_see_dashboard() 
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
    }

}