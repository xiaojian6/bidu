<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    //
    protected $table = 'app_version';

    protected $appends = [
        'type_str',
        'update_type_str'
    ];

    public function getTypeStrAttribute()
    {
        $type = $this->getAttribute('type');
        if($type == 1){
            return 'Android';
        }else{
            return 'IOS';
        }
    }

    public function getUpdateTypeStrAttribute()
    {
        $type = $this->getAttribute('update_type');
        if($type == 1){
            return '增量更新';
        }else{
            return '整包更新';
        }
    }
}
