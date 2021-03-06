<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use Exception;

use Auth;
use DB;
use App\Cuadrante;
use App\Empleado;
use App\Linea;
use App\Lineacambio;
use App\Centro;
use App\Ausencia;

class CuadranteController extends Controller
{
//de momento parece que no uso $semanaactual y $hoy, averiguar para que las quería y sino se pueden borrar, también están en el construct
private $semanaactual;
private $hoy;
private $situacionesconAusenciaId;

public function __construct() { 
    $this->semanaactual = date('W');
    $this->hoy = Carbon::today();
    $this->situacionesconAusenciaId = ['V','B','AJ','AN','BP','PR'];
}    

public function yearsemana($fecha)
{
    $year = Carbon::parse($fecha)->startOfWeek()->year;
    $semana = Carbon::parse($fecha)->weekOfYear;
    $semana = sprintf("%02d", $semana);
    return $year.$semana;
}

public function index()
{
    $isadmin = Auth::user()->isAdmin();
    switch ($isadmin) {
        case true:
            $cuadrantes = Cuadrante::whereIn('estado',array('Pendiente','AceptadoCambios','Aceptado'))->orderBy('yearsemana','asc');                
            
            $cuadrantes = \Request::has('centro') ? $cuadrantes->where('centro_id',\Request::input('centro')) : $cuadrantes;  
            $cuadrantes = $cuadrantes->get();


            $completados = Cuadrante::whereIn('estado',array('Archivado'))->orderBy('yearsemana','desc');
            
            $completados = \Request::has('centro') ? $completados->where('centro_id',\Request::input('centro')) : $completados;  
            $completados = $completados->get();


            break;
        
        default:
            $yearsemana = $this->yearsemana(date('Y-m-d'));
            // $cuadrantes = Cuadrante::where('centro_id', Auth::user()->centro_id)->whereNotIn('estado',array('Archivado'))->orderBy('yearsemana','asc')->get();
            $cuadrantes = Cuadrante::where('centro_id', Auth::user()->centro_id)
                ->whereNotIn('estado',array('Archivado'))
                ->orWhere(function($query){
                    $query->where('centro_id', Auth::user()->centro_id)
                        ->whereNull('estado');
                })->get();
            $completados = Cuadrante::where('centro_id', Auth::user()->centro_id)->whereIn('estado',array('Archivado'))->orderBy('yearsemana','desc')->get();
            
            break;
    }
    $centros = Centro::all();
    return view('cuadrantes.list',compact('cuadrantes','completados','centros'));
}

public function guardarHorarios(Request $request, $cuadrante_id){
    #TO DO: cuando el cuadrante esté aceptado, comparar los datos a grabar con lo grabado y si hay cambios, grabar el antiguo dato en lineacambios y esas lineas de lineacambios se borrarán cuando se ponga el cuadrante como archivado

    $cuadrante = Cuadrante::findOrFail($cuadrante_id);
    // $input = $request->all();
    // dd($input);
    $lineas = Linea::where('cuadrante_id',$cuadrante_id)->get();
    $cambios = false;
    switch ($cuadrante->estado) {
        case 'Pendiente':
        case NULL:
            if(count($cuadrante->lineacambios()->get())) {
            dd(count($cuadrante->lineacambios()));
            #TO DO:si hay lineasconcambios lanzar error o borrarlas
                dd('La plantilla estaba todavía Pendiente y hay lineas con cambio y no debería,');
            }
            break;
        case 'Archivado':
            if(count($cuadrante->lineacambios())){
            #TO DO:si hay lineasconcambios lanzar error o borrarlas
                dd('La plantilla ya estaba archivada y no debería haber lineacambios');
            }
            dd('Error, esta plantilla ya está archivada. No se debería haber podido hacer click en Guardar');
        default:
            # si hay lineas con cambios, poner $cambios true
            if (count($cuadrante->lineacambios())) {
                $cambios = true;
            }
            break;
    }

    try {
        $exception = DB::transaction(function() use ($lineas,$cuadrante,$request,$cambios)
        {
        foreach ($lineas as $linea) {
            $dia = $linea->dia;
            $empleado_id = $linea->empleado_id;
            $situacion = $request->{'situacion_'.$dia.'_'.$empleado_id};
            //esto lo hago pq cuando añado un empleado a una plantilla, el programa no lo dibuja y entonces al guardar los datos machaca la situación a null
            if(!isset($request->{'situacion_'.$dia.'_'.$empleado_id}))
            {
                $situacion = $linea->situacion;
            }
            $entrada1 = $request->{'entrada1_'.$dia.'_'.$empleado_id};
            $entrada2 = $request->{'entrada2_'.$dia.'_'.$empleado_id};
            $salida1 = $request->{'salida1_'.$dia.'_'.$empleado_id};
            $salida2 = $request->{'salida2_'.$dia.'_'.$empleado_id};
            if ($cuadrante->estado == 'Aceptado'||$cuadrante->estado == 'AceptadoCambios'){
                $arraynuevo = [$situacion,$entrada1,$entrada2,$salida1,$salida2];
                // $arrayaprobado = [$linea->situacion,substr($linea->entrada1,0,5),substr($linea->entrada2,0,5),substr($linea->salida1,0,5),substr($linea->salida2,0,5)];   
                $arrayaprobado = [$linea->situacion,$linea->entrada1?:null,$linea->entrada2?:null,$linea->salida1?:null,$linea->salida2?:null];
                if($arraynuevo == $arrayaprobado && count($linea->lineacambio) && $cuadrante->estaod == 'Aceptado'){
                    $linea->lineacambio()->delete();
                }

                if($arraynuevo != $arrayaprobado && $linea->doesntHave('lineacambio')){
                    #si ya hay lineaconcambios, no hacer nada, TO DO: si acaso podría comprobar que el registro de lineacambio fuese igual al arrayaprobado
                    $lineaconcambios = Lineacambio::where('linea_id',$linea->id)->first();
                    if(!$lineaconcambios){                 
                        $lineaconcambios = new Lineacambio([
                            'situacion' => $arrayaprobado[0],
                            'entrada1' => $arrayaprobado[1],
                            'entrada2' => $arrayaprobado[2],
                            'salida1' => $arrayaprobado[3],
                            'salida2' => $arrayaprobado[4],
                            'cuadrante_id' => $linea->cuadrante_id,
                            'empleado_id' => $linea->empleado_id,
                            'ausencia_id' => $linea->ausencia_id,
                            'fecha' => $linea->fecha,
                            'dia' => $linea->dia,
                            ]);
                        #con esto guarda la linea_id en $lineaconcambios
                        $linea->lineacambio()->save($lineaconcambios);
                        #para luego poner el cuadrante con estado AceptadoCambios
                        $cambios = true;
                    } 
                }
            }
            $linea->update([
                'situacion'=> $situacion,
                'entrada1'=> $entrada1,
                'salida1'=> $salida1,
                'entrada2'=> $entrada2,
                'salida2'=> $salida2,
                ]);
            if(count($linea->compensable) && !in_array($linea->situacion,array('FT','VT'))){
                dd('Hay compensable y no es FT o VT. Linea: '.$linea->id);
            }
            if(in_array($linea->situacion, array('FT','VT'))){
                if(count($linea->compensable)){
                    //update
                    $compensable = $linea->compensable;
                    $compensable->nota = $request->{'nota_'.$dia.'_'.$empleado_id};
                    $compensable->save();
                }else{
                    //TO DO:create
                    $compensable = new \App\Compensable;
                    $compensable->nota = $request->{'nota_'.$dia.'_'.$empleado_id};
                    $linea->compensable()->save($compensable);
                }             
            }

        }
        if ($cambios == true) {
            $cuadrante->estado = 'AceptadoCambios';
            $cuadrante->save();
        }
        if ($request->has('cambio_estado')) {
            $nuevo_estado = $request->cambio_estado;
            $cuadrante->estado = $nuevo_estado;
            $cuadrante->save();
        }

        if($request->has('cambio_apertura_dia')){
            $nuevo_estado = substr($request->cambio_apertura_dia, -2);
            $dia = substr($request->cambio_apertura_dia,0,1);
            
            $dia = 'dia_'.$dia;
            $cuadrante->{$dia} = $nuevo_estado;
            $cuadrante->save();
        }
        
        //TO DO: guardar también los cambios en el cuadrante (por ejemplo si se ha cambiado el día de cerrado)
        });
        return is_null($exception) ? 'Cambios guardados' : $exception;
               
    } catch(Exception $e) {
        // return $e;
        return "Error: no se han podido guardar los cambios".$e;
    }
}

public function archivarHorarios ($cuadrante_id){
    $cuadrante = Cuadrante::findOrFail($cuadrante_id);
    if($cuadrante->estado != 'Aceptado'){
        return redirect('home')->with('info','No se ha podido archivar el Horario');
    }
    //TO DO: comprobar que todas las ausencias estén confirmadas
    $cuadrante->estado = 'Archivado';
    $cuadrante->save();

    return redirect ('home');

}

public function desarchivarHorario ($cuadrante_id){

    $cuadrante = Cuadrante::findOrFail($cuadrante_id);
    $cuadrante->estado = 'Aceptado';
    $cuadrante->save();
    return 'Horario desarchivado';
}


public function aceptarHorarios ($cuadrante_id){
  
    $cuadrante = Cuadrante::findOrFail($cuadrante_id);
    switch ($cuadrante->estado) {
        case 'AceptadoCambios':
            # borro las lineacambios y cambio el estado a Aceptado
            $cuadrante->lineacambios()->delete();//no se si esto funcionará
            $cuadrante->estado = 'Aceptado'; 
            $cuadrante->save();
            break;
        case 'Aceptado':
            //TO DO: creo que en este caso mejor mandarlo a home con un aviso
            dd('Este cuadrante ya no está disponible para aceptar. Es posible que otro usuario lo haya aceptado o que esté de nuevo en preparación');
            break;  
        case 'Archivado':
            dd('No debería poderse aceptar un horario que ya está archivado. Revisar código');
            break;
        case 'Pendiente':
            $lineascambiadas = $cuadrante->lineacambios()->get();
            if(count($lineascambiadas)){
                dd('No tendría porque haber lineascambiadas en una plantilla Pendiente');
            }
            $cuadrante->estado = 'Aceptado';
            $cuadrante->save();
            break;
        default:
            dd('En una plantilla con estado NULL, no se debería poder aceptar el horario');
            break;

    }
}

public function rechazarHorarios ($cuadrante_id){

    $cuadrante = Cuadrante::findOrFail($cuadrante_id);

    switch ($cuadrante->estado) {
        case 'AceptadoCambios':
            # de alguna manera habrá que hacerle saber al manager del restaurante que sus cambios han sido rechazados. De momento no hago nada y que se le llame para que lo rectifique
            dd('Hay que comunicarle al encargado que cambie lo que no sea conforme');
            break;
        case 'Pendiente':
            # cambiar estado a NULL
            break;
        
        default:
            dd('Debe haber un error porque este horario no es todavía rechazable');
            # error porque no puede ser que no sea ...
            break;
    }
}

public function mostrarCuadrante($cuadrante_id = NULL)
{
    if(!$cuadrante_id){
        if (Auth::user()->isAdmin()){
            return redirect ('home');
        }
        $ultimo = Cuadrante::where('centro_id', Auth::user()->centro_id)->orderBy('yearsemana','desc')->first();
        if(!$ultimo){
            return view('cuadrantes.geencuadrante');
        }
        return redirect('cuadrante/'.$ultimo->id)->with('info', 'Ultimos horarios grabados');
    }

    if (Auth::user()->isAdmin()) {
        $cuadrante = Cuadrante::where('id',$cuadrante_id)->first();
    }else{
        $cuadrante = Cuadrante::where('id',$cuadrante_id)->where('centro_id', Auth::user()->centro_id)->first();
    }
    
    if(!$cuadrante){
        return redirect('cuadrante');
    }
    $anteriorId = Cuadrante::where('centro_id',$cuadrante->centro_id)->orderBy('yearsemana','desc')->where('yearsemana','<',$cuadrante->yearsemana)->first();
    if($anteriorId){
        $anteriorId = $anteriorId->id;
    }
    $posteriorId = Cuadrante::where('centro_id',$cuadrante->centro_id)->orderBy('yearsemana','asc')->where('yearsemana','>',$cuadrante->yearsemana)->first();
    if($posteriorId){
        $posteriorId = $posteriorId->id;
    }

    $centro_id = $cuadrante->centro_id;
    $empleadosdisponibles = collect();

    if(!$cuadrante->archivado) {
        #TO DO: actualizar las lineas con las ausencias, ver si hay conflictos (por ejemplo cuando es vacaciones pero en la linea pone Vacaciones Trabaja).
        // $empleados = Empleado::where('centro_id',$centro_id)->pluck('id')->toArray();
        //solo los empleados de ese cuadrante
        $empleados = $cuadrante->empleados->unique()->pluck('id')->toArray();
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
                    $fecha_linea = new DateTime($linea->fecha);
                    $fecha_linea = $fecha_linea->format('Y-m-d');
                    if( !in_array($fecha_linea,$intervaloausencia)){
                        $linea->ausencia_id = NULL;
                        $linea->save();
                    }else{
                        if ($ausencia->tipo == 'V' && $linea->situacion == 'VT') {
                            # como es VT, se deja como está.
                        }else{
                            $linea->update([
                            'situacion' => $ausencia->tipo,
                            'entrada1' => null,
                            'salida1' => null,
                            'entrada2' => null,
                            'salida2' => null,
                            ]);
                        }
                    }
                }else{
                    $linea->update([
                    'ausencia_id' => NULL,
                    ]);
                    // if (in_array($linea->sitaucion,$this->situacionesMustAusencia)){
                    if (in_array($linea->sitaucion,$this->situacionesconAusenciaId)){
                        $linea->update([
                        'situacion' => NULL,
                    ]);
                    }    
                }
            }
        }
        //actualizar las lineas con los datos de la tabla de ausencias
        $ausencias = Ausencia::where('fecha_fin','>=',$inicio_semana->toDateTimeString())->where('fecha_inicio','<=',$final_semana->toDateTimeString())->whereIn('empleado_id',$empleados)->get();
        if($ausencias){   
            //TO DO: como $diassemana lo utilizo en mas de 1 sitio, crear la función generateDateRangeWeek ($yearsemana) y simplificar lo siguiente
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
                        // $arrayprueba[] = $fecha_ausencia;
                        $linea = Linea::where('empleado_id',$ausencia->empleado_id)->where('fecha',$fecha_ausencia)->first();
                        
                        if($linea){
                            if($linea->situacion == 'VT' && $ausencia->tipo == 'V'){
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
        $empleadosdisponibles = $this->empleadosdisponibles($cuadrante);
    } #FIN IF ARCHIVADO

//TO DO: en teoría no debe ocurrir, pero cuando no hay lineas en el cuadrante, sale un error de sql
    $lineas = DB::table('lineas')
        ->join('empleados', 'lineas.empleado_id', '=', 'empleados.id')
        ->leftjoin('compensables', 'lineas.id', '=', 'compensables.linea_id')
        ->where('lineas.cuadrante_id','=',$cuadrante->id)
        ->select(
            'empleados.alias AS nombre','empleados.id AS empleado_id', 
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.situacion ELSE NULL END) AS 'situacion1'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN compensables.nota ELSE NULL END) AS 'nota1'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.entrada1 ELSE NULL END) AS 'ELU'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.salida1 ELSE NULL END) AS 'SLU'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.entrada2 ELSE NULL END) AS 'E2LU'"),
            DB::raw("min(CASE WHEN lineas.dia = 1 THEN lineas.salida2 ELSE NULL END) AS 'S2LU'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.situacion ELSE NULL END) AS 'situacion2'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN compensables.nota ELSE NULL END) AS 'nota2'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.entrada1 ELSE NULL END) AS 'EMA'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.salida1 ELSE NULL END) AS 'SMA'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.entrada2 ELSE NULL END) AS 'E2MA'"),
            DB::raw("min(CASE WHEN lineas.dia = 2 THEN lineas.salida2 ELSE NULL END) AS 'S2MA'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.situacion ELSE NULL END) AS 'situacion3'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN compensables.nota ELSE NULL END) AS 'nota3'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.entrada1 ELSE NULL END) AS 'EMI'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.salida1 ELSE NULL END) AS 'SMI'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.entrada2 ELSE NULL END) AS 'E2MI'"),
            DB::raw("min(CASE WHEN lineas.dia = 3 THEN lineas.salida2 ELSE NULL END) AS 'S2MI'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.situacion ELSE NULL END) AS 'situacion4'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN compensables.nota ELSE NULL END) AS 'nota4'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.entrada1 ELSE NULL END) AS 'EJU'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.salida1 ELSE NULL END) AS 'SJU'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.entrada2 ELSE NULL END) AS 'E2JU'"),
            DB::raw("min(CASE WHEN lineas.dia = 4 THEN lineas.salida2 ELSE NULL END) AS 'S2JU'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.situacion ELSE NULL END) AS 'situacion5'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN compensables.nota ELSE NULL END) AS 'nota5'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.entrada1 ELSE NULL END) AS 'EVI'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.salida1 ELSE NULL END) AS 'SVI'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.entrada2 ELSE NULL END) AS 'E2VI'"),
            DB::raw("min(CASE WHEN lineas.dia = 5 THEN lineas.salida2 ELSE NULL END) AS 'S2VI'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.situacion ELSE NULL END) AS 'situacion6'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN compensables.nota ELSE NULL END) AS 'nota6'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.entrada1 ELSE NULL END) AS 'ESA'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.salida1 ELSE NULL END) AS 'SSA'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.entrada2 ELSE NULL END) AS 'E2SA'"),
            DB::raw("min(CASE WHEN lineas.dia = 6 THEN lineas.salida2 ELSE NULL END) AS 'S2SA'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.situacion ELSE NULL END) AS 'situacion0'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN compensables.nota ELSE NULL END) AS 'nota0'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.entrada1 ELSE NULL END) AS 'EDO'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.salida1 ELSE NULL END) AS 'SDO'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.entrada2 ELSE NULL END) AS 'E2DO'"),
            DB::raw("min(CASE WHEN lineas.dia = 0 THEN lineas.salida2 ELSE NULL END) AS 'S2DO'")
            )
        ->groupBy ('empleados.alias','empleados.id')
        ->get();
    // dd($lineas);

    $predefinidos = DB::table('predefinidos')->where('centro_id',$centro_id)->select('id AS value','nombre AS label','entrada1','salida1','entrada2','salida2')->get();
    //crear collection con los alias y con key id (no lo convierto a array porque sino da error en el script de abajo)
    //me parece que $empleados no se está usando. Lo he quitado también del compact
    // $empleados = DB::table('empleados')->where('centro_id',$centro_id)->pluck('alias','id');


    // $empleados_compensar = Empleado::where('centro_id',$centro_id)->whereHas('compensables',function($query){
    //         $query->where('diacompensado', null)->where('pagar',false);      
    //     })
    //         ->with('compensables')
    //         ->withCount(['compensables'])
    //         ->get();
    $empleados_compensar = Empleado::whereHas('contratos',function($query) use($centro_id){
                $query->where('centro_id','=',$centro_id);
            })
            ->whereHas('compensables',function($query){
                $query->where('diacompensado', null)->where('pagar',false);      
            })
            ->with('compensables')
            ->withCount(['compensables'])
            ->get();
    $empleados_compensar = $cuadrante->empleados()
            ->whereHas('compensables',function($query){
                $query->where('diacompensado', null)->where('pagar',false);      
            })->with('compensables')
            ->withCount(['compensables'])->get()->unique();
    $empleados_compensar = $cuadrante->empleados();

    // dd($empleados_compensar);
    $lineasconcambios = $cuadrante->lineacambios()->get();

    $lineasconausencias = DB::table('lineas')
        ->where('cuadrante_id',$cuadrante->id)
        ->where('ausencia_id','>',0)
        ->join('ausencias',function($join){
            $join->on('ausencias.id','lineas.ausencia_id');
        })
        ->select('lineas.empleado_id','lineas.dia',DB::raw("DATE_FORMAT(ausencias.fecha_inicio, '%d-%m-%Y') as fecha_inicio"),DB::raw("DATE_FORMAT(ausencias.finalDay,'%d-%m-%Y') AS finalDay"),DB::raw("CASE WHEN ausencias.nota IS NULL THEN '' ELSE ausencias.nota END AS 'nota'"))
        ->get();
    $comments = $cuadrante->comments;
