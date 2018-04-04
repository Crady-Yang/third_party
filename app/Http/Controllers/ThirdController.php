<?php

namespace App\Http\Controllers;

use App\Services\ThirdPartyService;
use Illuminate\Http\Request;

class ThirdController extends Controller
{
    /**
     * 第三方登录
     * @param Request $request
     * @param ThirdPartyService $partyService
     * @return mixed
     */
    public function thirdLogin(Request $request, ThirdPartyService $partyService)
    {
        $tw = config('services.twitter');
        dd($tw);
        $type = $request->input('type','');
        $path = $request->input('path');

        return $partyService->thirdOauthRedirect($type,$path);
    }

    /**
     * twitter回调
     * @param Request $request
     * @param ThirdPartyService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function twitterCallback(Request $request, ThirdPartyService $service)
    {
        $referer = array_get($request->session()->get('_previous'),'url');

        $return = $service->thirdCallback('twitter',['state'=> $referer]);
        if(!$return){
            $url_arr = $service->returnFalse($referer);
            return redirect($url_arr['url'].'?'.http_build_query($url_arr['params']));
        }
        $url = $service->getRedirectCallbackUri();
        $return = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($return));
    }

    /**
     * facebook回调
     * @param Request $request
     * @param ThirdPartyService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function facebookCallback(Request $request, ThirdPartyService $service)
    {
        $state = $request->input('state');
        $return = $service->thirdCallback('facebook',['state'=>$state]);

        if(!$return){
            $url_arr = $service->returnFalse($state);
            return redirect($url_arr['url'].'?'.http_build_query($url_arr['params']));
        }

        $url = $service->getRedirectCallbackUri();
        $return = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($return));
    }

    /**
     * google回调
     * @param Request $request
     * @param ThirdPartyService $service
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function googleCallback(Request $request, ThirdPartyService $service)
    {
        $state = $request->input('state');
        $return = $service->thirdCallback('google',['state'=>$request->input('state')]);

        if(!$return){
            $url_arr = $service->returnFalse($state);
            return redirect($url_arr['url'].'?'.http_build_query($url_arr['params']));
        }

        $url = $service->getRedirectCallbackUri();
        $return = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($return));
    }
}
