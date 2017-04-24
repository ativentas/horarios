<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}</style>
@extends('layouts.app')

@section('css')
<link href="{{asset('fullcalendar/fullcalendar.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link href="{{asset('fullcalendar/fullcalendar.print.css')}}" media="print" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="{{asset('fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('fullcalendar/locale/es.js')}}"></script>

@endsection

@section('content')
<div class="container" id="container" data-id="{{$empleado->id}}">
<div class="row">
<!-- <div class="col-md-10 col-md-offset-1"> -->
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="pager">
        @if($empleado_anterior)
        <li><a href="{{ url('empleados_c/'.$empleado_anterior) }}">Ant.</a></li>
        @endif
        <h2 style="display:inline;"><span class="label label-info">Horarios {{$empleado->alias}}</span></h2>
        @if($empleado_posterior)
        <li><a href="{{ url('empleados_c/'.$empleado_posterior) }}">Prox.</a></li>
        @endif        
        </ul>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('/empleados') }}">Listado</a></li>
                    @if(Auth::user()->isAdmin())
                    <li><a href="{{ url('/empleados/create') }}">Nuevo</a></li>
                    @endif
                </ol>
        </div>   

    </div>


    <div class="panel-body">

    <div class="col-md-7"> <!-- COLUMNA IZQUIERDA -->
            <div id='calendar'></div>
    </div>
    <div class="col-md-5"> <!-- COLUMNA DERECHA -->

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
                    <th>dias</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

            @foreach($ausenciasyear as $ausencia)
                <tr>
                    <th>{{ $ausencia->tipo }}</th>
                    <td>{{ $ausencia->abarca }}</td>
                    <td>{{ $ausencia->dias }}</td>
                    <td>{{ $ausencia->estado }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>


    </div> <!-- FIN COLUMNA DERECHA -->

    </div> <!-- FIN PANEL BODY -->
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
//TODO SUMAR LAS HORAS DE LAS LINEAS

    var base_url = '{{ url('/') }}';
    var empleado = $('#container').data('id');
    var url = base_url +'/api2/'+empleado;


$.ajax({
  url: url,
  // data: { param1: "value1", param2: "value2" },
  type: "GET",
  // context: document.body
}).done(function(data) {
  // your code goes here
  console.log(data);
});


    $('#calendar').fullCalendar({
        weekends: true,
        lang: 'es',
        header: {
            left: 'today prev,next',
            center: 'title',
            // right: 'basicDay,month,basicWeek',
            right: 'listDay,listWeek,month',

        },

        // customize the button names,
        // otherwise they'd all just say "list"
        views: {
            listDay: { buttonText: 'día' },
            listWeek: { buttonText: 'semana' },
            month: { displayEventTime: true, displayEventEnd:true}
        },
        defaultView: 'listWeek',
        slotEventOverlap: false,
        nextDayThreshold: '03:00:00', // 9am
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: {
            url: base_url + '/api2/' + empleado,
            error: function() {
                alert("cannot load json");
            }
        }

    });

    views: {
        month: { // name of view
            titleFormat: 'YYYY, MM, DD'
            // other view-specific options here
        }
    }


});
</script>

@endsection

