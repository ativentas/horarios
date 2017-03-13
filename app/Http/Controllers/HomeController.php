<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuadrante;
use App\Ausencia;
use App\Centro;
use App\Comment;
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
                $arch_con_notaspdtes = Cuadrante::whereHas('comments', function ($query) {
                        $query->where('resuelto',0);
                    })->where('estado','Archivado')->get();
                $otras_notaspdtes = collect();
                foreach ($arch_con_notaspdtes as $archivado) {
                    $notas = $archivado->comments()->get();
                    foreach ($notas as $nota) {
                        $otras_notaspdtes->push($nota);
                    }
                    
                }
                // dd($otras_notaspdtes);
                $ausencias = Ausencia::where('estado','Pendiente')->get();
                $notasC_pdtes = Comment::has('cuadrante')->where('resuelto',0)->orderBy('updated_at','desc')->get();
                $notasA_pdtes = Comment::has('ausencia')->where('resuelto',0)->orderBy('updated_at','desc')->get();
                break;
            
            default:
                $yearsemana = $this->yearsemana(date('Y-m-d'));
                //TO DO: PONER CUANTAS SEMANAS ATRAS PUEDE VER EL ENCARGADO EN EL ARCHIVO DE CONFIGURACION. CADA EMPRESA PUEDE TENER UN VALOR DISTINTO, TB SE PUEDE HACER UN NUEVO CAMPO EN LA BBDD DE USUARIOS
                $cuadrantes = Cuadrante::where('centro_id', Auth::user()->centro_id)->where('yearsemana','>=',$yearsemana-100)->get();
                $completados = collect();
                $ausencias = Centro::find(Auth::user()->centro_id)->ausencias()->where('estado','Pendiente')->orderBy('fecha_inicio')->get();
                break;
        }
        return view('home',compact('cuadrantes','completados','ausencias','notasC_pdtes','notasA_pdtes','otras_notaspdtes'));
    }
}
