<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('user')->group(function(){
    Route::controller(App\Http\Controllers\User\FormController::class)->group(function(){
        Route::post('/eligibility','eligibilitySubmit');
        Route::post('/education','education_level');
        Route::post('/personal','personal_info');
        Route::post('/contact','contact_info');
        Route::post('/spouse','spouse_info');
        Route::post('/children','children_info');

        Route::post('/spouse/form','spouseSubmit');
        Route::post('/child/form','childInfoSubmit');

    });
});

Route::get("/check",function(){
    $database = DB::connection()->getDatabaseName();

    return response()->json([
        "database"=>$database,
        "Hello"=>"world"
    ]);
});
