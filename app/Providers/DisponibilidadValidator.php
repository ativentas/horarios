<?php

namespace App\Providers;

use Validator, DB;

use Illuminate\Support\ServiceProvider;

class DisponibilidadValidator extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		// Validator return true if pass validation for room availability
		Validator::extend('available', function($attribute, $value, $parameters, $validator) {
			
			$time = explode(" - ", $value);
			
			$start = $this->change_date_format($time[0]);
			$end = $this->change_date_format($time[1]);

			// search for any possible clash with available ausencias

			$ausenciasempleado = DB::table('ausencias')
										->where('empleado_id',$parameters[0]);

			// search for any possible clash with available ausencias
			
			// $scene1 = DB::table('ausencias')
			$scene1 = $ausenciasempleado
							->where('fecha_inicio', '<=', $start)
							->where('fecha_fin', '>=', $end)
							->count();
			
			// $scene2 = DB::table('ausencias')
			$scene2 = $ausenciasempleado
							->where('fecha_inicio', '<', $start)
							->where('fecha_fin', '>', $end)
							->count();
							
			// $scene3 = DB::table('ausencias')
			$scene3 = $ausenciasempleado
							->where('fecha_inicio', '>=', $start)
							->where('fecha_fin', '<=', $end)
							->count();
							
			// $scene4 = DB::table('ausencias')
			$scene4 = $ausenciasempleado
							->where('fecha_fin', '>', $start)
							->where('fecha_fin', '<', $end)
							->count();
			
			// $scene5 = DB::table('ausencias')
			$scene5 = $ausenciasempleado
							->where('fecha_inicio', '>', $start)
							->where('fecha_inicio', '<', $end)
							->count();			
			
			// if any ausencia exist, means more than 0, return false
			if($scene1 + $scene2 + $scene3 + $scene4 + $scene5 > 0)
			{
				return false;
			}
			
			return true;
		});
		
		// check time validity
		Validator::extend('duration', function($attribute, $value, $parameters, $validator) {
			
			$days = explode(" - ", $value);
			$start = $this->change_date_format($days[0]);
			$end = $this->change_date_format($days[1]);
			
			if(abs(strtotime($end) - strtotime($start)) < 0 )
			{
				return false;	
			}
			return true;
		});
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
	
	public function change_date_format($date)
	{
		$day = \DateTime::createFromFormat('d/m/Y', $date);
		return $day->format('Y-m-d');
	}
}
