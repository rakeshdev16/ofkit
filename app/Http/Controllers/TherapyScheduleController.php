<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\StaffSchedule;
use App\Models\TherapySchedule;
use App\Models\User;
use App\Models\Kindergarten;
use App\Models\StaffKindergarten;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB, Auth, DateTime;

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
        $createdEventIds = TherapySchedule::where('status', 'draft')->pluck('unique_id')->toArray();
        $createdEventIds = count($createdEventIds) > 0 ? json_encode($createdEventIds) : null;
        return view('therapy-schedule.create', compact('kindergartens', 'createdEventIds'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            if ($request->hasFile('image')) {
                $request['file'] = uploadFile($request->image, 'public/therapy-schedule');
            } else {
                $request['file'] = $request->old_image;
            }

            if (($request->type == 'group' || $request->type == 'staff-meeting') && !empty($request->unique_id)) {
                $scheduleTherapistIds = TherapySchedule::where('unique_id', $request->unique_id)->pluck('therapist_id')->toArray();
                $therapistGoingToBeDelete = array_diff($scheduleTherapistIds, $request->therapist_ids ?? []);
                 if (!empty($therapistGoingToBeDelete)) {
                    TherapySchedule::whereIn('therapist_id', $therapistGoingToBeDelete)->where('unique_id', $request->unique_id)->delete();
                }
            }

            $request['unique_id'] = $request->unique_id ? $request->unique_id : Str::uuid();
            $status = json_decode($request->status);
            foreach ($request->therapist_ids as $key => $therapistId) {
                $request['therapist_id'] = $therapistId;
                $event = TherapySchedule::updateOrCreate(['therapist_id' => $therapistId, 'unique_id' => $request->unique_id], $request->except('status', 'mode'));
                $event->childrens()->delete();
                if (isset($request->children_ids) && count($request->children_ids) > 0) {
                    foreach ($request->children_ids as $childrenId) {
                        $event->childrens()->create(['children_id' => $childrenId]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully saved as draft!', 'event' => $event]);
        } catch (\Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        if (TherapySchedule::whereIn('unique_id', json_decode($request->ids))->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ])) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully published!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }
    
    public function delete(Request $request)
    {
        if (TherapySchedule::whereIn('unique_id', $request->ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully deleted!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $event = $request->input('event');
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $header = []; 
        foreach ($days as $day) {
            $schedules = StaffSchedule::filter($filter)->with('user')->where('day', $day)->get()
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

        $schedules = TherapySchedule::filter($filter)->orderBy('start_time')->get();
        $events = $schedules->map(function ($schedule) use($schedules) {
            $scheduleTime = Carbon::parse($schedule->start_time);
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
        $userIds = StaffKindergarten::where('kindergarten_id', $filter['kindergarten_id'])->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)->select('id', 'name')->get()->toArray();
        $childrens = Children::select('id as key', 'name as value')->where('kindergarten_id', $filter['kindergarten_id'])->orderBy('name')->get()->toArray();

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
            'childrens' => $childrens,
            'childrenId' => @$filter['children_id'],
            'users' => $users,
            'usersId' => @$filter['user_id'],
        ]);
    }

    public function filterFormData(Request $request)
    {
        $data = $request->all();
        $data['childrens'] = Children::select('id as key', DB::raw('CONCAT(name, " ", family_name) as value'))->where('kindergarten_id', $data['kindergarten_id'])->orderBy('name')->get()->toArray();
        $data['therapists'] = StaffSchedule::filter($data)->with('user')->select('user_id')->distinct('user_id')->get()
            ->map(function ($schedule) {
                return [
                    'key' => $schedule->user_id,
                    'value' => $schedule->user->name ?? 'N/A',
                ];
            })->toArray();

        $view = view('components.schedule-form', ['data' => $data])->render();
        return response()->json($view);
    }

    public function checkTimeSlot(Request $request)
    {
        $checkSlot = TherapySchedule::where('start_time', '<=', $request['endTime'])->where('end_time', '>=', $request['startTime']);
        switch ($request->type) {
            case 'therapist':
                $checkSlot = $checkSlot->where('therapist_id', $request['id']);
                if ($request['frequencyRepeat'] === 'Weekly') {
                    $checkSlot = $checkSlot->where('frequency_repeat', 'Weekly')->exists();
                } else {
                    $checkSlot = false;
                }
                break;

            case 'children':
                $checkSlot = $checkSlot->whereHas('childrens', function ($query) use ($request) {
                                $query->where('children_id', $request['id']);
                            })->exists();
            break;
        }

        return response()->json([
            'status' => $checkSlot,
            'message' => $checkSlot ? 'This ' . $request->type . ' is already assigned to another on the same time' : ''
        ]);
    }

}
