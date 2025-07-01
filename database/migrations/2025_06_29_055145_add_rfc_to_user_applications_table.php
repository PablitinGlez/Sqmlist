<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade la columna 'rfc' a la tabla 'user_applications'.
     * Será un campo de tipo string y opcional (nullable).
     */
    public function up(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
            // Añade la nueva columna 'rfc' después de 'real_estate_company'
            // Será de tipo string, de hasta 255 caracteres y puede ser nulo.
            $table->string('rfc')->nullable()->after('real_estate_company');
        });
    }

    /**
     * Reverse the migrations.
     * Revierte la migración eliminando la columna 'rfc' de la tabla 'user_applications'.
     */
    public function down(): void
    {
        Schema::table('user_applications', function (Blueprint $table) {
            // Elimina la columna 'rfc' si se revierte la migración
            $table->dropColumn('rfc');
        });
    }
};
