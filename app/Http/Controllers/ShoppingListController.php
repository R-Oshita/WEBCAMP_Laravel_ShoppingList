<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingLists as ShoppingListsModel;
use App\Http\Requests\ShoppingRegisterPost;
use Illuminate\Support\Facades\DB;
use App\Models\CompletedShoppingList as CompletedShoppingListModel;


class ShoppingListController extends Controller
{
    //
    public function list()
    {
        // メモ：タスク管理機能で言うTaskController.phpにあたる

        // 1Page辺りの表示アイテム数を設定
        $per_page = 2;

        // 一覧の取得
        // $list = $this->getListBuilder()
        //     ->paginate($per_page);
        // ->get();

        $list = ShoppingListsModel::where('user_id', Auth::id())
            ->orderBy('name')
            ->orderBy('created_at')
            ->paginate($per_page);
        // ->get();
        //echo "<pre>\n"; var_dump($sql, $list); exit;
        // var_dump($sql);


        return view('shopping_list.list', ['list' => $list]);
    }

    /**
     * タスクの新規登録
     */
    public function register(ShoppingRegisterPost $request)
    {
        // validate済みのデータの取得
        $datum = $request->validated();


        // //
        // $user = Auth::user();
        // $id = Auth::id();
        // var_dump($datum, $user, $id); exit;

        // // user_id の追加
        $datum['user_id'] = Auth::id();

        // // テーブルへのINSERT
        try {
            $r = ShoppingListsModel::create($datum);
        } catch (\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
            exit;
        }

        // // タスク登録成功
        $request->session()->flash('front.task_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');
    }


    // 一覧用のIlluminate\Database\Rloquent\Builderインスタンスの取得
    // protected function getListBuilder()
    // {
    //     return ShoppingModel::where('user_id', Auth::id())
    //         ->orderBy('priority', 'DESC')
    //         ->orderBy('period')
    //         ->orderBy('created_at');
    // }

    /**
     * 「単一のタスク」Modelの取得
     */
    protected function getItemModel($item_id)
    {
        // task_idのレコードを取得する
        $item = ShoppingListsModel::find($item_id);
        if ($item === null) {
            return null;
        }
        // 本人以外のタスクならNGとする
        if ($item->user_id !== Auth::id()) {
            return null;
        }

        return $item;
    }


    // タスクの完了
    public function complete(Request $request, $item_id)
    {

        try {
            DB::beginTransaction();

            // item_idのレコードを取得する
            $item = $this->getItemModel($item_id);
            if ($item === null) {
                throw new \Exception('');
            }

    

            // tasks側を削除する
            $item->delete();

            // completed_tasks側にinsertする
            $dask_datum = $item->toArray();
            // 必要な registered_at を元の created_at の値で追加する

            unset($dask_datum['id']);
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);

            // ここで $dask_datum には user_id, name, registered_at のみが含まれる

            $r = CompletedShoppingListModel::create($dask_datum);

            if ($r === null) {
                throw new \Exception('');
            }

            // 以下の echo '処理成功'; exit; はデバッグ用なので、処理を成功させるために削除します
            // echo '処理成功';
            // exit;

            DB::commit(); // トランザクション終了
   // 完了メッセージ出力
            $request->session()->flash('front.shopping_completed_success', true);
        } catch (\Throwable $e) {
            // デバッグ用の一時停止コードを削除し、ロールバックとリダイレクトに戻します
            // var_dump($e->getMessage());
            // exit; 

            DB::rollBack();

            $request->session()->flash('front.shopping_completed_failure', true);
        }

        // 一覧に移動する
        return redirect('/shopping_list/list');
    }


        /**
     * 削除処理
     */
    public function delete(Request $request, $item_id)
    {
        // task_idのレコードを取得する
        $item = $this->getItemModel($item_id);
        // タスクを削除する
        if ($item !== null) {
            $item->delete();
            $request->session()->flash('front.item_delete_success', true);
        }
        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }

}
