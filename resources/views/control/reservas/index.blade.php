@extends("theme.$theme.layout")

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header font-weight-bold">Próximas reservas</div>
            <div class="card-body">
                <a href="{{ route('habitaciones') }}" title="Regresar"><button
                        class="btn btn-outline-secondary btn-sm mb-2"><i class="fa fa-arrow-left"
                            aria-hidden="true"></i>
                        Regresar</button></a>
                <a href="{{ route('reserva') }}" title="Reservar"><button class="btn btn-outline-info btn-sm mb-2"><i
                            class="fas fa-calendar" aria-hidden="true"></i>
                        Nueva Reserva</button></a>
                <div class="pagination-wrapper"> {!! $reservas->render() !!} </div>
                {{-- {{dd($reservas)}} --}}
                <div class="table-responsive">
                    <table class="table text-center table-hover" id="tabla-data">
                        <thead>
                            <tr>
                                <th>Fecha Entrada</th>
                                <th>Fecha Salida</th>
                                <th>Cliente</th>
                                {{-- <th>Cliente DNI</th> --}}
                                <th>Habitacion Nro.</th>
                                <th>Obervacion</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->fecha)->formatLocalized('%d %B %Y %H:%M:00') }}</td>
                                <td>
                                    {{isset($item->fechasalida) ?\Carbon\Carbon::parse($item->fechasalida)->formatLocalized('%d %B %Y %H:%M:00') : ''}}
                                </td>
                                <td>
                                    @if (!is_null($item->persona->razonsocial) && trim($item->persona->razonsocia)!='')
                                    {{$item->persona->razonsocial}}
                                    @else
                                    {{ $item->persona->nombres}} {{" "}} {{ $item->persona->apellidos}}
                                    @endif
                                </td>
                                {{-- <td>
                                    {{ $item->persona->dni}}
                                </td> --}}
                                <td>
                                    {{ $item->habitacion->numero}} - {{ $item->habitacion->tipohabitacion->nombre}}
                                </td>
                                <td>
                                    {{ isset($item->observacion) ? $item->observacion : '-'}}
                                </td>
                                <td>
                                    <a href="{{ route('edit_movimiento' , ['id'=>$item->habitacion->id, 'id_reserva'=>$item->id] ) }}"
                                        title="Editar reserva"><button class="btn btn-outline-success btn-sm"><i
                                                class="fas fa-edit" aria-hidden="true"></i>
                                            Check-In
                                        </button></a>
                                    <form class="form-eliminar" method="POST"
                                        action="{{ route('destroy_reserva', $item->id) }}" accept-charset="UTF-8"
                                        style="display:inline">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            title="Eliminar reserva"><i class="fa fa-trash" aria-hidden="true"></i>
                                            Cancelar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection