<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 2018/3/20
 * Time: 16:53
 */

namespace App\Services;

use App\Models\ApplicantThird;
use App\Classes\ThirdOauth\Facades\Socialite;


class ThirdPartyService
{
    /**
     * 第三方类型
     */
    const OAUTH_TYPE = [
        'google',
        'facebook',
        'twitter'
    ];

    //第三方请求的源地址
    protected $redirect_callback_uri = null;

    //回源地址需要的参数
    protected $return_params = [];

    /**
     * 第三方类型
     */
    protected $oauth_type = null;

    /**
     * 第三方用户信息
     */
    public $providerUser = null;

    /**
     * 验证第三方ID是否存在
     * @param $id
     * @return mixed
     */
    public function isExistProviderId($id)
    {
        $third = ApplicantThird::where('provider_id',$id)->first();
        if(!$third){
            // 不存在
            return false;
        }
        return $third->id;
    }

    /**
     * 设置第三方类型
     * @param $type
     * @return bool
     */
    protected function comfirmOauthType($type)
    {
        if(!in_array($type,self::OAUTH_TYPE)){
            return false;
        }else{
            $this->oauth_type = $type;
            return true;
        }
    }

    /**
     * 第三方授权回调获取用户信息
     * @return bool
     */
    public function thirdOauthUser($type)
    {
        if(!$this->comfirmOauthType($type)){
            return false;
        }else{
            try{
                $this->providerUser = Socialite::driver($this->oauth_type)->user();
                return true;
            }catch(\Exception $e){
                return false;
            }
        }
    }

    /**
     * 获取第三方邮箱
     * @return mixed
     */
    public function getUserEmail()
    {
        $email = $this->providerUser->getEmail();
        return is_null($email)? '':$email;
    }

    /**
     * 获取第三方id
     * @return mixed
     */
    public function getUserId()
    {
        $id = $this->providerUser->getId();
        return is_null($id)? '':$id;
    }

    /**
     * 添加第三方记录
     * @param string $type
     * @param string $email
     * @param $id
     * @return mixed
     */
    public function addThirdParty($type,$email,$id)
    {

        return ApplicantThird::insertGetId([
            'email' => $email,
            'provider_id' => $id,
            'from' => ApplicantThird::fromType($type),
            'create_time'=>date('Y-m-d H:i:s'),
            'update_time'=>date('Y-m-d H:i:s'),
        ]);
    }

    //修改邮箱
    public function updateProviderEmail($email,$provider_id)
    {
        return ApplicantThird::where('provider_id',$provider_id)->update(['email'=>$email]);
    }

    /**
     * 第三方授权跳转
     * @return mixed
     */
    public function thirdOauthRedirect($type,$referer)
    {
        return call_user_func_array([$this,$type.'Third'],[$type,$referer]);
    }

    //google第三方介入
    public function googleThird($type,$referer)
    {
//        $referer = array_get($_SERVER,'HTTP_REFERER','dd.default.com');//可将默认地址配置
        if(!$this->comfirmOauthType($type)){
            return false;
        }else{
            return Socialite::driver($this->oauth_type)->redirect($referer);
        }
    }

    //facebook第三方介入
    public function facebookThird($type,$referer)
    {
//        $referer = array_get($_SERVER,'HTTP_REFERER','dd.default.com');//可将默认地址配置
        if(!$this->comfirmOauthType($type)){
            return false;
        }else{
            return Socialite::driver($this->oauth_type)->redirect($referer);
        }
    }

    //twitter第三方介入
    public function twitterThird($type,$referer)
    {
//        $referer = array_get($_SERVER,'HTTP_REFERER','dd.default.com');//可将默认地址配置
        $params = http_build_query(['state'=>$referer]);
        $redirect_url = config('services.twitter.redirect');
        if(strpos($redirect_url,'?')){
            $redirect_url = substr($redirect_url,0,strpos($redirect_url,'?'));
        }
//        config()->set('services.twitter.redirect',$redirect_url.'?'.$params);
//        dd(config('services.twitter.redirect'));
        if(!$this->comfirmOauthType($type)){
            return false;
        }else{
            return Socialite::driver($this->oauth_type)->redirect($referer);
        }
    }

