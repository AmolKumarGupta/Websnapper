<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoView;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewsTest extends TestCase 
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

    public function test_count_as_view() 
    {
        $user = self::$user;

        $user->videos->each(function ($v) use ($user) {
            $response = $this->actingAs($user)->post("video/views", [
                "videoId" => $v->id,
            ]);

            $response->assertStatus(200);
            $view = VideoView::first();

            $this->assertSame(VideoView::count(), 1, "doesnt count as first view");
            $this->assertSame($view->model_id, $user->id);
        });
        
        $another = User::factory()->create();
        $user->videos->each(function ($v) use ($user, $another) {
            $response = $this->actingAs($another)->post("video/views", [
                "videoId" => $v->id,
            ]);

            $response->assertStatus(200);
            $view = VideoView::orderBy('id', 'DESC')->first();

            $this->assertSame(VideoView::count(), 2, "doesnt count as second view");
            $this->assertSame($view->model_id, $another->id);
        });
    }

    public function test_view_throttle() 
    {
        $user = self::$user;

        $user->videos->each(function ($v) use ($user) {
            $response = $this->actingAs($user)->post("video/views", [
                "videoId" => $v->id,
            ]);

            $response->assertStatus(200);
            $this->assertSame(VideoView::count(), 1);

            $response = $this->actingAs($user)->post("video/views", [
                "videoId" => $v->id,
            ]);
            $response->assertStatus(200);
            $this->assertSame(VideoView::count(), 1);
        });
    }

    public function test_invalid_video() 
    {
        $user = self::$user;

        $user->videos->each(function ($v) use($user) {
            $response = $this->actingAs($user)->post("video/views", [
                "videoId" => 9999,
            ]);

            $response->assertStatus(404);
            $this->assertSame(VideoView::count(), 0);
        });
    }

}