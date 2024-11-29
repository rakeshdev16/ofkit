<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\StaffSchedule;
use App\Models\TherapySchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TherapyScheduleController extends Controller
{
    public function index(Request $request)
    {
        $therapists = User::role('therapist')->orderBy('name')->get(['id', 'name']);
        $childrens = Children::orderBy('name')->get(['id', 'name']);
        return view('therapy-schedule.index', compact('therapists', 'childrens'));
    }

    public function create()
    {
        $therapists = User::role('therapist')->orderBy('name')->get(['id', 'name']);
        $childrens = Children::orderBy('name')->get(['id', 'name']);
        return view('therapy-schedule.create', compact('therapists', 'childrens'));
    }

    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $request['file'] = uploadFile($request->image, 'public/therapy-schedule', $request->extension);
        }
        $request['therapist_id'] = preg_match('/\d+/', $request->resource, $matches) ? $matches[0] : null;

        TherapySchedule::create($request->all());

        return response()->json(['status' => true, 'message' => 'Event detail has been successfully saved!']);

    }
}
