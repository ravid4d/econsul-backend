<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};


class GetFormController extends Controller
{
    public function applicantDetail(Request $request)
    {
        try {
            // Fetch all relevant details
            $applicantDetail = ApplicantDetail::all();
            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $applicantDetail);

        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return ApiResponse::error($e->getMessage());
        }
    }
    public function showApplicantDetail($id)
    {
        // Retrieve the applicant detail based on the $id
        $applicantDetail = ApplicantDetail::find($id);

        if (!$applicantDetail) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        return ApiResponse::success('Data retrieved successfully', $applicantDetail);

    }

    public function spouseDetail(Request $request)
    {
        try {
            $spouseDetail = SpouseDetail::all();
            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $spouseDetail);

        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return ApiResponse::error($e->getMessage());
        }
    }
    public function showSpouseDetail($id)
    {
        // Retrieve the applicant detail based on the $id
        $spouseDetail = SpouseDetail::where('applicant_detail_id', $id)->first();

        if (!$spouseDetail) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        return ApiResponse::success('Data retrieved successfully', $spouseDetail);

    }
    public function childDetail()
    {
        try {
          
            $childDetail = ChildDetail::all();

            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $childDetail);

        } catch (\Exception $e) {
            \Log::error('Error retrieving child details: ' . $e->getMessage());

            // Return an error response if something goes wrong
            return ApiResponse::error($e->getMessage());
        }
    }
    public function applicantPhoto($id)
    {
        try {
          
            $photoDetail= PhotoDetail::where("applicant_detail_id",$id)->get();

            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $photoDetail);

        } catch (\Exception $e) {
            \Log::error('Error retrieving child details: ' . $e->getMessage());

            // Return an error response if something goes wrong
            return ApiResponse::error($e->getMessage());
        }
    }

   
}
