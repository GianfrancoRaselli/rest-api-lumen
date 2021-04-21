<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = "libros";

    protected $fillable = [
        'titulo',
        'imagen',
        'id_usuario',
    ];

    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario');
    }
}
