<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Video;
use App\Services\Contract\Service;
use App\Services\Trait\SetService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('service')]
class ServiceTest extends TestCase
{

    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([DatabaseSeeder::class]);

        $this->user = User::factory()
            ->has( Video::factory())
            ->create();

        $this->user->syncRoles(['client']);
    }

    public function test_service_instance() 
    {
        $service = FakeService::init($this->user->id);
        $service->save($this->user->videos->first());

        $this->assertInstanceOf(FakeService::class, $service);
    }

}



class FakeService implements Service
{

    use SetService;

    public $provider = "mock";

    public static function init($userId): static 
    {
        return new FakeService;
    }

    public function save($model) 
    {

    }

}