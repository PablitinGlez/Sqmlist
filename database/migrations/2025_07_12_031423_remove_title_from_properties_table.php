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
            // Eliminar la columna 'title' si existe
            // Usamos dropColumn para quitarla de la tabla
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // En caso de rollback, volvemos a añadir la columna 'title' como nullable
            // para evitar errores si se intenta revertir la migración.
            $table->string('title')->nullable()->after('address_id');
        });
    }
};
