<div class="row">
    <div class="form-group col-sm">
        <label class="control-label" for="fecha">Fecha</label>
        <input type="datetime-local" id="fecha" class="form-control" name="fecha"
            value="{{isset($caja->fecha) ? Carbon\Carbon::parse($caja->fecha)->format('Y-m-d\TH:i'):Carbon\Carbon::now()->format('Y-m-d\TH:i')}}">
    </div>
    <div class="form-group col-sm">
        <label class="control-label" for="numero">Número</label>
        <input type="number" readonly class="form-control" name="numero" id="numero"
            value="{{ isset($caja->numero) ? $caja->numero : $numero}}">
    </div>
</div>
<div class="row">
    <div class="form-group col-sm {{ $errors->has('tipo') ? 'has-error' : ''}}">
        <label for="tipo" class="control-label">{{ 'Tipo' }}</label>
        <select required class="form-control" name="tipo" id="tipo">
            <option value="{{ isset($caja->tipo) ? $caja->tipo : ''}}">
                {{ isset($caja->tipo) ? $caja->tipo : 'Seleccione una opcion'}}
            </option>
            <option value="Ingreso">Ingreso</option>
            <option value="Egreso">Egreso</option>
        </select>
        {!! $errors->first('concepto', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group col-sm {{ $errors->has('concepto') ? 'has-error' : ''}}">
        <label for="concepto" class="control-label">{{ 'Concepto' }}</label>
        <select required class="form-control" name="concepto" id="concepto">
            <option value="{{ isset($caja->concepto) ? $caja->concepto->id : ''}}">
                {{ isset($caja->concepto->nombre) ? $caja->concepto->nombre : 'Seleccione una opcion'}}
            </option>
            @foreach ($conceptos as $item)
            @if (($item->tipo)=="Ingreso")
            <option data-attribute="Ingreso" value="{{$item->id}}">
                {{$item->nombre}}
            </option>
            @else
            <option data-attribute="Egreso" value="{{$item->id}}">
                {{$item->nombre}}
            </option>
            @endif
            @endforeach
        </select>
        {!! $errors->first('concepto', '<p class="text-danger">:message</p>') !!}
    </div>

</div>
<div class="row">
    <div class="form-group col-sm {{ $errors->has('persona') ? 'has-error' : ''}}">
        <label for="persona" class="control-label">{{ 'Personas' }}</label>
        <select required class="form-control clientes-select2" name="persona" id="persona">
            <option value="{{ isset($caja->persona) ? $caja->persona->id : ''}}">
                {{ isset($caja->persona->nombres) ? (($caja->persona->nombres!='-')?($caja->persona->nombres . ' ' . $caja->persona->apellidos):($caja->persona->razonsocial))  : 'Seleccione una opcion'}}
            </option>
            @foreach ($personas as $item)
            <option value="{{$item['id']}}">
                {{$item['nombres']}}
            </option>
            @endforeach
        </select>
        {!! $errors->first('persona', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="col-sm form-group">
        <label for="persona" class="control-label">{{ 'Total' }}</label>
        <input type="number" required class="form-control" step="0.01" name="total" id="total"
            value="{{ isset($caja->total) ? $caja->total : ''}}">
    </div>
</div>
<div class="form-group {{ $errors->has('comentario') ? 'has-error' : ''}}">
    <label for="comentario" class="control-label">{{ 'Comentario' }}</label>
    <input class="form-control" name="comentario" type="text" id="comentario"
        value="{{ isset($caja->comentario) ? $caja->comentario : ''}}">
    {!! $errors->first('comentario', '<p class="text-danger">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-outline-success" type="submit" value="{{ $formMode === 'edit' ? 'Actualizar' : 'Agregar' }}">
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        $("#tipo").change( function() {
            filterSelectOptions($("#concepto"), "data-attribute", $(this).val());
        });
        function filterSelectOptions(selectElement, attributeName, attributeValue) {
            if (selectElement.data("currentFilter") != attributeValue) {
                selectElement.data("currentFilter", attributeValue);
                var originalHTML = selectElement.data("originalHTML");
                if (originalHTML)
                    selectElement.html(originalHTML)
                else {
                    var clone = selectElement.clone();
                    clone.children("option[selected]").removeAttr("selected");
                    selectElement.data("originalHTML", clone.html());
                }
                if (attributeValue) {
                    selectElement.children("option:not([" + attributeName + "='" + attributeValue + "'],:not([" + attributeName + "]))").remove();
                }
            }
        }
})
</script>