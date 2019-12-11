<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Util;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as protected parentLogout;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Obtain the user information from Google.
     *
     * @param $request Illuminate\Http\Request
     * @return Response
     */
    public function index(Request $request)
    {
        if (backpack_auth()->check()) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/admin/login');
        }
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/admin/login')->with('status', 'Error: ' . $e->getMessage());
        }
        $localUser = User::where('email', $user->email)->first();
        $request->session()->flash('status', 'Task was successful!');
        if ($localUser) {
            backpack_auth()->login($localUser, true);
            return redirect('/admin')->with('status', 'Login success.');
        } else {
            return redirect('/admin/login')->with('status', 'User with email ' . $user->email . ' not authorized.');
        }
    }

    /**
     * Override logout to go to /admin/login.
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->parentLogout($request);
        return redirect('/admin/login');
    }

    /**
     * Development/test admin login.
     *
     * @return Response
     */
    public function devBypass(Request $request)
    {
        if (!Util::devBypassAvailable()) {
            abort(403);
        }
        $bypassUserEmail = config('abhayagiri.auth.mahapanel_admin');
        $bypassUser = User::where('email', $bypassUserEmail)->firstOrFail();
        backpack_auth()->login($bypassUser, true);
        return redirect('/admin')->with('status', 'Login success.');
    }
}
