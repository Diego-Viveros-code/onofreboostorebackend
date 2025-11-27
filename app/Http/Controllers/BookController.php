<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Books;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);
        $books = Books::inRandomOrder()->paginate($perPage, ['*'], 'page', $page);
        return response()->json($books);
    }

    public function store(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|max:2000',
                'cover' => 'required|max:2000',
                'price' => 'required|numeric',
                'category_id' => 'required|exists:category,category_id'
            ], [
                ".required" => 'El titulo del libro es obligatorio.',
                'tittitlele.string' => 'El titulo debe ser una cadena de texto.',
                'title.max' => 'El titulo no puede superar los 255 caracteres.',
                'description.required' => 'La descripción es obligatoria',
                'description.max' => 'La descripción no puede superar los 2000 caracteres.',
                'cover.required' => 'La descripción es obligatoria',
                'cover.max' => 'La descripción no puede superar los 2000 caracteres.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'category_id.required' => 'La categoría es obligatoria.',
                'category_id.exists' => 'La categoría seleccionada no es válida.',
            ]);

            $book = Books::create($validatedData);

            return response()->json($book);
        } catch (ValidationException $e) {
            return response()->json(["errors" => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // $request son los datos enviados desde el cliente
    public function update(UpdateBookRequest $request, Books $book)
    {
        try {
            $datosValidados = $request->validated();
            $book->update($datosValidados);

            return response()->json(["message" => "Libro actualizado exitosamente", "book" => $book]);
        } catch (Exception $e) {
            return response()->json(["error" => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Books $book)
    {
        $book->delete();
        return response()->json(["message" => "Libro eliminado"]);
    }
}
