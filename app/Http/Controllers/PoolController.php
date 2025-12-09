<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Http\Request;

class PoolController extends Controller
{
    public function __invoke(Set $set)
    {
        return [
            'set' => $set,
            'default_bases' => Card::where('type', 'base')
                ->whereHas('versions', function ($query) use ($set) {
                    $query->where('rarity', 'Common');
                    $query->where('set_id', $set->id);
                })
                ->withData()
                ->LoadVersionWithVariant($set)
                ->get(),
            'packs' => [
                (new \App\Helpers\Pack($set)->generate()),
                (new \App\Helpers\Pack($set)->generate()),
                (new \App\Helpers\Pack($set)->generate()),
                (new \App\Helpers\Pack($set)->generate()),
                (new \App\Helpers\Pack($set)->generate()),
                (new \App\Helpers\Pack($set)->generate()),
            ]
        ];
    }
}
