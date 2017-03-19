<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empleado;
use App\Centro;
use DB;
use Auth;

class CompensacionesController extends Controller
{
   public function index()
   {	
        $centro_id = \Request::input('centro');
		if(!Auth::user()->isAdmin()){
			$centro_id = Auth::user()->centro_id;
		}
        //TO DO: ver bien para obtener los empleados que tengan compensables de este año o pendientes en general
        $empleados = Empleado::whereHas('compensables',function($query){
                //aqui podría poner algun where       
            })  ->when($centro_id, function($query) use($centro_id){
                    return $query->where('empleados.centro_id',$centro_id);
            })
                ->with('compensables')
                ->withCount(['compensables'=> function ($query) {
                    $query->where('diacompensado', null);
                }])
                ->get();
        $centros = Centro::all();
        return view('compensables.index',compact('empleados','centros'));
   }

}
