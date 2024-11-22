<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


class NotaController extends Controller
{
    public function index()
    {
        $notas = Nota::orderBy('fecha', 'asc')->with('responsable');
        $usuarios=User::all();
        return view('notas.index', compact('notas','usuarios'));
    }

    public function json(Request $request)
    {
        $notas = Nota::query()->with('responsable');

        // Filtrar por fecha
        if ($request->filled('fecha')) {
            $notas->whereDate('fecha', $request->input('fecha'));
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $notas->where('completada', $request->input('estado'));
        }

        // Filtrar por prioridad
        if ($request->filled('prioridad')) {
            $notas->where('prioridad', $request->input('prioridad'));
        }

        // Filtrar por responsable
        if ($request->filled('responsable')) {
            $notas->where('id_responsable', $request->input('responsable'));
        }

        if ($request->filled('search.value')) {
            $notas->where('titulo', 'like', '%' . $request->input('search.value') . '%');
        }

        $columns = ['completada', 'titulo', 'fecha'];
        $orderColumn = $columns[$request->input('order.0.column')];
        $orderDir = $request->input('order.0.dir', 'asc');
        $notas = $notas->orderBy($orderColumn, $orderDir);

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $total = $notas->count();

        $notas = $notas->skip($start)->take($length)->get();


        // Mapeo para incluir los datos del responsable en la respuesta
        $data = $notas->map(function ($nota) {
            $editButton = '<button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Edtar" data-bs-original-title="Editar" onclick="openNotaModal(' . $nota->id . ')">
                            <i class="fa fa-pencil-alt"></i>
                    </button>';
        $deleteButton = '<button type="button" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip" aria-label="Eliminar" data-bs-original-title="Eliminar" onclick="deleteNota(' . $nota->id . ')">
                            <i class="fa fa-times"></i>
                    </button>';

        $actionButtons = '<div class="btn-group">' . $editButton . $deleteButton . '</div>';
            return [
                'id' => $nota->id,
                'titulo' => $nota->titulo,
                'descripcion' => $nota->descripcion,
                'fecha' => $nota->fecha,
                'prioridad' => $nota->prioridad,
                'completada' => $nota->completada ? 1 : 0,
                'responsable' => $nota->responsable ? $nota->responsable->name : 'Sin asignar', // Asegúrate de que el modelo User tenga un campo `name`

                'actions' => $actionButtons, // Agrega los botones de acción
            ];
        });

        // Retorna el formato esperado por DataTables
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'prioridad' => 'required|string|in:alta,baja',
        ]);

        Nota::create($request->all());
        return redirect()->route('notas.index');
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);
        $nota->update($request->only(['titulo', 'descripcion', 'fecha', 'prioridad','id_responsable']));
        //return redirect()->route('notas.index');
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();
        return redirect()->route('notas.index');
    }

    public function markAsCompleted(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);
        $nuevaCompletada = !$nota->completada;
        $nota->update(['completada' => $nuevaCompletada]);

        return response()->json([
            'success' => true,
            'nuevaCompletada' => $nuevaCompletada,
        ]);
    }

    public function show($id)
    {
        return Nota::findOrFail($id);
    }


}