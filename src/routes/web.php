<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListsController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/create', [ListsController::class, 'store']);