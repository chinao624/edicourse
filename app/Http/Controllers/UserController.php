<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(){
        return view('users.create');
    }


public function store(Request $request)
{
    $validatedData = $request->validate([
        'nickname' => 'required|string|max:255',
        'password' => 'required|string|min:8',
        'name' => 'required|string|max:255',
        'name_kana' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'school' => 'required|in:Middle,High,College',
        'pref' => 'required|string|max:255',
        'school_name' => 'nullable|string|max:255',
        'birth_year' => 'required|integer|digits:4',
        'birth_month' => 'required|integer|between:1,12',
        'birth_day' => 'required|integer|between:1,31',
        'introduction' => 'required|string',
        'icon' => 'nullable|image|max:2048',
    ]);
    
    $validatedData['password'] = Hash::make($validatedData['password']);

    $user = User::create([
        'nickname' => $validatedData['nickname'],
            'password' => $validatedData['password'],
            'name' => $validatedData['name'],
            'name_kana' => $validatedData['name_kana'],
            'email' => $validatedData['email'],
            'school' => $validatedData['school'],
            'pref' => $validatedData['pref'],
        ]);

    if ($request->hasFile('icon')) {
        $validatedData['icon'] = $request->file('icon')->store('icons', 'public');
    }

    Profile::create([
        'user_id' => $user->id, 
        'school_name' => $validatedData['school_name'] ?? null,
        'birth_year' => $validatedData['birth_year'],
        'birth_month' => $validatedData['birth_month'],
        'birth_day' => $validatedData['birth_day'], 
        'introduction' => $validatedData['introduction'],
        'icon' => $validatedData['icon'] ?? null,
    ]);
    
    return redirect()->route('login')->with('success','学生エディター登録が完了しました！');

}

// マイページ
public function showMypage()
{
    $user = Auth::user();
    $articles = Article::where('user_id', $user->id)->with('review')->get();

    return view('auth.mypage', compact('user', 'articles'));
}


// ユーザー情報変更画面
public function edit()
{
    $user = Auth::user();
    return view('auth.mypage_edit', compact('user'));
    
}

//ユーザー情報の更新
public function update(Request $request)
{
    $user = Auth::user();
    // dd($user);
    $validatedData = $request->validate([
        'nickname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
    ]);

    //saveがうまくいかないのでupdateでやってみる
    if($request->filled('password')){
        $validatedData['password'] = Hash::make($request->password);
    }else{
        //パスワードが空の場合バリデートされたデータから削除
        unset($validatedData['password']);
    }

    $user->update($validatedData);

    return redirect()->route('mypage')->with('success','プロフィールが更新されました');
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 関連するデータを削除
        Profile::where('user_id', $user->id)->delete();
        Article::where('user_id', $user->id)->delete();
        $user->delete();

        return redirect('/')->with('message', 'アカウントが削除されました。');
    }


}

