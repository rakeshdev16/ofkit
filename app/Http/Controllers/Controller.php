<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function inactiveRecords(Request $request)
    {
        $model = app("App\\Models\\" . $request->modal);

        if ($model->whereIn('id', $request->ids)->update(['status' => 'inactive'])) {
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

}
