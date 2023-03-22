<?php

namespace Tests;

use App\User;
use App\Utilities\ImageCache;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set the currently logged in user to a newly created admin.
     *
     * If $superAdmin is true, then the admin will be a super admin.
     *
     * @param  bool  $superAdmin
     *
     * @return self
     */
    protected function actingAsAdmin(bool $superAdmin = false): self
    {
        $factory = factory(User::class);
        if ($superAdmin) {
            $factory = $factory->state('super_admin');
        }
        return $this->actingAs($factory->create(), backpack_guard_name());
    }

    /**
     * Disable image creation with ImageCache to speed up testing.
     *
     * @return self
     */
    protected function withoutImageCreation(): self
    {
        $response = new Response();
        $mock = $this->mock('App\Utilities\ImageCache');
        $mock->shouldReceive('getImageResponse')->andReturn($response);
        $mock->shouldReceive('getModelImageResponse')->andReturn($response);
        return $this;
    }
}
