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
        Schema::create('property_feature_values', function (Blueprint $table) {
            $table->id();

            // Clave foránea a la tabla 'properties'
            $table->foreignId('property_id')
                ->constrained('properties') // Asegúrate de que 'properties' exista
                ->onDelete('cascade'); // Si se borra una propiedad, se borran sus valores de características

            // Clave foránea a la tabla 'features'
            $table->foreignId('feature_id')
                ->constrained('features')
                ->onDelete('cascade'); // Si se borra una característica, se borran sus valores asociados

            $table->text('value')->nullable(); // El valor real de la característica (siempre como TEXT)

            $table->timestamps();

            // Asegurar que solo haya un valor para una característica específica por propiedad
            $table->unique(['property_id', 'feature_id']);

            // Índices para mejorar el rendimiento de consultas
            $table->index('property_id');
            $table->index('feature_id');
            // Un índice compuesto para búsquedas comunes
            $table->index(['property_id', 'feature_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_feature_values');
    }
};
