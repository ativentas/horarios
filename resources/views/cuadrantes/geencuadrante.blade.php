@extends('layouts.app')

@section('content')

<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading">
    <div class="row">
       @include('layouts.alerts')            

            <ol class="breadcrumb">
                <li><a href="{{ url('home') }}">Salir</a></li>
                <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
            </ol>

    </div>

    <div class="panel-body">

 
</div> <!-- Fin del panel default-->
</div>
</div>
</div>

@endsection
<script>alert('no hay ningún horario preparado todavía');</script>