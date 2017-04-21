@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">

    <div class="panel-heading"><h2>Listado Horarios</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
                    <li><a href="{{ url('home') }}">Salir</a></li>
                    <li class="active">Listado</li>
                    @if (!Auth::user()->isAdmin())               
                    <li><a href="{{ url('nieuwcuadrante') }}">Nuevo Horario</a></li>
                    @endif
                    
                </ol>
        </div>
        @if(Auth::user()->isAdmin())
        <form method="get" action="{{url('cuadrantes')}}" class="form-inline">
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
        @endif

    </div>

    <div class="panel-body">

    <div class="col-md-12">
        @if($cuadrantes->count() > 0)
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
            <h2></h2>
        @endif
    </div>

    <div class="col-md-12">
        @if($completados->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Periodo</th>
                    <th>Centro Trabajo</th>
                    <th>Estado</th>
                    <th>Desarch.</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($completados as $completado)
                <tr data-id={{$completado->id}}>
                    <th>{{$completado->semana}}</th>
                    <td>{{$completado->abarca}}</td>
                    <td>{{$completado->centro->nombre}}</td>
                    <td>{{$completado->estado}}</td>
                    <td>
                    @if($completado->estado == 'Archivado')
                    <button type="button" class="btn_desarchivar btn btn-warning btn-xs" id="btn_desarch_{{$completado->id}}"style=""><span class="glyphicon glyphicon glyphicon-open"></span></button>
                    @endif
                    </td>
                    <td><a href="{{ url('/cuadrante/' . $completado->id) }}">Ver</a></td>

                </tr>
            @endforeach
            </tbody>
        </table>
        @else
            <h2></h2>
        @endif
    </div>

        <form id="form_desarchivar" action="{{route('desarchivarCuadrante',[':CUADRANTE_ID'])}}" method="POST">
        {{csrf_field()}}
        </form>                


    </div>

</div>
</div>
</div>
</div>
<script>
$(document).ready(function() {

    $('.btn_desarchivar').click(function(e){
        e.preventDefault();
        var elem = $(this);
        var fila = $(this).parents('tr');
        var id = fila.data('id');
        var form = $('#form_desarchivar');
        var url = form.attr('action').replace(':CUADRANTE_ID', id);

        var data = form.serialize();
        $.post(url, data).done(function(data){
            alert(data);
            elem.hide();
            elem.parent().prev().html('Aceptado');

        });

    });


});    

</script>


@endsection
