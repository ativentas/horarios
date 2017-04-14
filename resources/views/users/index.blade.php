@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Listado Usuarios</h2>
        @include('layouts.alerts')
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('home') }}">Salir</a></li>
                <li class="active">Listado</li>
            </ol>
        </div>
    </div>
    <div class="panel-body">
    <div class="col-md-12">
        @if($users->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Centro</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($users as $user)
                <tr data-id="{{$user->id}}">
                    <th scope="row">{{ $i++ }}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->centro->nombre or 'Nada'}}</td>
                    <td> 
                    <button data-activa="1" class="btn btn-success btn-xs btn-activar" type="button" value={{$user->id}} name="botonActivar" id="activar{{$user->id}}" @if($user->activo==1) style="display:none;" @endif></span>Reactivar
                    </button>
                    <button data-activa="0" class="btn btn-danger btn-xs btn-baja" type="button" value={{$user->id}} name="botonBaja" id="baja{{$user->id}}" @if($user->activo == 0) style="display:none;" @endif></span>Dar de baja
                    </button>                   
                    </td>
                    <td>
                    <a class="btn btn-info btn-xs btn-modificar" href="{{ route('users.edit',$user->id) }}">Modificar</a>
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

