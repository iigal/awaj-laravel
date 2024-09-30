<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'numeric', 'digits_between:10,10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],            
            'voterid' => 'required',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'phonenumber' => $data['phonenumber'],
            'email' => $data['email'],
            'address' => $data['address'],
            'password' => Hash::make($data['password']),            
            'voterid' => $data['voterid'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Generate OTP
        $verificationCode = $this->generateOtp($user->phonenumber);
        
        //send sms
        $args = http_build_query(array(
            'auth_token'=> env('SMS_TOKEN', 'none'),
            'to'    => $user->phonenumber,
            'text'  => $verificationCode->otp));

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

        // Check if the request expects a JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Please verify your mobile number.',
                'user_id' => $user->id,
                'otp' => $verificationCode->otp
            ]);
        }

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
