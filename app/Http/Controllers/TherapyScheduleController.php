<?php

namespace App\Http\Controllers;

use App\Models\StaffSchedule;
use Illuminate\Http\Request;

class TherapyScheduleController extends Controller
{
    public function index()
    {
        return view('therapy-schedule.index');
    }

    public function create(){

        return view('therapy-schedule.add');
    }
}
