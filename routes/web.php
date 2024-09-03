<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user_pdf');
});
Route::get('/check',[App\Http\Controllers\AuthController::class,'check']);
