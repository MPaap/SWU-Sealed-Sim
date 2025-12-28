<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    const SPECIAL_START_SET_ID = 4;
    protected $fillable = ['code', 'name'];

    public function poolLogs()
    {
        return $this->hasMany(PoolLog::class);
    }
}
