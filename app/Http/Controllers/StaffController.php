<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\KindergartenUser;
use App\Models\MemberRole;
use App\Models\Profession;
use App\Models\Staff;
use App\Models\StaffKindergarten;
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
        $members = User::whereNot('id', Auth::id())->filter()->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'table' => view('staff.table', ['members' => $members])->render(),
                'accordion' => view('staff.accordion', ['members' => $members])->render()
            ]);
        }
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        return view('staff.index', compact('members', 'kindergartens'));
    }
    
    public function create()
    {
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        return view('staff.create', compact('kindergartens', 'managers', 'roles', 'professions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => 'required|digits_between:8,14',
            'licence_number' => 'required',
            'profession_id' => 'required',
            'dob' => 'required',
            'role' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            'address.required' => __('staff.requiredAddress'),
            'email.required' => __('staff.requiredEmail'),
            'email.email' => __('staff.validEmail'),
            'email.unique' => __('staff.existsEmail'),
            'telephone.required' => __('staff.requiredTelephone'),
            'licence_number.required' => __('staff.requiredLicence'),
            'profession_id.required' => __('staff.requiredProfession'),
            'dob.required' => __('staff.requiredDOB'),
            'role.required' => __('staff.requiredRole'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['identification'] = Str::uuid();
        $request['password'] = rand();
        if ($request->hasFile('member_photo')) {
            $request['photo'] = uploadFile($request->member_photo, 'public/staff');
        }
        $user = User::create($request->all());
        $user->assignRole($request->role);
        $user->notify(new AccountDetailNotification($user, $request['password']));
        if (isset($request->kindergarten) && count($request->kindergarten)) {
            $user->staffKindergartens()->createMany($request->kindergarten);
        }
        if (isset($request->schedule) && count($request->schedule)) {
            $user->days()->createMany($request->schedule);
        }
        return redirect()->route('staff.index');
    }

    public function show($id)
    {
        $staff = User::findOrFail($id);
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        return view('staff.show', compact('staff', 'kindergartens', 'managers', 'roles', 'memberRoles', 'professions'));
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        return view('staff.edit', compact('staff', 'kindergartens', 'managers', 'roles', 'memberRoles', 'professions'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'telephone' => 'required|digits_between:8,14',
            'licence_number' => 'required',
            'profession_id' => 'required',
            'dob' => 'required',
            'role' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            'address.required' => __('staff.requiredAddress'),
            'telephone.required' => __('staff.requiredTelephone'),
            'licence_number.required' => __('staff.requiredLicence'),
            'profession_id.required' => __('staff.requiredProfession'),
            'dob.required' => __('staff.requiredDOB'),
            'role.required' => __('staff.requiredRole'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = User::findOrFail($id);
        if ($request->hasFile('member_photo')) {
            $request['photo'] = uploadFile($request->member_photo, 'public/staff');
            unset($request['member_photo']);
        }
        $user->update($request->except('_token', '_method', 'kindergarten_id', 'schedule'));
        $user->syncRoles($request->role);
        if (isset($request->kindergarten) && count($request->kindergarten)) {
            $user->staffKindergartens()->delete();
            $user->staffKindergartens()->createMany($request->kindergarten);
        }
        if (isset($request->schedule) && count($request->schedule)) {
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

    public function selectedKindergarten(Request $request)
    {
        $tr = '';
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        $roles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $index = 0;
        if (isset($request->ids) && count($request->ids) > 0) {
            foreach ($request->ids as $id) {
                $staffKindergarten = StaffKindergarten::where(['user_id' => $request->user_id, 'kindergarten_id' => $id])->first();
                $tr .= view('components.kindergarten-tr', [
                    'id' => $id,
                    'index' => $index,
                    'professions' => $professions,
                    'roles' => $roles,
                    'data' => $staffKindergarten,
                ])->render();
                $index++;
            }
            return response()->json(['status' => true, 'data' => $tr]);
        } else {
            return response()->json(['status' => false, 'data' => '']);
        }
    }
}
