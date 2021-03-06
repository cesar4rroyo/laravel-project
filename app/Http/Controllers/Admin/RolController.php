<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;

class RolController extends Controller
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
            $rol = Rol::where('nombre', 'LIKE', '%' . $search . '%')
                ->paginate($paginate_number);
        } else {
            $rol = Rol::orderBy('id')
                ->paginate($paginate_number);
        }
        return view('admin.rol.index', compact('rol'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rol.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Rol::create($request->all());
        return redirect()
            ->route('rol')
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
        $rol = Rol::findOrFail($id);
        return view('admin.rol.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        return view('admin.rol.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Rol::findOrFail($id)
            ->update($request->all());
        return redirect()
            ->route('rol')
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
            $rol = Rol::findOrFail($id);
            $rol->persona()->detach();
            $rol->delete();
            return response()->json(['mensaje' => 'ok']);
        } else {
            abort(404);
        }
    }
}
