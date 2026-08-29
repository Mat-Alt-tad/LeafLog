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
        Schema::create('modelos_3d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planta_id')->constrained()->cascadeOnDelete();
            $table->string('archivo_glb');
            $table->string('tipo')->default('glb');
            $table->timestamp('fecha_subida')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modelos_3d');
    }
};
