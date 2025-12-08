<?php

namespace Database\Seeders;

use App\Models\CardArena;
use App\Models\CardAspect;
use App\Models\Set;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Set::query()->truncate();
        foreach ([
                     'SOR' => 'Spark of Rebellion',
                     'SHD' => 'Shadows of the Galaxy',
                     'TWI' => 'Twilight of the Republic',
                     'JTL' => 'Jump to Lightspeed',
                     'LOF' => 'Legends of the Force',
                     'SEC' => 'Secrets of Power',
                 ] as $code => $set) {
            Set::create([
                'name' => $set,
                'code' => $code,
            ]);
        }

        CardAspect::query()->truncate();
        foreach (['Villainy', 'Heroism', 'Vigilance', 'Command', 'Aggression', 'Cunning'] as $aspect) {
            CardAspect::create([
                'name' => $aspect,
            ]);
        }

        CardArena::query()->truncate();
        foreach (['ground', 'space'] as $arena) {
            CardArena::create([
                'name' => $arena,
            ]);
        }
    }
}
