<?php

namespace App\Services\Packs;

use App\Contracts\PackStrategy;
use App\Models\Card;
use App\Models\CardVersion;
use App\Models\Set;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Random\Engine\Mt19937;
use Random\Randomizer;

class LAWPackStrategy implements PackStrategy
{
    public \Illuminate\Support\Collection $cards;
    public Set $set;
    private int $seed;
    private Randomizer $rng;

    public function config(Set $set, $seed): PackStrategy
    {
        $this->cards = collect();
        $this->set = $set;
        $this->seed = $seed;

        $engine = new Mt19937($this->seed);
        $this->rng = new Randomizer($engine);

        return $this;
    }

    public function generate(): Collection
    {
        $this->addLeader();
//        $this->addBase();

        $commons = 8;

        if ($this->rng->getInt(1, 18) <= 1) {
            $commons = 7; // Hyperspace replaces one common
            $this->addPrestige(1);
        }

        $this->addCommons($commons);
        $this->addUncommons(3);
        $this->addRares(1);
        $this->addHyperSpace(1);
        $this->addHyperSpaceFoil(1);

        return $this->cards;
    }

    private function add(Collection $cards)
    {
        $cards->each(function ($card) use ($cards) {
            $card->foil = (Str::contains($card->version->variant, ['Foil', 'Showcase']));
            $card->tmp_id = Uuid::uuid1()->toString();
        });

        $this->cards = $this->cards->merge($cards);
    }

    private function addLeader(): void
    {
        $rarity = $this->rng->getInt(1, 8) === 1 ? 'Rare' : 'Common';

        $variant = 'Normal';
        if ($this->rng->getInt(1, 288) === 1) {
            $variant = 'Showcase';
        }

        $cards = Card::inRandomOrder($this->seed)
            ->where('type', 'leader')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity)
                    ->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, $variant)
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addBase()
    {
        $rarity = 'Common';
        $variant = 'normal';

        // Get Common base
        $cards = Card::inRandomOrder($this->seed)
            ->where('type', 'base')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addCommons(int $amount)
    {
        $cards = Card::inRandomOrder($this->seed)
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'Common');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addUncommons(int $amount)
    {
        $cards = Card::inRandomOrder($this->seed)
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'Uncommon');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addRares(int $amount)
    {
        $rarity = 'Rare';
        if ($this->rng->getInt(1, 8) === 1) { // Find real odds
            $rarity = 'Legendary';
        }

        $cards = Card::inRandomOrder($this->seed)
            ->nonLeader()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addFoils(int $amount)
    {
        // Weighted rarities: Common/Uncommon more likely than Rare/Legendary
        $roll = $this->rng->getInt(1, 100);

        // LAW Set Specific Foil Weighted Distribution
        $rarity = match(true) {
            $roll <= 55 => ['Common'],
            $roll <= 85 => ['Uncommon'],
            $roll <= 95 => ['Rare', 'Special'], // "Wanted" cards appear here
            default     => ['Legendary'],
        };

        $cards = Card::inRandomOrder($this->seed + 999)
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('set_id', $this->set->id)
                    ->whereIn('rarity', $rarity);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, 'Hyperspace Foil')
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addHyperSpace(int $amount)
    {
        $roll = $this->rng->getInt(1, 48);

        $rarities = match(true) {
            $roll <= 1 => ['Legendary'],
            $roll <= 5 => ['Rare', 'Special'],
            $roll <= 17 => ['Uncommon'],
            default     => ['Common'],
        };

        $variant = 'Hyperspace';

        $cards = Card::inRandomOrder($this->seed + 250)
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarities) {
                $query->where('set_id', $this->set->id);
                $query->whereIn('rarity', $rarities);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, $variant)
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addHyperSpaceFoil(int $amount)
    {
        $roll = $this->rng->getInt(1, 96);

        $rarities = match(true) {
            $roll <= 1 => ['Legendary'],
            $roll <= 5 => ['Rare', 'Special'],
            $roll <= 32 => ['Uncommon'],
            default     => ['Common'],
        };

        $variant = 'Hyperspace Foil';

        $cards = Card::inRandomOrder($this->seed + 250)
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarities) {
                $query->where('set_id', $this->set->id);
                $query->whereIn('rarity', $rarities);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, $variant)
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addPrestige(int $amount)
    {
        $cards = Card::inRandomOrder($this->seed)
            ->nonLeader()
            ->whereHas('versions', function ($query) {
                $query->where('set_id', $this->set->id);
                $query->where('variant', 'Prestige');
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, 'Prestige')
            ->limit($amount)
            ->get();

        $this->add($cards);
    }
}
