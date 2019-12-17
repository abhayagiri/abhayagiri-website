<?php

namespace Tests;

use App\Models\BackpackUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set the currently logged in user to a newly created admin.
     *
     * If $superAdmin is true, then the admin will be a super admin.
     *
     * @param  bool  $superAdmin
     * @return $this
     */
    protected function actingAsAdmin(bool $superAdmin = false): TestCase
    {
        $factory = factory(BackpackUser::class);
        if ($superAdmin) {
            $factory = $factory->state('super_admin');
        }
        return $this->actingAs($factory->create(), backpack_guard_name());
    }
}
