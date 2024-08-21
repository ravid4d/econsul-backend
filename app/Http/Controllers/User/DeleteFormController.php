<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};
use Illuminate\Support\Facades\Storage;

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

    public function applicantPhotoDelete($id)
    {
        try {
            $applicantDetail = PhotoDetail::findOrFail($id);
            $filePath = $applicantDetail->photo_path; // Assuming 'photo_path' is the column storing the file path

            // Delete the file from storage if it exists
            if ($filePath && Storage::exists("storage/".$filePath)) {
                Storage::delete($filePath);
            }
            $applicantDetail->delete();
            return ApiResponse::success('Record deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
