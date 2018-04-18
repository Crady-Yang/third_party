<?php

namespace App\Http\Controllers\Vue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function vueTest(Request $request)
    {
        $type = $request->input('type');
        $view = 'vue.'.$type;
        return view($view);
    }
}
