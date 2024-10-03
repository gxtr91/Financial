<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\User;

use App\Models\Transaccion;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ReportesController extends Controller
{
    public function __invoke(){
        Carbon::setLocale('es');
        $cuentas = Cuenta::all();
        $usuarios = User::all();
        $ctx=[
            'usuarios'=>$usuarios,
            'cuentas'=>$cuentas,
            'mes_actual'=>Carbon::now()->translatedFormat('F Y')
        ];
        return view ('reportes.gastos_mensuales',$ctx);
    }


    public function json(Request $request){
        $cuentas = Cuenta::select([
            'nombre_cuenta',
            'limite',
            'cuentas.descripcion',
            DB::raw("COALESCE(SUM(transacciones.monto), 0) AS sumatoria_transacciones"),
            DB::raw("limite - COALESCE(SUM(transacciones.monto), 0) AS saldo_disponible")
        ])
        ->leftJoin('transacciones', function($join) {
            $join->on('cuentas.id', '=', 'transacciones.id_cuenta')
                 ->whereMonth('transacciones.fecha', Carbon::now()->month)
                 ->whereYear('transacciones.fecha', Carbon::now()->year);
        })
        ->groupBy('cuentas.id','cuentas.descripcion','cuentas.nombre_cuenta', 'cuentas.limite');

    return DataTables::of($cuentas)
        ->addColumn('action', function($cuenta) {
            return '<button class="btn btn-info">Acción</button>';
        })
        ->rawColumns(['action']) // Asegúrate de agregar aquí cualquier columna que tenga HTML como contenido
        ->make(true);
    }
}