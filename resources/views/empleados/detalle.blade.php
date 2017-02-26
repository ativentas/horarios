@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Datos {{$empleado->alias}}</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/empleados') }}">Listado</a></li>
                    <li><a href="{{ url('/empleados/create') }}">Nuevo</a></li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-12">
        @if($lineas->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Dia</th>
                    <th>Situacion</th>
                    <th>H. Entrada</th>
                    <th>H. Salida</th>
                    <th>H. Entrada</th>
                    <th>H. Salida</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($lineas as $linea)
                <tr>
                    <th>{{ $linea->fecha }}</th>
                    <td>{{ $linea->dia_texto }}</td>
                    <td>{{ $linea->situacion }}</td>
                    <td>{{ $linea->entrada1 }}</td>
                    <td>{{ $linea->salida1 }}</td>
                    <td>{{ $linea->entrada2 }}</td>
                    <td>{{ $linea->salida2 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2>No hay ningún dato</h2>
        @endif
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

