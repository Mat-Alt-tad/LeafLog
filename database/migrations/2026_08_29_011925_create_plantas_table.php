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
        Schema::create('plantas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cientifico')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('instrucciones')->nullable();
            $table->text('contexto')->nullable();
            $table->text('relato')->nullable();
            $table->string('img_url')->nullable();
            $table->string('img_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_persona_nombre')->nullable();
            $table->string('video_persona_rol')->nullable();
            $table->boolean('video_validado')->default(false);
            $table->foreignId('subtema_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tags')->nullable();
            $table->boolean('verificada')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantas');
    }
};
