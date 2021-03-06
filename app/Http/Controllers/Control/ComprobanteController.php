<?php

namespace App\Http\Controllers\Control;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Procesos\Comprobante;
use App\Models\Procesos\NotaCredito;
use Barryvdh\DomPDF\Facade as PDF;


class ComprobanteController extends Controller
{
    
    public function exportPDF($id)
    {
        $comprobante =
            Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
            ->findOrFail($id)
            ->toArray();
        $detalles =
            Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
                ->findOrFail($id)
                ->toArray()['detallecomprobante'];
        $dniRuc = "";
        if ($comprobante['tipodocumento'] == 'factura') {
            $dniRuc = $comprobante['persona']['ruc'];
            $nombre = $comprobante['persona']['razonsocial'];
            $direccion = $comprobante['persona']['direccion'];
            $pdf = PDF::loadView('pdf.factura', compact('comprobante', 'detalles', 'dniRuc', 'nombre', 'direccion'))->setPaper('a4');
        } else {
            if (!is_null($comprobante['persona']['dni'])) {
                $dniRuc = $comprobante['persona']['dni'];
            } else {
                $dniRuc = '';
            }
        }
        if ($comprobante['tipodocumento'] == 'boleta') {
            if ($comprobante['persona']['nombres'] == '-' && !is_null($comprobante['persona']['razonsocial'])) {
                $nombre = $comprobante['persona']['razonsocial'];
            } else {
                $nombre = $comprobante['persona']['nombres'] . ' ' . $comprobante['persona']['apellidos'];
            }
            $direccion = $comprobante['persona']['direccion'];
            $pdf = PDF::loadView('pdf.boleta', compact('comprobante', 'detalles', 'dniRuc', 'direccion', 'nombre'))->setPaper('a4');
        }
        if ($comprobante['tipodocumento'] == 'ticket') {
            if ($comprobante['persona']['nombres'] == '-' && !is_null($comprobante['persona']['razonsocial'])) {
                $nombre = $comprobante['persona']['razonsocial'];
            } else {
                $nombre = $comprobante['persona']['nombres'] . ' ' . $comprobante['persona']['apellidos'];
            }
            $direccion = $comprobante['persona']['direccion'];
            $pdf = PDF::loadView('pdf.ticket', compact('comprobante', 'detalles', 'dniRuc', 'direccion', 'nombre'))->setPaper('a4');
        }

        return $pdf->download('factura.pdf');
    }
    public function index(Request $request)
    {
        $tipo = $request->tipo;
        if (!empty($tipo)) {
            $comprobantes =
                Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
                ->where('tipodocumento', $tipo)
                ->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $comprobantes =
                Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
                ->orderBy('created_at', 'desc')->paginate(10);
        }
        return view('control.comprobante.index', compact('comprobantes'));
    }
    public function show($id)
    {
        $comprobante =
            Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
            ->findOrFail($id)
            ->toArray();
        $detalles =
            Comprobante::with('movimiento', 'persona', 'detallecomprobante.producto', 'detallecomprobante.servicios')
                ->findOrFail($id)
                ->toArray()['detallecomprobante'];

        return view('control.comprobante.show', compact('comprobante', 'detalles'));

        // return response()->json(['comprobante' => $comprobante, 'detalles' => $detalles]);
    }
}
