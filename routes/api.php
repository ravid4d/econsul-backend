<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Container\Attributes\DB;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('/google/login', 'handleGoogleCallback');
    Route::post('/login/otp', 'loginWithOtp');
    Route::post('/login/verify-otp', 'verifyOtp');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
Route::prefix('user')->group(function () {

    Route::controller(App\Http\Controllers\User\DashboardController::class)->group(function () {

        Route::post('/dashboard', 'index');
        Route::get('/applicant/year', 'ApplicantYear');
        Route::get('/applicant/{id}/dashboard', 'ApplicantDetailDashboard');
        Route::post('/applicant-details/pdf', 'idDashboardPDF');


    });

    Route::controller(App\Http\Controllers\User\FormController::class)->group(function () {
        Route::post('/eligibility', 'eligibilitySubmit');
        Route::post('/education', 'education_level');
        Route::post('/personal', 'personal_info');
        Route::post('/contact', 'contact_info');
        Route::post('/spouse', 'spouse_info');
        Route::post('/children', 'children_info');

        Route::post('/spouse/form', 'spouseSubmit');
        Route::post('/child/form', 'childInfoSubmit');

        Route::post('/applicant/photo', 'photoUpdate');

        Route::post('/final/submit', 'finalSubmission');
    });
    Route::controller(App\Http\Controllers\User\GetFormController::class)->group(function () {
        Route::get('/applicant/detail', 'applicantDetail');
        Route::get('/{id}/applicant/show', 'showApplicantDetail');
        Route::get('/spouse/detail', 'spouseDetail');
        Route::get('/{id}/spouse/show', 'showSpouseDetail');
        Route::get('/child/detail', 'childDetail');
        Route::get('/{id}/child/show', 'showChildDetail');
        Route::get('/applicant/{id}/photo', 'applicantPhoto');
    });

    Route::controller(App\Http\Controllers\User\PutFormController::class)->group(function () {
        Route::put('/childinfo/update', 'childInfoUpdate');
    });

    Route::controller(App\Http\Controllers\User\DeleteFormController::class)->group(function () {

        Route::delete('/applicant/{id}/delete', 'applicantdelete');
        Route::delete('/applicant/{id}/photo', 'applicantPhotoDelete');

    });
    Route::controller(App\Http\Controllers\ProfileController::class)->group(function () {
        Route::post('/profile-picture', 'profilePhotoUpdate')->middleware('auth:sanctum');
        Route::post('/profile', 'profileUpdate')->middleware('auth:sanctum');
        Route::post('/send-email-verification-code', 'sendEmailVerificationCode')->middleware('auth:sanctum');
        Route::post('/verify-email', 'verifyEmail')->middleware('auth:sanctum');
        Route::post('/send-mobile-verification-code','sendMobileVerificationCode')->middleware('auth:sanctum');
        Route::post('/verify-mobile','verifyMobile')->middleware('auth:sanctum');
    
    });
    Route::controller(App\Http\Controllers\User\CopyApplicationController::class)->group(function () {
        Route::get('/copy/application', 'copyApplicant'); 
    });

    // Route::get("/check", function () {
    //     $database = DB::connection()->getDatabaseName();
    //     return response()->json([
    //         "database" => $database,
    //         "Hello" => "world"
    //     ]);
    // });
});
