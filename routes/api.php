<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ConsultancyController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\FinanceCategoryController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VerifiAccountController;

//route for auth controller

Route::post('register', [RegisteredUserController::class, 'store']);
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('reset-password', [NewPasswordController::class, 'store']);
Route::get('success-password', function(){return view('auth/success-password');})->name('success-password');
Route::get('success-verification', function(){return view('auth/success-verification');})->name('success-verification');
Route::get('ResendVerifyEmail', [RegisteredUserController::class, 'resendVerifyEmail'])->middleware('auth:sanctum');

//route for user controller

Route::middleware('auth:sanctum')->group(function(){
    Route::get('user', [AuthenticatedSessionController::class, 'show']);
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update']);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::put('update-profile', [RegisteredUserController::class, 'update']);
    Route::put('apply-plan', [RegisteredUserController::class, 'applyPlan']);
});

//route for plan controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('plan', 'App\Http\Controllers\PlanController');
});

//route for category controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('finance', 'App\Http\Controllers\FinanceController');
});

//route for category controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('category', 'App\Http\Controllers\CategoryController');
});

//route for finance category controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('financeCategory', 'App\Http\Controllers\FinanceCategoryController');
    Route::delete('financeCategory/{category}/{finance}', [FinanceCategoryController::class, 'removeCategory']);
});

//route for consultant controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('consultant', 'App\Http\Controllers\ConsultantController');
});


//route for consultantReport controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('consultingReport', 'App\Http\Controllers\ConsultingReportController');
});


//route for contacts controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('contact', 'App\Http\Controllers\ContactController');
});

//route for consultancy controller

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('consultancy', 'App\Http\Controllers\ConsultancyController');
    Route::get('consultacy/{consultingReport}', [ConsultancyController::class, 'index']);
});

//route for verifi email controller
Route::middleware('auth:sanctum')->group(function(){
    Route::get('verifyEmail/{pin}', [VerifiAccountController::class, 'store']);
});