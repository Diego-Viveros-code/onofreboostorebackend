<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Para utilizar factories en el modelo se debe invocar e injectar
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Books extends Model 
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'books';

    // se indica que la clave primaria es book_id
    protected $primaryKey = 'book_id';
    
    // campos a ser llenados
    protected $fillable = ["title", "description", "price", "cover", "category_id"];

    // lo que se agrega en hidden no se mostrara en las solicitudes
    protected $hidden = ["created_at", "updated_at", "deleted_at"];

    // se indica que el FK pertenece a categorias
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
