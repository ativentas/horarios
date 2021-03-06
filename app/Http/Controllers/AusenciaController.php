<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ausencia;
use App\Empleado;
use App\Centro;
use App\Comment;
use App\Contrato;
use DateTime;
use Carbon\Carbon;
use Auth;
use DB;

class AusenciaController extends Controller
{
   private $tipos;
 
	public function __construct()
   {
      $this->middleware('auth');
    
      $this->tipos = array(
      	'V' => 'Vacaciones', 
      	'AN' => 'Ausencia sin justif.',
      	'B' => 'Baja Médica',
      	'AJ' => 'Ausencia justif.',
      	'PR' => 'Permiso retrib.',
      	'BP' => 'Baja Paternidad');
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(Auth::user()->isAdmin()){
		$data = [
			'ausencias'	 => Ausencia::orderBy('estado')->orderBy('fecha_inicio')->get(),
		];
		}else{
		$data = [
			'ausencias'	 => Centro::find(Auth::user()->centro_id)->ausencias()->orderBy('estado')->orderBy('fecha_inicio')->get(),
		];
		}
		return view('ausencias/list', $data);
    }
    
    //creo que este metodo no se usa
  //   public function listadoVacaciones()
  //   {
  //   	$data = [
		// 	'page_title' => 'Listado',
		// 	'ausencias'	 => Ausencia::where('empleado_id',Auth::user()->id)->orderBy('estado')->orderBy('fecha_inicio')->get(),
		// ];
  //   	return view('ausencias.list',$data);
  //   }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 

      if(Auth::user()->isAdmin()){
      $empleados = Empleado::all();
      $listado = DB::table('contratos')
      	->join('empleados','empleados.id','=','contratos.empleado_id')
      	->select('contratos.centro_id','contratos.empleado_id','empleados.alias')
			->get()->groupBy('centro_id');
			// dd($listado);	

		$data = [
			'empleados' => $empleados,
			'tipos'		=> $this->tipos,
			'centros'	=> Centro::all(),
			'listado'	=> $listado,

		];
      }else{     
		$listado = collect();
		$data = [
			'empleados' => Empleado::whereHas('contratos', function($query){
					$query->where('centro_id',Auth::user()->centro_id);
			})->get(),
			'tipos'		=> $this->tipos,
			'listado'	=> $listado,
		];
		}
		return view('ausencias/nieuwausencia', $data);
    }


	public function centro_fecha($empleado_id, $fecha){
     	$fecha = DateTime::createFromFormat('d/m/Y', $fecha);
     	$fecha = $fecha->format('Y-m-d');
     	
     	$contrato = Contrato::where('empleado_id',$empleado_id)
       	->where(function($query) use ($fecha){   	
	        	$query->where([
	            ['fecha_baja',NULL],
	            ['fecha_alta','<=',$fecha],
	            ]);
	        	$query->orWhere([
	        		['fecha_baja','>=',$fecha],
	        		['fecha_alta','<=',$fecha],
	        		]);
	        })
        	->get();
     	if(count($contrato)==1){
        	return $contrato[0]->centro_id;
     	}elseif(count($contrato)>1){
     		return 'Solapados';
      }
      return false;
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
			'centro'	=> 'required',
			'empleado_id'	=> 'required',
			'tipo' => 'required',
			'time'	=> "required|duration|available:$request->empleado_id|horarios_cerrados:$request"

		]);

		$empleado_id = $request->input('empleado_id');
		$empleado = Empleado::where('id',$empleado_id)->first();

		$time = explode(" - ", $request->input('time'));
		$fecha = $time[0];
		$centro_id = $this->centro_fecha($empleado_id,$fecha);
		$request_centro = $request->centro;
		if ($centro_id != $request_centro){
			return redirect()->back()->with('info','Error: el empleado no estaba con contrato vigente en la fecha de inicio de la ausencia');
		}
		switch ($centro_id) {
			case 'Solapados':
				#
				return redirect()->back()->with('info','Error: hay 2 contratos vigentes en la fecha de inicio para ese empleado. Revisa los contratos'); 
				break;
			case false:
				# code...
				return redirect()->back()->with('info','Error: no hay ningún contrato vigente en la fecha de inicio');
				break;
			
			default:
				#
				break;
		}

		$ausencia 					= new Ausencia;
		$ausencia->empleado_id	= $empleado->id;
		$ausencia->centro_id		= $request_centro;
		$ausencia->owner			= Auth::user()->id;
		$ausencia->alias 			= $empleado->alias;
		$ausencia->tipo 			= $request->input('tipo');
		$ausencia->fecha_inicio	= $this->change_date_format($time[0]);
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

		//grabo la nota/comentario si es que hay

		if(isset($request->body)){
			$input['from_user'] = $request->user()->id;
			$input['on_ausencia'] = $ausencia->id;
			$input['body'] = $request->input('body');
			Comment::create( $input );
	
		}
		
		//TO DO: MENSAJE INFO PARA DECIR QUE SE HA GUARDADO LA AUSENCIA
		$request->session()->flash('success', 'Guardado!');
		// if (isset($_POST['solicitudEmpleado'])){
		// 	return redirect()->back();
		// }
		return redirect('ausencias');
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
      $comments = $ausencia->comments;
        $data = [
			'page_title' 	=> 'Edit '.$ausencia->tipo,
			'ausencia'		=> $ausencia,
			'tipos'			=> $this->tipos,
			'comments'		=> $comments,
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
			'tipo' => 'required',
			'time'	=> 'required|duration'
		]);
		
		$time = explode(" - ", $request->input('time'));
		
		$ausencia 				= Ausencia::findOrFail($id);
		$ausencia->tipo 		= $request->input('tipo');
		$ausencia->owner 		= Auth::user()->id;
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
		// return redirect('ausencias');
		return redirect()->back();
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
		//TO DO:yo creo que el if siguiente no se usa
		if (isset($_POST['solicitudEmpleado'])){
			return redirect('ausencias/listadoVacaciones');}
		// return redirect('ausencias');
		return redirect()->back();
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
