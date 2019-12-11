<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
    protected function resetTable(...$tables)
    {
        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
    }
}
