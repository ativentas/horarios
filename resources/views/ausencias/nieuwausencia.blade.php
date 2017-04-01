
@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@endsection
@section('content')

<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Nueva Ausencia/Vacaciones</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('ausencias/calendario') }}">Calendario</a></li>
                    <li><a href="{{ url('/ausencias') }}">Listado</a></li>
                    <li class="active">Nuevo</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">
        <form action="{{ url('ausencias') }}" method="POST" autocomplete="off">
            {{ csrf_field() }}
            <div class="col-md-4 row">

            @if(Auth::user()->isAdmin())
            <div class="form-group @if($errors->has('centro')) has-error has-feedback @endif">
                <select class="form-control" id="centro" name="centro">
                    <option value="">Elige Dpto</option>
                    @foreach ($centros as $centro)
                    <option value="{{$centro->id}}" @if(old('centro')==$centro->id) selected="selected" @endif>{{$centro->nombre}}</option>
                    @endforeach
                </select>
                @if ($errors->has('centro'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('centro') }}
                    </p>
                @endif 
            </div>
            @endif
            @if(!Auth::user()->isAdmin())
            <input type="hidden" name="centro" value="{{Auth::user()->centro_id}}">
            @endif
            <div class="form-group @if($errors->has('empleado_id')) has-error has-feedback @endif">
                <select class="form-control" id="empleado_id" name="empleado_id">
                    <option value="">Elige un Empleado</option>
                    @foreach($empleados as $empleado)
                    <option class="todos_empleados" value="{{$empleado->id}}" @if(old('empleado_id')==$empleado->id) selected="selected" @endif>{{$empleado->alias}}</option>
                    @endforeach
                </select>
                @if ($errors->has('empleado_id'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('empleado_id') }}
                    </p>
                @endif                
            </div>


            <div class="form-group @if($errors->has('tipo')) has-error has-feedback @endif">
                <select class="form-control" id="tipo" name="tipo">
                    <option value="">Elige...</option>
                    @foreach($tipos as $sigla => $nombre)
                    <option {{Request::old('tipo')==$sigla ? ' selected' : ''}} value={{$sigla}}>{{$nombre}}</option>
                    @endforeach
                </select>

                @if ($errors->has('tipo'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('tipo') }}
                    </p>
                @endif
            </div>
            <div class="form-group @if($errors->has('time')) has-error @endif">
                <label for="time">Fechas</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="time" placeholder="Select your time" value="{{ old('time') }}">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                @if ($errors->has('time'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('time') }}
                    </p>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Crear</button>
            </div>
            
            <div class="col-md-6">
            <div class="panel-body">
              <!-- <form method="post" action="/comment/add"> -->
                <div class="form-group">
                  <textarea placeholder="Escribe una nota aquí..." name = "body" class="form-control"></textarea>
                </div>
                <!-- <input type="submit" name='post_comment' class="btn btn-success" value = "Enviar"/> -->
              <!-- </form> -->
            </div>
            </div>


        </form> 

    </div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js">
</script>
<script>
$(document).ready(function(){

var listado = {!!$listado!!};// 
// console.log(JSON.stringify(listado));
$('#centro').change(function() {
    var centro_elegido = $('#centro option:selected').val();
    var empleados = listado[centro_elegido];
    var options = $("#empleado_id");
    if (centro_elegido!=""){
        $('.empleados_populated').remove();
        //TO DO: PARECE SER QUE LA OPCION DE HIDE() NO FUNCIONA BIEN EN SAFARI. habría que hacerlo con remove y despues append.
        $('.todos_empleados').hide();
        $.each(empleados, function(key, value) {
            // console.log(value.alias);    
        options.append($('<option class="empleados_populated"/>').val(value.empleado_id).text(value.alias));
        });
    }else{
        $('.empleados_populated').remove();
        $('.todos_empleados').show();

    }


})
$('#empleado_id').change(function(){
    var empleado_elegido = $('#empleado_id option:selected').val();
})


$.each(listado, function(a, b){
    console.log(a, b);
});




$('input[name="time"]').daterangepicker({
    "showWeekNumbers": true,
    "autoApply": true,
    "locale": {
        "format": "DD/MM/YYYY",
        "weekLabel": "S",
        "separator": " - ",
        "firstDay": 1,
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
    }

});
});
</script>

@endsection

