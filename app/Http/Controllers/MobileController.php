<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use JWTAuth;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\RegisterService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class MobileController extends Controller
{
    // Fetch all public complaints for mobile API
    /* public function allPublicComplaints(Request $request)
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
 */
    // Register a new user for mobile API
    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        try {
            $registerService = new RegisterService();
            $user = $registerService->register($request->all());

            //$token = JWTAuth::fromUser($user);
            return response()->json(compact('user'/*, 'token'*/), 201);
        } catch (\Exception $e) {
            \Log::error('User Registration Failed: ' . $e->getMessage());
            return response()->json(['error' => 'User Registration Failed', 'message' => $e->getMessage()], 409);
        }
    }

    // Handle user login for mobile API
   /*  public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json(compact('token', 'user'), 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function relogin(Request $request)
    {
        // Get the token from the request (usually in the Authorization header)
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 401);
        }

        try {
            // Parse the token and authenticate the user
            $user = JWTAuth::parseToken()->authenticate();

            // Check if the user is found
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // If valid, return the user data and a new token (if needed)
            // Optionally, you can issue a new token to extend the session
            $newToken = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $newToken, // You can return the same token or generate a new one
                'user' => $user
            ], 200);

        } catch (JWTException $e) {
            // Token is invalid or expired
            return response()->json(['error' => 'Token is invalid or expired'], 401);
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
    } */
}
