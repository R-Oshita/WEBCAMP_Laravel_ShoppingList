<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginPostRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

     public function index(){
        return view('admin.index');
    }

/**
     * ログイン処理
     * 
     */
    public function login(AdminLoginPostRequest $request)
    {
        // validate済

        // データの取得
        $datum = $request->validated();
        // var_dump($datum); exit;
        

        // 認証
        if (Auth::guard('admin')->attempt($datum) === false) {
            return back()
                   ->withInput() // 入力値の保持
                   ->withErrors(['auth' => 'ログインIDかパスワードに誤りがあります。',]) // エラーメッセージの出力
                   ;
        }

        // 認証に成功した場合
        $request->session()->regenerate();
        return redirect()->intended('/admin/top');
    }  

}
