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

        Schema::create('card_traits', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('card_trait_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('trait_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_traits');
        Schema::dropIfExists('card_trait_pivot');
    }
};
