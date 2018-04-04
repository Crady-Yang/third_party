<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ApplicantThird extends Model
{
    /**
     * google服务
     */
    const THIRD_FROM_GOOGLE = 1;

    /**
     * facebook服务
     */
    const THIRD_FROM_FACEBOOK = 2;

    /**
     * twitter服务
     */
    const THIRD_FROM_TWITTER = 3;

    public $table = 'applicant_third';

    public static function fromType($type)
    {
        switch($type){
            case 'google':
                return self::THIRD_FROM_GOOGLE;
            case 'facebook':
                return self::THIRD_FROM_FACEBOOK;
            case 'twitter':
                return self::THIRD_FROM_TWITTER;
        }
    }
}