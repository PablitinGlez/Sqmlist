<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones (aplica los cambios a la base de datos).
     *
     * @return void
     */
    public function up(): void
    {
        Log::info('Iniciando migración: remove_additional_references_from_addresses_table (UP)');

        Schema::table('addresses', function (Blueprint $table) {
            // Eliminar la columna 'additional_references' si existe
            if (Schema::hasColumn('addresses', 'additional_references')) {
                $table->dropColumn('additional_references');
                Log::info('Columna additional_references eliminada de addresses.');
            } else {
                Log::warning('La columna additional_references no existe en la tabla addresses, no se eliminó.');
            }
        });

        Log::info('Migración: remove_additional_references_from_addresses_table (UP) completada.');
    }

    /**
     * Revierte las migraciones (deshace los cambios).
     *
     * @return void
     */
    public function down(): void
    {
        Log::info('Iniciando migración: remove_additional_references_from_addresses_table (DOWN)');

        Schema::table('addresses', function (Blueprint $table) {
            // Añadir de nuevo la columna 'additional_references' para el rollback
            // Asumimos que era un campo de texto nullable. Ajusta el tipo si era diferente.
            if (!Schema::hasColumn('addresses', 'additional_references')) {
                $table->text('additional_references')->nullable();
                Log::info('Columna additional_references restaurada en addresses.');
            } else {
                Log::warning('La columna additional_references ya existe en la tabla addresses, no se restauró.');
            }
        });

        Log::info('Migración: remove_additional_references_from_addresses_table (DOWN) completada.');
    }
};
