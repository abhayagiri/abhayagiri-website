<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminDevBypassTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        $email = 'dev@example.com';
        Config::set('abhayagiri.auth.mahapanel_admin', $email);
        factory(User::class)->state('super_admin')->create(['email' => $email]);
    }

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
