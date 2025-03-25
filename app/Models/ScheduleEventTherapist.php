<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEventTherapist extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_event_id', 'therapist_id'];
}
