<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["prefix" => "auth"], function () {
    Route::post("/register", [AuthController::class, 'register']);
    Route::post("/login", [AuthController::class, 'login'])->name("login");
});

Route::post('/roles', [RoleController::class, 'store']);
Route::group(["prefix" => "task"], function () {
    Route::middleware("auth:api")->put("/store", [TaskController::class, 'store']);
    Route::middleware("auth:api")->post("/{task_id}", [TaskController::class, 'update']);
    Route::middleware("auth:api")->delete("/{task_id}", [TaskController::class, 'destroy']);

});