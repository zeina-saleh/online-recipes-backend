<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipesController;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::get('/logout', [AuthController::class, "logout"]);


Route::group(["middleware" => "auth:api"], function () {
    Route::get('/getRecipe/{recipeId?}', [RecipesController::class, 'getRecipe']);
});
