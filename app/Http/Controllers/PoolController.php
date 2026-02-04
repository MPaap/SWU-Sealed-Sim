<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardVersion;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
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

        $createLog = true;
        if (auth()->user()) {
            if (auth()->user()->poolLogs()->where('set_id', $set->id)->where('seed', $baseSeed)->exists()) {
                $createLog = false;
            }
        }

        if ($createLog) {
            $set->poolLogs()->create(['seed' => $baseSeed, 'user_id' => auth()->id()]);
        }

        return [
            'seed' => $baseSeed,
            'set' => $set,
            'default_bases' => Card::where('type', 'base')
                ->whereHas('versions', function ($query) use ($set) {
                    $query->where('rarity', 'Common');
                    $query->where('set_id', $set->id);
                })
                ->withData($set)
                ->LoadVersionwithVariant($set)
                ->get(),
            'packs' => $packs
        ];
    }

    public function save(Request $request, Set $set, $seed)
    {
        $errors = [];

        if (! auth()->user()) {
            $errors[] = 'You need to login to save your deck';
        }

        if (count($request->deck) < 30) {
            $errors[] = 'Your deck needs te be at least 30 cards';
        }

        if (! $request->leader) {
            $errors[] = 'Select a leader';
        }

        if (! $request->base) {
            $errors[] = 'Select a base';
        }

        if (count($errors) > 0) {
            return response()->json([
                'errors' => $errors
            ], 401);
        }

        DB::transaction(function () use ($request, $set, $seed) {
            $deck = auth()->user()->decks()->create([
                'set_id' => $set->id,
                'seed' => $seed,
                'leader_card_version_id' => $this->findVersionIdBySetCode($request->leader, $set),
                'base_card_version_id' => $this->findVersionIdBySetCode($request->base, $set),
            ]);

            foreach ($request->deck as $card) {
                $deck->cardVersions()->attach($card['version']['id']);
            }
        });

        return ['message' => 'Deck saved.'];
    }

    private function findVersionIdBySetCode($string, Set $set)
    {
        list($setCode, $number) = explode('_', $string);

        $version = $set->cardVersions()->where('number', $number)->firstOrFail();

        return $version->id;
    }

    public function prereleaseLeaders(Set $set)
    {
        $cards = Card::where('type', 'leader')
            ->whereHas('versions', function ($query) use ($set) {
                $query->where('rarity', 'Special')
                    ->where('set_id', $set->id);
            })
            ->withData($set)
            ->LoadVersionwithVariant($set, 'Normal')
            ->get();

        $cards->each(function ($card) use ($cards) {
            $card->foil = (Str::contains($card->version->variant, ['Foil', 'Showcase']));
            $card->tmp_id = Uuid::uuid1()->toString();
        });

        return $cards;
    }
}
