<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);

        try{

            $post_data = $request->post();
            $post_data['password'] = bcrypt($post_data['password']);

            $user = User::create($post_data);
            $accessToken = $user->createToken('auth-token')->accessToken;

            //$request->session()->put('access_token', $accessToken);

            return response()->json([
                'access_token'=>$accessToken,
                'user'=>$user
            ]);

        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!!'
            ],500);
        }

    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required'
        ]);

        try{
            $user = User::where('email',$request->email)->first();

            if($user && \Hash::check($request->password, $user->password)){
                $accessToken = $user->createToken('auth-token')->accessToken;
                return response()->json([
                    'access_token'=>$accessToken,
                    'user'=>$user
                ]);
            }

            return response()->json([
                'message'=>'These credentials do not match our records.'
            ],500);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'message'=>'Something goes wrong!!'
            ],500);
        }
    }

    public function logout(){
        $user = \Auth::user()->token();
        $user->revoke();
        return response()->json([],200);
    }
}
