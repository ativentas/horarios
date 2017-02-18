@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Nueva Ausencia/Vacaciones</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li><a href="{{ url('ausencias/calendario') }}">Calendario</a></li>
                    <li class="active">Listado</li>
                    <li><a href="{{ url('/ausencias/create') }}">Nueva</a></li>
                </ol>
        </div>
    </div>

    <div class="panel-body">

    <div class="col-md-12">
        @if($ausencias->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Empleado</th>
                    <th>Comienzo</th>
                    <th>Fin</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($ausencias as $ausencia)
                <tr>
                    <th scope="row">{{ $i++ }}</th>
                    <td>{{ $ausencia->tipo }}</td>
                    <!-- <td><a href="{{ url('ausencias/' . $ausencia->id) }}">{{ $ausencia->tipo }}</a></td> -->
                    <td><a href="{{ url('/usuarios/modificar/' . $ausencia->empleado_id) }}">{{ $ausencia->empleado->alias }}</a></td>
                    <td>{{ date("j M Y", strtotime($ausencia->fecha_inicio)) }}</td>
                    <td>{{ date("j M Y", strtotime($ausencia->finalDay)) }}</td>
                    <td>{{ $ausencia->estado }}</td>
                    <td>
                        <a class="btn btn-primary btn-xs" href="{{ url('ausencias/' . $ausencia->id . '/edit')}}">
                            <span class="glyphicon glyphicon-edit"></span> Edit</a> 
                        @if($ausencia->estado=='Pendiente')
                        <form action="{{ route('confirmarVacaciones',$ausencia->id) }}" style="display:inline" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="accion" value="REQUERIR" />
                            <button class="btn btn-success btn-xs" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Confirmar</button>
                        </form>                     
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
            <h2>No hay ningún dato</h2>
        @endif
    </div>


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

