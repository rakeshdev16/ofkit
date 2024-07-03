<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\AccountDetailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $members = User::query();
        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $clusterIds = Cluster::where('manager_id', Auth::id())->pluck('id')->toArray();
            $kindergartenIds = Kindergarten::whereIn('cluster_id', $clusterIds)->pluck('id')->toArray();
            $members->whereIn('kindergarten_id', $kindergartenIds);
        }
        if ($request->ajax()) {
            if ($request->sort && $request->sorting) {
                $members->orderBy($request->sort, $request->sorting);
            }
            if ($request->kindergarten_id) {
                $members->where('kindergarten_id', $request->kindergarten_id);
            }
            if ($request->search) {
                $members->where('name', 'like', '%'.$request->search.'%');
            }
            $members = $members->paginate(10);
            return response()->json([
                'table' => view('staff.table', ['members' => $members])->render(),
                'accordion' => view('staff.accordion', ['members' => $members])->render()
            ]);
        }
        $members = $members->paginate(10);
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        return view('staff.index', compact('members', 'kindergartens'));
    }
    
    public function create()
    {
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        return view('staff.create', compact('kindergartens', 'managers', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => 'required',
            'licence_number' => 'required',
            'profession' => 'required',
            'dob' => 'required',
            'role' => 'required',
            'kindergarten_id' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            'address.required' => __('staff.requiredAddress'),
            'email.required' => __('staff.requiredEmail'),
            'email.email' => __('staff.validEmail'),
            'email.unique' => __('staff.existsEmail'),
            'telephone.required' => __('staff.requiredTelephone'),
            'licence_number.required' => __('staff.requiredLicence'),
            'profession.required' => __('staff.requiredProfession'),
            'dob.required' => __('staff.requiredDOB'),
            'role.required' => __('staff.requiredRole'),
            'kindergarten_id.required' => __('staff.requiredKindergarten'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['identification'] = Str::uuid();
        $request['password'] = rand();
        $user = User::create($request->all());
        $user->assignRole($request->role);
        $user->notify(new AccountDetailNotification($user, $request['password']));
        if (isset($request->kindergarten_id)) {
            $user->userKindergarten()->create(['kindergarten_id' => $request->kindergarten_id]);
        }
        if (count($request->schedule)) {
            $user->days()->createMany($request->schedule);
        }
        return redirect()->route('staff.index');
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        return view('staff.edit', compact('staff', 'kindergartens', 'managers', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'telephone' => 'required',
            'licence_number' => 'required',
            'profession' => 'required',
            'dob' => 'required',
            'role' => 'required',
            'kindergarten_id' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            'address.required' => __('staff.requiredAddress'),
            'telephone.required' => __('staff.requiredTelephone'),
            'licence_number.required' => __('staff.requiredLicence'),
            'profession.required' => __('staff.requiredProfession'),
            'dob.required' => __('staff.requiredDOB'),
            'role.required' => __('staff.requiredRole'),
            'kindergarten_id.required' => __('staff.requiredKindergarten'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = User::findOrFail($id);
        $user->update($request->except('_token', '_method', 'kindergarten_id', 'schedule'));
        $user->syncRoles($request->role);
        if (isset($request->kindergarten_id)) {
            $user->userKindergarten()->update(['kindergarten_id' => $request->kindergarten_id]);
        }
        if (count($request->schedule)) {
            foreach ($request->schedule as $schedule) {
                $user->days()->updateOrCreate(['id' => $schedule['id']], $schedule);
            }
        }
        return redirect()->route('staff.index');
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (User::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('staff.staff.deleteStaffMsg'), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
}
