<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
@extends('layouts.app')



@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Editar Ausencia/Vacaciones</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('ausencias/calendario') }}">Calendario</a></li>
                    <li><a href="{{ url('ausencias') }}">Listado</a></li>
                    <li><a href="{{ url('/ausencias/create') }}">Nueva</a></li>
                </ol>
        </div>
    </div>

    <div class="panel-body">


    <div class="col-md-6">
        
        @if($errors)
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        @endif
        
        <form action="{{ url('ausencias/' . $ausencia->id) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT" />
            <div class="form-group @if($errors->has('empleado_id')) has-error has-feedback @endif">
                <label for="empleado_id">Empleado</label>
                <input type="text" class="form-control" name="" value="{{ $ausencia->empleado->alias }}" placeholder="" readonly="true">
                @if ($errors->has('empleado_id'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('empleado_id') }}
                    </p>
                @endif
            </div>            

            <div class="form-group @if($errors->has('tipo')) has-error has-feedback @endif">
                <label for="title">Tipo</label>
                <select class="form-control" id="tipo" name="tipo">
                    @foreach ($tipos as $sigla => $nombre)
                    <option {{Request::old('tipo')==$sigla ? ' selected' : $ausencia->tipo==$sigla ? ' selected' : ''}} value={{$sigla}}>{{$nombre}}</option>
                    @endforeach
                </select>

                @if ($errors->has('tipo'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('tipo') }}
                    </p>
                @endif
            </div>





<!--             <div class="form-group @if($errors->has('tipo')) has-error has-feedback @endif">
                <label for="title">Ausencia</label>
                <input type="text" class="form-control" name="tipo" value="{{ $ausencia->tipo }}" placeholder="">
                @if ($errors->has('tipo'))
                    <p class="help-block"><span class="glyphicon glyphicon-exclamation-sign"></span> 
                    {{ $errors->first('tipo') }}
                    </p>
                @endif
            </div> -->
            <div class="form-group @if($errors->has('time')) has-error @endif">
                <label for="time">Fechas</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="time" value="{{ $ausencia->fecha_inicio . ' - ' . $ausencia->finalDay }}" placeholder="Elige el tiempo">
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
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>     
    </div>



    </div>
</div>
</div>
</div>
</div>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script>
$(document).ready(function(){

$('input[name="time"]').daterangepicker({
    "autoApply": true,
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
    }
});
});
</script>

@endsection
