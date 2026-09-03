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
        Schema::table('modelos_3d', function (Blueprint $table) {
            $table->enum('contenido_tipo', ['glb', 'imagenes'])->default('glb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modelos_3d', function (Blueprint $table) {
            $table->dropColumn('contenido_tipo');
        });
    }
};
