<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
use App\Models\Kindergarten;
use App\Models\Schedule;
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
        return view('therapy-schedule.index', compact('therapist', 'kindergartens'));
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $userKindergartenIds = Auth::user()->staffKindergartens()->pluck('kindergarten_id');
        $header = [
            ['name' => 'Sunday', 'id' => Auth::id().'sunday'],
            ['name' => 'Monday', 'id' => Auth::id().'monday'],
            ['name' => 'Tuesday', 'id' => Auth::id().'tuesday'],
            ['name' => 'Wednesday', 'id' => Auth::id().'wednesday'],
            ['name' => 'Thursday', 'id' => Auth::id().'thursday'],
            ['name' => 'Friday', 'id' => Auth::id().'friday'],
            ['name' => 'Saturday', 'id' => Auth::id().'saturday'],
        ];

        $events = [];
        $schedule = Schedule::filter($filter)->where('status', 'published')->first();
        if (!empty($schedule) && $schedule->events() !== null) {
            $scheduleEvents = $schedule->events()->where('therapist_id', Auth::id())->get();
            $events = scheduleResponse($scheduleEvents, $schedule);
        }

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }
}