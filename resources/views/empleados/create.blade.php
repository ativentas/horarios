@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Nuevo Empleado</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/empleados') }}">Listado</a></li>
                    <li class="active">Nuevo</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-12">

            <h2>Aqui va el formulario para crear nuevo empleado</h2>


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