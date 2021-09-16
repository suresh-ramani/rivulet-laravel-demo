<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->expectsJson()){
            $posts = Post::select('id','title','image','user_id')->withCount('comments')->get();
            $posts->map(function($post){
                $post->canEdit = $post->user_id == \Auth()->id();
                return $post;
            });

            return response()->json([
                'posts' => $posts
            ]);
        }
        return view('post.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'category_id'=>'required|exists:categories,id',
            'image'=>'required|image|mimes:jpg,jpeg,png'
        ]);

        try{

            $user = \Auth::user();
            $post = $user->posts()->create($request->post());

            if($file = $request->file('image')){
                $uuid = (string) Str::orderedUuid();
                $newFilename = $uuid.'.'.$file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('posts',$file,$newFilename);
                $post->image = $newFilename;
                $post->save();
            }

            return response()->json([
                'message'=> 'Post Created Successfully!!'
            ]);

        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return response()->json([
            'post'=> $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=>'required',
            'category_id'=>'required|exists:categories,id',
            'image'=>'nullable|image|mimes:jpg,jpeg,png'
        ]);

        try{

            $post_data = $request->post();
            if(!$request->hasFile('image')){
                $post_data['image'] = $post->image;
            }

            $post->fill($post_data)->save();

            if($request->hasFile('image') && $request->file('image')->isValid()){

                if(Storage::disk('public')->exists("posts/{$post->image}")){
                    Storage::disk('public')->delete("posts/{$post->image}");
                }

                $file = $request->file('image');
                $uuid = (string) Str::orderedUuid();
                $newFilename = $uuid.'.'.$file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('posts',$file,$newFilename);
                $post->image = $newFilename;
                $post->save();
            }

            return response()->json([
                'message'=> 'Post Updated Successfully!!'
            ]);

        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function categories(){
        $categories = Category::select('id','name')->get();
        return response()->json([
            'categories'=>$categories
        ]);
    }
}
