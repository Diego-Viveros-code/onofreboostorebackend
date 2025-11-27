<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Para utilizar factories en el modelo se debe invocar e injectar
use Illuminate\Database\Eloquent\Factories\hasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    // se indica que la clave primaria es book_id
    protected $primaryKey = 'order_items_id';

    // campos a ser llenados
    protected $fillable = ["order_id", "book_id", "quantity", "price"];

    // lo que se agrega en hidden no se mostrara en las solicitudes
    protected $hidden = ["created_at", "updated_at", "deleted_at"];

    // se indica que el FK pertenece a categorias
    public function books(){
        return $this->belongsTo(Books::class);
    }

    public function order(){
        return $this->belongsTo(Orders::class);
    }
}
