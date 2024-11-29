<?php

namespace App\Http\Controllers;

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
                $count = $model->whereIn('status',  $recordStatus)->count();
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
