<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuadrante;
use App\Ausencia;
use App\Centro;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function yearsemana($fecha)
    {
        $year = Carbon::parse($fecha)->startOfWeek()->year;
        $semana = Carbon::parse($fecha)->weekOfYear;
        $semana = sprintf("%02d", $semana);
        return $year.$semana;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isadmin = Auth::user()->isAdmin();
        switch ($isadmin) {
            case true:
                $cuadrantes = Cuadrante::whereIn('estado',array('Pendiente','AceptadoCambios'))->get();                
                $completados = Cuadrante::whereIn('estado',array('Aceptado'))->orderBy('yearsemana','asc')->get();
                $ausencias = Ausencia::where('estado','Pendiente')->get();
                break;
            
            default:
                $yearsemana = $this->yearsemana(date('Y-m-d'));
                $cuadrantes = Cuadrante::where('centro_id', Auth::user()->centro_id)->where('yearsemana','>=',$yearsemana)->get();
                $completados = collect();
                $ausencias = Centro::find(Auth::user()->centro_id)->ausencias()->where('estado','Pendiente')->orderBy('fecha_inicio')->get();
                break;
        }
        return view('home',compact('cuadrantes','completados','ausencias'));
    }
}
