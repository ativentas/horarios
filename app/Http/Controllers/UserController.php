<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Centro;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::normal()->get();
        return view('users.index',compact('users'));
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
        $user = User::find($id);
        $centros = Centro::all();
        return view('users.edit', compact('user','centros'));        
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
            $user = User::find($id);
            $user->activo=$request->estado;
            $user->save();
        }else {  
            $this->validate($request, [
            'nombre' => 'required|min:3|max:15',
            'email' => 'required' 
            ]);
            $user = User::find($id);
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->centro_id = $request->centro;

            $user->save();
        }
        return redirect()->route('users.index')->with('info','Usuario modificado');
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
