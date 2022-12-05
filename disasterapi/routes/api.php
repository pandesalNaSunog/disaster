<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/barangays', [PostController::class, 'barangays']);
Route::get('/categories', [PostController::class, 'categories']);
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/post', [PostController::class, 'post']);
    Route::post('/otp', [AuthController::class, 'sendotp']);
    Route::get('/posts', [PostController::class, 'getPosts']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/update-profile-picture', [AuthController::class, 'updateProfilePicture']);
    Route::post('/current-password', [AuthController::class, 'currentPassword']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/update-name', [AuthController::class, 'updateName']);
    Route::post('/delete-post', [PostController::class, 'deletePost']);
});