// DB::raw("CASE WHEN tipo = 'Cobro' THEN importe ELSE NULL END AS 'Cobro'"),
    return view('cuadrantes.detalle',compact('lineas','cuadrante','predefinidos','lineasconcambios','lineasconausencias','empleadosdisponibles','anteriorId','posteriorId','comments','empleados_compensar'));
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
        return redirect('cuadrante/'.$cuadrante->yearsemana); 
    }
    if (Auth::user()->isAdmin()){
        return redirect('home')->with('info','Los encargados son los que tienen que crear los horarios');
    }

    //TO DO: hacer try catch para que lo haga todo o nada      

    try {
        $exception = DB::transaction(function() use ($year,$semana) {

        $centro_id = Auth::user()->centro_id;
        $cuadrante = new Cuadrante;
        $cuadrante->yearsemana = $year.$semana;
        $cuadrante->centro_id = $centro_id;
        $cuadrante->author_id = Auth::user()->id;

        //grabar dia de cierre, en su caso
        $diacierre = Centro::where('id',$centro_id)->firstOrFail()->dia_cierre;
        
        if ( !is_null ( $diacierre ) ){
            $columna = 'dia_'.$diacierre;
            $cuadrante->$columna = 'C';
        }
        //ver si hay algún festivo para esta semana
        //TO DO: falta por poner FC o FA en el dia correspondiente del cuadrante,
        // tal y como está hecho con el día de cierre
        $festivos = DB::table('festivos')->pluck('fecha')->toArray();
        $diassemana = $this->generateDateRangeWeek($cuadrante->yearsemana);
        $festivos_thisweek = [];
        foreach ($festivos as $festivo) {
            if(in_array($festivo,$diassemana)){
                $festivos_thisweek [] = $festivo;
            }
        }

        if(!empty($festivos_thisweek)){
            foreach ($festivos_thisweek as $festivo) {
                $diafestivo = DateTime::createFromFormat('Y-m-d', $festivo);
                $diafestivo = $diafestivo->format('w');
                $columna = 'dia_'.$diafestivo;
                if(Centro::find($centro_id)->abrefestivos == 1){
                    $cuadrante->$columna = 'FA';
                }
                $cuadrante->$columna = 'FC';
            }
        }

        //TO DO: cuando modifique la función addLineas para que en vez de fecha_ini y fecha_fin se le pase $diassemana, entonces puedo borrar las siguientes lineas.
        $date = new Carbon();
        $date->setISODate($year,$semana);
        $fecha_ini = new Carbon($date->startOfWeek()); 
        $fecha_fin = new Carbon($date->endOfWeek());

        $cuadrante->save();
     
        //TO DO: creo que sería mejor que addLineas, en vez de fecha_ini y fecha_fin, se le pasara un array con las fechas. La idea es pasarle el array $diassemana
        $this->addLineas($cuadrante->id,Auth::user()->centro_id,$fecha_ini,$fecha_fin);

        /*incluir dia de cierre como libres y dias festivos*/
        if (!is_null($diacierre)){
            
            $lineas = Linea::where('cuadrante_id',$cuadrante->id)->where('dia',$diacierre)->get();
            foreach ($lineas as $linea) {
                $linea->situacion ='L';
                $linea->save();
            }
        }
        if (!is_null($festivos_thisweek)){
            $lineas = Linea::where('cuadrante_id',$cuadrante->id)->whereIn('fecha',$festivos_thisweek)->get();
            foreach ($lineas as $linea) {
                $linea->situacion ='F';
                $linea->save();
            }
        }
        
        return redirect('cuadrante/'.$cuadrante->id);
        }); #FIN $exception
        return is_null($exception) ? 'Horario creado' : $exception;
    
    } catch(Exception $e) {
        // return $e;
        return "Error: no se ha podido crear el horario".$e;
    }

}

