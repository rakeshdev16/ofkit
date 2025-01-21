<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class CalendarExport implements FromView, ShouldAutoSize
{
    protected $days;
    protected $timeSlots;
    protected $daySchedules;

    public function __construct($days, $timeSlots, $daySchedules)
    {
        $this->days = $days;
        $this->timeSlots = $timeSlots;
        $this->daySchedules = $daySchedules;
    }

    public function view(): View
    {
        return view('exports.therapy-schedule', [
            'days' => $this->days,
            'timeSlots' => $this->timeSlots,
            'daySchedules' => $this->daySchedules,
        ]);
    }
}
