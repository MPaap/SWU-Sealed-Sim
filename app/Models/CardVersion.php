<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardVersion extends Model
{
    protected $fillable = ['set_id', 'card_id', 'number', 'variant', 'frontArt', 'backArt', 'rarity'];

    public function card(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
