<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-s6z2{text-align:center}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 0px;}}
.A {background-color:palegreen}
.C {background:lightpink}
.FA {background-color: powderblue}
.FC {background-color: violet}
.ausencia {}
</style>

@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
        <div class="col-md-4 col-md-offset-3"><span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$semana}} ({{$year}}) - {{date_format($inicio_semana,'d M')}} al {{date_format($final_semana,'d M')}}</span>
        </div>
        </div>
    <!-- <h2>Detalle Horario Semana</h2> -->
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                </ol>
        </div>
<!--         <div class="row">
        <div class="col-md-4 col-md-offset-3"><span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$semana}} ({{$year}}) - {{date_format($inicio_semana,'d M')}} al {{date_format($final_semana,'d M')}}</span>
        </div>
        </div>
 -->    </div>

    <div class="panel-body">        
        <!-- TABLA -->
        <div class="tg-wrap">
        <table class="tg" style="undefined;table-layout: fixed; width: 767px">
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
                <th data-dia="1" class="tg-s6z2 {{$cuadrante->dia_1}}" colspan="2">Lunes</th>
                <th data-dia="2" class="tg-s6z2 {{$cuadrante->dia_2}}" colspan="2">Martes</th>
                <th data-dia="3" class="tg-s6z2 {{$cuadrante->dia_3}}" colspan="2">Miercoles</th>
                <th data-dia="4" class="tg-s6z2 {{$cuadrante->dia_4}}" colspan="2">Jueves</th>
                <th data-dia="5" class="tg-s6z2 {{$cuadrante->dia_5}}" colspan="2">Viernes</th>
                <th data-dia="6" class="tg-s6z2 {{$cuadrante->dia_6}}" colspan="2">Sabado</th>
                <th data-dia="0" class="tg-s6z2 {{$cuadrante->dia_0}}" colspan="2">Domingo</th>
            </tr>
            @foreach($lineas as $linea)
            <tr data-empleado_id={{$linea->empleado_id}} style="line-height:20px">
                <td data-empleado_id="{{$linea->empleado_id}}" class="tg-031e" rowspan="2">{{$linea->nombre}} 
                    <button class="btn btn-info btn-xs btn_modify" id="button_modify_{{$linea->empleado_id}}" type="submit"><span class="glyphicon glyphicon-edit"></span></button><button style="display:none" class="btn btn-success btn-xs btn_save" id="button_save_{{$linea->empleado_id}}" type="submit"><span class="glyphicon glyphicon-saved"></span></button>
                </td>
                @if(in_array($linea->situacion1,['V','B','AJ','AN','L']))
                <td data-dia="1" class="{{in_array($linea->situacion1,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion1}}</td>
                @else
                <td style="background-color:orange" data-dia="1" class="tg-031e editarhorario" id="entrada1_1_{{$linea->empleado_id}}">{{is_null($linea->ELU)?'':date('H:i',strtotime($linea->ELU))}}</td>
