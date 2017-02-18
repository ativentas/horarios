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
            @if($linea->situacion1 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->ELU))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SLU))}}</td>
            @endif
            @if($linea->situacion2 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EMA))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SMA))}}</td>
            @endif
            @if($linea->situacion3 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EMI))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SMI))}}</td>
            @endif
            @if($linea->situacion4 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EJU))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SJU))}}</td>
            @endif
            @if($linea->situacion5 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EVI))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SVI))}}</td>
            @endif
            @if($linea->situacion6 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->ESA))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SSA))}}</td>
            @endif
            @if($linea->situacion7 == 'V')
            <td colspan="2" rowspan="2">VAC</td>
            @else
            <td class="tg-031e">{{date('H:i',strtotime($linea->EDO))}}</td>
            <td class="tg-031e">{{date('H:i',strtotime($linea->SDO))}}</td>
            @endif
          </tr>
          <tr>
            @if($linea->situacion1 == 'V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion2 == 'V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion3 == 'V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion4 =='V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion5 == 'V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion6 == 'V')
            @else
            <td class="tg-031e"></td>
            <td class="tg-031e"></td>
            @endif
            @if($linea->situacion7 == 'V')
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


});


</script>

@endsection