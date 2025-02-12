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

    protected $appends = ['cell_title', 'event_time'];

    protected static function booted()
    {
        $childrenIds = request()->children_ids;
        if (request()->type == 'individual' & !empty($childrenIds)) {
            $color = json_encode(Children::where('id', $childrenIds[0] ?? null)->pluck('color')->first());
        } else {
            $colors = [
                'group' => json_encode(["background-color: #ede0d4;", "color: #000000;"]),
                'staff-meeting' => json_encode(["background-color: #2c3e50;", "color: #ffffff;"]),
                'documentation-break' => json_encode(["background-color: #b0bec5;", "color: #000000;"]),
                'preparation' => json_encode(["background-color: #7e57c2;", "color: #ffffff;"]),
                'tutorial' => json_encode(["background-color: #006d77;", "color: #ffffff;"]),
                'other' => json_encode(["background-color: #d9a300;", "color: #000000;"]),
            ];
            $color = $colors[request()->type] ?? NULL;
        }
        static::creating(function ($schedule) use($color) {
            if (!request()->has('_is_cloning')) $schedule->color = $color;
        });

        static::updating(function ($schedule) use($color) {
            if (in_array($schedule->type, ['documentation-break', 'preparation', 'tutorial', 'other'])) {
                $schedule->group_name = NULL;
                $schedule->description = NULL;
                $schedule->file = NULL;
            }
            if (!request()->has('_is_cloning')) $schedule->color = $color;
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

    public function getEventTimeAttribute()
    {
        return (strtotime($this->end_time) - strtotime($this->start_time)) / 60;
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
        return $query->where('day', $data['day'])->where('kindergarten_id', $data['kindergartenId'])
            ->where(function ($query) use ($startTime, $endTime) {
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

    public function getWeightedCount()
    {
        return $this->frequency_repeat === 'Weekly' ? 1 :
            ($this->frequency_repeat === 'Bi-weekly' ? 0.5 :
            ($this->frequency_repeat === 'Monthly' ? 0.25 : 0));
    }
}
