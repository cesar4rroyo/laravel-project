@extends("theme.$theme.layout")

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar grupomenu #{{ $grupomenu->id }}</div>
                <div class="card-body">
                    <a href="{{ route('grupomenu') }}" title="Back"><button class="btn btn-warning btn-sm"><i
                                class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button></a>
                    <br />
                    <br />

                    @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif

                    <form method="POST" action="{{ route('update_grupomenu', $grupomenu->id) }}" accept-charset="UTF-8"
                        class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}

                        @include ('admin.grupomenu.form', ['formMode' => 'edit'])

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection