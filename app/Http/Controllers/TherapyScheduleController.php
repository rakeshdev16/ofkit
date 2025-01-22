<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
use App\Models\Kindergarten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Auth, DB;

class TherapyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $therapist = Auth::user();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get();
        return view('therapy-schedule.index', compact('therapist', 'kindergartens'));
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $header = [
            ['name' => 'Sunday', 'id' => Auth::id().'sunday'],
            ['name' => 'Monday', 'id' => Auth::id().'monday'],
            ['name' => 'Tuesday', 'id' => Auth::id().'tuesday'],
            ['name' => 'Wednesday', 'id' => Auth::id().'wednesday'],
            ['name' => 'Thursday', 'id' => Auth::id().'thursday'],
            ['name' => 'Friday', 'id' => Auth::id().'friday'],
            ['name' => 'Saturday', 'id' => Auth::id().'saturday'],
        ];

        $schedules = TherapySchedule::filter($filter)->where(['therapist_id' => Auth::id(), 'status' => 'published'])->get();
        $events = $this->scheduleResponse($schedules);

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }

    public function scheduleResponse($schedules)
    {
        return $schedules->map(function ($schedule) use($schedules) {
            $therapistIds = $schedules->where('unique_id', $schedule->unique_id)->pluck('therapist_id')->toArray();
            return [
                'id' => $schedule->id,
                'day' => $schedule->day,
                'description' => $schedule->description,
                'start' => date('Y-m-d').' '.$schedule->start_time,
                'end' => date('Y-m-d').' '.$schedule->end_time,
                'startTime' => Carbon::parse($schedule->start_time)->format('H:i'),
                'endTime' => Carbon::parse($schedule->end_time)->format('H:i'),
                'resource' => $schedule->therapist_id . strtolower($schedule->day),
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