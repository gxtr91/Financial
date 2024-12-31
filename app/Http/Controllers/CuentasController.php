<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class CuentasController extends Controller
{
    public function __invoke(){

        $cuentas=Cuenta::all();
        $columns = ['Cuenta','Presupuesto','Limite','Alerta','Dia de pago','Opciones'];
        $json  = '[
            {"data": "nombre_cuenta", "name": "nombre_cuenta"},
            {"data": "fecha_pago", "name": "fecha_pago"},
            {"data": "acciones", "name": "acciones", "className": "text-right",  "width": "70px"},
        ]';
        $ctx=[
            'cuentas' => $cuentas,
            'columns' => $columns,
            'json' => $json,
        ];

        return view('cuentas.index',$ctx);
    }

    function json(Request $request){
        $query= new Cuenta();
        if ($request->has('cuenta')) {
            $query->where('es_presupuesto', 'si');
        }
        $cuentas = $query->get();
        return Datatables::of($cuentas)
        ->addColumn('acciones', function($row){
            // Aquí puedes definir el contenido de la columna de acciones
            $acciones = '<div class="col-sm-6 col-xl-4">
            <div class="dropdown">
              <button type="button" class="btn btn-icon dropdown-toggle" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="fa fa-fw fa-cog" aria-hidden="true"></span>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-align-primary" style="">
              <a class="dropdown-item delete" href="javascript:void(0)" data-id="' . $row->id . '">Eliminar</a>
            </div>
          </div>';
            return $acciones;
        })
        ->rawColumns(['acciones'])
        ->toJson();
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'descripcion' => 'required|string',

        ]);


        // Crear la nueva cuenta
        Cuenta::create([
            'nombre_cuenta' => $request->nombre_cuenta,
            'descripcion' => $request->descripcion,
            'es_presupuesto' => $request->has('active') ? 'si' : NULL,
            'limite' => $request->limite ? $request->limite : 0.00,
            'alerta' => $request->alerta ? $request->alerta : 0.00,
            'fecha_pago' => $request->dia_pago ? $request->dia_pago : NULL,
        ]);

        // Respuesta exitosa
        return response()->json(['success' => true]);
    }

    public function update(Request $request){
        $id = $request->input('id');
        try {
            $cuenta = Cuenta::find($id);

            if ($request->input('data')==0){
                $cuenta->nombre_cuenta=$request->input('value');
            }
            if ($request->input('data')==2){
                $cuenta->limite=$request->input('value');
            }
            if ($request->input('data')==3){
                if ($request->input('value')>=$cuenta->limite) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La alerta debe ser menor que el límite.'
                    ]);
                }else{
                    $cuenta->alerta = $request->input('value');
                }
            }
            if ($request->input('data')==4){
                $cuenta->fecha_pago = $request->input('value') ? $request->input('value') : null;
            }

           // $request->input('data')==0 ?  $cuenta->nombre_cuenta=$request->input('value') :  $cuenta->limite=$request->input('value');

            $cuenta->save();
            return response()->json(['success' => true, 'message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'error']);
        }

    }
}