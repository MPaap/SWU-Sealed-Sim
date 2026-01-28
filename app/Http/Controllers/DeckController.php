<?php

namespace App\Http\Controllers;

use App\Models\CardVersion;
use App\Models\Deck;
use App\Models\Set;

class DeckController
{
    public function show(Deck $deck)
    {
        $deck->load(['set', 'leaderCardVersion', 'baseCardVersion', 'cardVersions']);

        $json = $this->json($deck);

        return view('deck.show', compact('deck', 'json'));
    }

    public function json(Deck $deck)
    {
        $deck->load(['set', 'leaderCardVersion', 'baseCardVersion', 'cardVersions']);

        $data = [
            'metadata' => [
                'name' => "swusealed.com - {$deck->set->code} - $deck->seed"
            ],
            'leader' => [
                'id' => $this->getExportCode($deck->leaderCardVersion, $deck->set),
                'count' => 1,
            ],
            'base' => [
                'id' => $this->getExportCode($deck->baseCardVersion, $deck->set),
                'count' => 1,
            ],
            'deck' => []
        ];

        foreach ($deck->cardVersions as $cardVersion) {
            $code = $this->getExportCode($cardVersion, $deck->set);

            if ($key = array_search($code, array_column($data['deck'], 'id'))) {
                $data['deck'][$key]['count'] ++;
            } else {
                $data['deck'][] = [
                    'id' => $code,
                    'count' => 1,
                ];
            }
        }

        return $data;
    }

    private function getExportCode(CardVersion $cardVersion, Set $set)
    {
        if ($cardVersion->variant !== 'Normal') {
            $cardVersion = CardVersion::where('card_id', $cardVersion->card_id)
                ->where('set_id', $set->id)
                ->where('variant', 'Normal')
                ->first();
        }
        return "{$set->code}_" . str_pad($cardVersion->number, 3, '0', STR_PAD_LEFT);
    }
}
