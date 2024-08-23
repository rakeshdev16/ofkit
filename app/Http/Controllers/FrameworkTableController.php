<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Cluster;
use App\Models\FrameworkType;
use App\Models\Kindergarten;
use App\Models\KindergartenType;
use App\Models\MemberRole;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class FrameworkTableController extends Controller
{
    public function index(Request $request)
    {
        $kindergartenTypes = KindergartenType::filter()->orderBy('id', 'DESC')->paginate(10);
        $frameworkTypes = FrameworkType::filter()->orderBy('id', 'DESC')->paginate(10);
        $kindergartenTypeCount = KindergartenType::filter()->count();
        $frameworkTypeCount = FrameworkType::filter()->count();
        if ($request->ajax()) {
            switch ($request->type) {
                case 'kindergarten-type':
                    return response()->json([
                        'table' => view('table.framework.kindergarten-type.table', ['kindergartenTypes' => $kindergartenTypes])->render(),
                        'accordion' => view('table.framework.kindergarten-type.accordion', ['kindergartenTypes' => $kindergartenTypes])->render(),
                        'count' => $kindergartenTypeCount
                    ]);
                break;
                case 'framework-type':
                    return response()->json([
                        'table' => view('table.framework.framework-type.table', ['frameworkTypes' => $frameworkTypes])->render(),
                        'accordion' => view('table.framework.framework-type.accordion', ['frameworkTypes' => $frameworkTypes])->render(),
                        'count' => $frameworkTypeCount
                    ]);
                break;
                default:
                    return response()->json([
                        'table' => view('table.framework.kindergarten-type.table', ['kindergartenTypes' => $kindergartenTypes])->render(),
                        'accordion' => view('table.framework.kindergarten-type.accordion', ['kindergartenTypes' => $kindergartenTypes])->render(),
                        'count' => $kindergartenTypeCount
                    ]);
                break;
            }
        }
        return view('table.framework.index', compact('kindergartenTypes', 'frameworkTypes', 'kindergartenTypeCount', 'frameworkTypeCount'));
    }

    public function create(Request $request)
    {
        switch ($request->type) {
            case 'kindergarten-type':
                return view('table.framework.kindergarten-type.create');
            break;
            case 'framework-type':
                return view('table.framework.framework-type.create');
            break;
            default:
                return view('table.framework.kindergarten-type.create');
            break;
        }
    }
    
    public function store(Request $request)
    {
        switch ($request->type) {
            case 'kindergarten-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                KindergartenType::create($request->all());
                return redirect()->route('framework-table.index', ['type' => 'kindergarten-type']);
            break;
            case 'framework-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                FrameworkType::create($request->all());
                return redirect()->route('framework-table.index', ['type' => 'framework-type']);
            break;
        }
    }

    public function edit(Request $request, $id)
    {
        switch ($request->type) {
            case 'kindergarten-type':
                $kindergartenType = KindergartenType::findOrFail($id);
                return view('table.framework.kindergarten-type.edit', compact('kindergartenType'));
            break;
            case 'framework-type':
                $frameworkType = FrameworkType::findOrFail($id);
                return view('table.framework.framework-type.edit', compact('frameworkType'));
            break;
            default:
                $kindergartenType = KindergartenType::findOrFail($id);
                return view('table.framework.kindergarten-type.edit', compact('kindergartenType'));
            break;
        }
    }

    public function update(Request $request, $id)
    {
        switch ($request->type) {
            case 'kindergarten-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                KindergartenType::where('id', $id)->update($request->except('_token', '_method', 'type'));
                return redirect()->route('framework-table.index', ['type' => 'kindergarten-type']);
            break;
            case 'framework-type':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                FrameworkType::where('id', $id)->update($request->except('_token', '_method', 'type'));
                return redirect()->route('framework-table.index', ['type' => 'framework-type']);
            break;
        }
    }

    public function destroy(Request $request, $ids)
    {
        switch ($request->type) {
            case 'kindergarten-type':
                $ids = explode(',', $ids);
                KindergartenType::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Kindergarten Type has been successfully archived!']);
            break;
            case 'framework-type':
                $ids = explode(',', $ids);
                FrameworkType::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids, 'message' => 'Framework Type has been successfully archived!']);
            break;
        }
    }

    public function frameWorkTableTab(Request $request)
    {
        $kindergartenTypes = KindergartenType::filter()->paginate(10);
        $frameworkTypes = FrameworkType::filter()->paginate(10);
        $kindergartenTypeCount = KindergartenType::filter()->count();
        $frameworkTypeCount = FrameworkType::filter()->count();
        switch ($request->type) {
            case 'kindergarten-type':
                return response()->json([
                    'status' => true,
                    'data' => view('table.framework.kindergarten-type.index', ['kindergartenTypes' => $kindergartenTypes, 'count' => $kindergartenTypeCount])->render()
                ]);
            break;
            case 'framework-type':
                return response()->json([
                    'status' => true,
                    'data' => view('table.framework.framework-type.index', ['frameworkTypes' => $frameworkTypes, 'count' => $frameworkTypeCount])->render()
                ]);
            break;
        }
    }
}