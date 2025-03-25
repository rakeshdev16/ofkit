<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEventChildren extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_event_id', 'children_id'];

    public function children()
    {
        return $this->HasOne(Children::class, 'id', 'children_id');
    }

    public function event()
    {
        return $this->belongsTo(ScheduleEvent::class, 'schedule_event_id');
    }

    public function child()
    {
        return $this->belongsTo(Children::class, 'children_id');
    }
}
