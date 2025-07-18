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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->foreignId('state_id')->constrained()->onDelete('cascade'); // Clave foránea al estado
            $table->string('name'); // Nombre del municipio (ej. "Amatlán de los Reyes")
            $table->string('clave', 3)->nullable(); // Clave del municipio (ej. "004" para Amatlán), opcional
            $table->timestamps(); // created_at y updated_at

            // Asegurar que la combinación de estado y municipio sea única
            $table->unique(['state_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
