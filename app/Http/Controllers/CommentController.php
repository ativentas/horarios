<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Auth;

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
    Comment::create( $input );
    // return redirect('cuadrante/'.$cuadrante_id)->with('info', 'Comentario enviado');     
    if ($request->has('on_cuadrante')) {
      return redirect('cuadrante/'.$cuadrante_id)->with('info', 'Comentario enviado'); 
    }elseif ($request->has('on_ausencia')) {
      return redirect('ausencias')->with('info','Se ha guardado la nota');
    }    
  }
  public function storeRespuesta(Request $request, $nota_id)
  {

    $nota = Comment::find($nota_id);

    $nota->update([
      'visible'=> $request->visible,
      'resuelto'=> $request->resuelto,
      'resuelto_por'=> Auth::user()->id,
      'nota_respuesta'=> $request->respuesta,
    ]);
  }

}
