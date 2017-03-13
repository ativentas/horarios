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
        <div class="panel-heading"><h4>Horarios Pendientes de Aceptar</h4></div>
        @else
        <div class="panel-heading"><h4>Ultimos horarios</h4></div>
        @endif
        <!-- <table class="table table-striped"> -->
        <table class="table">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($cuadrantes as $cuadrante)
                <tr class="active">
                    <th>{{$cuadrante->semana}}</th>
                    <th>{{$cuadrante->abarca}}</th>
                    <td>{{$cuadrante->centro->nombre}}</td>
                    <td>{{$cuadrante->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $cuadrante->id) }}">Ver</a></td>

                </tr>
                @if($cuadrante->has('comments'))
                @foreach($cuadrante->comments as $comment)
                @if (!$comment->isSolved())
                <tr>
                    
                    <th colspan="5">{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}}<button type="button" class="btn btn-warning btn-sm" style="float: right;">
                    <span class="glyphicon glyphicon-pencil"> Respuesta</span></button>
                    
                    <br>
                    <span>{{$comment->body}}</span>
                    </th>
                </tr>
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
        <div class="panel-heading"><h4>Horarios Pendientes de Archivar</h4></div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($completados as $completado)
                <tr>
                    <th>{{$completado->semana}}</th>
                    <td>{{$completado->abarca}}</td>
                    <td>{{$completado->centro->nombre}}</td>
                    <td>{{$completado->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $completado->id) }}">Ver</a></td>

                </tr>
                @if($cuadrante->has('comments'))
                @foreach($cuadrante->comments as $comment)
                @if (!$comment->isSolved())
                <tr>
                    <th colspan="5">{{$comment->author->name}}: {{$comment->created_at->format('d-M-Y, h:i a')}}<button type="button" class="btn btn-warning btn-sm" style="float: right;">
                    <span class="glyphicon glyphicon-pencil"></span></button></th>
                </tr>
                <tr>
                    <th colspan="4">{{$comment->body}}</th>

                </tr>
                @endif
                @endforeach
                @endif

            @endforeach
            </tbody>
        </table>
        </div>
        @endif


        <div class="panel panel-default">
            <div class="panel-heading"><h4>Otras notas sin resolver</h4></div>
            <div class="list-group">
                @if(!empty($otras_notaspdtes[0]))
                @foreach($otras_notaspdtes as $nota)
                    <div class="list-group-item">
                        <p>{{ $nota->body }}</p>
                        <p></p>
                        <p>{{$nota->author->name}} - Horario Semana: <a href="{{ url('cuadrante/'.$nota->cuadrante->id) }}" >{{ $nota->cuadrante->semana}}</a> *{{ $nota->created_at->format('d-M-Y, h:i a') }}</p>
                    </div>
                @endforeach
                @else
                <div class="list-group-item">
                    <p>No hay ninguna nota mas pendiente</p>
                </div>
                @endif
            </div>
        </div>




    </div> <!-- FIN DIV IZQUIERDA -->

    <div class="col-md-6"> <!-- DIV COLUMNA DERECHA -->
        
        
        @if($ausencias->count() > 0)
        <div class="panel panel-default">
        <div class="panel-heading"><h4>Ausencias Pendientes de Confirmar</h4></div>
        <table class="table table-striped">
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
                <tr>
                    <th>{{$ausencia->tipo}}</th>
                    <td>{{$ausencia->alias}}</td>
                    <td>{{$ausencia->abarca}}</td>
                    <td>
                        @if($ausencia->owner == Auth::user()->id||Auth::user()->isAdmin())
                        <a class="btn btn-primary btn-xs" href="{{ url('ausencias/' . $ausencia->id . '/edit')}}"><span class="glyphicon glyphicon-edit"></span> Edit</a> 
                        @endif
                        @if(Auth::user()->isAdmin())
                        <form action="{{ route('confirmarVacaciones',$ausencia->id) }}" style="display:inline" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="accion" value="REQUERIR" />
                            <button class="btn btn-success btn-xs" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Confirmar</button>
                        </form>
                        @endif                     
                        @if($ausencia->owner == Auth::user()->id||Auth::user()->isAdmin())
                        <form action="{{ url('ausencias/' . $ausencia->id) }}" style="display:inline" method="POST">
                            <input type="hidden" name="_method" value="DELETE" />
                            {{ csrf_field() }}
                            <button class="btn btn-danger btn-xs" type="submit"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                        </form>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif


        <div class="panel panel-default">
            <div class="panel-heading"><h4>Notas sin resolver de Ausencias</h4></div>
            <div class="list-group">
                @if(!empty($notasA_pdtes[0]))
                @foreach($notasA_pdtes as $nota)
                    <div class="list-group-item">
                        <p>{{ $nota->body }}</p>
                        <p></p>
                        <p>{{$nota->author->name}} - Ausencia: <a href="{{ url('ausencias/'.$nota->ausencia->id).'/edit' }}" >{{ $nota->ausencia->id}}</a> *{{ $nota->created_at->format('d-M-Y, h:i a') }}</p>
                    </div>
                @endforeach
                @else
                <div class="list-group-item">
                    <p>No hay ninguna nota pendiente</p>
                </div>
                @endif
            </div>
        </div>




    </div> <!-- FIN DIV DERECHA -->

                


    </div> <!-- FIN PANEL BODY -->

</div>
</div>
</div>
</div>
@endsection
