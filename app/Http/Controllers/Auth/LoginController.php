<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('index', 'logout');
    }

    /**
     * Obtain the user information from Google.
     *
     * @param $request Illuminate\Http\Request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (backpack_auth()->check()) {
            return redirect($this->redirectTo());
        } else {
            return redirect($this->loginPath());
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
            return redirect($this->loginPath())
                ->with('status', 'Error: ' . $e->getMessage());
        }
        $localUser = User::where('email', $user->email)->first();
        $request->session()->flash('status', 'Task was successful!');
        if ($localUser) {
            backpack_auth()->login($localUser, true);
            return redirect($this->redirectTo())
                ->with('status', 'Login success.');
        } else {
            return redirect($this->loginPath())
                ->with('status', 'User with email ' . $user->email .
                                 ' not authorized.');
        }
    }

    /**
     * Override logout to go to admin.login.
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->parentLogout($request);
        return redirect($this->loginPath());
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
        $email = config('abhayagiri.auth.mahapanel_admin');
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Development Administrator',
                'email' => $email,
                'is_super_admin' => true,
            ]);
        }
        backpack_auth()->login($user, true);
        return redirect($this->redirectTo())
            ->with('status', 'Login success.');
    }

    /**
     * The path/URI to the page of the login form.
     *
     * @return string
     */
    protected function loginPath()
    {
        return route('admin.login');
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @return string
     */
    protected function redirectTo()
    {
        return route('backpack.dashboard');
    }
}
