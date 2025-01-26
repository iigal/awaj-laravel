<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\RegisterService;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/categories';

    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = (new RegisterService)->register($request->all());

        // Redirect to OTP verification form if not expecting JSON
        return redirect()->route('otp.verification', ['user_id' => $user->id])->with('success', 'Registration successful. Please verify your mobile number.');
    }
}
