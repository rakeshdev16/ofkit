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
        // echo '<pre>'; print_r($request->all()); die;
        DB::beginTransaction();
        try {

            if ($request->hasFile('image')) {
                $request['file'] = uploadFile($request->image, 'public/therapy-schedule');
            } else {
                $request['file'] = $request->old_image;
            }

            $schedule = Schedule::find($request->schedule_id);
            $schedule->events()->removeUnselectedUser($request->all());

            // $request['unique_id'] = $request->unique_id ? $request->unique_id : Str::uuid();
            if (isset($request->therapist_ids) && count($request->therapist_ids) > 0) {
                foreach ($request->therapist_ids as $key => $therapistId) {
                    $request['therapist_id'] = $therapistId;
                    $scheduleEvent = ScheduleEvent::updateOrCreate([
                        'schedule_id' => $request->schedule_id,
                        'therapist_id' => $therapistId,
                        // 'unique_id' => $request->unique_id
                    ],
                        $request->all()
                    );
                    Log::info('ScheduleEvent', ['scheduleEvent' => $scheduleEvent]);
                }
                if (isset($request->therapist_occurred)) {
                    foreach ($request->therapist_occurred as $therapistId => $value) {
                        $scheduleEventId = $schedule->events()->where(['unique_id' => $request->unique_id, 'therapist_id' => $therapistId])->pluck('id')->first();
                        ScheduleEventTherapistOccurred::create([
                            'schedule_event_id' => $request->event_id, 
                            'therapist_id' => $therapistId,
                        ]);
                    }
                }
            }
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
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function calendar(Request $request)
    {
        $filter = $request->all();
        $yearMonth = explode('-', $filter['month']);
        $dates = $this->getWeekStartEndDate($yearMonth[0], $yearMonth[1], $filter['week']);
        $startDate = Carbon::parse($dates['start']);
        $endDate = Carbon::parse($dates['end']);
        $week = CarbonPeriod::create($startDate, $endDate);
        $day = request('day', 'All Days');
        $header = [];

        if ($day == 'All Days') {
            foreach ($week as $date) {
                $parseDate = Carbon::parse($date);
                $day = $parseDate->format('l');
                $dateNumber = $parseDate->format('d');
                $header[] = [
                    'name' => '<div>' . $day . '<br>' . $dateNumber . '</div>',
                    'id' => Auth::id() . strtolower($day)
                ];
            }
        } else {
            $specificDate = Carbon::parse($startDate)->next($day);
            if ($specificDate->greaterThan($endDate)) {
                $specificDate = $startDate;
            }
            $dayName = $specificDate->format('l');
            $dateNumber = $specificDate->format('d');
            $header[] = [
                'name' => '<div>' . $dayName . '<br>' . $dateNumber . '</div>',
                'id' => Auth::id() . strtolower($dayName)
            ];
        }

        $events = [];
        $schedule = '';
        $scheduleIds = Schedule::where('status', 'published')
            ->where('start_date', '<=', $dates['end'])
            ->where('end_date', '>=', $dates['start'])
            ->where('end_date', '<=', $dates['end'])
            ->pluck('id');

        $scheduleEvents = ScheduleEvent::whereIn('schedule_id', $scheduleIds)->whereHas('therapists', function ($query) {
                $query->where('therapist_id', Auth::id());
            })
            ->get()
            ->when(true, function ($collection) {
                $groups = $collection->where('type', 'group')->unique('unique_id');
                $others = $collection->where('type', '!=', 'group');
                return $groups->merge($others);
            });

        $events = scheduleResponse($scheduleEvents, $schedule, Auth::id());

        return response()->json([
            'calenderHeader' => $header,
            'calenderEvents' => $events,
        ]);
    }

    function getWeekStartEndDate($year, $month, $week)
    {
        $week = @explode('Week ', $week)[1];
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