<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->seed([
            RoleSeeder::class,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_created_user_is_client(): void 
    {
        $this->seed([
            RoleSeeder::class,
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::latest()->first();
        $this->assertTrue($user->hasRole('client'));
    }
    
}
