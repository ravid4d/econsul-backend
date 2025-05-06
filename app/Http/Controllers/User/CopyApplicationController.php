<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus}; // Import the PDF facade
use App\Helpers\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CopyApplicationController extends Controller
{
    public function copyApplicant(Request $request)
    {
        try {
            $lastYear = Carbon::now()->subYear()->year;
            $userId = $request->user()->id;
            // Retrieve all records from the previous year
            $applicantDetails = ApplicantDetail::with('formStatus','SpouseDetail','ChildDetail','formPhoto')->whereYear('created_at', $lastYear)
                ->where('user_id', $userId)->get();

                foreach ($applicantDetails as $applicantDetail) {
                    // Copy the main applicant details
                    $newApplicantDetail = $applicantDetail->replicate();
                    $newApplicantDetail->timestamps = false;
                    $newApplicantDetail->created_at = now();
                    $newApplicantDetail->updated_at = now();
                    $newApplicantDetail->save(['timestamps' => false]);
                
                    // Copy the related form status
                    if ($applicantDetail->formStatus) {
                        $newFormStatus = $applicantDetail->formStatus->replicate();
                        $newFormStatus->applicant_detail_id = $newApplicantDetail->id;
                        $newFormStatus->timestamps = false;
                        $newFormStatus->created_at = now();
                        $newFormStatus->updated_at = now();
                        $newFormStatus->save();
                    }
                
                    // Copy the spouse details
                    if ($applicantDetail->SpouseDetail) {
                        $newSpouseDetail = $applicantDetail->SpouseDetail->replicate();
                        $newSpouseDetail->applicant_detail_id = $newApplicantDetail->id;
                        $newSpouseDetail->timestamps = false;
                        $newSpouseDetail->created_at = now();
                        $newSpouseDetail->updated_at = now();
                        $newSpouseDetail->save();
                    }
                
                    // Copy the child details
                    foreach ($applicantDetail->ChildDetail as $childDetail) {
                        $newChildDetail = $childDetail->replicate();
                        $newChildDetail->applicant_detail_id = $newApplicantDetail->id;
                        $newChildDetail->timestamps = false;
                        $newChildDetail->created_at = now();
                        $newChildDetail->updated_at = now();
                        $newChildDetail->save();
                    }
                
                    // Copy the form photos
                    foreach ($applicantDetail->formPhoto as $photo) {
                        $newPhoto = $photo->replicate();
                        $newPhoto->applicant_detail_id = $newApplicantDetail->id;
                        $newPhoto->timestamps = false;
                        $newPhoto->created_at = now();
                        $newPhoto->updated_at = now();
                        $newPhoto->save();
                    }
                }

            return response()->json(['message' => 'Data copied successfully!'], 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
