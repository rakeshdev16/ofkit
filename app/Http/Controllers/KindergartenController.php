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
        $kindergartens = Kindergarten::filter()->orderBy('id', 'DESC')->paginate(10);
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
            'cluster_id' => 'required',
            'symbol' => 'nullable|numeric',
            'telephone' => [
                'required',
                'regex:/^(?=.*\d)(?=(?:.{8,14}|.{0,7}-|.{0,3}-{0,3}-{4}|.{3}-{4}-{4})$)\d{1,3}-?\d{1,3}-?\d{4}$/'
            ],
        ],[
            'name.required' => __('kindergarten.requiredName'),
            'cluster_id.required' => __('kindergarten.requiredCluster'),
            'symbol.numeric' => 'Please enter numbers only',
            'telephone.required' => 'Please enter telephone number',
            'telephone.regex' => 'The phone number format is invalid. It should be a sequence of digits or digits separated by hyphens like (123-456-7890)',
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
            'symbol' => 'nullable|numeric',
            'telephone' => [
                'required',
                'regex:/^(?=.*\d)(?=(?:.{8,14}|.{0,7}-|.{0,3}-{0,3}-{4}|.{3}-{4}-{4})$)\d{1,3}-?\d{1,3}-?\d{4}$/'
            ],
        ],[
            'name.required' => __('kindergarten.requiredName'),
            'symbol.numeric' => 'Please enter numbers only',
            'telephone.required' => 'Please enter telephone number',
            'telephone.regex' => 'The phone number format is invalid. It should be a sequence of digits or digits separated by hyphens like (123-456-7890)',
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
