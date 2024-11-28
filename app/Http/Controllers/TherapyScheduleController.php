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
    {   $photo = null;
        if ($request->hasFile('image')) {
            $photo = uploadFile($request->image, 'public/therapy-schedule', $request->extension);
        }

        TherapySchedule::create([
            'type' => $request->type,
            'schedule_time' => $request->schedule_time,
            'frequency_repeat' => $request->frequency_repeat,
            'start' => $request->start,
            'group_name' => $request->group_name,
            'therapist_id' => $request->therapist_id,
            'children_ids' => json_encode($request->children_ids, true),
            'description' => $request->description,
            'file' => $photo
        ]);

        return back()->with('success', 'Record Created');

    }
}
