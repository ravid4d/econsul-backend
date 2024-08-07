<?php
namespace App\Helpers;

class ApiResponse
{
    public static function success($message,$data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $code
        ]);
    }
    public static function error($message,$data = null,$code = 400)
    {
        return response()->json([
            'success'=>false,
            'message' => $message,
            'data'=>$data,
            'status_code'=>$code
        ]);
    }
    public static function sessionError($message,$data = null,$code = 404)
    {
        return response()->json([
            'success'=>false,
            'message'=>$message,
            'data'=>$data,
            'status_code'=>$code
        ]);
    }
}