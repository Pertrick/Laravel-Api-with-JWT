<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){

        $post =  auth()->user()->posts()->with('comments')->get();

        return response()->json([
            'status' => true,
            'post' => $post,
        ]);
    }

    public function store(Request $request){

        $user = auth()->user();

        if($user){
            $this->validate($request, [
                'post' => 'required',
            ]);

            $post = new Post();

            $post->post = $request->post;
            $post->user_id = $user->id;


            $savePost = auth()->user()->posts()->save($post);

            if($savePost){
                return response()->json([
                    'status' => true,
                    'message' => 'Post added',
                    'post' => $post->toArray(),
                ], 400);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Post not Added'
                ]);
            }


        }

    }


        public function show($id){

            $post = auth()->user()->posts()->find($id);

    
            if(!$post){
               
                return response()->json([
                    'status' => false,
                    'message' => 'Post not found!'
                ]);
            }
    
                return response()->json([
                    'status' => true,
                    'post' => $post->toArray(),
                ],400);
               
            
        }
      
    

    //update post
    public function update(Request $request, $id){

        $post = auth()->user()->posts()->find($id);


        if(!$post){
            return response()->json([
                'status' => false,
                'message' => 'Post not found!'
            ]);

           }
           
           $updated = $post->fill($request->all())->save();

           if($updated){
                return response()->json([
                    'status' => true,
                    'message' => 'Post updated',
                    'post' => $post,
                ]);

           }else{

                return response()->json([
                    'status' => false,
                    'message' => 'Post can not updated'
                ]);
           }

       }


       //destroy
       public function destroy($id){
           $post = auth()->user()->posts()->find($id);

           if(!$post){
               return response()->json([
                'status' => false,
                'message' => 'Post not found!'
               ], 400);
           }

           if($post->delete()){
               return response()->json([
                    'status' => 1,
                    'message' => 'Post deleted Successfully.'
               ]);
           }
           else{

            return response()->json([
                'status' => false,
                'message' => 'Post can not be deleted'
            ], 500);
       }
       }
            

}
