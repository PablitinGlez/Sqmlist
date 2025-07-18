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
        Schema::create('feature_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre visible de la sección (ej. "Características", "Amenidades y Servicios")
            $table->string('slug')->unique(); // Slug para uso en código (ej. "caracteristicas_generales")
            $table->integer('order')->default(0); // Orden de visualización en la UI
            $table->boolean('is_active')->default(true); // Para activar/desactivar secciones
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_sections');
    }
};
