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
        Schema::create('card_versions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('set_id');
            $table->unsignedBigInteger('card_id');

            $table->integer('number');

            $table->string('variant')->default('normal');

            $table->string('frontArt');
            $table->string('backArt')->nullable();
            $table->string('rarity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_versions');
    }
};
