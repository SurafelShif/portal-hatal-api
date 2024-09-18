<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(WebsiteController::class)
    ->prefix("websites")->group(function () {
        Route::get("/", "index");
        Route::post("/", "store");
        Route::delete("/{id}", "delete");
        Route::put("/{id}", "update");
    });
