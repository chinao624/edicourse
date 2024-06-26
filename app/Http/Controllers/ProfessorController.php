<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfessorController extends Controller
{
    public function create()
    {
        return view('professors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_kana' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:professors,email',
            'business' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $professor = new Professor();
        $professor->name = $request->name;
        $professor->name_kana = $request->name_kana;
        $professor->password = Hash::make($request->password);
        $professor->email = $request->email;
        $professor->business = $request->business;
        $professor->title = $request->title;
       
        if ($request->hasFile('icon')) {
            $professor->icon = $request->file('icon')->store('icons', 'public');
        }

        $professor->save();

        return redirect()->route('professor.login')->with('success', 'オブザーバー登録が完了しました。');
    }

    public function showMypage()
{
    $professor = auth('professor')->user();
    return view('professors.mypage', compact('professor'));
}
}

