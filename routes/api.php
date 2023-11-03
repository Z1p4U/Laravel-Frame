<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix("v1")->group(function () {

    Route::middleware('jwt')->group(function () {

        Route::controller(AuthController::class)->group(function () {
            Route::post('register', "register");
            Route::get('user-lists', 'showUserLists');
            Route::get('your-profile', 'yourProfile');
            Route::put('edit', "edit");
            Route::get('user-profile/{id}', 'checkUserProfile');
            Route::put("change-password", 'changePassword');
            Route::post("logout", 'logout');
        });


        Route::controller(PhotoController::class)->prefix("photo")->group(function () {
            Route::post("store", 'store');
            Route::delete("delete/{id}", 'destroy');
            Route::post('multiple-delete', 'deleteMultiplePhotos');
            Route::get("trash", 'trash');
            Route::patch("deleted-photo/{id}", "deletedPhoto");
            Route::patch("restore/{id}", "restore");
            Route::post("force-delete/{id}", "forceDelete");
            Route::post("clear-trash", "clearTrash");
        });
    });

    Route::post('login', [AuthController::class, 'login']);

    Route::controller(PhotoController::class)->prefix("photo")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });
});
