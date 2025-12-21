<?php

namespace App\Helpers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Random\Engine\Mt19937;
use Random\Randomizer;

// @todo check if randomness is real
class Pack
{
    public \Illuminate\Support\Collection $cards;
    public Set $set;
    private int $seed;
    private Randomizer $rng;
    private array $selectedIds = [];

    public function __construct(Set $set, $seed)
    {
        $this->cards = collect();
        $this->set = $set;
        $this->seed = $seed;

        $engine = new Mt19937($this->seed);
        $this->rng = new Randomizer($engine);
    }

    public function generate()
    {
        $this->addLeader();
//        $this->addBase();

        $commons = 9;
        $hyperspace = false;

        // Hyperspace pull ~2/3 packs
        if ($this->rng->getInt(1, 3) <= 2) {
            // Conditional: Hyperspace Rare/Legendary/Special ~1 in 15
            if ($this->rng->getInt(1, 15) === 15) {
                // Only include Special if set >= certain ID
                $hyperspaceRarities = ['rare', 'legendary'];
                if ($this->set->id >= Set::SPECIAL_START_SET_ID) {
                    $hyperspaceRarities[] = 'special';
                }
                $hyperspace = $hyperspaceRarities;
            } else {
                $hyperspace = ['common', 'uncommon'];
            }
            $commons = 8; // Hyperspace replaces one common
        }

        $this->addCommons($commons);
        $this->addUncommons(3);
        $this->addRares(1);
        $this->addFoils(1);

        if (is_array($hyperspace)) {
            $this->addHyperSpace(1, $hyperspace);
        }

        return $this->cards;
    }

    private function add(Collection $cards)
    {
        $cards->each(function ($card) use ($cards) {
            $card->tmp_id = Uuid::uuid1()->toString();
            $this->selectedIds[] = $card->id;
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

        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->where('type', 'leader')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity)
                    ->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set, $variant)
            ->limit(1)
            ->get();

        $cards->each(function ($card) use ($variant) {
            $card->foil = $variant === 'Showcase';
        });

        $this->add($cards);
    }

    private function addBase()
    {
        $rarity = 'Common';
        $variant = 'normal';

        // Get Common base
        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->where('type', 'base')
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit(1)
            ->get();

        $cards->each(function ($card) {
            $card->foil = false;
        });

        $this->add($cards);
    }

    private function addCommons(int $amount)
    {
        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'Common');
                $query->where('set_id', $this->set->id);
            })
            ->whereNotIn('id', $this->selectedIds)
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $cards->each(function ($card) {
            $card->foil = false;
        });

        $this->add($cards);
    }

    private function addUncommons(int $amount)
    {
        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) {
                $query->where('rarity', 'Uncommon');
                $query->where('set_id', $this->set->id);
            })
            ->whereNotIn('id', $this->selectedIds)
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $cards->each(function ($card) {
            $card->foil = false;
        });

        $this->add($cards);
    }

    private function addRares(int $amount)
    {
        // Get 1 random X rarity leader
        $rarity = 'Rare';
        if ($this->rng->getInt(1, 8) === 1) { // Find real odds
            $rarity = 'Legendary';
        }
        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->nonLeader()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('rarity', $rarity);
                $query->where('set_id', $this->set->id);
            })
            ->whereNotIn('id', $this->selectedIds)
            ->withData()
            ->LoadVersionWithVariant($this->set)
            ->limit($amount)
            ->get();

        $cards->each(function ($card) {
            $card->foil = false;
        });

        $this->add($cards);
    }

    private function addFoils(int $amount)
    {
        // Weighted rarities: Common/Uncommon more likely than Rare/Legendary
        $roll = $this->rng->getInt(1, 100);

        if ($roll <= 60) {
            $rarity = ['Common'];
        } elseif ($roll <= 90) {
            $rarity = ['Uncommon'];
        } elseif ($roll <= 98) {
            $rarity = ['Rare'];
            if ($this->set->id >= Set::SPECIAL_START_SET_ID) {
                $rarity[] = 'Special';
            }
        } else {
            $rarity = ['Legendary'];
        }

        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarity) {
                $query->where('set_id', $this->set->id)
                    ->whereIn('rarity', $rarity);
            })
            ->whereNotIn('id', $this->selectedIds)
            ->withData()
            ->LoadVersionWithVariant($this->set, 'Foil')
            ->limit($amount)
            ->get();

        $cards->each(function ($card) use ($cards) {
            $card->foil = true;

            if (is_null($card->version)) {
                $card->load('version');
            }
        });

        $this->add($cards);
    }

    private function addHyperSpace(int $amount, array $rarities)
    {
        $variant = 'Hyperspace';
        if ($this->rng->getInt(1, 3) === 1) {
            $variant = 'Hyperspace Foil';
        }

        $cards = Card::orderByRaw('CRC32(CONCAT(id, ?))', [$this->seed])
            ->nonLeaderOrBase()
            ->whereHas('versions', function ($query) use ($rarities) {
                $query->where('set_id', $this->set->id);
                $query->whereIn('rarity', $rarities);
            })
            ->whereNotIn('id', $this->selectedIds)
            ->withData()
            ->LoadVersionWithVariant($this->set, $variant)
            ->limit($amount)
            ->get();

        $cards->each(function ($card) use ($variant) {
            $card->foil = $variant === 'Hyperspace Foil';
            if (is_null($card->version)) {
                $card->load('version');
            }
        });

        $this->add($cards);
    }
}
