<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * ToDoを保存する
     */
    public function store(Request $request){
        $title = $request->input('title');
        return response($title, 200);
    }
}
