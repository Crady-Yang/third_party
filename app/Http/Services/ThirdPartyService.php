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

    /**
     * 第三方请求的源地址
     */
    protected $redirect_callback_uri = null;

    /**
     * 回源地址需要的参数
     */
    protected $return_params = [];

    /**
     * 第三方类型
     */
    protected $oauth_type = null;

    /**
     * 第三方用户信息
     */
    protected $providerUser = null;

    /**
     * 回调url白名单
     */
    protected $whiteUrlConfig = [];


    public function __construct()
    {
        //初始化白名单
        $this->whiteUrlConfig = config('config.third_url_white_list',[]);
    }

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
        \Log::info('comfirmOauthType',[$type,in_array($type,self::OAUTH_TYPE)]);
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
    public function thirdOauthUser()
    {
        try{
            $this->providerUser = Socialite::driver($this->oauth_type)->user();
            return true;
        }catch(\Exception $e){
            return false;
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
    public function thirdOauthRedirect(string $type, string $referer)
    {
        $referer = $this->removeUrlParams($referer);
        \Log::info('thirdOauthRedirect',[
            $type,
            $referer,
            'comfirmOauthType'=>$this->comfirmOauthType($type),
            'isWhiteUrl'=>$this->isWhiteUrl($referer),
            'isValidUrl'=>$this->isValidUrl($referer),
        ]);
        /**
         * 第三方类型
         * 触发回调源地址白名单
         * url合法
         */
        if(
            !$this->comfirmOauthType($type)
            || !$this->isWhiteUrl($referer)
            || !$this->isValidUrl($referer)
        ){
            return false;
        }else{
            return call_user_func_array([$this,$type.'Third'],[$this->formatHttpUrl($referer)]);
        }
    }

    /**
     * 回调地址白名单验证
     * @param $url
     * @return bool
     */
    protected function isWhiteUrl($url)
    {
        $u = strtolower(env('APP_ENV','dev')) === 'production';
        \Log::info('isWhiteUrl',[!$u]);
        //开发环境 白名单不做验证
        if( ! (strtolower(env('APP_ENV','dev')) === 'production') ){
            return true;
        }

        if( in_array($url, $this->whiteUrlConfig) ){
            return true;
        }
        return false;
    }

    /**
     * google第三方接入
     * @param $referer
     * @return mixed
     */
    protected function googleThird($referer)
    {
        return Socialite::driver($this->oauth_type)->redirect($referer);
    }

    /**
     * facebook第三方接入
     * @param $referer
     * @return mixed
     */
    protected function facebookThird($referer)
    {
        return Socialite::driver($this->oauth_type)->redirect($referer);
    }

    /**
     * twitter第三方接入
     * @param $referer
     * @return mixed
     */
    protected function twitterThird($referer)
    {
        dd($referer);
        return Socialite::driver($this->oauth_type)->redirect($referer);
//        $referer = array_get($_SERVER,'HTTP_REFERER','dd.default.com');//可将默认地址配置
//        $params = http_build_query(['state'=>$referer]);
//        $redirect_url = config('services.twitter.redirect');
//        if(strpos($redirect_url,'?')){
//            $redirect_url = substr($redirect_url,0,strpos($redirect_url,'?'));
//        }
//        config()->set('services.twitter.redirect',$redirect_url.'?'.$params);
//        dd(config('services.twitter.redirect'));
    }

    /**
     * 第三方回调服务
     * @param $type
     * @param $params
     * @return mixed
     */
    public function thirdCallback($type,$params)
    {
        if( !$this->comfirmOauthType($type) ){
            return false;
        }else {
            return call_user_func_array([$this, $type . 'Callback'], $params);
        }
    }

    /**
     * google回调
     * @param $url
     * @return bool
     */
    protected function googleCallback($url)
    {
        $user = $this->thirdOauthUser();
        $this->sourceCallback($url);
        if(!$user){
            return false;
        }

        $email = $this->getUserEmail();
        $id = $this->getUserId();

        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('google',$email,$id);
        }

        return $this->returnCallback(['email'=>$email,'provider_id'=>$provider_id]);
    }

    /**
     * facebook回调
     * @param $params
     * @return bool
     */
    protected function facebookCallback($url)
    {
        $user = $this->thirdOauthUser();
        $this->sourceCallback($url);

        if(!$user){
            return false;
        }

        $email = $this->getUserEmail();
        $id = $this->getUserId();

        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('facebook',$email,$id);
        }

        return $this->returnCallback(['email'=>$email,'provider_id'=>$provider_id]);
    }

    /**
     * twitter回调
     * @param $params
     * @return bool
     */
    protected function twitterCallback($url)
    {
        $user = $this->thirdOauthUser();
        $this->sourceTwitterCallback($url);

        if(!$user){
            return false;
        }

        $email = $this->getUserEmail();
        $id = $this->getUserId();

        $provider_id = $this->isExistProviderId($id);
        if(!$provider_id){
            $provider_id = $this->addThirdParty('twitter',$email,$id);
        }

        return $this->returnCallback(['email'=>$email,'provider_id'=>$provider_id]);
    }

    /**
     * 源地址获取
     * @param $params
     */
    protected function sourceCallback($url)
    {
        $this->redirect_callback_uri = $this->formatHttpUrl($url);
    }

    /**
     * twitter授权前地址
     * @param $params
     */
    protected function sourceTwitterCallback($url)
    {
        $this->redirect_callback_uri;
        $redirect_url = $this->getUrlParams($url,'path');

        $this->redirect_callback_uri = $this->formatHttpUrl($this->removeUrlParams($redirect_url));
    }

    //回调返回参数
    public function returnCallback($params)
    {
        $params['auth_code'] = 1;
        if(empty($params['email'])){
            unset($params['email']);
        }
        $this->return_params = $params;
        return true;
    }

    /**
     * 失败信息url及错误码
     * @param $url
     * @return array
     */
    public function returnFalse()
    {
        return ['auth_code'=>'101006001'];
//        return ['url'=>$this->formatHttpUrl($url),'params'=>['auth_code'=>'101006001']];
    }

    /**
     * 源地址
     * @return null
     */
    public function getRedirectCallbackUri()
    {
        return $this->redirect_callback_uri;
    }

    /**
     * 源地址所需参数
     * @return array
     */
    public function getReturnParams()
    {
        return $this->return_params;
    }

    /**
     * 获取url参数
     * @param $url
     * @param $key
     * @return mixed|string
     */
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

    /**
     * http头格式化
     * @param $url
     * @return string
     */
    public function formatHttpUrl($url)
    {
        $url_params = parse_url($url);
        if(!array_key_exists('scheme',$url_params)){
            return 'http://'.$url;
        }
        return $url;
    }

    /**
     * 去除url后参数
     * @param $url
     * @return string
     */
    public function removeUrlParams($url)
    {
        if(strpos($url,'?')){
            return substr($url,0,strpos($url,'?'));
        }else{
            return $url;
        }
    }

    //url合法校验
    public function isValidUrl($path)
    {
        if (! preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
            \Log::info('third_login_path_in',[$path,filter_var($path, FILTER_VALIDATE_URL)]);
            return filter_var($path, FILTER_VALIDATE_URL) !== false;
        }
        \Log::info('third_login_path_true',[date('Y-m-d H:i:s')]);
        return true;
    }
}