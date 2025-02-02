<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

      protected $redirectTo = '/home';
    // 'admin' => '/admin',
    // 'user' => '/home',
   public function redirectTo()
    {
        //$rollen = Auth()->user();
        $redirects = [
    'admin' => '/admin',
    'user' => '/home',
        ];

        $roles = Auth()->user()->roles->map->name;

        foreach ($redirects as $role => $url) {
            if ($roles->contains($role)) {
                return $url;
            }
        }
        return '/login';
         
    } 

 public function logout()
    {
        Auth()->logout();
        return redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function loggedOut(Request $request)
    // {
    //     dd('d')
    // }
}
