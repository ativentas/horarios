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

    <div class="col-md-12">

    <div class="row col-md-6">
        <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('empleados.update',$empleado->id)}}">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

            <div class="form-group{{$errors->has('alias') ? ' has-error' : ''}}">
                <label for="alias" class="control-label">Nombre</label>
                <input type="text" autocomplete="off" name="alias" class="form-control" id="alias" value="{{Request::old('alias') ?: $empleado->alias}}">
                @if ($errors->has('alias'))
                    <span class="help-block">{{$errors->first('alias')}}</span>
                @endif
            </div>
            <div class="form-group{{$errors->has('nombre') ? ' has-error' : ''}}">
                <label for="nombre" class="control-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" id="nombre" value="{{Request::old('nombre') ?: $empleado->nombre_completo}}">
                @if ($errors->has('nombre'))
                    <span class="help-block">{{$errors->first('nombre')}}</span>
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
                <button type="submit" class="btn btn-default">Registrar</button>
            </div>      


        </form>
    </div>
    </div>


    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){

});
</script>

@endsection