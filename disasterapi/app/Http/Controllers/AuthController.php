<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'contact' => $request['contact'],
            'address' => $request['address'],
            'password' => bcrypt($request['password']),
            'user_type' => 'user',
        ]);

        return response($user, 200);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request['email'])->where('user_type','<>','admin')->first();

        if(!$user || !Hash::check($request['password'], $user->password)){
            return response([
                'message' => 'account not found'
            ], 404);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response([
            'token' => $token
        ], 200);
    }
}
