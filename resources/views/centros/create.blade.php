@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Nuevo Usuario</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/centros') }}">Listado</a></li>
                    <li class="active">Nuevo</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="row col-md-12">
    
    <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('centros.store')}}">
    {{ csrf_field() }}
    <div class="col-md-6"> <!-- COLUMNA IZQUIERDA -->

            <div class="form-group{{$errors->has('nombre') ? ' has-error' : ''}}">
                <label for="nombre" class="control-label">Nombre</label>
                <input type="text" autocomplete="off" name="nombre" class="form-control" id="nombre" value="{{Request::old('nombre')}}">
                @if ($errors->has('nombre'))
                    <span class="help-block">{{$errors->first('nombre')}}</span>
                @endif
            </div>

            <div class="form-group{{$errors->has('empresa') ? ' has-error' : ''}}">
                <label for="empresa" class="control-label">Empresa</label>
                <input type="text" name="empresa" class="form-control" id="empresa" value="{{Request::old('empresa')}}">
                @if ($errors->has('empresa'))
                    <span class="help-block">{{$errors->first('empresa')}}</span>
                @endif                  
            </div>





            <div class="form-group">
                <button type="submit" class="btn btn-default">Nuevo</button>
            </div>      
    </div> <!-- FIN COLUMNA IZQUIERDA -->
    </form>    


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