<html>

<head>
    <meta charset='utf-8' />
    <link href="{{asset('assets/fullcalendar/lib/main.css')}}" rel='stylesheet' />
    <script src="{{asset('assets/fullcalendar/lib/main.js')}}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: {!! json_encode($initialDate) !!},
          locale:'es',
          timeZone: 'local',
          dateClick:function(info){
                $('#divSelectHabitacion').hide();
                $('#loadingRoons').hide();
                $('#txtFecha').val(info.dateStr);
                var fecha = info.dateStr;
                $('#modal-default').modal();
                console.log(info);
                var data = {
                    fecha: fecha,
                    '_token': $('input[name=_token]').val(),
                }
                // getHabitaciones(data, '#selectHabitacion');
                  
             
          },
          eventClick:function(info){
            $("#btnActualizar").prop('disabled', false);
            $("#btnEliminar").prop('disabled', false);
            month=(info.event.start.getMonth()+1);
            day=(info.event.start.getDate());
            year=(info.event.start.getFullYear());
            month=(month<10)?'0'+month:month;
            day=(day<10)?'0'+day:day;
            fecha=year+"-"+month+"-"+day; 

            if(info.event.extendedProps.situacion==='Ocupada'){
                $("#btnActualizar").prop('disabled', true);
                $("#btnEliminar").prop('disabled', true);
            }
                        
            
            var data = {
                    fecha: fecha,
                   '_token': $('input[name=_token]').val(),
                }
            getHabitaciones(data, '#selectHabitacionShow');
            $('#txtFechaShow').val(fecha);
            $('#txtId').val(info.event.id);
            $('#txtTitulo').val(info.event.title);
            $('#personaShow').val(info.event.extendedProps.persona_id);
            $('#selectHabitacionShow').val(info.event.extendedProps.habitacion_id);
            $('#observacionShow').val(info.event.extendedProps.observacion); 
            $('#situacion').val(info.event.extendedProps.situacion);  
            $('#modal-show').modal();            
          },
          events:"{{route('show_reserva')}}",
        });
        
        calendar.render();
        $('#consultarHabitaciones').on('click', function(){
                $('#divSelectHabitacion').show();
                var fechaSalida = $('#txtFechaSalida').val();
                var fechaEntrada = $('#txtFecha').val(); 
                var data ={
                    fechaSalida: fechaSalida,
                    fechaEntrada: fechaEntrada,
                    '_token': $('input[name=_token]').val(),
                }
                getHabitaciones(data, '#selectHabitacion');       
        });
        
         //$('#btnAgregar').click(function(evento){
        //     evento.preventDefault();
        //      event = getDatosForm('POST');
        //      postDatos('', event, '#modal-default' );
        //  });
        $('#btnActualizar').click(function(evento){    
                   
             event = getDatosActualizados('PATCH');
             updateDatos('/'+$("#txtId").val(), event , '#modal-show');
         });
         $('#btnEliminar').click(function(evento){    
                   
                   event = getDatosActualizados('DELETE');
                   eliminarReserva('/'+$("#txtId").val()+'/destroy', event , '#modal-show');
         });
         $('#btnCheckIn').click(function(evento){         
            event = getDatosActualizados('GET');
            enviarDatosToMovimiento(event);
         });
        function getHabitaciones(data, selectName){
            $.ajax({
                    url: 'habitaciones',
                    type: 'POST',
                    data: data,
                    success: function (respuesta) {
                        $('#loadingRoons').hide();
                        $('#divSelectHabitacion').show();
                        var $select =$(selectName);
                        $(selectName).empty();
                        if(Object.keys(respuesta).length==0){
                            $select.append('<option>' + 'No hay habitaciones disponibles' +'</option>');
                        }else{
                            const options = respuesta;
                            $.each(options, function(id, numero) {
                                $select.append('<option value=' + numero.id + '>' + 'Habitacion Nro.:'+numero.numero + '-'+ numero.tipohabitacion.nombre + '</option>');
                            });
                        }
                       
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });   
        }
        function getDatosForm(method){            
            newEvent={
                txtFecha:$('#txtFecha').val(),
                txtFechaSalida:$('#txtFechaSalida').val(),
                observacion:$('#observacion').val(),
                persona:$('#persona').val(),
                habitacion:$('#selectHabitacion').val(),
                '_token': $('input[name=_token]').val(),
                '_method':method
            }

            return newEvent;
        } 
        function getDatosActualizados(method){            
            newEvent={
                txtFecha:$('#txtFechaShow').val(),
                txtFechaSalida:$('#txtFechaSalida').val(),
                id:$('#txtId').val(),
                observacion:$('#observacionShow').val(),
                persona:$('#personaShow').val(),
                habitacion:$('#selectHabitacionShow').val(),
                '_token': $('input[name=_token]').val(),
                '_method':method
            }

            return newEvent;
        }         
        function updateDatos(action, event, modal){
            $.ajax(
                {
                    type:'PUT',
                    url:"reserva" + action ,
                    data: event,
                    success: function (respuesta) {
                        Hotel.notificaciones(respuesta.respuesta, 'Reserva', 'success');
                        $(modal).modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (e) {
                        console.log(e);
                    },
                }
            )
            
        }
        function eliminarReserva(action, event, modal){
            $.ajax(
                {
                    type:'DELETE',
                    url:"reserva" + action ,
                    data: event,
                    success: function (respuesta) {
                        Hotel.notificaciones(respuesta.respuesta, 'Reserva', 'success');
                        $(modal).modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (e) {
                        console.log(e);
                    },
                }
            )
            
        }
        function postDatos(action, event, modal){
            $.ajax(
                {
                    type:'POST',
                    url:'reserva',
                    data: event,
                    success: function (respuesta) {
                        Hotel.notificaciones(respuesta.respuesta, 'Reserva', 'success');
                        $(modal).modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function (e) {
                        console.log(e);
                    },
                }
            );          
            }  
        });

        function enviarDatosToMovimiento(data){
            $.ajax({
                type:'GET',
                url:'movimiento',
                data:data,
                success: function(r){
                                       
                },
                error: function(e){
                    console.log(e);
                }
            });
        }
        
    </script>
</head>
<style>
    .fc-toolbar-title {
        text-transform: capitalize;
        font-size: 1.5rem
    }

    .fc-today-button {
        text-transform: capitalize;
    }

    #calendar {
        width: 70%;
        margin: auto;
    }

    .fc-event-time {
        display: none;
    }
</style>

<body>
    <div class="container m-3">
        <div id='calendar'></div>
    </div>

</body>

</html>