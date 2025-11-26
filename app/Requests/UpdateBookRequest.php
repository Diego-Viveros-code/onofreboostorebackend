<?php

namespace App\Http\Requests;

class UpdateBookRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|max:2000',
            'cover' => 'required|max:2000',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:category,id'
        ];
    }

    public function messages(){
        return [
            "title.required" => 'El titulo del libro es obligatorio.',
            'title.string' => 'El titulo debe ser una cadena de texto.',
            'title.max' => 'El titulo no puede superar los 255 caracteres.',
            'description.required' => 'La descripción es obligatoria',
            'description.max' => 'La descripción no puede superar los 2000 caracteres.',
            'cover.required' => 'La descripción es obligatoria',
            'cover.max' => 'La descripción no puede superar los 2000 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }


}
