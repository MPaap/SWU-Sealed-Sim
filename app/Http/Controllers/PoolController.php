<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Random\Engine\Mt19937;
use Random\Randomizer;

class PoolController extends Controller
{
    public function __invoke(Set $set)
    {
        $seed = request('seed', rand(1000,9999999));

        $engine = new Mt19937($seed);
        $rng = new Randomizer($engine);

        return [
            'seed' => $seed,
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
                ((new \App\Helpers\Pack($set, $seed))->generate()),
                ((new \App\Helpers\Pack($set, $rng->getInt(1000,9999999)))->generate()),
                ((new \App\Helpers\Pack($set, $rng->getInt(1000,9999999)))->generate()),
                ((new \App\Helpers\Pack($set, $rng->getInt(1000,9999999)))->generate()),
                ((new \App\Helpers\Pack($set, $rng->getInt(1000,9999999)))->generate()),
                ((new \App\Helpers\Pack($set, $rng->getInt(1000,9999999)))->generate()),
            ]
        ];
    }
}
