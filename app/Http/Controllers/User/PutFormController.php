<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};
use Illuminate\Support\Facades\Validator;



class PutFormController extends Controller
{
    public function eligibilitySubmit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "eligibility" => "required|array",
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            $form = ApplicantDetail::updateOrCreate(
                ['user_id' => $userId],
                ['eligibility_status' => $request->eligibility]
            );

            return ApiResponse::success('Form Updated Successfully!', ["applicationId" => $form->id, "updated_at" => $form->updated_at]);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function education_level(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "education" => "required",
                "application_id" => "required"
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            ApplicantDetail::where('id', $request->application_id)
                ->where('user_id', $userId)
                ->update(['education_level' => $request->education]);

            return ApiResponse::success('Education Level Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function personal_info(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "personal" => "required|array",
                "application_id" => "required"
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            ApplicantDetail::where('id', $request->application_id)
                ->where('user_id', $userId)
                ->update(['personal_info' => $request->personal]);

            return ApiResponse::success('Personal Info Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function contact_info(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "contact" => "required|array",
                "application_id" => "required"
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            ApplicantDetail::where('id', $request->application_id)
                ->where('user_id', $userId)
                ->update(['contact_info' => $request->contact]);

            return ApiResponse::success('Contact Info Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function spouse_info(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "spouse" => "required|array",
                "application_id" => "required"
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            ApplicantDetail::where('id', $request->application_id)
                ->where('user_id', $userId)
                ->update(['spouse_info' => $request->spouse]);

            return ApiResponse::success('Spouse Info Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function children_info(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "children" => "required",
                "application_id" => "required"
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = 1; // Replace with actual user ID retrieval logic
            ApplicantDetail::where('id', $request->application_id)
                ->where('user_id', $userId)
                ->update(['children_info' => $request->children]);

            return ApiResponse::success('Children Info Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function spouseSubmit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_id' => 'required',
                'first_name' => 'required',
                'middle_name' => 'required',
                'birth_date' => 'required',
                'country' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            SpouseDetail::updateOrCreate(
                ['applicant_detail_id' => $request->application_id],
                [
                    'name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'birth_date' => $request->birth_date,
                    'country' => $request->country,
                    'city' => $request->city
                ]
            );

            return ApiResponse::success('Spouse Detail Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function childInfoSubmit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_id' => 'required',
                'first_name' => 'required',
                'middle_name' => 'required',
                'birth_date' => 'required',
                'country' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            ChildDetail::updateOrCreate(
                ['applicant_detail_id' => $request->application_id],
                [
                    'name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'birth_date' => $request->birth_date,
                    'country' => $request->country,
                    'city' => $request->city
                ]
            );

            return ApiResponse::success('Child Detail Updated Successfully!');

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
