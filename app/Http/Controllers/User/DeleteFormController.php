<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};

class DeleteFormController extends Controller
{
    public function applicantdelete($id)
    {
        try {
            // return "yess";
            $applicantDetail = ApplicantDetail::with('formStatus')->findOrFail($id);
            $applicantDetail->delete();
            return ApiResponse::success('Record deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
