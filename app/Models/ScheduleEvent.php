<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'kindergarten_id',
        'therapist_id',
        'type',
        'day',
        'frequency_repeat',
        'frequency_repeat_at',
        'group_name',
        'description',
        'file',
        'start_time',
        'end_time',
        'color',
        'unique_id',
    ];

    protected static function booted()
    {
        static::creating(function ($schedule) {
            $childrenIds = request()->children_ids;
            if ($schedule->type === 'staff-meeting') {
                $color = json_encode(["background-color: #095F59;", "color: #fff;"]);
            } elseif (!empty($childrenIds)) {
                $color = json_encode(Children::where('id', $childrenIds[0] ?? null)->pluck('color')->first());
            } else {
                $colors = [
                    'documentation-break' => json_encode(["background-color: #8a8584;", "color: #0a0100;"]),
                    'preparation' => json_encode(["background-color: #c20c06;", "color: #fcfcfc;"]),
                    'tutorial' => json_encode(["background-color: #f2fa05;", "color: #0a0100;"]),
                    'other' => json_encode(["background-color: #05fa94;", "color: #0a0100;"]),
                    'no-child' => json_encode(["background-color:rgb(250, 5, 176);", "color: #0a0100;"]),
                ];

                $color = $colors[$schedule->type] ?? json_encode(["background-color:rgb(250, 5, 176);", "color: #0a0100;"]);
            }
            $schedule->color = $color;
        });
    }

    public function scopeRemoveUnselectedUser($query, $data)
    {
        $deletedIds = '';
        if (($data['type'] == 'group' || $data['type'] == 'staff-meeting') && !empty($data['unique_id'])) {
            $scheduleTherapistIds = $query->where('unique_id', $data['unique_id'])->pluck('therapist_id')->toArray();
            $therapistGoingToBeDelete = array_diff($scheduleTherapistIds, $data['therapist_ids'] ?? []);
            if (!empty($therapistGoingToBeDelete)) {
                $deletedIds = $query->whereIn('therapist_id', $therapistGoingToBeDelete)->where('unique_id', $data['unique_id'])->pluck('id')->toArray();
                $query->whereIn('therapist_id', $therapistGoingToBeDelete)->where('unique_id', $data['unique_id'])->delete();
            }
        }
        
        if (in_array($data['type'], ['individual', 'parental-guidance', 'documentation-break', 'preparation', 'tutorial', 'other']) && !empty($data['unique_id'])) {
            $deletedIds = $query->whereNotIn('therapist_id', $data['therapist_ids'])->where('unique_id', $data['unique_id'])->pluck('id')->toArray();
            $query->whereNotIn('therapist_id', $data['therapist_ids'])->where('unique_id', $data['unique_id'])->delete();
        }

        return $deletedIds;
    }

    public function getColorAttribute($value)
    {
        return json_decode($value);
    }

    public function childrens()
    {
        return $this->hasMany(ScheduleEventChildren::class, 'schedule_event_id');
    }
}
