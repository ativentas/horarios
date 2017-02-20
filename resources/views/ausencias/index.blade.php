
<!-- <link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css' media="all" rel="stylesheet" type="text/css"/>
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.print.css' media="print" rel="stylesheet" type="text/css"/> -->
<link href="{{asset('fullcalendar/fullcalendar.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link href="{{asset('fullcalendar/fullcalendar.print.css')}}" media="print" rel="stylesheet" type="text/css" />

@extends('layouts.app')

@section('content')

<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
    <div class="panel-heading"><h2>Calendario</h2>
        @include('layouts.alerts')
        <div class="row">
                <ol class="breadcrumb">
		            <li><a href="{{ url('home') }}">Salir</a></li>
					<li class="active">Calendario</li>
					<li><a href="{{ url('/ausencias') }}">Listado</a></li>
					<li><a href="{{ url('/ausencias/create') }}">Nuevo</a></li>
				</ol>
        </div>
    </div>

    <div class="panel-body">
	    <div class="col-md-12">
			<div id='calendar'></div>
		</div>
<!-- 	    <div class="col-md-12">
			<div id='calendar2'></div>
		</div> -->
	</div>
</div>
</div>
</div>
</div>
<!-- <script type="text/javascript" src=" //cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script> -->
<script src="{{asset('fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('fullcalendar/locale/es.js')}}"></script>


<script type="text/javascript">
	$(document).ready(function() {
		
		var base_url = '{{ url('/') }}';
		// $('#calendar2').fullCalendar({
		// 	weekends:true,
		// 	header: {
		// 		left:'today prev,next',
		// 		center:'title',
		// 		right: 'basicDay,month,basicWeek'
		// 	}
		// });
		$('#calendar').fullCalendar({
			weekends: true,
			lang: 'es',
			header: {
				left: 'today prev,next',
				center: 'title',
				right: 'basicDay,month,basicWeek',

			},
			defaultView: 'basicWeek',
			slotEventOverlap: false,
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: {
				url: base_url + '/api',
				error: function() {
					alert("cannot load json");
				}
			}

		});

	});
</script>
@endsection



