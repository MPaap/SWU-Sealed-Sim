<?php

namespace Database\Seeders;

use App\Models\CardArena;
use App\Models\CardAspect;
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
