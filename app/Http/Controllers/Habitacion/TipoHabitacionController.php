<?php

namespace App\Http\Controllers\Habitacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habitacion\ValidateTipoHabitacion;
use App\Models\TipoHabitacion;
use Illuminate\Support\Facades\DB;

class TipoHabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginate_number = 10;
        $search = $request->get('search');
        if (!empty($search)) {
            $tipohabitacion = TipoHabitacion::where('nombre', 'LIKE', '%' . $search . '%')
                ->paginate($paginate_number);
        } else {
            $tipohabitacion = TipoHabitacion::orderBy('id')
                ->paginate($paginate_number);
        }
        return view('habitacion.tipohabitacion.index', compact('tipohabitacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('habitacion.tipohabitacion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateTipoHabitacion $request)
    {
        TipoHabitacion::create($request->all());
        return redirect()
            ->route('tipohabitacion')
            ->with('success', 'Agregado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipohabitacion = Tipohabitacion::findOrFail($id);
        return view('habitacion.tipohabitacion.show', compact('tipohabitacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipohabitacion = Tipohabitacion::findOrFail($id);
        return view('habitacion.tipohabitacion.edit', compact('tipohabitacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateTipoHabitacion $request, $id)
    {
        Tipohabitacion::findOrFail($id)
            ->update($request->all());
        return redirect()
            ->route('tipohabitacion')
            ->with('success', 'Menú actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            Tipohabitacion::destroy($id);
            return response()->json(['mensaje' => 'ok']);
        } else {
            abort(404);
        }
    }
}
