<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

#[Middleware('guest:professor', except:['logout'])]
class ProfessorLoginController extends Controller
{
    
    public function showLoginForm() {
        return view('auth.professor_login');
    }

    public function login(Request $request) {
         $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('professor')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'メールアドレスもしくはパスワードが間違っています。',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('professor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

