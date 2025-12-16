<?php

namespace App\Http\Controllers;

use App\Models\CardVersion;
use App\Models\PackData;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// @todo add page for analytics per set
// @todo add page for community members to add packs so we can figure out the correct odds

class DataController extends Controller
{
    public function pack () {
        $sets = Set::orderByDesc('id')->get();
        $packs = PackData::with(['versions.card', 'set'])->latest()->limit(10)->get();

        return view('data.pack', compact('sets', 'packs'));
    }

    public function packStore (Request $request) {
        $request->validate([
            'set' => 'required',
            'cards' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $set = Set::findorFail($request->get('set'));

            $packData = PackData::create([
                'set_id' => $set->id,
            ]);

            foreach ($request->get('cards') as $slot => $number) {
                $version = CardVersion::where('set_id', $request->get('set'))
                    ->where('number', $number)
                    ->firstOrFail();

                $packData->versions()->attach($version->id, ['slot' => $slot]);
            }
        });

        return redirect()->back();
    }

    public function packDelete (PackData $packData) {
        $packData->versions()->sync([]);

        $packData->delete();

        return back();
    }
}
