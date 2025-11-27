<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['name', 'subtitle', 'type', 'cost', 'power', 'health', 'doubleSided'];

    public function arenas(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardArena::class, 'card_arena_pivot', 'card_id', 'arena_id');
    }

    public function aspects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardAspect::class, 'card_aspect_pivot', 'card_id', 'aspect_id');
    }

    public function keywords(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardKeyword::class, 'card_keyword_pivot', 'card_id', 'keyword_id');
    }

    public function traits(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardTrait::class, 'card_trait_pivot', 'card_id', 'trait_id');
    }

    public function versions(): Card|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardVersion::class);
    }

    public function scopeNonLeader($query)
    {
        $query->where('type', '!=', 'leader');
    }

    public function scopeWithData($query)
    {
        $query->with(['arenas', 'aspects', 'keywords', 'traits']);
    }
}
