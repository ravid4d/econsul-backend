<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, VerifyEmailCode, VerifyMobileCode};
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\OtpService;

class ProfileController extends Controller
{
    public function profileUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'surname' => 'required'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            $userId = $request->user()->id;
            $user = User::find($userId);

            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->save();

            return ApiResponse::success('Profile data updated successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function profilePhotoUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $user = $request->user();

            // Handle the uploaded file
            if ($request->hasFile('profile_picture')) {
                // Delete the old profile picture if it exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                // Generate a unique filename for the new profile picture
                $filename = time() . '_' . $request->file('profile_picture')->getClientOriginalName();

                // Store the new file using the Storage facade
                $path = $request->file('profile_picture')->storeAs('profile_pictures', $filename, 'public');

                // Save the new profile picture path in the database
                $user->profile_picture = $path;
                $user->save();
                return ApiResponse::success('Profile picture updated successfully!', ["profile_picture_url" => Storage::url($path)]);
            }
            return ApiResponse::error('No profile picture uploaded.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function sendEmailVerificationCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            // $code = rand(100000, 999999);

            $code = '111111';
            // $email = $request->email;
            VerifyEmailCode::updateOrCreate(
                ["user_id" => $request->user()->id, "email" => $request->email],
                ["code" => $code, "otp_expires_at" => now()->addMinutes(10)]
            );


            return ApiResponse::success('Otp sent to given email!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function verifyEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'code' => 'required|digits:6'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            $user = $request->user();
            $emailverify = VerifyEmailCode::where('email', $request->email)->where('user_id', $user->id)->where('code', $request->code)->first();

            if (!$emailverify || $emailverify->code != $request->code) {
                // return response()->json(['message' => 'Invalid OTP.'], 401);
                return ApiResponse::error('Invalid OTP.', [], 401);
            }

            if (now()->greaterThan($emailverify->otp_expires_at)) {
                // return response()->json(['message' => 'OTP has expired.'], 401);
                return ApiResponse::error('OTP has expired.', [], 401);
            }

            $emailverify->delete();
            $user->email = $request->email;
            $user->email_verified_at = now();
            $user->save();


            return ApiResponse::success('Email successfully verified!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function sendMobileVerificationCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required|unique:users,mobile_number'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            // $code = rand(100000, 999999);
            $code = '111111';
            $msg = "Your verification code is $code. Use this to change  your mobile number. Do not share this code with anyone. The code will expire in 10 minutes.";
            $response = OtpService::sendOtp($request->mobile_number, $msg);
            $user = $request->user();
            if ($response) {
                VerifyMobileCode::updateOrCreate(
                    ["user_id" => $request->user()->id, "mobile_number" => $request->mobile_number],
                    ["code" => $code, "otp_expires_at" => now()->addMinutes(10)]
                );

                return ApiResponse::success('Otp sent to given mobile number!');
            } else {
                return ApiResponse::error('Failed to send OTP.');
            }

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function verifyMobile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required|unique:users,mobile_number',
                'code' => 'required|digits:6'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            $user = $request->user();
            $numberverify = VerifyMobileCode::where('mobile_number', $request->mobile_number)->where('user_id', $user->id)->where('code', $request->code)->first();

            if (!$numberverify || $numberverify->code != $request->code) {
                // return response()->json(['message' => 'Invalid OTP.'], 401);
                return ApiResponse::error('Invalid OTP.', [], 401);
            }

            if (now()->greaterThan($numberverify->otp_expires_at)) {
                // return response()->json(['message' => 'OTP has expired.'], 401);
                return ApiResponse::error('OTP has expired.', [], 401);
            }

            $numberverify->delete();
            $user->mobile_number = $request->mobile_number;
            $user->number_verified_at = now();
            $user->save();

            return ApiResponse::success('Mobile Number successfully verified!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function deleteUser(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // Delete the current access token
            $user->currentAccessToken()->delete();


            $user->delete();

            return ApiResponse::success('Your profile has been successfully deleted.');
        } else {
            return ApiResponse::error('User not found.', 404);
        }
    }
}
