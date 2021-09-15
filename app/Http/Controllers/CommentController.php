<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class CommentController extends Controller
{
    public function index(Post $post){
        try{
            $comments = $post->comments()->with(['user:id,name,email'])->get();
            return response()->json($comments,200);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!!'
            ],500);
        }
    }

    public function store(Request $request, Post $post){

        $request->validate([
            'text'=>'required'
        ],[
            'text.required'=>'The comment field is required.'
        ]);

        try{
            $user = \Auth::user();
            $comment = $post->comments()->create($request->post()+['user_id'=>$user->id]);
            return response()->json([
                'comment'=>$comment->load(['user:id,name,email'])
            ]);

        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!!'
            ]);
        }
    }
}
