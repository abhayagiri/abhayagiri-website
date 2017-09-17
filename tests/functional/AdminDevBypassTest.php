<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminDevBypassTest extends TestCase
{
    use WithoutMiddleware;

    public function testBypassFailsWithoutConfig()
    {
        $this->app['config']->set('abhayagiri.auth.mahapanel_bypass', false);
        $this->get('/admin/login/dev-bypass')
            ->assertStatus(403);
    }

    public function testBypassPassesWithConfig()
    {
        $this->get('/admin/login/dev-bypass')
            ->assertStatus(302);
    }
}
