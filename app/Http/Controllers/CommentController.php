<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
     //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){

        $comments =  auth()->user()->comments()->with('posts')->get();

        return response()->json([
            'status' => true,
            'comments' => $comments,
        ]);
    }

    public function store(Request $request){

        $user = auth()->user();

        if($user){
            $this->validate($request, [
                'comment' => 'required',
            ]);

            $comment = new Comment();

            $comment->comment = $request->comment;
            $comment->post_id = $request->post_id;
            $comment->user_id = $user->id;


            $saveComment = auth()->user()->comments()->save($comment);

            if($saveComment){
                return response()->json([
                    'status' => true,
                    'message' => 'Comment added',
                    'comment' => $comment->toArray(),
                ], 400);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Comment not Added'
                ]);
            }


        }

    }


        public function show($id){

            $comment = auth()->user()->comments()->find($id);

    
            if(!$comment){
               
                return response()->json([
                    'status' => false,
                    'message' => 'Comment not found!'
                ]);
            }
    
                return response()->json([
                    'status' => true,
                    'comment' => $comment->toArray(),
                ],400);
               
            
        }
      
    

    //update post
    public function update(Request $request, $id){

        $comment = auth()->user()->comments()->find($id);


        if(!$comment){
            return response()->json([
                'status' => false,
                'message' => 'Comment not found!'
            ]);

           }
           
           $updated = $comment->fill($request->all())->save();

           if($updated){
                return response()->json([
                    'status' => true,
                    'message' => 'Comment updated',
                    'comment' => $comment,
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
           $comment = auth()->user()->comments()->find($id);

           if(!$comment){
               return response()->json([
                'status' => false,
                'message' => 'Comment not found!'
               ], 400);
           }

           if($comment->delete()){
               return response()->json([
                    'status' => 1,
                    'message' => 'Comment deleted Successfully.'
               ]);
           }
           else{

            return response()->json([
                'status' => false,
                'message' => 'Comment can not be deleted'
            ], 500);
       }
       }
            

}


