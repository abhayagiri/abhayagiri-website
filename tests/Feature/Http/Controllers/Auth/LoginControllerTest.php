<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /**
     * Set the currently logged in user as a super admin.
     *
     * @return $this
     */
    protected function actingAsSuperAdmin()
    {
        $email = config('abhayagiri.auth.mahapanel_admin');
        $user = User::where('email', $email)->firstOrFail();
        return $this->actingAs($user, backpack_guard_name());
    }

    public function testGuestAccess()
    {
        $response = $this
            ->assertGuest()
            ->get(route('admin.index'));
        $response->assertRedirect(route('login'));

        $response = $this
            ->assertGuest()
            ->get(route('elfinder.index'));
        $response->assertRedirect(route('login'));

        $response = $this
            ->assertGuest()
            ->get(route('crud.users.index'));
        $response->assertRedirect(route('login'));
    }

    public function xtestDashboard()
    {
        // TODO: Currently this does not work?!
        $response = $this
            ->actingAsSuperAdmin()
            ->get(route('admin.index'));
        $response->assertRedirect(route('admin.dashboard'));
    }
}
