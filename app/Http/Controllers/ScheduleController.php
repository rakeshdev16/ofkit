<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\StaffSchedule;
use App\Models\TherapySchedule;
use App\Models\ScheduleEvent;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Kindergarten;
use App\Models\StaffKindergarten;
use App\Models\TherapyScheduleChildren;
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
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $schedule = Schedule::where('status', 'published')->first();
        return view('schedule.index', compact('kindergartens', 'schedule'));
    }

    public function create()
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $createdEventIds = TherapySchedule::where('status', 'draft')->pluck('unique_id')->toArray();
        $createdEventIds = count($createdEventIds) > 0 ? json_encode($createdEventIds) : null;
        return view('schedule.create', compact('kindergartens', 'createdEventIds'));
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

            if ($request->schedule_id == 'null') {
                $schedule = Schedule::firstOrCreate(['status' => 'draft']);
            } else {
                $schedule = Schedule::where('id', $request->schedule_id)->first();
            }

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
            $schedules = $schedule->events()->where('unique_id', $request->unique_id)->get();
            $event = scheduleResponse($schedules);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully saved as draft!', 'event' => $event, 'deletedIds' => $deletedIds]);
        } catch (\Exception $e) {
            echo '<pre>'; print_r($e->getMessage()); die;
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $schedule = Schedule::where('status', 'draft');
        if ($schedule->exists() && $schedule->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'published',
        ])) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully published!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }
    
    public function delete(Request $request)
    {
        $ids = ScheduleEvent::whereIn('unique_id', $request->ids)->pluck('id')->toArray();
        if (ScheduleEvent::whereIn('unique_id', $request->ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Event detail has been successfully deleted!', 'ids' => $ids]);
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
        $events = !empty($schedule) ? scheduleResponse($schedule->events) : [];
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

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
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

    // public function checkTimeSlot(Request $request)
    // {
    //     if (Schedule::where('status', $request->status)->exists()) {
    //         $checkSlot = Schedule::where('status', $request->status)->first()->events()
    //             ->where('day', $request['day'])
    //             ->where('start_time', '>=', $request['startTime'])
    //             ->where('end_time', '<=', $request['endTime']);
    //         switch ($request->type) {
    //             case 'therapist':
    //                 $checkSlot = $checkSlot->where('therapist_id', $request['id']);
    //                 if ($request['frequencyRepeat'] === 'Weekly') {
    //                     $checkSlot = $checkSlot->where('frequency_repeat', 'Weekly')->exists();
    //                 } else {
    //                     $checkSlot = false;
    //                 }
    //                 break;
    //             case 'children':
    //                 $checkSlot = $checkSlot->whereHas('childrens', function ($query) use ($request) {
    //                     $query->where('children_id', $request['id']);
    //                 })->exists();
    //             break;
    //         }

    //         return response()->json([
    //             'status' => $checkSlot,
    //             'message' => $checkSlot ? 'This ' . $request->type . ' is already assigned to another on the same time' : ''
    //         ]);
    //     }
    // }

    public function checkTimeSlot(Request $request)
    {
        $data = $request->all();
        if (!Schedule::where('status', $data['status'])->exists()) {
            return response()->json(['status' => false, 'message' => '']);
        }

        $schedule = Schedule::where('status', $data['status'])->first();
        $checkSlot = $schedule->events()
            ->where('day', $data['day'])
            ->where(function ($query) use ($data) {
                $query->where(function ($query) use ($data) {
                    $query->whereTime('start_time', '>=', $data['startTime'].':00')->whereTime('end_time', '<=', $data['endTime'].':00');
                });
            });

        if (isset($data['uniqueId'])) {
            $checkSlot = $checkSlot->whereNot('unique_id', $data['uniqueId']);
        }

        if ($data['type'] == 'therapist') return $this->checkUserSlot($data, $checkSlot);
        if ($data['type'] == 'children') return $this->checkChildrenSlot($data, $checkSlot);

        return response()->json(['status' => false, 'message' => '']);
    }

    private function checkUserSlot($data, $query)
    {
        $freqRepeat = $data['frequencyRepeat'];
        $freqRepeatAt = $data['frequencyRepeatAt'];
        $checkSlot = $query->where('therapist_id', $data['id']);
        $checkBiWeeklySlot = clone $checkSlot;
        $checkBiWeeklySlot = $checkBiWeeklySlot->where('frequency_repeat', 'Bi-weekly');
        $checkMonthlySlot = clone $checkSlot;
        $checkMonthlySlot = $checkMonthlySlot->where('frequency_repeat', 'Monthly');

        $weeklyCheck = clone $checkSlot;
        if ($weeklyCheck->where('frequency_repeat', 'Weekly')->exists()) {
            return response()->json([
                'status' => true,
                'message' => 'This therapist already has a weekly appointment at this time'
            ]);
        }

        if ($freqRepeat === 'Bi-weekly') {
            $monthlySlot = [];
            if ($freqRepeatAt == 'Week 1') $monthlySlot = ['Week 2', 'Week 4'];
            if ($freqRepeatAt == 'Week 2') $monthlySlot = ['Week 1', 'Week 3'];

            if (!empty($monthlySlot) && $checkMonthlySlot->whereIn('frequency_repeat_at', $monthlySlot)->exists()) {
                $checkSlot = true;
            } else {
                $checkSlot = $checkBiWeeklySlot->where('frequency_repeat_at', $freqRepeatAt)->exists();
            }
        }

        if ($freqRepeat === 'Monthly') {
            $biWeeklySlot = '';
            if ($freqRepeatAt == 'Week 1') $biWeeklySlot = 'Week 2';
            if ($freqRepeatAt == 'Week 2') $biWeeklySlot = 'Week 1';
            if ($freqRepeatAt == 'Week 3') $biWeeklySlot = 'Week 2';
            if ($freqRepeatAt == 'Week 4') $biWeeklySlot = 'Week 1';

            if (!empty($biWeeklySlot) && $checkBiWeeklySlot->where('frequency_repeat_at', $biWeeklySlot)->exists()) {
                $checkSlot = true;
            } else {
                $checkSlot = $checkMonthlySlot->where('frequency_repeat_at', $freqRepeatAt)->exists();
            }
        }

        return response()->json([
            'status' => $checkSlot,
            'message' => $checkSlot ? 'This therapist already has a weekly appointment at this time' : ''
        ]);
    }

    private function checkChildrenSlot($data, $checkSlot)
    {
        $checkSlot = $checkSlot->whereHas('childrens', function ($query) use ($data) {
            $query->where('children_id', $data['id']);
        });

        if ($checkSlot->exists()) {
            return response()->json([
                'status' => true,
                'message' => 'This child has a conflicting schedule.'
            ]);
        }
    }

    public function hourSummary(Request $request)
    {
        $associations = Association::whereIn('name', ['Matia', 'Tabam'])->with('staffKindergarten:user_id,association_id')->get()->keyBy('name');
        $matiaTherapistIds = $associations['Matia']->staffKindergarten->pluck('user_id')->toArray();
        $tabamTherapistIds = $associations['Tabam']->staffKindergarten->pluck('user_id')->toArray();
        $childrens = Children::select('id', 'name', 'kindergarten_id')->where('kindergarten_id', $request->kindergarten_id)->get()->toArray();
        $schedule = Schedule::where('status', 'published')->first();
        $childrenSummary = '';
        $staffSummary = '';
        if ($schedule && $schedule->events() !== null) {
            foreach ($childrens as $children) {
                $tabamScheduls = $schedule->events()->whereIn('therapist_id', array_unique($tabamTherapistIds))->whereHas('childrens', function ($query) use ($children) {
                        $query->where('children_id', $children['id']);
                    })->get();
                $matiaScheduls = $schedule->events()->whereIn('therapist_id', array_unique($matiaTherapistIds))->whereHas('childrens', function ($query) use ($children) {
                        $query->where('children_id', $children['id']);
                    })->get();
                $summary = [
                    'tabam' => [
                        'individual' => $tabamScheduls->where('type', 'individual')->count(),
                        'group' => $tabamScheduls->where('type', 'group')->count(),
                    ],
                    'matia' => [
                        'individual' => $matiaScheduls->where('type', 'individual')->count(),
                        'group' => $matiaScheduls->where('type', 'group')->count(),
                    ]
                ];
                $childrenSummary .= view('components.children-hour-summary', ['children' => $children, 'summary' => $summary]);
            }

            $users = User::select('id', 'name')->whereHas('staffKindergartens', function ($query) use ($request) {
                        $query->where('kindergarten_id', $request->kindergarten_id);
                    })->get();
            foreach ($users as $user) {
                $staffScheduls = $schedule->events()->where('therapist_id', $user->id)->get();
                $summary = [
                    'individual' => $staffScheduls->where('type', 'individual')->count(),
                    'group' => $staffScheduls->where('type', 'group')->count(),
                    'staff-meeting' => $staffScheduls->where('type', 'staff-meeting')->count(),
                    'tutorial' => $staffScheduls->where('type', 'tutorial')->count(),
                    'preparation' => $staffScheduls->where('type', 'preparation')->count(),
                    'other' => $staffScheduls->where('type', 'other')->count(),
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
        // Fetch schedules for each day and time slot
        $daySchedules = [];
        $daySchedules = StaffSchedule::with('user')->whereIn('day', $days)->get()->groupBy('day');

        return Excel::download(new CalendarExport($days, $timeSlots, $daySchedules), 'Calendar_Export.xlsx');
    }
}
