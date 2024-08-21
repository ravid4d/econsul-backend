<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};
use Illuminate\Support\Facades\Validator;



class PutFormController extends Controller
{
    public function childInfoUpdate(Request $request)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:child_details,id', 
                'first_name' => 'required',
                'middle_name' => 'required',
                'birth_date' => 'required|date',
                'country' => 'required',
                'city' => 'required',
            ]);
    
            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            $childDetail = ChildDetail::findOrFail($request->id);
    
            $childDetail->update([
                'name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'birth_date' => $request->birth_date,
                'country' => $request->country,
                'city' => $request->city,
            ]);
    
            return ApiResponse::success('Child Detail Updated Successfully!');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
