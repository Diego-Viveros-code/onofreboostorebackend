<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(UserRequest $request){

        // aqui estan los datos ya validados
        $validatedData = $request->validated();

        // se crea una variable user para poder insertarlos en su modelo
        $user = User::create([
            'name' => $validatedData["name"],
            'email' => $validatedData["email"],
            'password' => bcrypt($validatedData["password"])
        ]);

        // se retorna mensaje exitoso
        return response()->json(["message" => "Usuario registrado correctamente"], Response::HTTP_CREATED);
    }
}
