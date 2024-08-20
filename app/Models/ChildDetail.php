<?php

namespace App\Models;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; 
class ChildDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'applicant_detail_id',
        'first_name',
        'middle_name',
        'surname',
        'gender',
        'birth_date',
        'country',
        'city'
    ];

    public function setApplicantDetailIdAttribute($value)
    {
        $this->attributes['applicant_detail_id'] = base64_encode(Crypt::encryptString($value));
    }
    // public function getApplicantDetailIdAttribute($value)
    // {
    //     return Crypt::decryptString(base64_decode($value));
    // }

    public function getIdAttribute($value)
    {
        return base64_encode(Crypt::encryptString($value));
    }

    // Decrypt id
    public function setIdAttribute($value)
    {
        $this->attributes['id'] = Crypt::decryptString(base64_decode($value));
    }

    

    // Decrypt applicant_detail_id when getting
    
}
