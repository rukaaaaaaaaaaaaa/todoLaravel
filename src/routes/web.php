<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ListsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

Route::get('/', function () {
    return view('welcome');
});

Route::post('/create', [ListsController::class, 'store']);

Route::get('/lists', [ListsController::class, 'index']);

Route::delete('/delete/{id}', [ListsController::class, 'destroy']);

Route::patch('/update/{id}', [ListsController::class, 'update']);

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
