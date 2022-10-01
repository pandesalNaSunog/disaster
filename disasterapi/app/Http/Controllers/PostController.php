<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Post;
use App\Models\User;
class PostController extends Controller
{
    public function post(Request $request){
        $request->validate([
            'caption' => 'required',
            'image' => 'required',
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $decoded = base64_decode($request['image']);
        $filename = uniqid().".jpg";
        file_put_contents($filename, $decoded);


        $post = Post::create([
            'user_id' => $id,
            'caption' => $request['caption'],
            'image' => $filename,
            'response' => 'Pending',
        ]);

        $user = User::where('id', $id)->first();


        return response([
            'user' => $user,
            'post' => $post
        ], 200);
    }
}
