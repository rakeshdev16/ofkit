<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\KindergartenUser;
use App\Models\MemberRole;
use App\Models\Profession;
use App\Models\Staff;
use App\Models\StaffDocument;
use App\Models\StaffKindergarten;
use App\Models\User;
use App\Notifications\AccountDetailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth, Session, DB;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $members = User::whereNot('id', Auth::id())->filter()->orderBy('id', 'DESC')->paginate(10);
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
        $associations = Association::select('id as key', 'name as value')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        return view('staff.create', compact('kindergartens', 'managers', 'roles', 'professions', 'associations', 'memberRoles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'address' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => 'required|digits_between:8,14',
            // 'licence_number' => 'required',
            // 'profession_id' => 'required',
            // 'dob' => 'required',
            'role' => 'required',
            // 'member_photo' => 'max:2000',
            // 'schedule' => 'required|array',
            // 'schedule.*.start_time' => 'required',
            // 'schedule.*.end_time' => 'required|after:schedule.*.start_time',
            // 'kindergarten.*.role_id' => 'required',
            // 'kindergarten.*.association_id' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            // 'address.required' => __('staff.requiredAddress'),
            'email.required' => __('staff.requiredEmail'),
            'email.email' => __('staff.validEmail'),
            'email.unique' => __('staff.existsEmail'),
            // 'telephone.required' => __('staff.requiredTelephone'),
            // 'licence_number.required' => __('staff.requiredLicence'),
            // 'association_id.required' => __('staff.requiredProfession'),
            // 'dob.required' => __('staff.requiredDOB'),
            // 'profession_id.required' => 'Please chose profession',
            'role.required' => __('staff.requiredRole'),
            // 'member_photo.max' => 'The photo may not be greater than 2MB',
            // 'schedule.*.start_time.required' => 'Please enter start time',
            // 'schedule.*.end_time.required' => 'Please enter end time',
            // 'schedule.*.end_time.after' => 'End time must be after start time',
            // 'kindergarten.*.role_id.required' => 'Please chose role',
            // 'kindergarten.*.association_id.required' => 'Please chose association',
        ]);
        $validator->after(function ($validator) use ($request) {
            if ($request->kindergarten_id && count($request->kindergarten_id) > 0) {
                foreach ($request->kindergarten as $index => $kindergarten) {
                    if (empty($kindergarten['role_id'])) {
                        $validator->errors()->add("kindergarten.$index.role_id", 'Please choose role');
                    }
                    if (empty($kindergarten['association_id'])) {
                        $validator->errors()->add("kindergarten.$index.association_id", 'Please choose association');
                    }
                }
            }
            if ($request->schedule && count($request->schedule) > 0) {
                foreach ($request->schedule as $index => $schedule) {
                    if (!empty($schedule['start_time']) && empty($schedule['end_time'])) {
                        $validator->errors()->add("schedule.$index.end_time", 'Please enter end time');
                    } elseif (!empty($schedule['start_time']) && !empty($schedule['end_time']) && $schedule['end_time'] <= $schedule['start_time']) {
                        $validator->errors()->add("schedule.$index.end_time", 'End time must be after start time');
                    }
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {
            
            $request['identification'] = Str::uuid();
            $request['password'] = rand();
            if (Session::has('staffPhoto')) {
                $request['photo'] = Session::get('staffPhoto');
            }
            $user = User::create($request->all());
            if (isset($request->documents) && count($request->documents) > 0) {
                foreach ($request->documents as $document) {
                    $name = uploadFile($document, 'public/staff-document');
                    $user->documents()->create(['name' => $name]);
                }
            }
            $user->assignRole($request->role);
            $user->notify(new AccountDetailNotification($user, $request['password']));
            if (isset($request->kindergarten) && count($request->kindergarten)) {
                $user->staffKindergartens()->createMany($request->kindergarten);
            }
            if (isset($request->schedule) && count($request->schedule)) {
                $user->days()->createMany($request->schedule);
            }
            Session::forget('kindergartenIds');
            
            DB::commit();
            return redirect()->route('staff.index');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
        
    }

    public function show($id)
    {
        $staff = User::findOrFail($id);
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        $associations = Association::select('id as key', 'name as value')->get()->toArray();
        return view('staff.show', compact('staff', 'kindergartens', 'managers', 'roles', 'memberRoles', 'professions', 'associations'));
    }

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        $managers = User::select('id as key', 'name as value')->role('manager')->get()->toArray();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $roles = Role::select('name as key', 'name as value')->where('name', '!=', 'admin')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $professions = Profession::select('id as key', 'name as value')->get()->toArray();
        $associations = Association::select('id as key', 'name as value')->get()->toArray();
        return view('staff.edit', compact('staff', 'kindergartens', 'managers', 'roles', 'memberRoles', 'professions', 'associations'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'address' => 'required',
            'telephone' => 'required|digits_between:8,14',
            // 'licence_number' => 'required',
            // 'profession_id' => 'required',
            // 'dob' => 'required',
            'role' => 'required',
            // 'member_photo' => 'file|max:2000', 
            // 'schedule' => 'required|array',
            // 'schedule.*.start_time' => 'required',
            // 'schedule.*.end_time' => 'required|after:schedule.*.start_time',
            // 'kindergarten.*.role_id' => 'required',
            // 'kindergarten.*.association_id' => 'required',
        ],[
            'name.required' => __('staff.requiredName'),
            // 'address.required' => __('staff.requiredAddress'),            
            'telephone.required' => __('staff.requiredTelephone'),
            // 'licence_number.required' => __('staff.requiredLicence'),
            // 'profession_id.required' => 'Please chose profession',
            // 'dob.required' => __('staff.requiredDOB'),
            'role.required' => __('staff.requiredRole'),
            // 'member_photo.max' => 'The photo may not be greater than 2MB',
            // 'schedule.*.start_time.required' => 'Please enter start time',
            // 'schedule.*.end_time.required' => 'Please enter end time',
            // 'schedule.*.end_time.after' => 'End time must be after start time',
            // 'kindergarten.*.role_id.required' => 'Please chose role',
            // 'kindergarten.*.association_id.required' => 'Please chose profession',
        ]);
        $validator->after(function ($validator) use ($request) {
            if ($request->kindergarten_id && count($request->kindergarten_id) > 0) {
                foreach ($request->kindergarten as $index => $kindergarten) {
                    if (empty($kindergarten['role_id'])) {
                        $validator->errors()->add("kindergarten.$index.role_id", 'Please choose role');
                    }
                    if (empty($kindergarten['association_id'])) {
                        $validator->errors()->add("kindergarten.$index.association_id", 'Please choose association');
                    }
                }
            }
            if ($request->schedule && count($request->schedule) > 0) {
                foreach ($request->schedule as $index => $schedule) {
                    if (!empty($schedule['start_time']) && empty($schedule['end_time'])) {
                        $validator->errors()->add("schedule.$index.end_time", 'Please enter end time');
                    } elseif (!empty($schedule['start_time']) && !empty($schedule['end_time']) && $schedule['end_time'] <= $schedule['start_time']) {
                        $validator->errors()->add("schedule.$index.end_time", 'End time must be after start time');
                    }
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);
            $user->update($request->except('_token', '_method', 'kindergarten_id', 'schedule'));
            if (isset($request->documents) && count($request->documents) > 0) {
                foreach ($request->documents as $document) {
                    $name = uploadFile($document, 'public/staff-document');
                    $user->documents()->create(['name' => $name]);
                }
            }
            $user->staffKindergartens()->delete();
            if (isset($request->kindergarten) && count($request->kindergarten)) {
                $user->staffKindergartens()->createMany($request->kindergarten);
            }
            if (isset($request->schedule) && count($request->schedule)) {
                foreach ($request->schedule as $schedule) {
                    $user->days()->updateOrCreate(['id' => $schedule['id']], $schedule);
                }
            }
            Session::forget('kindergartenIds');

            DB::commit();
            return redirect()->route('staff.show', $id);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (User::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('validation.archived', ['attribute' => 'Staff member']), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }

    public function uploadStaffProfile(Request $request)
    {
        if ($request->hasFile('image')) {
            $photo = uploadFile($request->image, 'public/staff', $request->extension);
            if ($request->type == 'add') {
                Session::put('staffPhoto', $photo);
            } else {
                User::where('id', $request->user_id)->update(['photo' => $photo]);
            }
            return response()->json(['status' => true, 'message' => 'Profile has been uploaded', 'src' => asset('storage/'.$photo)]);
        }
        return response()->json(['status' => false]);
    }

    public function selectedKindergarten(Request $request)
    {
        Session::forget('kindergartenIds');
        $rows = [];
        $associations = Association::select('id as key', 'name as value')->get()->toArray();
        $memberRoles = MemberRole::select('id as key', 'name as value')->get()->toArray();
        $row = view('components.kindergarten-tr', [
            'id' => $request->id,
            'index' => $request->index,
            'associations' => $associations,
            'memberRoles' => $memberRoles,
            'data' => getStaffKindergarten($request->user_id, $request->id),
        ])->render();
        return response()->json(['status' => true, 'data' => $row]);

        // if (isset($request->ids) && count($request->ids) > 0) {
        //     foreach ($request->ids as $id) {
        //         $staffKindergarten = StaffKindergarten::where(['user_id' => $request->user_id, 'kindergarten_id' => $id])->first();
        //         $row = view('components.kindergarten-tr', [
        //             'id' => $id,
        //             'index' => $index,
        //             'associations' => $associations,
        //             'memberRoles' => $memberRoles,
        //             'data' => $staffKindergarten,
        //         ])->render();
        //         $rows[] = $row;
        //         $index++;
        //     }
        //     return response()->json(['status' => true, 'data' => $rows]);
        // } else {
        //     Session::forget('kindergartenIds');
        //     return response()->json(['status' => false, 'data' => '']);
        // }
    }


    public function deleteDocument(Request $request)
    {
        if (StaffDocument::where('id', $request->id)->delete()) {
            return response()->json(['status' => true, 'message' => 'Document has been deleted!']);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong please try again!']);
    }
}
