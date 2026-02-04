<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $fillable = ['user_id', 'card_id', 'set_id', 'rating'];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }

    public function set()
    {
        return $this->belongsTo('App\Models\Set');
    }

    public function scopeForSet($query, Set $set)
    {
        return $query->where('set_id', $set->id);
    }

}
