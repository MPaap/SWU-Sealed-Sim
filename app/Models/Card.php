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

    public function version(): Card|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CardVersion::class);
    }

    public function normalVersion(): Card|\Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CardVersion::class);
    }

    public function versions(): Card|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CardVersion::class);
    }

    public function ratings(): Card|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function userRating(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Rating::class)
            ->where('user_id', auth()->id());
    }

    public function scopeNonLeader($query)
    {
        $query->whereNotIn('type', ['leader']);
    }

    public function scopeNonLeaderOrBase($query)
    {
        $query->whereNotIn('type', ['leader', 'base']);
    }

    public function scopeWithData($query, Set $set)
    {
        $query->with(['arenas', 'aspects', 'keywords', 'traits'])
            ->withAvg(['ratings' => function ($query) use ($set) {
                $query->forSet($set);
            }], 'rating');
    }

    public function scopeLoadVersionWithVariant($query, Set $set, $variant = 'normal')
    {
        $query->with(['version' => function ($q) use ($set, $variant) {
            $q->whereVariant($variant);
            $q->where('set_id', $set->id);
        }]);

        $query->with(['normalVersion' => function ($q) use ($set, $variant) {
            $q->whereVariant('normal');
            $q->where('set_id', $set->id);
        }]);
    }
}
