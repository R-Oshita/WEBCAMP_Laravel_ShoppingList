<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Task as TaskModel;

class AuthController extends Controller
{

    // ログイン前画面表示
    public function index()
    {
        return view('index');
    }

    // ログイン処理
    public function login(LoginPostRequest $request)
    {
        $datum = $request->validated();
        // var_dump($datum); exit;

        // 認証に失敗した場合
        if (Auth::attempt($datum) === false) {
            return back()
                ->withInput()
                ->withErrors(['auth' => 'emailかパスワードに誤りがあります']);
        }

        // 認証に成功した場合
        $request->session()->regenerate();
        return redirect()->intended('/shopping_list/list');
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerateToken(); // CSRFトークンの再生成
        $request->session()->regenerate(); // セッションIDの再生成
        return redirect(route('front.index'));
    }

  
}
