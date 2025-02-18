<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\StaffSchedule;
use App\Models\ScheduleEvent;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Kindergarten;
use App\Models\StaffKindergarten;
use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB, Auth, DateTime;
use App\Exports\CalendarExport;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value');
        if (Auth::user()->hasRole('manager')) {
            $kindergartens->whereHas('staffKindergartens', function($query) {
                $query->where('user_id', Auth::id());
            });
        }
        $kindergartens = $kindergartens->get()->toArray();
        $schedule = Schedule::filter($request->all())->first();
        return view('schedule.index', compact('kindergartens', 'schedule'));
    }

    public function create()
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        return view('schedule.create', compact('kindergartens'));
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

            $schedule = Schedule::firstOrCreate(['status' => 'draft']);
            $deletedIds = $schedule->events()->removeUnselectedUser($request->all());
            $request['unique_id'] = $request->unique_id ? $request->unique_id : Str::uuid();
            foreach ($request->therapist_ids as $key => $therapistId) {
                $request['therapist_id'] = $therapistId;
                $event = $schedule->events()->updateOrCreate(
                    ['therapist_id' => $therapistId, 'unique_id' => $request->unique_id],
                    $request->except('mode', 'schedule_id')
                );
                $event->childrens()->delete();
                if (isset($request->children_ids) && count($request->children_ids) > 0) {
                    foreach ($request->children_ids as $childrenId) {
                        $event->childrens()->create(['children_id' => $childrenId]);
                    }
                }
            }
            // $events = $schedule->events()->where('unique_id', $request->unique_id)->get();
            $events = $schedule->events()->whereIn('therapist_id', $request->therapist_ids)->where([
                    'day' => $request->day,
                    'start_time' => $request->start_time
                ])->get();
            $event = scheduleResponse($events, $schedule);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Event detail has been successfully saved as draft!',
                'event' => $event,
                'deletedIds' => $deletedIds
            ]);

        } catch (\Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
            if ($request->isAgree == 'false') {
                $existsSchedule = Schedule::where('status', 'published')->where(function ($query) use ($startDate, $endDate) {
                    $query->whereDate('start_date', '<=', $endDate)->whereDate('end_date', '>=', $startDate);
                })->exists();
                if ($existsSchedule) {
                    return response()->json(['status' => false, 'message' => 'A published event already exists between the entered date range!']);
                }
            } else {
                Schedule::filter(['status' => 'published'])->first()->delete();
            }

            $schedule = Schedule::where('status', 'draft')->first();
            $clonedSchedule = Schedule::create([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'published',
                'published_by' => $schedule->id,
            ]);
            $this->cloneSchedule($schedule, $clonedSchedule);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Schedule and associated events have been successfully published!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function delete(Request $request)
    {
        $ids = ScheduleEvent::whereIn('unique_id', $request->ids)->pluck('id')->toArray();
        if (ScheduleEvent::whereIn('unique_id', $request->ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully deleted!', 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }

    public function deleteSchedule(Request $request)
    {
        DB::beginTransaction();
        try {

            Schedule::where('status', 'draft')->delete();
            if ($request->type == 'edit') {
                $schedule = Schedule::filter(['status' => 'published'])->first();
                if (!Schedule::where('id', $request->scheduleId)->exists()) {
                    $clonedSchedule = Schedule::create(['status' => 'draft']);
                    $this->cloneSchedule($schedule, $clonedSchedule);
                }
            }

            DB::commit();
            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $header = [];
        foreach ($days as $day) {
            $schedules = StaffSchedule::filter($filter)->with('user')->where('day', $day)->get()
                ->map(function ($schedule) use ($day, $request) {
                    $f_name = $schedule->user->family_name;
                    return [
                        'id' => $schedule->user->id.''.strtolower($day),
                        'user_id' => $schedule->user->id,
                        'name' => $schedule->user->name ?? 'N/A',
                        'first_name' => $schedule->user->first_name ?? 'N/A',
                        'family_name' => $f_name ? mb_substr($f_name, 0, 1) . '.' : '',
                        'association' => @StaffKindergarten::where(['user_id' => $schedule->user_id, 'kindergarten_id' => $request->kindergarten_id])->first()->association->name,
                        'profession' => @StaffKindergarten::where(['user_id' => $schedule->user_id, 'kindergarten_id' => $request->kindergarten_id])->first()->profession->acronyms,
                    ];
                })->unique('id')->values()->toArray();

            $header[] = [
                'name' => $day,
                'children' => $schedules,
            ];
        }

        $schedule = Schedule::filter($filter)->first();
        $events = !empty($schedule) ? scheduleResponse($schedule->events, $schedule) : [];
        $userIds = StaffKindergarten::where('kindergarten_id', $filter['kindergarten_id'])->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)->select('id as key', 'name as value')->get()->toArray();
        $childrens = Children::select('id as key', 'name as value')->where('kindergarten_id', $filter['kindergarten_id'])->orderBy('name')->get()->toArray();
        $childrens = view('components.select-input', [
            'name' => '',
            'id' => 'childrenFilter',
            'icon' => 'buildings',
            'value' => @$filter['children_id'],
            'onchange' => "filterCalendar({ 'children_id': this.value })",
            'isSelectOption' => 'Select Children',
            'options' => $childrens,
        ])->render();
        $users = view('components.select-input', [
            'name' => '',
            'id' => 'staffFilter',
            'icon' => 'buildings',
            'value' => @$filter['user_id'],
            'onchange' => "filterCalendar({ 'user_id': this.value })",
            'isSelectOption' => 'Select Staff',
            'options' => $users,
        ])->render();

        $staffTimeSlots = StaffSchedule::where('kindergarten_id', $request->kindergarten_id)->get()->map(function ($schedule) {
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
            'childrens' => $childrens,
            'users' => $users,
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
        $data = $request->all();
        $isTimeOutSide = false;
        if ($data['type'] == 'therapist') {
            $unavailableUsers = collect($data['id'])->filter(function ($userId) use ($data) {
                return !StaffSchedule::where('user_id', $userId)
                ->where('day', strtolower($data['day']))
                ->where('kindergarten_id', $data['kindergartenId'])
                ->where('start_time', '<=', $data['startTime'])
                ->where('end_time', '>=', $data['endTime'])
                ->exists();
            });
            if ($unavailableUsers->isNotEmpty()) {
                $isTimeOutSide = true;
            }
        }

        $freqRepeat = $data['frequencyRepeat'];
        $freqRepeatAt = $data['frequencyRepeatAt'];

        if (!Schedule::where('status', $data['status'])->exists()) {
            return response()->json(['status' => false, 'message' => '']);
        }

        $schedule = isset($data['schedule_id']) ? Schedule::find($data['schedule_id']) : Schedule::where('status', $data['status'])->first();
        $events = $schedule->events()->overlappingWithTimeSlot($data);

        if ($data['type'] == 'therapist') {
            $events = $events->whereIn('therapist_id', @$data['id']);
        } elseif ($data['type'] == 'children') {
            $events = $events->whereHas('childrens', function ($query) use ($data) {
                $query->whereIn('children_id', @$data['id']);
            });
        }

        if (isset($data['uniqueId'])) {
            $events = $events->where('unique_id', '!=', $data['uniqueId']);
        }

        $weeklyExists = (clone $events)->weekly()->exists();
        $biWeeklyExists = (clone $events)->biWeekly()->exists();
        $monthlyExists = (clone $events)->monthly()->exists();

        if ($weeklyExists) {
            return response()->json([
                'status' => true,
                'isTimeOutSide' => $isTimeOutSide,
                'message' => ucfirst($data['type']) . ' is not available'
            ]);
        }

        $isSlotAvailable = false;

        switch ($freqRepeat) {
            case 'Weekly':
                if ($biWeeklyExists || $monthlyExists) {
                    $isSlotAvailable = true;
                }
            break;
            case 'Bi-weekly':
                $checkFirstWeek = (clone $events)->monthly()->whereIn('frequency_repeat_at', ['Week 1', 'Week 3'])->exists();
                $checkSecondWeek = (clone $events)->monthly()->whereIn('frequency_repeat_at', ['Week 2', 'Week 4'])->exists();
                if (($freqRepeatAt == 'Week 1' && $checkFirstWeek) || ($freqRepeatAt == 'Week 2' && $checkSecondWeek)) {
                    $isSlotAvailable = true;
                } else {
                    $isSlotAvailable = (clone $events)->biWeekly()->where('frequency_repeat_at', $freqRepeatAt)->exists();
                }
            break;
            case 'Monthly':
                $biWeeklySlotMap = ['Week 1' => 'Week 1', 'Week 2' => 'Week 2', 'Week 3' => 'Week 1', 'Week 4' => 'Week 2'];
                $biWeeklySlot = $biWeeklySlotMap[$freqRepeatAt] ?? '';
                if (!empty($biWeeklySlot) && (clone $events)->biWeekly()->where('frequency_repeat_at', $biWeeklySlot)->exists()) {
                    $isSlotAvailable = true;
                } else {
                    $isSlotAvailable = (clone $events)->monthly()->where('frequency_repeat_at', $freqRepeatAt)->exists();
                }
            break;
        }

        return response()->json([
            'status' => $isSlotAvailable,
            'isTimeOutSide' => $isTimeOutSide,
            'message' => ucfirst($data['type']) . ' is not available'
        ]);
    }

    public function hourSummary(Request $request)
    {
        $associations = Association::whereIn('name', ['Matia', 'Tabam'])->with('staffKindergarten:user_id,association_id,kindergarten_id,id')->get()->keyBy('name');
        $matiaTherapistIds = (clone $associations['Matia'])->staffKindergarten->where('kindergarten_id', $request->kindergarten_id)->pluck('user_id')->toArray();
        $tabamTherapistIds = (clone $associations['Tabam'])->staffKindergarten->where('kindergarten_id', $request->kindergarten_id)->pluck('user_id')->toArray();
        $childrens = Children::select('id', 'name', 'kindergarten_id')->where('kindergarten_id', $request->kindergarten_id)->get()->toArray();
        $schedule = Schedule::filter(['status' => $request->status])->first();
        $childrenSummary = '';
        $staffSummary = '';
        if ($schedule && (clone $schedule)->events() !== null) {
            foreach ($childrens as $children) {
                $tabamScheduls = (clone $schedule)->events()->whereIn('therapist_id', array_unique($tabamTherapistIds))->whereHas('childrens', function ($query) use ($children) {
                        $query->where('children_id', $children['id']);
                    })->get();
                $matiaScheduls = (clone $schedule)->events()->whereIn('therapist_id', array_unique($matiaTherapistIds))->whereHas('childrens', function ($query) use ($children) {
                        $query->where('children_id', $children['id']);
                    })->get();
                $summary = [
                    'tabam' => [
                        'individual' => $tabamScheduls->where('type', 'individual')->sum->getWeightedCount(),
                        'group' => $tabamScheduls->where('type', 'group')->sum->getWeightedCount(),
                    ],
                    'matia' => [
                        'individual' => $matiaScheduls->where('type', 'individual')->sum->getWeightedCount(),
                        'group' => $matiaScheduls->where('type', 'group')->sum->getWeightedCount(),
                    ]
                ];
                $childrenSummary .= view('components.children-hour-summary', ['children' => $children, 'summary' => $summary]);
            }

            $users = User::select('id', 'name')->whereHas('staffKindergartens', function ($query) use ($request) {
                        $query->where('kindergarten_id', $request->kindergarten_id);
                    })->get();
            foreach ($users as $user) {
                $staffScheduls = (clone $schedule)->events()->where('therapist_id', $user->id)->get();
                $summary = [
                    'individual' => $staffScheduls->where('type', 'individual')->sum->getWeightedCount(),
                    'group' => $staffScheduls->where('type', 'group')->sum->getWeightedCount(),
                    'staff-meeting' => $staffScheduls->where('type', 'staff-meeting')->sum->getWeightedCount(),
                    'tutorial' => $staffScheduls->where('type', 'tutorial')->sum->getWeightedCount(),
                    'preparation' => $staffScheduls->where('type', 'preparation')->sum->getWeightedCount(),
                    'other' => $staffScheduls->where('type', 'other')->sum->getWeightedCount(),
                ];
                $staffSummary .= view('components.staff-hour-summary', ['user' => $user, 'summary' => $summary]);
            }
        }

        return response()->json([
            'childrenSummary' => $childrenSummary,
            'staffSummary' => $staffSummary,
        ]);
    }

    public function export(Request $request)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $timeSlots = [
            '07:00', '07:15', '07:30', '07:45',
            '08:00', '08:15', '08:30', '08:45',
            '09:00', '09:15', '09:30', '09:45',
            '10:00', '10:15', '10:30', '10:45',
            '11:00', '11:15', '11:30', '11:45',
            '12:00', '12:15', '12:30', '12:45',
            '13:00', '13:15', '13:30', '13:45',
            '14:00', '14:15', '14:30', '14:45',
            '15:00', '15:15', '15:30', '15:45',
            '16:00', '16:15', '16:30', '16:45',
        ]; // Example time slots
        $staffSchedules = [];
        $staffSchedules = StaffSchedule::with('user')->whereIn('day', $days)->get()->groupBy('day');
        $schedule = Schedule::filter(['status' => 'published'])->first();
        $events = ScheduleEvent::where('schedule_id', $schedule->id)->get()->groupBy('day');
        return view('exports.therapy-schedule', compact('days', 'timeSlots', 'staffSchedules', 'schedule'));
        // Fetch schedules for each day and time slot
        // echo '<pre>'; print_r($events['Sunday'][0]); die;

        return Excel::download(new CalendarExport($days, $timeSlots, $staffSchedules, $events), 'Calendar_Export.xlsx');
    }

    private function cloneSchedule($schedule, $clonedSchedule)
    {
        request()->merge(['_is_cloning' => true]);
        if ($schedule) {
            foreach ($schedule->events()->get() as $event) {
                $clonedSchedules = $clonedSchedule->events()->create([
                    'kindergarten_id' => $event->kindergarten_id,
                    'therapist_id' => $event->therapist_id,
                    'type' => $event->type,
                    'day' => $event->day,
                    'frequency_repeat' => $event->frequency_repeat,
                    'frequency_repeat_at' => $event->frequency_repeat_at,
                    'group_name' => $event->group_name,
                    'description' => $event->description,
                    'file' => $event->file,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'color' => json_encode($event->color),
                    'unique_id' => $event->unique_id,
                ]);
                if ($event->childrens()->exists()) {
                    foreach ($event->childrens()->get() as $children) {
                        $clonedSchedules->childrens()->create(['children_id' => $children->children_id]);
                    }
                }
            }
        }
    }
}
