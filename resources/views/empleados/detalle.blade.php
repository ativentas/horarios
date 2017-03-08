<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<!-- <div class="col-md-10 col-md-offset-1"> -->
<div class="col-md-12">
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

    <div class="col-md-7">
        @if($lineas->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Dia</th>
                    <th>Situacion</th>
                    <th>Entr.</th>
                    <th>Salida</th>
                    <th>Entr.</th>
                    <th>Salida</th>
                    <th>Horas</th>
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
                    <td>{{ $linea->horasdiarias }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2>No hay ningún dato</h2>
        @endif
    </div>
    <div class="col-md-5">

        <div class="well">
            <h2 align="center"><span class="label label-info">Resumen</span></h2>
        </div>
        <div class="tg-wrap">
        <table class="tg" style="undefined;table-layout: fixed; width: 304px">
        <colgroup>
        <col style="width: 101px">
        <col style="width: 101px">
        <col style="width: 102px">
        </colgroup>
          <tr>
            <th class="tg-031e"></th>
            <th class="tg-s6z2">Mes<br></th>
            <th class="tg-s6z2">Año</th>
          </tr>
          <tr>
            <td class="tg-031e">Vacaciones</td>
            <td class="tg-031e"></td>
            <td class="tg-031e">{{$resumen}}</td>
          </tr>
          <tr>
            <td class="tg-031e">Ausencias</td>
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
          </tr>
        </table>
        </div>
    </div>
    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
//TODO SUMAR LAS HORAS DE LAS LINEAS
});
</script>

@endsection

