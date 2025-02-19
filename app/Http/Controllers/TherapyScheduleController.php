<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
use App\Models\Kindergarten;
use App\Models\StaffKindergarten;
use App\Models\StaffSchedule;
use App\Models\Schedule;
use App\Models\ScheduleEvent;
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
        $kindergartens = Kindergarten::select('id as key', 'name as value')->whereHas('staffKindergartens', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        $kindergartens->push(['key' => 'personal', 'value' => 'Personal']);
        return view('therapy-schedule.index', compact('therapist', 'kindergartens'));
    }

    public function calendar(Request $request)
    {
        $header = [
            ['name' => 'Sunday', 'id' => Auth::id().'sunday'],
            ['name' => 'Monday', 'id' => Auth::id().'monday'],
            ['name' => 'Tuesday', 'id' => Auth::id().'tuesday'],
            ['name' => 'Wednesday', 'id' => Auth::id().'wednesday'],
            ['name' => 'Thursday', 'id' => Auth::id().'thursday'],
            ['name' => 'Friday', 'id' => Auth::id().'friday'],
            ['name' => 'Saturday', 'id' => Auth::id().'saturday'],
        ];

        if ($request->kindergarten_id == 'personal') {
            $kindergartenId = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $scheduleIds = Schedule::filter(['status' => 'published'])->whereIn('kindergarten_id', $kindergartenId)->pluck('id');
            $scheduleEvents = ScheduleEvent::whereIn('schedule_id', $scheduleIds)->where('therapist_id', Auth::id())->get();
            $schedule = '';
        } else {
            $kindergartenId[] = $request->kindergarten_id;
            $schedule = Schedule::filter(['status' => 'published'])->where('kindergarten_id', $request->kindergarten_id)->first();
            $scheduleEvents = !empty($schedule) ? collect($schedule->events()->where('therapist_id', Auth::id())->get()) : collect([]);
        }

        $events = [];
        if ($scheduleEvents !== null) {
            $events = scheduleResponse($scheduleEvents, $schedule);
        }

        $staffTimeSlots = StaffSchedule::whereIn('kindergarten_id', $kindergartenId)->get()->map(function ($schedule) {
            return [
                'resource' => $schedule->user_id.''.$schedule->day,
                'startTime' => $schedule->start_time,
                'endEnd' => $schedule->end_time,
            ];
        });

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
            'staffTimeSlots' => $staffTimeSlots,
        ]);
    }
}