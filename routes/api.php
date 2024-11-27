<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RahtalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::post("login", [AuthController::class, 'login']);
Route::get("user", [UserController::class, 'user'])->middleware(['auth:api']);

Route::controller(WebsiteController::class)
    ->prefix("websites")
    ->group(function () {
        Route::post("/", "store")->middleware(['role:admin', 'auth:api']);
        Route::post("/update", "update")->middleware(['role:admin', 'auth:api']);
        Route::get("/", "index");
        Route::delete("/", "delete")->middleware(['role:admin', 'auth:api']);
    });
Route::controller(UserController::class)
    ->prefix("users")
    ->middleware(['auth:api', 'role:admin'])
    ->group(function () {
        Route::post("/admins", "store");
        Route::get("/admins", "index");
        Route::get("/{personal_number}", "getUserByPersonalNumber");
        Route::delete("/admins", "delete");
    });
Route::controller(RahtalController::class)
    ->prefix("rahtal")
    ->group(function () {
        Route::post("/{uuid}", "update")->middleware(['auth:api', 'role:admin']);
        Route::get("/", "index");
    });
