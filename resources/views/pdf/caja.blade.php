<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reportes Caja</title>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset("assets/$theme/dist/css/adminlte.min.css")}}">
</head>
<style>
    .table {
        width: 100%;
    }
</style>

<body>
    <div>
        <p class="font-weight-bold">Registro de Movimientos de caja</p>
        <p class=" float-right">Fecha: {{\Carbon\Carbon::now()}}</p>
        <table class="table text-center mt-5">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Persona</th>
                    <th>Total</th>
                    <th>Concepto</th>
                    <th>Comentario</th>
                    <th>Movimiento</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                @if (($item['tipo'])!='Configuración Inicial Caja')
                <tr>
                    <td>{{ $item['fecha'] }}</td>
                    @if ( ($item['tipo'])=='Ingreso' )
                    <td>
                        <span class="badge badge-success">
                            {{ $item['tipo'] }}
                        </span>
                    </td>
                    @else
                    <td>
                        <span class="badge badge-danger">
                            {{ $item['tipo'] }}
                        </span>
                    </td>
                    @endif
                    <td>
                        {{$item['persona']}}
                    </td>
                    @if ( ($item['tipo'])=='Ingreso' )
                    <td class="subtotal sumaIngreso">
                        <span class="badge badge-success">
                            {{ $item['total'] }}
                        </span>
                    </td>
                    @else
                    <td>
                        <span class="badge badge-danger sumaEgreso">
                            {{ $item['total'] }}
                        </span>
                    </td>
                    @endif
                    <td>
                        {{ $item['concepto'] }}
                    </td>
                    <td>
                        {{ $item['comentario'] }}
                    </td>
                    <td>
                        {{ $item['movimiento'] }}
                    </td>
                    <td>
                        {{ $item['usuario'] }}

                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>