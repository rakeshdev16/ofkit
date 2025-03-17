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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $scheduleEvents = ScheduleEvent::whereIn('schedule_id', $scheduleIds)->where('therapist_id', Auth::id())
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
}