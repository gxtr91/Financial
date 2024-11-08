<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use GuzzleHttp\Client;


class TransaccionesController extends Controller
{
    public function __invoke(){
        $cuentas = Cuenta::orderBy('nombre_cuenta','asc')->get();
        $usuarios = User::all();
        $ctx=[
            'usuarios'=>$usuarios,
            'cuentas'=>$cuentas
        ];
        return view ('transacciones.lista',$ctx);
    }

    function json(Request $request){
        $usuario = Auth::user();
        $query = Transaccion::with('cuenta', 'usuario');
        if ($request->has('cuenta') && !empty($request->cuenta)) {
            $query->where('id_cuenta', $request->cuenta);
        }

        if ($request->has('usuario') && !empty($request->usuario)) {
            $query->where('id_user', $request->usuario);
        }

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('fecha', [$request->startDate, $request->endDate]);
        }
        $query->orderBy('fecha', 'desc');

        $cuentas = $query->get();
        return Datatables::of($cuentas)
        ->toJson();
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        // Validar los datos
        $request->validate([
            'cuenta' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required',
        ]);

        // Crear la nueva cuenta
        $transaccion=Transaccion::create([
            'id_cuenta' => $request->cuenta,
            'id_user' =>  $usuario->id,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
        ]);



        if ($transaccion){
            $cuenta_enviada = Cuenta::find($request->cuenta);
            $presupuesto_cuenta = $cuenta_enviada->limite;

            $inicioMes = Carbon::now()->startOfMonth()->toDateString();
            $finMes = Carbon::now()->endOfMonth()->toDateString();

            $gasto_mes=Transaccion::whereBetween('fecha', [$inicioMes, $finMes])->where('id_cuenta',$request->cuenta)->sum('monto');

            $diferencia_actual=$presupuesto_cuenta-$gasto_mes;

            $this->sendTelegram($cuenta_enviada->nombre_cuenta,$request->descripcion,$request->monto,$presupuesto_cuenta,$gasto_mes,$diferencia_actual);



        }
        // Respuesta exitosa
        return response()->json(['success' => true]);
    }

    public function sendTelegram($cuenta,$descripcion,$monto,$presupuesto_cuenta,$gasto_mes,$diferencia_actual)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage";

        $response = $client->post($url, [
            'form_params' => [
                'chat_id' => env('TELEGRAM_CHANNEL_ID'),
                'text' => "<b>Nuevo registro</b>\n Cuenta: " . $cuenta . "\n Descripcion: " . $descripcion . " \n Monto: " . number_format($monto, 2, '.', ',')." \n\n Presupuesto asignado: " . number_format($presupuesto_cuenta, 2, '.', ',')."\n Gasto del mes acumulado: ".number_format($gasto_mes, 2, '.', ',')."\n Deficit o Superavit: ".number_format($diferencia_actual, 2, '.', ','),
                'parse_mode' => 'HTML'
            ]
        ]);

        return $response->getBody();
    }
}
