<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Helpers\ApiResponse;

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
                $data['name'] = $existingUser->name." ".$existingUser->surname;
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
                $data['name'] = $newUser->name." ".$newUser->surname;
                $data['email'] = $newUser->email;
            }
            return ApiResponse::success("Logged in Successfully!",$data);
        } catch (\Exception $e) {
            return ApiResponse::error("Invalid token or Google authentication failed.",["error_msg"=>$e->getMessage()]);
        }
    }
}
