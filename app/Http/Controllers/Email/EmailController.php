<?php

namespace App\Http\Controllers\Email;

use App\Services\EmailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    //
    public function createEmailTpl(Request $request, EmailService $service)
    {
        $type = $request->input('type');
        $path = $request->input('path');


        $list = $service->get_all_files($path);
        $service->type = $type;
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1X2lkIjoiNDY4IiwidV9taXgiOiJjcHRhMjE1NSIsInVfbmFtZSI6InNwZW5jZXIiLCJ1X3RpbWUiOjE1MjMxNTE1NzZ9.T_pQRk2kWhPk7bfxL_GXx6ZKr2E0CYMXR6SotQpB9nw';
        $service->sendTemplate($list,$token,$type);
        return response()->json(['msg'=>'ok']);
    }

    //创建单个模板文件
    public function createSingleTpl(Request $request, EmailService $service)
    {
        $type = $request->input('type');
        $path = $request->input('path');

        //文件
        $list = explode(',',$path);
        foreach($list as $file){
            if( !file_exists($file) ){
                return response()->json(['msg'=>'file_error']);
            }
        }

        $service->type = $type;
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1X2lkIjoiNDY4IiwidV9taXgiOiJjcHRhMjE1NSIsInVfbmFtZSI6InNwZW5jZXIiLCJ1X3RpbWUiOjE1MjMxNTE1NzZ9.T_pQRk2kWhPk7bfxL_GXx6ZKr2E0CYMXR6SotQpB9nw';
        $service->sendTemplate($list,$token,$type);
        return response()->json(['msg'=>'ok']);
    }

    //图片替换google地址
    public function replaceGooglePath(Request $request, EmailService $service)
    {
        $path = $request->input('path');//文件

        $list = $service->get_all_files($path);

        foreach($list as $file){
            if( !file_exists($file) ){
                return response()->json(['msg'=>'file_error']);
            }
        }

        $rs = $service->updateHtml($list);
        dd(1);
    }

    public function replaceUrl(Request $request, EmailService $service)
    {
        $path = $request->input('path');//文件

        $list = $service->get_all_files($path);

        foreach($list as $file){
            if( !file_exists($file) ){
                return response()->json(['msg'=>'file_error']);
            }
        }
        foreach($list as $item){
            $rs = $service->replaceUrl(file_get_contents($item),$item);
        }

        dd(1);
    }
}
