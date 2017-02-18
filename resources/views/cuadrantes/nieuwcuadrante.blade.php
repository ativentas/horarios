@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Seleccionar Semana</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Nuevo Horario</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">
        <div class="col-md-4 row">
        <form role="form" action="{{url('/nieuwcuadrante')}}" method="post">
            {{ csrf_field() }}
            <div class="form-group{{$errors->has('fecha') ? ' has-error' : ''}}">
            <label for="fecha">Semana-Año</label>
            <input type="text" name="fecha" class="form-control" id="fecha" value={{date('W-Y')}} readonly>
            @if ($errors->has('fecha'))
            <span class="help-block">{{$errors->first('fecha')}}</span>
            @endif
            </div>
   

            <button type="submit" class="btn btn-default">Crear</button>
        </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
$.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

$( function() {
    $( '#fecha' ).datepicker({
        dateFormat: "yy-mm-dd",
        showWeek: true,
    });
// var fecha = new Date($('#fecha').val());
// $('#fecha').change();

} );
$('#fecha').on( 'change', function() { 
    // TO DO: coger la fecha y convertirlo a semana
    // alert ($('#fecha').val());
    var fecha = new Date($('#fecha').val());
    // var year = '2017';
    var semana = $.datepicker.iso8601Week( fecha );    
    var output = ("00" + semana).slice(-2);
    $('#fecha').val(output+'-2017');
});

});


</script>

@endsection

