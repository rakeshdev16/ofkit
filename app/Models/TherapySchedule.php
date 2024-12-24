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
            if (in_array('published', json_decode($data['status']))) {
                $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i');
                $query->whereDate('start_date', '>=', date('Y-m-d'))->whereDate('end_date', '<=', $weekEndDate);
            }
        }

        if (isset($data['kindergarten_id'])) {
            $query->where('kindergarten_id', $data['kindergarten_id']);
        }

        if (isset($data['children_id'])) {
            $query->where('children_id', $data['children_id']);
        }

        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $backgroundColor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $rgb = sscanf($backgroundColor, "#%02x%02x%02x");
            $luminance = (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;
            $textColor = $luminance > 0.5 ? '#000000' : '#FFFFFF';
            $model->color = json_encode([
                "background-color: $backgroundColor",
                "color: $textColor"
            ]);
        });
    }

    public function getColorAttribute($value)
    {
        return json_decode($value);
    }

    // public function therapists()
    // {
    //     return $this->hasMany(TherapyScheduleTherapist::class, 'therapy_schedule_id');
    // }
    
    public function childrens()
    {
        return $this->hasMany(TherapyScheduleChildren::class, 'therapy_schedule_id');
    }
}
