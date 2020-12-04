<?php

/**
 * Created by PhpStorm.
 * User: swl
 * Date: 2018/7/3
 * Time: 10:23
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Session;
use App\Token;

class Optional extends Model
{
    protected $table = 'optional';
    public $timestamps = false;


    protected $appends = [

    ];


}