<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Http\Requests\StoreListsRequest;
use App\Http\Requests\UpdateListsRequest;
use Illuminate\Support\Facades\Auth;

class ListsController extends Controller
{
    /**
     * ToDo一覧表示
     */
    public function index (Request $request){ 
        $userId = Auth::id();
        //パラメーターから値を取り出す
        $q = $request->query('q');
        $folderId = $request->query('folder_id'); 
        $query = Lists::where('user_id', $userId);
        //フォルダIDがあれば絞る
        if (!is_null($folderId) && $folderId !== '') {
            $query->where('folder_id', $folderId);
        }
        //検索キーワードがある場合はキーワードで絞る
        if (!is_null($q) && $q !== '') {
        $query->where('title', 'like', '%'.$q.'%');
        }
        return $query->get();
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
            'user_id' =>  Auth::id()
        ]);

        // 保存失敗時
        if (! $todo) {
            return response()->json([
                'error' => 'ToDoの保存に失敗しました。'
            ], 500);
        }

        // 正常時
        return response()->json([
            'message' => 'ToDoを追加しました。',
            'data' => $todo
        ], 201);

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

        // フォルダ名 が送られてきた場合だけ更新
        if ($request->exists('folder_id')) {
            $folderId = $request->input('folder_id');
            $todo->folder_id = $folderId !== '' ? $folderId : null;
        }

        // 保存
        $todo->save();

        // 更新後のレコードを返す
        return response()->json($todo, 200);
    }
}