public function addLineas ($cuadrante_id, $centro_id, $fecha_ini, $fecha_fin){
    //seleccionar los empleados que en el intervalo entre $fecha_ini y $fecha_fin tienen algún contrato
    $fecha_ini_format= $fecha_ini->format('Y-m-d');
    $fecha_fin_format = $fecha_fin->format('Y-m-d');
    // dd($fecha_fin);
    
    //TO DO: JUNTAR ESTO CON LA FUNCION EMPLEADOSDISPONIBLES
    $empleados = DB::table('empleados')
        ->join('contratos',function($join) use($fecha_ini_format,$fecha_fin_format){
            $join->on('empleados.id','contratos.empleado_id');
            })
            ->where([
                    ['contratos.centro_id','=',$centro_id],
                    ['contratos.fecha_baja','=',NULL],
                    ['contratos.fecha_alta','<=',$fecha_fin_format],
                    ])
            ->orwhere([
                        ['contratos.centro_id','=',$centro_id],
                        ['contratos.fecha_baja','>=',$fecha_ini_format],
                        ['contratos.fecha_alta','<=',$fecha_fin_format],
                        ])

        ->select('empleados.*','contratos.centro_id AS centro_id','contratos.fecha_baja AS fecha_baja','contratos.fecha_alta AS fecha_alta')
        ->get();

    if(!$empleados){
        // TO DO: lanzar excepción para que no cree nada
        dd('no hay ningún empleado, no se puede crear un horario vacío');
    } 
    //TO DO: prerrellenar las Ausencias. De momento no hace falta porque en cuanto muestre el cuadrante ya se modifican las lineas
    while ($fecha_ini <= $fecha_fin) {
        foreach ($empleados as $empleado) {
            $linea = new Linea;
            $linea->cuadrante_id = $cuadrante_id;
            $linea->fecha = $fecha_ini;
            $linea->dia = $fecha_ini->dayOfWeek;
            $linea->empleado_id = $empleado->id;          
            $linea->save();   
            // dd($linea);  
        }
        //borro esta linea porque date convierte a string y entonces no le puedo aplicar dayOfWeek
        // $fecha_ini = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ini)));
        $fecha_ini = $fecha_ini->addDay();
    }
}
public function addLineas2 ($cuadrante_id, $centro_id, $fecha_ini, $fecha_fin){
    //seleccionar los empleados que en el intervalo entre $fecha_ini y $fecha_fin tienen algún contrato
    $fecha_ini_format= $fecha_ini->format('Y-m-d');
    $fecha_fin_format = $fecha_fin->format('Y-m-d');
    // dd($fecha_fin);
    
    //TO DO: JUNTAR ESTO CON LA FUNCION EMPLEADOSDISPONIBLES
    $empleados = DB::table('empleados')
        ->join('contratos',function($join) use($fecha_ini_format,$fecha_fin_format){
            $join->on('empleados.id','contratos.empleado_id');
            })
            ->where([
                    ['contratos.centro_id','=',$centro_id],
                    ['contratos.fecha_baja','=',NULL],
                    ['contratos.fecha_alta','<=',$fecha_fin_format],
                    ])
            ->orwhere([
                        ['contratos.centro_id','=',$centro_id],
                        ['contratos.fecha_baja','>=',$fecha_ini_format],
                        ['contratos.fecha_alta','<=',$fecha_fin_format],
                        ])



        // ->select('empleados.*','contratos.centro_id AS centro_id','contratos.fecha_baja AS fecha_baja','contratos.fecha_alta AS fecha_alta')
        ->select('empleados.*','contratos.centro_id AS centro_id',
            DB::raw("CASE WHEN contratos.fecha_baja <  ? THEN contratos.fecha_baja ELSE ? END AS 'fecha_fin'"),
            DB::raw("CASE WHEN contratos.fecha_alta >  ? THEN contratos.fecha_alta ELSE ? END AS 'fecha_ini'"),
            'contratos.fecha_alta AS fecha_alta')
        ->setBindings([$fecha_fin_format, $fecha_fin_format, $fecha_ini_format, $fecha_ini_format])
        ->get();
    // dd($empleados);
    // $empleados_parciales = $empleados->where('fecha_alta','>','fecha_ini_format')->orwhere('fecha_baja','<','fecha_fin_format')->get();
    
// TO DO: DESARROLLAR LO SIGUIENTE. LA IDEA ES QUE LAS LINEAS CUANDO NO HAY CONTRATO TODAVIA, NO SEAN RELLENABLES, POR EJEMPLO QUE SE LES PONGA UNA X
    // if($empleados_parciales){
    //     throw new exception ('hay empleados parciales');
    // }
    // throw new exception ('no hay empleados parciales');

    // $empleados = Empleado::activo()->where('centro_id',$centro_id)->get();
    if(!$empleados){
        // TO DO: lanzar excepción para que no cree nada
        dd('no hay ningún empleado, no se puede crear un horario vacío');
    } 
    //TO DO: prerrellenar las Ausencias. De momento no hace falta porque en cuanto muestre el cuadrante ya se modifican las lineas
    // while ($fecha_ini <= $fecha_fin) {
    //     foreach ($empleados as $empleado) {
    //         $linea = new Linea;
    //         $linea->cuadrante_id = $cuadrante_id;
    //         $linea->fecha = $fecha_ini;
    //         $linea->dia = $fecha_ini->dayOfWeek;
    //         $linea->empleado_id = $empleado->id;          
    //         $linea->save();   
    //         // dd($linea);  
    //     }
    //     //borro esta linea porque date convierte a string y entonces no le puedo aplicar dayOfWeek
    //     // $fecha_ini = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ini)));
    //     $fecha_ini = $fecha_ini->addDay();
    // }


    foreach ($empleados as $empleado) {
        while ($empleado->fecha_ini <= $empleado->fecha_fin) {
            $linea = new Linea;
            $linea->cuadrante_id = $cuadrante_id;
            $linea->fecha = $fecha_ini;
            $linea->dia = $fecha_ini->dayOfWeek;
            $linea->empleado_id = $empleado->id;          
            $linea->save();   
            $fecha_ini = $fecha_ini->addDay();  
        }
        //borro esta linea porque date convierte a string y entonces no le puedo aplicar dayOfWeek
        // $fecha_ini = date ("Y-m-d", strtotime("+1 day", strtotime($fecha_ini)));
        
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
public function generateDateRangeWeek($yearsemana)
{
    $year = substr($yearsemana,0,4);
    $semana = substr($yearsemana,-2,2);
    $date = new Carbon;
    $date = new Carbon($date->setISODate($year,$semana));
    $inicio_semana = new Carbon($date->startOfWeek());
    $final_semana = new Carbon($date->endOfWeek());
    $dates = $this->generateDateRange($inicio_semana, $final_semana);
    return $dates;

}
/**
 * Funcion que devuelve los dias pendientes de compensar de los empleados de un cuadrante
 */
public function compensablesdisponibles($cuadrante){
    $empleados = $cuadrante->empleados;
    #ver si los empleados tienes compensables pendientes.

    //TO DO: el objetivo sera llegar a un array con key el código de empleado y value el primer día compensable (mas adelante habrá que hacer que salgan todos los días compensables y que se pueda elegir)
    $compensablesdisponibles = [];

    // $compensablesdisponibles = DB::table('compensables')
    //     ->join()

}



public function empleadosdisponibles ($cuadrante)
{
    $yearsemana = $cuadrante->yearsemana;
    $year = substr($yearsemana,0,4);
    $semana = substr($yearsemana,-2,2);
    $date = new Carbon();
    $date->setISODate($year,$semana);
    $fecha_ini = new Carbon($date->startOfWeek()); 
    $fecha_fin = new Carbon($date->endOfWeek());
    $fecha_ini_format = $fecha_ini->format('Y-m-d');
    $fecha_fin_format = $fecha_fin->format('Y-m-d');
    $centro_id = $cuadrante->centro_id;

    $empleadosdisponibles = DB::table('empleados')
        ->join('contratos',function($join) use($fecha_ini_format,$fecha_fin_format,$centro_id){
            $join->on('empleados.id','=','contratos.empleado_id')
                ->where('contratos.centro_id','=',$centro_id);
            })            
        ->where(function($query) use ($fecha_fin_format,$fecha_ini_format){
            $query->where([
            ['contratos.fecha_baja','=',NULL],
            ['contratos.fecha_alta','<=',$fecha_fin_format],
            ]);
            $query->orwhere([
            ['contratos.fecha_baja','>=',$fecha_ini_format],
            ['contratos.fecha_alta','<=',$fecha_fin_format],
            ]);

        })          
        ->whereNotIn('empleados.id', function($q) use($cuadrante){
                $q->select('empleado_id')
                    ->from('lineas')
                    ->where('cuadrante_id',$cuadrante->id);
            })
        ->select('empleados.id','empleados.alias') 
        ->get();



    // $empleadosdisponibles = DB::table('empleados')
    //     ->join('contratos',function($join) use($fecha_ini,$fecha_fin){
    //         $join->on('empleados.id','contratos.empleado_id')
    //             ->where('fecha_baja',NULL)
    //             ->orwhere([
    //                     ['fecha_baja','>=',$fecha_ini],
    //                     ['fecha_alta','<=',$fecha_fin],
    //                     ]);
    //         })
    //     ->where('contratos.centro_id',$centro_id)
    //     ->whereNotIn('empleados.id', function($q) use($cuadrante){
    //             $q->select('empleado_id')
    //                 ->from('lineas')
    //                 ->where('cuadrante_id',$cuadrante->id);
    //         })
    //     ->select('empleados.*','contratos.centro_id AS centro_id')->get();

// dd($empleadosdisponibles);


    return $empleadosdisponibles;

}

/**
 * TO DO: unificar esta función con la de addlineas
 */
public function añadirempleado ($empleado_id,$cuadrante_id)
{
    
try {
    $exception = DB::transaction(function() use ($empleado_id,$cuadrante_id) {


    
// TO DO: DESARROLLAR LO SIGUIENTE. LA IDEA ES QUE LAS LINEAS CUANDO NO HAY CONTRATO TODAVIA, NO SEAN RELLENABLES, POR EJEMPLO QUE SE LES PONGA UNA X. PARA UNIFICAR, HABRA QUE PREVIAMENTE UNIFICAR AÑADIREMPLEADO CON ADDLINEAS...SI SE PUEDE
    // $empleado_parcial = Empleado::find($empleado_id)
    //     ->whereHas('contratos', function ($query){
    //         $query->where()
    //         'fecha_alta','>','fecha_ini_format')->orwhere('fecha_baja','<','fecha_fin_format')->get();
    // }
    // if($empleados_parciales){
    //     throw new exception ('hay empleados parciales');
    // }
    // throw new exception ('no hay empleados parciales');


    $cuadrante = Cuadrante::find($cuadrante_id);
    $yearsemana = $cuadrante->yearsemana;
    $year = substr($yearsemana,0,4);
    $semana = substr($yearsemana,-2,2);
    $date = new Carbon();
    $date->setISODate($year,$semana);
    $fecha_ini = new Carbon($date->startOfWeek()); 
    $fecha_fin = new Carbon($date->endOfWeek());
    
    $lineas = collect();
    while ($fecha_ini <= $fecha_fin) {
            $linea = new Linea;
            $linea->cuadrante_id = $cuadrante_id;
            $linea->fecha = $fecha_ini->format('Y-m-d');
            $linea->dia = $fecha_ini->dayOfWeek;
            $linea->empleado_id = $empleado_id;          
            $linea->save();
            $lineas->push($linea);     
            $fecha_ini = $fecha_ini->addDay();
    }
    $festivos = DB::table('festivos')->pluck('fecha')->toArray();
    $diassemana = $this->generateDateRangeWeek($cuadrante->yearsemana);
    $festivos_thisweek = [];
    foreach ($festivos as $festivo) {
        if(in_array($festivo,$diassemana)){
            $festivos_thisweek [] = $festivo;
        }
    }

            // $lineas = Linea::where('cuadrante_id',$cuadrante->id)->whereIn('fecha',$festivos_thisweek)->get();

    if (!is_null($festivos_thisweek)){
        $lineas = $lineas->whereIn('fecha',$festivos_thisweek)->all();
        foreach ($lineas as $linea) {
            $linea->situacion ='F';
            $linea->save();


        }

    }
    // dd($lineas);
    }); #FIN $exception
    
    return is_null($exception) ? 'Añadido' : $exception;
    
} catch(Exception $e) {
        // return $e;
        return "Error: no se ha podido añadir el empleado".$e;
}


}

public function eliminarempleado ($empleado_id,$cuadrante_id)
{
    $cuadrante = Cuadrante::find($cuadrante_id);
    $borrarlineas = $cuadrante->lineas()->where('empleado_id',$empleado_id)->delete();
    return 'eliminado';


}

}
