<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};
use PDF; // Import the PDF facade

class DashboardController extends Controller
{
    public function index()
    {
        try {

            $applicantDetail = ApplicantDetail::with('formStatuses')->get();
            return ApiResponse::success('Data retrieved successfully', $applicantDetail);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function dashboardPDF()
    {
        $applicantDetail = ApplicantDetail::all();
        $pdf = PDF::loadView('pdf.user_data', compact('applicantDetail'));
        return $pdf->download('my_data.pdf');
    }

    public function dashboardIdPdf($id)
    {
        try {

            $applicantDetail = ApplicantDetail::with('formStatuses')->findOrFail($id);
            $pdf = PDF::loadView('pdf.user_data', compact('applicantDetail'));
            return $pdf->download('applicant_detail_' . $id . '.pdf');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
