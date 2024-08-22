<?php

namespace App\Http\Controllers;

use App\Models\Association;
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
        $professions = Profession::filter()->orderBy('id', 'DESC')->get();
        $roles = MemberRole::filter()->orderBy('id', 'DESC')->get();
        $associations = Association::filter()->orderBy('id', 'DESC')->get();
        $professionCount = Profession::count();
        $roleCount = MemberRole::count();
        $associationCount = Association::count();
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
                case 'association':
                    return response()->json([
                        'table' => view('table.staff.association.table', ['associations' => $associations])->render(),
                        'accordion' => view('table.staff.association.accordion', ['associations' => $associations])->render()
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
        return view('table.staff.index', compact('professions', 'roles', 'associations', 'professionCount', 'roleCount', 'associationCount'));
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
            case 'association':
                return view('table.staff.association.create');
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
            case 'association':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                Association::create($request->all());
                return redirect()->route('staff-table.index', ['type' => 'association']);
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
            case 'association':
                $association = Association::findOrFail($id);
                return view('table.staff.association.edit', compact('association'));
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
            case 'association':
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                ],[
                    'name.required' => 'Please enter name',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                Association::where('id', $id)->update($request->except('_token', '_method', 'type'));
                return redirect()->route('staff-table.index', ['type' => 'association']);
            break;
        }
    }

    public function destroy(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        switch ($request->type) {
            case 'profession':
                Profession::whereIn('id', $ids)->delete();
            break;
            case 'role':
                MemberRole::whereIn('id', $ids)->delete();
            break;
            case 'association':
                Association::whereIn('id', $ids)->delete();
            break;
        }
        return response()->json(['status' => true, 'ids' => $ids]);
    }

    public function staffTableTab(Request $request)
    {
        $professions = Profession::filter()->get();
        $roles = MemberRole::filter()->get();
        $associations = Association::filter()->get();
        $professionCount = Profession::count();
        $roleCount = MemberRole::count();
        $associationCount = Association::count();
        switch ($request->type) {
            case 'profession':
                return response()->json([
                    'status' => true,
                    'data' => view('table.staff.profession.index', ['professions' => $professions, 'professionCount' => $professionCount])->render()
                ]);
            break;
            case 'role':
                return response()->json([
                    'status' => true,
                    'data' => view('table.staff.role.index', ['roles' => $roles, 'roleCount' => $roleCount])->render()
                ]);
            break;
            case 'association':
                return response()->json([
                    'status' => true,
                    'data' => view('table.staff.association.index', ['associations' => $associations, 'associationCount' => $associationCount])->render()
                ]);
            break;
        }
    }
}