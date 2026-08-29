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
        Schema::create('planta_tratamiento', function (Blueprint $table) {
            $table->foreignId('planta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tratamiento_id')->constrained()->cascadeOnDelete();
            $table->string('parte_usada')->nullable();
            $table->string('preparacion')->nullable();
            $table->primary(['planta_id', 'tratamiento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planta_tratamiento');
    }
};
