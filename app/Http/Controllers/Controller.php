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
            return response()->json(['status' => true, 'ids' => $ids, 'count' => $count]);
        }

        return response()->json(['status' => false]);
    }

}
