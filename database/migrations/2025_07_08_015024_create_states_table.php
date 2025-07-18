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
        Schema::create('states', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('name')->unique(); // Nombre del estado (ej. "Veracruz de Ignacio de la Llave")
            $table->string('clave', 2)->unique()->nullable(); // Clave del estado (ej. "30" para Veracruz), opcional si no la usas
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
