<?php

namespace App\Factories;

use App\Models\Set;
use App\Services\Packs\SORPackStrategy;
use App\Services\Packs\JTLPackStrategy;
use App\Services\Packs\LAWPackStrategy;
use App\Contracts\PackStrategy;

class PackFactory
{
    public static function make(Set $set): PackStrategy
    {
        return match (true) {
            $set->id >= 7 => app(LAWPackStrategy::class),
            $set->id >= 4 => app(JTLPackStrategy::class),
            default => app(SORPackStrategy::class),
        };
    }
}
