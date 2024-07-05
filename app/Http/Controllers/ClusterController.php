<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class ClusterController extends Controller
{
    public function index(Request $request)
    {
        $clusters = Cluster::query();
        if ($request->ajax()) {
            if ($request->sort && $request->sorting) {
                $clusters->orderBy($request->sort, $request->sorting);
            }
            if ($request->search) {
                $clusters->where('cluster', 'like', '%'.$request->search.'%');
            }
            $clusters = $clusters->paginate(10);
            return response()->json([
                'table' => view('cluster.table', ['clusters' => $clusters])->render(),
                'accordion' => view('cluster.accordion', ['clusters' => $clusters])->render()
            ]);
        }
        $clusters = $clusters->paginate(10);
        return view('cluster.index', compact('clusters'));
    }
    
    public function create()
    {
        $managers = User::role('manager')->select('id as key', 'name as value')->get();
        return view('cluster.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cluster' => 'required',
        ],[
            'cluster.required' => __('cluster.requiredCluster'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Cluster::create($request->all());
        return redirect()->route('cluster.index');
    }

    public function edit($id)
    {
        $member = Cluster::findOrFail($id);
        $managers = User::role('manager')->select('id as key', 'name as value')->get();
        return view('cluster.edit', compact('member', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cluster' => 'required',
        ],[
            'cluster.required' => __('cluster.requiredCluster'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Cluster::where('id', $id)->update($request->except('_token', '_method'));
        return redirect()->route('cluster.index');
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Cluster::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('cluster.deleteStaffMsg'), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
}
