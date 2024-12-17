<?php

namespace App\Http\Controllers;

use App\Models\ChildrenDocumentation;
use App\Models\GroupChildren;
use App\Models\StaffMeetingChildren;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function activeInactive(Request $request)
    {
        $model = app("App\\Models\\" . $request->model);
        $ids = explode(',', $request->ids);
        $status = $request->status == 'active' ? 'inactive' : 'active';
        $recordStatus = $request->status == 'active' ? ['active'] : ['active','inactive'];
        if ($model->whereIn('id', $ids)->update(['status' => $status])) {
            if($request->model == 'User'){
                $count = User::whereIn('status',  $recordStatus)->whereNot('id', Auth::id())->count();
            }else{
                if($request->children_id){
                    if($request->model == 'ChildrenDocumentAndApproval'){
                        $count = $model->whereIn('status',  $recordStatus)->where('children_id', $request->children_id)->count();
                    }else{
                        $childDocIds = ChildrenDocumentation::where('children_id', $request->children_id)->pluck('id')->toArray();
                        $staffMeetingDocIds = StaffMeetingChildren::where('children_id', $request->children_id)->pluck('children_doc_id')->toArray();
                        $groupDocIds = GroupChildren::where('children_id', $request->children_id)->pluck('children_documentation_id')->toArray();
                        $docIds = array_merge(array_unique($childDocIds), array_unique($staffMeetingDocIds), array_unique($groupDocIds));
                        $count = $model->whereIn('status',  $recordStatus)->whereIn('id', $docIds)->count();
                    }
                }else{
                    $count = $model->whereIn('status',  $recordStatus)->count();
                }
            }
            if($status == 'active'){
                $message = __('comon.activeMsg');
            }else{
                $message = __('comon.inactiveMsg');
            }
            return response()->json(['status' => true, 'ids' => $ids, 'count' => $count, 'message' => $message]);
        }

        return response()->json(['status' => false]);
    }

}
