<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    CategoryController, ProductController
};

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


// Route::get('/categories', [CategoryController::class, 'index']);
// Route::post('/categories', [CategoryController::class, 'store']);
// Route::put('/categories/{id}', [CategoryController::class, 'update']);
// Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


Route::group(['prefix' => 'v1'], function() {
    Route::get('categories/{id}/products', [CategoryController::class, 'products']);
    Route::apiResource('categories', CategoryController::class, [
        'except' => ['create', 'edit']
    ]);
    
    Route::apiResource('products', ProductController::class, [
        'except' => ['create', 'edit']
    ]);
});





