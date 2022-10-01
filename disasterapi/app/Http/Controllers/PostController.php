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
        
        $response = [
            'id' => $post->id,
            'name' => $user->name,
            'date' => $post->created_at->format("M d, Y h:i A"),
            'caption' => $post->caption,
            'image' => $post->image
        ];

        return response($response, 200);
    }

    public function getPosts(){
        $posts = Post::orderBy('created_at','desc')->get();
        $response = array();
        foreach($posts as $postItem){
            $userId = $postItem->user_id;

            $user = User::where('id', $userId)->first();

            $userName = $user->name;

            $date = $postItem->created_at->format("M d, Y h:i A");

            $caption = $postItem->caption;

            $image = $postItem->image;

            $response[] = [
                'id' => $postItem->id,
                'name' => $userName,
                'date' => $date,
                'caption' => $caption,
                'image' => $image,
            ];
        }

        return response($response, 200);
    }
}
