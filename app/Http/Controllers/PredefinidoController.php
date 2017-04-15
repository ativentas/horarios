<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Predefinido;

class PredefinidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate($request, [
            'nombre' => 'required|min:3|max:25',
            'entrada1' => 'required'
            'salida1' => 'required'
        ]);
        $predefinido = new Predefinido;
        $predefinido->nombre = $request->nombre;
        $predefinido->entrada1 = $request->entrada1;
        $predefinido->salida1 = $request->salida1;
        $predefinido->entrada2 = $request->entrada2;
        $predefinido->salida2 = $request->salida2;

        $predefinido->save();
        return 'Predefinido creado ';
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
        $predefinido = Predefinido::find($id);
  
        $this->validate($request, [
        'nombre' => 'required|min:3|max:25', 
        'entrada1' => 'required', 
        'salida1' => 'required', 
        ]);
        $predefinido->nombre = $request->nombre;
        $predefinido->entrada1 = $request->entrada1;
        $predefinido->salida1 = $request->salida1;
        $predefinido->entrada2 = $request->entrada2;
        $predefinido->salida2 = $request->salida2;

        $predefinido->save();
        
        return 'Predefinido modificado';
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
