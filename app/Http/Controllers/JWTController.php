<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\json;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class JWTController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password' =>'required|string|min:4'
        ]);
        if($validator->fails()){
            return response(['error'=>$validator->errors(),'validation error']);
        }else{
            $user = User::create([
                'name'=>$request->name,
                'email' => $request->email,
                'password'=> Hash::make($request->password)
            ]);
            return response(['message'=>'User create successfully','user'=>$user]);
        }
    }
    public function login(Request $request) {
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email',
            'password' =>'required|string|min:4'
        ]);
        if($validator->fails()) {
            return response(['error'=>$validator->errors(),'validation error']);
        }else{
            if(!$token= auth()->attempt($validator->validated())){
                return response(['error'=>'Unauthorised']);
            }else{
                
                return $this->respondWithToken($token);
            }
        }
    }
    public function respondWithToken($token){
        return response([
            'access_token'=>$token,
            "token_type"=>'barer',
            'expires_in' => auth()->factory()->getTTL()*60]);
    }
    public function profile(Request $req) {
        $validator = true;
        $barer = $req->header('Authorization');
        $b = auth()->attempt(['true']);
        
        if($barer == $b) {
            return response(['data'=> auth()->user(), 'message'=>'fetch successfully'],200);
        }else{  
            return response(['message'=>"invalid barer",'data1'=> $barer,'data2'=>$b]);
        }
        
    }
    public function reresh() {
        return $this->responceWithToken(auth()->refresh());
    }
}
