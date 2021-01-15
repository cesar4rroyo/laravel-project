@extends("theme.$theme.layout")

@section('content')
{{-- {{dd(session()->all())}} --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header font-weight-bold">Agregar Movimiento</div>
            <div class="card-body">
                <a href="{{ route('habitaciones') }}" title="Regresar"><button
                        class="btn btn-outline-secondary btn-sm mb-2"><i class="fa fa-arrow-left"
                            aria-hidden="true"></i>
                        Regresar</button></a>
                <div class="container">
                    <div class="row">
                        <div class="col-sm">
                            <p class="font-weight-bold ">Productos</p>
                            <div class="container">
                                <form action="{{route('consultarProducto', $id)}}">
                                    <div class="input-group">
                                        <input type="search" name="search" placeholder="Buscar Producto"
                                            class="form-control">
                                        <span class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                            <div class="container" style="height: 300px; overflow:auto">
                                <table class="table text-center table-hover table-fixed" id="tabla-data">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($productos as $item)
                                        <tr>
                                            <td>{{ $item['nombre'] }}</td>
                                            <td>{{'S/. '}}{{ $item['precioventa'] }}</td>
                                            <td>
                                                <button data-id="{{$item['id']}}" type="button"
                                                    class="addToCart btn btn-outline-success">
                                                    <i class="fas fa-plus-circle"></i>
                                                    Agregar
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm">
                            <p class="font-weight-bold ">Productos Seleccionados</p>
                            <div class="container" style="height: 300px; overflow:auto">
                                <table class="table text-center table-hover" id="tabla-data">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php $total = 0 ?>
                                        @if (session('cart'))
                                        @foreach (session('cart') as $id=>$details)
                                        <?php $total += $details['precio'] * $details['cantidad'] ?>
                                        <tr>
                                            <td>
                                                {{ $details['nombre']}}
                                            </td>
                                            <td data-th="Quantity" style="width: 20%">
                                                <input type="number"
                                                    class="txtCantidad form-control text-center quantity"
                                                    data-id="{{$id}}" value="{{$details['cantidad']}}">
                                            </td>
                                            <td>
                                                {{ $details['precio'] * $details['cantidad']}}
                                            </td>
                                            <td>

                                                <button data-id="{{$id}}" type="button"
                                                    class="addToCart btn btn-outline-success">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                                <button data-id="{{$id}}" type="button"
                                                    class="removeFromCart btn btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <button data-id="{{$id}}" type="button"
                                                    class="btn btn-outline-secondary updateCart d-none">
                                                    <i class="fas fa-save"></i>
                                                </button>

                                                {{-- @csrf --}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <p id="errMessage" class="text-center text-danger">Inserte al menos un producto</p>
                            <div class="container">
                                <p class="font-weight-bold ">Total: </p>
                                <input class="form-control" name="total" id="total" readonly type="number"
                                    value="{{$total}}">
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{route('store_detallemovimiento')}}">
                        @csrf
                        <input hidden name="movimiento" value="{{$movimientos['id']}}" type="text">
                        <div class="form-group">
                            <label for="comentario">{{'Comentario'}}</label>
                            <textarea class="form-control" name="comentario" id="comentario" cols="10"
                                rows="5"></textarea>
                        </div>
                        <div class="row">
                            <label for="movimientos">Personas</label>
                            <div id="personas" class="table-responsive">
                                <table class="table text-center table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>DNI / RUC</th>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pasajeros as $item)
                                        <tr>
                                            <td>
                                                {{isset($item['persona']['nombres'])? $item['persona']['nombres'] . " " . $item['persona']['apellidos'] : $item['persona']['nombres']}}
                                            </td>
                                            <td>
                                                {{isset($item['persona']['ruc'])?$item['persona']['ruc']:$item['persona']['dni']}}
                                            </td>
                                            <td>
                                                {{$item['persona']['telefono']}}
                                            </td>
                                            <td>
                                                {{$item['persona']['direccion']}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="btnContainer" class="container text-center">
                            <button id="btnAddToRoom" type="submit" class="btn btn-outline-success col-6">
                                Agregar a habitacion
                            </button>
                            <button id="btnAddToCaja" type="button" data-toggle="modal" data-target="#modalCaja"
                                {{-- href="{{route('add_detail_producto', $movimientos['id'])}}" --}}
                                class="btn btn-outline-info col-6 mt-1 btnSubmit">
                                Pago Caja
                            </button>
                        </div>
                    </form>
                    <div class="modal fade" id="modalCaja" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                        <form method="POST" id="formVentaProductos"
                            action="{{route('add_detail_producto', $movimientos['id'])}}">
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Datos de Documento</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group col-sm">
                                                <label class="control-label" for="numero">Número</label>
                                                <input type="text" readonly class="form-control"
                                                    name="numero_comprobante" id="numero" value="{{$numero}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm form-group">
                                                <label for="tipodocumento"
                                                    class="control-label">{{ 'Tipo Documento' }}</label>
                                                <select class="form-control" required name="tipodocumento"
                                                    id="tipodocumento">
                                                    <option selected value="boleta">Boleta</option>
                                                    <option value="factura">Factura</option>
                                                    <option value="ticket">Ticket</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm">
                                                <label class="control-label" for="fecha">Fecha</label>
                                                <input type="datetime-local" id="fecha" class="form-control"
                                                    name="fecha" value="{{Carbon\Carbon::now()->format('Y-m-d\TH:i')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div
                                                class="form-group col-sm {{ $errors->has('persona') ? 'has-error' : ''}}">
                                                <label for="persona" class="control-label">{{ 'Persona' }}</label>
                                                {{-- <input type="text" id="persona"> --}}
                                                <select class="form-control" required name="persona"
                                                    id="persona_select">
                                                    <option value="{{$pasajeros[0]['persona']['id']}}">
                                                        {{$pasajeros[0]['persona']['nombres'] . ' ' . $pasajeros[0]['persona']['apellidos']}}
                                                    </option>
                                                    @foreach ($pasajeros as $item)
                                                    @if ($item['persona']['id']!=$pasajeros[0]['persona']['id'])
                                                    <option value="{{$item['persona']['id']}}">
                                                        {{$item['persona']['nombres']}}
                                                        {{" "}}{{$item['persona']['apellidos']}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('persona', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="comentario" class="control-label">{{ 'Comentario' }}</label>
                                            </div>
                                            <textarea class="form-control" name="comentario" id="comentario" cols="3"
                                                rows="3"></textarea>
                                        </div>
                                    </div>
                                    <p id="loading" class="text-center text-info font-weight-bold mt-4">Espere...</p>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                                            Operación</button>
                                        <button id="btnPagoCaja" type="button" class="btn btn-primary">Registrar en
                                            Caja</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 
        $('#errMessage').hide();
        $('#loading').hide();
        const btnPagoCaja = document.getElementById('btnPagoCaja').onclick=function(e){
        const data = new FormData(document.getElementById('formVentaProductos'));  
        e.preventDefault();
        const idventa = '';
        if($('#total').val().trim()!='0'){
            $('#loading').show();            
            fetch("{{route('add_detail_producto',  $movimientos['id'])}}",{
            method:'POST',
            body:data
            }).then(res=>res.json())
              .then(function(data){
                if(data.respuesta=='ok'){
                    var idComprobante =data.id_comprobante
                    if(data.tipoDoc != "ticket"){
                        if(data.tipoDoc=="boleta"){
                        var funcion ='enviarBoleta'
                        }else if(data.tipoDoc=="factura"){
                            var funcion ='enviarFactura'
                        }                    
                        $.ajax({
                            type:'GET',
                            url:'http://localhost:81/clifacturacion/controlador/contComprobante.php?funcion='+funcion,
                            data:"idventa="+idComprobante+"&_token="+ $('input[name=_token]').val(),
                            success: function(r){
                                window.open('http://localhost:81/hotel/public/admin/comprobantes/pdf'+'/'+idComprobante, "_blank");         
                                window.location.href = "{{route('caja')}}";
                                console.log(r);
                            },
                            error: function(e){
                                console.log(e.message);
                            }
                        });
                    }else{
                        window.open('http://localhost:81/hotel/public/admin/comprobantes/pdf'+'/'+idComprobante, "_blank");         
                        window.location.href = "{{route('caja')}}";
                    }   
                }else{
                    Hotel.notificaciones(data.mensaje, 'Hotel', 'error');
                    $('#loading').hide();
                }                           
            })
            .catch(function (e){
                console.log(e);
            });
        }else{
            console.log('ERROR');
            $('#errMessage').show();
        }   
        
    }

        $('.txtCantidad').on('change', function(e){
        
        e.preventDefault();
        var ele = $(this);
            $.ajax({
                url: "{{ url('admin/updateCart') }}",
                method: "PATCH",
                data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), cantidad: ele.parents("tr").find(".quantity").val()},
                success: function (respuesta) {
                    Hotel.notificaciones(respuesta.respuesta, 'Hotel', 'success');
                    location.reload();
                }
        
            });
        });
        $(document.body).on('change',"#tipodocumento",function (e) {
           var optVal= $("#tipodocumento option:selected").val();
           $.ajax({
               url:"{{url('admin/ventas')}}" + "/" + optVal,
               success:function(r){
                   $('#numero').val(r);
               },
               error:function(e){
                   console.log(e);
               }
           })
    });
    $('.addToCart').on('click', function(){

            var id = $(this).data('id');
            if(id){
                $.ajax({
                    url:"{{url('admin/addToCart')}}"+'/'+id,
                    type:'GET',
                    success:function(respuesta){
                        Hotel.notificaciones(respuesta.respuesta, 'Hotel', 'success');
                        location.reload();                         
                    },
                    error: function(e){
                        console.log(e);
                    }
                })
            }

        })
    $(".removeFromCart").on('click',function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Desea Eliminar")) {
            $.ajax({
                url: "{{url('admin/removeFromCart')}}",
                method: "DELETE",
                data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                success: function (respuesta) {
                    Hotel.notificaciones(respuesta.respuesta, 'Hotel', 'success');
                    location.reload();
                }
            });
        }
    });
    $(".updateCart").click(function (e) {
           e.preventDefault();
           var ele = $(this);
            $.ajax({
               url: "{{ url('admin/updateCart') }}",
               method: "PATCH",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), cantidad: ele.parents("tr").find(".quantity").val()},
               success: function (respuesta) {
                    Hotel.notificaciones(respuesta.respuesta, 'Hotel', 'success');
                    location.reload();
            }
        });
    });

    });

</script>