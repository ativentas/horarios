<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;
use App\Empleado;
use App\Ausencia;
use App\Cuadrante;
use App\Linea;


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
        $empleados = Empleado::all();
        return view('empleados.index',compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
                    DB::raw("count(CASE WHEN lineas.situacion = 'B' THEN lineas.situacion ELSE NULL END) AS 'Otras'"),
                    )->get();
        dd($resumen);

        $ausenciasyear = Ausencia::where('empleado_id',$id)->where('finalDay','>=',$beginyear)->where('fecha_inicio','<=',$endyear)->get();
        return view('empleados.detalle', compact('lineas','empleado','ausenciasyear'));
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
        //
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
