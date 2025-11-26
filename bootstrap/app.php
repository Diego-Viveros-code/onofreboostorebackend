<?php

use App\Http\Middleware\CheckValueInHeader;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // se debe crear una nueva ruta para las apis
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // aqui el middleware es global para todos
    ->withMiddleware(function (Middleware $middleware): void {
        //$middleware->append(CheckValueInHeader::class);
        $middleware->alias([
            "checkvalue"=> CheckValueInHeader::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
