<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Crypt;

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

    // Accessor to encrypt 'id'
    public function getIdAttribute($value)
    {
        return Crypt::encrypt($value);
    }

    // Mutator to decrypt 'id'
    public function setIdAttribute($value)
    {
        $this->attributes['id'] = Crypt::decrypt($value);
    }

    // Accessor to encrypt 'applicant_detail_id'
    public function getApplicantDetailIdAttribute($value)
    {
        return Crypt::encrypt($value);
    }

    // Mutator to decrypt 'applicant_detail_id'
    public function setApplicantDetailIdAttribute($value)
    {
        $this->attributes['applicant_detail_id'] = Crypt::decrypt($value);
    }
}
