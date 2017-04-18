<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('auth');
Route::get('/cuadrantes', 'CuadranteController@index')->middleware('auth');
Route::get('/cuadrante/{cuadrante_id?}', 'CuadranteController@mostrarCuadrante')->middleware('auth');
Route::get('/nieuwcuadrante/', 'CuadranteController@mostrarNieuwCuadrante')->middleware('auth');
Route::post('/nieuwcuadrante/', 'CuadranteController@crearNieuwCuadrante')->middleware('auth');




Route::get('/empleados_c/{empleado_id}/{cuadrante_id?}', 'EmpleadoController@show')->middleware('auth');
// Route::get('/empleados_c2/{empleado_id}', 'EmpleadoController@show2')->middleware('auth');
Route::resource('empleados', 'EmpleadoController');
Route::resource('contratos', 'ContratoController');
Route::resource('users', 'UserController');
Route::resource('centros', 'CentroController');
Route::resource('predefinidos', 'PredefinidoController');


/**
 * AUSENCIAS CALENDARIO
 */
	Route::get('/ausencias/calendario', function () {
		$data = ['page_title' => 'Vacaciones',];
	    return view('ausencias/index', $data);
		});
//creo que esta ruta no se usa
	// Route::get('ausencias/listadoVacaciones', [
	// 	'uses' => 'AusenciaController@listadoVacaciones',
	// 	'as' => 'listadoVacaciones',
	// 	]);
	Route::post('vacaciones/{id}', [
		'uses' => 'AusenciaController@confirmarVacaciones',
		'as' => 'confirmarVacaciones',
		]);
	
	Route::resource('ausencias', 'AusenciaController');


Route::post('/validar/{id}', [
	'uses' => 'CuadranteController@guardarHorarios',
	'as' => 'guardarCuadrante',
	'middleware' => ['auth']
	]);
Route::post('/add/{empleado_id}/{cuadrante_id}', [
	'uses' => 'CuadranteController@añadirempleado',
	'as' => 'añadirEmpleado',
	'middleware' => ['auth']
	]);
Route::post('/delete/{empleado_id}/{cuadrante_id}', [
	'uses' => 'CuadranteController@eliminarempleado',
	'as' => 'deleteEmpleado',
	'middleware' => ['auth','isAdmin']
	]);

Route::post('/aceptar/{id}', [
	'uses' => 'CuadranteController@aceptarHorarios',
	'as' => 'aceptarCuadrante',
	'middleware' => ['auth','isAdmin']
	]);
Route::post('/archivar/{id}', [
	'uses' => 'CuadranteController@archivarHorarios',
	'as' => 'archivarCuadrante',
	'middleware' => ['auth','isAdmin']
	]);
Route::post('/desarchivar/{id}', [
	'uses' => 'CuadranteController@desarchivarHorario',
	'as' => 'desarchivarCuadrante',
	'middleware' => ['auth','isAdmin']
	]);


 Route::post('comment/add','CommentController@store')->middleware('auth');
 Route::post('comment/addrespuesta/{nota_id}','CommentController@storerespuesta')->middleware('auth');
 // delete comment
 Route::post('comment/delete/{id}','CommentController@destroy')->middleware('auth');

 Route::get('vacaciones','VacacionesController@index')->middleware('auth');
 Route::get('compensaciones','CompensacionesController@index')->middleware('auth');
 Route::post('compensaciones/{id}','CompensacionesController@update','asignar_compensable')->middleware('auth');

// Route::get('/api2', 'EmpleadoController@datos_calendario')->middleware('auth');



//TO DO: HABRIA QUE PREPARARLO PARA QUE DIERA LAS AUSENCIAS POR CENTRO DE TRABAJO Y NO TODOS A LA VEZ. DE MOMENTO Y COMO PRIMER PASO, DAR LAS DEL CENTRO DEL USER Y SI ES ADMIN DE TODOS. EN UNA SEGUNDA ETAPA, EL ADMIN PODRA ELEGIR DE QUE CENTRO.
Route::get('/api', function () {
	$centro='';
	if(!Auth::user()->isAdmin()){
		$centro = Auth::user()->centro_id;
	}
	$ausencias = DB::table('ausencias')
		->when($centro, function($query) use($centro){
			return $query->where('ausencias.centro_id',$centro);
		})
		->select('ausencias.centro_id','ausencias.id', 'ausencias.empleado_id', 'ausencias.tipo', 'ausencias.fecha_inicio as start', 'ausencias.fecha_fin as end','ausencias.finalDay','ausencias.allDay')
		->get();
	$tiposAusencia = ['V' => 'vacaciones', 'B' => 'baja', 'AJ' => 'ausencia justif.','AN' => 'ausencia no justif.','BP' => 'Baja Paternidad','PR' => 'Permiso Retribuido'];
 	$empleados = DB::table('empleados')->pluck('alias','id');

	foreach($ausencias as $ausencia){
		$start = date('d/m',strtotime($ausencia->start));
		$end = date('d/m',strtotime($ausencia->finalDay));
		$ausencia->title = $tiposAusencia[$ausencia->tipo] . ' - ' .$empleados[$ausencia->empleado_id]. ' (' .$start. ' A '.$end.')';
		$ausencia->url = url('ausencias/' . $ausencia->id);
		if($ausencia->allDay==0){
				$ausencia->allDay = false;
			}else{$ausencia->allDay = true;}
	}
	return $ausencias;
})->middleware('auth');


