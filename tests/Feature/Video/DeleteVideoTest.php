<?php

namespace Tests\Feature\Video;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Thumbnail;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

#[Group('video')]
class DeleteVideoTest extends TestCase 
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([DatabaseSeeder::class]);

        $this->user = User::factory()
            ->has( Video::factory()->has(Thumbnail::factory()) )
            ->create();

        $this->user->syncRoles(['client']);
        Storage::shouldReceive('delete')->andReturn(true);
    }

    public function test_user_can_delete_video() 
    {
        $video = $this->user->videos()->first();

        $response = $this->actingAs($this->user)
            ->delete(route('videos.destroy', $video->id));

        $response->assertSessionHasNoErrors()->assertRedirect('/dashboard');
    }
    
    public function test_user_can_delete_video_having_no_thumbnail() 
    {
        $video = $this->user->videos()->first();
        $video->thumbnail->delete();
        $video->refresh();

        $response = $this->actingAs($this->user)
            ->delete(route('videos.destroy', $video->id));

        $response->assertSessionHasNoErrors()->assertRedirect('/dashboard');
    }

    public function test_unauthorized_can_not_delete_video() 
    {
        $video = $this->user->videos()->first();

        $response = $this->actingAs( User::factory()->create() )
            ->delete(route('videos.destroy', $video->id));

        $response->assertForbidden();
    }

}