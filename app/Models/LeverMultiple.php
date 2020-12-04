<?php

namespace App\Models;

class LeverMultiple extends Model
{
    protected $table = 'lever_multiple';
    public $timestamps = false;

    public function getQuotesAttribute()
    {
        return unserialize($this->attributes['quotes']);
    }
}
