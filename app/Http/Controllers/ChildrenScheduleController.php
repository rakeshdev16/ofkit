<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\TherapyScheduleChildren;
use App\Models\TherapySchedule;
use App\Models\Schedule;
use App\Models\StaffSchedule;
use App\Models\StaffKindergarten;
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
        // $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        // $header = [];
        // foreach ($days as $day) {
        //     $schedules = StaffSchedule::with('user')->where('day', $day)->get()
        //         ->map(function ($schedule) use ($day, $request) {
        //             $f_name = $schedule->user->family_name;
        //             return [
        //                 'id' => $schedule->user->id.''.strtolower($day),
        //                 'user_id' => $schedule->user->id,
        //                 'name' => $schedule->user->name ?? 'N/A',
        //                 'first_name' => $schedule->user->first_name ?? 'N/A',
        //                 'family_name' => $f_name ? mb_substr($f_name, 0, 1) . '.' : '',
        //                 'association' => @StaffKindergarten::where(['user_id' => $schedule->user_id])->first()->association->name,
        //                 'profession' => @StaffKindergarten::where(['user_id' => $schedule->user_id])->first()->profession->acronyms,
        //             ];
        //         })->unique('id')->values()->toArray();

        //     $header[] = [
        //         'name' => $day,
        //         'children' => $schedules,
        //     ];
        // }

        $header = [
            ['name' => 'Sunday', 'id' =>  $filter['children_id'].'sunday'],
            ['name' => 'Monday', 'id' =>  $filter['children_id'].'monday'],
            ['name' => 'Tuesday', 'id' =>  $filter['children_id'].'tuesday'],
            ['name' => 'Wednesday', 'id' =>  $filter['children_id'].'wednesday'],
            ['name' => 'Thursday', 'id' =>  $filter['children_id'].'thursday'],
            ['name' => 'Friday', 'id' =>  $filter['children_id'].'friday'],
            ['name' => 'Saturday', 'id' =>  $filter['children_id'].'saturday'],
        ];

        $events = [];
        $schedule = '';
        $scheduleIds = Schedule::filter(['status' => 'published'])->pluck('id');
        $scheduleEvents = ScheduleEvent::whereIn('schedule_id', $scheduleIds)->whereHas('childrens', function($query) use ($filter) {
                $query->where('children_id', $filter['children_id']);
            })->get();
        // echo '<pre>'; print_r($schedule); die;
        // if (!empty($schedule) && $schedule->events() !== null) {
        //     $scheduleEvents = $schedule->events()->whereHas('childrens', function($query) use ($filter) {
        //         $query->where('children_id', $filter['children_id']);
        //     })->get();
        //     $events = scheduleResponse($scheduleEvents, $schedule, $filter['children_id']);
        // }

        $events = scheduleResponse($scheduleEvents, $schedule, $filter['children_id']);

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }
}