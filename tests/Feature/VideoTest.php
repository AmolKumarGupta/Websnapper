<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoTest extends TestCase 
{
    use RefreshDatabase;

    public function test_user_can_see_video() 
    {
        $user = User::factory()
            ->has(
                Video::factory()
                    ->count(1)
                    ->state(fn(array $attributes, User $user) => ['fk_user_id' => $user->id])
            )
            ->create();

        $user->videos->each(function ($v) use ($user) {
            $videoLink = hashget($v->id);
    
            $response = $this->actingAs($user)->get("/videos/$videoLink");
            $response->assertStatus(200);
        });
    }

    public function test_unauthorized_cannot_see_video() 
    {
        $user = User::factory()
            ->has(
                Video::factory()
                    ->count(1)
                    ->state(fn(array $attributes, User $user) => ['fk_user_id' => $user->id])
            )
            ->create();

        $unAuthorizedUser = User::factory()->create();

        $user->videos->each(function ($v) use ($unAuthorizedUser) {
            $videoLink = hashget($v->id);
    
            $response = $this->actingAs($unAuthorizedUser)->get("/videos/$videoLink");
            $response->assertStatus(403);
        });
    }

}