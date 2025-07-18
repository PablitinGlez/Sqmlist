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
        Schema::table('property_images', function (Blueprint $table) {
            // Eliminar la columna 'filename'
            $table->dropColumn('filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            // En caso de rollback, volvemos a añadir la columna 'filename' como nullable
            // para evitar problemas si se intenta revertir la migración.
            $table->string('filename')->nullable()->after('path');
        });
    }
};
