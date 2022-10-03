<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;
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
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'contact' => $request['contact'],
            'address' => $request['address'],
            'password' => bcrypt($request['password']),
            'user_type' => 'user',
            'verified' => 'no'
        ]);
        
        $otpText = generateOtp();

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'exodussearchandrescueinc@gmail.com';
        $mail->Password = 'etvowxaighslallq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('exodussearchandrescueinc@gmail.com', 'Exodus Search and Rescue, Inc.');
        $mail->addAddress($request['email']);
        $mail->isHTML(true);

        $mail->Subject = 'One Time Password';
        $mail->Body = 'Your OTP is ' . $otpText . ". ";

        if(!$mail->send()){
            $user->delete();
            return response ([
                'message' => 'email is invalid'
            ], 401);
        }

        

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
