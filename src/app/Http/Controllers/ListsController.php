<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Http\Requests\StoreListsRequest;

class ListsController extends Controller
{
    /**
     * ToDoを保存する
     */
    public function store(StoreListsRequest $request){

        // 値を取り出す
        $title = $request->input('title');

        // 保存
        $todo = Lists::create([
            'title'  => $title,
            'status' => false, 
        ]);

        // 保存したレコードを返す
        return response($title, 200);
    }

    /**
     * ToDoを削除する
     */
    public function destroy(int $id){

        // idのレコードを検索
        $todo = Lists::find($id);

        //レコード見つからなかった場合
        if (! $todo) {
            return response()->json([
                'error' => "ID {$id} のToDoは存在しません。"
            ], 404);
        }

        // レコード削除
        $todo->delete();
        return response()->noContent();
    }
}
