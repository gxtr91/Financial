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
        }else {
            // Si no hay filtros de fecha, usar desde el día 1 del mes actual hasta hoy
            $startDate = Carbon::now()->startOfMonth()->format('Y-m-d'); // Día 1 del mes actual
            $endDate = Carbon::now()->format('Y-m-d'); // Fecha actual
            $query->whereBetween('fecha', [$startDate, $endDate]);
        }
        $query->orderBy('fecha', 'desc');
        $cuentas = $query->get();
        $sumaMonto = $query->sum('monto');
        // Retornar los resultados en formato JSON
        return Datatables::of($cuentas)
        ->addColumn('acciones', function($row){
            $acciones = '<div class="btn-group">
                            <button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Eliminar" data-bs-original-title="Eliminar" onclick="deleteTransaction(' . $row->id . ')">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>';
            return $acciones;
        })
        ->rawColumns(['acciones'])
        ->with(['sumaMonto' => $sumaMonto]) // Incluir la suma como dato adicional
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

    public function update(Request $request){
        $id = $request->input('id');
        try {
            $transaccion = Transaccion::find($id);

            if ($request->input('data')==2){
                $transaccion->descripcion=$request->input('value');
            }
            if ($request->input('data')==3){
                $transaccion->monto=$request->input('value');
            }
         

           // $request->input('data')==0 ?  $cuenta->nombre_cuenta=$request->input('value') :  $cuenta->limite=$request->input('value');

            $transaccion->save();
            return response()->json(['success' => true, 'message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'error']);
        }

    }

    public function destroy($id)
    {
        $transaccion = Transaccion::findOrFail($id);
        // Obtener el mes y año de la fecha de la transacción

        $fechaTransaccion = Carbon::parse($transaccion->fecha); 

        $transaccionMes = $fechaTransaccion->format('m'); // 'm' devuelve el mes (01-12)
        $transaccionAnio = $fechaTransaccion->format('Y'); // 'Y' devuelve el año (YYYY)


        // Obtener el mes y año actuales
        $mesActual = now()->format('m');  // Mes actual
        $anioActual = now()->format('Y'); // Año actual

        // Verificar si el mes y año de la transacción son del mes y año actuales
        if ($transaccionMes == $mesActual && $transaccionAnio == $anioActual) {
            // Si la fecha de la transacción es del mes y año actual, proceder con la eliminación
            $transaccion->delete();
            return response()->json(['success' => 'Transacción eliminada!']);
        }else{
            return response()->json(['success' => 'No puedes eliminar transacciones de periodos anteriores!']);

        }

        //return redirect()->route('notas.index');
    }
}
