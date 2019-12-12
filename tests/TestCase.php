<?php

namespace Tests;

use App\Models\BackpackUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

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

    /**
     * Reset the appllcation.
     *
     * This is needed by some tests to work around caching issues.
     *
     * @return $this
     */
    protected function resetApp(): TestCase
    {
        $this->app = $this->createApplication();
        return $this;
    }

    /**
     * "Refresh" one or more database tables by issuing an SQL DELETE. This is
     * a stop-gap measure until we remove all tests testing against "real"
     * data.
     *
     * Tests that use this should probably use the DatabaseTransactions trait.
     * For example:
     *
     *     use Illuminate\Foundation\Testing\DatabaseTransactions;
     *
     *     class MyTest extends TestCase
     *     {
     *         use DatabaseTransactions;
     *         ...
     *
     * TODO: Remove this method and have tests use RefreshDatabase instead.
     *
     * @return $this
     */
    protected function resetTable(...$tables): TestCase
    {
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
        return $this;
    }
}
