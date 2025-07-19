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
        Schema::create('user_favorite_properties', function (Blueprint $table) {
            $table->id(); // Columna de ID primario
            
            // Columna para el ID del usuario
            $table->foreignId('user_id')
                  ->constrained('users') // Restricción de clave foránea a la tabla 'users'
                  ->onDelete('cascade'); // Si un usuario es eliminado, sus favoritos también se eliminan
            
            // Columna para el ID de la propiedad
            $table->foreignId('property_id')
                  ->constrained('properties') // Restricción de clave foránea a la tabla 'properties'
                  ->onDelete('cascade'); // Si una propiedad es eliminada, se elimina de los favoritos de los usuarios

            $table->timestamps(); // Columnas created_at y updated_at

            // Asegurarse de que un usuario no pueda marcar la misma propiedad como favorita más de una vez
            $table->unique(['user_id', 'property_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorite_properties');
    }
};