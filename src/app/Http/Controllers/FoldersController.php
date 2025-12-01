<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateListsRequest;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FoldersController extends Controller
{
    //一覧表示
    public function index (Request $request){ 
        $userId = Auth::id();
        $folders = Folder::where('user_id', $userId)->get();
        return response()->json($folders, 200);
    }

    //フォルダー保存
    public function store(Request $request){

        // 値を取り出す
        $name = $request->input('name');

        //バリデーション
         $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // 保存
        $folder = Folder::create([
            'name'  => $name,
            'user_id' =>  Auth::id()
        ]);

        // 保存失敗時
        if (! $folder) {
            return response()->json([
                'error' => 'フォルダの作成に失敗しました。'
            ], 500);
        }

        // 正常時
        return response()->json([
            'message' => 'フォルダを追加しました。',
            'data' => $folder
        ], 201);
    }

    public function show(int $id)
    {
        return view('welcome', [
            'currentFolderId' => $id,
        ]);
    }

    //フォルダ削除

    //フォルダ名更新 
}
