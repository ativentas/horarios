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

Route::get('/home', 'HomeController@index');
Route::get('/cuadrante/{cuadrante_id?}', 'CuadranteController@mostrarCuadrante')->middleware('auth');
Route::get('/nieuwcuadrante/', 'CuadranteController@mostrarNieuwCuadrante')->middleware('auth');
Route::post('/nieuwcuadrante/', 'CuadranteController@crearNieuwCuadrante')->middleware('auth');


Route::resource('empleados', 'EmpleadoController');


/**
 * AUSENCIAS CALENDARIO
 */
	Route::get('/ausencias/calendario', function () {
		$data = ['page_title' => 'Vacaciones',];
	    return view('ausencias/index', $data);
		});
	Route::get('ausencias/listadoVacaciones', [
		'uses' => '\Horarios\Http\Controllers\AusenciaController@listadoVacaciones',
		'as' => 'listadoVacaciones',
		]);
	Route::any('vacaciones/{id}', [
		'uses' => '\Horarios\Http\Controllers\AusenciaController@confirmarVacaciones',
		'as' => 'confirmarVacaciones',
		]);
	
	Route::resource('ausencias', 'AusenciaController');

	Route::get('/api', function () {
		$ausencias = DB::table('ausencias')->select('id', 'empleado_id', 'tipo', 'fecha_inicio as start', 'fecha_fin as end','finalDay','allDay')->get();
			$tiposAusencia = ['V' => 'vacaciones', 'B' => 'baja', 'AJ' => 'ausencia justif.','AN' => 'ausencia no justif.'];
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
	});


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
Route::post('/aceptar/{id}', [
	'uses' => 'CuadranteController@aceptarHorarios',
	'as' => 'aceptarCuadrante',
	'middleware' => ['auth']
	]);

