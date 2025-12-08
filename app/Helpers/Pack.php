<?php

namespace App\Helpers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class Pack
{
    public \Illuminate\Support\Collection $cards;
    public Set $set;

    public function __construct(Set $set)
    {
        $this->cards = collect();
        $this->set = $set;
    }

    public function generate()
    {
        $this->addLeader();
//        $this->addBase();
        $this->addCommons(9);
        $this->addUncommons(3);
        $this->addRares(1);
        $this->addFoils(1);

        return $this->cards;
    }

    private function add(Collection $cards)
    {
        $cards->each(function ($card) use ($cards) {
            $card->tmp_id = Uuid::uuid1()->toString();
        });

        $this->cards = $this->cards->merge($cards);
    }

    private function addLeader(): void
    {
        // Get 1 random X rarity leader
        $rarity = 'Common';
        if (rand(1, 8) === 1) { // Find real odds
            $rarity = 'rare';
        }

        // Get 1 random X rarity leader
        $variant = 'Normal';
        if (rand(1, (12*24)) === 1) { // Find real odds
            $variant = 'Showcase';
        }

        $cards = Card::inRandomOrder()
            ->where('type', 'leader')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($variant)
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addBase()
    {
        $rarity = 'Common';

        // Get Common base
        $cards = Card::inRandomOrder()
            ->where('type', 'base')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant()
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addCommons(int $amount)
    {
        $rarity = 'Common';
        if (rand(1, 20) === 1) { // Find real odds
            $rarity = 'Special';
        }

        // Get 9 random commons
        $cards = Card::inRandomOrder()
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->whereIn('rarity', [$rarity]);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant()
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addUncommons(int $amount)
    {
        // Get 3 random uncommons
        $cards = Card::inRandomOrder()
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'Uncommon');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant()
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addRares(int $amount)
    {
        // Get 1 random X rarity leader
        $rarity = 'Rare';
        if (rand(1, 8) === 1) { // Find real odds
            $rarity = 'Legendary';
        }
        $cards = Card::inRandomOrder()
            ->nonLeader()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant()
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addFoils(int $amount)
    {
        $cards = Card::inRandomOrder()
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant('Foil')
            ->limit($amount)
            ->get();

        $cards->each(function ($card) use ($cards) {
            if (is_null($card->version)) {
                $card->load('version');
            }
        });

        $this->add($cards);
    }
}
