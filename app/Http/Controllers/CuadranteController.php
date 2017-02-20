<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;

use Auth;
use DB;
use App\Cuadrante;
use App\Empleado;
use App\Linea;
use App\Centro;
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
        $empleados = Empleado::where('centro_id',$centro_id)->pluck('id')->toArray();

        $yearsemana = $cuadrante->yearsemana;
        $year = substr($yearsemana,0,4);
        $semana = substr($yearsemana,-2,2);
        $date = new Carbon();
        $date = new Carbon($date->setISODate($year,$semana));
        $date_clon = clone $date;
        $inicio_semana = new Carbon($date_clon->startOfWeek());

        $final_semana = new Carbon($date_clon->endOfWeek());
        //ver si las lineas con ausencias se corresponden con la tabla de ausencias
        $lineasconausencias = $cuadrante->lineas()->where('ausencia_id','>',0)->get();
        if($lineasconausencias){
            foreach ($lineasconausencias as $linea) {
                $ausencia = Ausencia::where('id',$linea->ausencia_id)->where('empleado_id',$linea->empleado_id)->first();
                //ver si la fecha de la linea se corresponde con el intervalo de la $ausencia
                if ($ausencia){
                    $inicio = new Carbon($ausencia->fecha_inicio);
                    $fin = new Carbon($ausencia->finalDay);
                    $intervaloausencia = $this->generateDateRange($inicio,$fin);
                    // actualizar la linea
                    $linea->update([
                        'situacion' => $ausencia->tipo,
                    ]);
                }
                //como no hay ausencia, modifico la linea borrando la ausencia

                $linea->update([
                    'ausencia_id' => NULL,
                    'situacion' => NULL,
                    ]);
            }
        }


        //actualizar las lineas con los datos de la tabla de ausencias
        $ausencias = Ausencia::where('fecha_fin','>=',$inicio_semana->toDateTimeString())->where('fecha_inicio','<=',$final_semana->toDateTimeString())->whereIn('empleado_id',$empleados)->get();
        // dd($ausencias,$empleados);
        if($ausencias){
            
            $inicio_semana_clon = clone $inicio_semana;
            $final_semana_clon = clone $final_semana;
            $diassemana = $this->generateDateRange($inicio_semana_clon, $final_semana_clon->addDay());
            // $arrayausencias = [];
            foreach ($ausencias as $ausencia) {
                $fecha_ausencia = $ausencia->fecha_inicio;
                $fecha_ausencia = new DateTime($fecha_ausencia);
                $fecha_ausencia = $fecha_ausencia->format('Y-m-d');
                
                $fechafin_ausencia = $ausencia->finalDay;

                while ($fecha_ausencia <= $fechafin_ausencia) {
                    if (in_array($fecha_ausencia, $diassemana)) {
                        $arrayprueba[] = $fecha_ausencia;
                        $linea = Linea::where('empleado_id',$ausencia->empleado_id)->where('fecha',$fecha_ausencia)->first();
                        if($linea){
                            if($linea->situacion == 'VT' && $ausencia->tipo == 'V')
                            {
                                $linea->update([
                                    'ausencia_id' => $ausencia->id,
                                ]);
                            }else{
                                $linea->update([
                                    'ausencia_id' => $ausencia->id,
                                    'situacion' => $ausencia->tipo,
                                ]);
                            }
                        }

                        //TO DO: si me decanto por crear primero el array y luego hacer un loop para actualizar las lineas
                        // $arrayausencias[] =[$fecha_ausencia,$ausencia->tipo,$ausencia->empleado_id]; 
                    }
                    $fecha_ausencia = DateTime::createFromFormat('Y-m-d', $fecha_ausencia);
                    $fecha_ausencia = $fecha_ausencia->modify('+1 day')->format('Y-m-d');
                    // $fecha_ausencia = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ausencia)));
                }
            }
            //TO DO: si me decanto por el $arrayausencias, actualizar la tabla de lineas con los datos del array

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
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.situacion ELSE NULL END) AS 'situacion7'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.entrada1 ELSE NULL END) AS 'EDO'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.salida1 ELSE NULL END) AS 'SDO'")
            )
        ->groupBy ('empleados.alias')
        ->get();
    
// dd($lineas);

    return view('cuadrantes.detalle',compact('lineas','year','semana','inicio_semana','final_semana'));

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
        // $lineas = $cuadrante->lineas();
        // return view('cuadrantes.detalle',compact('lineas')); 
        return redirect('cuadrante/'.$cuadrante->yearsemana); 
    }
    $centro_id = Auth::user()->centro_id;
    //TO DO: hacer try catch para que lo haga todo o nada    
    $cuadrante = new Cuadrante;
    $cuadrante->yearsemana = $year.$semana;
    $cuadrante->centro_id = $centro_id;

    //TO DO: grabar dia de cierre, en su caso
    $diacierre = Centro::where('id',$centro_id)->firstOrFail()->dia_cierre;
    
    if ( !is_null ( $diacierre ) ){
        $columna = 'dia_'.$diacierre;
        $cuadrante->$columna = 'C';
    }
    //TO DO: ver si hay algún festivo para esta semana

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
        dd('no hay ningún empleado, no se puede crear un horario vacío');
    }
    //TO DO: ver si conviene prerrellenar las lineas con la plantilla anterior. De momento decido que no, 
    //TO DO: prerrellenar las Ausencias. De momento no hace falta porque en cuanto muestre el cuadrante ya se modifican las lineas
    while ($fecha_ini <= $fecha_fin) {

        foreach ($empleados as $empleado) {
            $linea = new Linea;
            $linea->cuadrante_id = $cuadrante_id;
            $linea->fecha = $fecha_ini;
            $linea->dia = $fecha_ini->dayOfWeek;
            $linea->empleado_id = $empleado->id;          
            $linea->save();     
        }
        //borro esta linea porque date convierte a string y entonces no le puedo aplicar dayOfWeek
        // $fecha_ini = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ini)));
        $fecha_ini = $fecha_ini->addDay();
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
