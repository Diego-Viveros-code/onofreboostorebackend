<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Para utilizar factories en el modelo se debe invocar e injectar
use Illuminate\Database\Eloquent\Factories\hasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model 
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'orders';

    // se indica que la clave primaria es book_id
    protected $primaryKey = 'order_id';
    
    // campos a ser llenados
    protected $fillable = ["user_id", "total", "status", "cover"];

    // lo que se agrega en hidden no se mostrara en las solicitudes
    protected $hidden = ["created_at", "updated_at"];

    // se indica que el FK pertenece a categorias
    public function user(){
        return $this->belongsTo(User::class);
    }
}
