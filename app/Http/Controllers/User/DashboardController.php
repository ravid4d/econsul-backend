<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus}; // Import the PDF facade
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1); // Default page is 1
            $limit = $request->input('limit', 10); // Default limit is 10 records per page
            $search = $request->input('search'); // Search query
            $sortBy = $request->input('sortBy', 'id'); // Default sort by column is 'id'
            $sortOrder = $request->input('sortOrder'); // Default sort order is 'asc'

            $query = ApplicantDetail::with('formStatus');
            // return  $query;

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('personal_info->first_name', 'LIKE', "%$search%")
                        ->orWhereHas('formStatus', function ($q) use ($search) {
                            $q->where('status', 'LIKE', "%$search%");
                        });
                });
            }


            $validSortColumns = ['status', 'name', 'updated_at', 'id'];
            if (in_array($sortBy, $validSortColumns)) {
                if ($sortBy == 'name') {
                    $query->orderByRaw("JSON_EXTRACT(personal_info, '$.first_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.middle_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.last_name') {$sortOrder}"); $query->orderByRaw("JSON_EXTRACT(personal_info, '$.first_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.middle_name') {$sortOrder}, JSON_EXTRACT(personal_info, '$.last_name') {$sortOrder}");
                } elseif ($sortBy == 'status') {
                    $applicants = $query->get();
                    $applicants = $applicants->sortBy(function($applicant) {
                        return $applicant->formStatus->status ?? ''; // Return empty string if status is null
                    }, SORT_REGULAR, $sortOrder === 'desc'); 
                } elseif (in_array($sortBy, ['updated_at'])) {
                    // Sorting by 'updated_at'
                    $query->orderBy($sortBy, $sortOrder);
                }
            } else {
                // Fallback to default sort by 'id'
                $query->orderBy('id', $sortOrder);
            }


            $total = $query->count();
            $applicantDetail = $query->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();


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
    public function ApplicantDetailDashboard($id)
    {
        try {
            $applicantDetail = ApplicantDetail::with('formPhoto', 'formConfirm')->find($id);
            // Retrieve the applicant detail based on the $id
            // return  $applicantDetail;
            if (!$applicantDetail) {
                return response()->json(['message' => 'Applicant not found'], 404);
            }

            // Check each property and return the first key that is null
            if (is_null($applicantDetail->eligibility_status)) {
                return 'eligibility';
            }
            if (is_null($applicantDetail->education_level)) {
                return 'education_level';
            }
            if (is_null($applicantDetail->personal_info)) {
                return 'personal_info';
            }
            if (is_null($applicantDetail->contact_info)) {
                return 'contact_info';
            }
            if (is_null($applicantDetail->spouse_info)) {
                return 'spouse_info';
            }
            if (is_null($applicantDetail->children_info)) {
                return 'children_info';
            }
            if (is_null($applicantDetail->formPhoto)) {
                return 'photo'; // Assuming `photo` is a part of ApplicantDetail
            }
            if (is_null($applicantDetail->form_confirm)) {
                return 'confirmation'; // Assuming `confirmation` is a part of ApplicantDetail
            }

            // If none of the keys are null
            return ApiResponse::success('Data retrieved successfully', $applicantDetail);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }


    public function idDashboardPDF(Request $request)
    {
        try {
            // Retrieve the IDs from the request
            $ids = $request->input('applicantsId', []);

            if (empty($ids)) {
                return ApiResponse::error('No IDs provided');
            }

            // Fetch applicant details
            $applicantDetails = ApplicantDetail::with('formStatus')->whereIn('id', $ids)->get();

            if ($applicantDetails->isEmpty()) {
                return ApiResponse::error('No applicant details found');
            }

            // Generate PDF with all applicant details
            $pdf = PDF::loadView('user_pdf', ['applicantDetail' => $applicantDetails]);

            // Return PDF as a download response
            return $pdf->download('combined_applicants.pdf');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
