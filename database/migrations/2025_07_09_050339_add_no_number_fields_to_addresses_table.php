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
        Schema::table('addresses', function (Blueprint $table) {
            // Añade la columna 'no_external_number' como booleano con valor por defecto false
            $table->boolean('no_external_number')->default(false)->after('interior_number');
            
            // Añade la columna 'no_interior_number' como booleano con valor por defecto false
            $table->boolean('no_interior_number')->default(false)->after('no_external_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Elimina las columnas si se revierte la migración
            $table->dropColumn('no_external_number');
            $table->dropColumn('no_interior_number');
        });
    }
};

