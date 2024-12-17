<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapyScheduleTherapist extends Model
{
    use HasFactory;

    protected $fillable = ['therapy_schedule_id', 'therapist_id'];
}
