<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IlluminateFoundationAuthAuthenticatesUsers;

class AuthenticatedSessionController extends Controller
{

  
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */

    protected function hash($string){
        return hash('sha256', $string . config('app.encryption_key'));
    }

    protected function login(Request $request){
        $user = User::where([
               'email' => $request->request('email'), 
               'password' => $this->hash($request->input('password')) 
        ])->first();
        Auth::login($user);
        $token = $user->createToken('MyApp')->accessToken;
        return response()->json(compact('token', 'user'));
    }


    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
       
        $request->authenticate();

        $request->session()->regenerate();

        
       // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
