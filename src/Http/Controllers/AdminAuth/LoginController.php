<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/home';


    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function showLoginForm()
    {
        return view('admin-auth.login');
    }


    protected function guard()
    {
        return Auth::guard('admin_user');
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/admin/login');
    }
}