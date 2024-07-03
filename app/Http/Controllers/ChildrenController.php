<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\Cluster;
use App\Models\Kindergarten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class ChildrenController extends Controller
{
    public function index(Request $request)
    {
        $childrens = Children::where('user_id', Auth::id());
        if ($request->ajax()) {
            if ($request->sort && $request->sorting) {
                $childrens->orderBy($request->sort, $request->sorting);
            }
            if ($request->search) {
                $childrens->where('name', 'like', '%'.$request->search.'%');
            }
            $childrens = $childrens->paginate(10);
            return response()->json([
                'table' => view('children.table', ['childrens' => $childrens])->render(),
                'accordion' => view('children.accordion', ['childrens' => $childrens])->render()
            ]);
        }
        $childrens = $childrens->paginate(10);
        return view('children.index', compact('childrens'));
    }
    
    public function create()
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        return view('children.create', compact('kindergartens'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kindergarten_id' => 'required',
            'name' => 'required',
            'family_name' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'access_records' => 'required',
        ],[
            'kindergarten_id.required' => __('children.requiredKindergartenId'),
            'name.required' => __('children.requiredName'),
            'family_name.required' => __('children.requiredFamilyName'),
            'dob.required' => __('children.requiredDOB'),
            'address.required' => __('children.requiredAddress'),
            'access_records.required' => __('children.requiredAccessRecords'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['user_id'] = Auth::id();
        $request['identification'] = Str::uuid();
        Children::create($request->all());
        return redirect()->route('children.index');
    }

    public function edit($id)
    {
        $kindergartens = Kindergarten::select('id as key', 'name as value')->get()->toArray();
        $children = Children::findOrFail($id);
        return view('children.edit', compact('children', 'kindergartens'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kindergarten_id' => 'required',
            'name' => 'required',
            'family_name' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'access_records' => 'required',
        ],[
            'kindergarten_id.required' => __('children.requiredKindergartenId'),
            'name.required' => __('children.requiredName'),
            'family_name.required' => __('children.requiredFamilyName'),
            'dob.required' => __('children.requiredDOB'),
            'address.required' => __('children.requiredAddress'),
            'access_records.required' => __('children.requiredAccessRecords'),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $request['user_id'] = Auth::id();
        Children::where('id', $id)->update($request->except('_token', '_method'));
        return redirect()->route('children.index');
    }

    public function destroy($ids)
    {
        $ids = explode(',', $ids);
        if (Children::whereIn('id', $ids)->delete()) {
            return response()->json(['status' => true, 'message' => __('children.deleteStaffMsg'), 'ids' => $ids]);
        }
        return response()->json(['status' => false, 'ids' => $ids]);
    }
}
