<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Auth;

class ClusterController extends Controller
{
    public function index(Request $request)
    {
        $clusters = Cluster::filter()->orderBy('id', 'DESC')->paginate(50);
        $count = Cluster::filter()->count();
        if ($request->ajax()) {
            return response()->json([
                'table' => view('cluster.table', ['clusters' => $clusters])->render(),
                'accordion' => view('cluster.accordion', ['clusters' => $clusters])->render(),
                'count' => $count
            ]);
        }
        return view('cluster.index', compact('clusters', 'count'));
    }

    public function create()
    {
        $managers = User::role('manager')->select('id as key', 'name as value')->where('status', 'active')->get();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->where('status', 'active')->get()->toArray();
        return view('cluster.create', compact('managers', 'kindergartens'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cluster' => ['required', 'unique:clusters'],
        ],[
            'cluster.required' => __('cluster.requiredCluster'),
            'cluster.unique' => __('cluster.uniqueCluster'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $cluster = Cluster::create($request->all());
        return redirect()->route('cluster.index');
    }

    public function edit($id)
    {
        $cluster = Cluster::findOrFail($id);
        $managers = User::role('manager')->select('id as key', 'name as value')->where('status', 'active')->get();
        $kindergartens = Kindergarten::select('id as key', 'name as value')->where('status', 'active')->get()->toArray();
        return view('cluster.edit', compact('cluster', 'managers', 'kindergartens'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cluster' => ['required', Rule::unique('clusters')->ignore($id)],
        ],[
            'cluster.required' => __('cluster.requiredCluster'),
            'cluster.unique' => __('cluster.uniqueCluster'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $cluster = Cluster::findOrFail($id);
        $request['status'] = $request->status ?? 'inactive';
        $cluster->update($request->except('_token', '_method', 'kindergarten_id'));
        return redirect()->route('cluster.index');
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Cluster::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => 'Cluster has been successfully archived', 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
}
