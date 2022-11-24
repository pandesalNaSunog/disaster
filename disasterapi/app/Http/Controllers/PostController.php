<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Post;
use App\Models\User;
use App\Models\Barangay;
use App\Models\DisasterCategory;
class PostController extends Controller
{
    public function barangays(){
        $barangays = Barangay::orderBy('barangay', 'asc')->get();
        return response($barangays, 200);
    }

    public function categories(){
        $categories = DisasterCategory::orderBy('category', 'asc')->get();
        return response($categories, 200);
    }
    public function post(Request $request){
        $request->validate([
            'caption' => 'required',
            'image' => 'required',
            'barangay_id' => 'required',
            'disaster_category_id' => 'required',
            'lat' => 'required',
            'long' => 'required'
        ]);

        $barangay = Barangay::where('id', $request['barangay_id'])->first();

        if(!$barangay){
            return response([
                'message' => 'this barangay does not exist'
            ], 400);
        }

        $disasterCategory = DisasterCategory::where('id', $request['disaster_category_id'])->first();

        if(!$disasterCategory){
            return response([
                'message' => 'this category does not exist'
            ], 400);
        }

        $barangayName = $barangay->barangay;

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $decoded = base64_decode($request['image']);
        $filename = uniqid().".jpg";
        file_put_contents($filename, $decoded);


        $post = Post::create([
            'user_id' => $id,
            'caption' => $request['caption'],
            'image' => $filename,
            'barangay_id' => $request['barangay_id'],
            'response' => 'Pending',
            'disaster_category_id' => $request['disaster_category_id'],
            'lat' => $request['lat'],
            'long' => $request['long']
        ]);

        $user = User::where('id', $id)->first();
        
        $response = [
            'id' => $post->id,
            'name' => $user->name,
            'date' => $post->created_at->format("M d, Y h:i A"),
            'caption' => $post->caption,
            'barangay' => $barangayName,
            'image' => $post->image,
            'response' => $post->response,
            'lat' => $post->lat,
            'long' => $post->long
        ];

        return response($response, 200);
    }

    public function getPosts(Request $request){

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;


        $posts = Post::where('user_id', $id)->orderBy('created_at','desc')->get();
        $response = array();
        foreach($posts as $postItem){
            $userId = $postItem->user_id;

            $user = User::where('id', $userId)->first();

            $userName = $user->name;

            $date = $postItem->created_at->format("M d, Y h:i A");

            $caption = $postItem->caption;

            $image = $postItem->image;

            $barangayId = $postItem->barangay_id;

            $barangay = Barangay::where('id', $barangayId)->first();

            if($barangay){
                $barangayName = $barangay->barangay;
            }else{
                $barangayName = "Unknown Barangay";
            }
            

            $response[] = [
                'id' => $postItem->id,
                'name' => $userName,
                'date' => $date,
                'caption' => $caption,
                'image' => $image,
                'barangay' => $barangayName,
                'response' => $postItem->response
            ];
        }

        return response($response, 200);
    }
}
