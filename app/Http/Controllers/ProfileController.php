<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    public function profileUpdate(Request $request)
    {
        try {

        } catch (\Exception $e) {
            return ApiResponse::error("Invalid token or Google authentication failed.", ["error_msg" => $e->getMessage()]);
        }
    }
}
