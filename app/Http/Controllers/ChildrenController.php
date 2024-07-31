<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\ChildrenDocumentation;
use App\Models\ChildrenMedicalInformation;
use App\Models\ChildrenParent;
use App\Models\Cluster;
use App\Models\Diagnosis;
use App\Models\Functionality;
use App\Models\Hmo;
use App\Models\Individual;
use App\Models\IndividualGroup;
use App\Models\Kindergarten;
use App\Models\ParentsStatus;
use App\Models\StaffKindergarten;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth, DB;

class ChildrenController extends Controller
{
    public function index(Request $request)
    {
        $childrens = Children::filter()->paginate(10);
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.table', ['childrens' => $childrens])->render(),
                'accordion' => view('children.accordion', ['childrens' => $childrens])->render()
            ]);
        }
        return view('children.index', compact('childrens', 'kindergartens'));
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
            'dob' => 'required',
            'father_telephone' => 'nullable|digits_between:8,14',
            'mother_telephone' => 'nullable|digits_between:8,14',
            'emergency_telephone' => 'nullable|digits_between:8,14',
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            'medicine_name' => "required_if:medicine,==,yes",
            'type' => "required_if:medicine,==,yes",
            'dosage_and_timing' => "required_if:medicine,==,yes",
            'where' => "required_if:medicine,==,yes",
        ],[
            'name.required' => __('children.requiredName'),
            'family_name.required' => __('children.requiredFamilyName'),
            'dob.required' => __('children.requiredDOB'),
            'father_telephone.digits_between' => __('children.digitsBetween'),
            'mother_telephone.digits_between' => __('children.digitsBetween'),
            'emergency_telephone.digits_between' => __('children.digitsBetween'),
            'food_allergie_detail.required' => __('children.requiredFoodAllergieDetail'),
            'medicine_name.required_if' => __('children.requiredMedicineName'),
            'type.required_if' => __('children.requiredType'),
            'dosage_and_timing.required_if' => __('children.requiredDosageAndTiming'),
            'where.required_if' => __('children.requiredWhere'),
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
                'identification' => $request->identification,
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
                'medicine_name' => $request->medicine == 'yes' ? $request->medicine_name : '',
                'type' => $request->medicine == 'yes' ? $request->type : '',
                'dosage_and_timing' => $request->medicine == 'yes' ? $request->dosage_and_timing : '',
                'where' => $request->medicine == 'yes' ? $request->where : '',
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
            // 'gender' => 'required',
            'dob' => 'required',
            // 'address' => 'required',
            // 'functionality_id' => 'required',
            // 'diagnosis_id' => 'required',
            // 'status_id' => 'required',
            // 'service_start_date' => 'required',
            // 'hmo_id' => 'required',
            // 'father_name' => 'required',
            'father_telephone' => 'nullable|digits_between:8,14',
            // 'mother_name' => 'required',
            'mother_telephone' => 'nullable|digits_between:8,14',
            // 'family_status' => 'required',
            // 'emergency_name' => 'required',
            // 'emergency_relationship' => 'required',
            'emergency_telephone' => 'nullable|digits_between:8,14',
            // 'food_allergie' => 'required',
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            // 'medicine' => "required",
            // 'medicine_detail' => "required_if:medicine,==,yes",
            'medicine_name' => "required_if:medicine,==,yes",
            'type' => "required_if:medicine,==,yes",
            'dosage_and_timing' => "required_if:medicine,==,yes",
            'where' => "required_if:medicine,==,yes",
        ],[
            'name.required' => __('validation.required'),
            'family_name.required' => __('validation.required'),
            'dob.required' => __('validation.required'),
            // 'address.required' => __('validation.required'),
            // 'functionality_id.required' => __('validation.required'),
            // 'diagnosis_id.required' => __('validation.required'),
            // 'status_id.required' => __('validation.required'),
            // 'service_start_date.required' => __('validation.required'),
            // 'hmo_id.required' => __('validation.required'),
            // 'father_name.required' => __('validation.required'),
            // 'father_telephone.required' => __('validation.required'),
            'father_telephone.digits_between' => __('children.digitsBetween'),
            // 'mother_name.required' => __('validation.required'),
            // 'mother_telephone.required' => __('validation.required'),
            'mother_telephone.digits_between' => __('children.digitsBetween'),
            // 'family_status.required' => __('validation.required'),
            // 'emergency_name.required' => __('validation.required'),
            // 'emergency_relationship.required' => __('validation.required'),
            // 'emergency_telephone.required' => __('validation.required'),
            'emergency_telephone.digits_between' => __('children.digitsBetween'),
            // 'food_allergie.required' => __('validation.required'),
            'food_allergie_detail.required_if' => __('children.requiredFoodAllergieDetail'),
            // 'medicine.required' => __('validation.required'),
            // 'medicine_detail.required' => __('validation.required'),
            'medicine_name.required_if' => __('children.requiredMedicineName'),
            'type.required_if' => __('children.requiredType'),
            'dosage_and_timing.required_if' => __('children.requiredDosageAndTiming'),
            'where.required_if' => __('children.requiredWhere'),
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
                'identification' => $request->identification,
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
                'medicine_name' => $request->medicine == 'yes' ? $request->medicine_name : '',
                'type' => $request->medicine == 'yes' ? $request->type : '',
                'dosage_and_timing' => $request->medicine == 'yes' ? $request->dosage_and_timing : '',
                'where' => $request->medicine == 'yes' ? $request->where : '',
            ]);

            DB::commit();
            return redirect()->route('children.show', $id);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Children::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('children.archived'), 'ids' => $ids]);
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
            return response()->json(['status' => true, 'message' => __('children.uploadProfile'), 'src' => asset('storage/'.$photo)]);
        }
        return response()->json(['status' => false]);
    }

    public function documentations(Request $request, $id)
    {
        $documentations = ChildrenDocumentation::where('children_id', $id)->filter()->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.document.documentation-table', ['documentations' => $documentations])->render(),
                'accordion' => view('children.document.documentation-accordion', ['documentations' => $documentations])->render()
            ]);
        }
        return view('children.document.documentation', compact('documentations'));
    }

    public function documentationDetail($childId, $id)
    {
        $children = Children::findOrFail($childId);
        $document = ChildrenDocumentation::findOrFail($id);
        return view('children.document.documentation-detail', compact('document', 'children'));
    }

    public function documentation(Request $request, $type, $id)
    {
        $children = Children::findOrFail($id);
        $childrens = Children::where('id', '!=', $id)->select('id as key', 'name as value')->get();
        switch ($type) {
            case 'individual':
                return view('children.document.individual', compact('children'));
            break;
            case 'group':
                return view('children.document.group', compact('children', 'childrens'));
            break;
            case 'parental-guidance':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $kindergartens = User::where('id', '!=', $id)->whereIn('id', $userIds)->select('id as key', 'name as value')->get();
                return view('children.document.parental-guidance', compact('children', 'childrens', 'kindergartens'));
            break;
            case 'staff-meeting':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $therapist = User::where('id', '!=', $id)->whereIn('id', $userIds)->role('therapist')->select('id as key', 'name as value')->get();
                return view('children.document.staff-meeting', compact('children', 'childrens', 'therapist'));
            break;
            case 'initial-evaluation':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $therapist = User::where('id', '!=', $id)->whereIn('id', $userIds)->role('therapist')->select('id as key', 'name as value')->get();
                return view('children.document.initial-evaluation', compact('children', 'childrens', 'therapist'));
            break;
            case 'final-evaluation':
                return view('children.document.final-evaluation', compact('children'));
            break;
            default:
                return view('children.document.individual', compact('children'));
            break;
        }
    }
    public function saveDocumentation(Request $request, $type, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'occured' => 'required',
            'occured_description' => 'required',
            'occured_reason' => "required_if:occured,==,0",
            'child_file' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request['children_id'] = $id;
        $request['type'] = $type;
        if ($request->has('child_file')) {
            $request['file'] = uploadFile($request->child_file, 'public/child-document');
        }
        switch ($type) {
            case 'individual':
                $document = ChildrenDocumentation::create($request->all());
            break;
            case 'group':
                $document = ChildrenDocumentation::create($request->all());
                foreach ($request->participated as $participated) {
                    if (isset($participated['child_file'])) {
                        $participated['file'] = uploadFile($participated['child_file'], 'public/child-document');
                    }
                    $participated['children_id'] = $id;
                    $document->groupChildrens()->create($participated);
                }
            break;
            case 'parental-guidance':
                $document = ChildrenDocumentation::create($request->all());
                if (isset($request->children_ids) && count($request->children_ids) > 0) {
                    foreach ($request->children_ids as $childrenId) {
                        $document->parentalGuidanceChildren()->create(['children_id' => $childrenId]);
                    }
                }
                if (isset($request->kindergarten_ids) && count($request->kindergarten_ids) > 0) {
                    foreach ($request->kindergarten_ids as $kindergartenId) {
                        $document->parentalGuidanceKindergarten()->create(['kindergarten_id' => $kindergartenId]);
                    }
                }
            break;
            case 'staff-meeting':
                $document = ChildrenDocumentation::create($request->all());
                $document->staffMeeting()->create([
                    'children_id' => $id,
                    'topic' => $request->topic,
                    'discussion' => $request->discussion,
                    'decisions' => $request->decisions,
                ]);
                if (isset($request->children_ids) && count($request->children_ids) > 0) {
                    foreach ($request->children_ids as $childrenId) {
                        $document->staffMeetingChildren()->create(['children_id' => $childrenId]);
                    }
                }
                if (isset($request->therapist_id) && count($request->therapist_id) > 0) {
                    foreach ($request->therapist_id as $therapistId) {
                        $document->staffMeetingTherapist()->create(['therapist_id' => $therapistId]);
                    }
                }
            break;
            case 'initial-evaluation':
                
            break;
            case 'final-evaluation':
                
            break;
            default:
                
            break;
        }
        return redirect()->route('children.show', $id);

    }
}
