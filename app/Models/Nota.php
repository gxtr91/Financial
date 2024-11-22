<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;
    protected $fillable = [
        'titulo',        // Asegúrate de incluir este campo
        'descripcion',
        'fecha',
        'id_responsable',
        'prioridad',
        'completada',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'id_responsable','id');
    }
}