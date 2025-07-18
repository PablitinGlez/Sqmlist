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
        Schema::create('colonias', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade'); // Clave foránea al municipio
            $table->string('name'); // Nombre de la colonia (ej. "Venta Parada")
            $table->string('postal_code', 5); // Código postal (ej. "94954")
            $table->string('tipo_asentamiento')->nullable(); // Tipo de asentamiento (ej. "Colonia", "Pueblo", "Fraccionamiento")
            $table->string('zona')->nullable(); // Tipo de zona (ej. "Urbano", "Rural")
            $table->timestamps(); // created_at y updated_at

            // Asegurar que la combinación de municipio, nombre de colonia y código postal sea única
            $table->unique(['municipality_id', 'name', 'postal_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colonias');
    }
};
