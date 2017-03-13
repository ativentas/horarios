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
        $query = Empleado::orderBy('centro_id')->orderBy('alias');
        $query = \Request::has('centro') ? $query->where('centro_id',\Request::input('centro')) : $query;

        $empleados = $query->get(); 

        $centros = Centro::all();
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
        'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id,centro_id,'.$request->centro,
        'nombre' => 'required|min:8|unique:empleados,nombre_completo,'.$id.',id,centro_id,'.$request->centro,   
        'centro' => 'required',   
        ]);

        $empleado = new Empleado;
        $empleado->alias = $request->alias;
        $empleado->nombre_completo = $request->nombre;
        $empleado->centro_id = $request->centro;

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
        $empleado_anterior = Empleado::where('centro_id', $empleado->centro_id)->orderBy('alias','desc')->where('alias','<',$empleado->alias)->first();
        if($empleado_anterior){
            $empleado_anterior = $empleado_anterior->id;
        }
        $empleado_posterior = Empleado::where('centro_id', $empleado->centro_id)->orderBy('alias','asc')->where('alias','>',$empleado->alias)->first();
        if($empleado_posterior){
            $empleado_posterior = $empleado_posterior->id;
        }
        $cuadrante=Cuadrante::find($cuadrante_id);
        if(!$cuadrante){
            $date = new DateTime();
            $yearsemana = $date->format("YW");
            //buscar el cuadrante actual
            $cuadrante = Cuadrante::where('estado','<>',null)->where('yearsemana','<=',$yearsemana)->where('centro_id',$empleado->centro_id)->orderBy('yearsemana','desc')->first();
        }
        //para que cuando no hay nigún cuadrante, tengamos un valor de yearsemana para calcular el resto de variables
        $cuadrante_id = NULL;
        if($cuadrante){
            $yearsemana = $cuadrante->yearsemana;
            $cuadrante_id = $cuadrante->id;
        }
        $anteriorId = Cuadrante::where('centro_id',$empleado->centro_id)->orderBy('yearsemana','desc')->where('yearsemana','<',$yearsemana)->first();
        if($anteriorId){
            $anteriorId = $anteriorId->id;
        }
        $posteriorId = Cuadrante::where('centro_id',$empleado->centro_id)->orderBy('yearsemana','asc')->where('yearsemana','>',$yearsemana)->first();
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

        $otraslineasacum = $query->whereIn('situacion',array('AN','AJ','B','BP'))->count();
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
        $centros=Centro::all();
        return view('empleados.edit',compact('empleado','centros')); 
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
            'alias' => 'required|min:3|max:15|unique:empleados,alias,'.$id.',id,centro_id,'.$request->centro,
            'nombre' => 'required|min:8|unique:empleados,nombre_completo,'.$id.',id,centro_id,'.$request->centro,   
            'centro' => 'required',   
            ]);
            $empleado=Empleado::find($id);
            $empleado->alias = $request->alias;
            $empleado->nombre_completo = $request->nombre;
            $empleado->centro_id = $request->centro;
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
}
