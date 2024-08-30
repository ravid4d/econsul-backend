<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/check',[App\Http\Controllers\AuthController::class,'check']);
