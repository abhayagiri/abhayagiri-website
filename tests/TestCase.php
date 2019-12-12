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
    protected function actingAsAdmin(bool $superAdmin = false)
    {
        $factory = factory(BackpackUser::class);
        if ($superAdmin) {
            $factory = $factory->state('super_admin');
        }
        return $this->actingAs($factory->create(), backpack_guard_name());
    }

    /**
     * "Refresh" a database table by deleting everything. This is a stop-gap
     * measure until we remove all tests testing against "real" data.
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
     * @return void
     */
    protected function resetTable(...$tables): void
    {
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
    }
}
