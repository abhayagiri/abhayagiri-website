<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminDevBypassTest extends TestCase
{
    use WithoutMiddleware;

    public function testBypassFailsOnProduction()
    {
        Config::set('app.env', 'production');
        Config::set('abhayagiri.auth.mahapanel_bypass', true);
        $this->get('/admin/login/dev-bypass')
            ->assertStatus(403);
    }

    public function testBypassFailsWithoutConfig()
    {
        Config::set('app.env', 'local');
        Config::set('abhayagiri.auth.mahapanel_bypass', false);
        $this->get('/admin/login/dev-bypass')
            ->assertStatus(403);
    }

    public function testBypassPassesWithConfig()
    {
        Config::set('app.env', 'local');
        Config::set('abhayagiri.auth.mahapanel_bypass', true);
        $this->get('/admin/login/dev-bypass')
            ->assertStatus(302);
    }
}
