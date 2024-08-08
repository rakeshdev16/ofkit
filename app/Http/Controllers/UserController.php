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
            'telephone' => ['required', 'regex:/^[0-9-]{8,14}$/'],
            'licence_number' => 'required|regex:/^[0-9-]+$/',
            'member_photo' => 'image|max:2000',
        ], [
            'name.required' => __('validation.required'),
            'address.required' => __('validation.required'),
            'telephone.required' => __('validation.required'),
            'telephone.regex' => 'The number must be a combination of digits and hyphens, and must be between 8 and 14 characters long.',
            'licence_number.required' => __('validation.required'),
            'licence_number.regex' => 'Only digits are allowed with hyphens',
            'member_photo.image' => __('validation.image'),
            'member_photo.max' => __('validation.max'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {

            if ($request->hasFile('member_photo')) {
                $request['photo'] = uploadFile($request->member_photo, 'public/staff');
            }
            User::where('id', Auth::id())->update([
                'name' => $request->name,
                'address' => $request->address,
                'telephone' => $request->telephone,
                'licence_number' => $request->licence_number,
                'dob' => $request->dob,
            ]);
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

    public function uploadUserProfile(Request $request)
    {
        if ($request->hasFile('image')) {
            $photo = uploadFile($request->image, 'public/staff', $request->extension);
            if ($request->type == 'update') {
                User::where('id', $request->user_id)->update(['photo' => $photo]);
            }
            return response()->json(['status' => true, 'message' => 'Profile has been uploaded', 'src' => asset('storage/'.$photo)]);
        }
        return response()->json(['status' => false]);
    }
    
    public function deletePhoto(Request $request)
    {
        if (User::where('id', $request->id)->update(['photo' => NULL])) {
            return response()->json(['status' => true, 'message' => 'Profile image has been deleted', 'src' => asset('assets/images/avatars/dummy-image.webp')]);
        }
        return response()->json(['status' => true, 'message' => 'Profile image has been deleted', 'src' => asset('assets/images/avatars/dummy-image.webp')]);
    }

    public function setPreviousRoute(Request $request)
    {
        // \Session::put('last_url', url()->previous());
        \Cache::put('last_url', url()->previous());
        return response()->json(['status' => true]);
    }
}