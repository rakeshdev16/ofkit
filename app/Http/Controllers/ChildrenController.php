<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\ChildrenMedicalInformation;
use App\Models\ChildrenParent;
use App\Models\Cluster;
use App\Models\Diagnosis;
use App\Models\Functionality;
use App\Models\Hmo;
use App\Models\Kindergarten;
use App\Models\ParentsStatus;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth, DB;

class ChildrenController extends Controller
{
    public function index(Request $request)
    {
        $childrens = Children::filter()->where('user_id', Auth::id())->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.table', ['childrens' => $childrens])->render(),
                'accordion' => view('children.accordion', ['childrens' => $childrens])->render()
            ]);
        }
        return view('children.index', compact('childrens'));
    }
    
    public function create()
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $functionalities = Functionality::select('id as key', 'name as value')->get()->toArray();
        $dianioses = Diagnosis::select('id as key', 'name as value')->get()->toArray();
        $statuses = Status::select('id as key', 'name as value')->get()->toArray();
        $parentsStatus = ParentsStatus::select('id as key', 'name as value')->get()->toArray();
        $hmos = Hmo::select('id as key', 'name as value')->get()->toArray();
        return view('children.create', compact('kindergartens', 'functionalities', 'dianioses', 'statuses', 'hmos', 'parentsStatus'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'family_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'functionality_id' => 'required',
            'diagnosis_id' => 'required',
            'status_id' => 'required',
            'service_start_date' => 'required',
            'hmo_id' => 'required',
            'father_name' => 'required',
            'father_telephone' => 'required',
            'mother_name' => 'required',
            'mother_telephone' => 'required',
            'family_status' => 'required',
            'emergency_name' => 'required',
            'emergency_relationship' => 'required',
            'emergency_telephone' => 'required',
            'food_allergie' => 'required',
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            'medicine' => "required",
            'medicine_detail' => "required_if:medicine,==,yes",
            'medicine_name' => "required",
            'type' => "required",
            'dosage_and_timing' => "required",
            'where' => "required",
        ],[
            'name' => 'Please enter name',
            'family_name' => 'Please enter family name',
            'dob' => 'Please choose date of birth',
            'address' => 'Please enter address',
            'functionality_id' => 'Please choose functionality',
            'diagnosis_id' => 'Please choose diganosis',
            'status_id' => 'Please choose status',
            'service_start_date' => 'Please choose date',
            'hmo_id' => 'Please choose hmo',
            'father_name' => 'Please enter father name',
            'father_telephone' => "Please enter father's telephone",
            'mother_name' => 'Please enter mother name',
            'mother_telephone' => "Please enter mother's telephone",
            'family_status' => "Please choose family status",
            'emergency_name' => "Please enter name",
            'emergency_relationship' => "Please enter relationship",
            'emergency_telephone' => "Please enter telephone",
            'food_allergie' => "Please choose food allergie",
            'food_allergie_detail' => "Please enter food allergie detail",
            'medicine' => "Please choose medicine",
            'medicine_detail' => "Please enter the medicine detail",
            'medicine_name' => "Please enter the medicine name",
            'type' => "Please choose the medicine type",
            'dosage_and_timing' => "Please enter the medicine dosage and timing",
            'where' => "Please choose option",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {

            $photo = Session::has('childrenPhoto') ? Session::get('childrenPhoto') : NULL;
            $children = Children::create([
                'user_id' => Auth::id(),
                'kindergarten_id' => $request->kindergarten_id,
                'name' => $request->name,
                'family_name' => $request->family_name,
                'identification' => Str::uuid(),
                'gender' => $request->gender,
                'dob' => $request->dob,
                'age' => $request->age,
                'address' => $request->address,
                'functionality_id' => $request->functionality_id,
                'diagnosis_id' => $request->diagnosis_id,
                'status_id' => $request->status_id,
                'service_start_date' => $request->service_start_date,
                'hmo_id' => $request->hmo_id,
                'photo' => $photo,
            ]);
            $children->parent()->create([
                'father_name' => $request->father_name,
                'father_telephone' => $request->father_telephone,
                'mother_name' => $request->mother_name,
                'mother_telephone' => $request->mother_telephone,
                'family_status' => $request->family_status,
                'name' => $request->emergency_name,
                'relationship' => $request->emergency_relationship,
                'telephone' => $request->emergency_telephone,
            ]);
            $children->medicalInformation()->create([
                'food_allergie' => $request->food_allergie == 'yes' ? 1 : 0,
                'food_allergie_detail' => $request->food_allergie == 'yes' ? $request->food_allergie_detail : '',
                'medicine' => $request->medicine == 'yes' ? 1 : 0,
                'medicine_detail' => $request->medicine == 'yes' ? $request->medicine_detail : '',
                'medicine_name' => $request->medicine_name,
                'type' => $request->type,
                'dosage_and_timing' => $request->dosage_and_timing,
                'where' => $request->where,
            ]);

            DB::commit();
            return redirect()->route('children.index');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $children = Children::findOrFail($id);
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $functionalities = Functionality::select('id as key', 'name as value')->get()->toArray();
        $dianioses = Diagnosis::select('id as key', 'name as value')->get()->toArray();
        $statuses = Status::select('id as key', 'name as value')->get()->toArray();
        $parentsStatus = ParentsStatus::select('id as key', 'name as value')->get()->toArray();
        $hmos = Hmo::select('id as key', 'name as value')->get()->toArray();
        return view('children.show', compact('children', 'kindergartens', 'functionalities', 'dianioses', 'statuses', 'hmos', 'parentsStatus'));
    }

    public function edit($id)
    {
        $children = Children::findOrFail($id);
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $functionalities = Functionality::select('id as key', 'name as value')->get()->toArray();
        $dianioses = Diagnosis::select('id as key', 'name as value')->get()->toArray();
        $statuses = Status::select('id as key', 'name as value')->get()->toArray();
        $parentsStatus = ParentsStatus::select('id as key', 'name as value')->get()->toArray();
        $hmos = Hmo::select('id as key', 'name as value')->get()->toArray();
        return view('children.edit', compact('children', 'kindergartens', 'functionalities', 'dianioses', 'statuses', 'hmos', 'parentsStatus'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'family_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'functionality_id' => 'required',
            'diagnosis_id' => 'required',
            'status_id' => 'required',
            'service_start_date' => 'required',
            'hmo_id' => 'required',
            'father_name' => 'required',
            'father_telephone' => 'required',
            'mother_name' => 'required',
            'mother_telephone' => 'required',
            'family_status' => 'required',
            'emergency_name' => 'required',
            'emergency_relationship' => 'required',
            'emergency_telephone' => 'required',
            'food_allergie' => 'required',
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            'medicine' => "required",
            'medicine_detail' => "required_if:medicine,==,yes",
            'medicine_name' => "required",
            'type' => "required",
            'dosage_and_timing' => "required",
            'where' => "required",
        ],[
            'name' => 'Please enter name',
            'family_name' => 'Please enter family name',
            'dob' => 'Please choose date of birth',
            'address' => 'Please enter address',
            'functionality_id' => 'Please choose functionality',
            'diagnosis_id' => 'Please choose diganosis',
            'status_id' => 'Please choose status',
            'service_start_date' => 'Please choose date',
            'hmo_id' => 'Please choose hmo',
            'father_name' => 'Please enter father name',
            'father_telephone' => "Please enter father's telephone",
            'mother_name' => 'Please enter mother name',
            'mother_telephone' => "Please enter mother's telephone",
            'family_status' => "Please choose family status",
            'emergency_name' => "Please enter name",
            'emergency_relationship' => "Please enter relationship",
            'emergency_telephone' => "Please enter telephone",
            'food_allergie' => "Please choose food allergie",
            'food_allergie_detail' => "Please enter food allergie detail",
            'medicine' => "Please choose medicine",
            'medicine_detail' => "Please enter the medicine detail",
            'medicine_name' => "Please enter the medicine name",
            'type' => "Please choose the medicine type",
            'dosage_and_timing' => "Please enter the medicine dosage and timing",
            'where' => "Please choose the option",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();

        try {

            $children = Children::findOrFail($id);
            $children->update([
                'user_id' => Auth::id(),
                'kindergarten_id' => $request->kindergarten_id,
                'name' => $request->name,
                'family_name' => $request->family_name,
                'identification' => Str::uuid(),
                'gender' => $request->gender,
                'dob' => $request->dob,
                'age' => $request->age,
                'address' => $request->address,
                'functionality_id' => $request->functionality_id,
                'diagnosis_id' => $request->diagnosis_id,
                'status_id' => $request->status_id,
                'service_start_date' => $request->service_start_date,
                'hmo_id' => $request->hmo_id,
            ]);
            $children->parent()->update([
                'father_name' => $request->father_name,
                'father_telephone' => $request->father_telephone,
                'mother_name' => $request->mother_name,
                'mother_telephone' => $request->mother_telephone,
                'family_status' => $request->family_status,
                'name' => $request->emergency_name,
                'relationship' => $request->emergency_relationship,
                'telephone' => $request->emergency_telephone,
            ]);
            $children->medicalInformation()->update([
                'food_allergie' => $request->food_allergie == 'yes' ? 1 : 0,
                'food_allergie_detail' => $request->food_allergie == 'yes' ? $request->food_allergie_detail : '',
                'medicine' => $request->medicine == 'yes' ? 1 : 0,
                'medicine_detail' => $request->medicine == 'yes' ? $request->medicine_detail : '',
                'medicine_name' => $request->medicine_name,
                'type' => $request->type,
                'dosage_and_timing' => $request->dosage_and_timing,
                'where' => $request->where,
            ]);

            DB::commit();
            return redirect()->route('children.index');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Children::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('children.deleteStaffMsg'), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }

    public function uploadProfile(Request $request)
    {
        if ($request->hasFile('image')) {
            $photo = uploadFile($request->image, 'public/children', $request->extension);
            if ($request->type == 'add') {
                Session::put('childrenPhoto', $photo);
            } else {
                Children::where('id', $request->user_id)->update(['photo' => $photo]);
            }
            return response()->json(['status' => true, 'message' => 'Profile has been uploaded', 'src' => asset('storage/'.$photo)]);
        }
        return response()->json(['status' => false]);
    }
}
