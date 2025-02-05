<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'published_by',
    ];

    public function scopeFilter($query, $data)
    {
        if (isset($data['status'])) {
            $query->where('status', $data['status']);
            if ($data['status'] == 'published') {
                $query->whereDate('start_date', '<=', date('Y-m-d').' 00:00:00')->whereDate('end_date', '>=', date('Y-m-d').' 00:00:00');
            }
        }

        if (isset($data['kindergarten_id'])) {
            $query->whereHas('events', function ($q) use ($data) {
                $q->where('kindergarten_id', $data['kindergarten_id']);
            });
        }

        if (isset($data['children_id'])) {
            $query->whereHas('events.childrens', function ($q) use ($data) {
                $q->where('children_id', $data['children_id']);
            });
        }

        return $query;
    }

    public function events()
    {
        return $this->hasMany(ScheduleEvent::class, 'schedule_id');
    }
}
