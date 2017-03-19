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
            })  ->with('compensables')
                ->withCount(['compensables'=> function ($query) {
                    $query->where('diacompensado', null);
                }])
                ->get();
        $centros = Centro::all();
        return view('compensables.index',compact('empleados','centros'));
   }

}
