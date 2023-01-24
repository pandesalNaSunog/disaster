<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\User;
use App\Models\OTP;
use App\Models\Cred;
use App\Models\Post;
class AuthController extends Controller
{
    public function register(Request $request){

        function generateOtp(){
            $characters = "1234567890";
            $otp = "";
            for($i = 0; $i < 6; $i++){
                $index = rand(0, strlen($characters) - 1);
                $otp .= $characters[$index];
                
            }

            return $otp;
        }
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'password' => 'required',
            'valid_id' => 'required',
        ]);

        $validId = base64_decode($request['valid_id']);
        $filename = uniqid() . ".jpg";
        file_put_contents($filename, $validId);

        $creds = Cred::where('id', 1)->first();

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'contact' => $request['contact'],
            'address' => $request['address'],
            'password' => bcrypt($request['password']),
            'valid_id' => $filename,
            'user_type' => 'user',
            'verified' => 'no',
            'approved' => 'no',
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;
        
        $otpText = generateOtp();
        $otp = OTP::create([
            'user_id' => $user->id,
            'otp' => $otpText
        ]);

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $creds->email; 
        $mail->Password = $creds->key; 
        $mail->SMTPSecure = $creds->secure; 
        $mail->Port = $creds->port ;

        $mail->setFrom($creds->email, 'Exodus Search and Rescue, Inc.');
        $mail->addAddress($request['email']);
        $mail->isHTML(true);

        $mail->Subject = 'One Time Password';
        $mail->Body = 'Your OTP is ' . $otpText . ". Note that this can only be used once. Do not close the app or this information will not be valid anymore.";

        if(!$mail->send()){
            $user->delete();
            return response ([
                'message' => 'email is invalid'
            ], 401);
        }

        

        return response([
            'token' => $token
        ], 200);
    }

    public function forgotPassword(Request $request){
        function generateOtp(){
            $characters = "1234567890";
            $otp = "";
            for($i = 0; $i < 6; $i++){
                $index = rand(0, strlen($characters) - 1);
                $otp .= $characters[$index];
                
            }

            return $otp;
        }
        $request->validate([
            'email' => 'required'
        ]);

        $user = User::where('email', $request['email'])->first();

        if(!$user){
            return response([
                'message' => 'email does not exist'
            ], 400);
        }

        $otp = generateOtp();

        $otpData = OTP::create([
            'user_id' => $user->id,
            'otp' => $otp
        ]);

        $token = $user->createToken('myToken')->plainTextToken;
        $creds = Cred::where('id', 1)->first();
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $creds->email; 
        $mail->Password = $creds->key; 
        $mail->SMTPSecure = $creds->secure; 
        $mail->Port = $creds->port ;

        $mail->setFrom($creds->email, 'Exodus Search and Rescue, Inc.');
        $mail->addAddress($request['email']);
        $mail->isHTML(true);

        $mail->Subject = 'One Time Password';
        $mail->Body = 'Your OTP is ' . $otp . ". Note that this can only be used once. Do not close the app or this information will not be valid anymore.";

        if(!$mail->send()){
            $user->delete();
            return response ([
                'message' => 'email is invalid'
            ], 401);
        }

        return response([
            'token' => $token
        ], 200);
    }

    public function forgotPasswordOTP(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $otp = OTP::where('user_id', $id)->where('otp', $request->otp)->first();

        if(!$otp){
            return response([
                'message' => 'invalid otp'
            ], 400);
        }
        $otp = OTP::where('user_id', $id)->delete();

        return response([
            'message' => 'you may now enter new password'
        ], 200);
    }

    public function updateProfilePicture(Request $request){
        $request->validate([
            'image' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        $image = base64_decode($request['image']);
        $filename = uniqid() . ".jpg";
        file_put_contents($filename, $image);

        if($user->profile_image != null || $user->profile_image != ""){
            unlink($user->profile_image);
        }

        $user->update([
            'profile_image' => $filename
        ]);

        

        return response([
            'image' => $user->profile_image
        ], 200);
    }

    public function sendotp(Request $request){
        $request->validate([
            'otp' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $otp = OTP::where('user_id', $id)->where('otp', $request['otp'])->orderBy('id', 'desc')->first();

        if(!$otp){
            return response([
                'message' => 'invalid otp'
            ], 400);
        }

        $user = User::where('id', $id)->first();

        $user->update([
            'verified' => 'yes'
        ]);
        $otp->delete();
        return response([
            'message' => 'ok'
        ], 200);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request['email'])->where('user_type','<>','admin')->first();

        if(!$user || !Hash::check($request['password'], $user->password) || $user->verified == 'no' || $user->approved == 'no'){
            return response([
                'message' => 'account not found'
            ], 404);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response([
            'token' => $token
        ], 200);
    }

    public function profile(Request $request){
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        if(!$user){
            return response([
                'message' => 'this user does not exist'
            ], 400);
        }
        $reportNum = 0;
        $caseClosed = 0;
        $pending = 0;
        $crewDispatched = 0;
        $reports = Post::where('user_id', $id)->get();
        foreach($reports as $report){
            $reportNum++;

            if($report->response == "case closed"){
                $caseClosed++;
            }else if($report->response == "Pending"){
                $pending++;
            }else if($report->response == "crew dispatched"){
                $crewDispatched++;
            }
        }
        return response([
            'user' => $user,
            'reports' => $reportNum,
            'case_closed' => $caseClosed,
            'crew_dispatched' => $crewDispatched,
            'pending' => $pending
        ]);
    }

    public function currentPassword(Request $request){
        $request->validate([
            'password' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        if(!$user || !Hash::check($request['password'], $user->password)){
            return response([
                'message' => 'invalid'
            ], 400);
        }

        return response($user, 200);
    }

    public function updateName(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        $user->update([
            'name' => $request['name'],
        ]);

        return response($user, 200);
    }
    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required'
        ]);

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        if(!$user){
            return response([
                'message' => 'not found'
            ], 404);
        }

        $user->update([
            'password' => bcrypt($request['password'])
        ]);

        return response($user, 200);
    }
}
