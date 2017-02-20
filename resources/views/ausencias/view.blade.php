@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Vista Detalle</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('ausencias/calendario') }}">Calendario</a></li>
                    <li><a href="{{ url('ausencias') }}">Listado</a></li>
                    <li><a href="{{ url('ausencias/create') }}">Nuevo</a></li>
                </ol>
        </div>
    </div>

    <div class="panel-body">
    <h2>{{ $ausencia->tipo }} <small>{{ $ausencia->alias }}</small></h2>
    <hr>
    <div class="col-md-12">

        <p>Fechas: <br>
        {{ date("jS M Y", strtotime($ausencia->fecha_inicio)) . ' hasta ' . date("jS M Y", strtotime($ausencia->finalDay)) }}
        </p>
        
        <p>Duración: <br>
        {{ $duration }}
        </p>
        
        <p>
            <form action="{{ url('ausencias/' . $ausencia->id) }}" style="display:inline;" method="POST">
                <input type="hidden" name="_method" value="DELETE" />
                {{ csrf_field() }}
                <button class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-trash"></span> Delete</button>
            </form>
            <a class="btn btn-primary" href="{{ url('ausencias/' . $ausencia->id . '/edit')}}">
                <span class="glyphicon glyphicon-edit"></span> Edit</a> 
            
        </p>



    </div>


    </div>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){

});
</script>

@endsection

