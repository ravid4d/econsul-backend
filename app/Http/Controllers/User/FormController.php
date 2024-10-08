<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\{ApplicantDetail, SpouseDetail, ChildDetail, PhotoDetail, FormStatus};

class FormController extends Controller
{
    public function eligibilitySubmit(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'id' => 'nullable|exists:applicant_details,id', // Validate if 'id' exists if provided
                'eligibility' => 'required|array',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $userId = $request->user()->id; // Replace this with $request->user()->id if using authentication

            // Safely get the 'id' from request

            if ($request->input('id')) {
                $id = $request->input('id');
                // Update the existing record
                $form = ApplicantDetail::updateOrCreate(
                    ['id' => $id], // Match record by 'id'
                    ['user_id' => $userId, 'eligibility_status' => $request->eligibility] // Update with these values
                );
            } else {
                // Create a new record if 'id' is not provided
                $form = ApplicantDetail::create([
                    'user_id' => $userId,
                    'eligibility_status' => $request->eligibility
                ]);
            }


            FormStatus::updateOrCreate(
                ['applicant_detail_id' => $form->id],
                ['status' => 'inprogress']
            );

            return ApiResponse::success('Form Submitted Successfully!', [
                'applicationId' => $form->id,
                'created_at' => $form->created_at
            ]);
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
            // $userId = $request->user()->id;
            $userId = $request->user()->id;
            ApplicantDetail::where('id', $request->application_id)->where('user_id', $userId)->update(['education_level' => $request->education]);

            return ApiResponse::success('Form Submitted Successfully!');
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
            // $userId = $request->user()->id;
            $userId = $request->user()->id;
            ApplicantDetail::where('id', $request->application_id)->where('user_id', $userId)->update(['personal_info' => $request->personal]);

            return ApiResponse::success('Form Submitted Successfully!');
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
            // $userId = $request->user()->id;
            $userId = $request->user()->id;
            ApplicantDetail::where('id', $request->application_id)->where('user_id', $userId)->update(['contact_info' => $request->contact]);

            return ApiResponse::success('Form Submitted Successfully!');
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

            if ($request->spouse['maritalStatus'] != "Married and my spouse is NOT a U.S. citizen or U.S. Lawful Permanent Resident (LPR)") {
                // Get the spouse detail instance
                $spouseDetail = SpouseDetail::where('applicant_detail_id', $request->application_id)->first();
            
                // Check if the spouse detail exists
                if ($spouseDetail) {
                    // Check if the corresponding photo exists
                    $photoDetail = PhotoDetail::where('photo_owner', 'spouse')
                        ->where('applicant_detail_id', $spouseDetail->applicant_detail_id) // Access the property on the instance
                        ->where('photo_id', $spouseDetail->id)
                        ->first();
            
                    // If the photo exists, delete it
                    if ($photoDetail) {
                        $photoDetail->delete();
                    }
            
                    // Delete the spouse detail
                    $spouseDetail->delete();
                }
            }
            // $userId = $request->user()->id;
            $userId = $request->user()->id;
            ApplicantDetail::where('id', $request->application_id)->where('user_id', $userId)->update(['spouse_info' => $request->spouse]);

            return ApiResponse::success('Form Submitted Successfully!');
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

            $childrenToDelete = $request->children; // Number of records to delete
            $childCount = ChildDetail::where('applicant_detail_id', $request->application_id)->count();

            if ($childCount > $childrenToDelete) {
                $extraChildren = $childCount - $childrenToDelete;
                $latestChildrenToDelete = ChildDetail::where('applicant_detail_id', $request->application_id)
                    ->orderBy('created_at', 'desc') // or 'id' if no 'created_at'
                    ->take($extraChildren) // Get the extra records that need to be deleted
                    ->get();
                foreach ($latestChildrenToDelete as $child) {
                    PhotoDetail::where('photo_id', $child->id)->where('applicant_detail_id',$request->application_id)->delete();
                    $child->delete();
                }
            }

            $userId = $request->user()->id;
            ApplicantDetail::where('id', $request->application_id)->where('user_id', $userId)->update(['children_info' => $request->children]);

            return ApiResponse::success('Form Submitted Successfully!');
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
                'surname' => 'required',
                'gender' => 'required',
                'birth_date' => 'required',
                'country' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $spouseDetail = SpouseDetail::updateOrCreate(
                ['applicant_detail_id' => $request->application_id], // Search criteria
                [
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'surname' => $request->surname,
                    'gender' => $request->gender,
                    'birth_date' => $request->birth_date,
                    'country' => $request->country,
                    'city' => $request->city
                ]
            );

            return ApiResponse::success('Spouse Detail submitted successfully!');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function childInfoSubmit(Request $request)
    {
        // return $request->all();
        try {
            $validator = Validator::make($request->all(), [
                'applicant_detail_id' => 'required',
                'first_name' => 'required',
                'surname' => 'required',
                'gender' => 'required',
                'birth_date' => 'required',
                'country' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            ChildDetail::create([
                'applicant_detail_id' => $request->applicant_detail_id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'surname' => $request->surname,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'country' => $request->country,
                'city' => $request->city
            ]);


            return ApiResponse::success('Child Detail submitted successfully!');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    // public function applicantPhotoSave(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'application_id' => 'required|exists:applicant_details,id',
    //             'photos' => 'required|array',
    //             'photos.*.photo_owner' => 'required|string'
    //         ]);

    //         if ($validator->fails()) {
    //             return ApiResponse::error("Validation Error!", $validator->errors());
    //         }

    //         foreach ($request->photos as $index => $photo) {
    //             $hasEphotoLink = array_key_exists('ephoto_link', $photo);
    //             $hasImageUrl = $request->hasFile("photos.$index.image");

    //             if (!$hasEphotoLink && !$hasImageUrl) {
    //                 return ApiResponse::error('Either ephoto_link or image must be provided for each photo.');
    //             }

    //             if ($hasEphotoLink && $hasImageUrl) {
    //                 return ApiResponse::error('Only one of ephoto_link or image can be provided for each photo.');
    //             }

    //             $originalFileName = null;
    //             $imageUrl = null;
    //             if ($hasImageUrl) {
    //                 $originalFileName = $request->file("photos.$index.image")->getClientOriginalName();
    //                 $imageUrl = $request->file("photos.$index.image")->store('photos', 'public');
    //             }


    //             PhotoDetail::create([
    //                 'applicant_detail_id' => $request->application_id,
    //                 'photo_owner' => $photo['photo_owner'],
    //                 'ephoto_link' => $hasEphotoLink ? $photo['ephoto_link'] : null,
    //                 'image_url' => $imageUrl,
    //                 'originalFileName' => $originalFileName
    //             ]);
    //         }


    //         return ApiResponse::success('Spouse Detail submitted successfully!');
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage());
    //     }
    // }
    // public function photoUpdate(Request $request)
    // {
    //     try {
    //         // return $request->all();
    //         $validator = Validator::make($request->all(), [
    //             // 'application_id' => 'required|exists:applicant_details,id',
    //             'photos' => 'required|array',
    //             'photos.*.photo_owner' => 'required|string'
    //         ]);

    //         if ($validator->fails()) {
    //             return ApiResponse::error("Validation Error!", $validator->errors());
    //         }

    //         foreach ($request->photos as $index => $photo) {
    //             $hasEphotoLink = array_key_exists('ephoto_link', $photo);
    //             $hasImageUrl = $request->hasFile("photos.$index.image");
    //             $originalFileName = null;
    //             $imageUrl = null;
    //             if ($hasImageUrl) {
    //                 $originalFileName = $request->file("photos.$index.image")->getClientOriginalName();
    //                 $imageUrl = $request->file("photos.$index.image")->store('photos', 'public');
    //             }

    //             // Update or create the photo detail record
    //             PhotoDetail::updateOrCreate(
    //                 [
    //                     'applicant_detail_id' => $request->application_id,
    //                     'photo_owner' => $photo['photo_owner']
    //                 ],
    //                 [
    //                     'ephoto_link' => $hasEphotoLink ? $photo['ephoto_link'] : null,
    //                     'image_url' => $imageUrl,
    //                     'originalFileName' => $originalFileName
    //                 ]
    //             );
    //         }

    //         return ApiResponse::success('Photos updated successfully!');
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage());
    //     }
    // }




    public function photoUpdate(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'application_id' => 'required|exists:applicant_details,id',
                'photo_id' => 'required|string',
                'photo_owner' => 'required|string',  // Ensure photo_owner is included in the request
                'image' => [
                    'nullable', // Allow for no image to be present
                    'file',
                    'mimes:jpeg', // Only JPEG images allowed
                    'max:240', // Max file size of 240 KB (in kilobytes)
                    function ($attribute, $value, $fail) {
                        // Custom validation for image dimensions
                        $image = $value;
                        $imageSize = getimagesize($image->getPathname());

                        if ($imageSize === false) {
                            $fail('Unable to get image size.');
                            return;
                        }

                        $width = $imageSize[0];
                        $height = $imageSize[1];

                        // Check if image is square
                        if ($width !== $height) {
                            $fail('The image must have square dimensions (width must equal height).');
                            return;
                        }

                        // Check for minimum and maximum dimensions
                        if ($width < 600 || $height < 600) {
                            $fail('The image dimensions must be at least 600x600 pixels.');
                        } elseif ($width > 1200 || $height > 1200) {
                            $fail('The image dimensions must not exceed 1200x1200 pixels.');
                        }
                    }
                ],
                'ephoto_link' => 'nullable|string' // Optional ephoto_link
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }

            $originalFileName = null;
            $imageUrl = null;

            // Check if an image file is included in the request
            if ($request->hasFile('image')) {
                $originalFileName = $request->file('image')->getClientOriginalName();
                $imageUrl = $request->file('image')->store('photos', 'public');
            }

            $photoDetail = PhotoDetail::where('photo_id', $request->photo_id)
                ->where('photo_owner', $request->photo_owner)
                ->first();
// return $photoDetail;
            if ($photoDetail) {
                // If it exists, update the record
                $photoDetail->update([
                    'ephoto_link' => $request->input('ephoto_link', null),
                    'image_url' => $imageUrl,
                    'originalFileName' => $originalFileName,
                ]);
                return ApiResponse::success('Photo updated successfully!');
            } else {
                // If it doesn't exist, create a new record
                PhotoDetail::create([
                     // Manually set the photo_id from the request
                    'applicant_detail_id' => $request->application_id,
                    'photo_owner' => $request->photo_owner,
                    'ephoto_link' => $request->input('ephoto_link', null),
                    'image_url' => $imageUrl,
                    'originalFileName' => $originalFileName,
                    'photo_id' => $request->photo_id,
                ]);
                return ApiResponse::success('Photo created successfully!');
            }
        } catch (\Exception $e) {
            // Return a generic error message with exception details
            return ApiResponse::error('An error occurred while updating the photo: ' . $e->getMessage());
        }
    }
    public function Submission(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_id' => 'required|exists:applicant_details,id'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            FormStatus::updateOrCreate(
                ['applicant_detail_id' => $request->application_id],  // Match on this condition
                ['status' => 'submitting']  // Update with this value
            );
            return ApiResponse::success('Form is submitted successfully!');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
    public function finalSubmission(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'application_id' => 'required|exists:applicant_details,id'
            ]);

            if ($validator->fails()) {
                return ApiResponse::error("Validation Error!", $validator->errors());
            }
            FormStatus::updateOrCreate(
                ['applicant_detail_id' => $request->application_id],  // Match on this condition
                ['status' => 'submitting']  // Update with this value
            );
            return ApiResponse::success('Form is submitted successfully!');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
