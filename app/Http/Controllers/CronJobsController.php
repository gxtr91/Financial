<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;

class CronJobsController extends Controller
{

    private function sendTelegram($msg)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage";

        $response = $client->post($url, [
            'form_params' => [
                'chat_id' => env('TELEGRAM_CHANNEL_ID'),
                'text' => $msg,
                'parse_mode' => 'HTML'
            ]
        ]);

        return $response->getBody();
    }

    public function notas(Request $request){
        $tokenEnviado = $request->query('token');

        if ($tokenEnviado !== config('app.cron_job_token')) {
            return response()->json(['error' => 'Acceso no autorizado'], 403);
        }

        $notas = Nota::where('fecha', date('Y-m-d'))
        ->where('completada',0)
        ->orderBy('prioridad','DESC')
        ->select('titulo')
        ->get();

        //return dd($notas);

        if (!$notas->isEmpty()) {
            $mensaje = "";
            $x=1;
            foreach ($notas as $nota) {
                $mensaje .= "<b>".$x."-". $nota['titulo'] . ".</b>\n"; // <b> para negrita y \n para una nueva línea
                $x++;
            }
            $mensaje="<b>❗Recordatorio de actividades pendientes❗</b>\n\n" . $mensaje;
            $this->sendTelegram($mensaje);

        }
        //$this->sendTelegram($mensaje);
        //return  response()->json($notas);
       // return $mensaje;
    }

    public function correrNotas(Request $request){
        $tokenEnviado = $request->query('token');

        if ($tokenEnviado !== config('app.cron_job_token')) {
            return response()->json(['error' => 'Acceso no autorizado'], 403);
        }

        date_default_timezone_set('America/Guatemala');
        $notas = Nota::where('fecha', date('Y-m-d'))->where('completada',0)->get();

        foreach ($notas as $nota) {
            $nuevaFecha = Carbon::parse($nota->fecha)->addDay(); // Sumar un día a la fecha actual
            $nota->fecha = $nuevaFecha; // Actualizar el campo 'fecha'
            $nota->save(); // Guardar los cambios en la base de datos
        }

        //return  response()->json($notas);

    }
}