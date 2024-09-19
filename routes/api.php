<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, 'login']);
Route::middleware('auth:api')->get("/user", [UserController::class, 'user']);

Route::controller(WebsiteController::class)
    ->prefix("websites")->group(function () {
        Route::get("/", "index");
        Route::post("/", "store");
        Route::delete("/{id}", "delete");
        Route::put("/{id}", "update");
    });
Route::controller(UserController::class)
    ->prefix("users")->group(function () {
        Route::get("/admins", "index");
        Route::get("/users", "getUsers");
        Route::post("/{id}", "store");
        Route::delete("/{id}", "delete");
    });
