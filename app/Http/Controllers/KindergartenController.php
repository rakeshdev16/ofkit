<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\FrameworkType;
use App\Models\Kindergarten;
use App\Models\KindergartenType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class KindergartenController extends Controller
{
    public function index(Request $request)
    {
        $kindergartens = Kindergarten::filter()->paginate(10);
        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            // $kindergartens->where('user_id', Auth::id());
        }
        if ($request->ajax()) {
            return response()->json([
                'table' => view('kindergarten.table', ['kindergartens' => $kindergartens])->render(),
                'accordion' => view('kindergarten.accordion', ['kindergartens' => $kindergartens])->render()
            ]);
        }
        return view('kindergarten.index', compact('kindergartens'));
    }
    
    public function create()
    {
        $clusters = Cluster::select('id as key', 'cluster as value')->get();
        $frameworks = FrameworkType::select('id as key', 'name as value')->get();
        $types = KindergartenType::select('id as key', 'name as value')->get();
        $managers = User::role('manager')->select('id as key', 'name as value')->get();
        return view('kindergarten.create', compact('clusters', 'managers', 'frameworks', 'types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'framework_type_id' => 'required',
            'kindergarten_type_id' => 'required',
            'address' => 'required',
            'telephone' => 'required|digits_between:8,14',
        ],[
            'name.required' => __('kindergarten.requiredName'),
            'symbol.required' => __('kindergarten.requiredSymbol'),
            'framework_type_id.required' => __('kindergarten.requiredFramework'),
            'kindergarten_type_id.required' => __('kindergarten.requiredType'),
            'address.required' => __('kindergarten.requiredAddress'),
            'telephone.required' => __('kindergarten.requiredTelephone'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $kindergarten = Kindergarten::create($request->all());
        if (isset($request->manager_id)) {
            $kindergarten->kindergartenUser()->create(['user_id' => $request->manager_id]);
        }
        return redirect()->route('kindergarten.index');
    }

    public function edit($id)
    {
        $kindergarten = Kindergarten::findOrFail($id);
        $clusters = Cluster::select('id as key', 'cluster as value')->get()->toArray();
        $managers = User::role('manager')->select('id as key', 'name as value')->get();
        $frameworks = FrameworkType::select('id as key', 'name as value')->get();
        $types = KindergartenType::select('id as key', 'name as value')->get();
        return view('kindergarten.edit', compact('kindergarten', 'clusters', 'managers', 'frameworks', 'types'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'symbol' => 'required',
            'framework_type_id' => 'required',
            'kindergarten_type_id' => 'required',
            'address' => 'required',
            'telephone' => 'required|digits_between:8,14',
        ],[
            'name.required' => __('kindergarten.requiredName'),
            'symbol.required' => __('kindergarten.requiredSymbol'),
            'framework_type_id.required' => __('kindergarten.requiredFramework'),
            'kindergarten_type_id.required' => __('kindergarten.requiredType'),
            'address.required' => __('kindergarten.requiredAddress'),
            'telephone.required' => __('kindergarten.requiredTelephone'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $kindergarten = Kindergarten::findOrFail($id);
        $kindergarten->update($request->except('_token', '_method', 'manager_id'));
        $kindergarten->kindergartenUser()->delete();
        if (isset($request->manager_id)) {
            $kindergarten->kindergartenUser()->create(['user_id' => $request->manager_id]);
        }
        return redirect()->route('kindergarten.index');
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Kindergarten::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('validation.archived', ['attribute' => 'Kindergarten']), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
    
    public function getClusterManager(Request $request)
    {
        $cluster = Cluster::where('id', $request->cluster_id)->first();
        if ($cluster->manager) {
            return response()->json(['status' => true, 'data' => $cluster->manager]);
        }
        return response()->json(['status' => false]);
    }
}
