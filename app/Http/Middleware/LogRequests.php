<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // Diego - se ejecuta antes de llegar al controlador
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            // ver url
            'url' => $request->fullUrl(),
            // ver ip
            'ip' => $request->ip(),
            // ver tipo de metodo
            'method' => $request->method(),
            // ver info de cabeceras
            'headers' => $request->headers->all(),
            // ver el cuerpo
            'body' => $request->getContent(),
        ];

        // dd($data);
        Log::info("Solicitud Recibida: ", $data);

        return $next($request);
    }

    // Diego - se ejecuta luego de pasar por el controlador
    public function terminate(Request $request, Response $response){
        Log::info("Respuesta enviada: ",[
            "status" => $response->getStatusCode(),
            'content' => $response->getContent()
        ]);
    }


}
