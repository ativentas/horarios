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

    <div class="col-md-6">
        @if($cuadrantes->count() > 0)
        @if(Auth::user()->isAdmin())
        Horarios Pendientes de Aceptar
        @else
        Ultimos horarios
        @endif
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
            @foreach($cuadrantes as $cuadrante)
                <tr>
                    <th>{{$cuadrante->semana}}</th>
                    <th>{{$cuadrante->abarca}}</th>
                    <td>{{$cuadrante->centro->nombre}}</td>
                    <td>{{$cuadrante->estado}}</td>
                    <td><a href="{{ url('/cuadrante/' . $cuadrante->id) }}">Ver</a></td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @else
        @endif

        @if($completados->count() > 0)
        Horarios Pendientes de Archivar
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
            @endforeach
            </tbody>
        </table>
        @else
            <h2></h2>
        @endif
    </div>

    <div class="col-md-6">
        @if($ausencias->count() > 0)
        Ausencias Pendientes de Confirmar
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
        @else
            <h2></h2>
        @endif
    </div>

                


    </div>

</div>
</div>
</div>
</div>
@endsection
