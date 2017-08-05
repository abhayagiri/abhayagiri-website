<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
        Log::debug(Session::getId());
        Log::debug($request->session()->all());
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/admin/login')->with('status', 'Error: ' . $e->getMessage());
        }
        $localUser = User::where('email', $user->email)->first();
        $request->session()->flash('status', 'Task was successful!');
        Log::debug($request->session()->all());
        if ($localUser) {
            Auth::login($localUser, true);
            return redirect('/admin')->with('status', 'Login success.');
        } else {
            return redirect('/admin/login')->with('status', 'User with email ' . $user->email . ' not authorized.');
        }
    }

}
