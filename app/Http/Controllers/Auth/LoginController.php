<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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