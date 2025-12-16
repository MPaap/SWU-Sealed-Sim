<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackData extends Model
{
    protected $fillable = ['set_id'];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    public function versions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CardVersion::class, 'card_version_pack_data')
            ->withPivot('slot')
            ->orderByPivot('slot');
    }

    public function countEachRarityInPack()
    {
        return $this->versions->countBy(fn ($item) =>
            $item['rarity']
        );
    }

    public function countEachAspectInPack()
    {
        $counts = $this->versions->countBy(fn ($item) =>
            $item->card->aspects->implode('name', '-')
        );

        return $counts->sortKeys();
    }
}
