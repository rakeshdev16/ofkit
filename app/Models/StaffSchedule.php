<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'day', 'start_time', 'end_time', 'kindergarten_id'];

    public function scopeFilter($query, $data)
    {
        if (isset($data['kindergarten_id'])) {
            $userIds = Kindergarten::findOrFail($data['kindergarten_id'])->staffKindergartens->pluck('user_id');
            $query->whereIn('user_id', $userIds);
        }
        if (isset($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }
        if (isset($data['day'])) {
            $query->where('day', $data['day']);
        }
        if (isset($data['startTime']) && isset($data['endTime'])) {
            $therapistIds = TherapySchedule::where('start_time', '<=', $data['startTime'])
                ->where('end_time', '>=', $data['endTime'])
                ->where('therapist_id', $data['therapistIds'][0])
                ->pluck('therapist_id')->toArray();
            $query->whereNotIn('user_id', $therapistIds);
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
