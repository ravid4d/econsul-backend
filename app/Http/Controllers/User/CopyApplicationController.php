<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus}; // Import the PDF facade
use App\Helpers\ApiResponse;
use Carbon\Carbon;


class CopyApplicationController extends Controller
{
    public function copyApplicant()
    {
        try {
            $lastYear = Carbon::now()->subYear()->year;
            $userId = "2";
            // Retrieve all records from the previous year
            $applicantDetails = ApplicantDetail::with('formStatus','SpouseDetail','ChildDetail','formPhoto')->whereYear('created_at', $lastYear)
                ->where('user_id', $userId)->get();

                foreach ($applicantDetails as $applicantDetail) {
                    // Copy the main applicant details
                    $newApplicantDetail = $applicantDetail->replicate();
                    $newApplicantDetail->created_at = now();
                    $newApplicantDetail->updated_at = now();
                    $newApplicantDetail->save();
                
                    // Copy the related form status
                    if ($applicantDetail->formStatus) {
                        $newFormStatus = $applicantDetail->formStatus->replicate();
                        $newFormStatus->applicant_detail_id = $newApplicantDetail->id;
                        $newFormStatus->save();
                    }
                
                    // Copy the spouse details
                    if ($applicantDetail->SpouseDetail) {
                        $newSpouseDetail = $applicantDetail->SpouseDetail->replicate();
                        $newSpouseDetail->applicant_detail_id = $newApplicantDetail->id;
                        $newSpouseDetail->save();
                    }
                
                    // Copy the child details
                    foreach ($applicantDetail->ChildDetail as $childDetail) {
                        $newChildDetail = $childDetail->replicate();
                        $newChildDetail->applicant_detail_id = $newApplicantDetail->id;
                        $newChildDetail->save();
                    }
                
                    // Copy the form photos
                    foreach ($applicantDetail->formPhoto as $photo) {
                        $newPhoto = $photo->replicate();
                        $newPhoto->applicant_detail_id = $newApplicantDetail->id;
                        $newPhoto->save();
                    }
                }

            return response()->json(['message' => 'Data copied successfully!'], 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
