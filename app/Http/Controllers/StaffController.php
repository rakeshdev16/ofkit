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
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $members = User::whereNot('id', Auth::id())->filter()->orderBy('id', 'DESC')->paginate(10);
        $count = User::whereNot('id', Auth::id())->count();
        if ($request->ajax()) {
            return response()->json([
                'table' => view('staff.table', ['members' => $members])->render(),
                'accordion' => view('staff.accordion', ['members' => $members])->render()
            ]);
        }
        return view('staff.index', compact('members', 'count'));
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
            'first_name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'identification' => 'nullable|numeric|digits:9|unique:users',
            'telephone' => ['required', 'regex:/^[0-9-]{8,14}$/'],
            'role' => 'required',
            'kindergarten.*.role_id' => 'required',
            'kindergarten.*.association_id' => 'required',
        ],[
            'first_name.required' => __('staff.requiredName'),
            'email.required' => __('staff.requiredEmail'),
            'email.email' => __('staff.validEmail'),
            'email.unique' => __('staff.existsEmail'),
            'identification.numeric' => 'Please enter numbers only',
            'identification.digits' => 'Enter 9 digits only',
            'telephone.required' => __('validation.required'),
            'telephone.regex' => 'The number must be a combination of digits and hyphens, and must be between 8 and 14 characters long.',
            'role.required' => __('staff.requiredRole'),
            'kindergarten.*.role_id.required' => 'Please choose role',
            'kindergarten.*.association_id.required' => 'Please choose association',
        ]);
        $validator->after(function ($validator) use ($request) {
            // if ($request->kindergarten_id && count($request->kindergarten_id) > 0) {
            //     foreach ($request->kindergarten as $index => $kindergarten) {
            //         if (empty($kindergarten['role_id'])) {
            //             $validator->errors()->add("kindergarten.$index.role_id", 'Please choose role');
            //         }
            //         if (empty($kindergarten['association_id'])) {
            //             $validator->errors()->add("kindergarten.$index.association_id", 'Please choose association');
            //         }
            //     }
            // }
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
            
            $request['password'] = rand();
            if (Session::has('staffPhoto')) {
                $request['photo'] = Session::get('staffPhoto');
            }
            $request['name'] = $request->first_name.' '.$request->family_name;
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
            'first_name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'identification' => ['nullable', 'numeric', 'digits:9', Rule::unique('users')->ignore($id)],
            'telephone' => ['required', 'regex:/^[0-9-]{8,14}$/'],
            'role' => 'required',
            'kindergarten.*.role_id' => 'required',
            'kindergarten.*.association_id' => 'required',
        ],[
            'first_name.required' => __('staff.requiredName'),
            'email.required' => __('staff.requiredEmail'),
            'email.email' => __('staff.validEmail'),
            'email.unique' => __('staff.existsEmail'),
            'identification.numeric' => 'Please enter numbers only',
            'identification.digits' => 'Enter 9 digits only',
            'telephone.required' => __('validation.required'),
            'telephone.regex' => 'The number must be a combination of digits and hyphens, and must be between 8 and 14 characters long.',
            'role.required' => __('staff.requiredRole'),
            'kindergarten.*.role_id.required' => 'Please choose role',
            'kindergarten.*.association_id.required' => 'Please choose association',
        ]);
        $validator->after(function ($validator) use ($request) {
            // if ($request->kindergarten_id && count($request->kindergarten_id) > 0) {
            //     foreach ($request->kindergarten as $index => $kindergarten) {
            //         if (empty($kindergarten['role_id'])) {
            //             $validator->errors()->add("kindergarten.$index.role_id", 'Please choose role');
            //         }
            //         if (empty($kindergarten['association_id'])) {
            //             $validator->errors()->add("kindergarten.$index.association_id", 'Please choose association');
            //         }
            //     }
            // }
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

            $request['name'] = $request->first_name.' '.$request->family_name;
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
            return response()->json(['status' => true, 'message' => 'Staff member has been successfully archived!', 'ids' => $ids]);
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

    public function deleteStaffKindergarten(Request $request)
    {
        StaffKindergarten::where(['user_id' => $request->user_id, 'kindergarten_id' => $request->id])->delete();
        return response()->json(['status' => true]);
    }
}
