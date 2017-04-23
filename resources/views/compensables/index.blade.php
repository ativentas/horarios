<style type="text/css">
.table{margin-bottom: 0px !important;}
</style>

@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Dias para compensar</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                </ol>
        </div>
        @if(Auth::user()->isAdmin())     
        <form method="get" action="{{url('compensaciones')}}" class="form-inline">
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
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Centro Trabajo</th>
                    <th>Días pendientes</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>           
            @foreach($empleados as $empleado)
            <tbody>
                <tr data-id="{{$empleado->id}}">
                    <td><a href="{{ url('/empleados_c/' . $empleado->id) }}">{{ $empleado->alias }}</a></td>
                    <td>{{$empleado->centro[0]->nombre}}</td>
                    <td>{{$empleado->compensables_count}}</td>
                    <td></td>
                </tr>
                @foreach($empleado->compensables as $compensable)
				<td colspan=""></td>
                <td colspan="3">	
					<table class="table">
					<tr data-compensable_id="{{$compensable->id}}" data-empleado_nombre="{{$empleado->alias}}">
						<td></td>
						<td>{{date("d-m-Y",strtotime($compensable->dia))}}</td>
						<td>{{$compensable->linea->situacion}}</td>
						<td>@if($compensable->disponible)<button class="btn btn-success btn-xs btn_asignar" type="button"><span class="glyphicon glyphicon-ok-sign"></span> Asignar</button>@elseif($compensable->diacompensado){{$compensable->diacompensado}}@else PAGAR @endif</td>
					</tr>
					</table>
                </td>
                <td></td>
                @endforeach
            </tbody>
            @endforeach
            
        </table>
        @else
            <h2>No hay ningún dato</h2>
        @endif

    </div>

    </div> <!-- FIN DEL PANEL BODY -->


{{csrf_field()}}
</form>



    <div id="dialogAsignar-form" title="">
  	<form id = "Asignar-form" action="{{url('compensaciones/:COMPENSABLE_ID')}}" method="POST" autofocus>
    {{csrf_field()}}
    <fieldset>
        <div class="form-group">
            <label class="radio-inline">
              <input type="radio" name="radio_compensar" value="P">Pagar
            </label>
            <label class="radio-inline">
              <input type="radio" name="radio_compensar" value="DL">Día Libre
            </label>
        </div>
<!--         <div class="form-group" id="group_pagar" style="display:none;">
            <textarea class="form-control" required="" placeholder="Escribe un comentario aquí..." name = "nota" id=""></textarea> 
        </div>
        <div class="form-group" id="group_libre" style="display:none;">
            <div class="col-md-8" style="margin-bottom: 10px;">
            <input type="text" name="fecha" class="form-control" id="fecha" value="" style="" placeholder="elige fecha" readonly>
            </div>
            
            <textarea class="form-control" required="" placeholder="Escribe una comentario aquí..." name = "nota" id=""></textarea> 
        </div> -->

 
            <div class="col-md-8" id="div_fecha" style="margin-bottom: 10px;display:none;">
            <input type="text" name="fecha" class="form-control" id="fecha" value="" style="" placeholder="elige fecha" readonly>
            </div>
            <div id="div_nota" style="display:none;">
            <textarea class="form-control" required="" placeholder="Escribe un comentario aquí..." name = "nota" id=""></textarea>
            </div>







    </fieldset>
  	</form>
	</div>

</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/compensables.js')}}"></script>

<script>
$(document).ready(function() {

$.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

$( function() {
    $( '#fecha' ).datepicker({
        dateFormat: "dd-mm-yy",
        showWeek: true,
    });

} );




});    

</script>

@endsection

