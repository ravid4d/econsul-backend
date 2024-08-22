<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyEmailCode extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","email","code","otp_expires_at"];
}
