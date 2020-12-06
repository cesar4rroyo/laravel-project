@extends("theme.$theme.layout")
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Opcion menu {{ $opcionmenu->id }}</div>
                <div class="card-body">

                    <a href="{{ route('opcionmenu') }}" title="Back"><button class="btn btn-warning btn-sm"><i
                                class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button></a>
                    <a href="{{ route('edit_opcionmenu' , $opcionmenu->id ) }}" title="Edit opcionmenu"><button
                            class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            Editar</button></a>
                    <form method="POST" action="{{ route('opcionmenu' , $opcionmenu->id) }}" accept-charset="UTF-8"
                        style="display:inline">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete opcionmenu"
                            onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o"
                                aria-hidden="true"></i> Eliminar</button>
                    </form>
                    <br />
                    <br />
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $opcionmenu->id }}</td>
                                </tr>
                                <tr>
                                    <th> Nombre </th>
                                    <td> {{ $opcionmenu->nombre}} </td>
                                </tr>
                                <tr>
                                    <th> Link </th>
                                    <td> {{ $opcionmenu->link }} </td>
                                </tr>
                                <tr>
                                    <th> Icono </th>
                                    <td> {{ $opcionmenu->icono }} </td>
                                </tr>
                                <tr>
                                    <th> Orden </th>
                                    <td> {{ $opcionmenu->orden }} </td>
                                </tr>
                                <tr>
                                    <th> Grupo Menu </th>
                                    <td> {{ $opcionmenu->grupomenu->nombre }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection