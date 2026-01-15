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
        $randomness = [
            'min' => 1000,
            'max' => 999999999999999999
        ];

        $baseSeed = request('seed', (int) (microtime(true) * 1000));
        $engine = new Mt19937($baseSeed);
        $rng = new Randomizer($engine);

        $packs = [];
        for ($i = 0; $i < 6; $i++) {
            $packSeed = $rng->getInt($randomness['min'], $randomness['max']); // unique-ish seed per pack
            $packs[] = $set->generatePack($packSeed);
        }

        $set->poolLogs()->create(['seed' => $baseSeed]);

        return [
            'seed' => $baseSeed,
            'set' => $set,
            'default_bases' => Card::where('type', 'base')
                ->whereHas('versions', function ($query) use ($set) {
                    $query->where('rarity', 'Common');
                    $query->where('set_id', $set->id);
                })
                ->withData()
                ->LoadVersionWithVariant($set)
                ->get(),
            'packs' => $packs
        ];
    }
}
