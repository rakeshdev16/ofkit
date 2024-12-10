<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapySchedule extends Model
{
    use HasFactory;

    protected $fillable = [ 'therapist_id', 'type', 'day', 'frequency_repeat', 'start', 'group_name', 'description', 'file', 'start_date', 'end_date', 'draft_name', 'is_draft'];

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
            $query->where('status', $data['status']);
        }
        return $query;
    }

}
