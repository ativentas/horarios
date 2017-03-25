@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Modificar Empleado</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/empleados') }}">Listado</a></li>
                    <li class="active">Modificar</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">
    <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('empleados.update',$empleado->id)}}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="row col-md-12">

    <div class="col-md-6"> <!-- COLUMNA IZQUIERDA -->

            <div class="form-group{{$errors->has('alias') ? ' has-error' : ''}}">
                <label for="alias" class="control-label">Alias</label>
                <input type="text" autocomplete="off" name="alias" class="form-control" id="alias" value="{{Request::old('alias') ?: $empleado->alias}}">
                @if ($errors->has('alias'))
                    <span class="help-block">{{$errors->first('alias')}}</span>
                @endif
            </div>
            <div class="form-group{{$errors->has('nombre') ? ' has-error' : ''}}">
                <label for="nombre" class="control-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" id="nombre" value="{{Request::old('nombre') ?: $empleado->nombre_completo}}">
                @if ($errors->has('nombre'))
                    <span class="help-block">{{$errors->first('nombre')}}</span>
                @endif                  
            </div>
            <div class="form-group{{$errors->has('apellidos') ? ' has-error' : ''}}">
                <label for="apellidos" class="control-label">Apellidos</label>
                <input type="text" name="apellidos" class="form-control" id="apellidos" value="{{Request::old('apellidos') ?: $empleado->apellidos}}">
                @if ($errors->has('apellidos'))
                    <span class="help-block">{{$errors->first('apellidos')}}</span>
                @endif                  
            </div>


            <div class="form-group{{$errors->has('centro') ? ' has-error' : ''}}">
                <label for="centro" class="control-label"></label>
                <select class="form-control" id="centro" name="centro">
                    <option {{Request::old('centro')==''?' selected': $empleado->centro_id ==''?' selected':''}} value="">Elige un Centro</option>
                    @foreach ($centros as $centro)
                    <option {{Request::old('centro')==$centro->id ?' selected':$empleado->centro_id==$centro->id ?' selected':''}} value={{$centro->id}}>{{$centro->nombre}}</option>
                    @endforeach
                </select>
                @if ($errors->has('centro'))
                    <span class="help-block">{{$errors->first('centro')}}</span>
                @endif  
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Modificar</button>
            </div>      

    </div> <!-- FIN COLUMNA IZQUIERDA -->
    
    <div class="col-md-6"> <!-- COLUMNA DERECHA -->
        <div class="col-md-6 form-group{{$errors->has('alta') ? ' has-error' : ''}}">
            <label for="alta" class="control-label">F.Alta</label>
            <input type="date" autocomplete="off" name="alta" class="form-control" id="alta" value="{{Request::old('alta') ?: $empleado->fecha_alta}}">
            @if ($errors->has('alta'))
                <span class="help-block">{{$errors->first('alta')}}</span>
            @endif
        </div>
        <div class="col-md-6 form-group{{$errors->has('baja') ? ' has-error' : ''}}">
            <label for="baja" class="control-label">F.Baja</label>
            <input type="date" autocomplete="off" name="baja" class="form-control" id="baja" value="{{Request::old('baja') ?: $empleado->fecha_baja}}">
            @if ($errors->has('baja'))
                <span class="help-block">{{$errors->first('baja')}}</span>
            @endif
        </div>
    
    </div>


    </div>
    </form>

    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){

$.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

$( function() {
    $( '#alta' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });
    $( '#baja' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });

} );

});
</script>

@endsection