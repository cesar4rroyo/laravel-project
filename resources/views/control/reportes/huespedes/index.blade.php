@extends("theme.$theme.layout")

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header font-weight-bold">Generar Reportes de Huepesdes</div>
            <div class="card-body">
                <form id="formMovimientos">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm">
                            <label for="from">Inicio</label>
                            <input class="form-control" value="{{$today}}" type="date" name="from" id="from">
                        </div>
                        <div class="form-group col-sm">
                            <label for="to">Fin</label>
                            <input class="form-control" value="{{$today}}" type="date" name="to" id="to">
                        </div>
                        <div class="form-group col-sm">
                            <label for="presente">Según su Presencia</label>
                            <select class="form-control" name="presente" id="presente">
                                <option value="">Todos</option>
                                <option value="presentes">Presentes</option>
                                <option value="retirados">Retirados</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm">
                            <label for="habitacion">Habitación</label>
                            <select class="form-control" name="habitacion" id="habitacion">
                                <option value="">Todas</option>
                                @foreach ($habitaciones as $item)
                                <option value="{{$item['id']}}">
                                    {{$item['numero'] . " - " . $item['tipohabitacion']['nombre']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm">
                            <label for="sexo">Según Sexo</label>
                            <select class="form-control" name="sexo" id="sexo">
                                <option value="">Todos</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group col-sm">
                            <label for="edad">Rango de Edad</label>
                            <select class="form-control" name="edad" id="edad">
                                <option value="">Todos</option>
                                <option value="r0">Niños (0-12)</option>
                                <option value="r1">Adolescentes (12-18)</option>
                                <option value="r2">Jóvenes (18-30) </option>
                                <option value="r3">Adultos (30-63)</option>
                                <option value="r4">Adulto Mayor (63 - más)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <button id="btnGenerar" class="btn btn-outline-danger col-4 mr-1">
                            <i class="fas fa-search"></i>
                            Generar Reporte
                        </button>
                    </div>
                </form>
                <div class="row mb-2" id="btnsReport">
                    <div class="table-responsive mt-4">
                        <p class=" text-bold">Total: <span id="totalspam"></span></p>
                        <p class=" text-bold">Mostrando: <span id="totalItems"></span>  húespedes</p>
                        <table class="table text-center table-hover" id="huespedestable" class="display"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Edad</th>
                                    <th>Sexo</th>
                                    <th>Ciudad</th>
                                    <th>Fecha Entrada</th>
                                    <th>Fecha Salida</th>
                                    <th>Habitación Nro</th>
                                    <th>Tipo de Habitacion</th>
                                    <th>Total</th>
                                    <th>Early Check In</th>
                                    <th>Late Check Out</th>
                                    <th>Day Use</th>

                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Nombres</th>
                                    <th>Edad</th>
                                    <th>Sexo</th>
                                    <th>Ciudad</th>
                                    <th>Fecha Entrada</th>
                                    <th>Fecha Salida</th>
                                    <th>Habitación Nro</th>
                                    <th>Tipo de Habitacion</th>
                                    <th>Total</th>
                                    <th>Early Check In</th>
                                    <th>Late Check Out</th>
                                    <th>Day Use</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        $('#btnsReport').hide();
        $('#btnGenerar').on('click', function(e){
            e.preventDefault();
            var table = '';
            const data = new FormData(document.getElementById('formMovimientos'));
            fetch("{{route('reportes_huespedes_pdf')}}", {
                method:'POST',
                body:data
            }).then(res=>res.json())
            .then((data)=>{
                $('#btnsReport').show();
                table=$('#huespedestable').DataTable( {
                        "language": {
                            "decimal": "",
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Mostrar _MENU_ Entradas",
                            "loadingRecords": "Cargando...",
                            "processing": "Procesando...",
                            "search": "Buscar:",
                            "zeroRecords": "Sin resultados encontrados",
                            "paginate": {
                                "first": "Primero",
                                "last": "Ultimo",
                                "next": "Siguiente",
                                "previous": "Anterior"
                            },
                            "buttons":{
                                'excel':'Exportar a Excel',
                                'pdf':'Exportart a PDF',
                                'print':'Imprimir'
                            }
                        },
                        "processing": true,
                        'data': data.data,
                        "columns": [
                            { "data": "nombres" },
                            { "data": "edad" },
                            { "data": "sexo" },
                            { "data": "ciudad" },
                            { "data": "fechaingreso" },
                            { "data": "fechasalida" },
                            { "data": "numero" },
                            { "data": "tipohabitacion" },
                            { "data": "total" },
                            { "data": "early_checkin" },
                            { "data": "late_checkout" },
                            { "data": "day_use" },
                        ],
                        dom: 'lBfrtip',
                        buttons: [
                            {
                                extend: 'print',
                                autoPrint: false,
                                footer:false,
                                title: 'Reporte de Húespedes:' +  $('#from').val() + ' hasta ' + $('#to').val(),
                                messageTop: function(){
                                    return "<p class='text-bold'>Total: " +$('#totalspam').text() + "</p><p class='text-bold'>Húespedes Nro: " + $('#totalItems').text() + " </p>";
                                },
                                customize: function(win){
                                    var body = $(win.document.body).find( 'table tbody' )
                                    $(body).append($(body).find('tr:eq(0)').clone())
                                    var row = $(body).find('tr').last();
                                    $(row).find('td').text('');
                                    $(row).find('td:eq(8)').text('Total: ' + $('#totalspam').text());
                                }
                            },
                            {
                                extend: 'excel',
                                title: 'Reporte de Húespedes:' +  $('#from').val() + ' hasta ' + $('#to').val(),
                                footer:false, 
                                messageTop: function(){
                                    return "Total: " +$('#totalspam').text() + " - " + "Húespedes Nro: " + $('#totalItems').text();
                                },
                                
                            },
                            {
                                extend: 'pdf',                                
                                footer:false, 
                                orientation: 'landscape',
                                title: 'Reporte de Húespedes:' +  $('#from').val() + ' hasta ' + $('#to').val(),
                                messageTop: function(){
                                    return "Total: " +$('#totalspam').text() + "\n\n" + "Húespedes Nro: " + $('#totalItems').text();
                                },
                            },
                        ],
                        "lengthMenu": [[-1,10,25,50],["All",10,25,50]],
                        "bLengthChange": false,
                   

                        "bDestroy": true,
                        "footerCallback": function( row, data, start, end, display ){
                                var api = this.api();
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                    i : 0;
                                };
                                total = api
                                    .column( 8 )
                                    .data()
                                    .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                pageTotal = api
                                    .column( 8, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );
                                
                                // Update footer
                                $( api.column( 8 ).footer() ).html(
                                    'Total: S./'+Number(pageTotal).toFixed(2)
                                );
                                $('#totalspam').text('S/.' + Number(pageTotal).toFixed(2));


                                var totalItems = api.page.info().end;
                                $('#totalItems').text(totalItems);
                                                             
                                
                        }
                    });
            })
            .catch(e=>console.log(e));
        });       
    });
</script>