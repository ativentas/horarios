@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Modificar Usuario</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/users') }}">Listado</a></li>
                    <li class="active">Modificar</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="row col-md-12">
    
    <form autocomplete="off" class="form-vertical" role="form" method="post" action="{{route('users.update',$user->id)}}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="col-md-6"> <!-- COLUMNA IZQUIERDA -->

            <div class="form-group{{$errors->has('nombre') ? ' has-error' : ''}}">
                <label for="nombre" class="control-label">Nombre</label>
                <input type="text" autocomplete="off" name="nombre" class="form-control" id="nombre" value="{{Request::old('nombre') ?: $user->name}}">
                @if ($errors->has('nombre'))
                    <span class="help-block">{{$errors->first('nombre')}}</span>
                @endif
            </div>

            <div class="form-group{{$errors->has('email') ? ' has-error' : ''}}">
                <label for="email" class="control-label">Email</label>
                <input type="text" name="email" class="form-control" id="email" value="{{Request::old('email') ?: $user->email}}">
                @if ($errors->has('email'))
                    <span class="help-block">{{$errors->first('email')}}</span>
                @endif                  
            </div>

            <div class="form-group @if($errors->has('centro')) has-error has-feedback @endif">
                <label for="">Centro</label>
                <select class="form-control" id="centro" name="centro">
                    <option value="">Selecciona un Dpto</option>
                    @foreach ($centros as $centro)
                    <option {{Request::old('centro')==$centro->id ? ' selected' : $user->centro_id==$centro->id ? ' selected' : ''}} value={{$centro->id}}>{{$centro->nombre}}</option>
                    @endforeach
                </select>

                @if ($errors->has('tipo'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('tipo') }}
                    </p>
                @endif
            </div>






            <div class="form-group">
                <button type="submit" class="btn btn-default">Modificar</button>
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