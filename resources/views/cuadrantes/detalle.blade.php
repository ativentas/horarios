<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:2px 2px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}
.A {background-color:palegreen}
.C {background:lightpink}
.FA {background-color: powderblue}
.FC {background-color: violet}
.ausencia {}

.wrapper {
    height: 60px;
    width: 100px;
    padding: 0px;
    position:absolute;
    margin-top: -25px;
    margin-left: -3px;
}
.diasemana{
  pointer-events: none;
}

.wrapper button {
    height: 100%;
    width: 100%;
}
td input {
    width: 100%;
    padding: 0px;

}

</style>

@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
        <div class="col-md-5 col-md-offset-3"><span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$semana}} ({{$year}}) - {{date_format($inicio_semana,'d M')}} al {{date_format($final_semana,'d M')}}</span>
        </div>
        </div>
    <!-- <h2>Detalle Horario Semana</h2> -->
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                    @if ($cuadrante->archivado == '0')
                    <li><button class="btn-success btn-xs btn-guardar" id="btn_guardar" name="guardar" style="display:none;">Guardar Cambios</button></li>
                    @endif
                </ol>
        </div>
<!--         <div class="row">
        <div class="col-md-4 col-md-offset-3"><span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$semana}} ({{$year}}) - {{date_format($inicio_semana,'d M')}} al {{date_format($final_semana,'d M')}}</span>
        </div>
        </div>
 -->    

