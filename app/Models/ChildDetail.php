<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_detail_id',
        'name',
        'middle_name',
        'birth_date',
        'country',
        'city'
    ];
}
