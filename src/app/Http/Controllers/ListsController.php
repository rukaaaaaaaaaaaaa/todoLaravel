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

        //バリデーションする（文字数、必須をやる）


        // 保存
        $todo = Lists::create([
            'title'  => $title,
            'status' => false, 
        ]);

        // 保存したレコードを返す
        return response($title, 200);
    }
}
