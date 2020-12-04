<?php

namespace App\Models;

class AdminModule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_module';
    public $timestamps = false;


    public function actions()
    {
        return $this->hasMany('App\AdminModuleAction');
    }
}
