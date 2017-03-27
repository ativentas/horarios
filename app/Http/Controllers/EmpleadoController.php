<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;
use App\Empleado;
use App\Ausencia;
use App\Cuadrante;
use App\Linea;
use App\Centro;


class EmpleadoController extends Controller
{

    private $hoy;
    public function __construct()
    {
        $this->middleware('auth');
        $this->hoy = new Datetime();
        $this->hoy = $this->hoy->format('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hoy = new Datetime();
        $hoy = $hoy->format('Y-m-d');

        // $query = Empleado::orderBy('centro_id')->orderBy('alias');
        // $query = \Request::has('centro') ? $query->where('centro_id',\Request::input('centro')) : $query;

        // $empleados = $query->with('centro','contrato_actual')->get();
        $centros = Centro::all();

        $query = DB::table('empleados')
            ->leftjoin('contratos', function($join) use ($hoy){
                $join->on('empleados.id','=','contratos.empleado_id')
                    ->where([
                        ['fecha_baja',NULL],
                        ['fecha_alta','<=',$hoy],])
                    ->orWhere('fecha_baja','>=',$hoy);
            })->leftjoin('centros','contratos.centro_id','=','centros.id')
            ->select('empleados.*','centros.id AS centro_id','centros.nombre AS centro_nombre',
                DB::RAW("DATE_FORMAT(contratos.fecha_alta,'%d-%m-%Y') AS 'fecha_alta'"),
                DB::RAW("DATE_FORMAT(contratos.fecha_baja,'%d-%m-%Y') AS 'fecha_baja'")
                )->orderBy('centros.nombre','desc')->orderBy('alias','asc');
            
        $query = \Request::has('centro') ? $query->where('centro_id',\Request::input('centro')) : $query;  
        $empleados = $query->get();



            // dd($empleados);
        return view('empleados.index',compact('empleados','centros'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centros = Centro::all();
        return view('empleados.create',compact('centros'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!isset($id)){
            $id = 'NULL';
        }
        $this->validate($request, [
        // 'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id,centro_id,'.$request->centro,
        'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id',
        // 'nombre' => 'required|min:8|unique:empleados,nombre_completo,'.$id.',id,centro_id,'.$request->centro,   
        // 'apellidos' => 'unique:empleados,apellidos,'.$id.',id,nombre_completo,'.$request->nombre.',centro_id,'.$request->centro,
        'apellidos' => 'unique:empleados,apellidos,'.$id.',id,nombre_completo,'.$request->nombre
        ]);

        $empleado = new Empleado;
        $empleado->alias = $request->alias;
        $empleado->nombre_completo = $request->nombre;
        $empleado->apellidos = $request->apellidos;

        $empleado->save();
    
        return redirect()->back()->with('info', 'Nuevo empleado registrado');
    }

    /**
     * Display the specified resource.
     * Aqui se muestra 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$cuadrante_id=NULL)
    {
        $empleado = Empleado::findOrFail($id);
        $centro_id = $empleado->centro[0]->id;
        $empleado_anterior = Empleado::whereHas('centro',function($query) use($centro_id) {
            $query->where('centros.id',$centro_id);
            })->orderBy('alias','desc')->where('alias','<',$empleado->alias)->first();
        if($empleado_anterior){
            $empleado_anterior = $empleado_anterior->id;
        }
        $empleado_posterior = Empleado::whereHas('centro',function($query) use($centro_id) {
            $query->where('centros.id',$centro_id);
            })->orderBy('alias','asc')->where('alias','>',$empleado->alias)->first();

        if($empleado_posterior){
            $empleado_posterior = $empleado_posterior->id;
        }
        $cuadrante=Cuadrante::find($cuadrante_id);
        if(!$cuadrante){
            $date = new DateTime();
            $yearsemana = $date->format("YW");
            //buscar el cuadrante actual
            $cuadrante = Cuadrante::where('estado','<>',null)->where('yearsemana','<=',$yearsemana)->where('centro_id',$centro_id)->orderBy('yearsemana','desc')->first();
        }
        //para que cuando no hay nigún cuadrante, tengamos un valor de yearsemana para calcular el resto de variables
        $cuadrante_id = NULL;
        if($cuadrante){
            $yearsemana = $cuadrante->yearsemana;
            $cuadrante_id = $cuadrante->id;
        }
        $anteriorId = Cuadrante::where('centro_id',$centro_id)->orderBy('yearsemana','desc')->where('yearsemana','<',$yearsemana)->first();
        if($anteriorId){
            $anteriorId = $anteriorId->id;
        }
        $posteriorId = Cuadrante::where('centro_id',$centro_id)->orderBy('yearsemana','asc')->where('yearsemana','>',$yearsemana)->first();
        if($posteriorId){
            $posteriorId = $posteriorId->id;
        }

        $year = substr($yearsemana,0,4);
        $beginyear = $year.'-01-01';
        $endyear = $year.'-12-31';
        $lineas = Linea::where('cuadrante_id',$cuadrante_id)->where('empleado_id',$id)->whereHas('cuadrante', function ($query) {
            $query->where('estado', '<>', NULL);
            })->get();

        $query = Linea::where('empleado_id',$id)->whereBetween('fecha',[$beginyear,min($endyear,$this->hoy)])->whereHas('cuadrante', function ($restrict) {
            $restrict->where('estado', '<>', NULL);})->get();

        $otraslineasacum = $query->whereIn('situacion',array('AN','AJ','B','BP','PR'))->count();
        $vaclineasacum = $query->where('situacion','V')->count();
        $ausenciasyear = Ausencia::where('empleado_id',$id)->where('finalDay','>=',$beginyear)->where('fecha_inicio','<=',$endyear)->get();
        return view('empleados.detalle', compact('lineas','empleado','ausenciasyear','vaclineasacum','otraslineasacum','year','cuadrante','anteriorId','posteriorId','empleado_anterior','empleado_posterior'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empleado = Empleado::find($id);
        $centros = Centro::all();
        $contrato_actual = $empleado->contrato_actual->first();
        $contratos_anteriores = DB::table('contratos')
            ->leftjoin('centros','centros.id','=','contratos.centro_id')
            ->where('empleado_id','=',$id)
            ->where('fecha_baja','!=','')
            ->where('fecha_baja','<',$this->hoy)
            ->select('contratos.*','centros.nombre as centro_nombre')
            ->get();
        // dd($contratos_anteriores);
        return view('empleados.edit',compact('empleado','centros','contrato_actual','contratos_anteriores')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->has('estado')) {
            $empleado=Empleado::find($id);
            $empleado->activo=$request->estado;
            $empleado->save();
        }else {  
            $this->validate($request, [
            // 'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id,centro_id,'.$request->centro,
            'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id',
            // 'nombre' => 'required|min:3|unique:empleados,nombre_completo,'.$id.',id,centro_id,'.$request->centro,
            // 'apellidos' => 'unique:empleados,apellidos,'.$id.',id,nombre_completo,'.$request->nombre.',centro_id,'.$request->centro,
            'apellidos' => 'unique:empleados,apellidos,'.$id.',id,nombre_completo,'.$request->nombre   
            ]);
            $empleado=Empleado::find($id);
            $empleado->alias = $request->alias;
            $empleado->nombre_completo = $request->nombre;
            $empleado->apellidos = $request->apellidos;

            $empleado->save();
        }
        return redirect()->route('empleados.index')->with('info','Empleado modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function change_date_format($date)
    {
        $day = DateTime::createFromFormat('d-m-Y', $date);
        // dd($day);
        return $day->format('Y-m-d');
    }
}
