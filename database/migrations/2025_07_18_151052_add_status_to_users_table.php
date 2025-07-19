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
        Schema::table('users', function (Blueprint $table) {
            // Añadir la columna 'status' después de 'email_verified_at'
            // Será un string (ej. 'active', 'inactive') con un valor por defecto 'active'.
            $table->string('status')->default('active')->after('email_verified_at');
        });

        // Opcional: Si ya tienes usuarios, puedes asegurarte de que todos los existentes
        // tengan el estado 'active' por defecto. Esto es útil si no lo hiciste con `default('active')`
        // o si quieres ser explícito.
        // \DB::table('users')->whereNull('status')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar la columna 'status' si se revierte la migración
            $table->dropColumn('status');
        });
    }
};

