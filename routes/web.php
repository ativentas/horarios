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
Route::resource('empleados', 'EmpleadoController');
Route::resource('contratos', 'ContratoController');


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

	//TO DO: HABRIA QUE PREPARARLO PARA QUE DIERA LAS AUSENCIAS POR CENTRO DE TRABAJO Y NO TODOS A LA VEZ. DE MOMENTO Y COMO PRIMER PASO, DAR LAS DEL CENTRO DEL USER Y SI ES ADMIN DE TODOS. EN UNA SEGUNDA ETAPA, EL ADMIN PODRA ELEGIR DE QUE CENTRO.

	Route::get('/api', function () {
		$centro='';
		if(!Auth::user()->isAdmin()){
			$centro = Auth::user()->centro_id;
		}
		$ausencias = DB::table('ausencias')
			->join('empleados', function($join) use($centro){
				$join->on('ausencias.empleado_id', '=', 'empleados.id')
				->when($centro, function($query) use($centro){
					return $query->where('empleados.centro_id',$centro);
				});  
			})
			->select('empleados.centro_id','ausencias.id', 'ausencias.empleado_id', 'ausencias.tipo', 'ausencias.fecha_inicio as start', 'ausencias.fecha_fin as end','ausencias.finalDay','ausencias.allDay')
			// ->where('empleados.centro_id','=',$centro)
			->get();
		$tiposAusencia = ['V' => 'vacaciones', 'B' => 'baja', 'AJ' => 'ausencia justif.','AN' => 'ausencia no justif.','BP' => 'Baja Paternidad'];
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


 Route::post('comment/add','CommentController@store')->middleware('auth');
 Route::post('comment/addrespuesta/{nota_id}','CommentController@storerespuesta')->middleware('auth');
 // delete comment
 Route::post('comment/delete/{id}','CommentController@destroy')->middleware('auth');

 Route::get('vacaciones','VacacionesController@index')->middleware('auth');
 Route::get('compensaciones','CompensacionesController@index')->middleware('auth');
 Route::post('compensaciones/{id}','CompensacionesController@update','asignar_compensable')->middleware('auth');


