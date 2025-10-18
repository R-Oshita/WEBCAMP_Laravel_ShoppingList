<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompletedShoppingList as CompletedShoppingListModel;

class CompletedShoppingListController extends Controller
{
    //
     

    public function list(){

             // 1ページ当たりの表示数を設定
        $per_page = 2;
        
        // completedlisttaskテーブルから情報を取得
        $list = CompletedShoppingListModel::where('user_id', Auth::id())
            ->orderBy('name')
            ->orderBy('created_at','ASC')
            ->paginate($per_page);

         return view('completed_shopping_list',['completed_shopping_list'=> $list]);
    }
    
}
