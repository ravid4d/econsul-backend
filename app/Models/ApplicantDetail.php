<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDetail extends Model
{
    use HasFactory;

    protected $fillable =[
          'user_id',
          'eligibility_status',
          'education_level', 
          'personal_info', 
          'contact_info', 
          'spouse_info', 
          'children_info'
    ];

    protected $casts = ["eligibility_status" => 'array',"personal_info"=>'array',"contact_info"=>'array',"spouse_info"=>'array'];
  
    public function formStatuses()
    {
        return $this->hasMany(FormStatus::class);
    }

}
