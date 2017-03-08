@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Listado Empleados</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                    <li><a href="{{ url('/empleados/create') }}">Nuevo</a></li>
                </ol>
        </div>
    

        <form method="get" action="{{url('empleados')}}" class="form-inline">
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



    </div>

    <div class="panel-body">

    <div class="col-md-10 col-md-offset-1">
        @if($empleados->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Centro Trabajo</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($empleados as $empleado)
                <tr data-id="{{$empleado->id}}">
                    <th scope="row">{{ $i++ }}</th>
                    <td><a href="{{ url('/empleados/' . $empleado->id) }}">{{ $empleado->alias }}</a></td>
                    <td>{{$empleado->centro->nombre}}</td>
                    <td> 
                    <button data-activa="1" class="btn btn-success btn-xs btn-activar" type="button" value={{$empleado->id}} name="botonActivarMaquina" id="activar{{$empleado->id}}" @if($empleado->activo==1) style="display:none;" @endif></span>Reactivar
                    </button>
                    <button data-activa="0" class="btn btn-danger btn-xs btn-baja" type="button" value={{$empleado->id}} name="botonBajaMaquina" id="baja{{$empleado->id}}" @if($empleado->activo == 0) style="display:none;" @endif></span>Dar de baja
                    </button>                   
                    </td>
                    <td>
                    <a class="btn btn-info btn-xs btn-modificar" href="{{ route('empleados.edit',$empleado->id) }}">Modificar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2>No hay ningún dato</h2>
        @endif

        <form id="form_modificarEstadoEmpleado" action="{{route('empleados.update',[':EMPLEADO_ID'])}}" method="POST">
        <input id="estado" name="estado" type="text" value="" class="hidden">
        {{csrf_field()}}
        {{ method_field('PUT') }}        
        </form>


    </div>


    </div>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function() {

    $('.btn-activar').click(function(e){
        e.preventDefault();

        var fila = $(this).parents('tr');
        var id = fila.data('id');
        var form = $('#form_modificarEstadoEmpleado');
        var url = form.attr('action').replace(':EMPLEADO_ID', id);
        $('#estado').val(1);

        var data = form.serialize();
        $.post(url, data, function(){
        });
        $(this).hide();
        $(this).next().show(); 
    });

    $('.btn-baja').click(function(e){

        e.preventDefault();
        var fila = $(this).parents('tr');
        var id = fila.data('id');
        var form = $('#form_modificarEstadoEmpleado');
        var url = form.attr('action').replace(':EMPLEADO_ID', id);
        $('#estado').val(0);

        var data = form.serialize();
        $.post(url, data, function(){
        });

        $(this).hide();
        $(this).prev().show(); 
    });

});    

</script>

@endsection

