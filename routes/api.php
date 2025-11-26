<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\BookController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\UppercaseName;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return "Simple GET test";
});

//------------------------- SECUENCIA DE FILTROS Y BUSQUEDAS ------------------------- //

// retorna todos los libros
Route::get("/query", [QueriesController::class, "get"]);

// busqueda por un id
Route::get("/query/{id}", [QueriesController::class, "getById"]);

// retorna todos los nombres solamente
Route::get("/query/method/names", [QueriesController::class, "getNames"]);

// busqueda por nombre y por precio
Route::get("/query/method/search/{name}/{price}", [QueriesController::class, "searchName"]);

// busqueda por descripcion o por nombre, por uno de los dos
Route::get("/query/method/searchString/{value}", [QueriesController::class, "searchString"]);

// Busqueda condicional busqueda por nombre y por descripion y por precio
// util cuando se tiene varios parametros de busqueda y solo si se envia los parametros - SQL condicional
Route::post("/query/method/advancedSearch", [QueriesController::class, "advancedSearch"]);

// metodos de busqueda todos los libros con sus categorias    
Route::get("/query/method/join_books_category", [QueriesController::class, 'join_books_category']);

// ordenar resultados 
Route::get("/query/method/groupby", [QueriesController::class, 'groupBy']);

//------------------------- CRUDS ------------------------- //
// al tener apiResource agrega los metodos CRUD pero depende del tipo de metodo 
// GET POST PUT DELETE hay que especificar a donde se dirige
Route::apiResource("/books", BookController::class)->middleware([LogRequests::class]);

//------------------------- CREACION Y LGIN ------------------------- //

// se habilita la ruta para registrar usuarios
Route::post("/register", [AuthController::class, 'register']);

// ruta para el login, hay que agregarle un nombre para que el framework lo pueda encontrar
Route::post("/login", [AuthController::class, 'login'])->name("login");