<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpouseDetail extends Model
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
}
