@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Listado Empleados</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                    <li><a href="{{ url('/empleados/create') }}">Nuevo</a></li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-12">
        @if($empleados->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Centro Trabajo</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($empleados as $empleado)
                <tr>
                    <th scope="row">{{ $i++ }}</th>
                    <td><a href="{{ url('/empleados/' . $empleado->id) }}">{{ $empleado->alias }}</a></td>
                    <td>{{$empleado->centro->nombre}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
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

