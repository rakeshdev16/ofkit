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
use DB, Auth, DateTime, Session;
use App\Exports\CalendarExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $day = request('day', 'All Days');
        $today = date('d');
        $header = [];

        if ($day == 'All Days') {
            for ($i = 0; $i < 7; $i++) {
                $date = date('d', strtotime("last Sunday +{$i} days"));
                $dayName = date('l', strtotime("last Sunday +{$i} days"));
                $todayClass = ($date == $today) ? ' today' : '';
                $header[] = [
                    'name' => '<div class="' . $todayClass . '">' . $dayName . '<br>' . $date . '</div>',
                    'id' => Auth::id() . strtolower($dayName)
                ];
            }
        } else {
            $date = date('d', strtotime("last $day"));
            $dayName = date('l', strtotime("last Sunday $day"));
            $todayClass = ($date == $today) ? ' today' : '';
            $header[] = [
                'name' => '<div class="' . $todayClass . '">' . $day . '<br>' . $date . '</div>',
                'id' => Auth::id() . strtolower($dayName)
            ];
        }

        $yearMonth = explode('-', $filter['month']);
        $date = $this->getWeekStartEndDate($yearMonth[0], $yearMonth[1], $filter['week']);
        $events = [];
        $schedule = '';
        $scheduleIds = Schedule::where('status', 'published')
            ->where('start_date', '<=', $date['end'])
            ->where('end_date', '>=', $date['start'])
            ->where('end_date', '<=', $date['end'])
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