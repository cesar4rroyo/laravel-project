<?php

namespace App\Http\Controllers\Control;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Concepto;
use App\Models\Habitacion;
use App\Models\Persona;
use App\Models\Procesos\Caja;
use App\Models\Procesos\Movimiento;
use Carbon\Carbon;

class CajaController extends Controller
{

    public function index()
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')->whereHas('concepto', function ($q) {
                $q->where('id', 2);
            })
            ->latest('created_at')->first()->toArray();

        $cajas =
            Caja::with('concepto', 'usuario', 'persona', 'movimiento')
            ->where('fecha', '>', $caja['fecha'])
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        // dd($cajas);

        return view('control.caja.index', compact('cajas'));
    }

    public function indexLista()
    {
        $cajas =
            Caja::with('concepto', 'usuario', 'persona', 'movimiento')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        return view('control.caja.movimientos.index', compact('cajas'));
    }


    public function create()
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($caja['concepto_id'] != '2') {
            $caja = Caja::latest('id')->first();
            if (!is_null($caja)) {
                $caja->get()->toArray();
                $numero = $caja['numero'] + 1;
                $numero = $this->zero_fill($numero, 8);
            } else {
                $numero = $this->zero_fill(5000, 8);
            }
            $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
            $today = Carbon::now();
            $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
            return view('control.caja.create', compact('numero', 'conceptos', 'personas', 'today'));
        } else {
            return redirect()
                ->route('caja')
                ->with('error', 'No se ha aperturado la caja');
        }
    }


    public function store(Request $request)
    {
        $caja = Caja::create([
            'fecha' => $request->fecha,
            'numero' => $request->numero,
            'concepto_id' => $request->concepto,
            'tipo' => $request->tipo,
            'persona_id' => $request->persona,
            'total' => $request->total,
            'comentario' => $request->comentario,
            'usuario_id' => session()->all()['usuario_id'],

        ]);
        return redirect()
            ->route('caja')
            ->with('success', 'Registro agregado correctamente');
    }


    public function show($id)
    {
        $caja = Caja::findOrFail($id);
        return view('control.caja.show', compact('caja'));
    }


    public function edit($id)
    {
        $cajaApertura =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($cajaApertura['concepto_id'] != '2') {
            $caja = Caja::findOrFail($id);
            $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
            $today = Carbon::now()->toDateString();
            $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
            return view('control.caja.edit', compact('caja', 'conceptos', 'personas', 'today'));
        } else {
            return redirect()
                ->route('caja')
                ->with('error', 'La caja ya fue cerrada');
        }
    }


    public function update(Request $request, $id)
    {
        $caja = Caja::findOrFail($id);

        $caja->update([
            'fecha' => $request->fecha,
            'numero' => $request->numero,
            'concepto_id' => $request->concepto,
            'tipo' => $request->tipo,
            'persona_id' => $request->persona,
            'total' => $request->total,
            'comentario' => $request->comentario,
            'usuario_id' => session()->all()['usuario_id'],

        ]);
        return redirect()
            ->route('caja')
            ->with('success', 'Registro actualizado correctamente');
    }


    public function destroy(Request $request, $id)
    {
        $cajaApertura =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($cajaApertura['concepto_id'] != '2') {
            if ($request->ajax()) {
                Caja::destroy($id);
                return response()->json(['mensaje' => 'ok']);
            } else {
                abort(404);
            }
        }
    }

    public function create_cierre(Request $request)
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($caja['concepto_id'] != '2') {
            $total = $request->total;
            if (!empty($total)) {
                $caja = Caja::latest('id')->first();
                if (!is_null($caja)) {
                    $caja->get()->toArray();
                    $numero = $caja['numero'] + 1;
                    $numero = $this->zero_fill($numero, 8);
                } else {
                    $numero = $this->zero_fill(300, 8);
                }
                $caja =
                    Caja::with('concepto', 'usuario', 'persona', 'movimiento')->get()->toArray();
                $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
                $today = Carbon::now()->toDateString();
                $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
                return view('control.caja.cierre.create', compact('numero', 'total', 'conceptos', 'personas', 'today'));
            }
        }
        return redirect()
            ->route('caja')
            ->with('error', 'La caja ya se cerró, abra otra de nuevo');
    }
    public function zero_fill($valor, $long = 0)
    {
        return str_pad($valor, $long, '0', STR_PAD_LEFT);
    }
    public function create_apertura()
    {
        $cajaValidar =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($cajaValidar['concepto_id'] == '2') {
            $caja = Caja::latest('id')->first();

            if (!is_null($caja)) {
                $caja->get()->toArray();
                $numero = $caja['numero'] + 1;
                $numero = $this->zero_fill($numero, 8);
            } else {
                $numero = $this->zero_fill(1, 8);
            }

            $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
            $today = Carbon::now()->toDateString();
            $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
            return view('control.caja.apertura.create', compact('numero', 'conceptos', 'personas', 'today'));
        }
        return redirect()
            ->route('caja')
            ->with('error', 'Hay una caja abierta, cierrela y crea otra de nuevo');
    }

    public function createCheckout(Request $request, $id)
    {

        $movimiento = Movimiento::findOrFail($id);
        $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
        $today = Carbon::now()->toDateString();
        $total = $request->total;
        $habitacion = $request->habitacion_id;
        $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();

        $movimiento->update([
            'fechasalida' => $request->fechasalida,
            'dias' => $request->dias,
            'total' => $request->total,
            'situacion' => 'En espera pago',
        ]);

        return view('control.caja.checkout.create', compact('habitacion', 'conceptos', 'id', 'personas', 'today', 'total'));
    }
    public function checkout(Request $request, $id)
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();

        if ($caja['concepto_id'] != '2') {
            $habitacion = $request->habitacion;
            $caja = Caja::create([
                'fecha' => $request->fecha,
                'numero' => $request->numero,
                'concepto_id' => $request->concepto,
                'tipo' => $request->tipo,
                'persona_id' => $request->persona,
                'total' => $request->total,
                'comentario' => $request->comentario,
                'usuario_id' => session()->all()['usuario_id'],
                'movimiento_id' => $id,
            ]);
            return view('control.caja.checkout.updatehabitacion', compact('habitacion'));
        }
        return redirect()
            ->route('habitaciones')
            ->with('error', 'La caja no ha sido aperturada');
    }
    public function updateHabitacion(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->update([
            'situacion' => 'En limpieza',

        ]);
        return redirect()
            ->route('caja')
            ->with('success', 'Registro agregado correctamente');
    }
    public function addFromDetallePdto($id)
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();
        if ($caja['concepto_id'] != '2') {
            $cart = session()->get('cart');
            $total = 0;
            foreach ($cart as $key => $item) {
                $total += ($item['precio'] * $item['cantidad']);
            }
            $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
            $today = Carbon::now()->toDateString();
            $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
            return view('control.caja.producto.create', compact('conceptos', 'id', 'personas', 'today', 'total'));
        }
        return redirect()
            ->route('habitaciones')
            ->with('error', 'La caja no ha sido aperturada');
    }
    public function addFromDetalleService($id)
    {
        $caja =
            Caja::with('movimiento', 'persona', 'concepto')
            ->latest('created_at')->first()->toArray();
        if ($caja['concepto_id'] != '2') {
            $cart = session()->get('servicio');
            $total = 0;
            foreach ($cart as $key => $item) {
                $total += ($item['precio'] * $item['cantidad']);
            }

            $conceptos = Concepto::with('caja')->orderBy('nombre')->get();
            $today = Carbon::now()->toDateString();
            $personas = Persona::with('caja', 'reserva', 'movimiento')->orderBy('nombres')->get();
            return view('control.caja.servicio.create', compact('conceptos', 'id', 'personas', 'today', 'total'));
        }
        return redirect()
            ->route('habitaciones')
            ->with('error', 'La caja no ha sido aperturada');
    }
    public function storeProducto(Request $request)
    {
        $caja = Caja::create([
            'fecha' => $request->fecha,
            'numero' => $request->numero,
            'concepto_id' => $request->concepto,
            'tipo' => $request->tipo,
            'persona_id' => $request->persona,
            'total' => $request->total,
            'comentario' => $request->comentario,
            'usuario_id' => session()->all()['usuario_id'],
            'movimiento_id' => $request->movimiento,

        ]);
        session()->pull('cart', []);

        return redirect()
            ->route('caja')
            ->with('success', 'Registro agregado correctamente');
    }
    public function storeServicio(Request $request)
    {
        $caja = Caja::create([
            'fecha' => $request->fecha,
            'numero' => $request->numero,
            'concepto_id' => $request->concepto,
            'tipo' => $request->tipo,
            'persona_id' => $request->persona,
            'total' => $request->total,
            'comentario' => $request->comentario,
            'usuario_id' => session()->all()['usuario_id'],
            'movimiento_id' => $request->movimiento,

        ]);
        session()->pull('servicio', []);
        return redirect()
            ->route('caja')
            ->with('success', 'Registro agregado correctamente');
    }
}