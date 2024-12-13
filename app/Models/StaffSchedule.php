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
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
