<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth, DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('user.index', compact('user'));
    }
    
    public function edit(Request $request)
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'telephone' => 'required|digits_between:8,14',
            'licence_number' => 'required',
            'dob' => 'required',
            'member_photo' => 'max:2000',
        ],[
            'name.required' => __('staff.requiredName'),
            'address.required' => __('staff.requiredAddress'),
            'telephone.required' => __('staff.requiredTelephone'),
            'licence_number.required' => __('staff.requiredLicence'),
            'dob.required' => __('staff.requiredDOB'),
            'member_photo.max' => 'The photo may not be greater than 2MB',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {

            if ($request->hasFile('member_photo')) {
                $request['photo'] = uploadFile($request->member_photo, 'public/staff');
            }
            User::where('id', Auth::id())->update($request->except('_token', 'member_photo'));
            DB::commit();
            return redirect()->route('profile.index');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function changePasswordView()
    {
        $user = Auth::user();
        return view('user.password', compact('user'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'The old password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'confirm_password.required' => 'The confirm password is required.',
            'confirm_password.same' => 'The confirm password must match with the new password.',
        ]);
        
        $user = Auth::user();
        $validator->after(function ($validator) use ($request, $user) {
            if (!Hash::check($request->input('old_password'), $user->password)) {
                $validator->errors()->add('old_password', 'The old password is incorrect.');
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            
            $user->update(['password' => Hash::make($request->confirm_password)]);
            DB::commit();
            return redirect()->route('profile.index');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }
}