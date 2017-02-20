<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}
</style>



@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Detalle Horario Semana</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                </ol>
        </div>
        <div class="row">
        <div class="col-md-4 col-md-offset-3"><span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$semana}} ({{$year}}) - {{date_format($inicio_semana,'d M')}} al {{date_format($final_semana,'d M')}}</span>
        </div>
        </div>
    </div>

    <div class="panel-body">
        
<!-- TABLA -->
        <div class="tg-wrap"><table class="tg" style="undefined;table-layout: fixed; width: 767px">
        <colgroup>
        <col style="width: 121px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        <col style="width: 50px">
        </colgroup>
          <tr>
            <th class="tg-s6z2"></th>
            <th class="tg-s6z2" colspan="2">Lunes</th>
            <th class="tg-s6z2" colspan="2">Martes</th>
            <th class="tg-s6z2" colspan="2">Miercoles</th>
            <th class="tg-s6z2" colspan="2">Jueves</th>
            <th class="tg-s6z2" colspan="2">Viernes</th>
            <th class="tg-s6z2" colspan="2">Sabado</th>
            <th class="tg-s6z2" colspan="2">Domingo</th>
          </tr>
          @foreach($lineas as $linea)
          <tr>
            <td class="tg-031e" rowspan="2">{{$linea->nombre}}</td>
            @if($linea->situacion1)
            <td colspan="2" rowspan="2">{{$linea->situacion1}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->ELU))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SLU))}}</td>
            @endif
            @if($linea->situacion2)
            <td colspan="2" rowspan="2">{{$linea->situacion2}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EMA))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SMA))}}</td>
            @endif
            @if($linea->situacion3)
            <td colspan="2" rowspan="2" align="center">{{$linea->situacion3}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EMI))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SMI))}}</td>
            @endif
            @if($linea->situacion4)
            <td colspan="2" rowspan="2">{{$linea->situacion4}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EJU))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SJU))}}</td>
            @endif
            @if($linea->situacion5)
            <td colspan="2" rowspan="2">{{$linea->situacion5}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EVI))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SVI))}}</td>
            @endif
            @if($linea->situacion6)
            <td colspan="2" rowspan="2">{{$linea->situacion6}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->ESA))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SSA))}}</td>
            @endif
            @if($linea->situacion7)
            <td colspan="2" rowspan="2">{{$linea->situacion7}}</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EDO))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SDO))}}</td>
            @endif
          </tr>
          <tr>
            @if($linea->situacion1)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion2)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion3)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion4)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion5)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion6)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion7)
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
          </tr>
          @endforeach
        </table></div>
    </div>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
// TO DO: si hay algún día cerrado o festivo, cambiar el color de la columna
// TO DO: click en botón Abierto o Cerrado del día de la semana, abre modal para elegir Abierto o Cerrado. Si se marca cerrado, borrará todos los horarios de ese día
// TO DO: (ver siguiente TO DO) si se modifica algún horario de una columna que es festivo, y se comprueba que al menos se trabaja 1 hora, grabar en base de datos FT. si ese día está cerrado, y al menos 1 hora trabajada, entonces se marca SD (se debe)
// TO DO: click en una casilla de horario, se abre modal para introducir horario (desplegable con horarios habituales, opción marcar SD (se debe))
// TO DO: click en nombre del empleado, se abre modal para introducir el horario por defecto para esa semana, el programa rellenará las casillas, excepto el dia de cierre y, en su caso, los festivos
});


</script>

@endsection