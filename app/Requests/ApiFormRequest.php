<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): never{
        throw new HttpResponseException(response()->json([
            'message' => "Error de validación",
            'errors' => $validator->errors()

        ], Response::HTTP_UNPROCESSABLE_ENTITY));

    }
}
