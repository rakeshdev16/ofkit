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
            $userIds = StaffKindergarten::where('kindergarten_id', $data['kindergarten_id'])->pluck('user_id')->toArray();
            $query->whereIn('user_id', $userIds)->where('kindergarten_id', $data['kindergarten_id']);
        }
        if (isset($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }
        if (isset($data['day'])) {
            $query->where('day', $data['day']);
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
