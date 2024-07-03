<?php

namespace Tests\Feature\Service;

use App\Models\ServiceVideo;
use App\Models\User;
use App\Models\Video;
use App\Services\Contract\Service;
use App\Services\Contract\ServiceManager;
use App\Services\Trait\SetService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('service'), Group('google-drive')]
class GoogleDriveTest extends TestCase
{

    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([DatabaseSeeder::class]);

        $this->user = User::factory()
            ->has( Video::factory() )
            ->create();

        $this->user->syncRoles(['client']);

        $manager = resolve(ServiceManager::class);
        $manager->set('google-drive', fn($id) => FakeDrive::init($id));
    }

    public function test_create_shareable_link()
    {
        /** @var Video $video */
        $video = $this->user->videos()->first();

        $response = $this->actingAs($this->user)
            ->post(route('video.sync'), ["videoId" => $video->id]);

        $response->assertSessionHasNoErrors();

        $link = $video->getSharableLink();

        $this->assertEquals('test-link', $link, "link is not same to `test-link`");

    }

}


class FakeDrive implements Service
{

    public static function init($userId): FakeDrive
    {
        return new static;
    }

    public function save($video) 
    {
        $sVideo = new ServiceVideo;
        $sVideo->service_id = 1;
        $sVideo->video_id = $video->id;
        $sVideo->payload = json_encode(["link" => "test-link"]);
        $sVideo->save();
    }

}