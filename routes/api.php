<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
});



Route::group(['middleware' => ["auth:api"]], function () {
    //auth metodos
    Route::controller(AuthController::class)->group(function () {
        Route::get('auth/profile', 'userProfile');
        Route::post('auth/logout', 'logout');
    });
    //metodos de usuario
    Route::controller(UserController::class)->group(function () {
        Route::post('auth/changePassword', 'changePassword');
        Route::post('auth/check-password', 'checkThePassword');
        Route::put('auth/editProfile', 'editProfile');
    });
    //metodos de contactos
    Route::controller(ContactController::class)->group(function () {
        Route::get('contact', 'index');
        Route::post('contact', 'store');
        Route::get('contact/{id}', 'show');
        Route::put('contact/{id}', 'update');
        Route::delete('contact/{id}', 'destroy');
    });
});

//auth metodos
