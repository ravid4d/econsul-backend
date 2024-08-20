<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        // Find or create the user in the database
        return response()->json(["user"=>$user]);
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // Log the user in
            $token = $existingUser->createToken('authToken')->accessToken;
        } else {
            // Create a new user
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
            ]);
            $token = $newUser->createToken('authToken')->accessToken;
        }

        // Return the token to the frontend
        return response()->json(['token' => $token]);
    }
}
