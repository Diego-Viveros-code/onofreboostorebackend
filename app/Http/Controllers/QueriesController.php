<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Books;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class QueriesController extends Controller
{

    // obtener todos los libros
    public function get()
    {
        $books = Books::all();
        return response()->json($books);
    }

    // obtener libro por id
    public function getById(int $id)
    {

        $book = Books::find($id);

        if (!$book) {
            return response()->json(["message" => "Libro no encontrado"], Response::HTTP_NOT_FOUND);
        }

        return response()->json($book);
    }

    // obtener solo los nombres de libros
    public function getNames()
    {
        $books = Books::select("title")
            ->orderBy("title", "desc")
            ->get();

        return response()->json($books);
    }

    // buscar por nombre
    public function searchTitle(string $title, float $price)
    {
        $books = Books::where("titile", $title)
            ->where("price", ">", $price)
            ->orderBy("description")
            ->select("titile", "description")
            ->get();

        return response()->json($books);
    }

    // busqueda por descripcion o por titulo, por uno de los dos
    public function searchString(string $value)
    {
        $books = Books::where("description", "like", "%{$value}%")
            ->orWhere("title", "like", "%{$value}%")
            ->get();
        return response()->json($books);
    }

    public function advancedSearch(Request $request)
    {
        $books = Books::where(function ($query) use ($request) {
            if ($request->input("title")) {
                $query->where("title", "like", "%{$request->input("title")}%");
            }
        })
            ->where(function ($query) use ($request) {
                if ($request->input("description")) {
                    $query->where("description", "like", "%{$request->input("description")}%");
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->input("price")) {
                    $query->where("price", ">", $request->input("price"));
                }
            })
            ->get();

        return response()->json($books);
    }

    public function join()
    {
        $books = Books::join("category", "books.category_id", "=", "category.category_id")
            ->select("books.*", "category.name as category")
            ->get();

        return response()->json($books);
    }

    public function join_books_category()
    {
        $books = Books::join("category", "books.category_id", "=", "category.category_id")
            ->select("books.*", "category.name as category")
            ->get();
        return response()->json($books);
    }
}
