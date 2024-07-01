<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reviewer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReviewerController extends Controller
{
    public function create()
    {
        return view('reviewer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:reviewers,email',
            'title' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            // 必要に応じて他のフィールドを追加
        ]);
    
        $reviewer = new Reviewer();
        $reviewer->name = $request->name;
        $reviewer->email = $request->email;
        $reviewer->title = $request->title;
        $reviewer->password = Hash::make($request->password);
        // 必要に応じて他のフィールドを設定
    
        $reviewer->save();
    
        // ログインページにリダイレクト
        return redirect()->route('reviewer.login')->with('success', 'アカウントが正常に作成されました。ログインしてください。');
}
}
