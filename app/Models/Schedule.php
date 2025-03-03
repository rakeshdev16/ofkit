<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'kindergarten_id',
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
            if (is_array($data['kindergarten_id'])) {
                $query->whereIn('kindergarten_id', $data['kindergarten_id']);
            } else {
                Session::put('kindergarten_id', $data['kindergarten_id']);
                $query->where('kindergarten_id', $data['kindergarten_id']);
            }
        }

        if (isset($data['children_id'])) {
            $query->with('events', function ($q) use ($data) {
                $q->whereHas('childrens', function ($qq) use ($data) {
                    $qq->where('children_id', $data['children_id']);
                });
            });
        }

        return $query;
    }

    public function events()
    {
        return $this->hasMany(ScheduleEvent::class, 'schedule_id');
    }
}
