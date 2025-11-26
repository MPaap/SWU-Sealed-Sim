<?php

namespace App\Console\Commands;

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

        $set = Set::firstOrCreate(['code' => $code], ['name' => $code]);

        $result = Cache::remember("set_raw_data_{$code}", \Illuminate\Support\now()->addWeek(), function () use ($code) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.swu-db.com/cards/{$code}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


            $headers = array();
            $headers[] = "Accept: application/json";
            $headers[] = "Authorization: Bearer APIKEY";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);

            return $result;
        });

        $result = json_decode($result, true);

        foreach ($result['data'] as $result['data'][0]) {
            dd($result['data'][0]);
        }
    }
}
