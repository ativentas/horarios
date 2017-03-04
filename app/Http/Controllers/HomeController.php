<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuadrante;
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
                $completados = Cuadrante::whereIn('estado',array('Aceptado','Archivado'))->orderBy('yearsemana','asc')->get();
                break;
            
            default:
                $yearsemana = $this->yearsemana(date('Y-m-d'));
                $cuadrantes = Cuadrante::where('centro_id', Auth::user()->centro_id)->where('yearsemana','>=',$yearsemana)->get();
                $completados = Cuadrante::where('centro_id', Auth::user()->centro_id)->where('yearsemana','<',$yearsemana)->orderBy('yearsemana','desc')->get();
                
                break;
        }
        return view('home',compact('cuadrantes','completados'));
    }
}
