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
            // Añade la columna 'slug' después de 'title'.
            // Es de tipo string, debe ser única para cada propiedad,
            // y la hacemos nullable temporalmente para no tener problemas
            // con las propiedades ya existentes que aún no tienen un slug.
            $table->string('slug')->unique()->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Cuando se revierta la migración, elimina la columna 'slug'.
            $table->dropColumn('slug');
        });
    }
};
