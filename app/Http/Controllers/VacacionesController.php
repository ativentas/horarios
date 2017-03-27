<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\Centro;
use DB;
use Auth;

class VacacionesController extends Controller
{
   public function index()
   {
   	
		$centro_id = \Request::input('centro');
		if(!Auth::user()->isAdmin()){
			$centro_id = Auth::user()->centro_id;
		}
		//TO DO: que el año lo saque automáticamente, el anterior al año actual por ejemplo. También se podría hacer para navegar por años en el listado de vacaciones
     	$year = 2016;
     	$empleados = DB::table('empleados')
     		->leftJoin('ausencias',function($join) {
     			$join->on('empleados.id','=','ausencias.empleado_id')
     					->where('ausencias.tipo','V')
     					->where('ausencias.estado','Confirmado');
     		})
            // ->Join('centros', function($join) use($centro_id){
            //     $join->on('empleados.centro_id','=','centros.id')
            //         // ->where('centros.id',$centro_id);
            //         ->when($centro_id, function($query) use($centro_id){
            //                 return $query->where('centros.id',$centro_id);
            //         });
            //     })
     		->join('contratos', function($join) use($centro_id){
     			$join->on('contratos.empleado_id','=','empleados.id')
     				// ->where('centros.id',$centro_id);
     				->when($centro_id, function($query) use($centro_id){
     						return $query->where('contratos.centro_id',$centro_id);
     				});
     			})
            ->join('centros','centros.id','=','contratos.centro_id')
     		->leftJoin('saldos',function($join) use($year){
     			$join->on('empleados.id','=','saldos.empleado_id')
     				->where('saldos.year',$year);
     		})
     		->select(
     			// 'empleados.id AS id','empleados.alias AS alias','centros.nombre AS centro','saldos.dias AS saldoanterior',
     			'empleados.id AS id','empleados.alias AS alias','centros.nombre AS centro',
     			// DB::raw("sum(CASE WHEN ausencias.estado = 'Confirmado' AND ausencias.tipo = 'V' THEN ausencias.dias ELSE 0 END) AS 'confirmadas'"),
     			// DB::raw("sum(CASE WHEN saldos.year = '2016' THEN saldos.dias ELSE 0 END) AS 'saldoanterior'")
     			// )
     			DB::raw("sum(ausencias.dias) AS 'confirmadas'"),
     			DB::raw("sum(saldos.dias) AS 'saldoanterior'")
     			)
     		->groupBy ('empleados.id','empleados.alias','contratos.centro_id')
        	->get();


   	$centros = Centro::all();
      return view('vacaciones.index',compact('empleados','centros'));
   }

}
