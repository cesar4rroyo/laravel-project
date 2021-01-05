@extends("theme.$theme.layout")

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header font-weight-bold">Check In</div>
            <div class="card-body">
                <a href="{{ route('habitaciones') }}" title="Regresar"><button
                        class="btn btn-outline-secondary btn-sm mb-2"><i class="fa fa-arrow-left"
                            aria-hidden="true"></i>
                        Regresar</button></a>
                <div class="modal fade" id="modal-pasajero" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Cliente</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('store_persona_checkin') }}" accept-charset="UTF-8"
                                    class="form-horizontal" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    @include ('control.checkin.form', ['formMode' => 'create'])
                                    <input type="text" name="habitacion" hidden value="{{$habitacion['id']}}">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <form method="POST" action="{{route('store_movimiento', isset($reserva) ? $reserva : 'no')}}">
                        @csrf
                        <div class="row">
                            <div class="col-sm form-group">
                                <label for="habitacion">{{'Habitacion'}}</label>
                                <input class="form-control" type="text" name="habitacion" hidden
                                    value="{{$habitacion['id']}}">
                                <input class="form-control" type="text" readonly value="{{$habitacion['numero']}}">
                            </div>
                            <div class="col-sm form-group">
                                <label class="control-label" for="fechaingreso">{{'Fecha Ingreso'}}</label>
                                <input class="form-control" id="fechaingreso" name="fechaingreso" type="datetime-local"
                                    value="{{$initialDate}}">
                            </div>
                            <div class="col-sm form-group">
                                <label class="control-label" for="fechasalida">{{'Fecha Salida'}}</label>
                                <input class="form-control" id="fechasalida" name="fechasalida" required
                                    type="datetime-local">
                            </div>
                            @isset($reserva)
                            <div class="col-sm form-group">
                                <label class="control-label" for="reserva">{{'Reserva Nro.'}}</label>
                                <input readonly class="form-control" value="{{$reserva}}" type="number" name="reserva"
                                    id="reserva">
                            </div>
                            @endisset
                        </div>
                        <div class="row">
                            <div class="col-sm form-group">
                                <label class="control-label" for="preciohabitacion">{{'Precio Habitacion'}}</label>
                                <input class="form-control" readonly value="{{$habitacion['tipohabitacion']['precio']}}"
                                    type="number" name="preciohabitacion" id="preciohabitacion">
                            </div>
                            <div class="col-sm form-group">
                                <label class="control-label" for="capacidad">{{'Capacidad Habitacion'}}</label>
                                <input class="form-control" readonly
                                    value="{{$habitacion['tipohabitacion']['capacidad']}}" type="number"
                                    name="capacidad" id="capacidad">
                            </div>
                            {{-- <div class="col-sm form-group">
                                <label class="control-label" for="descuento">{{'Descuento'}}</label>
                            <input class="form-control" type="number" name="descuento" id="descuento">
                        </div> --}}
                        {{-- <div class="col-sm form-group">
                            <label class="control-label" for="total">{{'Total'}}</label>
                        <input class="form-control" readonly type="number" name="total" id="total">
                </div> --}}
            </div>
            <div class="form-group">
                <label class="control-label" for="persona">{{'Pasajero Principal'}}</label>
                <a type="button" data-toggle="modal" data-target="#modal-pasajero">
                    <span class="badge badge-success">
                        <i class="fas fa-plus-circle"></i>
                        {{'Agregar Nuevo Cliente'}}</span>
                </a>
                <select class="form-control clientes-select2" name="persona_principal" id="persona_principal" required>
                    <option value="">Seleccione Uno</option>
                    @foreach ($personas as $persona)
                    <option value="{{$persona['id']}}">
                        {{$persona['nombres']}}{{" "}}{{$persona['apellidos']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="control-label" for="persona">{{'Acompañantes'}}</label>
                <a type="button" data-toggle="modal" data-target="#modal-pasajero">
                    <span class="badge badge-success">
                        <i class="fas fa-plus-circle"></i>
                        {{'Agregar Nuevo Cliente'}}</span>
                </a>
                <select class="form-control clientes-select2" multiple='multiple' name="persona[]" id="persona">
                    <option value="">Seleccione Uno</option>
                    @foreach ($personas as $persona)
                    <option value="{{$persona['id']}}">
                        {{$persona['nombres']}}{{" "}}{{$persona['apellidos']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <label for="comentario">{{'Comentario'}}</label>
                <textarea class="form-control" name="comentario" id="comentario" cols="5" rows="5"></textarea>
            </div>
            <p class="font-weight-bold mt-4">Datos de Tarjeta</p>
            <hr>
            <div class="form-group">
                <label class="control-label" for="tipo">{{'Tipo de Tarjeta'}}</label>
                <select class="form-control" name="tipo" id="tipo">
                    <option value="">Seleccione Uno</option>
                    <option value="amex">{{'American Express'}}</option>
                    <option value="visa">{{'Visa'}}</option>
                    <option value="mastercard">{{'Master Card'}}</option>
                    <option value="diners">{{'Diners'}}</option>
                </select>
            </div>
            <div class="row">
                <div class="col-sm form-group">
                    <label for="numero">{{'Número de Tarjeta'}}</label>
                    <input autocomplete="false" class="form-control" id="numero" type="text" name="numero">
                </div>
                <div class="col-sm form-group">
                    <label for="fechavencimiento">{{'Fecha de Vencimiento(ej.: 01/21)'}}</label>
                    <input autocomplete="false" class="form-control" id="fechavencimiento" type="text"
                        name="fechavencimiento">
                </div>
            </div>
            <div class="form-group">
                <label for="titular">{{'Nombre del Titular'}}</label>
                <input autocomplete="false" class="form-control" id="titular" type="text" name="titular">
            </div>
            <div class="container text-center">
                <button type="submit" class="btn btn-outline-success col-sm-6">
                    Check-In
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
    
    $("#descuento").on('change',function(){
        var dscto = $(this).val();
        console.log(dscto);
        $('#total').val(parseFloat({{isset($total) ? $total : 0}}));     
        var preciohabitacion = $('#preciohabitacion').val();
        var habitaciontotal = preciohabitacion - (dscto*preciohabitacion/100);
        var total = $('#total').val();
        total = parseFloat(habitaciontotal) + parseFloat(total);
        $('#total').val(parseFloat(total));
       
    });
 })


</script>