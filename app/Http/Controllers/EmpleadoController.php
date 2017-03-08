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

    public function __construct()
    {
        $this->middleware('auth');
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
        $this->validate($request, [
        'alias' => 'required|min:4|max:15|unique:empleados',
        'nombre' => 'required|min:8|unique:empleados,nombre_completo',   
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
        $cuadrante=Cuadrante::find($cuadrante_id);
        if(!$cuadrante){
            $date = new DateTime();
            $yearsemana = $date->format("YW");
            //buscar el cuadrante actual
            $cuadrante = Cuadrante::where('yearsemana','<=',$yearsemana)->where('centro_id',$empleado->centro_id)->orderBy('yearsemana','desc')->first();
        }
        $beginyear = substr($yearsemana, 0,4).'-01-01';
        $endyear = substr($yearsemana,0,4).'-12-31';
        $lineas = Linea::where('cuadrante_id',$cuadrante->id)->where('empleado_id',$id)->whereHas('cuadrante', function ($query) {
            $query->where('estado', '<>', NULL);
            })->get();

        $resumen = DB::table('lineas')
                ->join('cuadrantes','lineas.cuadrante_id','cuadrantes.id')
                ->where('empleado_id',$id)
                ->where('cuadrantes.estado','<>',NULL)             
                ->select(
                    DB::raw("count(CASE WHEN lineas.situacion = 'V' THEN lineas.situacion ELSE NULL END) AS 'Vacaciones'"),
                    DB::raw("count(CASE WHEN lineas.situacion IN ('B','AJ','AN') THEN lineas.situacion ELSE NULL END) AS 'Otras'")
                    )->get();
        // dd($resumen);

        $ausenciasyear = Ausencia::where('empleado_id',$id)->where('finalDay','>=',$beginyear)->where('fecha_inicio','<=',$endyear)->get();
        return view('empleados.detalle', compact('lineas','empleado','ausenciasyear','resumen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        dd('aqui actualizo el estado entre otras cosas');
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
