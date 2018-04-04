<?php
namespace App\Classes\ThirdOauth;

use Laravel\Socialite\Facades\Socialite;

/**
 * 第三方登录 facebook google twitter
 * Class ThirdOauth
 */
class ThirdOauth
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
     * 第三方类型
     */
    protected $oauth_type = null;

    /**
     * 第三方用户信息
     */
    public $providerUser = null;


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
     * 第三方授权跳转
     * @return mixed
     */
    public function thirdOauthRedirect($type)
    {
        if(!$this->comfirmOauthType($type)){
           return false;
        }else{
            return Socialite::driver($this->oauth_type)->redirect();
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
}