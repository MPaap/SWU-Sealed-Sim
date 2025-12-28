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
        Schema::create('pool_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('set_id');
            $table->string('seed');

            $table->timestamp('created_at');

            $table->index(['set_id', 'seed']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pool_logs');
    }
};