<!-- Imagen que ocupa las 4 celdas si se pone en la entrada1, puede servir para cuando sea VT, pero prefiero probar a poner un fondo en los td que sean VT o SD.
TO DO: se me ha ocurrido combinar tanto el color naranja como una pequeñita imagen que sea solo la letra V y que vaya en medio de los 4 horarios y así no tapa y no hay que poner opacidad<img style="margin-top:-9px;margin-left:-4px;position: absolute;opacity: 0.5;" src="{{asset('img/v.png')}}" />  -->               
                <td class="tg-031e" id="salida1_1_{{$linea->empleado_id}}">{{is_null($linea->SLU)?'':date('H:i',strtotime($linea->SLU))}}</td>
                @endif
                @if(in_array($linea->situacion2,['V','B','AJ','AN','L']))
                <td data-dia="2" class="{{in_array($linea->situacion2,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion2}}</td>
                @else
                <td data-dia="2" class="tg-031e editarhorario" id="entrada1_2_{{$linea->empleado_id}}">{{is_null($linea->EMA)?'':date('H:i',strtotime($linea->EMA))}}</td>
                <td class="tg-031e" id="salida1_2_{{$linea->empleado_id}}">{{is_null($linea->SMA)?'':date('H:i',strtotime($linea->SMA))}}</td>
                @endif
                @if(in_array($linea->situacion3,['V','B','AJ','AN','L']))
                <td data-dia="3" class="{{in_array($linea->situacion3,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion3}}</td>
                @else
                <td data-dia="3" class="tg-031e editarhorario" id="entrada1_3_{{$linea->empleado_id}}">{{is_null($linea->EMI)?'':date('H:i',strtotime($linea->EMI))}}</td>
                <td class="tg-031e" id="salida1_3_{{$linea->empleado_id}}">{{is_null($linea->SMI)?'':date('H:i',strtotime($linea->SMI))}}</td>
                @endif
                @if(in_array($linea->situacion4,['V','B','AJ','AN','L']))
                <td data-dia="4" class="{{in_array($linea->situacion4,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion4}}</td>
                @else
                <td data-dia="4" class="tg-031e editarhorario" id="entrada1_4_{{$linea->empleado_id}}">{{is_null($linea->EJU)?'':date('H:i',strtotime($linea->EJU))}}</td>
                <td class="tg-031e" id="salida1_4_{{$linea->empleado_id}}">{{is_null($linea->SJU)?'':date('H:i',strtotime($linea->SJU))}}</td>
                @endif
                @if(in_array($linea->situacion5,['V','B','AJ','AN','L']))
                <td data-dia="5" class="{{in_array($linea->situacion5,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion5}}</td>
                @else
                <td data-dia="5" class="tg-031e editarhorario" id="entrada1_5_{{$linea->empleado_id}}">{{is_null($linea->EVI)?'':date('H:i',strtotime($linea->EVI))}}</td>
                <td class="tg-031e" id="salida1_5_{{$linea->empleado_id}}">{{is_null($linea->SVI)?'':date('H:i',strtotime($linea->SVI))}}</td>
                @endif
                @if(in_array($linea->situacion6,['V','B','AJ','AN','L']))
                <td data-dia="6" class="{{in_array($linea->situacion6,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion6}}</td>
                @else
                <td data-dia="6" class="tg-031e editarhorario" id="entrada1_6_{{$linea->empleado_id}}">{{is_null($linea->ESA)?'':date('H:i',strtotime($linea->ESA))}}</td>
                <td class="tg-031e" id="salida1_6_{{$linea->empleado_id}}">{{is_null($linea->ESA)?'':date('H:i',strtotime($linea->SSA))}}</td>
                @endif
                @if(in_array($linea->situacion7,['V','B','AJ','AN','L']))
                <td data-dia="0" class="{{in_array($linea->situacion7,['V','B','AJ','AN','L']) ? 'ausencia' : ''}}" colspan="2" rowspan="2" align="center">{{$linea->situacion7}}</td>
                @else
                <td data-dia="0" class="tg-031e editarhorario" id="entrada1_0_{{$linea->empleado_id}}">{{is_null($linea->EDO)?'':date('H:i',strtotime($linea->EDO))}}</td>
                <td class="tg-031e" id="salida1_0_{{$linea->empleado_id}}">{{is_null($linea->SDO)?'':date('H:i',strtotime($linea->SDO))}}</td>
                @endif
            </tr>
            <tr style="line-height:20px">
                @if(in_array($linea->situacion1,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_1_{{$linea->empleado_id}}">{{is_null($linea->E2LU)?'':date('H:i',strtotime($linea->E2LU))}}</td>
                <td class="tg-031e" id="salida2_1_{{$linea->empleado_id}}">{{is_null($linea->S2LU)?'':date('H:i',strtotime($linea->S2LU))}}</td>
                @endif
                @if(in_array($linea->situacion2,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_2_{{$linea->empleado_id}}">{{is_null($linea->E2MA)?'':date('H:i',strtotime($linea->E2MA))}}</td>
                <td class="tg-031e" id="salida2_2_{{$linea->empleado_id}}">{{is_null($linea->S2MA)?'':date('H:i',strtotime($linea->S2MA))}}</td>
                @endif
                @if(in_array($linea->situacion3,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_3_{{$linea->empleado_id}}">{{is_null($linea->E2MI)?'':date('H:i',strtotime($linea->E2MI))}}</td>
                <td class="tg-031e" id="salida2_3_{{$linea->empleado_id}}">{{is_null($linea->S2MI)?'':date('H:i',strtotime($linea->S2MI))}}</td>
                @endif
                @if(in_array($linea->situacion4,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_4_{{$linea->empleado_id}}">{{is_null($linea->E2JU)?'':date('H:i',strtotime($linea->E2JU))}}</td>
                <td class="tg-031e" id="salida2_4_{{$linea->empleado_id}}">{{is_null($linea->S2JU)?'':date('H:i',strtotime($linea->S2JU))}}</td>
                @endif
                @if(in_array($linea->situacion5,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_5_{{$linea->empleado_id}}">{{is_null($linea->E2VI)?'':date('H:i',strtotime($linea->ESVI))}}</td>
                <td class="tg-031e" id="salida2_5_{{$linea->empleado_id}}">{{is_null($linea->S2VI)?'':date('H:i',strtotime($linea->S2VI))}}</td>
                @endif
                @if(in_array($linea->situacion6,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_6_{{$linea->empleado_id}}">{{is_null($linea->E2SA)?'':date('H:i',strtotime($linea->E2SA))}}</td>
                <td class="tg-031e" id="salida2_6_{{$linea->empleado_id}}">{{is_null($linea->S2SA)?'':date('H:i',strtotime($linea->S2SA))}}</td>
                @endif
                @if(in_array($linea->situacion7,['V','B','AJ','AN','L']))
                @else
                <td class="tg-031e" id="entrada2_0_{{$linea->empleado_id}}">{{is_null($linea->E2DO)?'':date('H:i',strtotime($linea->E2DO))}}</td>
                <td class="tg-031e" id="salida2_0_{{$linea->empleado_id}}">{{is_null($linea->S2DO)?'':date('H:i',strtotime($linea->S2DO))}}</td>
                @endif
            </tr>
            @endforeach
        </table></div>
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
            <label><input type="checkbox" id="check_trabaja" value="VT">VT</label>
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
        <div class="form-group">Checkbox para L o SD</div>
        <div class="form-group">
        <label for="predefinidos">Elegir horario base</label>
        <input class="predefinidos" type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all">
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


</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/cuadrante.js')}}"></script>
<script>
$(document).ready(function(){

  var predefinidos = <?php echo $predefinidos; ?>;
  console.log(predefinidos);
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