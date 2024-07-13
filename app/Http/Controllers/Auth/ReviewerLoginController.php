<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

#[Middleware('guest:reviewer', except:['logout'])]
class ReviewerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.reviewer_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('reviewer')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'メールアドレスもしくはパスワードが間違っています。',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('reviewer')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
