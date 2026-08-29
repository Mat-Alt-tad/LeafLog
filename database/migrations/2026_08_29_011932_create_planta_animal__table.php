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
        Schema::create('planta_animal', function (Blueprint $table) {
            $table->foreignId('planta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('animal_id')->constrained('animales')->cascadeOnDelete();
            $table->string('tipo_consumo')->default('complemento');
            $table->primary(['planta_id', 'animal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planta_animal');
    }
};
