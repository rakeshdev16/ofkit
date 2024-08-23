<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Cluster;
use App\Models\Diagnosis;
use App\Models\FrameworkType;
use App\Models\Functionality;
use App\Models\Hmo;
use App\Models\Kindergarten;
use App\Models\KindergartenType;
use App\Models\MemberRole;
use App\Models\ParentsStatus;
use App\Models\Profession;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class ChildrenTableController extends Controller
{
    public function index(Request $request)
    {
        $parentsStatus = ParentsStatus::filter()->orderBy('id', 'DESC')->paginate(10);
        $hmos = Hmo::filter()->orderBy('id', 'DESC')->paginate(10);
        $diagnosises = Diagnosis::filter()->orderBy('id', 'DESC')->paginate(10);
        $functionalities = Functionality::filter()->orderBy('id', 'DESC')->paginate(10);
        $statuses = Status::filter()->orderBy('id', 'DESC')->paginate(10);
        $parentsStatusCount = ParentsStatus::filter()->count();
        $hmoCount = Hmo::filter()->count();
        $diagnosisCount = Diagnosis::filter()->count();
        $functionalityCount = Functionality::filter()->count();
        $statusCount = Status::filter()->count();
        if ($request->ajax()) {
            switch ($request->type) {
                case 'parents-status':
                    return response()->json([
                        'table' => view('table.children.parents-status.table', ['parentsStatus' => $parentsStatus])->render(),
                        'accordion' => view('table.children.parents-status.accordion', ['parentsStatus' => $parentsStatus])->render(),
                        'count' => $parentsStatusCount
                    ]);
                break;
                case 'hmo':
                    return response()->json([
                        'table' => view('table.children.hmo.table', ['hmos' => $hmos])->render(),
                        'accordion' => view('table.children.hmo.accordion', ['hmos' => $hmos])->render(),
                        'count' => $hmoCount
                    ]);
                break;
                case 'diagnosis':
                    return response()->json([
                        'table' => view('table.children.diagnosis.table', ['diagnosises' => $diagnosises])->render(),
                        'accordion' => view('table.children.diagnosis.accordion', ['diagnosises' => $diagnosises])->render(),
                        'count' => $diagnosisCount
                    ]);
                break;
                case 'functionality':
                    return response()->json([
                        'table' => view('table.children.functionality.table', ['functionalities' => $functionalities])->render(),
                        'accordion' => view('table.children.functionality.accordion', ['functionalities' => $functionalities])->render(),
                        'count' => $functionalityCount
                    ]);
                break;
                case 'status':
                    return response()->json([
                        'table' => view('table.children.status.table', ['statuses' => $statuses])->render(),
                        'accordion' => view('table.children.status.accordion', ['statuses' => $statuses])->render(),
                        'count' => $statusCount
                    ]);
                break;
                default:
                    return response()->json([
                        'table' => view('table.children.parents-status.table', ['parentsStatus' => $parentsStatus])->render(),
                        'accordion' => view('table.children.parents-status.accordion', ['parentsStatus' => $parentsStatus])->render(),
                        'count' => $parentsStatusCount
                    ]);
                break;
            }
        }
        return view('table.children.index', compact('parentsStatus', 'hmos', 'diagnosises', 'functionalities', 'statuses', 'parentsStatusCount', 'hmoCount', 'diagnosisCount', 'functionalityCount', 'statusCount'));
    }

    public function create(Request $request)
    {
        switch ($request->type) {
            case 'parents-status':
                return view('table.children.parents-status.create');
            break;
            case 'hmo':
                return view('table.children.hmo.create');
            break;
            case 'diagnosis':
                return view('table.children.diagnosis.create');
            break;
            case 'functionality':
                return view('table.children.functionality.create');
            break;
            case 'status':
                return view('table.children.status.create');
            break;
            default:
                return view('table.children.parents-status.create');
            break;
        }
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ],[
            'name.required' => 'Please enter name',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        switch ($request->type) {
            case 'parents-status':
                ParentsStatus::create($request->all());
                return redirect()->route('children-table.index', ['type' => 'parents-status']);
            break;
            case 'hmo':
                Hmo::create($request->all());
                return redirect()->route('children-table.index', ['type' => 'hmo']);
            break;
            case 'diagnosis':
                Diagnosis::create($request->all());
                return redirect()->route('children-table.index', ['type' => 'diagnosis']);
            break;
            case 'functionality':
                Functionality::create($request->all());
                return redirect()->route('children-table.index', ['type' => 'functionality']);
            break;
            case 'status':
                Status::create($request->all());
                return redirect()->route('children-table.index', ['type' => 'status']);
            break;
        }
    }

    public function edit(Request $request, $id)
    {
        switch ($request->type) {
            case 'parents-status':
                $parentsStatus = ParentsStatus::findOrFail($id);
                return view('table.children.parents-status.edit', compact('parentsStatus'));
            break;
            case 'hmo':
                $hmos = Hmo::findOrFail($id);
                return view('table.children.hmo.edit', compact('hmos'));
            break;
            case 'diagnosis':
                $diagnosis = Diagnosis::findOrFail($id);
                return view('table.children.diagnosis.edit', compact('diagnosis'));
            break;
            case 'functionality':
                $functionality = Functionality::findOrFail($id);
                return view('table.children.functionality.edit', compact('functionality'));
            break;
            case 'status':
                $status = Status::findOrFail($id);
                return view('table.children.status.edit', compact('status'));
            break;
            default:
                $parentsStatus = ParentsStatus::findOrFail($id);
                return view('table.children.parents-status.edit', compact('parentsStatus'));
            break;
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ],[
            'name.required' => 'Please enter name',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        switch ($request->type) {
            case 'parents-status':
                ParentsStatus::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('children-table.index', ['type' => 'parents-status']);
            break;
            case 'hmo':
                Hmo::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('children-table.index', ['type' => 'hmo']);
            break;
            case 'diagnosis':
                Diagnosis::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('children-table.index', ['type' => 'diagnosis']);
            break;
            case 'functionality':
                Functionality::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('children-table.index', ['type' => 'functionality']);
            break;
            case 'status':
                Status::where('id', $id)->update($request->except('_token', '_method', 'type', 'form_changed'));
                return redirect()->route('children-table.index', ['type' => 'status']);
            break;
        }
    }

    public function destroy(Request $request, $ids)
    {
        switch ($request->type) {
            case 'parents-status':
                $ids = explode(',', $ids);
                ParentsStatus::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Kindergarten Type has been successfully archived!']);
            break;
            case 'hmo':
                $ids = explode(',', $ids);
                Hmo::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Framework Type has been successfully archived!']);
            break;
        }
    }

    public function childrenTableTab(Request $request)
    {
        $parentsStatus = ParentsStatus::filter()->paginate(10);
        $hmos = Hmo::filter()->paginate(10);
        $diagnosises = Diagnosis::filter()->paginate(10);
        $functionalities = Functionality::filter()->paginate(10);
        $statuses = Status::filter()->paginate(10);
        $parentsStatusCount = ParentsStatus::filter()->count();
        $hmoCount = Hmo::filter()->count();
        $diagnosisCount = Diagnosis::filter()->count();
        $functionalityCount = Functionality::filter()->count();
        $statusCount = Status::filter()->count();
        switch ($request->type) {
            case 'parents-status':
                return response()->json([
                    'status' => true,
                    'data' => view('table.children.parents-status.index', ['parentsStatus' => $parentsStatus, 'parentsStatusCount' => $parentsStatusCount])->render()
                ]);
            break;
            case 'hmo':
                return response()->json([
                    'status' => true,
                    'data' => view('table.children.hmo.index', ['hmos' => $hmos, 'hmoCount' => $hmoCount])->render()
                ]);
            break;
            case 'diagnosis':
                return response()->json([
                    'status' => true,
                    'data' => view('table.children.diagnosis.index', ['diagnosises' => $diagnosises, 'diagnosisCount' => $diagnosisCount])->render()
                ]);
            break;
            case 'functionality':
                return response()->json([
                    'status' => true,
                    'data' => view('table.children.functionality.index', ['functionalities' => $functionalities, 'functionalityCount' => $functionalityCount])->render()
                ]);
            break;
            case 'status':
                return response()->json([
                    'status' => true,
                    'data' => view('table.children.status.index', ['statuses' => $statuses, 'statusCount' => $statusCount])->render()
                ]);
            break;
        }
    }
}