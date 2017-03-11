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
            <ul class="pager">
            @if($anteriorId)
            <li><a href="{{ url('empleados_c/'.$empleado->id.'/'.$anteriorId) }}">Ant.</a></li>
            @endif
            @if($cuadrante)
            <span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$cuadrante->semana}} ({{$cuadrante->year}}) - {{$cuadrante->abarca}}</span>
            @endif
            @if($posteriorId)
            <li><a href="{{ url('empleados_c/'.$empleado->id.'/'.$posteriorId) }}">Prox.</a></li>
            @endif
          </ul>




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
            <col style="width: 121px">
            <col style="width: 101px">
            <col style="width: 82px">
            </colgroup>
              <tr>
                <th class="tg-031e"></th>
    <!--             <th class="tg-s6z2">Mes<br></th>
     -->            <th class="tg-s6z2">{{$year}}</th>
              </tr>
              <tr>
                <td class="tg-031e">Vacaciones</td>
    <!--             <td class="tg-031e"></td>
     -->            <td class="tg-031e">{{$vaclineasacum}}</td>
              </tr>
              <tr>
                <td class="tg-031e">Otras Ausencias</td>
    <!--             <td class="tg-031e"></td>
     -->            <td class="tg-031e">{{$otraslineasacum}}</td>
              </tr>
            </table>
        </div>



    <div class="">
        @if($ausenciasyear->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Abarca</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

            @foreach($ausenciasyear as $ausencia)
                <tr>
                    <th>{{ $ausencia->tipo }}</th>
                    <td>{{ $ausencia->abarca }}</td>
                    <td>{{ $ausencia->estado }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
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

