<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardVersion;
use App\Models\Set;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $sets = \App\Models\Set::orderByDesc('id')->withCount(['cardVersions' => function ($query) {
            $query->where('variant', '=', 'Normal');
        }])->get();

        return view('rating.index', compact('sets'));
    }

    public function show(Set $set)
    {
        $set->load(['cardVersions' => function ($query) use ($set) {
            $query->where('variant', '=', 'Normal');
            $query->orderBy('number', 'asc');
            $query->with([
                'card' => function ($query) use ($set) {
                    $query->withAvg(['ratings' => function ($query) use ($set) {
                        $query->forSet($set);
                    }], 'rating');
                },
                'card.userRating' => function ($query) use ($set) {
                    $query->forSet($set)
                        ->where('user_id', auth()->id());
                }
            ]);
        }]);

        return view('rating.show', compact('set'));
    }

    public function rate(Request $request, Set $set)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        $card = Card::findOrFail($request->card_id);

        return $card->ratings()->updateOrCreate(
            [
                'set_id' => $set->id,
                'user_id' => auth()->id(),
            ],
            [
                'rating' => $request->rating
            ]
        );
    }
}
