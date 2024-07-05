<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Kindergarten;
use App\Models\MemberRole;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class StaffTableController extends Controller
{
    public function index(Request $request)
    {
        $professions = Profession::filter()->paginate(10);
        $roles = MemberRole::filter()->paginate(10);
        if ($request->ajax()) {
            switch ($request->type) {
                case 'profession':
                    return response()->json([
                        'table' => view('table.staff.profession.table', ['professions' => $professions])->render(),
                        'accordion' => view('table.staff.profession.accordion', ['professions' => $professions])->render()
                    ]);
                break;
                case 'role':
                    return response()->json([
                        'table' => view('table.staff.role.table', ['roles' => $roles])->render(),
                        'accordion' => view('table.staff.role.accordion', ['roles' => $roles])->render()
                    ]);
                break;
                default:
                    return response()->json([
                        'table' => view('table.staff.profession.table', ['professions' => $professions])->render(),
                        'accordion' => view('table.staff.profession.accordion', ['professions' => $professions])->render()
                    ]);
                break;
            }
        }
        return view('table.staff.index', compact('professions', 'roles'));
    }

    public function create(Request $request)
    {
        switch ($request->type) {
            case 'profession':
                return view('table.staff.profession.create');
            break;
            case 'role':
                return view('table.staff.role.create');
            break;
            default:
                return view('table.staff.profession.create');
            break;
        }
    }
    
    public function store(Request $request)
    {
        switch ($request->type) {
            case 'profession':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                Profession::create($request->all());
                return redirect()->route('staff-table.index', ['type' => 'profession']);
            break;
            case 'role':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                MemberRole::create($request->all());
                return redirect()->route('staff-table.index', ['type' => 'role']);
            break;
        }
    }

    public function edit(Request $request, $id)
    {
        switch ($request->type) {
            case 'profession':
                $profession = Profession::findOrFail($id);
                return view('table.staff.profession.edit', compact('profession'));
            break;
            case 'role':
                $role = MemberRole::findOrFail($id);
                return view('table.staff.role.edit', compact('role'));
            break;
            default:
                $profession = Profession::findOrFail($id);
                return view('table.staff.profession.edit', compact('profession'));
            break;
        }
    }

    public function update(Request $request, $id)
    {
        switch ($request->type) {
            case 'profession':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                Profession::where('id', $id)->update($request->except('_token', '_method', 'type'));
                return redirect()->route('staff-table.index', ['type' => 'profession']);
            break;
            case 'role':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                MemberRole::where('id', $id)->update($request->except('_token', '_method', 'type'));
                return redirect()->route('staff-table.index', ['type' => 'role']);
            break;
        }
    }

    public function destroy(Request $request, $ids)
    {
        switch ($request->type) {
            case 'profession':
                $ids = explode(',', $ids);
                Profession::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids]);
            break;
            case 'role':
                $ids = explode(',', $ids);
                MemberRole::whereIn('id', $ids)->delete();
                return response()->json(['status' => true, 'ids' => $ids]);
            break;
        }
    }

    public function staffTableTab(Request $request)
    {
        $professions = Profession::filter()->paginate(10);
        $roles = MemberRole::filter()->paginate(10);
        switch ($request->type) {
            case 'profession':
                return response()->json([
                    'status' => true,
                    'data' => view('table.staff.profession.index', ['professions' => $professions])->render()
                ]);
            break;
            case 'role':
                return response()->json([
                    'status' => true,
                    'data' => view('table.staff.role.index', ['roles' => $roles])->render()
                ]);
            break;
        }
    }
}