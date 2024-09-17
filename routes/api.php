<?php

use App\Http\Controllers\SiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(SiteController::class)
    ->prefix("sites")->group(function () {
        Route::post("/", "store");
    });
