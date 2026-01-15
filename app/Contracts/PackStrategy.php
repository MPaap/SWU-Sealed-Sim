<?php

namespace App\Contracts;

use App\Models\Set;
use Illuminate\Support\Collection;

interface PackStrategy
{
    public function config(Set $set, int $seed): PackStrategy;

    public function generate(): Collection;
}
