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
                    $newApplicantDetail = $applicantDetail->replicate()->toArray();
                    $newApplicantDetail["created_at"] = now();
                    $newApplicantDetail["updated_at"] = now();
                    // $newApplicantDetail["save"(['tim]estamps' => false]);
                    $newApplicantDetailId = DB::table('applicant_details')->insertGetId($newApplicantDetail);
                
                    // Copy the related form status
                    if ($applicantDetail->formStatus) {
                        $newFormStatus = $applicantDetail->formStatus->replicate()->toArray();
                        $newFormStatus["applicant_detail_id"] = $newApplicantDetailId;
                        // $newFormStatus->timestamps = false;
                        $newFormStatus["created_at"] = now();
                        $newFormStatus["updated_at"] = now();
                        DB::table('form_statuses')->insert($newFormStatus);
                        // $newFormStatus->save(['timestamps' => false]);
                    }
                
                    // Copy the spouse details
                    if ($applicantDetail->SpouseDetail) {
                        $newSpouseDetail = $applicantDetail->SpouseDetail->replicate()->toArray();
                        $newSpouseDetail["applicant_detail_id"] = $newApplicantDetailId;
                        // $newSpouseDetail->timestamps = false;
                        $newSpouseDetail["created_at"] = now();
                        $newSpouseDetail["updated_at"] = now();
                        // $newSpouseDetail->save(['timestamps' => false]);
                        DB::table('spouse_details')->insert($newSpouseDetail);
                    }
                
                    // Copy the child details
                    foreach ($applicantDetail->ChildDetail as $childDetail) {
                        $newChildDetail = $childDetail->replicate()->toArray();
                        $newChildDetail["applicant_detail_id"] = $newApplicantDetailId;
                        // $newChildDetail->timestamps = false;
                        $newChildDetail["created_at"] = now();
                        $newChildDetail["updated_at"] = now();
                        DB::table('child_details')->insert($newChildDetail);
                        // $newChildDetail->save(['timestamps' => false]);
                    }
                
                    // Copy the form photos
                    foreach ($applicantDetail->formPhoto as $photo) {
                        $newPhoto = $photo->replicate()->toArray();
                        $newPhoto["applicant_detail_id"] = $newApplicantDetailId;
                        // $newPhoto->timestamps = false;
                        $newPhoto["created_at"] = now();
                        $newPhoto["updated_at"] = now();
                        // $newPhoto->save(['timestamps' => false]);
                        DB::table('photo_details')->insert($newPhoto);
                    }
                }

            return response()->json(['message' => 'Data copied successfully!'], 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
