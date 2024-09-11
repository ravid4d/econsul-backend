<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus}; // Import the PDF facade
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1); // Default page is 1
            $limit = $request->input('limit', 10); // Default limit is 10 records per page
            $search = $request->input('search'); // Search query
            $sortBy = $request->input('sortBy', 'id'); // Default sort by column is 'id'
            $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order is 'asc'
            $year = $request->input('year'); // Year filter
            $userId = $request->user()->id;
            // return $userId;
            // Initialize the query
            $query = ApplicantDetail::query()
                ->where('user_id', $userId)
                ->join('form_statuses', 'applicant_details.id', '=', 'form_statuses.applicant_detail_id')
                ->select('applicant_details.*', 'form_statuses.status as form_status_status');

            // Apply search filter if provided
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('personal_info->first_name', 'LIKE', "%$search%")
                        ->orWhere('personal_info->last_name', 'LIKE', "%$search%")
                        ->orWhere('form_statuses.status', 'LIKE', "%$search%");
                });
            }

            if ($year && is_numeric($year) && strlen($year) === 4) {
                $query->whereYear('applicant_details.created_at', $year);
            }
            // Apply sorting
            $validSortColumns = ['status', 'name', 'updated_at', 'created_at'];
            if (in_array($sortBy, $validSortColumns)) {
                if ($sortBy == 'name') {
                    $query->orderByRaw("JSON_EXTRACT(personal_info, '$.first_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.middle_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.last_name') {$sortOrder}");
                } elseif ($sortBy == 'status') {
                    $query->orderBy('form_status_status', $sortOrder);
                } elseif (in_array($sortBy, ['updated_at'])) {
                    $query->orderBy($sortBy, $sortOrder);
                }
            } else {
                // Fallback to default sort by 'id'
                $query->orderBy('created_at', $sortOrder);
            }

            // Get total count before pagination
            $total = $query->count();

            // Apply pagination
            $applicantDetail = $query->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->map(function ($applicant) {
                    return [
                        'id' => $applicant->id,
                        'user_id' => $applicant->user_id,
                        'eligibility_status' => $applicant->eligibility_status,
                        'education_level' => $applicant->education_level,
                        'personal_info' => $applicant->personal_info,
                        'contact_info' => $applicant->contact_info,
                        'spouse_info' => $applicant->spouse_info,
                        'children_info' => $applicant->children_info,
                        'created_at' => $applicant->created_at,
                        'updated_at' => $applicant->updated_at,
                        'status' => $applicant->form_status_status, // Include only status value
                    ];
                });

            // Prepare the response
            $response = [
                'data' => $applicantDetail,
                'total' => $total,
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($total / $limit),
            ];

            // Return a success response with the data
            return ApiResponse::success('Data retrieved successfully', $response);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return ApiResponse::error($e->getMessage());
        }
    }
    public function ApplicantYear()
    {
        try {
            $years = ApplicantDetail::select(DB::raw('YEAR(created_at) as year'))
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->pluck('year');

            return ApiResponse::success('Data retrieved successfully', $years);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function ApplicantDetailDashboard($id)
    {
        try {
            $applicantDetail = ApplicantDetail::with('formPhoto', 'SpouseDetail', 'ChildDetail', 'formStatus')->find($id);
            // return $applicantDetail->formStatus->status;
            // Check if the applicant detail exists
            if (!$applicantDetail) {
                return response()->json(['message' => 'Applicant not found'], 404);
            }

            $nullKeys = [];

            // Check each property and add the key to the array if it is null
            if (is_null($applicantDetail->education_level)) {
                $nullKeys[] = 'education';
            }
            if (is_null($applicantDetail->personal_info)) {
                $nullKeys[] = 'personal';
            }
            if (is_null($applicantDetail->contact_info)) {
                $nullKeys[] = 'contact';
            }
            if (is_null($applicantDetail->spouse_info)) {
                $nullKeys[] = 'spouse';
            }
            if (is_null($applicantDetail->children_info)) {
                $nullKeys[] = 'children';
            }
            if (!is_null($applicantDetail->spouse_info)) {
                if (empty($applicantDetail->SpouseDetail)) {
                    if($applicantDetail->spouse_info['maritalStatus'] == "Married and my spouse is NOT a U.S. citizen or U.S. Lawful Permanent Resident (LPR)")
                    {
                    $nullKeys[] = 'spouse-info';
                    }
                }
            }
            if (!is_null($applicantDetail->children_info) && is_int($applicantDetail->children_info)) {
                // Convert the ChildDetail collection to an array of records
                $childDetails = $applicantDetail->ChildDetail->toArray();

                // Ensure you loop correctly over the number of expected children
                for ($i = 0; $i < $applicantDetail->children_info; $i++) {
                    if (!isset($childDetails[$i]) || is_null($childDetails[$i])) {
                        $nullKeys[] = 'child/' . ($i + 1);
                    }
                }
            }

            // Check if the main applicant has a photo
            if ($applicantDetail->formPhoto->isEmpty()) {
                $nullKeys[] = 'photo';
            }

            if ($applicantDetail->formStatus->status == 'inprogress') {
                $nullKeys[] = "submit-application";
            }
            if ($applicantDetail->formStatus->status == 'submitting') {
                $nullKeys[] = "submitted";
            }
            if ($applicantDetail->formStatus->status == 'confirmed') {
                $nullKeys[] = "submitted";
            }


            // Return the array of null keys
            if (!empty($nullKeys)) {
                return response()->json(['data' => $nullKeys]);
            }

            return response()->json(['data' => $nullKeys]);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }


    public function idDashboardPDF(Request $request)
    {
        try {

            $ids = $request->input('applicantsId', []);

            if (empty($ids)) {
                return ApiResponse::error('No IDs provided');
            }

            $applicantDetails = ApplicantDetail::with('formStatus', 'SpouseDetail', 'formPhoto', 'ChildDetail')->whereIn('id',$ids)->get();
            foreach ($applicantDetails as $applicant) {
                $applicantPhotos = [
                    'applicant' => null,
                    'spouse' => null,
                ];

                foreach ($applicant->formPhoto as $photo) {
                    if ($photo->photo_owner == 'applicant') {
                        $applicantPhotos['applicant'] = $photo->image_url;
                    } elseif ($photo->photo_owner == 'spouse') {
                        $applicantPhotos['spouse'] = $photo->image_url;
                    }
                }
                $applicant->photos = $applicantPhotos;
            }

            $childrenDetails = [];

            // Loop through form photos to collect child photos
            foreach ($applicant->formPhoto as $photo) {
                if (strpos($photo->photo_owner, 'child') === 0) {
                    // Extract child index from photo_owner
                    $childIndex = str_replace('child', '', $photo->photo_owner);

                    // Initialize child details if not already done
                    if (!isset($childrenDetails[$childIndex])) {
                        $childrenDetails[$childIndex] = [
                            'photo' => null // Default to null in case no photo is found
                        ];
                    }

                    // Assign photo URL to the corresponding child
                    $childrenDetails[$childIndex]['photo'] = $photo->image_url;
                }
            }

            // Assign children details to applicant
            $applicant->children = $childrenDetails;

            $applicant->children = $childrenDetails;

            // return $applicantDetails[0];

            if ($applicantDetails->isEmpty()) {
                return ApiResponse::error('No applicant details found');
            }

            $temporaryDir = storage_path('app/public/tmp_pdfs');
            Storage::makeDirectory('public/tmp_pdfs');

            $pdfFiles = [];

            foreach ($applicantDetails as $applicantDetail) {
                $pdf = PDF::loadView('user_pdf', ['detail' => $applicantDetail]);
                $pdfFileName = 'applicant_' . $applicantDetail->id . '.pdf';
                $pdfFilePath = $temporaryDir . '/' . $pdfFileName;
                $pdf->save($pdfFilePath);
                $pdfFiles[] = $pdfFilePath;
            }

            if (count($pdfFiles) === 1) {
                return response()->download($pdfFiles[0])->deleteFileAfterSend(true);
            }
    
            $zipFileName = 'applicants_pdfs.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach ($pdfFiles as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
            }

            Storage::deleteDirectory('public/tmp_pdfs');

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
