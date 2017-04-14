@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Listado Departamentos</h2>
        @include('layouts.alerts')
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('home') }}">Salir</a></li>
                <li class="active">Listado</li>
                <li><a href="{{ url('/centros/create') }}">Nuevo</a></li>           
            </ol>
        </div>
    </div>
    <div class="panel-body">
    <div class="col-md-12">
        @if($centros->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Cierra</th>
                    <th>Festivos</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($centros as $centro)
                <tr data-id="{{$centro->id}}">
                    <th scope="row">{{ $i++ }}</th>
                    <td>{{$centro->nombre}}</td>
                    <td>{{$centro->empresa}}</td>
                    <td>{{$centro->dia}}</td>
                    <td>{{$centro->abrefestivos}}</td>
                    <td>Predefinidos</td>
                    <td> 
                    <button data-activa="1" class="btn btn-success btn-xs btn-activar" type="button" value={{$centro->id}} name="botonActivar" id="activar{{$centro->id}}" @if($centro->activo==1) style="display:none;" @endif></span>Reactivar
                    </button>
                    <button data-activa="0" class="btn btn-danger btn-xs btn-baja" type="button" value={{$centro->id}} name="botonBaja" id="baja{{$centro->id}}" @if($centro->activo == 0) style="display:none;" @endif></span>Dar de baja
                    </button>                   
                    </td>
                    <td>
                    <a class="btn btn-info btn-xs btn-modificar" href="{{ route('centros.edit',$centro->id) }}">Modificar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2>No hay ningún dato</h2>
        @endif

        <form id="form_modificarEstadoUser" action="{{route('users.update',[':USER_ID'])}}" method="POST">
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
        var form = $('#form_modificarEstadoUser');
        var url = form.attr('action').replace(':USER_ID', id);
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
        var form = $('#form_modificarEstadoUser');
        var url = form.attr('action').replace(':USER_ID', id);
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

