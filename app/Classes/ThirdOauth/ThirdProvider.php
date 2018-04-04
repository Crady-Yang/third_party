<?php
namespace App\Classes\ThirdOauth;
/**
 * Created by PhpStorm.
 * User: crady_yang
 * Date: 2018/3/31
 * Time: 下午6:04
 */

interface ThirdProvider
{
    /**
     * Redirect the user to the authentication page for the provider.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($str);

    /**
     * Get the User instance for the authenticated user.
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    public function user();
}