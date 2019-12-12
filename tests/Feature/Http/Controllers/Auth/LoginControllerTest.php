<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testGuestAccess()
    {
        $response = $this
            ->assertGuest()
            ->get(route('admin.index'));
        $response->assertRedirect(route('admin.login'));

        $response = $this
            ->assertGuest()
            ->get(route('backpack.dashboard'));
        $response->assertRedirect(route('admin.login'));

        $response = $this
            ->assertGuest()
            ->get(route('elfinder.index'));
        $response->assertRedirect(route('admin.login'));

        $response = $this
            ->assertGuest()
            ->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function testDashboardAsAdmin()
    {
        $response = $this
            ->actingAsAdmin()
            ->get(route('admin.index'));
        $response->assertRedirect(route('backpack.dashboard'));

        $response = $this
            ->actingAsAdmin()
            ->get(route('backpack.dashboard'));
        $response->assertOk();
    }
}
