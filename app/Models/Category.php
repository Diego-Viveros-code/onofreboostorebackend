<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Para utilizar factories en el modelo se debe invocar e injectar
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    // Se invoca mas arriba y aqui se injecta la fabrica para poder acceder a sus miembros
    use HasFactory;

    // se indica primeramente como se llama la table en base de datos
    protected $table = 'category';

    protected $primaryKey = 'category_id';

    // se le indica cuales son los campos que se puede rellenar, solamente estos campos
    protected $fillable = ["name"];

    // se indica cardinalidad uno a muchos para book
    public function books(){
        return $this->hasMany(Books::class);
    }
}
