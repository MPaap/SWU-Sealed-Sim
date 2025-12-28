<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoolLog extends Model
{
    protected $fillable = ['seed'];

    public const UPDATED_AT = null;

    public function set()
    {
        return $this->belongsTo(Set::class, 'set_id');
    }
}
