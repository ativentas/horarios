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
    $input['body'] = $request->input('body');
    $cuadrante_id = $request->input('cuadrante_id');
    Comment::create( $input );
    return redirect('cuadrante/'.$cuadrante_id)->with('info', 'Comentario enviado');     
  }

}
