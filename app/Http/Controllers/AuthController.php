<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function create(Request $request){
        $validator = \Validator::make($request->input(), [

            'name'=> 'required|string|max:100',
            'email'=> 'required|string|max:100|unique:users',
            'password'=> 'required|string|min:8',
        ]);

       /*  $validator = \Validator::make($request->input(), $rules); */

        if($validator->fails()){

            return response()->json([
            'status' =>false,
            'errors' => $validator->errors()->all()
            ],400);
         }    
         
         $user = User::create([
            'name' => $request->name, 
            'email' => $request->email,
            'password' => Hash::make($request->password),

         ]);

         Return response()->json([
            'status'=>true,
            'message'=>'Users Create Successfully',
            'token'=>$user->createToken('API TOKEN')->plainTextToken
         ],201);

      }


      public function login(Request $request){
        $rules=[
           
            'email'=> 'required|string|max:100',
            'password'=> 'required|string'
        ];

        $validator = \Validator::make($request->input(), $rules);

        if($validator->fails()){
            return response()->json([
            'status' =>false,
            'errors' => $validator->errors()->all()
            ],400);
         } 
         if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' =>false,
                'errors' => ['Unauthorized']
                ],401);
         } 

         //Entonces si las credenciales del usuario son positivasvalidas
         $user = User::where('email', $request->email)->first(); 
         Return response()->json([ //responder con un JSON
            'status'=>true, //Status positivo
            'message'=>'Users logged  Successfully',// Acceso Satifactorio
            'data'=>$user, //Devuelve los datos del usuario en la var data
            'token'=>$user->createToken('API TOKEN')->plainTextToken// Asigna un token a este Login
         ],200); 

      }

      //Eliminamos todos los token creados por el Usuario
      public function logout(){
        auth()->user()->tokens()->delete();

        Return response()->json([
            'status'=>true,
            'message'=>'User logged out correctly'
         ],200);

      }


}
