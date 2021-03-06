<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ValidateOpcionMenu;
use App\Models\GrupoMenu;
use App\Models\OpcionMenu;
use Illuminate\Support\Facades\DB;

class OpcionMenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $paginate_number = 10;
        if (!empty($search)) {
            $opcionmenu =
                OpcionMenu::where('grupomenu_id', '=', $search)
                ->paginate($paginate_number);
            $grupomenu = GrupoMenu::with('opcionmenu')->get();
        } else {
            $opcionmenu =
                OpcionMenu::with('grupomenu')
                ->orderBy('grupomenu_id')
                ->paginate($paginate_number);
            $grupomenu = GrupoMenu::with('opcionmenu')->get();
        }
        return view('admin.opcionmenu.index', compact('opcionmenu', 'grupomenu'));
    }


    public function create()
    {
        $grupomenu = GrupoMenu::with('opcionmenu')->get();
        return view('admin.opcionmenu.create', compact('grupomenu'));
    }


    public function store(ValidateOpcionMenu $request)
    {
        $opcionmenu = OpcionMenu::create([
            'nombre' => $request->nombre,
            'link' => $request->link,
            'icono' => $request->icono,
            'orden' => $request->orden,
            'grupomenu_id' => $request->grupomenu_id
        ]);

        return redirect()
            ->route('opcionmenu')
            ->with('success', 'Agregado correctamente');
    }


    public function show($id)
    {
        $opcionmenu = OpcionMenu::findOrFail($id);
        return view('admin.opcionmenu.show', compact('opcionmenu'));
    }


    public function edit($id)
    {
        $grupomenu = GrupoMenu::with('opcionmenu')->get();
        $opcionmenu = OpcionMenu::findOrFail($id);
        return view('admin.opcionmenu.edit', compact('grupomenu', 'opcionmenu'));
    }


    public function update(ValidateOpcionMenu $request, $id)
    {
        $opcionmenu = OpcionMenu::findOrFail($id)
            ->update([
                'nombre' => $request->nombre,
                'link' => $request->link,
                'icono' => $request->icono,
                'orden' => $request->orden,
                'grupomenu_id' => $request->grupomenu_id
            ]);

        return redirect()
            ->route('opcionmenu')
            ->with('success', 'Actualizado correctamente');
    }


    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            OpcionMenu::destroy($id);
            return response()->json(['mensaje' => 'ok']);
        } else {
            abort(404);
        }
    }
}