    //第三方回调服务
    public function thirdCallback($type,$params)
    {
//        dd($params,'aaa');
        return call_user_func_array([$this,$type.'Callback'],$params);
    }

    //google回调
    public function googleCallback($params)
    {
        $user = $this->thirdOauthUser('google');
        if(!$user){
            return false;
        }
        $email = $this->getUserEmail();
        $id = $this->getUserId();
        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('google',$email,$id);
        }
//        dd('geeee',$params,$state);
        return $this->returnCallback(['email'=>$email,'provider_id'=>$provider_id,'state'=>$params]);
    }

    //google回调
    public function facebookCallback($params)
    {
        $user = $this->thirdOauthUser('facebook');
        if(!$user){
            return false;
        }
        $email = $this->getUserEmail();
        $id = $this->getUserId();
        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('facebook',$email,$id);
        }
        return $this->returnCallback(['email'=>$email,'provider_id'=>$provider_id,'state'=>$params]);
    }

    //twitter回调
    public function twitterCallback($params=[])
    {
        $user = $this->thirdOauthUser('twitter');
        if(!$user){
            return false;
        }
        $email = $this->getUserEmail();
        $id = $this->getUserId();
        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('twitter',$email,$id);
        }
        return $this->returnTwitterCallback(['email'=>$email,'provider_id'=>$provider_id,'state'=>$params]);
    }

    //回调返回参数
    public function returnCallback($params)
    {
        \Log::info('params',$params);
        $this->redirect_callback_uri = $this->formatHttpUrl($params['state']);
        \Log::info('state',$params);
        \Log::info('url',[$this->redirect_callback_uri]);
        unset($params['state']);
        $params['auth_code'] = 1;
        if(empty($params['email'])){
            unset($params['email']);
        }
        $this->return_params = $params;
        return true;
    }

    public function returnTwitterCallback($params)
    {
        \Log::info('params',$params);
        $this->redirect_callback_uri = $params['state'];
        \Log::info('state',$params);
        $this->redirect_callback_uri = $this->getTwitterRedictCallbackUri();
        \Log::info('url',[$this->redirect_callback_uri]);
        unset($params['state']);
        $params['auth_code'] = 1;
        if(empty($params['email'])){
            unset($params['email']);
        }
        $this->return_params = $params;
        return true;
    }

    public function returnFalse($url)
    {
        return ['url'=>$this->formatHttpUrl($url),'params'=>['auth_code'=>'101006001']];
    }

    public function getRedirectCallbackUri()
    {
        return $this->redirect_callback_uri;
    }

    public function getReturnParams()
    {
        return $this->return_params;
    }

    public function getTwitterRedictCallbackUri()
    {
        $redirect_url = $this->getUrlParams($this->redirect_callback_uri,'path');
        if(strpos($redirect_url,'?')){
            $redirect_url =  substr($redirect_url,0,strpos($redirect_url,'?'));
        }
        return $this->formatHttpUrl($redirect_url);
    }

    protected function getUrlParams($url,$key)
    {
        $parse_url = parse_url($url);
        if(!array_key_exists('query',$parse_url)){
            return '';
        }

        $query = explode('&',urldecode(parse_url($url)['query']));
        $params = [];
        foreach($query as $item){
            $tmp = explode('=',$item);
            $params[$tmp[0]] = $tmp[1];
        }
        if(!array_key_exists($key,$params)){
            return '';
        }
        return $params[$key];
    }

    public function formatHttpUrl($url)
    {
        $url_params = parse_url($url);
        if(!array_key_exists('scheme',$url_params)){
            return 'http://'.$url;
        }
        return $url;
    }
}