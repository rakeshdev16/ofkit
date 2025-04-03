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
use App\Models\ScheduleEventTherapistOccurred;
use App\Models\ScheduleEventChildrenOccurred;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use DB, Auth, DateTime, Session;
use App\Exports\CalendarExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        $kindergartens = Kindergarten::whereHas('staffKindergartens', function ($query) {
            $query->where('user_id', Auth::id());
        })->get();
        return view('documentation.index', compact('kindergartens'));
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

            if (isset($request->schedule_id)) {
                $schedule = Schedule::find($request->schedule_id);
            } else {
                if ($request->filter_type === 'week') {
                    $yearMonth = explode('-', $request->month);
                    $dates = $this->getSpecificWeekStartEnd($yearMonth[0], $yearMonth[1], $request->filter_type_num);
                    $startDate = Carbon::parse($dates['start']);
                    $endDate = Carbon::parse($dates['end']);
                    $schedule = Schedule::where('status', 'published')
                        ->where('kindergarten_id', $request->kindergarten_id)
                        ->where(function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('start_date', [$startDate, $endDate])
                                ->orWhereBetween('end_date', [$startDate, $endDate])
                                ->orWhere(function ($query) use ($startDate, $endDate) {
                                    $query->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate);
                                });
                        })->first();
                } else {
                    $schedule = Schedule::where('start_date', '<=', $date)->where('end_date', '>=', $date)->orderBy('id', 'DESC')->first();
                }
                if (isset($schedule->id)) {
                    $request['schedule_id'] = $schedule->id;
                } else {
                    return response()->json(['status' => false, 'message' => "The selected kindergarten dosen't have any published schedule"]);
                }
            }

            $event = ScheduleEvent::firstOrCreate(['id' => $request->event_id, 'schedule_id' => $schedule->id], $request->all());
            $deletedIds = $event->therapists()->exists() ? $event->therapists()->pluck('id') : collect();
            $event->therapists()->delete();
            if (isset($request->therapist_ids) && count($request->therapist_ids) > 0) {
                foreach ($request->therapist_ids as $therapistId) {
                    $event->therapists()->create(['therapist_id' => $therapistId]);
                }
            }
            $event->childrens()->delete();
            if (isset($request->children_ids) && count($request->children_ids) > 0) {
                foreach ($request->children_ids as $childrenId) {
                    $event->childrens()->create(['children_id' => $childrenId]);
                }
            }

            $event->therapistOccurred()->delete();
            if (isset($request->therapist_occurred) && count($request->therapist_occurred) > 0) {
                foreach ($request->therapist_occurred as $therapistId) {
                    $event->therapistOccurred()->create(['therapist_id' => $therapistId]);
                }
            }

            if (isset($request->participated) && count($request->participated) > 0) {
                foreach ($request->participated as $key => $participated) {
                    $participated['schedule_event_id'] = $event->id;
                    $participated['file'] = isset($participated['child_file']) ? uploadFile($participated['child_file'], 'public/child-document') : @$participated['file'];
                    ScheduleEventChildrenOccurred::updateOrCreate(['id' => $participated['id']], $participated);
                }
            }

            $events = $schedule->events()->whereHas('therapists', function ($query) use ($request) {
                        $query->where('therapist_id', Auth::id());
                    })->where(['day' => $request->day, 'start_time' => $request->start_time])->get();
            $event = scheduleResponse($events, $schedule, Auth::id());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Event detail has been successfully saved!',
                'event' => $event,
                'deletedIds' => $deletedIds
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $header = [];
        $schedules = Schedule::where('status', 'published')->whereIn('kindergarten_id', Auth::user()->staffKindergartens->pluck('kindergarten_id'));
        if ($filter['filter-type'] == 'days') {
            $day = $filter['filter-type-num'];
            $dayName = date('l', strtotime($filter['month'].'-'.$day));
            $date = $filter['month'].'-'.$day;
            $header[] = [
                'name' => '<div>' . $dayName . '<br>' . $day . '</div>',
                'id' => Auth::id() . strtolower($dayName)
            ];
            $schedules = $schedules->where('start_date', '<=', $date)->where('end_date', '>=', $date)->get();
        } else {
            $yearMonth = explode('-', $filter['month']);
            $dates = $this->getSpecificWeekStartEnd($yearMonth[0], $yearMonth[1], $filter['filter-type-num']);
            $startDate = Carbon::parse($dates['start']);
            $endDate = Carbon::parse($dates['end']);
            $week = CarbonPeriod::create($startDate, $endDate);
            foreach ($week as $date) {
                $parseDate = Carbon::parse($date);
                $day = $parseDate->format('l');
                $dateNumber = $parseDate->format('d');
                $header[] = [
                    'name' => '<div>' . $day . '<br>' . $dateNumber . '</div>',
                    'id' => Auth::id() . strtolower($day)
                ];
            }
            $schedules = $schedules->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate);
                    });
            })->get();
        }

        $events = [];
        $scheduleEvents = ScheduleEvent::whereIn('schedule_id', $schedules->pluck('id'))
            ->whereHas('therapists', function ($query) {
                $query->where('therapist_id', Auth::id());
            })
            ->get()
            ->when(true, function ($collection) {
                $groups = $collection->where('type', 'group')->unique('unique_id');
                $others = $collection->where('type', '!=', 'group');
                return $groups->merge($others);
            });

        $events = scheduleResponse($scheduleEvents, '', Auth::id());
        $staffTimeSlots = [];
        if ($schedules->isNotEmpty()) {
            $staffTimeSlots = StaffSchedule::where('user_id', Auth::id())->whereIn('kindergarten_id', $schedules->pluck('kindergarten_id'))
                ->get()->map(function ($schedule) {
                    return [
                        'resource' => $schedule->user_id.''.$schedule->day,
                        'startTime' => $schedule->start_time,
                        'endEnd' => $schedule->end_time,
                    ];
                });
        }

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
            'staffTimeSlots' => $staffTimeSlots,
        ]);
    }

    function getSpecificWeekStartEnd($year, $month, $weekNumber)
    {
        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $firstSunday = $firstDayOfMonth->copy()->previous(Carbon::SUNDAY);
        $weekStart = $firstSunday->copy()->addWeeks($weekNumber - 1);
        $weekEnd = $weekStart->copy()->addDays(6);
        return [
            'start' => $weekStart->format('Y-m-d 00:00:00'),
            'end' => $weekEnd->format('Y-m-d 23:59:59'),
        ];
    }

    function getWeekStartEndDate($year, $month, $week)
    {
        // $week = @explode('Week ', $week)[1];
        $firstDayOfMonth = new DateTime("$year-$month-01");
        $firstSunday = clone $firstDayOfMonth;
        if ($firstSunday->format('N') != 7) {
            $firstSunday->modify('next Sunday');
        }
        $weekStart = clone $firstSunday;
        $weekStart->modify('+' . ($week - 1) * 7 . ' days');
        $weekEnd = clone $weekStart;
        $weekEnd->modify('+6 days');
        $lastDayOfMonth = new DateTime("$year-$month-01");
        $lastDayOfMonth->modify('last day of this month');
        if ($weekEnd > $lastDayOfMonth) {
            $weekEnd = $lastDayOfMonth;
        }
        return [
            'start' => $weekStart->format('Y-m-d').' 00:00:00',
            'end' => $weekEnd->format('Y-m-d').' 00:00:00'
        ];
    }

    public function formData(Request $request)
    {
        $data = $request->all();
        $allChildrens = [];
        $allTherapists = [];
        if (isset($data['kindergarten_id']) && isset($data['day'])) {
            $allChildrens = Children::select('id as key', DB::raw('CONCAT(name, " ", family_name) as value'))
                ->where('kindergarten_id', $data['kindergarten_id'])
                ->orderBy('name')
                ->get()
                ->toArray();
            $allTherapists = StaffSchedule::filter($data)
                ->with('user')
                ->select('user_id')
                ->distinct('user_id')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'key' => $schedule->user_id,
                        'value' => $schedule->user->name ?? 'N/A',
                    ];
                })->toArray();
        }
        $data['allChildrens'] = $allChildrens;
        $data['allTherapists'] = $allTherapists;
        $form = view('components.event-status-form', ['data' => $data])->render();
        return response()->json(['status' => true, 'data' => $form]);
    }
}