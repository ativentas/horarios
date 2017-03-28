<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Auth;
use DB;

class CommentController extends Controller
{
  public function store(Request $request)
  {
    $this->validate($request, [
    'body' => 'required|min:4'   
    ]);

    //on_post, from_user, body
    $input['from_user'] = $request->user()->id;
    $input['on_cuadrante'] = $request->input('on_cuadrante');
    $input['on_ausencia'] = $request->input('on_ausencia');
    $input['body'] = $request->input('body');
    // $cuadrante_id = $request->input('cuadrante_id');
    $cuadrante_id = $request->input('on_cuadrante');
    $ausencia_id = $request->input('on_ausencia');
    try {
        $exception = DB::transaction(function() use ($input,$request,$cuadrante_id)
        {

          Comment::create( $input );
          // return redirect('cuadrante/'.$cuadrante_id)->with('info', 'Comentario enviado');     
          if ($request->has('on_cuadrante')) {
            return redirect('cuadrante/'.$cuadrante_id)->with('info', 'Comentario enviado'); 
          }elseif ($request->has('on_ausencia')) {
            return redirect('ausencias')->with('info','Se ha guardado la nota');
          }    
  
        });
        return is_null($exception) ? 'Nota guardada' : $exception;
               
    } catch(Exception $e) {
        // return $e;
        return "Error: no se ha podido guardar la nota".$e;
    }


  }
  public function storeRespuesta(Request $request, $nota_id)
  {

    $nota = Comment::find($nota_id);

    $visible = $request->visible?'1':0;
    $resuelto = $request->resuelto?'1':0;

    $nota->update([
      'visible'=> $visible,
      'resuelto'=> $resuelto,
      'resuelto_por'=> Auth::user()->id,
      'nota_respuesta'=> $request->respuesta,
    ]);
  }

}
