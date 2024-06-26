<?php

namespace Tests\Unit;

use App\Actions\StoreVideo;
use App\Enums\VideoStatus;
use App\Models\Thumbnail;
use App\Models\User;
use App\Models\Video;
use FFMpeg\FFMpeg;
use FFMpeg\Media\Frame;
use FFMpeg\Media\Video as MediaVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
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

        $this->instance(
            Frame::class,
            Mockery::mock(Frame::class, function (MockInterface $mock) {
                $mock->shouldReceive('save')
                    ->andReturn( $mock );
            })
        );

        $this->instance(
            MediaVideo::class,
            Mockery::mock(MediaVideo::class, function (MockInterface $mock) {
                $mock->shouldReceive('frame')
                    ->andReturn( resolve(Frame::class) );
            })
        );

        $this->instance(
            FFMpeg::class,
            Mockery::mock(FFMpeg::class, function (MockInterface $mock) use($user, $file) {
                $videoPath = "videos/{$user->id}/{$file->hashName()}";
                $mock->shouldReceive('open')
                    ->with( storage_path("app/{$videoPath}") )
                    ->andReturn( resolve(MediaVideo::class) );
            })
        );

        $this->assertTrue(StoreVideo::handle($user, $file));

        $video = Video::latest()->first();
        $this->assertTrue($user->id === $video->fk_user_id);
        $this->assertTrue($video->path === "videos/{$user->id}/{$file->hashName()}");
        $this->assertTrue($video->status == VideoStatus::Active);
        $this->assertInstanceOf(Thumbnail::class, $video->thumbnail);
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
