<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasUuids;

    protected $fillable = ['set_id', 'seed', 'leader_card_version_id', 'base_card_version_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function leaderCardVersion()
    {
        return $this->belongsTo(CardVersion::class);
    }

    public function baseCardVersion()
    {
        return $this->belongsTo(CardVersion::class);
    }

    public function cardVersions()
    {
        return $this->belongsToMany(CardVersion::class);
    }
}
