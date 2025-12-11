<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrdersController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\UppercaseName;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return "Simple GET test";
});

//------------------------- FILTROS Y BUSQUEDAS ------------------------- //

// metodos de busqueda todos los libros con sus categorias    
Route::get("/query/method/join_books_category", [QueriesController::class, 'join_books_category']);

// ordenar resultados 
Route::get("/query/method/groupby", [QueriesController::class, 'groupBy']);

//------------------------- CRUD LIBROS ------------------------- //

Route::apiResource("/books", BookController::class)->middleware([LogRequests::class]);

//------------------------- ORDEN ------------------------- //

// crear orden
Route::post('/order/create-order', [OrdersController::class, 'createOrder']);

// verificar pago (sin webhook)
Route::get('/order/{order_id}/check-status', [OrdersController::class, 'checkAdamsPayStatus']);

// verificar pago (webhook)
Route::post('/adamspay/webhook', [OrdersController::class, 'webhook']);

// trae todas las ordenes del usuario tal
Route::get('/order/user/{user_id}', [OrdersController::class, 'getOrdersByUser']);
