<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    use HasFactory;
    protected $fillable = ["applicant_detail_id","status","	confirmation_no","digital_sign"];
}
