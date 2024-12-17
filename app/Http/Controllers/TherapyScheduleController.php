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
use DB;

class TherapyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $kindergartens = Kindergarten::select('id', 'name')->get();
        return view('therapy-schedule.index', compact('kindergartens'));
    }

    public function create()
    {
        $kindergartens = Kindergarten::select('id', 'name')->get();
        $createdEventIds = TherapySchedule::where('status', 'draft')->pluck('id')->toArray();
        $createdEventIds = count($createdEventIds) > 0 ? json_encode($createdEventIds) : null;
        return view('therapy-schedule.create', compact('kindergartens', 'createdEventIds'));
    }

    public function store(Request $request)
    {
        // echo '<pre>'; print_r($request->all()); die;
        DB::beginTransaction();
        try {

            if ($request->hasFile('image')) {
                $request['file'] = uploadFile($request->image, 'public/therapy-schedule', $request->extension);
            } else {
                $request['file'] = $request->old_image;
            }

            $event = TherapySchedule::updateOrCreate(['id' => $request->id], $request->all());
            $event->childrens()->delete();
            if (isset($request->children_id) && count($request->children_id) > 0) {
                foreach ($request->children_id as $childrenId) {
                    $event->childrens()->create(['children_id' => $childrenId]);
                }
            }
            $event->resource = $request->therapist_id . strtolower($request->day);
            $event->isCreated = isset($request->id) ? false : true;

        DB::commit();
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully saved!', 'event' => $event]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        if (TherapySchedule::whereIn('id', json_decode($request->ids))->update(['status' => $request->status])) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully published!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }
    
    public function delete(Request $request)
    {
        if (TherapySchedule::whereIn('id', $request->ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully deleted!']);
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
                        'user_id' => $schedule->user->id,
                        'name' => $schedule->user->name ?? 'N/A',
                    ];
                })->unique('id')->values()->toArray();

            $header[] = [
                'name' => $day,
                'children' => $schedules,
            ];
        }

        $schedules = TherapySchedule::filter($event)->orderBy('start_time')->get();
        $events = $schedules->map(function ($schedule) {
            $scheduleTime = Carbon::parse($schedule->start_time);
            return [
                'id' => $schedule->id,
                'day' => $schedule->day,
                'description' => $schedule->description,
                'start' => Carbon::parse($schedule->start_time)->format('Y-m-d H:i:s'),
                'startTime' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end' => Carbon::parse($schedule->end_time)->format('Y-m-d H:i:s'),
                'resource' => $schedule->therapist_id . strtolower($schedule->day),
                'therapistId' => $schedule->therapist_id,
                'childrenId' => $schedule->childrens->pluck('children_id')->toArray(),
                'therapistName' => getUserNameById($schedule->therapist_id),
                'type' => $schedule->type,
                'groupName' => $schedule->group_name,
                'frequencyRepeat' => $schedule->frequency_repeat,
                'frequencyRepeatAt' => $schedule->start,
                'description' => $schedule->description,
                'file' => $schedule->file,
                'color' => $schedule->color,
            ];
        });
        $childrens = Children::select('id as key', 'name as value')->where('kindergarten_id', $therapist['kindergarten_id'])->orderBy('name')->get()->toArray();
        $users = StaffSchedule::filter($therapist)->with('user')->select('user_id')->distinct('user_id')->get()
            ->map(function ($schedule) {
                return [
                    'key' => $schedule->user_id,
                    'value' => $schedule->user->name ?? 'N/A',
                ];
            })->toArray();       
        $therapistDropdown = view('components.multi-select-input', [
            'name' => "therapist_id", 'class' => 'selectTherapist', 'id' => 'therapist', 'icon' => 'buildings', 'options' => $users,
        ])->render();
        $childrensDropdown = view('components.multi-select-input', [
            'name' => "children_id[]", 'class' => 'selectChildrens', 'id' => 'children', 'icon' => 'buildings', 'options' => $childrens,
        ])->render();
        
        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
            'childrens' => $childrens,
            'therapistDropdown' => $therapistDropdown,
            'childrensDropdown' => $childrensDropdown
        ]);
    }
}