<!-- Imagen que ocupa las 4 celdas si se pone en la entrada1, puede servir para cuando sea VT, pero prefiero probar a poner un fondo en los td que sean VT o SD.
TO DO: se me ha ocurrido combinar tanto el color naranja como una pequeñita imagen que sea solo la letra V y que vaya en medio de los 4 horarios y así no tapa y no hay que poner opacidad -->


    </div> <!-- fin panel heading -->

    <div class="panel-body">        
        <form id="form_guardar" action="{{route('guardarCuadrante',array('cuadrante_id'=>$cuadrante->id))}}" method="POST"> 
        {{csrf_field()}}
        <!-- TABLA -->
        <div class="tg-wrap">
        <table data-isadmin="{{Auth::user()->is_admin}}" data-estadocuadrante="{{$cuadrante->estado}}" class="tg" id="tabla_plantilla" style="undefined;table-layout: fixed; width: 767px">
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
                <th data-dia="1" data-estadodia="{{$cuadrante->dia_1}}" class="tg-s6z2 diasemana {{$cuadrante->dia_1}}" id="estadodia_1" colspan="2" disabled>Lunes</th>
                <input type="hidden" name="nuevoestadodia_1" id="nuevoestadodia_1">
                <th data-dia="2" data-estadodia="{{$cuadrante->dia_2}}" class="tg-s6z2 diasemana {{$cuadrante->dia_2}}" id="estadodia_2" colspan="2">Martes</th>
                <input type="hidden" name="nuevoestadodia_2" id="nuevoestadodia_2">
                <th data-dia="3" data-estadodia="{{$cuadrante->dia_3}}" class="tg-s6z2 diasemana {{$cuadrante->dia_3}}" id="estadodia_3" colspan="2">Miercoles</th>
                <input type="hidden" name="nuevoestadodia_3" id="nuevoestadodia_3">
                <th data-dia="4" data-estadodia="{{$cuadrante->dia_4}}" class="tg-s6z2 diasemana {{$cuadrante->dia_4}}" id="estadodia_4" colspan="2">Jueves</th>
                <input type="hidden" name="nuevoestadodia_4" id="nuevoestadodia_4">
                <th data-dia="5" data-estadodia="{{$cuadrante->dia_5}}" class="tg-s6z2 diasemana {{$cuadrante->dia_5}}" id="estadodia_5" colspan="2">Viernes</th>
                <input type="hidden" name="nuevoestadodia_5" id="nuevoestadodia_5">
                <th data-dia="6" data-estadodia="{{$cuadrante->dia_6}}" class="tg-s6z2 diasemana {{$cuadrante->dia_6}}" id="estadodia_6" colspan="2">Sabado</th>
                <input type="hidden" name="nuevoestadodia_6" id="nuevoestadodia_6">
                <th data-dia="0" data-estadodia="{{$cuadrante->dia_0}}" class="tg-s6z2 diasemana {{$cuadrante->dia_0}}" id="estadodia_0" colspan="2">Domingo</th>
                <input type="hidden" name="nuevoestadodia_0" id="nuevoestadodia_0">
            </tr>
            @foreach($lineas as $linea)
            <tr class="datos_empleado" data-empleado_id={{$linea->empleado_id}} data-empleado_nombre={{$linea->nombre}} style="">
                <td data-empleado_id="{{$linea->empleado_id}}" class="tg-031e" rowspan="2"style="height:60px;"><span>{{$linea->nombre}}</span> 
                    <button class="btn btn-info btn-xs btn_modify" id="button_modify_{{$linea->empleado_id}}" type="button" style="display: none;"><span class="glyphicon glyphicon-edit"></span></button>
                </td>
                <td data-dia="1" class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="entrada1_1_{{$linea->empleado_id}}" id="entrada1_1_{{$linea->empleado_id}}" value="{{is_null($linea->ELU) ? '' : date('H:i',strtotime($linea->ELU))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion1,['V','B','AJ','AN','L'])? 'display:none;':''}}" disabled>{{$linea->situacion1}}</button>
                    </div>
                    <input type="hidden" name="situacion_1_{{$linea->empleado_id}}" id="situacion_1_{{$linea->empleado_id}}" value="{{$linea->situacion1}}">
                </td>             
                <td class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="salida1_1_{{$linea->empleado_id}}" id="salida1_1_{{$linea->empleado_id}}" value="{{is_null($linea->SLU) ? '' : date('H:i',strtotime($linea->SLU))}}">
                </td>

                <td data-dia="2" class="tg-031e" id="">
                    <input class="horariosdia_2"  type="text" name="entrada1_2_{{$linea->empleado_id}}" id="entrada1_2_{{$linea->empleado_id}}" value="{{is_null($linea->EMA) ? '' : date('H:i',strtotime($linea->EMA))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion2,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion2}}</button>
                    </div>
                    <input type="hidden" name="situacion_2_{{$linea->empleado_id}}" id="situacion_2_{{$linea->empleado_id}}" value="{{$linea->situacion2}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_2" type="text" name="salida1_2_{{$linea->empleado_id}}" id="salida1_2_{{$linea->empleado_id}}" value="{{is_null($linea->SMA) ? '' : date('H:i',strtotime($linea->SMA))}}">                    
                </td>

                <td data-dia="3" class="tg-031e" id="">
                    <input class="horariosdia_3" class="" type="text" name="entrada1_3_{{$linea->empleado_id}}" id="entrada1_3_{{$linea->empleado_id}}" value="{{is_null($linea->EMI) ? '' : date('H:i',strtotime($linea->EMI))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion3,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion3}}</button>
                    </div>
                    <input type="hidden" name="situacion_3_{{$linea->empleado_id}}" id="situacion_3_{{$linea->empleado_id}}"  value="{{$linea->situacion3}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_3" type="text" name="salida1_3_{{$linea->empleado_id}}" id="salida1_3_{{$linea->empleado_id}}" value="{{is_null($linea->SMI) ? '' : date('H:i',strtotime($linea->SMI))}}">                
                </td>

                <td data-dia="4" class="tg-031e" id="">
                    <input class="horariosdia_4"  type="text" name="entrada1_4_{{$linea->empleado_id}}" id="entrada1_4_{{$linea->empleado_id}}" value="{{is_null($linea->EJU) ? '' : date('H:i',strtotime($linea->EJU))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion4,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion4}}</button>
                    </div>
                    <input type="hidden" name="situacion_4_{{$linea->empleado_id}}" id="situacion_4_{{$linea->empleado_id}}"  value="{{$linea->situacion4}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_4" type="text" name="salida1_4_{{$linea->empleado_id}}" id="salida1_4_{{$linea->empleado_id}}" value="{{is_null($linea->SJU) ? '' : date('H:i',strtotime($linea->SJU))}}">                    
                </td>

                <td data-dia="5" class="tg-031e" id="">
                    <input class="horariosdia_5"  type="text" name="entrada1_5_{{$linea->empleado_id}}" id="entrada1_5_{{$linea->empleado_id}}" value="{{is_null($linea->EVI) ? '' : date('H:i',strtotime($linea->EVI))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion5,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion5}}</button>
                    </div>
                    <input type="hidden" name="situacion_5_{{$linea->empleado_id}}" id="situacion_5_{{$linea->empleado_id}}" value="{{$linea->situacion5}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_5" type="text" name="salida1_5_{{$linea->empleado_id}}" id="salida1_5_{{$linea->empleado_id}}" value="{{is_null($linea->SVI) ? '' : date('H:i',strtotime($linea->SVI))}}">                    
                </td>

                <td data-dia="6" class="tg-031e" id="">
                    <input class="horariosdia_6"  type="text" name="entrada1_6_{{$linea->empleado_id}}" id="entrada1_6_{{$linea->empleado_id}}" value="{{is_null($linea->ESA) ? '' : date('H:i',strtotime($linea->ESA))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion6,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion6}}</button>
                    </div>
                    <input type="hidden" name="situacion_6_{{$linea->empleado_id}}" id="situacion_6_{{$linea->empleado_id}}" value="{{$linea->situacion6}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_6" type="text" name="salida1_6_{{$linea->empleado_id}}" id="salida1_6_{{$linea->empleado_id}}" value="{{is_null($linea->SSA) ? '' : date('H:i',strtotime($linea->SSA))}}">                    
                </td>

                <td data-dia="0" class="tg-031e" id="">
                    <input class="horariosdia_0"  type="text" name="entrada1_0_{{$linea->empleado_id}}" id="entrada1_0_{{$linea->empleado_id}}" value="{{is_null($linea->EDO) ? '' : date('H:i',strtotime($linea->EDO))}}">
                    <div class="wrapper">                        
                        <button class="ausencia" type="button" style="{{!in_array($linea->situacion0,['V','B','AJ','AN','L'])? 'display:none;':''}}">{{$linea->situacion0}}</button>
                    </div>
                    <input type="hidden" name="situacion_0_{{$linea->empleado_id}}" id="situacion_0_{{$linea->empleado_id}}" value="{{$linea->situacion0}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_0" type="text" name="salida1_0_{{$linea->empleado_id}}" id="salida1_0_{{$linea->empleado_id}}" value="{{is_null($linea->SDO) ? '' : date('H:i',strtotime($linea->SDO))}}">                    
                </td>

            </tr>
            <tr style="">

                <td class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="entrada2_1_{{$linea->empleado_id}}" id="entrada2_1_{{$linea->empleado_id}}" value="{{is_null($linea->E2LU)?'':date('H:i',strtotime($linea->E2LU))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="salida2_1_{{$linea->empleado_id}}" id="salida2_1_{{$linea->empleado_id}}" value="{{is_null($linea->S2LU)?'':date('H:i',strtotime($linea->S2LU))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_2" type="text" name="entrada2_2_{{$linea->empleado_id}}" id="entrada2_2_{{$linea->empleado_id}}" value="{{is_null($linea->E2MA)?'':date('H:i',strtotime($linea->E2MA))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_2" type="text" name="salida2_2_{{$linea->empleado_id}}" id="salida2_2_{{$linea->empleado_id}}" value="{{is_null($linea->S2MA)?'':date('H:i',strtotime($linea->S2MA))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_3" type="text" name="entrada2_3_{{$linea->empleado_id}}" id="entrada2_3_{{$linea->empleado_id}}" value="{{is_null($linea->E2MI)?'':date('H:i',strtotime($linea->E2MI))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_3" type="text" name="salida2_3_{{$linea->empleado_id}}" id="salida2_3_{{$linea->empleado_id}}" value="{{is_null($linea->S2MI)?'':date('H:i',strtotime($linea->S2MI))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_4" type="text" name="entrada2_4_{{$linea->empleado_id}}" id="entrada2_4_{{$linea->empleado_id}}" value="{{is_null($linea->E2JU)?'':date('H:i',strtotime($linea->E2JU))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_4" type="text" name="salida2_4_{{$linea->empleado_id}}" id="salida2_4_{{$linea->empleado_id}}" value="{{is_null($linea->S2JU)?'':date('H:i',strtotime($linea->S2JU))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_5" type="text" name="entrada2_5_{{$linea->empleado_id}}" id="entrada2_5_{{$linea->empleado_id}}" value="{{is_null($linea->E2VI)?'':date('H:i',strtotime($linea->E2VI))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_5" type="text" name="salida2_5_{{$linea->empleado_id}}" id="salida2_5_{{$linea->empleado_id}}" value="{{is_null($linea->S2VI)?'':date('H:i',strtotime($linea->S2VI))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_6" type="text" name="entrada2_6_{{$linea->empleado_id}}" id="entrada2_6_{{$linea->empleado_id}}" value="{{is_null($linea->E2SA)?'':date('H:i',strtotime($linea->E2SA))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_6" type="text" name="salida2_6_{{$linea->empleado_id}}" id="salida2_6_{{$linea->empleado_id}}" value="{{is_null($linea->S2SA)?'':date('H:i',strtotime($linea->S2SA))}}">
                </td>

                <td class="tg-031e" id="">
                    <input class="horariosdia_0" type="text" name="entrada2_0_{{$linea->empleado_id}}" id="entrada2_0_{{$linea->empleado_id}}" value="{{is_null($linea->E2DO)?'':date('H:i',strtotime($linea->E2DO))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_0" type="text" name="salida2_0_{{$linea->empleado_id}}" id="salida2_0_{{$linea->empleado_id}}" value="{{is_null($linea->S2DO)?'':date('H:i',strtotime($linea->S2DO))}}">
                </td>
            </tr>
            @endforeach
        </table></div>
        </form>
    </div>

<div id="dialogEmpleado-form" title="">
  <form id = "Empleado-form" autofocus>
    <fieldset>
        <div class="form-group">
        <label for="predefinidos">Elegir horario base</label>
        <input type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada1" type="text" tabindex="" name="entrada1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida1" type="text" tabindex="" name="salida1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada2" type="text" tabindex="" name="entrada2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida2" type="text" tabindex="" name="salida2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
    </fieldset>
  </form>
</div> 

<div id="dialogAusencia-form" title="">
  <form autofocus>
    <fieldset>
        <div class="checkbox">
            <label><input type="checkbox" id="check_trabaja" value="VT">Este dia trabaja</label>
        </div>
        <div id="container_horarioVT">
        <div class="form-group">
        <!-- <label for="predefinidos">Elegir horario base</label> -->

        <input type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all" placeholder="Elige horario">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada1" type="text" tabindex="" name="entrada1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida1" type="text" tabindex="" name="salida1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada2" type="text" tabindex="" name="entrada2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida2" type="text" tabindex="" name="salida2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
        </div>
    </fieldset>
  </form>
</div> 

<div id="dialogHorarioDia-form" title="">
  <form autofocus>
    <fieldset>
        <div id="div_check_libre" class="checkbox">
            <label><input type="checkbox" id="check_libre" value="">Cambiar a Dia Libre</label>
        </div>
        <div id="div_check_vacaciones" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_vacaciones" value="">Cambiar a Vacaciones</label>
        </div>
        <div id="container_horarioL">
        <div class="form-group">
        <input class="predefinidos" type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all" placeholder="Elige horario">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada1" type="text" tabindex="" name="entrada1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida1" type="text" tabindex="" name="salida1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada2" type="text" tabindex="" name="entrada2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida2" type="text" tabindex="" name="salida2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]|[2][4]:[0][0]" id="" size="5" placeholder="00:00" value="">
        </div>
        </div>
    </fieldset>
  </form>
</div> 

<div id="dialogAbierto-form" title="">
  <form autofocus>
    <fieldset>
        <div class="radio">
            <label><input type="radio" name="optradio" id="optAbierto">Abierto</label>
        </div>
        <div class="radio">
            <label><input type="radio" name="optradio" id="optCerrado">Cerrado</label>
        </div>       
    </fieldset>
  </form>
</div> 

</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/cuadrante.js')}}"></script>
<script>
$(document).ready(function(){

  var predefinidos = <?php echo $predefinidos; ?>;
  // console.log(predefinidos);
  $( function() {
    $( ".predefinidos" ).autocomplete({
      minLength: 0,
      source: predefinidos,
      focus: function( event, ui ) {
        $( ".predefinidos" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        var entrada1 = ui.item.entrada1;
        var salida1 = ui.item.salida1;
        var entrada2 = ui.item.entrada2;
        var salida2 = ui.item.salida2;
        if(entrada1 != null){entrada1 = entrada1.replace(/:\d\d/,'');}
        if(salida1 != null)salida1 = salida1.replace(/:\d\d/,'');
        if(entrada2 != null)entrada2 = entrada2.replace(/:\d\d/,'');
        if(salida2 != null)salida2 = salida2.replace(/:\d\d/,'');
        $( ".predefinidos" ).val( ui.item.label );
        $( ".predefinidos-entrada1").val(entrada1 );
        $( ".predefinidos-salida1").val(salida1 );
        $( ".predefinidos-entrada2").val(entrada2 );
        $( ".predefinidos-salida2").val(salida2 );
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div>" + item.label + "</div>" )
        .appendTo( ul );
    };
  } );

// TO DO: si hay algún día cerrado o festivo, cambiar el color de la columna. Se puede hacer con css, creo que no hace falta jquery. Un caso a estudiar es cuando se pone manualmente un día como cerrado, entonces sí que haría falta jquery, a no ser que recarge la página (mejor que no recarge para asegurarse que no se graba por error)
// TO DO: click en botón Abierto o Cerrado del día de la semana, abre modal para elegir Abierto o Cerrado. Si se marca cerrado, borrará todos los horarios de ese día, pero no grabará los cambios en la base de datos.


// TO DO: (ver siguiente TO DO) si se modifica algún horario de una columna que es festivo, y se comprueba que al menos se trabaja 1 hora, grabar en base de datos FT. si ese día está cerrado, y al menos 1 hora trabajada, entonces se marca SD (se debe)
// TO DO: click en una casilla de horario, se abre modal para introducir horario (desplegable con horarios habituales, opción marcar SD (se debe))
// TO DO: click en nombre del empleado, se abre modal para introducir el horario por defecto para esa semana, el programa rellenará las casillas, excepto el dia de cierre y, en su caso, los festivos
});


</script>

@endsection