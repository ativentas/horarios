@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">

    <div class="panel-heading"><h2>Listado Horarios</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                    @if (!Auth::user()->isAdmin())               
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                    @endif
                    
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-12">
        @if($cuadrantes->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($cuadrantes as $cuadrante)
                <tr>
                    <th>{{$cuadrante->semana}}</th>
                    <th>{{$cuadrante->abarca}}</th>
                    <td>{{$cuadrante->centro->nombre}}</td>
                    <td>{{$cuadrante->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $cuadrante->id) }}">Ver</a></td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2></h2>
        @endif
    </div>

    <div class="col-md-12">
        @if($completados->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($completados as $completado)
                <tr>
                    <th>{{$completado->semana}}</th>
                    <td>{{$completado->abarca}}</td>
                    <td>{{$completado->centro->nombre}}</td>
                    <td>{{$completado->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $completado->id) }}">Ver</a></td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2></h2>
        @endif
    </div>

                


    </div>

</div>
</div>
</div>
</div>
@endsection