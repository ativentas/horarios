<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
  public function store(Request $request)
  {
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

}
