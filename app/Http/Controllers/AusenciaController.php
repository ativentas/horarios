<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ausencia;
use App\Empleado;
use DateTime;
use Carbon\Carbon;
use Auth;

class AusenciaController extends Controller
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
		$data = [
			'page_title' => 'Listado',
			'ausencias'	 => Ausencia::orderBy('estado')->orderBy('fecha_inicio')->get(),
		];
		return view('ausencias/list', $data);
    }
    
    public function listadoVacaciones()
    {
    	$data = [
			'page_title' => 'Listado',
			'ausencias'	 => Ausencia::where('empleado_id',Auth::user()->id)->orderBy('estado')->orderBy('fecha_inicio')->get(),
		];
    	return view('ausencias.list',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
		$data = [
			'empleados' => Empleado::where('centro_id',Auth::user()->centro_id)->get(),
		];
		return view('ausencias/nieuwausencia', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$centro_id = Auth::user()->centro_id;
		$this->validate($request, [
			'empleado_id'	=> 'required',
			'tipo' => 'required',
			'time'	=> "required|duration|available:$request->empleado_id|horarios_cerrados:$centro_id"

		]);
		
		$time = explode(" - ", $request->input('time'));
		$empleado_id = $request->input('empleado_id');
		$empleado = Empleado::where('id',$empleado_id)->first();

		$ausencia 					= new Ausencia;
		$ausencia->empleado_id		= $empleado->id;
		$ausencia->alias 			= $empleado->alias;
		$ausencia->tipo 			= $request->input('tipo');
		$ausencia->fecha_inicio		= $this->change_date_format($time[0]);
		$ausencia->finalDay 		= $this->change_date_format($time[1]);
		$format = 'd/m/Y';
		$finalDay = Carbon::createFromFormat($format,$time[1])->startOfDay()->addDay()->format($format);
		$ausencia->fecha_fin = $this->change_date_format($finalDay);
		$ausencia->allDay = 1;

		$first_date = new DateTime($ausencia->fecha_inicio);
		$second_date = new DateTime($ausencia->fecha_fin);

		// $diasformateados = $this->format_interval($first_date->diff($second_date));	 
		$intervalo = $first_date->diff($second_date);	

		$ausencia->dias = $intervalo->format("%a");
		$ausencia->save();	

		$request->session()->flash('success', 'Guardado!');
		// if (isset($_POST['solicitudEmpleado'])){
		// 	return redirect()->back();
		// }
		return redirect('ausencias/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$ausencia = Ausencia::findOrFail($id);
		
		$first_date = new DateTime($ausencia->fecha_inicio);
		$second_date = new DateTime($ausencia->fecha_fin);

		$difference = $first_date->diff($second_date);

        $data = [
			'page_title' 	=> $ausencia->tipo,
			'ausencia'		=> $ausencia,
			'duration'		=> $this->format_interval($difference)
		];
		
		return view('ausencias/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ausencia = Ausencia::findOrFail($id);
		$ausencia->fecha_inicio =  $this->change_date_format_fullcalendar($ausencia->fecha_inicio);
		$ausencia->fecha_fin =  $this->change_date_format_fullcalendar($ausencia->fecha_fin);
		$ausencia->finalDay = $this->change_date_format2($ausencia->finalDay);
        $data = [
			'page_title' 	=> 'Edit '.$ausencia->tipo,
			'ausencia'		=> $ausencia,
		];
		return view('ausencias/edit', $data);
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
			'empleado_id'	=> 'required|max:15',
			'tipo' => 'required',
			'time'	=> 'required|duration'
		]);
		
		$time = explode(" - ", $request->input('time'));
		
		$ausencia 				= Ausencia::findOrFail($id);
		$ausencia->empleado_id	= $request->input('empleado_id');
		$ausencia->tipo 		= $request->input('tipo');
		$ausencia->fecha_inicio = $this->change_date_format($time[0]);
		$ausencia->finalDay 	= $this->change_date_format($time[1]);
		$format = 'd/m/Y';
		$finalDay = Carbon::createFromFormat($format,$time[1])->startOfDay()->addDay()->format($format);
		$ausencia->fecha_fin = $this->change_date_format($finalDay);
		
		$first_date = new DateTime($ausencia->fecha_inicio);
		$second_date = new DateTime($ausencia->fecha_fin);

		// $diasformateados = $this->format_interval($first_date->diff($second_date));	 
		$intervalo = $first_date->diff($second_date);	

		$ausencia->dias = $intervalo->format("%a");

		$ausencia->save();

		return redirect('ausencias');
    }
    public function confirmarVacaciones($id)
    {
		$ausencia = Ausencia::findOrFail($id);
		$ausencia->estado = 'Confirmado';
		$ausencia->save();
		return redirect('ausencias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {        
        $ausencia = Ausencia::find($id);
		$ausencia->delete();
		if (isset($_POST['solicitudEmpleado'])){
			return redirect('ausencias/listadoVacaciones');}
		return redirect('ausencias');
    }
	
	public function change_date_format($date)
	{
		$day = DateTime::createFromFormat('d/m/Y', $date);
		return $day->format('Y-m-d');
	}	

	public function change_date_format2($date)
	{
		$day = DateTime::createFromFormat('Y-m-d', $date);
		return $day->format('d/m/Y');
	}
	
	public function change_date_format_fullcalendar($date)
	{
		$day = DateTime::createFromFormat('Y-m-d H:i:s', $date);
		
		return $day->format('d/m/Y');

	}
	
	public function format_interval(\DateInterval $interval)
	{
		$result = "";
		if ($interval->y) { $result .= $interval->format("%y año(s) "); }
		if ($interval->m) { $result .= $interval->format("%m mese(s) "); }
		if ($interval->d) { $result .= $interval->format("%d dia(s) "); }
		
		return $result;
	}
}
