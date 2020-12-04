<?php

namespace App\Models;

use App\Models\CurrencyMatch;
use Illuminate\Database\Eloquent\Model;

class CurrencyPlate extends Model
{
    protected $appends = [
    ];

    public function matches()
    {
        return $this->hasMany(CurrencyMatch::class, 'plate_id', 'id');
    }
}
