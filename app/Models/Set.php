<?php

namespace App\Models;

use App\Factories\PackFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['code', 'name'];

    public function poolLogs()
    {
        return $this->hasMany(PoolLog::class);
    }

    public function generatePack(int $seed)
    {
        return PackFactory::make($this)->config($this, $seed)->generate();
    }
}
