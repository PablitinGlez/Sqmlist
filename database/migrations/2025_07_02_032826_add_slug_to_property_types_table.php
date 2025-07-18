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
        Schema::table('property_types', function (Blueprint $table) {
            // Añade la columna 'slug' como un string único.
            // La colocamos después de 'name' para un orden lógico.
            // Aseguramos que no exista ya para evitar errores si se ejecuta más de una vez.
            if (!Schema::hasColumn('property_types', 'slug')) {
                $table->string('slug')->unique()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            // En el rollback, eliminamos la columna 'slug'.
            if (Schema::hasColumn('property_types', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
