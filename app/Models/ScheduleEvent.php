<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ScheduleEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'type',
        'day',
        'frequency_repeat',
        'frequency_repeat_at',
        'group_name',
        'description',
        'file',
        'start_time',
        'end_time',
        'added_by',
    ];

    protected $appends = ['cell_title', 'event_time', 'color'];

    protected static function booted()
    {
        static::saving(function ($scheduleEvent) {
            if (!request()->has('_is_cloning')) {
                // $scheduleEvent->color = static::getColorFromRequest();
                $scheduleEvent->added_by = Auth::id();
            }
        });

        static::updating(function ($schedule) {
            if (in_array($schedule->type, ['documentation-break', 'preparation', 'tutorial', 'other'])) {
                $schedule->group_name = NULL;
                $schedule->description = NULL;
                $schedule->file = NULL;
            }
        });
    }

    // protected static function getColorFromRequest()
    // {
    //     $childrenIds = request()->children_ids;
    //     if ((request()->type == 'individual' || request()->type == 'parental-guidance') && !empty($childrenIds)) {
    //         return json_encode(Children::where('id', $childrenIds[0] ?? null)->pluck('color')->first());
    //     }
    //     $colors = [
    //         'group' => json_encode(["background-color: #ede0d4;", "color: #000000;"]),
    //         'staff-meeting' => json_encode(["background-color: #2c3e50;", "color: #ffffff;"]),
    //         'documentation-break' => json_encode(["background-color: #b0bec5;", "color: #000000;"]),
    //         'preparation' => json_encode(["background-color: #7e57c2;", "color: #ffffff;"]),
    //         'tutorial' => json_encode(["background-color: #006d77;", "color: #ffffff;"]),
    //         'other' => json_encode(["background-color: #d9a300;", "color: #000000;"]),
    //     ];
    //     return $colors[request()->type] ?? null;
    // }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // public function scopeRemoveUnselectedUser($query, $data)
    // {
    //     $deletedIds = '';
    //     if (($data['type'] == 'group' || $data['type'] == 'staff-meeting') && !empty($data['event_id'])) {
    //         $scheduleTherapistIds = $this->where('id', $data['event_id'])->first()->therapists->pluck('therapist_id')->toArray();
    //         $therapistGoingToBeDelete = array_diff($scheduleTherapistIds, $data['therapist_ids'] ?? []);
    //         if (!empty($therapistGoingToBeDelete)) {
    //             $deletedIds = $query->whereHas('therapists', function ($query) use ($data, $therapistGoingToBeDelete) {
    //                 $query->whereIn('therapist_id', $therapistGoingToBeDelete);
    //             })->where('id', $data['event_id'])->pluck('id')->toArray();
    //             $query->whereHas('therapists', function ($query) use ($data, $therapistGoingToBeDelete) {
    //                 $query->whereIn('therapist_id', $therapistGoingToBeDelete);
    //             })->where('id', $data['event_id'])->delete();
    //         }
    //     }
        
    //     if (in_array($data['type'], ['individual', 'parental-guidance', 'documentation-break', 'preparation', 'tutorial', 'other']) && !empty($data['event_id'])) {
    //         $deletedIds = $query->whereHas('therapists', function ($query) use ($data) {
    //                 $query->whereNotIn('therapist_id', $data['therapist_ids']);
    //             })->where('id', $data['event_id'])->pluck('id')->toArray();
    //         $query->whereHas('therapists', function ($query) use ($data) {
    //                 $query->whereNotIn('therapist_id', $data['therapist_ids']);
    //             })->where('id', $data['event_id'])->delete();
    //     }

    //     return $deletedIds;
    // }

    public function getColorAttribute($value)
    {
        $childColor = isset($this->childrens) ? $this->childrens[0]->children->color : [];
        $colors = [
            'individual' => $childColor,
            'group' => ["background-color: #ede0d4;", "color: #000000;"],
            'parental-guidance' => $childColor,
            'staff-meeting' => ["background-color: #2c3e50;", "color: #ffffff;"],
            'documentation-break' => ["background-color: #b0bec5;", "color: #000000;"],
            'preparation' => ["background-color: #7e57c2;", "color: #ffffff;"],
            'tutorial' => ["background-color: #006d77;", "color: #ffffff;"],
            'other' => ["background-color: #d9a300;", "color: #000000;"],
        ];
        return $colors[$this->type];
    }

    public function getEventTimeAttribute()
    {
        return (strtotime($this->end_time) - strtotime($this->start_time)) / 60;
    }

    public function therapists()
    {
        return $this->hasMany(ScheduleEventTherapist::class, 'schedule_event_id');
    }

    public function childrens()
    {
        return $this->hasMany(ScheduleEventChildren::class, 'schedule_event_id');
    }

    public function getCellTitleAttribute()
    {
        $isBold = $this->event_time >= 30 ? "font-weight: bold;" : "";
        $title = $this->childrens->pluck('children_id')->map(function ($childId) {
            $name = Children::where('id', $childId)->select('name', 'family_name')->first();
            $firstName = $name->name ?? '';
            $lastName = $name->family_name ?? '';
            $lastInitial = $lastName ? mb_substr($lastName, 0, 1) . '.' : '';
            return $firstName . ' ' . $lastInitial;
        })->take(2)->join(' ');

        if (\Route::currentRouteName() == 'children-schedule.calendar') {
            $therapistName = getUserNameById($this->therapists[0]->therapist_id);
            $profession = User::where('id', $this->therapists[0]->therapist_id)->first()->profession->acronyms;
            if (($this->type == 'group')) {
                return '<div style="'.$isBold.'"><div style="'.$isBold.'">'.$this->group_name.':</div>'.$title.'<br>'.$therapistName.'<br>'.$profession.'</div>';
            }
            return '<div style="'.$isBold.'">'.$therapistName.'<br>'.$profession.'</div>';
        }
        if ($this->type == 'staff-meeting') return '<div style="'.$isBold.'">Staff Metting: <br>'.$title.'</div>';
        if ($this->type == 'group') return '<div style="font-size: 16px;""><div style="'.$isBold.'">'.$this->group_name.':</div>'.$title.'</div>';
        if ($this->type == 'individual') return '<div style="'.$isBold.'">'.$title.'</div>';
        if ($this->type == 'parental-guidance') return '<div style="'.$isBold.'">'.$title.'</div>';

        return '<div>'.ucfirst(str_replace('-', ' ', $this->type)).'</div>';

    }

    public function scopeOverlappingWithTimeSlot($query, $data)
    {
        $startTime = @$data['startTime'];
        $endTime = @$data['endTime'];
        return $query->where('day', $data['day'])->whereHas('schedule', function ($query) use ($data) {
                $query->where('kindergarten_id', $data['kindergartenId']);
            })->where(function ($query) use ($startTime, $endTime) {
                $query->whereTime('start_time', '<', $endTime . ':00')->whereTime('end_time', '>', $startTime . ':00');
            });
    }

    public function scopeWeekly($query)
    {
        return $query->where('frequency_repeat', 'Weekly');
    }

    public function scopeBiWeekly($query)
    {
        return $query->where('frequency_repeat', 'Bi-weekly');
    }

    public function scopeMonthly($query)
    {
        return $query->where('frequency_repeat', 'Monthly');
    }

    // public function getWeightedCount()
    // {
    //     return $this->frequency_repeat === 'Weekly' ? 1 :
    //         ($this->frequency_repeat === 'Bi-weekly' ? 0.5 :
    //         ($this->frequency_repeat === 'Monthly' ? 0.25 : 0));
    // }

    public function getWeightedCount($type)
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        $duration = $start->diffInMinutes($end);

        $children = 0;
        $staff = 0;

        if ($duration <= 30) $children = 0.5;
        if ($duration > 30 && $duration <= 60) $children = 1;
        if ($duration == 75) $children = 1.5;
        if ($duration == 90) $children = 2;

        $staff = $duration / 60 * 1;

        if ($type == 'children') return $children;
        if ($type == 'staff') return $staff;
    }
}
