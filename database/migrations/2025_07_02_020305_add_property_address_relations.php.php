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
        // ✅ ELIMINADO: El bloque que añadía 'address_id' a la tabla 'properties'.
        // Este campo no es necesario para una relación HasOne/BelongsTo estándar.

        Schema::table('addresses', function (Blueprint $table) {
            // Asegurarse de que la columna 'property_id' no exista antes de añadirla
            // (esto es una buena práctica para idempotencia, aunque migrate:fresh lo limpia)
            if (!Schema::hasColumn('addresses', 'property_id')) {
                $table->foreignId('property_id')->after('id')->constrained()->onDelete('cascade');
                // Nota: 'constrained()' ya asume 'properties' y 'id' por convención.
                // Si necesitas que sea nullable, añade ->nullable() aquí.
                // Por lo general, en una relación 1-1, property_id en addresses NO es nullable
                // si cada dirección SIEMPRE debe pertenecer a una propiedad.
            }

            // ✅ Asegurarse de que el unique constraint se añade correctamente.
            // Si la columna ya existía y no era única, esto podría fallar.
            // Para una relación uno a uno, property_id debe ser único en la tabla 'addresses'.
            $table->unique('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Eliminar la clave foránea primero
            $table->dropForeign(['property_id']);
            // Luego eliminar la columna
            $table->dropColumn('property_id');
        });

        // ✅ ELIMINADO: El bloque que revertía los cambios en 'properties'.
        // Ya no es necesario porque no se añadieron cambios en 'properties' en up().
    }
};
