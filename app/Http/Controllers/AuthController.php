<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('profile');
        } else {
            return back()->with('message', 'Invalid login credentials');
        }
    }

    public function signupForm()
    {
        return view('citizen.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phonenumber' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'voterid' => 'required',
        ]);  


        $user = new User();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phonenumber = $request->phonenumber;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->voterid = $request->voterid;
        $user->role = "user";
        $user->save();

        // Assuming OTP is sent here
        return redirect()->route('verify')->with('message', 'OTP sent to your email.');
    }

    public function verify(Request $request)
    {
        $otp = $request->otp;
        $user = Auth::user();

        if ($otp === $user->otp) {
            $user->is_verified = true;
            $user->save();
            return redirect()->route('profile');
        } else {
            return back()->with('message', 'Invalid OTP');
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Send reset email here (for simplicity, just return a message)
        return back()->with('message', 'A password reset link has been sent to your email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect the user to the home page or login page
        return redirect('/');
    }
}

