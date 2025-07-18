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
            // Añadir la columna 'order' como un entero sin signo, que puede ser nulo inicialmente
            // y después lo rellenaremos con un seeder.
            // Es bueno añadirlo después de 'is_active' para mantener un orden lógico.
            $table->integer('order')->unsigned()->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_types', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
