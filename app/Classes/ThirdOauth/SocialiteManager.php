<?php

namespace App\Classes\ThirdOauth;


use Laravel\Socialite\SocialiteManager as Manager;
use Illuminate\Support\Arr;
use App\Classes\ThirdOauth\Two\FacebookProvider;
use App\Classes\ThirdOauth\Two\GoogleProvider;
//use League\OAuth1\Client\Server\Twitter as TwitterServer;
use App\Classes\ThirdOauth\One\Twitter as TwitterServer;
use App\Classes\ThirdOauth\One\TwitterProvider;

class SocialiteManager extends Manager
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createFacebookDriver()
    {
        $config = $this->app['config']['services.facebook'];

        return $this->buildProvider(
            FacebookProvider::class, $config
        );
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createGoogleDriver()
    {
        $config = $this->app['config']['services.google'];
        return $this->buildProvider(
            GoogleProvider::class, $config
        );
    }



    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        $request = $this->app['request'];
        $formatRedirectUrl = $this->formatRedirectUrl($config);
        $guzzle = Arr::get($config, 'guzzle', []);
        $return = new $provider($request,$config['client_id'],$config['client_secret'],$formatRedirectUrl,$guzzle);
        return $return;
    }

    /**
     * Format the server configuration.
     *
     * @param  array  $config
     * @return array
     */
    public function formatConfig(array $config)
    {
        $return = array_merge([
            'identifier' => $config['client_id'],
            'secret' => $config['client_secret'],
            'callback_uri' => $this->formatRedirectUrl($config),
        ], $config);
        return $return;
        return array_merge([
            'identifier' => $config['client_id'],
            'secret' => $config['client_secret'],
            'callback_uri' => $this->formatRedirectUrl($config),
        ], $config);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\One\AbstractProvider
     */
    protected function createTwitterDriver()
    {
        $config = $this->app['config']['services.twitter'];

        return new TwitterProvider(
            $this->app['request'], new TwitterServer($this->formatConfig($config))
        );
    }

}