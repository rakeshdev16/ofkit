<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TherapySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kindergarten_id',
        'therapist_id',
        'type',
        'day',
        'frequency_repeat',
        'start',
        'group_name',
        'description',
        'file',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'draft_name',
        'status',
        'color',
        'unique_id',
    ];

    // public function getFileAttribute()
    // {
    //     return asset('storage/' . $this->attributes['file']) ?? null;
    // }

    public function getChildrenIdsAttribute()
    {
        return isset($this->attributes['children_ids']) ? json_decode($this->attributes['children_ids'], true) : null;
    }

    public function scopeFilter($query, $data)
    {
        if (isset($data['status'])) {
            $query->whereIn('status', json_decode($data['status']));
            if (count(json_decode($data['status'])) == 1 && json_decode($data['status'])[0] == 'published') {
                $query->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'));
            }
        }

        if (isset($data['kindergarten_id'])) {
            $query->where('kindergarten_id', $data['kindergarten_id']);
        }

        if (isset($data['children_id'])) {
            $scheduleIds = TherapyScheduleChildren::where('children_id', $data['children_id'])->pluck('therapy_schedule_id')->toArray();
            $query->whereIn('id', $scheduleIds);
        }

        return $query;
    }

    public function getColorAttribute($value)
    {
        return json_decode($value);
    }
    
    public function childrens()
    {
        return $this->hasMany(TherapyScheduleChildren::class, 'therapy_schedule_id');
    }
}
