@extends("theme.$theme.layout")

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Nueva persona</div>
                <div class="card-body">
                    <a href="{{ route('persona') }}" title="Back"><button class="btn btn-warning btn-sm"><i
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

                    <form method="POST" action="{{ route('create_persona') }}" accept-charset="UTF-8"
                        class="form-horizontal" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        @include ('admin.persona.form', ['formMode' => 'create'])

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection