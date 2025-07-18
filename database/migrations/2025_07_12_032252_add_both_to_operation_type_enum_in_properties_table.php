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
        Schema::table('properties', function (Blueprint $table) {
            // Modificar la columna 'operation_type' para incluir 'both' en el ENUM
            // El método ->change() es crucial aquí para alterar una columna existente.
            $table->enum('operation_type', ['sale', 'rent', 'both'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // En caso de rollback, revertir la columna a su estado anterior (sin 'both')
            $table->enum('operation_type', ['sale', 'rent'])->nullable()->change();
        });
    }
};
