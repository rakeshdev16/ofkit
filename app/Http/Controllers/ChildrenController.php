<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\ChildrenDocumentAndApproval;
use App\Models\ChildrenDocumentation;
use App\Models\ChildrenMedicalInformation;
use App\Models\ChildrenMedicine;
use App\Models\ChildrenParent;
use App\Models\Cluster;
use App\Models\Diagnosis;
use App\Models\FamilyLanguage;
use App\Models\Functionality;
use App\Models\GroupChildren;
use App\Models\Hmo;
use App\Models\Individual;
use App\Models\IndividualGroup;
use App\Models\Kindergarten;
use App\Models\KindergartenUser;
use App\Models\ParentsStatus;
use App\Models\Profession;
use App\Models\StaffKindergarten;
use App\Models\StaffMeetingChildren;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Auth, DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class ChildrenController extends Controller
{
    public function index(Request $request)
    {
        $previousRequest = app('request')->create(app('url')->previous());
        if ($previousRequest && (app('router')->getRoutes()->match($previousRequest)->getName() == 'password.reset')) {
            Auth::logout();
            return redirect()->route('login');
        }
        $childrens = Children::filter()->orderBy('id', 'DESC')->paginate(50);
        $count = Children::filter()->count();
        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.table', ['childrens' => $childrens])->render(),
                'accordion' => view('children.accordion', ['childrens' => $childrens])->render(),
                'count' => $count
            ]);
        }
        return view('children.index', compact('childrens', 'count'));
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
            'identification' => 'nullable|numeric|digits:9|unique:childrens',
            'father_email' => ['nullable', 'string', 'email', 'max:255', 'unique:children_parents'],
            'father_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'mother_email' => ['nullable', 'string', 'email', 'max:255', 'unique:children_parents'],
            'mother_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'emergency_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            'medicine_dosage.*.name' => "required_if:medicine,==,yes",
            'medicine_dosage.*.type' => "required_if:medicine,==,yes",
            'medicine_dosage.*.dosage_and_timing' => "required_if:medicine,==,yes",
            'medicine_dosage.*.where' => "required_if:medicine,==,yes",
        ], [
            'name.required' => __('children.requiredName'),
            'family_name.required' => __('children.requiredFamilyName'),
            'dob.required' => __('children.requiredDOB'),
            'identification.numeric' => __('children.requiredIdentificationNumeric'),
            'identification.digits' => __('children.requiredIdentificationDigits'),
            'father_telephone.regex' => __('children.requiredTelephoneRegex'),
            'mother_telephone.regex' => __('children.requiredTelephoneRegex'),
            'emergency_telephone.regex' => __('children.requiredTelephoneRegex'),
            'food_allergie_detail.required_if' => __('children.requiredFoodAllergieDetail'),
            'medicine_dosage.*.name' => __('children.requiredName'),
            'medicine_dosage.*.type' => __('children.requiredType'),
            'medicine_dosage.*.dosage_and_timing' => __('children.requiredDosageAndTiming'),
            'medicine_dosage.*.where' => __('children.requiredWhere'),
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
                'status_id' => $request->status_id,
                'service_start_date' => $request->service_start_date,
                'hmo_id' => $request->hmo_id,
                'photo' => $photo,
            ]);
            if (isset($request->diagnosis_id) && count($request->diagnosis_id) > 0) {
                foreach ($request->diagnosis_id as $diagnosisId) {
                    $children->diagnosis()->create(['diagnosis_id' => $diagnosisId]);
                }
            }
            $children->parent()->create([
                'father_name' => $request->father_name,
                'father_email' => $request->father_email,
                'father_telephone' => $request->father_telephone,
                'father_work' => $request->father_work,
                'mother_name' => $request->mother_name,
                'mother_email' => $request->mother_email,
                'mother_telephone' => $request->mother_telephone,
                'mother_work' => $request->mother_work,
                'family_status' => $request->family_status,
                'siblings' => $request->siblings,
                'disabilities' => $request->disabilities,
                'name' => $request->emergency_name,
                'relationship' => $request->emergency_relationship,
                'telephone' => $request->emergency_telephone,
            ]);

            if (isset($request->spoken_language) && count($request->spoken_language) > 0) {
                foreach ($request->spoken_language as $language) {
                    $children->language()->create(['language' => $language]);
                }
            }

            $children->medicalInformation()->create([
                'food_allergie' => $request->food_allergie == 'yes' ? 1 : 0,
                'food_allergie_detail' => $request->food_allergie == 'yes' ? $request->food_allergie_detail : '',
                'medicine' => $request->medicine == 'yes' ? 1 : 0,
                'medicine_detail' => $request->medicine == 'yes' ? $request->medicine_detail : '',
            ]);

            if ($request->medicine == 'yes' && isset($request->medicine_dosage) && count($request->medicine_dosage) > 0) {
                $children->medicine()->createMany($request->medicine_dosage);
            }

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
            'dob' => 'required',
            'identification' => ['nullable', 'numeric', 'digits:9', Rule::unique('childrens')->ignore($id)],
            'father_email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('children_parents', 'father_email')->ignore($id, 'children_id')],
            'father_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'mother_email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('children_parents', 'mother_email')->ignore($id, 'children_id')],
            'mother_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'emergency_telephone' => ['nullable', 'regex:/^[0-9-]{8,14}$/'],
            'food_allergie_detail' => "required_if:food_allergie,==,yes",
            'medicine_dosage.*.name' => "required_if:medicine,==,yes",
            'medicine_dosage.*.type' => "required_if:medicine,==,yes",
            'medicine_dosage.*.dosage_and_timing' => "required_if:medicine,==,yes",
            'medicine_dosage.*.where' => "required_if:medicine,==,yes",
        ], [
            'name.required' => __('children.required'),
            'family_name.required' => __('children.required'),
            'dob.required' => __('children.required'),
            'identification.numeric' => __('children.requiredIdentificationDigits'),
            'identification.digits' => __('children.requiredIdentificationDigits'),
            'father_telephone.regex' => __('children.requiredTelephoneRegex'),
            'mother_telephone.regex' => __('children.requiredTelephoneRegex'),
            'emergency_telephone.regex' => __('children.requiredTelephoneRegex'),
            'food_allergie_detail.required_if' => __('children.requiredFoodAllergieDetail'),
            'medicine_dosage.*.name' => __('children.requiredName'),
            'medicine_dosage.*.type' => __('children.requiredType'),
            'medicine_dosage.*.dosage_and_timing' => __('children.requiredDosageAndTiming'),
            'medicine_dosage.*.where' => __('children.requiredWhere'),
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
                'status_id' => $request->status_id,
                'service_start_date' => $request->service_start_date,
                'hmo_id' => $request->hmo_id,
                'updated_at' => now(),
            ]);
            $children->diagnosis()->delete();
            if (isset($request->diagnosis_id) && count($request->diagnosis_id) > 0) {
                foreach ($request->diagnosis_id as $diagnosisId) {
                    $children->diagnosis()->create(['diagnosis_id' => $diagnosisId]);
                }
            }
            $children->parent()->update([
                'father_name' => $request->father_name,
                'father_email' => $request->father_email,
                'father_telephone' => $request->father_telephone,
                'father_work' => $request->father_work,
                'mother_name' => $request->mother_name,
                'mother_email' => $request->mother_email,
                'mother_telephone' => $request->mother_telephone,
                'mother_work' => $request->mother_work,
                'family_status' => $request->family_status,
                'siblings' => $request->siblings,
                'disabilities' => $request->disabilities,
                'name' => $request->emergency_name,
                'relationship' => $request->emergency_relationship,
                'telephone' => $request->emergency_telephone,
            ]);
            $children->language()->delete();
            if (isset($request->spoken_language) && count($request->spoken_language) > 0) {
                foreach ($request->spoken_language as $language) {
                    $children->language()->create(['language' => $language]);
                }
            }
            $children->medicalInformation()->update([
                'food_allergie' => $request->food_allergie == 'yes' ? 1 : 0,
                'food_allergie_detail' => $request->food_allergie == 'yes' ? $request->food_allergie_detail : '',
                'medicine' => $request->medicine == 'yes' ? 1 : 0,
                'medicine_detail' => $request->medicine == 'yes' ? $request->medicine_detail : '',
            ]);
            $children->medicine()->delete();
            if ($request->medicine == 'yes' && isset($request->medicine_dosage) && count($request->medicine_dosage) > 0) {
                $children->medicine()->createMany($request->medicine_dosage);
            }

            DB::commit();
            return redirect()->route('children.show', ['child' => $id, 'kindergarten_id' => $request->query_string]);
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
            return response()->json(['status' => true, 'message' => __('children.uploadProfile'), 'src' => asset('storage/' . $photo)]);
        }
        return response()->json(['status' => false]);
    }

    public function documentations(Request $request, $id)
    {
        $children = Children::findOrFail($id);
        // $roles = Role::get();
        $roles = Profession::get();
        $therapistIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
        $therapists = User::role(['admin', 'therapist'])->whereIn('id', $therapistIds)->select('id', 'name')->get();

        $docIds = [];
        $childDocIds = ChildrenDocumentation::where('children_id', $id)->pluck('id')->toArray();
        $staffMeetingDocIds = StaffMeetingChildren::where('children_id', $id)->pluck('children_doc_id')->toArray();
        $groupDocIds = GroupChildren::where('children_id', $id)->pluck('children_documentation_id')->toArray();
        $docIds = array_merge(array_unique($childDocIds), array_unique($staffMeetingDocIds), array_unique($groupDocIds));
        $documentations = ChildrenDocumentation::whereIn('id', $docIds)->filter()->orderBy('id', 'DESC')->paginate(50);
        $documentationCount = ChildrenDocumentation::whereIn('id', $docIds)->filter()->count();

        // Get start and end date of last week
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $lastWeek = json_encode([$startOfLastWeek->toDateString(), $endOfLastWeek->toDateString()]);
        // Get start and end date of current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $month = json_encode([$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
        // Get start and end date of past three month
        $startDateOfPast3Month = Carbon::now()->subMonths(3)->startOfMonth();
        $pastThreeMonth = json_encode([$startDateOfPast3Month->toDateString(), $endOfMonth->toDateString()]);
        // Get start and end date of past 6 month
        $startDateOfPast6Month = Carbon::now()->subMonths(6)->startOfMonth();
        $pastSixMonth = json_encode([$startDateOfPast6Month->toDateString(), $endOfMonth->toDateString()]);


        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.document.documentation-table', ['documentations' => $documentations, 'children' => $children])->render(),
                'accordion' => view('children.document.documentation-accordion', ['documentations' => $documentations, 'children' => $children])->render(),
                'count' => $documentationCount
            ]);
        }
        return view('children.document.documentation', compact('children', 'documentations', 'documentationCount', 'roles', 'therapists', 'lastWeek', 'month', 'pastThreeMonth', 'pastSixMonth'));
    }

    public function documentationDetail($childId, $id, $mailchildId = NULL)
    {
        $children = Children::findOrFail($childId);
        $mainChildren = Children::findOrFail($mailchildId);
        $document = ChildrenDocumentation::findOrFail($id);

        return view('children.document.documentation-detail', compact('document', 'children', 'mainChildren'));
    }

    public function documentation(Request $request, $type, $childId, $id = null)
    {
        $document = '';
        if ($id) {
            $document = ChildrenDocumentation::findOrFail($id);
        }
        $children = Children::findOrFail($childId);
        $childrens = Children::where('id', '!=', $childId)->where('kindergarten_id', $children->kindergarten_id)->select('id as key', 'name as value')->get();
        $user = Auth::user();
        $allTherapists = User::role(['admin', 'therapist'])->select('id as key', 'name as value')->get();
        switch ($type) {
            case 'individual':
                return view('children.document.individual', compact('allTherapists', 'children', 'user', 'document'));
                break;
            case 'group':
                return view('children.document.group', compact('allTherapists', 'children', 'user', 'document', 'childrens'));
                break;
            case 'parental-guidance':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $kindergartens = User::whereIn('id', $userIds)->select('id as key', 'name as value')->get();
                return view('children.document.parental-guidance', compact('allTherapists', 'children', 'user', 'document', 'childrens', 'kindergartens'));
                break;
            case 'staff-meeting':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $therapist = User::whereIn('id', $userIds)->role(['therapist', 'manager'])->select('id as key', 'name as value')->get();
                return view('children.document.staff-meeting', compact('allTherapists', 'children', 'user', 'document', 'childrens', 'therapist'));
                break;
            case 'initial-evaluation':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $therapist = User::whereIn('id', $userIds)->role(['therapist', 'manager'])->select('id as key', 'name as value')->get();
                return view('children.document.initial-evaluation', compact('allTherapists', 'children', 'user', 'document', 'childrens', 'therapist'));
                break;
            case 'final-evaluation':
                $userIds = StaffKindergarten::where('kindergarten_id', $children->kindergarten_id)->pluck('user_id')->toArray();
                $therapist = User::whereIn('id', $userIds)->role(['therapist', 'manager'])->select('id as key', 'name as value')->get();
                return view('children.document.final-evaluation', compact('allTherapists', 'children', 'user', 'document', 'childrens', 'therapist'));
                break;
            default:
                return view('children.document.individual', compact('allTherapists', 'children', 'childrens', 'user', 'document'));
                break;
        }
    }
    public function saveDocumentation(Request $request, $type, $id)
    {
        $request['children_id'] = $id;
        $request['type'] = $type;
        // $request['therapist_id'] = Auth::id();
        if ($request['occured'] == 0) {
            $request['occured_description'] = '';
        }
        if ($request['occured'] == 1) {
            $request['occured_reason'] = '';
        }
        if ($request->has('child_file')) {
            $request['file'] = uploadFile($request->child_file, 'public/child-document');
        }
        if ($request->has('delete_file') && $request->delete_file == 1) {
            $request['file'] = NULL;
        }
        switch ($type) {
            case 'individual':
                return $this->individual($request->all(), $id);
                break;
            case 'group':
                return $this->group($request->all(), $id);
                break;
            case 'parental-guidance':
                return $this->parentalGuidance($request->all(), $id);
                break;
            case 'staff-meeting':
                return $this->staffMeeting($request->all(), $id);
                break;
            case 'initial-evaluation':
                return $this->initialEvaluation($request->all(), $id);
                break;
            case 'final-evaluation':
                return $this->finalEvaluation($request->all(), $id);
                break;
        }
    }

    public function individual(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'end_time' => 'required_with:start_time',
        ];

        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
            // 'end_time.required_if' => 'Please enter end time',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);

        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function group(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'group_name' => 'required_if:occured,==,0',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'children_ids' => "required_if:occured,==,0",
            'participated.*.participated' => 'required_if:occured,==,1',
            'participated.*.reason' => "required_if:participated.*.participated,==,0",
            'participated.*.description' => "required_if:participated.*.participated,==,1",
            'participated.*.child_file' => "nullable",
            'end_time' => 'required_with:start_time',
        ];

        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'group_name.required_if' => __('children.groupName'),
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
            'children_ids.required_if' => 'Please choose children',
            'participated.*.participated.required_if' => __('children.participated'),
            'participated.*.reason.required_if' => __('children.reason'),
            'participated.*.description.required_if' => __('children.description'),
            'participated.*.child_file.required' => __('children.file'),
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($data['occured'] == 0) {
            $data['file'] = NULL;
        }
        $document = ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);
        $document->groupChildrens()->delete();
        if (isset($data['participated']) && count($data['participated']) > 0) {
            foreach ($data['participated'] as $participated) {
                if (isset($participated['child_file'])) {
                    $participated['file'] = uploadFile($participated['child_file'], 'public/child-document');
                } else {
                    $participated['file'] = $participated['old_file'];
                }
                $document->groupChildrens()->create($participated);
            }
        }

        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function parentalGuidance(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'end_time' => 'required_with:start_time',
        ];

        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $document = ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);
        // if (isset($request->children_ids) && count($request->children_ids) > 0) {
        //     foreach ($request->children_ids as $childrenId) {
        //         $document->parentalGuidanceChildren()->create(['children_id' => $childrenId]);
        //     }
        // }
        // if (isset($request->kindergarten_ids) && count($request->kindergarten_ids) > 0) {
        //     foreach ($request->kindergarten_ids as $kindergartenId) {
        //         $document->parentalGuidanceKindergarten()->create(['kindergarten_id' => $kindergartenId]);
        //     }
        // }
        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function staffMeeting(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'end_time' => 'required_with:start_time',
            'therapist_ids' => 'required|array|min:1',
            'children.*.topic' => 'required_if:occured,==,1',
            'children.*.discussion' => 'required_if:occured,==,1',
            'children.*.decisions' => 'required_if:occured,==,1',

            // 'topic' => 'required_if:occured,==,1',
            // 'discussion' => 'required_if:occured,==,1',
            // 'decisions' => 'required_if:occured,==,1',
        ];

        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
            'children.*.topic.required_if' => __('children.topic'),
            'children.*.discussion.required_if' => __('children.discussion'),
            'children.*.decisions.required_if' => __('children.decisions'),
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($data['occured'] == 0) {
            $data['file'] = NULL;
        }
        $document = ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);
        $document->staffMeeting()->delete();
        if ($data['occured'] == 1) {
            // $document->staffMeeting()->create([
            //     'children_id' => $id,
            //     'topic' => $data['topic'],
            //     'discussion' => $data['discussion'],
            //     'decisions' => $data['decisions'],
            // ]);
            $document->staffMeeting()->delete();
            if (isset($data['children']) && count($data['children']) > 0) {
                foreach ($data['children'] as $children) {
                    $document->staffMeeting()->create([
                        'children_id' => $children['children_id'],
                        'topic' => $children['topic'],
                        'discussion' => $children['discussion'],
                        'decisions' => $children['decisions'],
                    ]);
                }
            }
        }
        $document->staffMeetingChildren()->delete();
        $document->staffMeetingChildren()->create(['children_id' => $id]);
        if (isset($data['children_ids']) && count($data['children_ids']) > 0) {
            foreach ($data['children_ids'] as $childrenId) {
                $document->staffMeetingChildren()->create(['children_id' => $childrenId]);
            }
        }
        $document->staffMeetingTherapist()->delete();
        if (isset($data['therapist_ids']) && count($data['therapist_ids']) > 0) {
            foreach ($data['therapist_ids'] as $therapistId) {
                $document->staffMeetingTherapist()->create(['therapist_id' => $therapistId]);
            }
        }
        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function initialEvaluation(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'end_time' => 'required_with:start_time',
        ];

        if (!$data['id']) {
            $rules['child_file'] = 'required';
        }
        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);
        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function finalEvaluation(array $data, $id)
    {
        $rules = [
            'date' => 'required',
            'occured' => 'required',
            'occured_description' => 'required_if:occured,==,1',
            'occured_reason' => "required_if:occured,==,0",
            'end_time' => 'required_with:start_time',
        ];

        if (!empty($data['end_time'])) {
            $rules['start_time'] = 'required';
        }

        $messages = [
            'occured_description.required_if' => __('children.occuredDescription'),
            'occured_reason.required_if' => __('children.occuredReason'),
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ChildrenDocumentation::updateOrCreate(['id' => $data['id']], $data);
        if ($data['id']) {
            return redirect()->route('children-documentation.show', [$id, $data['id'], $id]);
        }
        return redirect()->route('children-documentations.get', $id);
    }

    public function deleteProfile(Request $request)
    {
        if (Children::where('id', $request->id)->update(['photo' => NULL])) {
            return response()->json(['status' => true, 'message' => 'Profile image has been deleted', 'src' => asset('assets/images/avatars/dummy-image.webp')]);
        }
        return response()->json(['status' => true, 'message' => 'Profile image has been deleted', 'src' => asset('assets/images/avatars/dummy-image.webp')]);
    }

    public function deleteChildrenMedicine(Request $request)
    {
        if (ChildrenMedicine::where('id', $request->id)->delete()) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function getKindergartenManager(Request $request)
    {
        $managerId = KindergartenUser::where('kindergarten_id', $request->kindergarten_id)->pluck('user_id')->first();
        return response()->json([
            'status' => !empty($managerId) ? true : false,
            'name' => getUserNameById($managerId)
        ]);
    }

    public function documentsAndApprovals(Request $request, $childId)
    {
        $children = Children::findOrFail($childId);
        $documents = ChildrenDocumentAndApproval::where('children_id', $childId)->filter()->orderBy('id', 'DESC')->paginate(50);
        $count = ChildrenDocumentAndApproval::where('children_id', $childId)->filter()->count();
        if ($request->ajax()) {
            return response()->json([
                'table' => view('children.document-approvals.table', ['children' => $children, 'documents' => $documents])->render(),
                'accordion' => view('children.document-approvals.accordion', ['children' => $children, 'documents' => $documents])->render(),
                'count' => $count
            ]);
        }
        return view('children.document-approvals.index', compact('children', 'documents', 'count'));
    }

    public function saveDocumentsAndApprovals(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'document' => 'required',
        ], [
            'document.required' => 'Please choose document',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->has('document')) {
            $document = uploadFile($request->document, 'public/child-document');
        }
        ChildrenDocumentAndApproval::create([
            'children_id' => $request->children_id,
            'document' => $document
        ]);
        return redirect()->route('documents-approvals.get', $request->children_id);
    }

    public function deleteDocumentsAndApprovals($ids)
    {
        $ids = explode(',', $ids);
        if (ChildrenDocumentAndApproval::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Document has been successfully archived', 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
}
