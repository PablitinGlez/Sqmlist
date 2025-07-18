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
            // Añade la columna 'title' después de 'property_type_id'
            // Hazla nullable temporalmente si tienes propiedades existentes
            // que no tendrán título al principio, o si quieres que sea opcional.
            // Si quieres que sea obligatoria, quita ->nullable()
            $table->string('title')->nullable()->after('property_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Elimina la columna 'title' si se revierte la migración
            $table->dropColumn('title');
        });
    }
};
