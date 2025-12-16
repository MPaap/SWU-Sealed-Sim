<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create a new user';

    public function handle()
    {
        $name = $this->ask('name');
        $email = $this->ask('email');
        $password = Str::random(10);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('User created successfully!');
        $this->line('ID: ' . $user->id);
        $this->line('password: ' . $password);
    }
}
