<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    /** @use HasFactory<\Database\Factories\CuentaFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nombre_cuenta',
        'prioridad',
        'descripcion',
        'es_presupuesto',
        'fecha_pago',
        'limite',
        'alerta'
    ];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'id_cuenta');
    }
}