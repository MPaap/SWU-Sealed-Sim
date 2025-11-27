<?php

namespace App\Helpers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Support\Collection;

class Pack
{
    public \Illuminate\Support\Collection $pack;
    public Set $set;

    public function __construct(Set $set)
    {
        $this->pack = collect();
        $this->set = $set;
    }

    public function generate()
    {
        $pack = collect();

        $this->addLeader();
        $this->addBase();
        $this->addCommons(9);
        $this->addUncommons(3);
        $this->addRares(1);

        return $this->pack;
    }

    private function add(Collection $cards)
    {
        $this->pack = $this->pack->merge($cards);
    }

    private function addLeader(): void
    {
        // Get 1 random X rarity leader
        $rarity = 'common';
        if (rand(1, 8) === 1) { // Find real odds
            $rarity = 'rare';
        }

        $cards = Card::inRandomOrder()
            ->where('type', 'leader')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->with('versions')
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addBase()
    {
        // Get Common base
        $cards = Card::inRandomOrder()
            ->where('type', 'base')
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'common');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->with('versions')
            ->limit(1)
            ->get();

        $this->add($cards);
    }

    private function addCommons(int $amount)
    {
        // Get 9 random commons
        $cards = Card::inRandomOrder()
            ->nonLeader()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'common');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->with('versions')
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addUncommons(int $amount)
    {
        // Get 3 random uncommons
        $cards = Card::inRandomOrder()
            ->nonLeader()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'uncommon');
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->with('versions')
            ->limit($amount)
            ->get();

        $this->add($cards);
    }

    private function addRares(int $amount)
    {
        // Get 1 random X rarity leader
        $rarity = 'rare';
        if (rand(1, 8) === 1) { // Find real odds
            $rarity = 'legendary';
        }
        $cards = Card::inRandomOrder()
            ->nonLeader()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->with('versions')
            ->limit($amount)
            ->get();

        $this->add($cards);
    }
}
