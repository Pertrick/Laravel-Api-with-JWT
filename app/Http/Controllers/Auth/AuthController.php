<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Auth\BaseController as BaseController;
use App\Models\User;

class AuthController extends BaseController
{
    //
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    //register function
    public function register(RegistrationRequest $request){

         //validation handled by RegistrationRequest

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
            
        ]);


        return $this->sendResponse($user, 'User Registration Successfull');
    }

    //login function
    public function login(LoginRequest $request){

        //validation handled by LoginRequest

      if(!$token = auth()->attempt($request->validated())){
          return $this->sendError('Unauthorized.', ['error' => 'unauthorized']);
      }

      return $this->createNewToken($token);

 
   }

   //generate token functon
   public function createNewToken($token){

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
   }


   //refresh token
   public function refresh(){
       return $this->createNewToken(auth()->refresh());
   }

   //logout
   public function logout(){
       auth()->logout();

       return response()->json([
            'status' => true,
            'message' => 'User logged out successfully!',
       ], 400);
   }

   //profile

   public function profile(){
       $user = auth()->user();

       if($user){
           return response()->json([
               'status' => true,
               'data' => $user,
           ], 400);
       }
   }

   

   
}
