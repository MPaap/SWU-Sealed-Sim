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
        Schema::create('pack_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('set_id');

            $table->timestamps();

            $table->index('set_id');
        });


        Schema::create('card_version_pack_data', function (Blueprint $table) {
            $table->unsignedBigInteger('card_version_id');
            $table->unsignedBigInteger('pack_data_id');
            $table->tinyInteger('slot');
            $table->index(['card_version_id', 'pack_data_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_version_pack_data');
        Schema::dropIfExists('pack_data');
    }
};
