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
        Schema::table('features', function (Blueprint $table) {
            // Añade la columna 'icon' como string, que puede ser nula.
            // La colocamos después de la columna 'name' para mantener un orden lógico.
            $table->string('icon')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            // Elimina la columna 'icon' si se revierte la migración.
            $table->dropColumn('icon');
        });
    }
};
