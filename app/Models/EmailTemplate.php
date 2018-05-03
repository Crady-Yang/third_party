<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /**
     * base64模板
     */
    const BASE_EMAIL = 1;

    /**
     * gmail模板
     */
    const GMAIL_EMAIL = 2;

    public $table = 'email_template';


}