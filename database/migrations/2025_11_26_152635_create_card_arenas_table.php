<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->down();

        Schema::create('card_arenas', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('card_arena_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('arena_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_arenas');
        Schema::dropIfExists('card_arena_pivot');
    }
};
