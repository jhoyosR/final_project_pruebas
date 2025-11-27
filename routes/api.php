<?php

use App\Http\Controllers\API\CategoryAPIController;
use App\Http\Controllers\API\ProductAPIController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para administración de categorias
Route::resource('categories', CategoryAPIController::class)->except('edit');
// Rutas para administración de productos
Route::resource('products', ProductAPIController::class)->except('edit');
