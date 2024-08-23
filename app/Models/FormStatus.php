<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    use HasFactory;
    protected $fillable = ["applicant_detail_id","status","	confirmation_no","digital_sign"];
    public function getIdAttribute($value)
    {
        return base64_encode($value);
    }

    // Mutator to Base64 decode 'id'
    

    // Accessor to Base64 encode 'applicant_detail_id'
    public function getApplicantDetailIdAttribute($value)
    {
        return base64_encode($value);
    }

    // Mutator to Base64 decode 'applicant_detail_id'
    // public function setApplicantDetailIdAttribute($value)
    // {
    //     $this->attributes['applicant_detail_id'] = base64_decode($value);
    // }
}
