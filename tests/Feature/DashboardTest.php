<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase 
{
    use RefreshDatabase;

    public function test_login_user_can_see_dashboard() 
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_cant_see_dashboard() 
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(302);
    }

}