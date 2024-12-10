<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\StaffSchedule;
use App\Models\TherapySchedule;
use App\Models\User;
use App\Models\Kindergarten;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TherapyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $therapists = User::role('therapist')->orderBy('name')->get(['id', 'name']);
        $childrens = Children::orderBy('name')->get(['id', 'name']);
        $kindergartens = Kindergarten::select('id', 'name')->get();
        return view('therapy-schedule.index', compact('therapists', 'childrens', 'kindergartens'));
    }

    public function create()
    {
        $therapists = User::role('therapist')->orderBy('name')->get(['id', 'name']);
        $childrens = Children::orderBy('name')->get(['id', 'name']);
        $kindergartens = Kindergarten::select('id', 'name')->get();
        $createdEventIds = TherapySchedule::where('status', 'created')->pluck('id')->toArray();
        $createdEventIds = json_encode($createdEventIds);
        return view('therapy-schedule.create', compact('therapists', 'childrens', 'kindergartens', 'createdEventIds'));
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request['file'] = uploadFile($request->image, 'public/therapy-schedule', $request->extension);
        }

        $event = TherapySchedule::create($request->all());
        $event->resource = $request->therapist_id . strtolower($request->day);
        $event->therapistName = getUserNameById($request->therapist_id);
        return response()->json(['status' => true, 'message' => 'Event detail has been successfully saved!', 'event' => $event]);
    }

    public function update(Request $request)
    {
        if (TherapySchedule::whereIn('id', json_decode($request->ids))->update(['status' => $request->status])) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully '.$request->status.'!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }

    public function calendar(Request $request)
    {
        $therapist = $request->input('therapist');
        $event = $request->input('event');

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $header = [];
        foreach ($days as $day) {
            $schedules = StaffSchedule::filter($therapist)->with('user')->where('day', $day)->get()
                ->map(function ($schedule) use ($day) {
                    return [
                        'id' => $schedule->user->id.''.strtolower($day),
                        'name' => $schedule->user->name ?? 'N/A',
                        'workingHours' => [
                            'start' => date('H:i', strtotime($schedule->start_time)),
                            'end' => date('H:i', strtotime($schedule->end_time))
                        ]
                    ];
                })->unique('id')->values()->toArray();

            $header[] = [
                'name' => $day,
                'children' => $schedules,
            ];
        }

        $schedules = TherapySchedule::filter($event)->orderBy('start_date')->get();
        $events = $schedules->map(function ($schedule) {
            $scheduleTime = Carbon::parse($schedule->start_date);
            return [
                'id' => $schedule->id,
                'description' => $schedule->description,
                'start' => Carbon::parse($schedule->start_date)->format('Y-m-d H:i:s'),
                'end' => Carbon::parse($schedule->end_date)->format('Y-m-d H:i:s'),
                'resource' => $schedule->therapist_id . strtolower($schedule->day),
                'therapistName' => getUserNameById($schedule->therapist_id),
                'type' => $schedule->type,
                'groupName' => $schedule->group_name,
                'frequencyRepeat' => $schedule->frequency_repeat,
                'frequencyRepeatAt' => $schedule->start,
                'description' => $schedule->description,
                'file' => $schedule->file,
                'workingTime' => true
            ];
        });

        return response()->json(['calenderHeader' => $header, 'calenderEvents' => $events]);
    }
}
