<?php
namespace App\Http\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegisterService
{
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phonenumber' => $data['phonenumber'],
            'email' => $data['email'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),            
            'healthid' => $data['healthid']
        ]);
    }

    public function register($arr_request){
        $user = $this->create($arr_request);
        $this->sendOtp($user);
        return $user;
    }

    public function sendOtp($user)
    {
        $verificationCode = $this->generateOtp($user->phonenumber);
        
        //send sms
        $args = http_build_query(array(
            'auth_token'=> env('SMS_TOKEN', 'none'),
            'to'    => $user->phonenumber,
            'text'  => "Your OTP Code is: ".$verificationCode->otp));

        $url = "https://sms.aakashsms.com/sms/v3/send/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1); ///
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // Response
        $response = curl_exec($ch);
        curl_close($ch);  

        // Redirect to OTP verification form if not expecting JSON
        return redirect()->route('otp.verification', ['user_id' => $user->id])->with('success', 'Registration successful. Please verify your mobile number.');
    }

    protected function generateOtp($phonenumber)
    {
        $user = User::where('phonenumber', $phonenumber)->first();

        // Generate OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expired_at' => Carbon::now()->addMinutes(10)
        ]);
    }
}