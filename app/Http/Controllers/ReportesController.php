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


    public function json(Request $request) {
        $subQuery = Cuenta::select([
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
            ->groupBy('cuentas.id', 'cuentas.descripcion', 'cuentas.nombre_cuenta', 'cuentas.limite');
    
        // Usa el subquery como base para DataTables
        $cuentas = DB::table(DB::raw("({$subQuery->toSql()}) as cuentas"))
            ->mergeBindings($subQuery->getQuery())
            ->where(function($query) use ($request) {
                $search = strtolower($request->get('search')['value'] ?? '');
    
                if ($search) {
                    $query->whereRaw('LOWER(nombre_cuenta) LIKE ?', ["%$search%"])
                          ->orWhereRaw('LOWER(descripcion) LIKE ?', ["%$search%"])
                          ->orWhereRaw('CAST(limite AS CHAR) LIKE ?', ["%$search%"])
                          ->orWhereRaw('CAST(sumatoria_transacciones AS CHAR) LIKE ?', ["%$search%"])
                          ->orWhereRaw('CAST(saldo_disponible AS CHAR) LIKE ?', ["%$search%"]);
                }
            });
    
        return DataTables::of($cuentas)
           
            ->make(true);
    }

    public function graficos() {
        $cuentas = Cuenta::select('nombre_cuenta', DB::raw("COALESCE(SUM(transacciones.monto), 0) as sumatoria_transacciones"))
            ->leftJoin('transacciones', 'cuentas.id', '=', 'transacciones.id_cuenta')
            ->groupBy('cuentas.id', 'cuentas.nombre_cuenta')
            ->get();
    
        $labels = $cuentas->pluck('nombre_cuenta')->toArray();
        $data = $cuentas->pluck('sumatoria_transacciones')->toArray();
    
        return view('reportes.gastosGrafico', compact('labels', 'data'));
    }
    
}