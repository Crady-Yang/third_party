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
        $type = $request->input('type');
        $path = $request->input('path');
        \Log::info('twitter-request-start',[date('Y-m-d H:i:s')]);
        // url白名单
        // type 验证

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
        $state = array_get($request->session()->get('_previous'),'url');
        $return = $service->thirdCallback('twitter',['state'=> $state]);

        $url = $service->getRedirectCallbackUri();

        if(!$return){
            return redirect($url.'?'.http_build_query($service->returnFalse()));
        }
        $returnParams = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($returnParams));
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

        $url = $service->getRedirectCallbackUri();

        if(!$return){
            return redirect($url.'?'.http_build_query($service->returnFalse()));
        }

        $returnParams = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($returnParams));
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
        $return = $service->thirdCallback('google',['state' => $state]);

        $url = $service->getRedirectCallbackUri();

        if(!$return){
            return redirect($url.'?'.http_build_query($service->returnFalse()));
        }

        $returnParams = $service->getReturnParams();
        return redirect($url.'?'.http_build_query($returnParams));
    }
}
