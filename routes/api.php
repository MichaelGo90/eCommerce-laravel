<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

Route::resource('products', ProductController::class);
//Route::get('/products', [ProductController::class, 'list']);
//Route::post('/products', [ProductController::class, 'create']);
//Route::get('/products/{id}', [ProductController::class, 'show']);
//Route::put('/products/{id}', [ProductController::class, 'update']);
