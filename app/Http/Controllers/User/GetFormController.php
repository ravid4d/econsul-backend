<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};
use Illuminate\Support\Facades\Log;


class GetFormController extends Controller
{
    public function applicantDetail(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $applicantDetail = ApplicantDetail::where('user_id', $userId)->get();
            return ApiResponse::success('Data retrieved successfully', $applicantDetail);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function showApplicantDetail(Request $request, $id)
    {
        $userId = $request->user()->id;
        $applicantDetail = ApplicantDetail::with('formPhoto', 'SpouseDetail', 'ChildDetail', 'formStatus')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$applicantDetail) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        return ApiResponse::success('Data retrieved successfully', $applicantDetail);
    }

    public function spouseDetail(Request $request)
    {
        try {
            // Retrieve the authenticated user's ID
            $userId = $request->user()->id;
            $spouseDetails = SpouseDetail::where('user_id', $userId)->get();
            return ApiResponse::success('Data retrieved successfully', $spouseDetails);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve spouse details. Please try again later.');
        }
    }

    public function showSpouseDetail(Request $request, $id)
    {
        $spouseDetail = SpouseDetail::where('applicant_detail_id', $id)->first();
    
        if (!$spouseDetail) {
            return response()->json(['message' => 'Spouse detail not found'], 404);
        }
    
        return ApiResponse::success('Data retrieved successfully', $spouseDetail);
    }

    public function showChildDetail($id)
    {
        // Retrieve the applicant detail based on the $id
        $childDetail = ChildDetail::where('applicant_detail_id', $id)->get();

        if (!$childDetail) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }

        return ApiResponse::success('Data retrieved successfully', $childDetail);
    }
    public function applicantPhoto($id)
    {
        try {

            $photoDetail = PhotoDetail::where("applicant_detail_id", $id)->get();

            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $photoDetail);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function formSubmition($id)
    {
        try {

            $status = FormStatus::where("applicant_detail_id", $id)->select('status')->first();

            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $status);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
