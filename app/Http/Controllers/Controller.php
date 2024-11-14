<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function activeInactive(Request $request)
    {
        $model = app("App\\Models\\" . $request->model);
        $ids = explode(',', $request->ids);
        $status = $request->status == 'active' ? 'inactive' : 'active';
        if ($model->whereIn('id', $ids)->update(['status' => $status])) {
            $count = $model->where('status',  $request->status)->count();
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
