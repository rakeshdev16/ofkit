<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KindergartenUser extends Model
{
    use HasFactory;

    protected $fillable = ['kindergarten_id', 'user_id'];
}
