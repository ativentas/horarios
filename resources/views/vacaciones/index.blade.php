@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Resumen Vacaciones</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                </ol>
        </div>
        @if(Auth::user()->isAdmin())     
        <form method="get" action="{{url('vacaciones')}}" class="form-inline">
                {{ csrf_field() }}
        
        <div class="form-group">
            <select class="form-control" id="centro" name="centro">
                <option value="">Todas los centros</option>
                @foreach ($centros as $centro)
                <option class="" value="{{$centro->id}}" {{ (Request::input('centro') == $centro->id ? "selected":"") }}>{{$centro->nombre}}</option>
                @endforeach                
            </select>
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-default">Filtrar</button>
        </div>
        </form>
        @endif
    </div>

    <div class="panel-body">

    <div class="col-md-10 col-md-offset-1">
        @if($empleados->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Centro Trabajo</th>
                    <th>Saldo 2016</th>
                    <th>Confirmadas</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($empleados as $empleado)
                <tr data-id="{{$empleado->id}}">
                    <td><a href="{{ url('/empleados_c/' . $empleado->id) }}">{{ $empleado->alias }}</a></td>
                    <td>{{$empleado->centro}}</td>
                    <td>{{$empleado->saldoanterior}}</td>
                    <td>{{$empleado->confirmadas}}</td>
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
$(document).ready(function() {


});    

</script>

@endsection

