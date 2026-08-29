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
        Schema::create('categoria_planta', function (Blueprint $table) {
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->foreignId('planta_id')->constrained()->cascadeOnDelete();
            $table->primary(['categoria_id', 'planta_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_planta');
    }
};
