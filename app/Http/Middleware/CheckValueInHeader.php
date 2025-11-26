<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Response;

class CheckValueInHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $number, $some): Response
    {
        if ($request->header(key: "token") !== "123456") {
            return response()->json(["message" => "Acceso denegado ".$number." ".$some], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
