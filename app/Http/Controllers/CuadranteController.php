<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use DB;
use App\Cuadrante;
use App\Empleado;
use App\Linea;
use App\Ausencia;

class CuadranteController extends Controller
{
private $semanaactual;
private $hoy;

public function __construct() { 
    $this->semanaactual = date('W');
    $this->hoy = Carbon::today();
}
    
public function yearsemana($fecha)
{
    $year = Carbon::parse($fecha)->startOfWeek()->year;
    $semana = Carbon::parse($fecha)->weekOfYear;
    $semana = sprintf("%02d", $semana);
    return $year.$semana;
}

public function mostrarCuadrante($yearsemana=NULL)
{
    if(!$yearsemana){
        $ultimo = Cuadrante::where('centro_id', Auth::user()->centro_id)->orderBy('yearsemana','desc')->first();
        if(!$ultimo){
            return view('cuadrantes.geencuadrante');
        }
        return redirect('cuadrante/'.$ultimo->yearsemana)->with('info', 'Ultimos horarios grabados');

    }
    
    $cuadrante = Cuadrante::where('yearsemana',$yearsemana)->where('centro_id', Auth::user()->centro_id)->first();
    if(!$cuadrante){
        return redirect('cuadrante');
    }
    if($cuadrante->archivado == '0'){
        //TO DO: actualizar las lineas con las ausencias, ver si hay conflictos (por ejemplo cuando es vacaciones pero en la linea pone Vacaciones Trabaja).
        $centro_id = $cuadrante->centro_id;
        $empleados = Empleado::where('centro_id',$centro_id)->get();

        $yearsemana = $cuadrante->yearsemana;
        $year = substr($yearsemana,0,4);
        $semana = substr($yearsemana,-2,2);
        $date = new Carbon();
        $date->setISODate($year,$semana); 
        $inicio_semana = new Carbon($date->startOfWeek()); 
        $final_semana = new Carbon($date->endOfWeek());

        $ausencias = Ausencia::where('fecha_fin','>=',$inicio_semana)->where('fecha_inicio','<=',$final_semana)->whereIn('empleado_id',$empleados)->get();
        if($ausencias){
            //TO DO:para cada ausencia ver las lineas afectadas

            $diassemana = $this->generateDateRange($inicio_semana, $final_semana);

            foreach ($ausencias as $ausencia) {
                $fecha_ausencia = $ausencia->fecha_inicio;
                $fechafin_ausencia = $ausencia->fecha_fin;
                while ($fecha_ausencia <= $fechafin_ausencia) {
                    if (in_array($fecha_ausencia, $diassemana)) {
                        //añadir al array de fechas con tipo y ausencia_id
                    }
                                        
                    $fecha_ausencia = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ausencia)));
                }
                
            }


        }
    }
    $lineas = DB::table('lineas')
        ->join('empleados', 'lineas.empleado_id', '=', 'empleados.id')
        ->where('lineas.cuadrante_id','=',$cuadrante->id)
        ->select(
            'empleados.alias AS nombre', 
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.situacion ELSE NULL END) AS 'situacion1'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.entrada1 ELSE NULL END) AS 'ELU'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.salida1 ELSE NULL END) AS 'SLU'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.situacion ELSE NULL END) AS 'situacion2'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.entrada1 ELSE NULL END) AS 'EMA'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.salida1 ELSE NULL END) AS 'SMA'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.situacion ELSE NULL END) AS 'situacion3'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.entrada1 ELSE NULL END) AS 'EMI'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.salida1 ELSE NULL END) AS 'SMI'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.situacion ELSE NULL END) AS 'situacion4'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.entrada1 ELSE NULL END) AS 'EJU'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.salida1 ELSE NULL END) AS 'SJU'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.situacion ELSE NULL END) AS 'situacion5'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.entrada1 ELSE NULL END) AS 'EVI'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.salida1 ELSE NULL END) AS 'SVI'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.situacion ELSE NULL END) AS 'situacion6'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.entrada1 ELSE NULL END) AS 'ESA'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.salida1 ELSE NULL END) AS 'SSA'"),
            DB::raw("min(CASE WHEN lineas.dia = 7 THEN lineas.situacion ELSE NULL END) AS 'situacion7'"),
            DB::raw("min(CASE WHEN lineas.dia = 7 THEN lineas.entrada1 ELSE NULL END) AS 'EDO'"),
            DB::raw("min(CASE WHEN lineas.dia = 7 THEN lineas.salida1 ELSE NULL END) AS 'SDO'")
            )
        ->groupBy ('empleados.alias')
        ->get();
    return view('cuadrantes.detalle',compact('lineas'));

}

public function mostrarNieuwCuadrante()
{
    return view('cuadrantes.nieuwcuadrante');

}
public function crearNieuwCuadrante(Request $request)
{
    //comprobar primero si ya existe el cuadrante
    $semanayear = $request->fecha;
    $year = substr($semanayear,-4,4);
    $semana = substr($semanayear,0,2);

    $cuadrante = Cuadrante::where('centro_id', Auth::user()->centro_id)->where('yearsemana',$year.$semana)->first();
    if ($cuadrante){
        //TO DO: como ya existe, mostrarlo pero con un mensaje diciendo que ya existía
        $lineas = $cuadrante->lineas();
        // return view('cuadrantes.detalle',compact('lineas')); 
        return redirect('cuadrante/'.$cuadrante->yearsemana); 
    }
    //TO DO: hacer try catch para que lo haga todo o nada    
    $cuadrante = new Cuadrante;
    $cuadrante->yearsemana = $year.$semana;
    $cuadrante->centro_id = Auth::user()->centro_id;

    $cuadrante->save();
    $date = new Carbon();
    $date->setISODate($year,$semana); 
    $fecha_ini = new Carbon($date->startOfWeek()); 
    $fecha_fin = new Carbon($date->endOfWeek());

    $this->addLineas($cuadrante->id,Auth::user()->centro_id,$fecha_ini,$fecha_fin);
    return redirect('cuadrante/'.$cuadrante->id);

}

public function addLineas ($cuadrante_id, $centro_id, $fecha_ini, $fecha_fin){
    $empleados = Empleado::where('centro_id',$centro_id)->get();
    if(!$empleados){
        // TO DO: lanzar excepción para que no cree nada
    }
    //TO DO: ver si conviene prerrellenar las lineas con la plantilla anterior.
    //TO DO: prerrellenar las Ausencias
    
    while ($fecha_ini <= $fecha_fin) {
        foreach ($empleados as $empleado) {
            $linea = new Linea;
            $linea->cuadrante_id = $cuadrante_id;
            $linea->fecha = $fecha_ini;
            $linea->empleado_id = $empleado->id;          
            $linea->save();     
        }
        $fecha_ini = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ini)));
    }
}

public function guardarAusencia (Request $request){
    $ausencia = new Ausencia;
    $ausencia->empleado_id = $request->empleado_id;
    $ausencia->tipo = $request->tipo;
    $ausencia->fecha_inicio = $request->inicio;
    $ausencia->fecha_fin = $request->fin;
    $ausencia->nota = $request->nota;
    $ausencia->save();
}

public function generateDateRange(Carbon $start_date, Carbon $end_date)
{
    $dates = [];

    for($date = $start_date; $date->lte($end_date); $date->addDay()) {
        $dates[] = $date->format('Y-m-d');
    }

    return $dates;
}

}
