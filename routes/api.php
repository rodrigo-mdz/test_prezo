<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/recipes', [RecipeController::class, 'createRecipe']);
Route::get('/recipes', [RecipeController::class, 'getSortedRecipes']);
Route::get('/recipes/most-profitable', [RecipeController::class, 'getMostProfitableRecipe']);
Route::get('/recipes/least-profitable', [RecipeController::class, 'getLeastProfitableRecipe']);
