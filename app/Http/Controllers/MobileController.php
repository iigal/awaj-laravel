<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class MobileController extends Controller
{
    // Fetch all public complaints for mobile API
    public function allPublicComplaints(Request $request)
    {
        try {
            $complaints = Complaint::where('category', 'public')
                ->orderBy('id', 'desc')
                ->with('user')
                ->get();

            return response()->json($complaints, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    // Register a new user for mobile API
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user', 'token'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User Registration Failed', 'message' => $e->getMessage()], 409);
        }
    }

    // Handle user login for mobile API
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Generate and send OTP for mobile API
    public function sendOtp(Request $request)
    {
        $otpCode = Str::random(6); // Use a secure method for OTP generation
        $email = $request->email;

        Otp::create([
            'email' => $email,
            'otp' => $otpCode,
            'created_at' => now(),
        ]);

        try {
            Mail::send([], [], function ($message) use ($email, $otpCode) {
                $message->to($email)
                        ->subject('Your OTP Code')
                        ->setBody('Your OTP code is ' . $otpCode);
            });

            return response()->json(['message' => 'OTP sent successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send OTP', 'message' => $e->getMessage()], 500);
        }
    }

    // Upload images using Cloudinary for mobile API
    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $file = $request->file('file');
            $uploadResult = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'mobile_app'
            ]);

            return response()->json(['url' => $uploadResult->getSecurePath()], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image upload failed', 'message' => $e->getMessage()], 500);
        }
    }
}
