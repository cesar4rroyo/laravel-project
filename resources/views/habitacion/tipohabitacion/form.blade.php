<div class="row">
    <div class="form-group col-sm {{ $errors->has('nombre') ? 'has-error' : ''}}">
        <label for="nombre" class="control-label">{{ 'Nombre' }}</label>
        <input class="form-control" name="nombre" type="text" required id="nombre"
            value="{{ isset($tipohabitacion->nombre) ? $tipohabitacion->nombre : ''}}">
        {!! $errors->first('nombre', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group col-sm {{ $errors->has('precio') ? 'has-error' : ''}}">
        <label for="precio" class="control-label">{{ 'Precio' }}</label>
        <input class="form-control" name="precio" type="number" required step="0.01" id="precio"
            value="{{ isset($tipohabitacion->precio) ? $tipohabitacion->precio : ''}}">
        {!! $errors->first('precio', '<p class="text-danger">:message</p>') !!}
    </div>
</div>


<div class="form-group">
    <input class="btn btn-outline-success" type="submit" value="{{ $formMode === 'edit' ? 'Actualizar' : 'Agregar' }}">
</div>