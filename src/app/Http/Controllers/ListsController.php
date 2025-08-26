<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;

class ListsController extends Controller
{
    /**
     * ToDoを保存する
     */
    public function store(Request $request){

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
}
