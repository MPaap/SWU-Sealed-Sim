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
        Schema::create('decks', function (Blueprint $table) {
            $table->uuid('id');

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('set_id');
            $table->string('seed');

            $table->unsignedBigInteger('leader_card_version_id');
            $table->unsignedBigInteger('base_card_version_id');

            $table->timestamps();
        });


        Schema::create('card_version_deck', function (Blueprint $table) {
            $table->uuid('deck_id')->index();
            $table->unsignedBigInteger('card_version_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_version_deck');
        Schema::dropIfExists('decks');
    }
};