Route::get('/api2/{empleado_id}', function ($empleado_id) {
  	$centro_user='';
  	if(!Auth::user()->isAdmin()){
      $centro_user = Auth::user()->centro_id;
  	}
  	$conhorarios = ['FT','VT',''];
   $conausencias =['V','AJ','AN','BP','PR'];
   $empleados = DB::table('empleados')->pluck('alias','id');
	$lineas_calendario_1 = DB::table('lineas')
	->where(function($query) use($empleado_id) {
		$query->where('lineas.empleado_id',$empleado_id)
		->where('situacion', null);
	})
	->orWhere(function($query) use($empleado_id,$conhorarios){
		$query->where('lineas.empleado_id',$empleado_id)
		->whereIn('situacion',$conhorarios);
	})
	->join('cuadrantes','lineas.cuadrante_id','cuadrantes.id')
	->join('centros','centros.id','cuadrantes.centro_id')
	->select('lineas.id','lineas.situacion','centros.nombre as centro','lineas.fecha as fecha_inicio','lineas.fecha as fecha_fin','lineas.entrada1 as entrada','lineas.salida1 as salida')
	->get();
	
	$lineas_calendario_2 = DB::table('lineas')
	->where('lineas.empleado_id',$empleado_id)
	->where('lineas.salida2','<>',null)
	->join('cuadrantes','lineas.cuadrante_id','cuadrantes.id')
	->join('centros','centros.id','cuadrantes.centro_id')
	->select('lineas.id','lineas.situacion','centros.nombre as centro','lineas.fecha as fecha_inicio','lineas.fecha as fecha_fin','lineas.entrada2 as entrada','lineas.salida2 as salida')
	->get();

	// $lineas_ausencias = DB::table('lineas')
	// ->where('lineas.empleado_id',$empleado_id)
	// ->whereIn('situacion',$conausencias)
	// ->join('cuadrantes','lineas.cuadrante_id','cuadrantes.id')
	// ->join('centros','centros.id','cuadrantes.centro_id')
	// ->select('lineas.id','lineas.situacion','centros.nombre as centro','lineas.fecha as fecha_inicio','lineas.fecha as fecha_fin','lineas.entrada2 as entrada','lineas.salida2 as salida')
	// ->get();

	$ausencias = DB::table('ausencias')
	->where('ausencias.empleado_id',$empleado_id)
	->select('ausencias.centro_id','ausencias.id', 'ausencias.tipo', 'ausencias.fecha_inicio as start', 'ausencias.fecha_fin as end','ausencias.finalDay','ausencias.allDay')
	->get();
	$tiposAusencia = ['V' => 'vacaciones', 'B' => 'baja', 'AJ' => 'ausencia justif.','AN' => 'ausencia no justif.','BP' => 'Baja Paternidad','PR' => 'Permiso Retribuido'];

	foreach($ausencias as $ausencia){
		$start = date('d/m',strtotime($ausencia->start));
		$end = date('d/m',strtotime($ausencia->finalDay));
		$ausencia->title = $tiposAusencia[$ausencia->tipo] .  ' (' .$start. ' A '.$end.')';
		if($ausencia->allDay==0){
				$ausencia->allDay = false;
			}else{$ausencia->allDay = true;}
	}


$lineas_calendarioH = $lineas_calendario_1->merge($lineas_calendario_2);

    foreach($lineas_calendarioH as $linea){
        $linea->title = $linea->situacion.' '.$linea->centro;
        // $linea->allDay = false;
        $linea->start = $linea->fecha_inicio.'T'.$linea->entrada.'Z';
        // $linea->end = $linea->fecha_fin.'T'.$linea->salida1;
        if($linea->salida == '00:00:00'){
        		$fecha = DateTime::createFromFormat('Y-m-d', $linea->fecha_fin);
				$fecha->add(new DateInterval('P1D'));
				$fecha = $fecha->format('Y-m-d');
				$linea->end = $fecha.'T'.$linea->salida;
        	}else{
        		$linea->end = $linea->fecha_fin.'T'.$linea->salida.'Z';
     		}
    }

$lineas_calendario = $lineas_calendarioH->merge($ausencias);

   return $lineas_calendario;
})->middleware('auth');

