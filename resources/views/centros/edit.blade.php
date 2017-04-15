@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Modificar Departamento</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/centros') }}">Listado</a></li>
                    <li class="active">Modificar</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="row col-md-12">
    
    <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('centros.update',$centro->id)}}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="col-md-5"> <!-- COLUMNA IZQUIERDA -->

            <div class="form-group{{$errors->has('nombre') ? ' has-error' : ''}}">
                <label for="nombre" class="control-label">Nombre</label>
                <input type="text" autocomplete="off" name="nombre" class="form-control" id="nombre" value="{{Request::old('nombre')?:$centro->nombre}}">
                @if ($errors->has('nombre'))
                    <span class="help-block">{{$errors->first('nombre')}}</span>
                @endif
            </div>

            <div class="form-group{{$errors->has('empresa') ? ' has-error' : ''}}">
                <label for="empresa" class="control-label">Empresa</label>
                <input type="text" name="empresa" class="form-control" id="empresa" value="{{Request::old('empresa')?:$centro->empresa}}">
                @if ($errors->has('empresa'))
                    <span class="help-block">{{$errors->first('empresa')}}</span>
                @endif                  
            </div>

            <div class="form-group @if($errors->has('dia_cierre')) has-error has-feedback @endif">
                <label for="">Cierra</label>
                <select class="form-control" id="dia_cierre" name="dia_cierre">
                    <option value="">No Cierra</option>
                    @foreach ($dias as $num=>$texto)
                    <option {{Request::old('dia_cierre')===$num ? ' selected' : $centro->dia_cierre===$num ? ' selected':''}} value={{$num}}>{{$texto}}</option>
                    @endforeach
                </select>

                @if ($errors->has('dia_cierre'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('dia_cierre') }}
                    </p>
                @endif
            </div>
            <div class="form-group">
            <div class="checkbox">
                <label><input type="checkbox" value="1" name="abrefestivos"  {{ old('abrefestivos', $centro->abrefestivos) === 1 ? 'checked' : '' }}>Abre Festivos</label>
            </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default">Modificar</button>
            </div>      
    </div> <!-- FIN COLUMNA IZQUIERDA -->
    </form>    


    <div class="col-md-7"> <!-- COLUMNA DERECHA -->

    <div class="panel panel-default">
    <div class="panel-heading"><h4>Alta Nuevo Predefinido</h4></div>
    <div class="panel-body">
        <div class="form-group">
            <button type="submit" class="btn btn-default" id="button_new_predefinido">Nuevo Predefinido</button>
        </div>  
    </div>
    </div>

    @if(count($predefinidos))
    <div class="panel panel-default">
    <div class="panel-heading"><h4>Predefinidos</h4></div>
    <div class="panel-body">
    @foreach($predefinidos as $predefinido)
    <div class="form-inline">
        <div class="col-md-4 form-group" style="padding:0px;">
            <label for="nombre" class="control-label">Nombre</label>
            <div class="input-group">
            <input type="text" autocomplete="off" name="" class="form-control" id="predefinido_nombre_{{$predefinido->id}}" value="{{$predefinido->nombre}}" readonly>
            <span class="input-group-addon"><button data-predefinido_id="{{$predefinido->id}}" class="btn btn-info btn-xs btn_modify_predefinido" id="button_modify_{{$predefinido->id}}" type="button" style=""><span class="glyphicon glyphicon-edit"></span></button></span>         
            </div>
        </div>
        <div class="col-md-2 form-group" style="padding:0px;">
            <label for="entrada1" class="control-label">Entrada</label>
            <input type="" autocomplete="off" name="entrada1" class="form-control" id="entrada1_{{$predefinido->id}}" value="{{$predefinido->entrada1}}" style="max-width: 100%;" readonly>
        </div>
        <div class="col-md-2 form-group" style="padding:0px;">
            <label for="salida1" class="control-label">Salida</label>
            <input type="" autocomplete="off" name="salida1" class="form-control" id="salida1_{{$predefinido->id}}" value="{{$predefinido->salida1}}" style="max-width: 100%;" readonly>
        </div>
        <div class="col-md-2 form-group" style="padding:0px;">
            <label for="entrada2" class="control-label">Entrada</label>
            <input type="" autocomplete="off" name="entrada2" class="form-control" id="entrada2_{{$predefinido->id}}" value="{{$predefinido->entrada2}}" style="max-width: 100%;" readonly>
        </div>
        <div class="col-md-2 form-group" style="padding:0px;">
            <label for="salida2" class="control-label">Salida</label>
            <input type="" autocomplete="off" name="salida2" class="form-control" id="salida2_{{$predefinido->id}}" value="{{$predefinido->salida2}}" style="max-width: 100%;" readonly>
        </div>

    </div>
    @endforeach
    </div>
    </div>
    @endif
    
    </div> <!-- FIN COLUMNA DERECHA -->


    <div id="dialogPredefinido" title="">
        <form autofocus autocomplete="off" class="form-vertical" id="form_predefinido" role="form" method="post" action="{{route('predefinidos.update',[':PREDEFINIDO_ID'])}}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="col-md-10 form-group" style="margin-top: 1em">
            <label for="nombre" class="control-label">Nombre</label>
            <input type="" name="nombre" id="dialog_nombre" class="form-control predefinido_nombre" value="" style="max-width: 100%">
        </div>
        <div class="form-inline">
            <div class="col-md-6 form-group">
            <label for="entrada1" class="control-label">Entrada</label>
            <input type="" name="entrada1" id="dialog_entrada1" class="form-control predefinido_entrada1" value="" style="max-width: 100%">
            </div>
            <div class="col-md-6 form-group">
            <label for="salida1" class="control-label">Salida</label>
            <input type="" name="salida1" id="dialog_salida1" class="form-control predefinido_salida1" value="" style="max-width: 100%">
            </div>
        </div>
        </form>
    </div> 


    <div id="dialog_newPredefinido" title="">
        <form autofocus autocomplete="off" class="form-vertical" id="form_newPredefinido" role="form" method="post" action="{{route('predefinidos.store')}}">
        {{ csrf_field() }}
         <input type="hidden" name="centro_id" value={{$centro->id}}>

        </form>
    </div> 







    </div>
    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/edit_centro.js')}}"></script>

<script>

$(document).ready(function(){



});
</script>

@endsection