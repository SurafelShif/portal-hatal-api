<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

//->middleware('role:admin')

Route::post("/login", [AuthController::class, 'login']);
Route::get("user", [UserController::class, 'user'])->middleware(['auth:api']);

Route::controller(WebsiteController::class)
    ->prefix("websites")->group(function () {
        Route::get("/", "index");
        Route::post("/", "store")->middleware(['auth:api', 'role:admin']);
        Route::delete("/{uuid}", "delete")->middleware(['auth:api', 'role:admin']);
        Route::put("/{uuid}", "update")->middleware(['auth:api', 'role:admin']);
    });
Route::controller(UserController::class)
    ->prefix("users")->middleware(['auth:api', 'role:admin'])->group(function () {
        Route::get("/admins", "index"); //getAdmins
        Route::get("/users", "getUsers");
        Route::post("/{uuid}", "store");
        Route::delete("/{uuid}", "delete");
    });
