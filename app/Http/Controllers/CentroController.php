<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Centro;

class CentroController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','isAdmin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $centros = Centro::all();
        return view ('centros.index',compact('centros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dias = [1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',0=>'Domingo'];
        return view('centros.create',compact('dias'));
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
        'nombre' => 'required|min:3|max:25',
        ]);

        $centro = new Centro;
        $centro->nombre = $request->nombre;
        $centro->dia_cierre = $request->dia_cierre;
        $centro->abrefestivos = $request->abrefestivos?:'0';
        $centro->empresa = $request->empresa;

        $centro->save();
    
        return redirect()->back()->with('info', 'Nuevo Departamento creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dias = [1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',0=>'Domingo'];

        $centro = Centro::find($id);
        $predefinidos = $centro->predefinidos()->get();
        return view('centros.edit',compact('centro','predefinidos','dias'));
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
        $centro = Centro::find($id);
        if ($request->has('estado')) {
            $centro->activo=$request->estado;
            $centro->save();
        }else {  
            $this->validate($request, [
            'nombre' => 'required|min:3|max:25', 
            ]);
            $centro->nombre = $request->nombre;
            $centro->empresa = $request->empresa;
            $centro->dia_cierre = $request->dia_cierre;
            $centro->abrefestivos = $request->abrefestivos?:0;

            $centro->save();
        }
        return redirect()->route('centros.index')->with('info','Departamento modificado');
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
