<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\CardArena;
use App\Models\CardAspect;
use App\Models\CardKeyword;
use App\Models\CardTrait;
use App\Models\Set;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class fetchSetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-set {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all cards from SWU-DB api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $code = $this->argument('code');

        if ($code === 'all') {
            foreach (Set::all() as $set) {
                $this->fetch($set);
            }
        } else {
            $set = Set::firstOrCreate(['code' => $code], ['name' => $code]);

            if ($set) {
                $this->fetch($set);
            }
        }
    }

    private function fetch(Set $set)
    {
        $this->line("Getting {$set->name} from SWU-DB");

        $result = $this->getDataFromSWUDB($set->code);

        $result = json_decode($result, true);

        $bar = $this->output->createProgressBar(count($result['data']));

        $bar->start();

        foreach ($result['data'] as $data) {
            $this->addCard($data, $set);

            $bar->advance();
        }

        $bar->finish();

        $this->line("Done with {$set->name}!");
    }

    private function getDataFromSWUDB(string $code)
    {
        return Cache::remember("set_raw_data_{$code}", \Illuminate\Support\now()->addWeek(), function () use ($code) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.swu-db.com/cards/{$code}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

//            $headers = array();
//            $headers[] = "Accept: application/json";
//            $headers[] = "Authorization: Bearer APIKEY";
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);

            return $result;
        });
    }

    private function addCard(array $data, $set)
    {
//        dump($data);

        $debug = false;

        $card = Card::firstOrCreate([
            'name' => $data['Name'],
            'subtitle' => $data['Subtitle'] ?? null,
        ], [
            'type' => $data['Type'],
            'cost' => $data['Cost'] ?? null,
            'power' => $data['Power'] ?? null,
            'health' => $data['HP'] ?? null,
            'doubleSided' => $data['DoubleSided'],
        ]);

        foreach ($data['Aspects'] ?? [] as $aspectName) {
            $aspect = CardAspect::firstOrCreate(['name' => $aspectName]);
            $card->aspects()->syncWithoutDetaching($aspect);
        }

        foreach ($data['Arenas'] ?? [] as $arenaName) {
            $arena = CardArena::firstOrCreate(['name' => $arenaName]);
            $card->arenas()->syncWithoutDetaching($arena);
        }

        foreach ($data['Traits'] ?? [] as $traitName) {
            $trait = CardTrait::firstOrCreate(['name' => $traitName]);
            $card->traits()->syncWithoutDetaching($trait);
        }

        foreach ($data['Keywords'] ?? [] as $keywordName) {
            $keyword = CardKeyword::firstOrCreate(['name' => $keywordName]);
            $card->keywords()->syncWithoutDetaching($keyword);
        }

        $card->versions()->firstOrCreate([
            'set_id' => $set->id,
            'number' => $data['Number'],
        ], [
            'rarity' => $data['Rarity'],
            'variant' => $data['VariantType'],
            'frontArt' => $data['FrontArt'],
            'backArt' => $data['BackArt'] ?? null,
        ]);

        if ($debug) {
            dump($data);
            dump($card);
            dump("Aspects: ". $card->aspects->implode('name', ', '));
            dump("Arenas: ". $card->arenas->implode('name', ', '));
            dump("Traits: ". $card->traits->implode('name', ', '));
            dump("Keywords: ". $card->keywords->implode('name', ', '));
            dump("Versions: ". $card->versions->implode('variant', ', '));

            dd('debug');
        }
    }
}
