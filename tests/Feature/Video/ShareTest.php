<?php

namespace Tests\Feature\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;

#[Group('video')]
class ShareTest extends TestCase 
{
    use RefreshDatabase;

    protected static User $user;

    protected function setUp(): void 
    {
        parent::setUp();

        $this->seed([DatabaseSeeder::class]);

        self::$user = User::factory()
        ->has(
            Video::factory()
                ->count(1)
                ->state(fn(array $attributes, User $user) => ['fk_user_id' => $user->id])
        )
        ->create();

        self::$user->syncRoles(['client']);
    }

    public function test_give_access_to_user() 
    {
        $user = self::$user;
        $another = User::factory()->create();
        $another->syncRoles(['client']);

        $user->videos->each(function($video) use($user, $another) {

            $response = $this->actingAs($user)->post('/video/access', [
                "videoId" => $video->id,
                "userEmail" => $another->email,
            ]);

            $response->assertStatus(200);
            $access = VideoAccess::orderBy('id', 'DESC')->first();

            $this->assertSame($access->model_type, User::class);
            $this->assertSame($access->model_id, $another->id);
            $this->assertSame($access->email, null);
        });
    }

    public function test_give_access_to_email() 
    {
        $user = self::$user;

        $user->videos->each(function($video) use($user) {
            $email = "example@gmail.com";

            $response = $this->actingAs($user)->post('/video/access', [
                "videoId" => $video->id,
                "userEmail" => $email,
            ]);

            $response->assertStatus(200);
            $access = VideoAccess::orderBy('id', 'DESC')->first();

            $this->assertSame($access->model_id, null);
            $this->assertSame($access->email, $email);
        });
    }

}