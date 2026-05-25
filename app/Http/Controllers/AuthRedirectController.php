<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class AuthRedirectController extends Controller
{
    //

    public function redirect(Request $request){
        return redirect()->route("login");
    }
    public function dashboardRedirect(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return view('admin.index');
        } elseif ($user->role === 'beneficiary') {
            return view('beneficiary.index');
        } elseif ($user->role === 'coordinator') {
            return view('coordinator.index');
        } else {
            return view('applicant.index');
        }
    }

    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }
}
