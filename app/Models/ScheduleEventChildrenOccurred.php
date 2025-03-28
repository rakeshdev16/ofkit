<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ScheduleEventChildrenOccurred extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_event_id', 'children_id', 'participated', 'reason', 'description', 'file', 'submitted_by'];

    protected static function booted()
    {
        static::saving(function ($scheduleEvent) {
            $scheduleEvent->submitted_by = Auth::id();
        });
    }
}
