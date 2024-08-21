<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {
        try {
            $token = $request->input('id_token');
            $user = Socialite::driver('google')->stateless()->userFromToken($token);

            // Find or create the user in your database
            $existingUser = User::where('email', $user->email)->first();
            $data = [];
            if ($existingUser) {
                // Log the user in
                $token = $existingUser->createToken('remember_token')->plainTextToken;

                $data['authToken'] = $token;
                $data['name'] = $existingUser->name . " " . $existingUser->surname;
                $data['email'] = $existingUser->email;
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->user['given_name'],
                    'surname' => $user->user['family_name'],
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'email_verified_at' => now()
                ]);
                $token = $newUser->createToken('remember_token')->plainTextToken;
                $data['authToken'] = $token;
                $data['name'] = $newUser->name . " " . $newUser->surname;
                $data['email'] = $newUser->email;
            }
            return ApiResponse::success("Logged in Successfully!", $data);
        } catch (\Exception $e) {
            return ApiResponse::error("Invalid token or Google authentication failed.", ["error_msg" => $e->getMessage()]);
        }
    }
    public function loginWithOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $mobileNumber = $request->input('mobile_number');
            $user = User::where('mobile_number', $mobileNumber)->first();

            if (!$user) {
                // Register the user
                $user = User::create([
                    'mobile_number' => $mobileNumber,
                ]);
            }

            // Generate OTP
            // $otp = rand(100000, 999999);
            $otp = '111111';

            // Send OTP using external API
            // $response = Http::post('https://your-otp-service.com/send', [
            //     'mobile_number' => $mobileNumber,
            //     'otp' => $otp,
            // ]);

            // Check if OTP was sent successfully
            // if ($response->successful()) {
            // Store OTP and expiration time in the session or database
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10); // OTP expires in 10 minutes
            $user->save();

            // return response()->json(['message' => 'OTP sent successfully.']);
            return ApiResponse::success('OTP sent successfully.');
            // }

            // return response()->json(['message' => 'Failed to send OTP.'], 500);
        } catch (\Exception $e) {
            return ApiResponse::error("Invalid token or Google authentication failed.", ["error_msg" => $e->getMessage()]);
        }
    }
    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required',
                'otp' => 'required|digits:6',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $user = User::where('mobile_number', $request->mobile_number)->first();

            if (!$user || $user->otp_code !== $request->input('otp')) {
                // return response()->json(['message' => 'Invalid OTP.'], 401);
                return ApiResponse::error('Invalid OTP.',[],401);
            }

            if (now()->greaterThan($user->otp_expires_at)) {
                // return response()->json(['message' => 'OTP has expired.'], 401);
                return ApiResponse::error('OTP has expired.',[],401);
            }

            // OTP is valid, proceed with login
            $token = $user->createToken('remember_token')->plainTextToken;

            // Clear OTP after successful verification
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // return response()->json(['token' => $token]);
            return ApiResponse::success("Logged in Successfully!",['authToken'=>$token,'name'=>$user->name . " " . $user->surname,'email'=>$user->email]);


        } catch (\Exception $e) {
            return ApiResponse::error("Invalid token or Google authentication failed.", ["error_msg" => $e->getMessage()]);
        }
    }
}