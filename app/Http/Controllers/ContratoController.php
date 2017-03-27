<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;
use DateTime;

class ContratoController extends Controller
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
        //
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
            'alta' => 'required',
            'centro' => 'required'
        ]);
        $contrato = new Contrato;
        $contrato->empleado_id = $request->empleado_id;
        $contrato->fecha_alta = $this->change_date_format($request->alta);
        if($request->has('baja')){
            $contrato->fecha_baja = $this->change_date_format($request->baja);
        }
        $contrato->centro_id = $request->centro;
        $contrato->save();
        return 'contrato creado ';

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
        $this->validate($request, [
        'alta' => 'required', 
        'centro' => 'required',   
        ]);
        $contrato = Contrato::find($id);
        $contrato->fecha_alta = $this->change_date_format($request->alta);
        if($request->has('baja')){
            $contrato->fecha_baja = $this->change_date_format($request->baja);
        }
        $contrato->centro_id = $request->centro;
        $contrato->save();
        return 'contrato modificado';
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
