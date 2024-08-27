<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class OtpService
{
    public static function sendOtp($mobileNumber,$msg)
    {
        try {
            // Send the OTP via HTTP POST request
            $response = Http::withHeaders([
                'x-api-key' => 'Ha9tIrLDrUV6?lx-k$8UDr6s?k_t#_tLm',
            ])->post('http://sms-sender.eu-central-1.elasticbeanstalk.com/send_sms?phone_number='.$mobileNumber.'&message='.$msg.'&utf=1');


            // Check if the request was successful
            if ($response->successful()) {
                // return [
                //     'success' => true,
                //     'message' => 'OTP sent successfully.',
                //     'data' => $response->json(),
                //     'status_code' => 200,
                // ];
                return true;
            }

            // Handle client or server errors
            // return [
            //     'success' => false,
            //     'message' => 'Failed to send OTP.',
            //     'data' => $response->json(), // Or simply $response->body()
            //     'status_code' => $response->status(),
            // ];
            return false;

        } catch (RequestException $e) {
            // Handle HTTP request-related errors (e.g., network issues)
            // return [
            //     'success' => false,
            //     'message' => 'HTTP request failed.',
            //     'data' => null,
            //     'status_code' => 500,
            // ];
            return false;

        } catch (\Exception $e) {
            // Handle general errors (e.g., unexpected issues)
            // return [
            //     'success' => false,
            //     'message' => 'An unexpected error occurred.',
            //     'data' => null,
            //     'status_code' => 500,
            // ];
            return false;
        }
    }
}