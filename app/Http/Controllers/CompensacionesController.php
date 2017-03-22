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
                    $query->where('diacompensado', null)->where('pagar',false);
                }])
                ->get();
        $centros = Centro::all();
        return view('compensables.index',compact('empleados','centros'));
   }

   public function update(Request $request, $compensable_id)
   {
    
    //TO DO: la idea es que desde el listado de Compensaciones, el administrador pueda asignarle como para pagar. Y el encargado desde la plantilla de horarios pueda asignar a un día libre un/os de lo/s dias pendientes

    if ($request->has('radio_compensar')) {
        //actualizar compensable
        $compensable = \App\Compensable::find($compensable_id);
        switch ($request->radio_compensar) {
            case 'P':
                $compensable->diacompensado = null;
                $compensable->pagar = true;
                break;
            case 'DL':
                $compensable->diacompensado = $request->fecha;
                $yearsemana = $this->yearsemana($request->fecha);
                $centro_id = $compensable->linea->empleado->centro_id;
                $cuadrante = Compensable::where('yearsemana',$yearsemana)->where('centro_id',$centro_id)->first();
                if($cuadrante){
                    if($cuadrante->estado=='Archivado'){
                        dd('El Horario ya está archivado');
                    }
                    $compensable->cuadrante_id = $cuadrante->id;
                }
                $compensable->pagar = false;
                
                break;
            default:
                dd('Esta opción no puede haber salido. Error en el programa. Revisar');
                break;
        }
        $compensable->nota = $request->nota;
        $compensable->resuelto_por = Auth::user()->id;
        $compensable->save();

    }
   }
    public function show($compensable_id)
    {

    }

    public function yearsemana($fecha)
    {
        $year = Carbon::parse($fecha)->startOfWeek()->year;
        $semana = Carbon::parse($fecha)->weekOfYear;
        $semana = sprintf("%02d", $semana);
        return $year.$semana;
    }


}
