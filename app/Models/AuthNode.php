<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AuthNode extends Model
{
    protected $connection = 'pp';

    protected $table = 'auth_nodes';

    public $timestamps = false;
}