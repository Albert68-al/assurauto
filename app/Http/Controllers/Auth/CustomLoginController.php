<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class CustomLoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        if($user->isSuperAdmin()){
            return redirect()->route('dashboard');
        } 
        elseif($user->isAdmin()){
            return redirect()->route('dashboard');
        }
        else{
            return redirect()->route('client.dashboard.index');
        }
    }
}
