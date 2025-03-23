<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post("/login",[App\Http\Controllers\userController::class,"login"]);
Route::post("/logout", [App\Http\Controllers\userController::class, "logout"]);
Route::post("/register",[App\Http\Controllers\userController::class,"store"]);

Route::get("/allEtudiants",[App\Http\Controllers\userController::class,"allEtudiants"]);
Route::get("/allProfs",[App\Http\Controllers\userController::class,"allProfs"]);
Route::delete("/delete/{id}",[App\Http\Controllers\userController::class,"destroy"]);


    Route::post("/sendCodeEmail", [App\Http\Controllers\userController::class, "sendCodeViaEmail"]);
    Route::post("/verifiyCode", [App\Http\Controllers\userController::class, "verifiedCodeUserWithCodeEmail"]);
Route::middleware('auth:api')->group( function () {
   
    Route::get("/allUser",[App\Http\Controllers\userController::class,"index"]);
    Route::post("/sendMessage",[App\Http\Controllers\messsageController::class,"store"]);
    Route::post("/allMessages",[App\Http\Controllers\messsageController::class,"index"]);


});
