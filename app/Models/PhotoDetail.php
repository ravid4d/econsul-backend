<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoDetail extends Model
{
    use HasFactory;

    protected $fillable = ["applicant_detail_id","photo_owner","ephoto_link","image_url","originalFileName"];
}
