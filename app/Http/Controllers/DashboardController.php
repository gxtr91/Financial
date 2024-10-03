<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;
use App\Models\Transaccion;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function __invoke() {
        $presupuesto=Cuenta::sum('limite');
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();
        $finMes = Carbon::now()->endOfMonth()->toDateString();
        $gasto_mes=Transaccion::whereBetween('fecha', [$inicioMes,$finMes])->sum('monto');
        $diferencia=$presupuesto-$gasto_mes;

         //Mes anterior
         Carbon::setLocale('es');
         $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth()->toDateString();
         $finMesAnterior = Carbon::now()->subMonth()->endOfMonth()->toDateString();
         $gasto_anterior=Transaccion::whereBetween('fecha', [$inicioMesAnterior,$finMesAnterior])->sum('monto');
         $diferencia_anterior=$presupuesto-$gasto_anterior;

         //Todas las cuentas
         $cuentas=Cuenta::all();
        $ctx=[
            'presupuesto'=>$presupuesto,
            'gasto_mes'=> $gasto_mes,
            'diferencia'=> $diferencia,
            'gasto_anterior'=>$gasto_anterior,
            'diferencia_anterior'=>$diferencia_anterior,
            'mes_actual'=>Carbon::now()->translatedFormat('F Y'),
            'mes_anterior'=>Carbon::now()->subMonth()->translatedFormat('F Y'),
            'cuentas'=>$cuentas,
        ];
        return view('dashboard',$ctx);
    }
}
