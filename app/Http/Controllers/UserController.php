<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRegisterPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User as UserModel;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //

      public function index()
   {
      return view('user.register');
   }

      // データベース登録処理
   public function register(UserRegisterPost $request)
   {
      // validate済みのデータの取得
      $datum = $request->validated();
      // var_dump($datum);exit;
      $datum['password'] = Hash::make($datum['password']);

      // user_id の追加
      $datum['user_id'] = Auth::id();

    
      // テーブルへのINSERT
      try {
         $r = UserModel::create($datum);
      } catch (\Throwable $e) {
         // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
         echo $e->getMessage();
         exit;
      }

      // タスク登録成功
      $request->session()->flash('front.user_register_success', true);

      // リダイレクト
      return redirect('/');
   }
}
