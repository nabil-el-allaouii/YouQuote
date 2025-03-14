<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse;
    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('Api Token for' . $user->name)->plainTextToken;
        return $this->Success([
            'user'=>$user,
            'token'=>$token
        ]);
    }
    public function login(LoginRequest $request){
        $user = User::where('email' , $request->email)->first();
        if(!$user || !Hash::check($request->password , $user->password)){
            return $this->Error('Error Credentials' , 401);
        }
        return $this->Success([
            'token'=> $user->createToken('API Token')->plainTextToken
        ]);
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->Success([
            'status'=> 'success',
            'message'=>'user Logged out'
        ]);
    }
}
