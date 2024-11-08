<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Transaccion extends Model
{
    use HasFactory;
    protected $table = 'transacciones';
    public $timestamps = false;

    protected $fillable = [
        'id_cuenta',
        'id_user',
        'monto',
        'descripcion',
        'fecha'
    ];


    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'id_cuenta','id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_user','id');
    }

}
