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


tr:nth-child(4n){
    background-color: gainsboro;
}
tr:nth-child(4n+1){
    background-color: gainsboro;
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

        <div class="col-md-10 col-md-offset-1">
            <ul class="pager">
            @if($anteriorId)
            <li><a href="{{ url('cuadrante/'.$anteriorId) }}">Ant.</a></li>
            @endif
            <span style="background-color: #800000; color: #ffffff; display: inline-block; margin:0px 5px 7px 5px ;padding: 3px 10px; font-weight: bold; border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;font-size:18px">Semana {{$cuadrante->semana}} ({{$cuadrante->year}}) - {{$cuadrante->abarca}}</span>
            @if($posteriorId)
            <li><a href="{{ url('cuadrante/'.$posteriorId) }}">Prox.</a></li>
            @endif
          </ul>
        </div>
        </div>
    <!-- <h2>Detalle Horario Semana</h2> -->
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    @if (!Auth::user()->isAdmin())
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                    @endif
                    @if ($cuadrante->archivado == '0')
                    <li><button class="btn-success btn-xs btn-guardar" id="btn_guardar" name="guardar" style="display:none;">Guardar Cambios</button></li>
                    @endif
                    <h3 style="display:inline;margin-left: 3em"><span class="label label-warning">{{$cuadrante->estado}}</span></h3>
                    <div id="div_verificar" style="float:right;margin-right: 50px;display:none;">
                    <input style="text-align:left;width:auto;margin:6px;margin-left: 60px;" type="checkbox" name="check_preparado" id="check_preparado" value="1"><span style="margin-left: 0px">Marcar para enviar</span>
                    <button class="btn-primary btn-xs btn-danger" name="solicitarverificacion" id="boton_solicitarverificacion">Click para Enviar a Oficina</button>
                    </div>
                    <div id="div_aceptar" style="float:right;margin-right: 50px;display:none;">
                    <input style="text-align:left;width:auto;margin:6px;margin-left: 60px;" type="checkbox" name="check_aceptar" id="check_aceptar" value="1"><span style="margin-left: 0px">Marcar para aceptar</span>
                    <button class="btn-primary btn-xs btn-danger" name="aceptar" id="boton_aceptar">Click para Aceptar</button>
                    </div>
                </ol>
                    <form id="form_aceptar" action="{{route('aceptarCuadrante',array('cuadrante_id'=>$cuadrante->id))}}" method="POST"> 
                    {{csrf_field()}}
                    </form>




        </div>


<!-- Imagen que ocupa las 4 celdas si se pone en la entrada1, puede servir para cuando sea VT, pero prefiero probar a poner un fondo en los td que sean VT o SD.
TO DO: se me ha ocurrido combinar tanto el color naranja como una pequeñita imagen que sea solo la letra V y que vaya en medio de los 4 horarios y así no tapa y no hay que poner opacidad -->


    </div> <!-- fin panel heading -->

    <div class="panel-body">        
        <form id="form_guardar" action="{{route('guardarCuadrante',array('cuadrante_id'=>$cuadrante->id))}}" method="POST"> 
        {{csrf_field()}}
        <!-- TABLA -->
        <div class="tg-wrap">
        <table data-ausencias="{{$lineasconausencias}}" data-cambios="{{$lineasconcambios}}" data-isadmin="{{Auth::user()->is_admin}}" data-estadocuadrante="{{$cuadrante->estado}}" class="tg" id="tabla_plantilla" style="undefined;table-layout: fixed; width: 851px">
        <colgroup>
            <col style="width: 30px">
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
                <th></th>
                <th class="tg-s6z2"></th>
                <th data-dia="1" data-estadodia="{{$cuadrante->dia_1}}" class="tg-s6z2 diasemana {{$cuadrante->dia_1}}" id="estadodia_1" colspan="2">Lunes</th>
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
<!--                 <td class="tg-031e" rowspan="2"><button class="btn btn-danger btn-sm btn_delete" id="button_delete_{{$linea->empleado_id}}" type="button" style=""><span class="glyphicon glyphicon-remove-circle"></span></button></td> -->
                <td class="tg-031e" rowspan="2">@if(Auth::user()->isAdmin())<span class="delete_empleado glyphicon glyphicon-remove-circle" id="icon_delete_{{$linea->empleado_id}}" style="color: red;cursor:pointer;"></span>@endif</td>
                <td data-empleado_id="{{$linea->empleado_id}}" class="tg-031e" rowspan="2" style="height:60px;"><span>{{$linea->nombre}}</span> 
                    <button class="btn btn-info btn-xs btn_modify" id="button_modify_{{$linea->empleado_id}}" type="button" style="display: none;"><span class="glyphicon glyphicon-edit"></span></button>
                </td>
                <td data-dia="1" class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="entrada1_1_{{$linea->empleado_id}}" id="entrada1_1_{{$linea->empleado_id}}" value="{{is_null($linea->ELU) ? '' : date('H:i',strtotime($linea->ELU))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-1">                        
                        <button class="ausencia" type="button" id="ausencia_1_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion1,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion1}}</button>
                    </div>
                    <input type="hidden" name="situacion_1_{{$linea->empleado_id}}" id="situacion_1_{{$linea->empleado_id}}" value="{{$linea->situacion1}}">
                    <input type="hidden" name="nota_1_{{$linea->empleado_id}}" id="nota_1_{{$linea->empleado_id}}" value="{{$linea->nota1}}">

                </td>             
                <td class="tg-031e" id="">
                    <input class="horariosdia_1" type="text" name="salida1_1_{{$linea->empleado_id}}" id="salida1_1_{{$linea->empleado_id}}" value="{{is_null($linea->SLU) ? '' : date('H:i',strtotime($linea->SLU))}}">
                </td>

                <td data-dia="2" class="tg-031e" id="">
                    <input class="horariosdia_2"  type="text" name="entrada1_2_{{$linea->empleado_id}}" id="entrada1_2_{{$linea->empleado_id}}" value="{{is_null($linea->EMA) ? '' : date('H:i',strtotime($linea->EMA))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-2">                        
                        <button class="ausencia" type="button" id="ausencia_2_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion2,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion2}}</button>
                    </div>
                    <input type="hidden" name="situacion_2_{{$linea->empleado_id}}" id="situacion_2_{{$linea->empleado_id}}" value="{{$linea->situacion2}}">
                    <input type="hidden" name="nota_2_{{$linea->empleado_id}}" id="nota_2_{{$linea->empleado_id}}" value="{{$linea->nota2}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_2" type="text" name="salida1_2_{{$linea->empleado_id}}" id="salida1_2_{{$linea->empleado_id}}" value="{{is_null($linea->SMA) ? '' : date('H:i',strtotime($linea->SMA))}}">                    
                </td>

                <td data-dia="3" class="tg-031e" id="">
                    <input class="horariosdia_3" class="" type="text" name="entrada1_3_{{$linea->empleado_id}}" id="entrada1_3_{{$linea->empleado_id}}" value="{{is_null($linea->EMI) ? '' : date('H:i',strtotime($linea->EMI))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-3">                        
                        <button class="ausencia" type="button" id="ausencia_3_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion3,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion3}}</button>
                    </div>
                    <input type="hidden" name="situacion_3_{{$linea->empleado_id}}" id="situacion_3_{{$linea->empleado_id}}"  value="{{$linea->situacion3}}">
                    <input type="hidden" name="nota_3_{{$linea->empleado_id}}" id="nota_3_{{$linea->empleado_id}}" value="{{$linea->nota3}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_3" type="text" name="salida1_3_{{$linea->empleado_id}}" id="salida1_3_{{$linea->empleado_id}}" value="{{is_null($linea->SMI) ? '' : date('H:i',strtotime($linea->SMI))}}">                
                </td>

                <td data-dia="4" class="tg-031e" id="">
                    <input class="horariosdia_4"  type="text" name="entrada1_4_{{$linea->empleado_id}}" id="entrada1_4_{{$linea->empleado_id}}" value="{{is_null($linea->EJU) ? '' : date('H:i',strtotime($linea->EJU))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-4">                        
                        <button class="ausencia" type="button" id="ausencia_4_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion4,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion4}}</button>
                    </div>
                    <input type="hidden" name="situacion_4_{{$linea->empleado_id}}" id="situacion_4_{{$linea->empleado_id}}"  value="{{$linea->situacion4}}">
                    <input type="hidden" name="nota_4_{{$linea->empleado_id}}" id="nota_4_{{$linea->empleado_id}}" value="{{$linea->nota4}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_4" type="text" name="salida1_4_{{$linea->empleado_id}}" id="salida1_4_{{$linea->empleado_id}}" value="{{is_null($linea->SJU) ? '' : date('H:i',strtotime($linea->SJU))}}">                    
                </td>

                <td data-dia="5" class="tg-031e" id="">
                    <input class="horariosdia_5"  type="text" name="entrada1_5_{{$linea->empleado_id}}" id="entrada1_5_{{$linea->empleado_id}}" value="{{is_null($linea->EVI) ? '' : date('H:i',strtotime($linea->EVI))}}">
                    <div class="wrapper"  id="wrapper-{{$linea->empleado_id}}-5">                        
                        <button class="ausencia" type="button" id="ausencia_5_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion5,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion5}}</button>
                    </div>
                    <input type="hidden" name="situacion_5_{{$linea->empleado_id}}" id="situacion_5_{{$linea->empleado_id}}" value="{{$linea->situacion5}}">
                    <input type="hidden" name="nota_5_{{$linea->empleado_id}}" id="nota_5_{{$linea->empleado_id}}" value="{{$linea->nota5}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_5" type="text" name="salida1_5_{{$linea->empleado_id}}" id="salida1_5_{{$linea->empleado_id}}" value="{{is_null($linea->SVI) ? '' : date('H:i',strtotime($linea->SVI))}}">                    
                </td>

                <td data-dia="6" class="tg-031e" id="">
                    <input class="horariosdia_6"  type="text" name="entrada1_6_{{$linea->empleado_id}}" id="entrada1_6_{{$linea->empleado_id}}" value="{{is_null($linea->ESA) ? '' : date('H:i',strtotime($linea->ESA))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-6">                        
                        <button class="ausencia" type="button" id="ausencia_6_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion6,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion6}}</button>
                    </div>
                    <input type="hidden" name="situacion_6_{{$linea->empleado_id}}" id="situacion_6_{{$linea->empleado_id}}" value="{{$linea->situacion6}}">
                    <input type="hidden" name="nota_6_{{$linea->empleado_id}}" id="nota_6_{{$linea->empleado_id}}" value="{{$linea->nota6}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_6" type="text" name="salida1_6_{{$linea->empleado_id}}" id="salida1_6_{{$linea->empleado_id}}" value="{{is_null($linea->SSA) ? '' : date('H:i',strtotime($linea->SSA))}}">                    
                </td>

                <td data-dia="0" class="tg-031e" id="">
                    <input class="horariosdia_0"  type="text" name="entrada1_0_{{$linea->empleado_id}}" id="entrada1_0_{{$linea->empleado_id}}" value="{{is_null($linea->EDO) ? '' : date('H:i',strtotime($linea->EDO))}}">
                    <div class="wrapper" id="wrapper-{{$linea->empleado_id}}-0">                        
                        <button class="ausencia" type="button" id="ausencia_0_{{$linea->empleado_id}}" style="{{!in_array($linea->situacion0,['V','B','AJ','AN','L','BP','F','PR'])? 'display:none;':''}}">{{$linea->situacion0}}</button>
                    </div>
                    <input type="hidden" name="situacion_0_{{$linea->empleado_id}}" id="situacion_0_{{$linea->empleado_id}}" value="{{$linea->situacion0}}">
                    <input type="hidden" name="nota_0_{{$linea->empleado_id}}" id="nota_0_{{$linea->empleado_id}}" value="{{$linea->nota0}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_0" type="text" name="salida1_0_{{$linea->empleado_id}}" id="salida1_0_{{$linea->empleado_id}}" value="{{is_null($linea->SDO) ? '' : date('H:i',strtotime($linea->SDO))}}">                    
                </td>

            </tr>

            <tr style="">
            <?php   $dias = array('DO','LU','MA','MI','JU','VI','SA');
                    $numericos = array('1','2','3','4','5','6','0');?>
            @foreach(array_combine($dias, $numericos) as $dia=>$num)
                <td class="tg-031e" id="">
                    <input class="horariosdia_{{$num}}" type="text" name="entrada2_{{$num}}_{{$linea->empleado_id}}" id="entrada2_{{$num}}_{{$linea->empleado_id}}" value="{{is_null(object_get($linea,"E2{$dia}"))?'':date('H:i',strtotime(object_get($linea,"E2{$dia}")))}}">
                </td>
                <td class="tg-031e" id="">
                    <input class="horariosdia_{{$num}}" type="text" name="salida2_{{$num}}_{{$linea->empleado_id}}" id="salida2_{{$num}}_{{$linea->empleado_id}}" value="{{is_null(object_get($linea,"S2{$dia}"))?'':date('H:i',strtotime(object_get($linea,"S2{$dia}")))}}">
                </td>
            @endforeach
                
            </tr>
            @endforeach





            @if(count($empleadosdisponibles))
            <tr>
                <td></td>
                <td> 
                <!-- <div class="form-group"> -->
                  <select class="form-control" id="select_añadir">
                    <option value="" >Añadir</option>
                    @foreach ($empleadosdisponibles as $empleado)
                    <option value="{{$empleado->id}}">{{$empleado->alias}}</option>
                    @endforeach
                  </select>

                <!-- </div> -->
                </td>
                <td colspan="2"><button type="button" class="btn-success btn-xs btn-añadir" id="btn_añadir_empleado" name="añadir_empleado" style="">Añadir</button></td>
            </tr>
            @endif
        </table></div>
        
        <input type="hidden" name="cambio_estado" id="cambio_estado" val="">
        <input type="hidden" name="cambio_apertura_dia" id="cambio_apertura_dia" val="">

        </form>
   


<div class="col-md-8 col-md-offset-2">

    <div class="panel-body">
      <form method="post" id="form_enviar_comment" action="/comment/add">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="on_cuadrante" value="{{ $cuadrante->id }}">
        <input type="hidden" name="cuadrante_id" value="{{ $cuadrante->id }}">
        <div class="form-group">
          <textarea required="required" placeholder="Escribe una nota aquí..." name = "body" class="form-control"></textarea>
        </div>
        <input type="submit" name='post_comment' class="btn btn-success" value = "Enviar"/>
      </form>
    </div>

  <div>
    @if($comments)
    <ul style="list-style: none; padding: 0">
      @foreach($comments as $comment)
        <li class="panel-body">
          <div class="list-group">
            <div class="list-group-item">
              <h3>{{ $comment->author->name }}</h3>
              <p>{{ $comment->created_at->format('d-M-Y h:i a') }}</p>
            </div>
            <div class="list-group-item">
              <p>{{ $comment->body }}</p>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
    @endif
  </div>
</div>


    </div> <!-- FIN PANEL BODY -->




<form id="form_añadir_empleado" action="{{route('añadirEmpleado',array('empleado'=>':EMPLEADO_ID','cuadrante'=>$cuadrante->id))}}" method="POST"> 
{{csrf_field()}}
</form>
<form id="form_delete_empleado" action="{{route('deleteEmpleado',array('empleado'=>':EMPLEADO_ID','cuadrante'=>$cuadrante->id))}}" method="POST"> 
{{csrf_field()}}
</form>




<div id="dialogEmpleado-form" title="">
  <form id = "Empleado-form" autofocus>
    <fieldset>
        <div class="form-group">
        <label for="predefinidos">Elegir horario base</label>
<!--         <input type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all"> -->        
        <select class="predefinidos form-control" name="predefinidos" id="">
            <option value=""></option>
            @foreach ($predefinidos as $key => $predefinido)
            <option data-key="{{$key}}" class="" value="">{{$predefinido->label}}</option>
            @endforeach                
        </select>
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada1" type="text" tabindex="" name="entrada1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida1" type="text" tabindex="" name="salida1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada2" type="text" tabindex="" name="entrada2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida2" type="text" tabindex="" name="salida2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        </div>
    </fieldset>
  </form>
</div> 

<div id="dialogAusencia-form" title="">
  <form autofocus>
    <fieldset>
        <div id="div_check_trabaja" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_trabaja" value="VT">Este dia trabaja</label>
        </div>
        <div id="container_horarioVT">
        
        <div class="form-group">
<!--         <input type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all" placeholder="Elige horario"> -->
        <select class="predefinidos form-control" name="predefinidos" id="">
            <option value=""></option>
            @foreach ($predefinidos as $key => $predefinido)
            <option data-key="{{$key}}" class="" value="">{{$predefinido->label}}</option>
            @endforeach                
        </select>
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
        <div id="div_notaAusencia" style="display:none;"">
          <textarea class="form-control" required="" placeholder="Escribe una nota aquí..." name = "nota" id=""></textarea>        
        </div>
    </fieldset>
  </form>
</div> 

<div id="dialogHorarioDia-form" title="">
  <form>
    <fieldset>
        <div id="div_check_libre" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_libre" value="">Cambiar a Dia Libre</label>
        </div>
        <div id="div_check_compensar" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_compensar" value="">Compensar con día Pdte</label>
        </div>
        <div id="div_select_dia_compensar" style="display:none;">
            <select class="form-control" name="" id="">
                <option value="">Día que se compensa</option>
                @foreach ($empleados_compensar as $key => $compensable)
                <option data-key="{{$key}}" class="" value="">{{$compensable->fecha}}</option>
                @endforeach                
            </select>            
        </div>
        <div id="div_check_vacaciones" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_vacaciones" value="">Cambiar a Vacaciones</label>
        </div>
        <div id="div_check_festivo" class="checkbox" style="display:none;">
            <label><input type="checkbox" id="check_festivo" value="">Cambiar a Festivo</label>
        </div>
        <div id="container_horarioL">
        <!-- <div class="form-group">
        <input class="predefinidos" type="text" name="predefinidos" id="" value="" class="predefinidos text ui-widget-content ui-corner-all" placeholder="Elige horario">
        </div> -->
        
        <div class="form-group">
        <select autofocus class="predefinidos form-control" name="predefinidos" id="">
            <option value=""></option>
            @foreach ($predefinidos as $key => $predefinido)
            <option data-key="{{$key}}" class="" value="">{{$predefinido->label}}</option>
            @endforeach                
        </select>
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada1" type="text" tabindex="" name="entrada1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida1" type="text" tabindex="" name="salida1" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        </div>
        <div class="form-group">
        <input class="predefinidos-entrada2" type="text" tabindex="" name="entrada2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        <input class="predefinidos-salida2" type="text" tabindex="" name="salida2" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" id="" size="5" placeholder="00:00" value="">
        </div>
        </div>
        <div id="div_notaWrapper" style="display:none;"">
          <textarea class="form-control" required="" placeholder="Escribe una nota aquí..." name = "nota" id=""></textarea>        
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

  var empleados_compensar = <?php echo $empleados_compensar; ?>;

  console.log(empleados_compensar);


  var predefinidos = <?php echo $predefinidos; ?>;
  

  $('.predefinidos').on('change', function(e){
    var indice = $( "option:selected", this ).data('key');
    var entrada1 = predefinidos[indice].entrada1;
    var salida1 = predefinidos[indice].salida1;
    var entrada2 = predefinidos[indice].entrada2;
    var salida2 = predefinidos[indice].salida2;
    if(entrada1 != null){entrada1 = entrada1.replace(/(:\d{2})$/, "");}    
    if(salida1 != null){salida1 = salida1.replace(/(:\d{2})$/, "");}    
    if(entrada2 != null){entrada2 = entrada2.replace(/(:\d{2})$/, "");}    
    if(salida2 != null){salida2 = salida2.replace(/(:\d{2})$/, "");}    
    $( ".predefinidos-entrada1").val(entrada1);
    $( ".predefinidos-salida1").val(salida1);
    $( ".predefinidos-entrada2").val(entrada2);
    $( ".predefinidos-salida2").val(salida2);
  });




// TO DO: si hay algún día cerrado o festivo, cambiar el color de la columna. Se puede hacer con css, creo que no hace falta jquery. Un caso a estudiar es cuando se pone manualmente un día como cerrado, entonces sí que haría falta jquery, a no ser que recarge la página (mejor que no recarge para asegurarse que no se graba por error)
// TO DO: click en botón Abierto o Cerrado del día de la semana, abre modal para elegir Abierto o Cerrado. Si se marca cerrado, borrará todos los horarios de ese día, pero no grabará los cambios en la base de datos.


// TO DO: (ver siguiente TO DO) si se modifica algún horario de una columna que es festivo, y se comprueba que al menos se trabaja 1 hora, grabar en base de datos FT. si ese día está cerrado, y al menos 1 hora trabajada, entonces se marca SD (se debe)
// TO DO: click en una casilla de horario, se abre modal para introducir horario (desplegable con horarios habituales, opción marcar SD (se debe))
// TO DO: click en nombre del empleado, se abre modal para introducir el horario por defecto para esa semana, el programa rellenará las casillas, excepto el dia de cierre y, en su caso, los festivos
});


</script>

@endsection