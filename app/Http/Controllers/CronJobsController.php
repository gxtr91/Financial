<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\User;
use GuzzleHttp\Client;


class CronJobsController extends Controller
{

    private function sendTelegram($notas)
    {
        $client = new Client();
        $url = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage";

        $response = $client->post($url, [
            'form_params' => [
                'chat_id' => env('TELEGRAM_CHANNEL_ID'),
                'text' => "<b>❗Recordatorio de actividades pendientes❗</b>\n\n" . $notas,
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

        $mensaje = "";
        $x=1;
        foreach ($notas as $nota) {
            $mensaje .= "<b>".$x."-". $nota['titulo'] . "</b>\n"; // <b> para negrita y \n para una nueva línea
            $x++;
        }
        $this->sendTelegram($mensaje);
        //return  response()->json($notas);
       // return $mensaje;
    }
}