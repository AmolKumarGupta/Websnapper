<?php

namespace Tests\Unit;

use App\Actions\StoreVideo;
use App\Enums\VideoStatus;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_save_file_successfully() 
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test.webm', 5000);

        Storage::shouldReceive('put')
            ->with("videos/{$user->id}", $file)
            ->andReturn(true);

        $this->assertTrue(StoreVideo::handle($user, $file));

        $video = Video::latest()->first();
        $this->assertTrue($user->id === $video->fk_user_id);
        $this->assertTrue($video->path === "videos/{$user->id}/{$file->hashName()}");
        $this->assertTrue($video->status == VideoStatus::Active);
    }

    public function test_file_not_saved() 
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test.webm', 5000);

        Storage::shouldReceive('put')
            ->with("videos/{$user->id}", $file)
            ->andReturn(false);

        $this->assertThrows(fn () => StoreVideo::handle($user, $file));
    }

}
