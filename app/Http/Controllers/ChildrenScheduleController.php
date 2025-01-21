<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Auth, DB;

class ChildrenScheduleController extends Controller
{
    public function index(Request $request, $id)
    {
        $children = Children::where('id', $id)->first();
        return view('children.schedule.index', compact('children'));
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $header = [
            ['name' => 'Sunday', 'id' => $filter['children_id'].'sunday'],
            ['name' => 'Monday', 'id' => $filter['children_id'].'monday'],
            ['name' => 'Tuesday', 'id' => $filter['children_id'].'tuesday'],
            ['name' => 'Wednesday', 'id' => $filter['children_id'].'wednesday'],
            ['name' => 'Thursday', 'id' => $filter['children_id'].'thursday'],
            ['name' => 'Friday', 'id' => $filter['children_id'].'friday'],
            ['name' => 'Saturday', 'id' => $filter['children_id'].'saturday'],
        ];

        $scheduleIds = TherapyScheduleChildren::where('children_id', $filter['children_id'])->pluck('therapy_schedule_id')->toArray();
        $schedules = TherapySchedule::whereIn('id', $scheduleIds)->where('status', 'published')->get();
        $events = $this->scheduleResponse($schedules, $filter['children_id']);

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }

    public function scheduleResponse($schedules, $childId)
    {
        return $schedules->map(function ($schedule) use($schedules, $childId) {
            $therapistIds = $schedules->where('unique_id', $schedule->unique_id)->pluck('therapist_id')->toArray();
            return [
                'id' => $schedule->id,
                'day' => $schedule->day,
                'description' => $schedule->description,
                'start' => date('Y-m-d').' '.$schedule->start_time,
                'end' => date('Y-m-d').' '.$schedule->end_time,
                'startTime' => Carbon::parse($schedule->start_time)->format('H:i'),
                'endTime' => Carbon::parse($schedule->end_time)->format('H:i'),
                'resource' => $childId . strtolower($schedule->day),
                'therapistId' => $schedule->therapist_id,
                'therapistName' => getUserNameById($schedule->therapist_id),
                'therapistIds' => $therapistIds,
                'therapistNames' => getUserNameByIds($therapistIds),
                'childrenId' => $schedule->childrens->pluck('children_id')->toArray(),
                'childrenNames' => getChildrenNamesById($schedule->childrens->pluck('children_id')->toArray()),
                'twoChildrenNames' => getChildrenNamesById($schedule->childrens->pluck('children_id')->take(2)->toArray()),
                'type' => $schedule->type,
                'groupName' => $schedule->group_name,
                'frequencyRepeat' => $schedule->frequency_repeat,
                'frequencyRepeatAt' => $schedule->start,
                'description' => $schedule->description,
                'file' => $schedule->file,
                'color' => $schedule->color,
                'icon' => appointmentIcon($schedule->type),
                'uniqueId' => $schedule->unique_id,
            ];
        });
    }
}