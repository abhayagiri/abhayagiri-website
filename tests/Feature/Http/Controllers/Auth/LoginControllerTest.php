<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    protected function actingAsSuperAdmin()
    {
        $email = config('abhayagiri.auth.mahapanel_admin');
        $user = User::where('email', $email)->firstOrFail();
        return $this->actingAs($user, backpack_guard_name());
    }

    public function testDashboard()
    {
        $this->assertGuest()
             ->get(route('admin.index'))
             ->assertRedirect(route('login'));

        // TODO: Currently this does not work?!
        //$this->actingAsSuperAdmin()
        //     ->get(route('admin.index'))
        //     ->assertRedirect(route('admin.dashboard'));
    }
}
