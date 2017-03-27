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

    <div class="row col-md-12">
    
    <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('empleados.update',$empleado->id)}}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="col-md-5"> <!-- COLUMNA IZQUIERDA -->

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

            <div class="form-group">
                <button type="submit" class="btn btn-default">Modificar</button>
            </div>      
    </div> <!-- FIN COLUMNA IZQUIERDA -->
    </form>    

    <div class="col-md-7"> <!-- COLUMNA DERECHA -->
    @if(count($contrato_actual))
    <div class="panel panel-default">
    <div class="panel-heading"><h4>Contrato Actual</h4></div>
    <div class="panel-body">
    <div class="form-inline">
        <div class="col-md-3 form-group" style="padding:0px;">
            <label for="alta" class="control-label">F.Alta</label>
            <input type="" autocomplete="off" name="actual_alta" class="form-control" id="actual_alta" value="{{$contrato_actual->fecha_alta}}" style="max-width: 100%" readonly>
        </div>
        <div class="col-md-3 form-group" style="padding:0px;">
            <label for="baja" class="control-label">F.Baja</label>
            <input type="" autocomplete="off" name="actual_baja" class="form-control" id="actual_baja" value="{{$contrato_actual->fecha_baja}}" style="max-width: 100%" readonly>
        </div>
        <div class="col-md-6 form-group" style="padding:0px;">
            <label for="centro" class="control-label">Dpto.</label>
            <div class="input-group">
            <input type="text" autocomplete="off" name="" class="form-control" id="centro_nombre" value="{{$contrato_actual->centro->nombre}}" readonly>
            <span class="input-group-addon"><button class="btn btn-info btn-xs" id="button_modificar_vigente" type="button" style=""><span class="glyphicon glyphicon-edit"></span></button></span>         
            </div>
        </div>
    </div>
    </div>
    </div>
    @else
    <div class="panel panel-default">
    <div class="panel-heading"><h4>Alta Nuevo Contrato</h4></div>
    <div class="panel-body">
        <div class="form-group">
            <button type="submit" class="btn btn-default" id="button_new_contrato">Nuevo Contrato</button>
        </div>  
    </div>
    </div>
    @endif
    @if(count($contratos_anteriores))
    <div class="panel panel-default">
    <div class="panel-heading"><h4>Histórico Contratos</h4></div>
    <div class="panel-body">
    @foreach($contratos_anteriores as $contrato)
    <div class="form-inline">
        <div class="col-md-3 form-group" style="padding:0px;">
            <label for="actual_alta" class="control-label">F.Alta</label>
            <input type="" autocomplete="off" name="actual_alta" class="form-control" id="actual_alta" value="{{$contrato->fecha_alta}}" style="max-width: 100%;" readonly>
        </div>
        <div class="col-md-3 form-group" style="padding:0px;">
            <label for="actual_baja" class="control-label">F.Baja</label>
            <input type="" autocomplete="off" name="actual_baja" class="form-control" id="actual_baja" value="{{$contrato->fecha_baja}}" style="max-width: 100%;" readonly>
        </div>
        <div class="col-md-6 form-group" style="padding:0px;">
            <label for="centro" class="control-label">Dpto.</label>
            <div class="input-group">
            <input type="text" autocomplete="off" name="" class="form-control" id="centro_nombre" value="{{$contrato->centro_nombre}}" readonly>
            <span class="input-group-addon"><button class="btn btn-info btn-xs btn_modify_{{$contrato->id}}" id="button_modify_{{$contrato->id}}" type="button" style=""><span class="glyphicon glyphicon-edit"></span></button></span>         
            </div>
        </div>
    </div>
    @endforeach
    </div>
    </div>
    @endif
    
    </div> <!-- FIN COLUMNA DERECHA -->


    @if(count($contrato_actual))
    <div id="dialogContrato" title="">
        <form autofocus autocomplete="off" class="form-vertical" id="form_contrato_actual" role="form" method="post" action="{{route('contratos.update',$contrato_actual->id)}}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
         <div class="form-inline">
            <div class="col-md-6 form-group">
            <label for="alta" class="control-label">F.Alta</label>
            <input type="" name="alta" id="dialog_alta" class="form-control" value="{{$contrato_actual->fecha_alta}}" style="max-width: 100%">
            </div>
            <div class="col-md-6 form-group">
            <label for="baja" class="control-label">F.Baja</label>
            <input type="" name="baja" id="dialog_baja" class="form-control" value="{{$contrato_actual->fecha_baja}}" style="max-width: 100%">
            </div>
         </div>
         <div class="col-md-10 form-group" style="margin-top: 1em">
            <select class="form-control" id="dialog_centro" name="centro">
                @foreach ($centros as $centro)
                <option {{$contrato_actual->centro_id==$centro->id ?' selected':''}} value={{$centro->id}}>{{$centro->nombre}}</option>
                @endforeach
            </select>
         </div>
        </form>
    </div> 
    @endif

    <div id="dialog_newContrato" title="">
        <form autofocus autocomplete="off" class="form-vertical" id="form_newContrato" role="form" method="post" action="{{route('contratos.store')}}">
        {{ csrf_field() }}
         <input type="hidden" name="empleado_id" value={{$empleado->id}}>
         <div class="form-inline">
            <div class="col-md-6 form-group">
            <label for="alta" class="control-label">F.Alta</label>
            <input type="" name="alta" id="dialog_new_alta" class="form-control" value="" style="max-width: 100%">
            </div>
            <div class="col-md-6 form-group">
            <label for="baja" class="control-label">F.Baja</label>
            <input type="" name="baja" id="dialog_new_baja" class="form-control" value="" style="max-width: 100%">
            </div>
         </div>
         <div class="col-md-10 form-group" style="margin-top: 1em">
            <select class="form-control" id="dialog_new_centro" name="centro">
                <option value="">Elige un centro</option>
                @foreach ($centros as $centro)
                <option value={{$centro->id}}>{{$centro->nombre}}</option>
                @endforeach
            </select>
         </div>
        </form>
    </div> 



    </div>
    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/edit_empleado.js')}}"></script>

<script>
$(document).ready(function(){


$.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

$( function() {

    $( '#dialog_new_alta' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });
    $( '#dialog_new_baja' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });
    $( '#dialog_alta' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });
    $( '#dialog_baja' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });

} );









});
</script>

@endsection