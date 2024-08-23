<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Cluster;
use App\Models\DocumentAndApproval;
use App\Models\FrameworkType;
use App\Models\InterventionType;
use App\Models\Kindergarten;
use App\Models\KindergartenType;
use App\Models\MemberRole;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class InterventionTableController extends Controller
{
    public function index(Request $request)
    {
        $documents = DocumentAndApproval::filter()->orderBy('id', 'DESC')->paginate(10);
        $interventionTypes = InterventionType::filter()->orderBy('id', 'DESC')->paginate(10);
        $documentCount = DocumentAndApproval::filter()->count();
        $interventionCount = InterventionType::filter()->count();
        if ($request->ajax()) {
            switch ($request->type) {
                case 'documents-and-approval':
                    return response()->json([
                        'table' => view('table.intervention.documents-and-approval.table', ['documents' => $documents])->render(),
                        'accordion' => view('table.intervention.documents-and-approval.accordion', ['documents' => $documents])->render(),
                        'count' => $documentCount
                    ]);
                break;
                case 'intervention-type':
                    return response()->json([
                        'table' => view('table.intervention.intervention-type.table', ['interventionTypes' => $interventionTypes])->render(),
                        'accordion' => view('table.intervention.intervention-type.accordion', ['interventionTypes' => $interventionTypes])->render(),
                        'count' => $interventionCount
                    ]);
                break;
                default:
                    return response()->json([
                        'table' => view('table.intervention.documents-and-approval.table', ['documents' => $documents])->render(),
                        'accordion' => view('table.intervention.documents-and-approval.accordion', ['documents' => $documents])->render(),
                        'count' => $documentCount
                    ]);
                break;
            }
        }
        return view('table.intervention.index', compact('documents', 'interventionTypes', 'documentCount', 'interventionCount'));
    }

    public function create(Request $request)
    {
        switch ($request->type) {
            case 'documents-and-approval':
                return view('table.intervention.documents-and-approval.create');
            break;
            case 'intervention-type':
                return view('table.intervention.intervention-type.create');
            break;
            default:
                return view('table.intervention.documents-and-approval.create');
            break;
        }
    }
    
    public function store(Request $request)
    {
        switch ($request->type) {
            case 'documents-and-approval':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                DocumentAndApproval::create($request->all());
                return redirect()->route('intervention.index', ['type' => 'documents-and-approval']);
            break;
            case 'intervention-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                InterventionType::create($request->all());
                return redirect()->route('intervention.index', ['type' => 'intervention-type']);
            break;
        }
    }

    public function edit(Request $request, $id)
    {
        switch ($request->type) {
            case 'documents-and-approval':
                $document = DocumentAndApproval::findOrFail($id);
                return view('table.intervention.documents-and-approval.edit', compact('document'));
            break;
            case 'intervention-type':
                $interventionType = InterventionType::findOrFail($id);
                return view('table.intervention.intervention-type.edit', compact('interventionType'));
            break;
            default:
                $document = DocumentAndApproval::findOrFail($id);
                return view('table.intervention.documents-and-approval.edit', compact('document'));
            break;
        }
    }

    public function update(Request $request, $id)
    {
        switch ($request->type) {
            case 'documents-and-approval':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                DocumentAndApproval::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('intervention.index', ['type' => 'documents-and-approval']);
            break;
            case 'intervention-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                InterventionType::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('intervention.index', ['type' => 'intervention-type']);
            break;
        }
    }

    public function destroy(Request $request, $ids)
    {
        switch ($request->type) {
            case 'documents-and-approval':
                $ids = explode(',', $ids);
                DocumentAndApproval::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Kindergarten Type has been successfully archived!']);
            break;
            case 'intervention-type':
                $ids = explode(',', $ids);
                InterventionType::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Framework Type has been successfully archived!']);
            break;
        }
    }

    public function interventionTableTab(Request $request)
    {
        $documents = DocumentAndApproval::filter()->orderBy('id', 'DESC')->paginate(10);
        $interventionTypes = InterventionType::filter()->orderBy('id', 'DESC')->paginate(10);
        $documentCount = DocumentAndApproval::filter()->count();
        $interventionCount = InterventionType::filter()->count();
        switch ($request->type) {
            case 'documents-and-approval':
                return response()->json([
                    'status' => true,
                    'data' => view('table.intervention.documents-and-approval.index', ['documents' => $documents, 'documentCount' => $documentCount])->render()
                ]);
            break;
            case 'intervention-type':
                return response()->json([
                    'status' => true,
                    'data' => view('table.intervention.intervention-type.index', ['interventionTypes' => $interventionTypes, 'interventionCount' => $interventionCount])->render()
                ]);
            break;
        }
    }
}