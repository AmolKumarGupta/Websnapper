<?php

namespace Tests\Feature\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoTest extends TestCase 
{
    use RefreshDatabase;

    protected static User $user;

    protected function setUp(): void 
    {
        parent::setUp();

        self::$user = User::factory()
        ->has(
            Video::factory()
                ->count(1)
                ->state(fn(array $attributes, User $user) => ['fk_user_id' => $user->id])
        )
        ->create();
    }

    public function test_user_can_see_video() 
    {
        $user = self::$user;

        $user->videos->each(function ($v) use ($user) {
            $videoLink = hashget($v->id);
    
            $response = $this->actingAs($user)->get("/videos/$videoLink");
            $response->assertStatus(200);
        });
    }

    public function test_unauthorized_cannot_see_video() 
    {
        $user = self::$user;

        $unAuthorizedUser = User::factory()->create();

        $user->videos->each(function ($v) use ($unAuthorizedUser) {
            $videoLink = hashget($v->id);
    
            $response = $this->actingAs($unAuthorizedUser)->get("/videos/$videoLink");
            $response->assertStatus(403);
        });
    }

    public function test_authorized_can_see_video() 
    {
        $user = self::$user;

        $authorizedUser = User::factory()->create();

        $user->videos->each(function ($v) use ($user, $authorizedUser) {
            $videoLink = hashget($v->id);

            $this->actingAs($user)->post('/video/access', [
                "videoId" => $v->id,
                "userEmail" => $authorizedUser->email,
            ]);
    
            $response = $this->actingAs($authorizedUser)->get("/videos/$videoLink");
            $response->assertStatus(200);
        });
    }

}