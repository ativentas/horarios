@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<!-- <div class="col-md-10 col-md-offset-1"> -->
<div class="col-md-12">
<div class="panel panel-default">

    <div class="panel-heading"><h2>Programa Horarios</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li class="active">Inicio</li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-6"> <!-- DIV IZQUIERDA -->
        
        @if($cuadrantes->count() > 0)
                <div class="panel panel-default">

        @if(Auth::user()->isAdmin())
        <div class="panel-heading" style="background-color: beige"><h4>Horarios Pendientes de Aceptar</h4></div>
        @else
        <div class="panel-heading" style="background-color: beige"><h4>Ultimos horarios</h4></div>
        @endif
        <table class="table">
            <!-- <thead> -->
                <tr>
                    <th>Sem.</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            <!-- </thead> -->
            <tbody>
            @foreach($cuadrantes as $cuadrante)
                <tr class="active">
                    <td>{{$cuadrante->semana}}</td>
                    <td>{{$cuadrante->abarca}}</td>
                    <td>{{$cuadrante->centro->nombre}}</td>
                    <td>{{$cuadrante->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $cuadrante->id) }}">Ver</a></td>

                </tr>
                @if($cuadrante->has('comments'))
                @foreach($cuadrante->comments as $comment)
                @if (!$comment->isSolved())

                </tr>
                <tr data-nota_id="{{$comment->id}}">
                    <td colspan="5"><em>{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}}: </em><em style="color:red"> {{$comment->body}}</em>
                    @if(Auth::user()->isAdmin())<button type="button" class="btn_respuesta btn btn-warning btn-xs" style="display:inline;"><span class="glyphicon glyphicon-share-alt"></span></button>@endif
                    </td>

                </tr>
                @if ($comment->nota_respuesta&&(Auth::user()->isAdmin()||$comment->visible==true))
                
                <tr data-hayrespuesta="yes" data-visible="{{$comment->visible}}">
                    <td colspan="5"><em>{{$comment->resolvedor->name}}: {{$comment->updated_at->format('d-M-Y, h:i a')}}: </em><em class="nota_respuesta" style="color:blue">{{$comment->nota_respuesta}}</em></td>
                </tr>
                @else
                <tr data-hayrespuesta="no"></tr>
                @endif                
                @endif
                @endforeach
                @endif

            @endforeach
            </tbody>
        </table>
        </div>
        @endif


        @if($completados->count() > 0)
        <div class="panel panel-default">
        <div class="panel-heading" style="background-color: beige"><h4>Horarios Pendientes de Archivar</h4></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Sem.</th>
                    <th>Periodo</th>
                    <th>Centro</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($completados as $completado)
                <tr class="active">
                    <td>{{$completado->semana}}</td>
                    <td>{{$completado->abarca}}</td>
                    <td>{{$completado->centro->nombre}}</td>
                    <td>{{$completado->estado}}</td>
                    <td>
                        <a href="{{ url('/cuadrante/' . $completado->id) }}" style=""><button type="button" class="btn btn-info btn-xs" style="">Ver</button></a>
                        <form class="" action="{{ route('archivarCuadrante', $completado->id) }}" style="display:inline;" method="POST">
                            {{ csrf_field() }}
                            <button class="btn btn-success btn-xs" type="submit" style="">Archivar</button>
                        </form>                    
                    </td>

                </tr>
                @if($completado->has('comments'))
                @foreach($completado->comments as $comment)
                @if (!$comment->isSolved())
                <tr data-nota_id="{{$comment->id}}">
                    <td colspan="5"><em>{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}}: </em><em style="color:red"> {{$comment->body}}</em>
                    @if(Auth::user()->isAdmin())<button type="button" class="btn_respuesta btn btn-warning btn-xs" style=""><span class="glyphicon glyphicon-share-alt"></span></button>@endif
                    </td>
                </tr>
                @if ($comment->nota_respuesta&&(Auth::user()->isAdmin()||$comment->visible==true))
                
                <tr data-hayrespuesta="yes" data-visible="{{$comment->visible}}">
                    <td colspan="5"><em>{{$comment->resolvedor->name}}: {{$comment->updated_at->format('d-M-Y, h:i a')}}: </em><em class="nota_respuesta" style="color:blue">{{$comment->nota_respuesta}}</em></td>
                </tr>
                @else
                <tr data-hayrespuesta="no"></tr>
                @endif
                @endif
                @endforeach
                @endif

            @endforeach
            </tbody>
        </table>
        </div>
        @endif


        <div class="panel panel-default">
            <div class="panel-heading" style="background-color: beige"><h4>Otras notas sin resolver</h4></div>
            <table class="table">
                @if(!empty($otras_notaspdtes[0]))
                @foreach($otras_notaspdtes as $nota)
                @if (!$comment->isSolved())
                    <tr data-nota_id="{{$comment->id}}">
                        <td colspan="5"><em>{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}} - ({{$comment->cuadrante->centro->nombre}}, Semana: <a href="{{ url('cuadrante/'.$nota->cuadrante->id) }}" >{{ $nota->cuadrante->semana}}):</a></em><em style="color:red"> {{$comment->body}}</em>
                        <button type="button" class="btn_respuesta btn btn-warning btn-xs" style=""><span class="glyphicon glyphicon-share-alt"></span></button>
                        </td>
                    </tr>
                    @if ($comment->nota_respuesta&&(Auth::user()->isAdmin()||$comment->visible==true))
                    
                    <tr data-hayrespuesta="yes" data-visible="{{$comment->visible}}">
                        <td colspan="5"><em>{{$comment->resolvedor->name}}: {{$comment->updated_at->format('d-M-Y, h:i a')}}: </em><em class="nota_respuesta" style="color:blue">{{$comment->nota_respuesta}}</em></td>
                    </tr>
                    @else
                    <tr data-hayrespuesta="no"></tr>
                    @endif
                    @endif
                @endforeach
                @else
                <div class="list-group-item">
                    <p>No hay ninguna nota mas pendiente</p>
                </div>
                @endif
            </table>
        </div>

    </div> <!-- FIN DIV IZQUIERDA -->

    <div class="col-md-6"> <!-- DIV COLUMNA DERECHA -->
        
        
        @if($ausencias->count() > 0)
        <div class="panel panel-default">
        <div class="panel-heading" style="background-color: beige"><h4>Ausencias Pendientes de Confirmar</h4></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Empleado</th>
                    <th>Periodo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($ausencias as $ausencia)
                <tr class="active">
                    <td>{{$ausencia->tipo}}</td>
                    <td>{{$ausencia->alias}}</td>
                    <td>{{$ausencia->abarca}}</td>
                    <td>
                        @if($ausencia->owner == Auth::user()->id||Auth::user()->isAdmin())
                        <button class="btn btn-primary btn-xs" href="{{ url('ausencias/' . $ausencia->id . '/edit')}}"><span class="glyphicon glyphicon-edit"></span> Edit</button> 
                        @endif
                        @if(Auth::user()->isAdmin())
                        <form action="{{ route('confirmarVacaciones',$ausencia->id) }}" style="display:inline;" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="accion" value="REQUERIR" />
                            <button class="btn btn-success btn-xs" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Confir.</button>
                        </form>
                        @endif                     
                        @if($ausencia->owner == Auth::user()->id||Auth::user()->isAdmin())
                        <form action="{{ url('ausencias/' . $ausencia->id) }}" style="display:inline;" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE" />
                            <button class="btn btn-danger btn-xs" type="submit"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                        </form>
                        @endif
                    </td>

                </tr>
                @if($ausencia->has('comments'))
                @foreach($ausencia->comments as $comment)
                @if (!$comment->isSolved())
                
                <tr data-nota_id="{{$comment->id}}">
                    <td colspan="5"><em>{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}}: </em><em style="color:red"> {{$comment->body}}</em>
                    @if(Auth::user()->isAdmin())<button type="button" class="btn_respuesta btn btn-warning btn-xs" style=""><span class="glyphicon glyphicon-share-alt"></span></button>@endif
                    </td>
                </tr>
                @if ($comment->nota_respuesta&&(Auth::user()->isAdmin()||$comment->visible==true))
                
                <tr data-hayrespuesta="yes" data-visible="{{$comment->visible}}">
                    <td colspan="5"><em>{{$comment->resolvedor->name}}: {{$comment->updated_at->format('d-M-Y, h:i a')}}: </em><em class="nota_respuesta" style="color:blue">{{$comment->nota_respuesta}}</em></td>
                </tr>
                @else
                <tr data-hayrespuesta="no"></tr>
                @endif


                @endif
                @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
        </div>
        @endif


    </div> <!-- FIN DIV DERECHA -->
             


    </div> <!-- FIN PANEL BODY -->

<div id="dialogRespuestaNota" title="">
    <form id = "respuesta_form" autofocus  action="{{url('comment/addrespuesta/:NOTA_ID')}}" method="POST"> 
    {{csrf_field()}}
    <fieldset>
    <div id="div_check_resuelto" class="checkbox" style="">
        <label><input type="checkbox" id="check_resuelto" name="resuelto" value="1">Marcar como Resuelta</label>
    </div>
    <div id="div_check_visible" class="checkbox" style="">
        <label><input type="checkbox" id="check_visible"  name="visible" value="1">Enviar respuesta al Encargado</label>
    </div>
    <div class="form-group">
      <textarea required="required" placeholder="Escribe aquí..." id="respuesta" name="respuesta" class="form-control"></textarea>
    </div>
    </fieldset>
    </form>
</div>

</div>
</div>
</div>
</div>
<script type="text/javascript" src="{{asset('js/home.js')}}"></script>

<script>
$(document).ready(function(){


});


</script>



@endsection
