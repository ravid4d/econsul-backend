<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyMobileCode extends Model
{
    use HasFactory;
    protected $fillable = ["user_id","mobile_number","code","otp_expires_at"];
}
