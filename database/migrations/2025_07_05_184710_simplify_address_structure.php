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
        Log::info('Iniciando migración: consolidate_address_to_single_table_v2 (UP)');

        Schema::table('addresses', function (Blueprint $table) {
            // 1. Eliminar claves foráneas y columnas de IDs (AHORA SÍ DEBERÍAN EXISTIR)
            // Ya que hemos hecho migrate:fresh, estas columnas y FKs deberían estar presentes.

            // Eliminar clave foránea y columna state_id
            $table->dropForeign(['state_id']);
            $table->dropColumn('state_id');
            Log::info('Clave foránea y columna state_id eliminadas de addresses.');

            // Eliminar clave foránea y columna municipality_id
            $table->dropForeign(['municipality_id']);
            $table->dropColumn('municipality_id');
            Log::info('Clave foránea y columna municipality_id eliminadas de addresses.');

            // Eliminar clave foránea y columna neighborhood_id
            $table->dropForeign(['neighborhood_id']);
            $table->dropColumn('neighborhood_id');
            Log::info('Clave foránea y columna neighborhood_id eliminadas de addresses.');

            // 2. Añadir las nuevas columnas de tipo string para los nombres de ubicación
            // Se añaden después de 'google_address_components' para mantener un orden lógico.
            $table->string('state_name')->nullable()->after('google_address_components');
            $table->string('municipality_name')->nullable()->after('state_name');
            $table->string('neighborhood_name')->nullable()->after('municipality_name');
            Log::info('Columnas state_name, municipality_name, neighborhood_name añadidas a addresses.');

            // Opcional: Si 'postal_code' no es ya un string y necesitas cambiar su tipo
            // Si tu 'postal_code' ya es string, comenta esta línea.
            // $table->string('postal_code')->change();
        });

        // 3. Eliminar las tablas de estados, municipios y colonias (AHORA SÍ DEBERÍAN EXISTIR)
        // El orden es importante: las tablas dependientes primero.
        Schema::dropIfExists('neighborhoods');
        Log::info('Tabla neighborhoods eliminada.');
        Schema::dropIfExists('municipalities');
        Log::info('Tabla municipalities eliminada.');
        Schema::dropIfExists('states');
        Log::info('Tabla states eliminada.');

        Log::info('Migración: consolidate_address_to_single_table_v2 (UP) completada.');
    }

    /**
     * Revierte las migraciones (deshace los cambios).
     *
     * @return void
     */
    public function down(): void
    {
        Log::info('Iniciando migración: consolidate_address_to_single_table_v2 (DOWN)');

        Schema::table('addresses', function (Blueprint $table) {
            // Eliminar las columnas de texto añadidas por esta migración
            $table->dropColumn(['state_name', 'municipality_name', 'neighborhood_name']);
            Log::info('Columnas state_name, municipality_name, neighborhood_name eliminadas de addresses.');

            // IMPORTANTE: No se intenta recrear las tablas eliminadas ni sus claves foráneas.
            // Un 'rollback' de esta migración solo revertirá los cambios en la tabla 'addresses'.
            // Para restaurar completamente la estructura anterior, necesitarías un 'php artisan migrate:fresh'.
        });

        Log::info('Migración: consolidate_address_to_single_table_v2 (DOWN) completada.');
    }
};
