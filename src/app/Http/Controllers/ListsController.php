<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Http\Requests\StoreListsRequest;
use App\Http\Requests\UpdateListsRequest;

class ListsController extends Controller
{
    /**
     * ToDo一覧表示
     */
    public function index (){ 
        return Lists::get();
    }

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
        return response()->json(['title' => $title], 200);
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

    /**
     * ToDoを更新する
     */
    public function update(int $id, UpdateListsRequest $request){

        // idのレコードを検索
        $todo = Lists::find($id);

        //レコード見つからなかった場合
        if (! $todo) {
            return response()->json([
                'error' => "ID {$id} のToDoは存在しません。"
            ], 404);
        }

         // title が送られてきた場合だけ更新
        if ($request->exists('title')) {
            $todo->title = $request->input('title');
        }

        // status が送られてきた場合だけ更新
        if ($request->exists('status')) {
            $todo->status = boolval($request->input('status'));
        }

        // 保存
        $todo->save();

        // 更新後のレコードを返す
        return response()->json($todo, 200);
    }
}
