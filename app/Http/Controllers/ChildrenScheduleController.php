<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
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

        $events = [];
        $schedule = Schedule::where('status', 'published')->first();
        if (!empty($schedule) && $schedule->events() !== null) {
            $scheduleEvents = $schedule->events()->whereHas('childrens', function($query) use ($filter) {
                $query->where('children_id', $filter['children_id']);
            })->get();
            $events = scheduleResponse($scheduleEvents, $filter['children_id']);
        }

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }
}